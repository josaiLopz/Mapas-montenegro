<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\TicketsTable;
use Cake\I18n\FrozenTime;
use Laminas\Diactoros\UploadedFile;

class TicketsController extends AppController
{
    private const ALLOWED_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv',
    ];

    private const MAX_FILE_SIZE_BYTES = 15 * 1024 * 1024;

    public function index()
    {
        $identity = $this->request->getAttribute('identity');
        $currentUserId = (int)$identity->getIdentifier();
        $canManage = $this->canManageTickets($identity);

        $scope = (string)$this->request->getQuery('scope', 'mine');
        if (!$canManage) {
            $scope = 'mine';
        }

        $status = (string)$this->request->getQuery('status', '');
        $type = (string)$this->request->getQuery('type', '');

        $query = $this->fetchTable('Tickets')
            ->find()
            ->contain(['Requesters', 'Assignees'])
            ->order(['Tickets.created' => 'DESC']);

        if ($scope !== 'all') {
            $query->where([
                'OR' => [
                    'Tickets.requested_by' => $currentUserId,
                    'Tickets.assigned_to' => $currentUserId,
                ],
            ]);
        }

        if ($status !== '') {
            $query->where(['Tickets.status' => $status]);
        }

        if ($type !== '') {
            $query->where(['Tickets.type' => $type]);
        }

        $tickets = $this->paginate($query, ['limit' => 20]);
        $this->set([
            'tickets' => $tickets,
            'scope' => $scope,
            'status' => $status,
            'type' => $type,
            'canManage' => $canManage,
            'statusLabels' => TicketsTable::statusLabels(),
            'typeLabels' => TicketsTable::typeLabels(),
            'priorityLabels' => TicketsTable::priorityLabels(),
        ]);
    }

    public function add()
    {
        $identity = $this->request->getAttribute('identity');
        $currentUserId = (int)$identity->getIdentifier();

        $tickets = $this->fetchTable('Tickets');
        $ticketUpdates = $this->fetchTable('TicketUpdates');

        $ticket = $tickets->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = (array)$this->request->getData();
            $data['requested_by'] = $currentUserId;
            $data['status'] = TicketsTable::STATUS_NEW;
            if (empty($data['assigned_to'])) {
                $data['assigned_to'] = null;
            }

            $ticket = $tickets->patchEntity($ticket, $data);
            if (!$ticket->hasErrors() && $tickets->save($ticket)) {
                $ticket->folio = $this->buildTicketFolio((int)$ticket->id);
                $tickets->save($ticket);

                $update = $ticketUpdates->newEntity([
                    'ticket_id' => (int)$ticket->id,
                    'created_by' => $currentUserId,
                    'update_type' => 'created',
                    'status_to' => $ticket->status,
                    'message' => 'Incidencia registrada por el usuario.',
                ]);
                $ticketUpdates->save($update);

                $files = $this->normalizeFiles($this->request->getData('attachments'));
                $this->saveAttachments((int)$ticket->id, $update?->id ? (int)$update->id : null, $currentUserId, $files);

                $supportUserIds = $this->findSupportUserIds($currentUserId);
                $this->createNotifications(
                    $supportUserIds,
                    (int)$ticket->id,
                    'Nueva incidencia recibida',
                    sprintf('Se registro %s: %s', (string)$ticket->folio, (string)$ticket->title),
                    'new_ticket'
                );

                $this->Flash->success('Ticket creado correctamente.');

                return $this->redirect(['action' => 'view', (int)$ticket->id]);
            }

            $this->Flash->error('No se pudo crear el ticket. Revisa los datos.');
        }

        $assignees = [];
        if ($this->canManageTickets($identity)) {
            $assignees = $this->fetchTable('Users')->find('list', [
                'keyField' => 'id',
                'valueField' => function ($u) {
                    return trim((string)$u->name . ' (' . (string)$u->email . ')');
                },
            ])->order(['name' => 'ASC'])->toArray();
        }

        $this->set([
            'ticket' => $ticket,
            'assignees' => $assignees,
            'typeLabels' => TicketsTable::typeLabels(),
            'priorityLabels' => TicketsTable::priorityLabels(),
            'canManage' => $this->canManageTickets($identity),
        ]);
    }

    public function view($id = null)
    {
        $identity = $this->request->getAttribute('identity');
        $currentUserId = (int)$identity->getIdentifier();
        $canManage = $this->canManageTickets($identity);

        $ticket = $this->fetchTable('Tickets')->get((int)$id, [
            'contain' => [
                'Requesters',
                'Assignees',
                'TicketUpdates' => function ($q) {
                    return $q
                        ->contain(['Creators', 'TicketAttachments'])
                        ->order(['TicketUpdates.created' => 'ASC']);
                },
            ],
        ]);

        if (!$this->canViewTicket($ticket, $currentUserId, $canManage)) {
            $this->Flash->error('No tienes acceso a este ticket.');

            return $this->redirect(['action' => 'index']);
        }

        $this->set([
            'ticket' => $ticket,
            'canManage' => $canManage,
            'assignees' => $canManage ? $this->fetchTable('Users')->find('list', [
                'keyField' => 'id',
                'valueField' => function ($u) {
                    return trim((string)$u->name . ' (' . (string)$u->email . ')');
                },
            ])->order(['name' => 'ASC'])->toArray() : [],
            'statusLabels' => TicketsTable::statusLabels(),
            'typeLabels' => TicketsTable::typeLabels(),
            'priorityLabels' => TicketsTable::priorityLabels(),
            'canChangeStatus' => $this->canChangeStatus($ticket, $currentUserId, $canManage),
            'statusOptions' => $this->buildStatusOptions($ticket, $currentUserId, $canManage),
        ]);
    }

    public function addUpdate($ticketId = null)
    {
        $this->request->allowMethod(['post']);

        $identity = $this->request->getAttribute('identity');
        $currentUserId = (int)$identity->getIdentifier();
        $canManage = $this->canManageTickets($identity);

        $ticket = $this->fetchTable('Tickets')->get((int)$ticketId, [
            'contain' => ['Requesters', 'Assignees'],
        ]);

        if (!$this->canViewTicket($ticket, $currentUserId, $canManage)) {
            $this->Flash->error('No tienes acceso a este ticket.');

            return $this->redirect(['action' => 'index']);
        }

        $message = trim((string)$this->request->getData('message', ''));
        $files = $this->normalizeFiles($this->request->getData('attachments'));

        if ($message === '' && empty($files)) {
            $this->Flash->error('Escribe un comentario o sube un archivo para registrar seguimiento.');

            return $this->redirect(['action' => 'view', (int)$ticketId]);
        }

        $update = $this->fetchTable('TicketUpdates')->newEntity([
            'ticket_id' => (int)$ticketId,
            'created_by' => $currentUserId,
            'update_type' => 'comment',
            'message' => $message !== '' ? $message : 'Adjunto agregado.',
        ]);

        if (!$this->fetchTable('TicketUpdates')->save($update)) {
            $this->Flash->error('No se pudo guardar el seguimiento.');

            return $this->redirect(['action' => 'view', (int)$ticketId]);
        }

        $this->saveAttachments((int)$ticketId, (int)$update->id, $currentUserId, $files);

        $recipients = $this->resolveCommentRecipients($ticket, $currentUserId, $canManage);
        $this->createNotifications(
            $recipients,
            (int)$ticketId,
            'Nuevo seguimiento',
            sprintf('Ticket %s tiene un nuevo comentario.', (string)$ticket->folio),
            'comment'
        );

        $this->Flash->success('Seguimiento guardado.');

        return $this->redirect(['action' => 'view', (int)$ticketId]);
    }

    public function updateStatus($ticketId = null)
    {
        $this->request->allowMethod(['post']);

        $identity = $this->request->getAttribute('identity');
        $currentUserId = (int)$identity->getIdentifier();
        $canManage = $this->canManageTickets($identity);

        $tickets = $this->fetchTable('Tickets');
        $ticket = $tickets->get((int)$ticketId, ['contain' => ['Requesters', 'Assignees']]);

        if (!$this->canViewTicket($ticket, $currentUserId, $canManage) || !$this->canChangeStatus($ticket, $currentUserId, $canManage)) {
            $this->Flash->error('No tienes permisos para cambiar el estatus de este ticket.');

            return $this->redirect(['action' => 'view', (int)$ticketId]);
        }

        $newStatus = (string)$this->request->getData('status');
        $statusOptions = array_keys($this->buildStatusOptions($ticket, $currentUserId, $canManage));

        if (!in_array($newStatus, $statusOptions, true)) {
            $this->Flash->error('Estatus no permitido para tu perfil.');

            return $this->redirect(['action' => 'view', (int)$ticketId]);
        }

        $oldStatus = (string)$ticket->status;
        $statusComment = trim((string)$this->request->getData('status_comment', ''));

        if ($oldStatus === $newStatus && !$canManage) {
            $this->Flash->error('El estatus no cambio.');

            return $this->redirect(['action' => 'view', (int)$ticketId]);
        }

        $ticket->status = $newStatus;
        if ($newStatus === TicketsTable::STATUS_CLOSED) {
            $ticket->closed_at = FrozenTime::now();
        } elseif ($oldStatus === TicketsTable::STATUS_CLOSED && $newStatus !== TicketsTable::STATUS_CLOSED) {
            $ticket->closed_at = null;
        }

        if ($canManage && $this->request->getData('assigned_to') !== null) {
            $assignedTo = (int)$this->request->getData('assigned_to');
            $ticket->assigned_to = $assignedTo > 0 ? $assignedTo : null;
        }

        if (!$tickets->save($ticket)) {
            $this->Flash->error('No se pudo actualizar el estatus del ticket.');

            return $this->redirect(['action' => 'view', (int)$ticketId]);
        }

        $updateMessage = $statusComment !== '' ? $statusComment : 'Cambio de estatus registrado.';
        $update = $this->fetchTable('TicketUpdates')->newEntity([
            'ticket_id' => (int)$ticketId,
            'created_by' => $currentUserId,
            'update_type' => 'status_change',
            'status_from' => $oldStatus,
            'status_to' => $newStatus,
            'message' => $updateMessage,
        ]);
        $this->fetchTable('TicketUpdates')->save($update);

        $recipients = array_unique(array_filter([
            (int)$ticket->requested_by,
            (int)$ticket->assigned_to,
            ...$this->findSupportUserIds(),
        ]));
        $recipients = array_values(array_filter($recipients, fn ($id) => $id !== $currentUserId));

        $statusLabels = TicketsTable::statusLabels();
        $this->createNotifications(
            $recipients,
            (int)$ticketId,
            'Cambio de estatus',
            sprintf(
                'Ticket %s paso de %s a %s.',
                (string)$ticket->folio,
                $statusLabels[$oldStatus] ?? $oldStatus,
                $statusLabels[$newStatus] ?? $newStatus
            ),
            'status_change'
        );

        $this->Flash->success('Estatus actualizado.');

        return $this->redirect(['action' => 'view', (int)$ticketId]);
    }

    public function myNotifications()
    {
        $this->request->allowMethod(['get']);

        $identity = $this->request->getAttribute('identity');
        $currentUserId = (int)$identity->getIdentifier();

        $notificationsTable = $this->fetchTable('TicketNotifications');

        $query = $notificationsTable
            ->find()
            ->where(['TicketNotifications.user_id' => $currentUserId])
            ->order(['TicketNotifications.created' => 'DESC'])
            ->limit(15);

        $includeRead = (string)$this->request->getQuery('include_read', '0') === '1';
        if (!$includeRead) {
            $query->where(['TicketNotifications.is_read' => false]);
        }

        $notifications = $query->all()->toArray();

        $unreadCount = $notificationsTable
            ->find()
            ->where([
                'TicketNotifications.user_id' => $currentUserId,
                'TicketNotifications.is_read' => false,
            ])
            ->count();

        $rows = array_map(function ($item) {
            return [
                'id' => (int)$item->id,
                'ticket_id' => (int)$item->ticket_id,
                'title' => (string)$item->title,
                'message' => (string)$item->message,
                'event_type' => (string)$item->event_type,
                'is_read' => (bool)$item->is_read,
                'created' => $item->created ? $item->created->format('Y-m-d H:i:s') : '',
                'url' => $this->Url->build(['controller' => 'Tickets', 'action' => 'view', (int)$item->ticket_id]),
            ];
        }, $notifications);

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'ok' => true,
                'unread_count' => $unreadCount,
                'rows' => $rows,
            ]));
    }

    public function markNotificationRead($id = null)
    {
        $this->request->allowMethod(['post']);

        $identity = $this->request->getAttribute('identity');
        $currentUserId = (int)$identity->getIdentifier();

        $notificationsTable = $this->fetchTable('TicketNotifications');
        $markAll = (string)$this->request->getData('all', '0') === '1';

        if ($markAll) {
            $notificationsTable->updateAll(
                ['is_read' => true, 'read_at' => FrozenTime::now()],
                ['user_id' => $currentUserId, 'is_read' => false]
            );
        } elseif ($id !== null) {
            $notification = $notificationsTable->find()
                ->where([
                    'id' => (int)$id,
                    'user_id' => $currentUserId,
                ])
                ->first();

            if ($notification) {
                $notification->is_read = true;
                $notification->read_at = FrozenTime::now();
                $notificationsTable->save($notification);
            }
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['ok' => true]));
    }

    public function downloadAttachment($id = null)
    {
        $identity = $this->request->getAttribute('identity');
        $currentUserId = (int)$identity->getIdentifier();
        $canManage = $this->canManageTickets($identity);

        $attachment = $this->fetchTable('TicketAttachments')->get((int)$id, [
            'contain' => ['Tickets'],
        ]);

        if (!$attachment->ticket || !$this->canViewTicket($attachment->ticket, $currentUserId, $canManage)) {
            $this->Flash->error('No tienes acceso a este archivo.');

            return $this->redirect(['action' => 'index']);
        }

        $fullPath = WWW_ROOT . ltrim((string)$attachment->relative_path, '/\\');
        if (!is_file($fullPath)) {
            $this->Flash->error('El archivo ya no existe en el servidor.');

            return $this->redirect(['action' => 'view', (int)$attachment->ticket_id]);
        }

        return $this->response->withFile($fullPath, [
            'download' => true,
            'name' => (string)$attachment->original_name,
        ]);
    }

    public function manage()
    {
        return $this->redirect(['action' => 'index', '?' => ['scope' => 'all']]);
    }

    private function canManageTickets($identity): bool
    {
        $user = $identity ? $identity->getOriginalData() : null;
        if (!$user || empty($user->role) || empty($user->role->permissions)) {
            return false;
        }

        foreach ($user->role->permissions as $permission) {
            if (strtolower((string)$permission->controller) === 'tickets' && strtolower((string)$permission->action) === 'manage') {
                return true;
            }
        }

        $roleName = strtolower((string)($user->role->name ?? ''));

        return str_contains($roleName, 'admin')
            || str_contains($roleName, 'super')
            || str_contains($roleName, 'soporte')
            || str_contains($roleName, 'support');
    }

    private function canViewTicket($ticket, int $currentUserId, bool $canManage): bool
    {
        if ($canManage) {
            return true;
        }

        return (int)$ticket->requested_by === $currentUserId || (int)$ticket->assigned_to === $currentUserId;
    }

    private function canChangeStatus($ticket, int $currentUserId, bool $canManage): bool
    {
        if ($canManage) {
            return true;
        }

        return (int)$ticket->requested_by === $currentUserId;
    }

    private function buildStatusOptions($ticket, int $currentUserId, bool $canManage): array
    {
        $all = TicketsTable::statusLabels();
        if ($canManage) {
            return $all;
        }

        if ((int)$ticket->requested_by !== $currentUserId) {
            return [];
        }

        return [
            TicketsTable::STATUS_IN_PROGRESS => $all[TicketsTable::STATUS_IN_PROGRESS],
            TicketsTable::STATUS_CLOSED => $all[TicketsTable::STATUS_CLOSED],
        ];
    }

    private function buildTicketFolio(int $id): string
    {
        return 'TKT-' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);
    }

    private function normalizeFiles(mixed $rawFiles): array
    {
        if ($rawFiles instanceof UploadedFile) {
            return [$rawFiles];
        }

        if (is_array($rawFiles)) {
            return array_values(array_filter($rawFiles, fn ($file) => $file instanceof UploadedFile));
        }

        return [];
    }

    private function saveAttachments(int $ticketId, ?int $ticketUpdateId, int $userId, array $files): void
    {
        if (empty($files)) {
            return;
        }

        $attachmentsTable = $this->fetchTable('TicketAttachments');
        $relativeDir = 'uploads' . DS . 'tickets' . DS . date('Y') . DS . date('m') . DS;
        $targetDir = WWW_ROOT . $relativeDir;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile || $file->getError() !== UPLOAD_ERR_OK) {
                continue;
            }

            $originalName = (string)($file->getClientFilename() ?: 'archivo');
            $extension = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
                $this->Flash->error(sprintf('Archivo no permitido: %s', $originalName));
                continue;
            }

            $size = (int)$file->getSize();
            if ($size > self::MAX_FILE_SIZE_BYTES) {
                $this->Flash->error(sprintf('Archivo excede 15MB: %s', $originalName));
                continue;
            }

            $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)pathinfo($originalName, PATHINFO_FILENAME));
            $safeBase = $safeBase !== '' ? $safeBase : 'archivo';
            $storedName = uniqid('tkt_', true) . '_' . $safeBase . '.' . $extension;
            $fullPath = $targetDir . $storedName;

            $file->moveTo($fullPath);

            $attachment = $attachmentsTable->newEntity([
                'ticket_id' => $ticketId,
                'ticket_update_id' => $ticketUpdateId,
                'created_by' => $userId,
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'relative_path' => str_replace('\\', '/', $relativeDir . $storedName),
                'mime_type' => (string)$file->getClientMediaType(),
                'file_size' => $size,
                'extension' => $extension,
            ]);

            $attachmentsTable->save($attachment);
        }
    }

    private function findSupportUserIds(?int $excludeUserId = null): array
    {
        $users = $this->fetchTable('Users')
            ->find()
            ->contain(['Roles' => ['Permissions']])
            ->all()
            ->toArray();

        $ids = [];
        foreach ($users as $user) {
            $roleName = strtolower((string)($user->role->name ?? ''));
            $isManagerByRole = str_contains($roleName, 'admin')
                || str_contains($roleName, 'super')
                || str_contains($roleName, 'soporte')
                || str_contains($roleName, 'support');

            $hasManagePermission = false;
            if (!empty($user->role->permissions)) {
                foreach ($user->role->permissions as $permission) {
                    if (
                        strtolower((string)$permission->controller) === 'tickets' &&
                        strtolower((string)$permission->action) === 'manage'
                    ) {
                        $hasManagePermission = true;
                        break;
                    }
                }
            }

            if ($isManagerByRole || $hasManagePermission) {
                $ids[] = (int)$user->id;
            }
        }

        $users = array_values(array_unique(array_filter($ids, fn ($id) => $id > 0)));

        if ($excludeUserId !== null) {
            $users = array_values(array_filter($users, fn ($id) => $id !== $excludeUserId));
        }

        return $users;
    }

    private function resolveCommentRecipients($ticket, int $authorId, bool $authorCanManage): array
    {
        if ($authorCanManage) {
            return array_values(array_filter([(int)$ticket->requested_by], fn ($id) => $id !== $authorId));
        }

        return $this->findSupportUserIds($authorId);
    }

    private function createNotifications(array $userIds, int $ticketId, string $title, string $message, string $eventType): void
    {
        if (empty($userIds)) {
            return;
        }

        $notificationsTable = $this->fetchTable('TicketNotifications');
        $rows = [];
        foreach (array_unique($userIds) as $userId) {
            if ((int)$userId <= 0) {
                continue;
            }
            $rows[] = [
                'user_id' => (int)$userId,
                'ticket_id' => $ticketId,
                'event_type' => $eventType,
                'title' => $title,
                'message' => $message,
                'is_read' => false,
            ];
        }

        if (empty($rows)) {
            return;
        }

        $entities = $notificationsTable->newEntities($rows);
        foreach ($entities as $entity) {
            $notificationsTable->save($entity);
        }
    }
}
