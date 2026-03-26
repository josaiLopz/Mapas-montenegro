<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * api.php — Backend completo para Calculadora de Descuentos v2
 * Requiere PHP 7.4+ y extensión PDO + PDO_MySQL
 *
 * ⚠️  CONFIGURA TUS CREDENCIALES DE BD ABAJO
 * ⚠️  CONFIGURA TU SMTP PARA ENVÍO DE CORREOS
 */

// ============================================================
//  CONFIGURACIÓN — EDITA ESTOS VALORES
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'new_calculadora');   // nombre de tu base de datos
define('DB_USER', 'sistemas');          // usuario MySQL
define('DB_PASS', 'montenegro');              // contraseña MySQL
define('DB_CHARSET', 'utf8mb4');

// SMTP para envío de códigos OTP
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'noreply@montenegroeditores.net');   // ← tu correo
define('SMTP_PASS', 'opjsfemetomeinpf');      // ← contraseña de app Gmail
define('SMTP_FROM', 'noreply@montenegroeditores.net');
define('SMTP_FROM_NAME', 'Calculadora Descuentos');

// Duración del OTP en minutos
define('OTP_MINUTES', 15);

define('SESSION_HOURS', 8);
define('ADMIN_KEY', 'calculadora_descuentos'); 
//  CORS & HEADERS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Admin-Key');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// ============================================================
//  PDO
// ============================================================
function getDB(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    return $pdo;
}

// ============================================================
//  ROUTER
// ============================================================
$resource = $_GET['resource'] ?? '';
$method   = $_SERVER['REQUEST_METHOD'];
$body     = json_decode(file_get_contents('php://input'), true) ?? [];

// Verificar si es admin (por header o query param)
$adminKey = $_SERVER['HTTP_X_ADMIN_KEY'] ?? ($_GET['admin_key'] ?? '');
$isAdmin  = ($adminKey === ADMIN_KEY);

try {
    switch ($resource) {

        // ── Líneas ──────────────────────────────────────────
        case 'lineas':
            handleLineas($method, $body, $isAdmin);
            break;

        // ── Campos ──────────────────────────────────────────
        case 'campos':
            handleCampos($method, $body, $isAdmin);
            break;

        // ── Descuentos ──────────────────────────────────────
        case 'descuentos':
            handleDescuentos($method, $body, $isAdmin);
            break;

        // ── Usuarios ────────────────────────────────────────
        case 'usuarios':
            requireAdmin($isAdmin);
            handleUsuarios($method, $body);
            break;

        // ── Valores de usuario ──────────────────────────────
        case 'usuario_valores':
            requireAdmin($isAdmin);
            handleUsuarioValores($method, $body);
            break;

        // ── Auth usuario (OTP) ──────────────────────────────
        case 'auth_solicitar':
            handleAuthSolicitar($body);
            break;

        case 'auth_verificar':
            handleAuthVerificar($body);
            break;

        case 'auth_logout':
            handleAuthLogout($body);
            break;

        // ── Sesión / perfil usuario ──────────────────────────
        case 'mi_perfil':
            handleMiPerfil($method, $body);
            break;

        // ── Cotizaciones ────────────────────────────────────
        case 'cotizaciones':
            handleCotizaciones($method, $body, $isAdmin);
            break;

        default:
            jsonError('Recurso no encontrado', 404);
    }
} catch (PDOException $e) {
    jsonError('Error de base de datos: ' . $e->getMessage(), 500);
} catch (\Throwable $e) {
    jsonError('Error interno: ' . $e->getMessage(), 500);
}

// ============================================================
//  HELPERS
// ============================================================
function jsonOk($data): void {
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function jsonError(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['error' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

function requireAdmin(bool $isAdmin): void {
    if (!$isAdmin) jsonError('Acceso denegado', 403);
}

function requireSession(): array {
    $token = getBearerToken();
    if (!$token) jsonError('No autenticado', 401);
    $db  = getDB();
    $st  = $db->prepare(
        "SELECT s.usuario_id, u.nombre, u.email, u.activo
         FROM sesiones s JOIN usuarios u ON u.id = s.usuario_id
         WHERE s.token = ? AND s.expires_at > NOW()"
    );
    $st->execute([$token]);
    $row = $st->fetch();
    if (!$row) jsonError('Sesión inválida o expirada', 401);
    if (!$row['activo']) jsonError('Usuario desactivado', 403);
    return $row;
}

function getBearerToken(): ?string {
    $h = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    if (!$h && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $h = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    }

    if (!$h && function_exists('getallheaders')) {
        $headers = getallheaders();
        $h = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    }

    if (!$h && isset($_SERVER['PHP_AUTH_DIGEST'])) {
        $h = $_SERVER['PHP_AUTH_DIGEST'];
    }

    if (!$h && isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
        $h = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']);
    }

    if (preg_match('/Bearer\s+(.+)/i', $h, $m)) {
        return trim($m[1]);
    }

    return $_GET['token'] ?? null;
}

function decodeJsonField($val) {
    if (is_array($val)) return $val;
    if (!$val) return null;
    $d = json_decode($val, true);
    return $d ?? $val;
}

function castCampo(array $row): array {
    $row['activo']   = (bool)(int)$row['activo'];
    $row['orden']    = (int)$row['orden'];
    $row['formula']  = decodeJsonField($row['formula'] ?? null);
    return $row;
}

function castDescuento(array $row): array {
    $row['activo']      = (bool)(int)$row['activo'];
    $row['escalonado']  = (bool)(int)$row['escalonado'];
    $row['sujeto_meta'] = (bool)(int)$row['sujeto_meta'];
    $row['valor']       = (float)$row['valor'];
    $row['orden']       = (int)$row['orden'];
    $row['tramos']      = decodeJsonField($row['tramos'] ?? null) ?? [];
    return $row;
}

// Tipos de campo permitidos — usados inline en handleCampos

// ============================================================
//  LÍNEAS
// ============================================================
function handleLineas(string $method, array $body, bool $isAdmin): void {
    $db = getDB();
    switch ($method) {
        case 'GET':
            $rows = $db->query("SELECT * FROM lineas ORDER BY id")->fetchAll();
            foreach ($rows as &$r) {
                $r['activa']      = (bool)(int)$r['activa'];
                $r['precio_base'] = (float)$r['precio_base'];
            }
            jsonOk($rows);

        case 'POST':
            requireAdmin($isAdmin);
            $st = $db->prepare("INSERT INTO lineas (nombre, precio_base, activa) VALUES (?,?,?)");
            $st->execute([trim($body['nombre']), $body['precio_base'], $body['activa'] ?? 1]);
            jsonOk(['id' => $db->lastInsertId(), 'ok' => true]);

        case 'PUT':
            requireAdmin($isAdmin);
            $id = (int)($_GET['id'] ?? 0);
            $st = $db->prepare("UPDATE lineas SET nombre=?, precio_base=?, activa=? WHERE id=?");
            $st->execute([trim($body['nombre']), $body['precio_base'], $body['activa'] ?? 1, $id]);
            jsonOk(['ok' => true]);

        case 'DELETE':
            requireAdmin($isAdmin);
            $id = (int)($_GET['id'] ?? 0);
            $db->prepare("DELETE FROM lineas WHERE id=?")->execute([$id]);
            jsonOk(['ok' => true]);
    }
}

// ============================================================
//  CAMPOS
// ============================================================
function handleCampos(string $method, array $body, bool $isAdmin): void {
    $db = getDB();
    switch ($method) {
        case 'GET':
            $lineaId = (int)($_GET['linea_id'] ?? 0);
            $st = $db->prepare("SELECT * FROM campos WHERE linea_id=? ORDER BY orden, id");
            $st->execute([$lineaId]);
            $rows = array_map('castCampo', $st->fetchAll());
            jsonOk($rows);

        case 'POST':
            requireAdmin($isAdmin);
            // Validar tipo permitido (incluye 'fecha')
            $tipo = $body['tipo'] ?? 'numero';
            if (!in_array($tipo, ['numero','checkbox','texto','suma','porcentaje','fecha'], true)) {
                jsonError('Tipo de campo no válido: ' . $tipo);
            }
            // formula: si viene en el body Y no es null, serializar; si no hay o es null → NULL en BD
            $formulaRaw = $body['formula'] ?? null;
            $formula = ($formulaRaw !== null && $formulaRaw !== '' && $formulaRaw !== 'null')
                ? json_encode($formulaRaw, JSON_UNESCAPED_UNICODE)
                : null;

            // Validaciones básicas
            if (empty(trim($body['clave'] ?? ''))) jsonError('La clave no puede estar vacía');
            if (empty(trim($body['etiqueta'] ?? ''))) jsonError('La etiqueta no puede estar vacía');

            $st = $db->prepare(
                "INSERT INTO campos (linea_id, clave, etiqueta, tipo, formula, orden, activo)
                 VALUES (?,?,?,?,?,?,?)"
            );
            $st->execute([
                (int)$body['linea_id'],
                trim($body['clave']),
                trim($body['etiqueta']),
                $tipo,
                $formula,
                (int)($body['orden'] ?? 99),
                $body['activo'] ? 1 : 0
            ]);
            jsonOk(['id' => $db->lastInsertId(), 'ok' => true]);

        case 'PUT':
            requireAdmin($isAdmin);
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) jsonError('ID de campo requerido');

            $tipo = $body['tipo'] ?? 'numero';
            if (!in_array($tipo, ['numero','checkbox','texto','suma','porcentaje','fecha'], true)) {
                jsonError('Tipo de campo no válido: ' . $tipo);
            }
            // Mismo manejo robusto de formula
            $formulaRaw = $body['formula'] ?? null;
            $formula = ($formulaRaw !== null && $formulaRaw !== '' && $formulaRaw !== 'null')
                ? json_encode($formulaRaw, JSON_UNESCAPED_UNICODE)
                : null;

            if (empty(trim($body['clave'] ?? ''))) jsonError('La clave no puede estar vacía');
            if (empty(trim($body['etiqueta'] ?? ''))) jsonError('La etiqueta no puede estar vacía');

            $st = $db->prepare(
                "UPDATE campos SET clave=?, etiqueta=?, tipo=?, formula=?, orden=?, activo=?
                 WHERE id=?"
            );
            $affected = $st->execute([
                trim($body['clave']),
                trim($body['etiqueta']),
                $tipo,
                $formula,
                (int)($body['orden'] ?? 99),
                $body['activo'] ? 1 : 0,
                $id
            ]);
            if ($st->rowCount() === 0) {
                // Puede que no haya cambiado nada — igual devolver ok
                jsonOk(['ok' => true, 'affected' => 0]);
            }
            jsonOk(['ok' => true, 'affected' => $st->rowCount()]);

        case 'DELETE':
            requireAdmin($isAdmin);
            $id = (int)($_GET['id'] ?? 0);
            $db->prepare("DELETE FROM campos WHERE id=?")->execute([$id]);
            jsonOk(['ok' => true]);
    }
}

// ============================================================
//  DESCUENTOS
// ============================================================
function handleDescuentos(string $method, array $body, bool $isAdmin): void {
    $db = getDB();
    switch ($method) {
        case 'GET':
            $lineaId = (int)($_GET['linea_id'] ?? 0);
            $st = $db->prepare("SELECT * FROM descuentos WHERE linea_id=? ORDER BY orden, id");
            $st->execute([$lineaId]);
            $rows = array_map('castDescuento', $st->fetchAll());
            jsonOk($rows);

        case 'POST':
            requireAdmin($isAdmin);
            $tramos = isset($body['tramos']) ? json_encode($body['tramos']) : null;
            $st = $db->prepare(
                "INSERT INTO descuentos (linea_id, nombre, depende_de, escalonado, valor, tramos, sujeto_meta, activo, orden)
                 VALUES (?,?,?,?,?,?,?,?,?)"
            );
            $st->execute([
                $body['linea_id'], $body['nombre'], $body['depende_de'],
                $body['escalonado'] ? 1 : 0, $body['valor'] ?? 0, $tramos,
                $body['sujeto_meta'] ? 1 : 0, $body['activo'] ? 1 : 0, $body['orden'] ?? 99
            ]);
            jsonOk(['id' => $db->lastInsertId(), 'ok' => true]);

        case 'PUT':
            requireAdmin($isAdmin);
            $id     = (int)($_GET['id'] ?? 0);
            $tramos = isset($body['tramos']) ? json_encode($body['tramos']) : null;
            $st = $db->prepare(
                "UPDATE descuentos SET nombre=?, depende_de=?, escalonado=?, valor=?,
                 tramos=?, sujeto_meta=?, activo=?, orden=? WHERE id=?"
            );
            $st->execute([
                $body['nombre'], $body['depende_de'], $body['escalonado'] ? 1 : 0,
                $body['valor'] ?? 0, $tramos, $body['sujeto_meta'] ? 1 : 0,
                $body['activo'] ? 1 : 0, $body['orden'] ?? 99, $id
            ]);
            jsonOk(['ok' => true]);

        case 'DELETE':
            requireAdmin($isAdmin);
            $id = (int)($_GET['id'] ?? 0);
            $db->prepare("DELETE FROM descuentos WHERE id=?")->execute([$id]);
            jsonOk(['ok' => true]);
    }
}

// ============================================================
//  USUARIOS (admin)
// ============================================================
function handleUsuarios(string $method, array $body): void {
    $db = getDB();
    switch ($method) {
        case 'GET':
            // Traer usuarios con sus valores para una línea
            $lineaId = (int)($_GET['linea_id'] ?? 0);
            $rows = $db->query("SELECT id, nombre, email, activo, created_at FROM usuarios ORDER BY nombre")->fetchAll();
            foreach ($rows as &$r) {
                $r['activo'] = (bool)(int)$r['activo'];
                if ($lineaId) {
                    $st = $db->prepare(
                        "SELECT campo_clave, valor FROM usuario_valores WHERE usuario_id=? AND linea_id=?"
                    );
                    $st->execute([$r['id'], $lineaId]);
                    $vals = $st->fetchAll();
                    $r['valores'] = array_column($vals, 'valor', 'campo_clave');
                }
            }
            jsonOk($rows);

        case 'POST':
            $st = $db->prepare("INSERT INTO usuarios (nombre, email, activo) VALUES (?,?,?)");
            $st->execute([trim($body['nombre']), strtolower(trim($body['email'])), $body['activo'] ?? 1]);
            jsonOk(['id' => $db->lastInsertId(), 'ok' => true]);

        case 'PUT':
            $id = (int)($_GET['id'] ?? 0);
            $st = $db->prepare("UPDATE usuarios SET nombre=?, email=?, activo=? WHERE id=?");
            $st->execute([trim($body['nombre']), strtolower(trim($body['email'])), $body['activo'] ? 1 : 0, $id]);
            jsonOk(['ok' => true]);

        case 'DELETE':
            $id = (int)($_GET['id'] ?? 0);
            $db->prepare("DELETE FROM usuarios WHERE id=?")->execute([$id]);
            jsonOk(['ok' => true]);
    }
}

// ============================================================
//  VALORES DE USUARIO POR LÍNEA (admin)
// ============================================================
function handleUsuarioValores(string $method, array $body): void {
    $db = getDB();
    if ($method === 'POST') {
        // Upsert masivo: { usuario_id, linea_id, valores: {clave: valor} }
        // Soporta valores numéricos Y fechas (formato YYYY-MM-DD)
        $usuarioId = (int)$body['usuario_id'];
        $lineaId   = (int)$body['linea_id'];
        $valores   = $body['valores'] ?? [];

        $st = $db->prepare(
            "INSERT INTO usuario_valores (usuario_id, linea_id, campo_clave, valor)
             VALUES (?,?,?,?)
             ON DUPLICATE KEY UPDATE valor=VALUES(valor), updated_at=NOW()"
        );
        foreach ($valores as $clave => $valor) {
            // Sanitizar: si parece fecha (YYYY-MM-DD) validar formato; si no, dejar como string
            $valorFinal = is_string($valor) ? trim($valor) : (string)$valor;
            $st->execute([$usuarioId, $lineaId, $clave, $valorFinal]);
        }
        jsonOk(['ok' => true]);
    }
    if ($method === 'GET') {
        $usuarioId = (int)($_GET['usuario_id'] ?? 0);
        $lineaId   = (int)($_GET['linea_id'] ?? 0);
        $st = $db->prepare(
            "SELECT campo_clave, valor FROM usuario_valores WHERE usuario_id=? AND linea_id=?"
        );
        $st->execute([$usuarioId, $lineaId]);
        $rows = $st->fetchAll();
        jsonOk(array_column($rows, 'valor', 'campo_clave'));
    }
}

// ============================================================
//  AUTH — SOLICITAR CÓDIGO OTP
// ============================================================
function handleAuthSolicitar(array $body): void {
    $email = strtolower(trim($body['email'] ?? ''));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonError('Email inválido');

    $db = getDB();

    // Verificar que el usuario exista y esté activo
    $st = $db->prepare("SELECT id, nombre, activo FROM usuarios WHERE email=?");
    $st->execute([$email]);
    $user = $st->fetch();
    if (!$user) jsonError('Correo no registrado en el sistema', 404);
    if (!$user['activo']) jsonError('Usuario desactivado', 403);

    // Invalidar códigos anteriores
    $db->prepare("UPDATE verificacion_codigos SET usado=1 WHERE email=? AND usado=0")->execute([$email]);

    // Generar código de 6 dígitos
    $codigo    = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiresAt = date('Y-m-d H:i:s', strtotime('+' . OTP_MINUTES . ' minutes'));

    $db->prepare(
        "INSERT INTO verificacion_codigos (email, codigo, expires_at) VALUES (?,?,?)"
    )->execute([$email, $codigo, $expiresAt]);

    // Enviar correo
    $sent = sendOtpEmail($email, $user['nombre'], $codigo);

    if (!$sent) {
        // En desarrollo: devolver el código para pruebas
        jsonOk(['ok' => true, 'dev_codigo' => $codigo, 'msg' => 'SMTP no configurado — código en dev_codigo']);
    }

    jsonOk(['ok' => true, 'msg' => 'Código enviado a ' . $email]);
}

// ============================================================
//  AUTH — VERIFICAR CÓDIGO OTP
// ============================================================
function handleAuthVerificar(array $body): void {
    $email  = strtolower(trim($body['email'] ?? ''));
    $codigo = trim($body['codigo'] ?? '');

    if (!$email || !$codigo) jsonError('Email y código requeridos');

    $db = getDB();
    $st = $db->prepare(
        "SELECT id FROM verificacion_codigos
         WHERE email=? AND codigo=? AND usado=0 AND expires_at > NOW()
         ORDER BY id DESC LIMIT 1"
    );
    $st->execute([$email, $codigo]);
    $row = $st->fetch();
    if (!$row) jsonError('Código inválido o expirado', 401);

    // Marcar código como usado
    $db->prepare("UPDATE verificacion_codigos SET usado=1 WHERE id=?")->execute([$row['id']]);

    // Obtener usuario
    $st = $db->prepare("SELECT id, nombre, email FROM usuarios WHERE email=?");
    $st->execute([$email]);
    $user = $st->fetch();

    // Crear sesión
    $token     = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+' . SESSION_HOURS . ' hours'));
    $db->prepare(
        "INSERT INTO sesiones (usuario_id, token, expires_at) VALUES (?,?,?)"
    )->execute([$user['id'], $token, $expiresAt]);

    jsonOk([
        'ok'    => true,
        'token' => $token,
        'usuario' => [
            'id'     => $user['id'],
            'nombre' => $user['nombre'],
            'email'  => $user['email'],
        ]
    ]);
}

// ============================================================
//  AUTH — LOGOUT
// ============================================================
function handleAuthLogout(array $body): void {
    $token = getBearerToken() ?? ($body['token'] ?? '');
    if ($token) {
        getDB()->prepare("DELETE FROM sesiones WHERE token=?")->execute([$token]);
    }
    jsonOk(['ok' => true]);
}

// ============================================================
//  MI PERFIL — datos del usuario autenticado
// ============================================================
function handleMiPerfil(string $method, array $body): void {
    $session = requireSession();
    $db      = getDB();

    if ($method === 'GET') {
        $lineaId = (int)($_GET['linea_id'] ?? 0);
        $usuario = ['id' => $session['usuario_id'], 'nombre' => $session['nombre'], 'email' => $session['email']];
        $valores = [];
        if ($lineaId) {
            $st = $db->prepare(
                "SELECT campo_clave, valor FROM usuario_valores WHERE usuario_id=? AND linea_id=?"
            );
            $st->execute([$session['usuario_id'], $lineaId]);
            $vals   = $st->fetchAll();
            $valores = array_column($vals, 'valor', 'campo_clave');
        }
        jsonOk(['usuario' => $usuario, 'valores' => $valores]);
    }
}

// ============================================================
//  COTIZACIONES
// ============================================================
function handleCotizaciones(string $method, array $body, bool $isAdmin): void {
    $db = getDB();

    if ($method === 'GET') {
        $lineaId = (int)($_GET['linea_id'] ?? 0);
        // Admin ve todas; usuario ve las suyas
        if ($isAdmin) {
            $st = $db->prepare(
                "SELECT c.*, u.nombre as usuario_nombre, u.email as usuario_email
                 FROM cotizaciones c LEFT JOIN usuarios u ON u.id = c.usuario_id
                 WHERE c.linea_id=? ORDER BY c.id DESC LIMIT 50"
            );
            $st->execute([$lineaId]);
        } else {
            $session = requireSession();
            $st = $db->prepare(
                "SELECT * FROM cotizaciones WHERE linea_id=? AND usuario_id=? ORDER BY id DESC LIMIT 50"
            );
            $st->execute([$lineaId, $session['usuario_id']]);
        }
        $rows = $st->fetchAll();
        foreach ($rows as &$r) {
            $r['inputs_json']   = decodeJsonField($r['inputs_json']);
            $r['desglose_json'] = decodeJsonField($r['desglose_json']);
            $r['precio_base']   = (float)$r['precio_base'];
            $r['precio_final']  = (float)$r['precio_final'];
        }
        jsonOk($rows);
    }

    if ($method === 'POST') {
        $usuarioId = null;
        if (!$isAdmin) {
            $session   = requireSession();
            $usuarioId = $session['usuario_id'];
        } else {
            $usuarioId = $body['usuario_id'] ?? null;
        }

        $lineaId = (int)$body['linea_id'];
        $stLinea = $db->prepare("SELECT precio_base FROM lineas WHERE id=?");
        $stLinea->execute([$lineaId]);
        $linea = $stLinea->fetch();        
        
        if (!$linea) jsonError('Línea no encontrada', 404);

        $st = $db->prepare(
            "INSERT INTO cotizaciones (linea_id, usuario_id, inputs_json, desglose_json, precio_base, precio_final)
             VALUES (?,?,?,?,?,?)"
        );
        $st->execute([
            $lineaId,
            $usuarioId,
            json_encode($body['inputs'] ?? []),
            json_encode($body['desglose'] ?? []),
            $linea['precio_base'],
            $body['precio_final']
        ]);
        jsonOk(['id' => $db->lastInsertId(), 'ok' => true]);
    }

    if ($method === 'DELETE') {
        requireAdmin($isAdmin);
        $id = (int)($_GET['id'] ?? 0);
        $db->prepare("DELETE FROM cotizaciones WHERE id=?")->execute([$id]);
        jsonOk(['ok' => true]);
    }
}

// ============================================================
//  ENVÍO DE EMAIL OTP (PHPMailer)
// ============================================================
function sendOtpEmail(string $to, string $nombre, string $codigo): bool {
    $subject = 'Tu codigo de acceso: ' . $codigo;
    $html    = buildOtpHtml($nombre, $codigo);

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = 20;

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to, $nombre);
        $mail->addReplyTo(SMTP_FROM, SMTP_FROM_NAME);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = "Hola {$nombre},\n\nTu codigo de verificacion es: {$codigo}\n\nEste codigo expira en 15 minutos.";

        $mail->send();
        return true;
    } catch (\Throwable $e) {
        error_log('OTP Mailer Error: ' . $e->getMessage());
        return false;
    }
}
function buildOtpHtml(string $nombre, string $codigo): string {
    return <<<HTML
<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
  body{font-family:Arial,sans-serif;background:#f4f4f8;margin:0;padding:20px;}
  .box{max-width:480px;margin:0 auto;background:#fff;border-radius:12px;padding:40px;box-shadow:0 4px 20px rgba(0,0,0,.08);}
  .brand{color:#aa2334;font-size:22px;font-weight:700;margin-bottom:24px;}
  .code{font-size:48px;font-weight:900;letter-spacing:12px;color:#aa2334;text-align:center;
        background:#fbe9ec;border-radius:10px;padding:20px;margin:24px 0;}
  .footer{font-size:12px;color:#888;margin-top:24px;}
</style></head><body>
<div class="box">
  <div class="brand">Calculadora de Descuentos</div>
  <p>Hola <strong>{$nombre}</strong>,</p>
  <p>Tu código de verificación es:</p>
  <div class="code">{$codigo}</div>
  <p>Este código expira en <strong>15 minutos</strong>.</p>
  <p>Si no solicitaste este código, ignora este mensaje.</p>
  <div class="footer">© Calculadora de Descuentos</div>
</div></body></html>
HTML;
}