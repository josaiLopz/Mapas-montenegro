<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Permissions Controller
 *
 * @property \App\Model\Table\PermissionsTable $Permissions
 */
class PermissionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $permissions = $this->paginate(
            $this->Permissions->find()->contain(['Roles']),
            ['limit' => 10]
        );
        $this->set(compact('permissions'));
        // $query = $this->Permissions->find();
        // $permissions = $this->paginate($query);

        // $this->set(compact('permissions'));
    }

    /**
     * View method
     *
     * @param string|null $id Permission id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $permission = $this->Permissions->get($id, contain: ['Roles']);
        $this->set(compact('permission'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $permission = $this->Permissions->newEmptyEntity();
        if ($this->request->is('post')) {
            $permission = $this->Permissions->patchEntity($permission, $this->request->getData());
            if ($this->Permissions->save($permission)) {
                $this->Flash->success(__('Permiso creado correctamente'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Error al crear el permiso. Por favor, inténtalo de nuevo.'));
        }
        $roles = $this->Permissions->Roles->find('list', limit: 200)->all();
        $this->set(compact('permission', 'roles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Permission id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $permission = $this->Permissions->get($id, contain: ['Roles']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $permission = $this->Permissions->patchEntity($permission, $this->request->getData());
            if ($this->Permissions->save($permission)) {
                $this->Flash->success(__('The permission has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The permission could not be saved. Please, try again.'));
        }
        $roles = $this->Permissions->Roles->find('list', limit: 200)->all();
        $this->set(compact('permission', 'roles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Permission id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $permission = $this->Permissions->get($id);
        if ($this->Permissions->delete($permission)) {
            $this->Flash->success(__('The permission has been deleted.'));
        } else {
            $this->Flash->error(__('The permission could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
