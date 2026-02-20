<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;

class ChatbotController extends AppController
{
    private const SESSION_KEY_FLOW = 'chatbot.flow';
    private const SESSION_KEY_HISTORY = 'chatbot.history';
    private const HISTORY_LIMIT = 14;

    public function initialize(): void
    {
        parent::initialize();
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
    }

    public function ask()
    {
        try {
            $data = $this->request->getData();

            $question = trim((string)($data['question'] ?? ''));
            if ($question === '') {
                throw new BadRequestException('Pregunta vacía.');
            }

            $context = $data['context'] ?? [];
            if (is_string($context)) $context = json_decode($context, true) ?: [];
            if (!is_array($context)) $context = [];

            $session = $this->request->getSession();

            // =========================
            // Historial: fusiona sesión + lo que llega del front
            // =========================
            $reqHistory = $data['history'] ?? [];
            if (!is_array($reqHistory)) $reqHistory = [];
            $reqHistory = $this->sanitizeHistory($reqHistory);

            $sessHistory = $session->read(self::SESSION_KEY_HISTORY) ?: [];
            if (!is_array($sessHistory)) $sessHistory = [];
            $sessHistory = $this->sanitizeHistory($sessHistory);

            $history = array_merge($sessHistory, $reqHistory);
            $history = $this->sanitizeHistory($history);
            $history = array_slice($history, -self::HISTORY_LIMIT);

            // =========================
            // Flow state
            // =========================
            $flow = $session->read(self::SESSION_KEY_FLOW) ?: $this->defaultFlow();
            if (!empty($history)) $flow['greeted'] = true;

            // =========================
            // 0) SMALL TALK: NO usar LLM
            // =========================
            if ($this->isSmallTalk($question)) {
                $answer = $this->smallTalkReply($question);

                $this->appendHistory($session, $history, $question, $answer);
                return $this->jsonResponse($answer, false, $this->chipsMainModules());
            }

            if ($this->isSupportRequest($question)) {
                $answer = 'Para soporte, abre el formulario de tickets y registra la incidencia con detalle y adjuntos.';
                $this->appendHistory($session, $history, $question, $answer);
                return $this->jsonResponse($answer, true, $this->chipsSupportActions());
            }

            // =========================
            // 0.5) PETICIONES DE EXPLICACIÓN/RESUMEN: MODO DOC (NO UI)
            // =========================
            if ($this->isExplainRequest($question)) {
                // Interpreta intent por si quieres personalizar chips, pero no forces UI
                $intent = $this->interpretUserIntent($question, $context);

                $flow = $this->mergeFlow($flow, $intent);
                $session->write(self::SESSION_KEY_FLOW, $flow);

                $kbRows = $this->retrieveKbSafe($question, $context, 10);

                $plan = "Explica el sistema en forma de resumen práctico y detallado: "
                    . "módulos principales (Usuarios, Escuelas, Roles, Permisos, About Us), "
                    . "qué se puede hacer en cada uno, y 5 tips de uso/seguridad. "
                    . "NO menciones 'Siguiente paso:' ni pidas botones. "
                    . "Cierra con UNA sola pregunta: ¿Qué módulo quieres revisar primero?";

                $chips = $this->chipsMainModules();

                $answer = $this->ollamaReplyFromPlan(
                    $question,
                    $context,
                    $history,
                    $flow,
                    $plan,
                    ['tone' => 'conversacional', 'must_ask' => '(ninguna)'],
                    $kbRows
                );

                $answer = $this->postSanitizeAnswer(trim($answer));
                $answer = $this->stripBlandOpeners($answer);
                if (!empty($flow['greeted'])) {
                    $answer = preg_replace('/^hola[^\n]*\n?/iu', '', $answer);
                    $answer = preg_replace('/^soy el asistente[^\n]*\n?/iu', '', $answer);
                    $answer = trim($answer);
                }
                $this->appendHistory($session, $history, $question, $answer);
                return $this->jsonResponse($answer, false, $chips);
            }

            // =========================
            // 1) Interpretar intent (normal)
            // =========================
            $intent = $this->interpretUserIntent($question, $context);

            // =========================
            // 2) Confirmación si hay pending_switch
            // =========================
            if (!empty($flow['pending_switch'])) {
                $yn = $this->yesNo($question);

                if ($yn === 'yes') {
                    $flow = $this->startNewFlowFromIntent($intent);
                    $flow['pending_switch'] = null;
                } elseif ($yn === 'no') {
                    $flow['pending_switch'] = null;
                    $intent = $this->forceIntentToCurrentFlow($intent, $flow);
                } else {
                    $chips = $this->chipsYesNo();
                    $answer = $this->ollamaReplyFromPlan(
                        $question,
                        $context,
                        $history,
                        $flow,
                        "Necesitas confirmar cambio de tema. Pide sí/no una sola vez.",
                        ['tone' => 'amable', 'must_ask' => '¿Quieres dejar a un lado este tema? (sí/no)'],
                        []
                    );

                    $session->write(self::SESSION_KEY_FLOW, $flow);
                    $answer = $this->postSanitizeAnswer(trim($answer));
                    $answer = $this->stripBlandOpeners($answer);

                    $this->appendHistory($session, $history, $question, $answer);
                    return $this->jsonResponse($answer, false, $chips);
                }
            }

            // =========================
            // 3) Detectar salto de tema (confirmación)
            // =========================
            $switch = $this->detectSwitchOfFlow($flow, $intent);
            if ($switch['shouldConfirm'] === true) {
                $flow['pending_switch'] = [
                    'to_module' => $intent['module'] ?: 'otro tema',
                    'to_task' => $intent['task'] ?: '',
                    'from_module' => $flow['module'],
                    'from_task' => $flow['task'],
                ];
                $session->write(self::SESSION_KEY_FLOW, $flow);

                $chips = $this->chipsYesNo();
                $answer = $this->ollamaReplyFromPlan(
                    $question,
                    $context,
                    $history,
                    $flow,
                    "Detectaste cambio claro de tema. Debes pedir confirmación sí/no UNA sola vez y esperar respuesta.",
                    ['tone' => 'amable', 'must_ask' => '¿Quieres dejar a un lado este tema? (sí/no)'],
                    []
                );

                $answer = $this->postSanitizeAnswer(trim($answer));
                $answer = $this->stripBlandOpeners($answer);

                $this->appendHistory($session, $history, $question, $answer);
                return $this->jsonResponse($answer, false, $chips);
            }

            // =========================
            // 4) Actualizar flow con intent
            // =========================
            $flow = $this->mergeFlow($flow, $intent);
            $session->write(self::SESSION_KEY_FLOW, $flow);

            // =========================
            // 5) KB (opcional)
            // =========================
            $kbRows = $this->retrieveKbSafe($question, $context, 10);

            // =========================
            // 6) Plan y chips
            // =========================
            $plan = $this->makePlanFromFlow($question, $context, $flow);
            $chips = $this->chipsForFlow($flow);

            // =========================
            // 7) Ollama SIEMPRE (modo normal)
            // =========================
            $answer = $this->ollamaReplyFromPlan(
                $question,
                $context,
                $history,
                $flow,
                $plan,
                ['tone' => 'conversacional', 'must_ask' => '(ninguna)'],
                $kbRows
            );

            // =========================
            // 8) Guardrails + NO forzar “Siguiente paso” salvo que el usuario pida guía paso a paso
            // =========================
            $answer = $this->postSanitizeAnswer(trim($answer));
            $answer = $this->stripBlandOpeners($answer);

            // Solo agregamos "Siguiente paso" si el usuario realmente pide guía paso a paso
            if (!$this->isSmallTalk($question) && $this->userWantsStepByStep($question)) {
                if (!preg_match('/\bSiguiente paso:/i', $answer)) {
                    $answer .= "\n\nSiguiente paso: dime qué botón estás usando o qué opción quieres elegir.";
                }
            }

            $escalate = $this->shouldEscalate($answer);

            $this->appendHistory($session, $history, $question, $answer);

            return $this->json([
                'ok' => true,
                'answer' => $answer,
                'chips' => $chips,
                'escalate' => $escalate,
                'support_url' => Router::url('/tickets/add', false),
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'ok' => true,
                'answer' => "Tuve un detalle al responder. ¿Qué estabas intentando hacer exactamente?",
                'chips' => $this->chipsMainModules(),
                'escalate' => true,
                'support_url' => Router::url('/tickets/add', false),
            ]);
        }
    }

    // =========================
    // JSON helpers
    // =========================
    private function jsonResponse(string $answer, bool $escalate, array $chips = [])
    {
        return $this->json([
            'ok' => true,
            'answer' => $answer,
            'chips' => $chips,
            'escalate' => $escalate,
            'support_url' => Router::url('/tickets/add', false),
        ]);
    }

    private function json(array $payload)
    {
        $this->set($payload);
        $this->viewBuilder()->setOption('serialize', array_keys($payload));
        return;
    }

    // =========================
    // Flow state
    // =========================
    private function defaultFlow(): array
    {
        return [
            'module' => '',
            'task' => '',
            'step' => 0,
            'entities' => [],
            'pending_switch' => null,
            'greeted' => false,
        ];
    }

    private function mergeFlow(array $flow, array $intent): array
    {
        $oldTask = (string)($flow['task'] ?? '');
        $oldModule = (string)($flow['module'] ?? '');

        if (!empty($intent['module'])) $flow['module'] = $intent['module'];
        if (!empty($intent['task']))   $flow['task']   = $intent['task'];

        if (!empty($intent['entities']) && is_array($intent['entities'])) {
            $flow['entities'] = array_merge($flow['entities'] ?? [], $intent['entities']);
        }

        $newTask = (string)($flow['task'] ?? '');
        $newModule = (string)($flow['module'] ?? '');

        if ($newModule !== $oldModule || ($newTask !== '' && $newTask !== $oldTask)) {
            $flow['step'] = 0;
        } else {
            $flow['step'] = (int)($flow['step'] ?? 0);
        }

        return $flow;
    }

    private function startNewFlowFromIntent(array $intent): array
    {
        $new = $this->defaultFlow();
        $new['module'] = $intent['module'] ?: 'general';
        $new['task'] = $intent['task'] ?: '';
        $new['entities'] = $intent['entities'] ?? [];
        return $new;
    }

    private function forceIntentToCurrentFlow(array $intent, array $flow): array
    {
        $intent['module'] = $flow['module'] ?: ($intent['module'] ?? '');
        $intent['task']   = $flow['task']   ?: ($intent['task'] ?? '');
        return $intent;
    }

    private function detectSwitchOfFlow(array $flow, array $intent): array
    {
        $curModule = (string)($flow['module'] ?? '');
        $curTask   = (string)($flow['task'] ?? '');

        $newModule = (string)($intent['module'] ?? '');
        $newTask   = (string)($intent['task'] ?? '');

        if ($curModule === '' && $curTask === '') return ['shouldConfirm' => false];
        if ($newModule === '' || $newModule === 'general') return ['shouldConfirm' => false];

        if ($curModule !== '' && $newModule !== '' && $newModule !== $curModule) {
            return ['shouldConfirm' => true];
        }

        $strongTasks = ['delete_user','transfer_school','assign_school','edit_user','create_user'];
        $isStrongCurrent = in_array($curTask, $strongTasks, true);
        $isStrongNew = in_array($newTask, $strongTasks, true);

        if ($curModule === $newModule && $isStrongCurrent && $isStrongNew && $newTask !== '' && $newTask !== $curTask) {
            return ['shouldConfirm' => true];
        }

        return ['shouldConfirm' => false];
    }

    // =========================
    // Intent
    // =========================
    private function interpretUserIntent(string $q, array $context): array
    {
        $t = $this->norm($q);

        $module = $this->guessModuleFromTextOrUrl($t, (string)($context['url'] ?? ''));
        $task = '';

        if ($module === 'users') {
            if ($this->hasAny($t, ['buscar','filtrar','encontrar','localizar'])) $task = 'search_user';
            if ($this->hasAny($t, ['crear','agregar','nuevo'])) $task = 'create_user';
            if ($this->hasAny($t, ['editar','actualizar','cambiar'])) $task = 'edit_user';
            if ($this->hasAny($t, ['eliminar','borrar','quitar'])) $task = 'delete_user';
            if ($this->hasAny($t, ['ver','modal','detalle'])) $task = 'view_user';
        }

        if ($module === 'schools') {
            if ($this->hasAny($t, ['filtro','filtrar','mis filtros','buscar'])) $task = 'filter_schools';
            if ($this->hasAny($t, ['asignar'])) $task = 'assign_school';
            if ($this->hasAny($t, ['transferir','transfer'])) $task = 'transfer_school';
            if ($this->hasAny($t, ['editar','actualizar'])) $task = 'edit_school';
            if ($this->hasAny($t, ['visita','visitas'])) $task = 'visits';
        }

        if ($module === 'seguridad') {
            if ($this->hasAny($t, ['rol','roles'])) $task = 'roles';
            if ($this->hasAny($t, ['permiso','permisos'])) $task = 'permissions';
            if ($this->hasAny($t, ['acceso','no me deja','no aparece','no veo'])) $task = 'access_issue';
        }

        if ($module === 'about_us') {
            if ($this->hasAny($t, ['public','publico','vista'])) $task = 'public_view';
            if ($this->hasAny($t, ['crear','agregar','nuevo'])) $task = 'create_about';
            if ($this->hasAny($t, ['editar','actualizar'])) $task = 'edit_about';
            if ($this->hasAny($t, ['activar','active','visible'])) $task = 'activate_about';
        }

        $entities = [];
        if (preg_match('/\bid\s*(\d+)\b/', $t, $m)) $entities['id'] = (int)$m[1];
        if (preg_match('/usuario\s+([a-z0-9_]+)/i', $q, $m2)) $entities['user_name'] = trim((string)$m2[1]);

        return [
            'module' => $module,
            'task' => $task,
            'entities' => $entities,
            'raw' => $q,
        ];
    }

    private function guessModuleFromTextOrUrl(string $t, string $url): string
    {
        $u = mb_strtolower(trim($url));

        // Prioriza URL si existe
        if ($u !== '') {
            if (str_starts_with($u, '/users')) return 'users';
            if (str_starts_with($u, '/schools')) return 'schools';
            if (str_starts_with($u, '/roles') || str_starts_with($u, '/permissions')) return 'seguridad';
            if (str_starts_with($u, '/about-us')) return 'about_us';
        }

        // Fallback por texto
        if (str_contains($t, 'usuario') || str_contains($t, 'usuarios') || str_contains($t, 'users')) return 'users';
        if (str_contains($t, 'escuela') || str_contains($t, 'escuelas') || str_contains($t, 'schools')) return 'schools';
        if (str_contains($t, 'rol') || str_contains($t, 'roles') || str_contains($t, 'permiso') || str_contains($t, 'permisos')) return 'seguridad';
        if (str_contains($t, 'about') || str_contains($t, 'acerca')) return 'about_us';

        return 'general';
    }

    private function hasAny(string $t, array $words): bool
    {
        foreach ($words as $w) {
            if (str_contains($t, $w)) return true;
        }
        return false;
    }

    private function yesNo(string $q): ?string
    {
        $t = $this->norm($q);
        if (in_array($t, ['si','sí','sip','simon','ok','vale','va','dale','de acuerdo'], true)) return 'yes';
        if (in_array($t, ['no','nel','nop','para nada'], true)) return 'no';
        return null;
    }

    // =========================
    // KB (MySQL)
    // =========================
    private function retrieveKbSafe(string $question, array $context, int $limit = 10): array
    {
        $page = trim((string)($context['page'] ?? ''));
        $url  = trim((string)($context['url'] ?? ''));

        $qq = '%' . mb_substr($question, 0, 120) . '%';
        $qp = $page !== '' ? ('%' . mb_substr($page, 0, 120) . '%') : '%';
        $qu = $url  !== '' ? ('%' . mb_substr($url,  0, 120) . '%') : '%';

        $moduleGuess = $this->guessModuleFromUrl($url);

        $conn = $this->fetchTable('KbArticles')->getConnection();

        $sql = "
            SELECT
                a.id AS article_id,
                a.title,
                a.module,
                COALESCE(c.chunk_text, a.body) AS text
            FROM kb_articles a
            LEFT JOIN kb_chunks c ON c.article_id = a.id
            WHERE a.is_active = 1
              AND (
                    a.title LIKE :qq OR a.body LIKE :qq OR c.chunk_text LIKE :qq
                 OR a.title LIKE :qp OR a.body LIKE :qp OR c.chunk_text LIKE :qp
                 OR a.title LIKE :qu OR a.body LIKE :qu OR c.chunk_text LIKE :qu
              )
        ";

        $params = ['qq' => $qq, 'qp' => $qp, 'qu' => $qu];

        if ($moduleGuess !== '') {
            $sql .= " AND (a.module = :m OR a.module IS NULL OR a.module = '') ";
            $params['m'] = $moduleGuess;
        }

        $sql .= " ORDER BY a.id DESC, c.id ASC LIMIT {$limit} ";

        return $conn->execute($sql, $params)->fetchAll('assoc') ?: [];
    }

    private function guessModuleFromUrl(string $url): string
    {
        $u = mb_strtolower(trim($url));
        if ($u === '') return '';
        if (str_starts_with($u, '/users')) return 'users';
        if (str_starts_with($u, '/schools')) return 'schools';
        if (str_starts_with($u, '/roles') || str_starts_with($u, '/permissions')) return 'seguridad';
        if (str_starts_with($u, '/about-us')) return 'about_us';
        return '';
    }

    // =========================
    // Plans
    // =========================
    private function makePlanFromFlow(string $question, array $context, array $flow): string
    {
        $module = $flow['module'] ?? 'general';
        $task   = $flow['task'] ?? '';
        $entities = $flow['entities'] ?? [];

        if ($module === 'users' && $task === '') {
            return "El usuario está en el tema Usuarios. Ofrece opciones (buscar, crear, editar, eliminar, ver). "
                . "Cierra con una sola pregunta: ¿qué quieres hacer en Usuarios?";
        }
        if ($module === 'users' && $task === 'delete_user') {
            $id = $entities['id'] ?? null;
            $name = $entities['user_name'] ?? null;
            return "Tema: Usuarios / Eliminar. Guía paso a paso en UI, confirma (ID/nombre) si falta. "
                . "Cierra preguntando si ya lo ve en tabla o necesita encontrarlo primero.";
        }
        if ($module === 'users' && $task === 'search_user') {
            return "Tema: Usuarios / Buscar. Explica filtros y botón Buscar. "
                . "Cierra preguntando si lo busca por email o nombre.";
        }

        if ($module === 'schools' && $task === '') {
            return "Tema: Escuelas. Ofrece opciones (mis filtros, asignar, transferir, editar, visitas). "
                . "Cierra con una sola pregunta.";
        }
        if ($module === 'schools' && $task === 'filter_schools') {
            return "Tema: Escuelas / Mis filtros. Explica qué se puede filtrar y cómo aplicar. "
                . "Cierra preguntando qué criterio quiere usar primero.";
        }
        if ($module === 'schools' && $task === 'visits') {
            return "Tema: Escuelas / Visitas. Explica agendar/completar visita y dónde se ve estado. "
                . "Cierra preguntando si quiere agendar o completar.";
        }

        if ($module === 'seguridad' && $task === '') {
            return "Tema: Roles/Permisos. Ofrece opciones (roles, permisos, dar acceso). "
                . "Cierra con una pregunta.";
        }
        if ($module === 'about_us' && $task === '') {
            return "Tema: About Us. Ofrece opciones (crear, editar, activar, vista pública). "
                . "Cierra con una pregunta.";
        }

        return "Ayuda al usuario con su objetivo. Si falta info, pide SOLO 1 dato. "
            . "Si estás guiando UI, termina con una indicación concreta del siguiente clic (sin repetir muletillas).";
    }

    // =========================
    // Chips
    // =========================
    private function chipsYesNo(): array
    {
        return [
            ['label' => 'Sí', 'value' => 'sí'],
            ['label' => 'No', 'value' => 'no'],
        ];
    }

    private function chipsMainModules(): array
    {
        return [
            ['label' => 'Usuarios', 'value' => 'Usuarios'],
            ['label' => 'Escuelas', 'value' => 'Escuelas'],
            ['label' => 'Soporte', 'value' => 'Soporte'],
            ['label' => 'Roles', 'value' => 'Roles'],
            ['label' => 'Permisos', 'value' => 'Permisos'],
            ['label' => 'About Us', 'value' => 'About Us'],
        ];
    }

    private function chipsSupportActions(): array
    {
        return [
            ['label' => 'Abrir soporte', 'value' => 'Abrir formulario de soporte'],
            ['label' => 'Nuevo ticket', 'value' => 'Crear ticket de error'],
            ['label' => 'Ver tickets', 'value' => 'Ver mis tickets'],
        ];
    }

    private function chipsForFlow(array $flow): array
    {
        $module = $flow['module'] ?? 'general';
        $task   = $flow['task'] ?? '';

        if (!empty($flow['pending_switch'])) return $this->chipsYesNo();

        if ($module === 'users' && $task === '') {
            return [
                ['label' => 'Buscar', 'value' => 'Buscar usuario'],
                ['label' => 'Crear', 'value' => 'Crear usuario'],
                ['label' => 'Editar', 'value' => 'Editar usuario'],
                ['label' => 'Eliminar', 'value' => 'Eliminar usuario'],
                ['label' => 'Ver (modal)', 'value' => 'Ver usuario (modal)'],
            ];
        }

        if ($module === 'schools' && $task === '') {
            return [
                ['label' => 'Mis filtros', 'value' => 'Mis filtros escuelas'],
                ['label' => 'Visitas', 'value' => 'Visitas'],
                ['label' => 'Asignar', 'value' => 'Asignar escuela'],
                ['label' => 'Transferir', 'value' => 'Transferir escuela'],
                ['label' => 'Editar', 'value' => 'Editar escuela'],
            ];
        }

        if ($module === 'seguridad' && $task === '') {
            return [
                ['label' => 'Roles', 'value' => 'Roles'],
                ['label' => 'Permisos', 'value' => 'Permisos'],
                ['label' => 'Dar acceso', 'value' => 'No veo una pantalla (acceso)'],
            ];
        }

        if ($module === 'about_us' && $task === '') {
            return [
                ['label' => 'Crear', 'value' => 'Crear About Us'],
                ['label' => 'Editar', 'value' => 'Editar About Us'],
                ['label' => 'Activar', 'value' => 'Activar About Us'],
                ['label' => 'Vista pública', 'value' => 'Ver vista pública About Us'],
            ];
        }

        return [];
    }

    // =========================
    // Prompt + Ollama
    // =========================
    private function ollamaReplyFromPlan(
        string $question,
        array $context,
        array $history,
        array $flow,
        string $plan,
        array $options,
        array $kbRows
    ): string {
        $prompt = $this->buildPrompt($question, $context, $history, $flow, $plan, $options, $kbRows);
        return $this->ollamaGenerateSafe($prompt, 'llama3:latest');
    }

    private function buildPrompt(
        string $question,
        array $context,
        array $history,
        array $flow,
        string $plan,
        array $options,
        array $kbRows
    ): string {
        $page = (string)($context['page'] ?? '');
        $url  = (string)($context['url'] ?? '');
        $greeted = !empty($flow['greeted']) ? 'sí' : 'no';
    
        $module = (string)($flow['module'] ?? '');
        $task   = (string)($flow['task'] ?? '');
        $entities = $flow['entities'] ?? [];
        if (!is_array($entities)) $entities = [];
    
        $histText = $this->historyToText($history, 12);
    
        $kbText = '';
        foreach ($kbRows as $r) {
            $txt = trim((string)($r['text'] ?? ''));
            if ($txt !== '') $kbText .= "- {$txt}\n";
        }
    
        $entitiesText = '';
        foreach ($entities as $k => $v) {
            $k = trim((string)$k);
            $v = is_scalar($v) ? (string)$v : json_encode($v, JSON_UNESCAPED_UNICODE);
            if ($k !== '' && trim((string)$v) !== '') $entitiesText .= "- {$k}: {$v}\n";
        }
    
        $mustAsk = trim((string)($options['must_ask'] ?? ''));
        if ($mustAsk === '') $mustAsk = '(ninguna)';
    
        $tone = (string)($options['tone'] ?? 'conversacional');
    
        return <<<PROMPT
    Eres un asistente de ayuda INTERNO del sistema web "Mapa Distribuidores Montenegro".
    Respondes SIEMPRE en español con tono {$tone}, como soporte profesional y directo.
    
    Estado de saludo previo: {$greeted}
    
    REGLAS CRÍTICAS
    - Si Estado de saludo previo = "sí", NO vuelvas a saludar.
    - Si Estado de saludo previo = "sí", NO te presentes.
    - NO uses frases como:
      "Hola. Soy el asistente..."
      "Entiendo que estás en..."
      "¡Hola! Entiendo que..."
    - Ve directo a la respuesta.
    - No afirmes que ejecutas acciones por el usuario.
    - Máximo 1 pregunta por respuesta.
    - Si el usuario no pidió paso a paso, no menciones botones.
    
    CONTEXTO (no repetir literal)
    Pantalla: {$page}
    URL: {$url}
    Flujo: {$module} / {$task}
    Entidades:
    {$entitiesText}
    
    CONVERSACIÓN RECIENTE
    {$histText}
    
    KB
    {$kbText}
    
    PLAN
    {$plan}
    
    MENSAJE DEL USUARIO
    {$question}
    
    PREGUNTA OBLIGATORIA
    {$mustAsk}
    PROMPT;
    }
    

    private function ollamaGenerateSafe(string $prompt, string $model): string
    {
        try {
            return $this->ollamaGenerate($prompt, $model);
        } catch (\Throwable $e) {
            return "Va 🙂 ¿Qué necesitas hacer (buscar, crear, editar, eliminar, asignar, transferir)?";
        }
    }

    private function ollamaGenerate(string $prompt, string $model): string
    {
        $url = 'http://localhost:11434/api/generate';

        $payload = json_encode([
            'model' => $model,
            'prompt' => $prompt,
            'stream' => false,
        ], JSON_UNESCAPED_UNICODE);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 45,
        ]);

        $resp = curl_exec($ch);
        if ($resp === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException($err ?: 'curl_exec failed');
        }

        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code < 200 || $code >= 300) {
            throw new \RuntimeException("HTTP {$code}");
        }

        $json = json_decode($resp, true);
        $text = trim((string)($json['response'] ?? ''));
        if ($text === '') throw new \RuntimeException('Respuesta vacía de Ollama');
        return $text;
    }

    // =========================
    // History (session)
    // =========================
    private function appendHistory($session, array $history, string $userText, string $assistantText): void
    {
        $history[] = ['role' => 'user', 'content' => $userText];
        $history[] = ['role' => 'assistant', 'content' => $assistantText];

        $history = $this->sanitizeHistory($history);
        $history = array_slice($history, -self::HISTORY_LIMIT);

        $session->write(self::SESSION_KEY_HISTORY, $history);
    }

    private function sanitizeHistory(array $history): array
    {
        $out = [];
        foreach ($history as $m) {
            $role = (string)($m['role'] ?? '');
            $content = trim((string)($m['content'] ?? ''));
            if ($role !== 'user' && $role !== 'assistant') continue;
            if ($content === '') continue;
            $out[] = ['role' => $role, 'content' => $content];
        }
        return $out;
    }

    private function historyToText(array $history, int $max = 12): string
    {
        if (!$history) return "(sin historial)";
        $history = array_slice($history, -$max);

        $lines = [];
        foreach ($history as $m) {
            $role = ($m['role'] ?? '') === 'user' ? 'Usuario' : 'Asistente';
            $content = trim((string)($m['content'] ?? ''));
            if ($content === '') continue;
            if (mb_strlen($content) > 280) $content = mb_substr($content, 0, 280) . '…';
            $lines[] = "{$role}: {$content}";
        }
        return $lines ? implode("\n", $lines) : "(sin historial)";
    }

    // =========================
    // Small talk + Explain mode + Step-by-step detection
    // =========================
    private function isSmallTalk(string $q): bool
    {
        $t = mb_strtolower(trim($q));
        $t = preg_replace('/\s+/', ' ', $t);

        $patterns = [
            'hola','buenas','buenos dias','buenas tardes','buenas noches',
            'como estas','como andas','que tal','todo bien','que onda',
            'gracias','muchas gracias','solo pasaba a saludar','pasaba a saludar'
        ];

        foreach ($patterns as $p) {
            if ($t === $p || str_contains($t, $p)) return true;
        }

        if (in_array($t, ['ok','vale','va','dale'], true)) return true;

        return false;
    }

    private function smallTalkReply(string $q): string
    {
        $t = $this->norm($q);

        if (str_contains($t, 'como estas') || str_contains($t, 'que tal') || str_contains($t, 'todo bien')) {
            return "¡Bien! 🙂 ¿Tú qué tal? Si quieres, dime en qué parte del sistema andas (Usuarios, Escuelas, Roles/Permisos o About Us) y te guío.";
        }

        if (str_contains($t, 'gracias')) {
            return "¡De nada! 🙌 ¿Te ayudo con algo del sistema?";
        }

        if (str_contains($t, 'saludar') || str_contains($t, 'hola') || str_contains($t, 'buenas')) {
            return "¡Hola! 👋 ¿Qué necesitas hacer dentro del sistema?";
        }

        return "Va 🙂 ¿Qué necesitas hacer en el sistema?";
    }

    private function isExplainRequest(string $q): bool
    {
        $t = $this->norm($q);
        $keys = [
            'resumen','que es','que hace','antes de usar','como funciona','explicame',
            'explica','que debo saber','manual','guia general','general'
        ];
        foreach ($keys as $k) {
            if (str_contains($t, $k)) return true;
        }
        return false;
    }

    private function userWantsStepByStep(string $q): bool
    {
        $t = $this->norm($q);
        $keys = [
            'como','donde','boton','clic','click','paso','paso a paso','no encuentro',
            'no veo','me aparece','error','pantalla','filtro','filtrar'
        ];

        foreach ($keys as $k) {
            if (str_contains($t, $k)) return true;
        }
        return false;
    }

    private function isSupportRequest(string $q): bool
    {
        $t = $this->norm($q);
        $keys = [
            'soporte',
            'support',
            'ticket',
            'tickets',
            'incidencia',
            'reportar error',
            'levantar error',
            'ayuda tecnica',
            'mesa de ayuda',
        ];

        foreach ($keys as $k) {
            if (str_contains($t, $k)) {
                return true;
            }
        }

        return false;
    }

    // =========================
    // Guardrails
    // =========================
    private function postSanitizeAnswer(string $text): string
    {
        $bad = [
            'ya lo eliminé' => 'cuando lo elimines',
            'yo lo elimino' => 'tú lo puedes eliminar',
            'voy a eliminar' => 'puedes eliminar',
            'puedo navegar' => 'te puedo indicar dónde dar clic',
            'puedo entrar al sistema' => 'te puedo guiar a entrar al módulo',
        ];

        foreach ($bad as $from => $to) {
            $text = str_ireplace($from, $to, $text);
        }

        return $text;
    }

    private function stripBlandOpeners(string $text): string
    {
        $patterns = [
            '/^hola[^\n]*\n?/iu',
            '/^¡hola![^\n]*\n?/iu',
            '/^hola\. soy el asistente[^\n]*\n?/iu',
            '/^soy el asistente[^\n]*\n?/iu',
            '/^entiendo que[^\n]*\n?/iu',
            '/^¡hola! entiendo que[^\n]*\n?/iu',
        ];
    
        foreach ($patterns as $p) {
            $text = preg_replace($p, '', trim($text));
        }
    
        return trim($text);
    }
    

    private function shouldEscalate(string $answer): bool
    {
        $a = mb_strtolower($answer);
        return str_contains($a, 'contactar soporte')
            || str_contains($a, 'soporte')
            || str_contains($a, 'mensaje de error')
            || str_contains($a, 'pega el mensaje');
    }

    // =========================
    // Normalización
    // =========================
    private function norm(string $q): string
    {
        $t = mb_strtolower(trim($q), 'UTF-8');
        $map = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ü' => 'u', 'ñ' => 'n'
        ];
        $t = strtr($t, $map);
        $t = preg_replace('/\s+/', ' ', $t);
        return $t ?: '';
    }
}
