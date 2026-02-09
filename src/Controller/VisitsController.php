<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\I18n\FrozenTime;
use Laminas\Diactoros\UploadedFile;

class VisitsController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        if ($this->components()->has('FormProtection')) {
            $this->FormProtection->setConfig('unlockedActions', [
                'schedule',
                'startRoute',
                'complete',
            ]);
        }
    }

    public function listVisits()
    {
        $this->request->allowMethod(['get']);

        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            return $this->response
                ->withStatus(401)
                ->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'message' => 'No autenticado']));
        }

        $currentUserId = (int)$identity->getIdentifier();
        $scope = (string)$this->request->getQuery('scope', 'mine');
        $status = (string)$this->request->getQuery('status', 'scheduled');

        $query = $this->fetchTable('Visits')
            ->find()
            ->contain(['Schools', 'Users']);

        if ($scope !== 'all') {
            $query->where(['Visits.user_id' => $currentUserId]);
        }

        if ($status !== '') {
            $query->where(['Visits.status' => $status]);
        }

        $rows = $query
            ->order(['Visits.scheduled_at' => 'DESC'])
            ->all()
            ->map(function ($v) {
                $school = $v->school ?? null;
                $evidenceUrl = '';
                if (!empty($v->evidence_file)) {
                    $evidenceUrl = '/uploads/visits/' . $v->evidence_file;
                }
                return [
                    'id' => $v->id,
                    'school_id' => $v->school_id,
                    'school_name' => $school->nombre ?? '',
                    'school_lat' => isset($school->lat) ? (float)$school->lat : null,
                    'school_lng' => isset($school->lng) ? (float)$school->lng : null,
                    'user_id' => $v->user_id,
                    'user_name' => $v->user->name ?? '',
                    'scheduled_at' => $v->scheduled_at ? $v->scheduled_at->format('Y-m-d H:i:s') : null,
                    'status' => $v->status,
                    'start_lat' => $v->start_lat,
                    'start_lng' => $v->start_lng,
                    'started_at' => $v->started_at ? $v->started_at->format('Y-m-d H:i:s') : null,
                    'completed_at' => $v->completed_at ? $v->completed_at->format('Y-m-d H:i:s') : null,
                    'evidence_url' => $evidenceUrl,
                    'notes' => $v->notes,
                    'completion_notes' => $v->completion_notes,
                ];
            })
            ->toArray();

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'ok' => true,
                'rows' => $rows,
            ]));
    }

    public function schedule()
    {
        $this->request->allowMethod(['post']);

        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            return $this->response
                ->withStatus(401)
                ->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'message' => 'No autenticado']));
        }

        $data = $this->request->getData();
        $schoolId = (int)($data['school_id'] ?? 0);
        $scheduledAtRaw = (string)($data['scheduled_at'] ?? '');
        $notes = trim((string)($data['notes'] ?? ''));

        if (!$schoolId || $scheduledAtRaw === '') {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'message' => 'Datos incompletos']));
        }

        try {
            $scheduledAt = new FrozenTime($scheduledAtRaw);
        } catch (\Exception $e) {
            throw new BadRequestException('Fecha invalida');
        }

        $visits = $this->fetchTable('Visits');
        $visit = $visits->newEmptyEntity();
        $visit->school_id = $schoolId;
        $visit->user_id = (int)$identity->getIdentifier();
        $visit->scheduled_at = $scheduledAt;
        $visit->status = 'scheduled';
        $visit->notes = $notes !== '' ? $notes : null;

        if ($visit->hasErrors()) {
            return $this->response
                ->withStatus(422)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'ok' => false,
                    'message' => 'Errores de validacion',
                    'errors' => $visit->getErrors(),
                ]));
        }

        if (!$visits->save($visit)) {
            return $this->response
                ->withStatus(422)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'ok' => false,
                    'message' => 'No se pudo agendar',
                ]));
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'ok' => true,
                'id' => $visit->id,
            ]));
    }

    public function startRoute()
    {
        $this->request->allowMethod(['post']);

        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            return $this->response
                ->withStatus(401)
                ->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'message' => 'No autenticado']));
        }

        $data = $this->request->getData();
        $visitId = (int)($data['visit_id'] ?? 0);
        $startLat = $data['start_lat'] ?? null;
        $startLng = $data['start_lng'] ?? null;

        if (!$visitId || !is_numeric($startLat) || !is_numeric($startLng)) {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'message' => 'Datos invalidos']));
        }

        $visits = $this->fetchTable('Visits');
        $visit = $visits->get($visitId);

        $currentUserId = (int)$identity->getIdentifier();
        if ((int)$visit->user_id !== $currentUserId) {
            throw new ForbiddenException('No autorizado');
        }

        $visit->start_lat = (float)$startLat;
        $visit->start_lng = (float)$startLng;
        $visit->started_at = FrozenTime::now();

        if (!$visits->save($visit)) {
            return $this->response
                ->withStatus(422)
                ->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'message' => 'No se pudo iniciar ruta']));
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['ok' => true]));
    }

    public function complete()
    {
        $this->request->allowMethod(['post']);

        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            return $this->response
                ->withStatus(401)
                ->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'message' => 'No autenticado']));
        }

        $data = $this->request->getData();
        $visitId = (int)($data['visit_id'] ?? 0);
        $completionNotes = trim((string)($data['completion_notes'] ?? ''));

        if (!$visitId) {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'message' => 'Datos invalidos']));
        }

        $visits = $this->fetchTable('Visits');
        $visit = $visits->get($visitId);

        $currentUserId = (int)$identity->getIdentifier();
        if ((int)$visit->user_id !== $currentUserId) {
            throw new ForbiddenException('No autorizado');
        }

        $file = $data['evidence'] ?? null;
        if ($file instanceof UploadedFile && $file->getError() === UPLOAD_ERR_OK) {
            $maxBytes = 10 * 1024 * 1024;
            if ($file->getSize() > $maxBytes) {
                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['ok' => false, 'message' => 'Archivo mayor a 10MB']));
            }

            $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
            $orig = $file->getClientFilename() ?: '';
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt, true)) {
                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['ok' => false, 'message' => 'Tipo de archivo no permitido']));
            }

            $dir = WWW_ROOT . 'uploads' . DS . 'visits' . DS;
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }

            $filename = uniqid('visit_', true) . '.' . $ext;
            $file->moveTo($dir . $filename);
            $visit->evidence_file = $filename;
        }

        $visit->status = 'completed';
        $visit->completed_at = FrozenTime::now();
        $visit->completion_notes = $completionNotes !== '' ? $completionNotes : null;

        if (!$visits->save($visit)) {
            return $this->response
                ->withStatus(422)
                ->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'message' => 'No se pudo completar']));
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['ok' => true]));
    }
}
