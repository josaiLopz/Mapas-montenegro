<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\RolesTable $Roles
 */
class RolesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');
    }
    public function index()
    {
        $roles =  $this->paginate($this->Roles->find()->contain(['Permissions', 'Users']));
        $this->set(compact('roles'));
    }

    /**
     * View method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $role = $this->Roles->get($id, ['contain' => ['Permissions', 'Users']]);
        $this->set(compact('role'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $role = $this->Roles->newEmptyEntity();
        if ($this->request->is('post')) {
            $role = $this->Roles->patchEntity($role, $this->request->getData(), ['associated' => ['Permissions']]);
            if ($this->Roles->save($role)) {
                $this->Flash->success(__('Rol creado correctamente'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Error al crear el rol.'));
        }
      $permissionsRaw = $this->Roles->Permissions
            ->find()
            ->order(['controller' => 'ASC', 'action' => 'ASC'])
            ->all();

        $permissionsGrouped = [];

        foreach ($permissionsRaw as $permission) {
            $permissionsGrouped[$permission->controller][] = $permission;
        }

        $this->set(compact('role', 'permissionsGrouped'));

    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $role = $this->Roles->get($id, ['contain' => ['Permissions']]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $role = $this->Roles->patchEntity($role, $this->request->getData(), ['associated' => ['Permissions']]);
            if ($this->Roles->save($role)) {
                $this->Flash->success(__('El rol ha sido guardado.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('El rol no se pudo guardar. Por favor, inténtalo de nuevo.'));
        }
      $permissionsRaw = $this->Roles->Permissions
    ->find()
    ->order(['controller' => 'ASC', 'action' => 'ASC'])
    ->all();

$permissionsGrouped = [];

foreach ($permissionsRaw as $permission) {
    $permissionsGrouped[$permission->controller][] = $permission;
}

$this->set(compact('role', 'permissionsGrouped'));

    }

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $role = $this->Roles->get($id);
        if ($this->Roles->delete($role)) {
            $this->Flash->success(__('Rol eliminado.'));
        } else {
            $this->Flash->error(__('Error al eliminar el rol. Por favor, inténtalo de nuevo.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
