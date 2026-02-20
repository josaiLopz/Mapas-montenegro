<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Flash');
        //$this->loadComponent('FormProtection');
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        $identity = $this->request->getAttribute('identity');
        if ($identity) {
            $this->set('identity', $identity);
        }
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $identity = $this->request->getAttribute('identity');
        $controller = (string)$this->request->getParam('controller');
        $action = (string)$this->request->getParam('action');

        if ($controller === 'Users' && in_array($action, ['login', 'logout'], true)) {
            return;
        }

        if (!$identity) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $publicForAllRoles = [
            'AboutUs' => ['publicView'],
        ];

        if (isset($publicForAllRoles[$controller]) && in_array($action, $publicForAllRoles[$controller], true)) {
            return;
        }

        $userId = (int)$identity->get('id');
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($userId, [
            'contain' => ['Roles' => ['Permissions']],
        ]);

        $role = $user->role;
        if (!$role || empty($role->permissions)) {
            throw new ForbiddenException('No tienes permiso para acceder a esta pagina.');
        }

        $permissionMap = $this->buildPermissionMap($role->permissions);
        $controllerKey = strtolower($controller);
        $actionKey = strtolower($action);

        if ($this->hasDirectPermission($permissionMap, $controllerKey, $actionKey)) {
            return;
        }

        if ($this->hasImpliedPermission($permissionMap, $controllerKey, $actionKey, (string)($role->name ?? ''))) {
            return;
        }

        $this->Flash->error('No tienes permiso para acceder a esa seccion.');

        if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
            return $this->response
                ->withStatus(403)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'ok' => false,
                    'message' => 'No autorizado',
                ]));
        }

        $referer = (string)$this->referer(['controller' => 'Dashboard', 'action' => 'index'], true);
        if ($referer === '' || str_contains(strtolower($referer), '/users/login')) {
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        return $this->redirect($referer);
    }

    private function buildPermissionMap(iterable $permissions): array
    {
        $map = [];
        foreach ($permissions as $permission) {
            $controller = strtolower((string)($permission->controller ?? ''));
            $action = strtolower((string)($permission->action ?? ''));
            if ($controller === '' || $action === '') {
                continue;
            }
            $map[$controller . '.' . $action] = true;
        }

        return $map;
    }

    private function hasDirectPermission(array $permissionMap, string $controller, string $action): bool
    {
        return isset($permissionMap[$controller . '.' . $action]);
    }

    private function hasImpliedPermission(array $permissionMap, string $controller, string $action, string $roleName): bool
    {
        if ($controller === 'schools') {
            if ($action === 'territoriosresumen') {
                return $this->hasDirectPermission($permissionMap, 'schools', 'filtros')
                    || $this->hasDirectPermission($permissionMap, 'schools', 'misfiltros')
                    || $this->hasDirectPermission($permissionMap, 'schools', 'filtrarschools');
            }
            return false;
        }

        if ($controller !== 'tickets') {
            return false;
        }

        $roleName = strtolower($roleName);
        if (
            str_contains($roleName, 'admin') ||
            str_contains($roleName, 'super') ||
            str_contains($roleName, 'soporte') ||
            str_contains($roleName, 'support')
        ) {
            return true;
        }

        if ($this->hasDirectPermission($permissionMap, 'tickets', 'manage')) {
            return true;
        }

        if ($this->hasDirectPermission($permissionMap, 'tickets', 'index')) {
            $allowedFromIndex = [
                'index',
                'view',
                'add',
                'addupdate',
                'updatestatus',
                'mynotifications',
                'marknotificationread',
                'downloadattachment',
                'manage',
            ];

            return in_array($action, $allowedFromIndex, true);
        }

        return false;
    }
}
