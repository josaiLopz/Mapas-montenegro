<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddTicketsPermissions extends BaseMigration
{
    public function up(): void
    {
        $permissions = $this->fetchTable('permissions');
        $roles = $this->fetchTable('roles');
        $rolesPermissions = $this->fetchTable('roles_permissions');

        $baseActions = [
            ['controller' => 'Tickets', 'action' => 'index', 'description' => 'Ver tickets'],
            ['controller' => 'Tickets', 'action' => 'add', 'description' => 'Crear ticket'],
            ['controller' => 'Tickets', 'action' => 'view', 'description' => 'Ver detalle de ticket'],
            ['controller' => 'Tickets', 'action' => 'addUpdate', 'description' => 'Agregar seguimiento a ticket'],
            ['controller' => 'Tickets', 'action' => 'updateStatus', 'description' => 'Cambiar estatus de ticket'],
            ['controller' => 'Tickets', 'action' => 'myNotifications', 'description' => 'Consultar notificaciones de ticket'],
            ['controller' => 'Tickets', 'action' => 'markNotificationRead', 'description' => 'Marcar notificacion de ticket'],
            ['controller' => 'Tickets', 'action' => 'downloadAttachment', 'description' => 'Descargar adjuntos de ticket'],
        ];

        $manageActions = [
            ['controller' => 'Tickets', 'action' => 'manage', 'description' => 'Gestion avanzada de tickets'],
        ];

        $permissionIds = [];
        foreach (array_merge($baseActions, $manageActions) as $item) {
            $exists = $permissions
                ->find()
                ->where([
                    'controller' => $item['controller'],
                    'action' => $item['action'],
                ])
                ->first();

            if ($exists) {
                $permissionIds[$item['action']] = (int)$exists['id'];
                continue;
            }

            $permissions->insert($item)->save();
            $created = $permissions
                ->find()
                ->where([
                    'controller' => $item['controller'],
                    'action' => $item['action'],
                ])
                ->first();

            if ($created) {
                $permissionIds[$item['action']] = (int)$created['id'];
            }
        }

        $allRoles = $roles->find()->all()->toArray();
        foreach ($allRoles as $role) {
            $roleId = (int)$role['id'];
            foreach ($baseActions as $actionData) {
                $permissionId = $permissionIds[$actionData['action']] ?? null;
                if (!$permissionId) {
                    continue;
                }

                $exists = $rolesPermissions
                    ->find()
                    ->where([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ])
                    ->first();

                if (!$exists) {
                    $rolesPermissions->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ])->save();
                }
            }

            $roleName = strtolower((string)($role['name'] ?? ''));
            $isManager = str_contains($roleName, 'admin')
                || str_contains($roleName, 'super')
                || str_contains($roleName, 'soporte')
                || str_contains($roleName, 'support');

            if ($isManager) {
                $manageId = $permissionIds['manage'] ?? null;
                if ($manageId) {
                    $exists = $rolesPermissions
                        ->find()
                        ->where([
                            'role_id' => $roleId,
                            'permission_id' => $manageId,
                        ])
                        ->first();

                    if (!$exists) {
                        $rolesPermissions->insert([
                            'role_id' => $roleId,
                            'permission_id' => $manageId,
                        ])->save();
                    }
                }
            }
        }
    }

    public function down(): void
    {
        $permissions = $this->fetchTable('permissions');
        $rolesPermissions = $this->fetchTable('roles_permissions');

        $ticketPermissions = $permissions->find()->where(['controller' => 'Tickets'])->all();

        foreach ($ticketPermissions as $permission) {
            $permissionId = (int)$permission['id'];
            $rolesPermissions->delete()->where(['permission_id' => $permissionId])->execute();
        }

        $permissions->delete()->where(['controller' => 'Tickets'])->execute();
    }
}
