<?php
declare(strict_types=1);

namespace App\Controller;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
/**
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 *
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    /**
     * Initialize controller
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');

        $this->Authentication->allowUnauthenticated(['login']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
    $roles = $this->Users->Roles->find('list')->toArray();

    $query = $this->Users->find()->contain(['Roles']);
         // Aplicar filtros si existen
    $filters = $this->request->getQueryParams();

    if (!empty($filters['name'])) {
        $query->where(['Users.name LIKE' => '%' . $filters['name'] . '%']);
    }
    if (!empty($filters['apellido_paterno'])) {
        $query->where(['Users.apellido_paterno LIKE' => '%' . $filters['apellido_paterno'] . '%']);
    }
    if (!empty($filters['apellido_materno'])) {
        $query->where(['Users.apellido_materno LIKE' => '%' . $filters['apellido_materno'] . '%']);
    }
    if (!empty($filters['email'])) {
        $query->where(['Users.email LIKE' => '%' . $filters['email'] . '%']);
    }
    if (!empty($filters['role_id'])) {
        $query->where(['Users.role_id' => $filters['role_id']]);
    }
    if (isset($filters['activo']) && $filters['activo'] !== '') {
        $query->where(['Users.activo' => $filters['activo']]);
    }
    $users = $this->paginate($query);
           $this->set(compact('users', 'roles'));

        // $query = $this->Users->find();
        // $users = $this->paginate($query);

        // $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->request->allowMethod(['get']);

        $user = $this->Users->get($id, ['contain' => ['Roles']]);
        $this->set(compact('user'));
     if ($this->request->is('ajax')) {
        $this->viewBuilder()->setLayout('ajax');
    }
    //     $user = $this->Users->get($id, contain: []);
    //     $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
         $user = $this->Users->newEmptyEntity();
            if ($this->request->is('post')) {
                $data = $this->request->getData();
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    $this->Flash->success('Usuario creado correctamente');
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error('Error al crear usuario');
            }
            $roles = $this->Users->Roles->find('list');
            $this->set(compact('user','roles'));
            $this->render('add');
        // $user = $this->Users->newEmptyEntity();
        // if ($this->request->is('post')) {
        //     $user = $this->Users->patchEntity($user, $this->request->getData());
        //     if ($this->Users->save($user)) {
        //         $this->Flash->success(__('The user has been saved.'));

        //         return $this->redirect(['action' => 'index']);
        //     }
        //     $this->Flash->error(__('The user could not be saved. Please, try again.'));
        // }
        // $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id);
        if ($this->request->is(['patch','post','put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success('Usuario actualizado correctamente');
                return $this->redirect(['action'=>'index']);
            }
            $this->Flash->error('Error al actualizar usuario');
        }
        $roles = $this->Users->Roles->find('list');
        $this->set(compact('user','roles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post','delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success('Usuario eliminado');
        } else {
            $this->Flash->error('Error al eliminar usuario');
        }
        return $this->redirect(['action'=>'index']);
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful login, renders view otherwise.
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);

        $result = $this->Authentication->getResult();

       if ($result->isValid()) {
    return $this->redirect([
        'controller' => 'Users',
        'action' => 'profile'
    ]);
}


        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('Usuario o contraseña incorrectos');
        }
    }

    public function logout()
    {
        $this->Authentication->logout();
        return $this->redirect('/users/login');
    }
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    public function profile()
    {
            $identity = $this->request->getAttribute('identity');

    $user = $this->Users->get($identity->get('id'), [
        'contain' => ['Roles' => ['Permissions']]
    ]);

    $this->set(compact('user'));
        // $identity = $this->request->getAttribute('identity');
        // $user = $this->Users->get($identity->getIdentifier());
        // $this->set(compact('user'));
    }

public function editProfile()
{
    $identity = $this->request->getAttribute('identity');
    $user = $this->Users->get($identity->getIdentifier());
        $oldData = json_encode($user->toArray());

    if ($this->request->is(['patch', 'post', 'put'])) {

        $data = $this->request->getData();

        // Saca avatar del patch para que NUNCA intente guardarse como UploadedFile
        $file = $data['avatar'] ?? null;
        unset($data['avatar']);

        // Parchea solo texto
        $user = $this->Users->patchEntity($user, $data, [
            'fields' => ['name', 'email']
        ]);

        // Maneja archivo aparte
        if ($file instanceof \Laminas\Diactoros\UploadedFile && $file->getError() === UPLOAD_ERR_OK) {
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientFilename());
            $filename = uniqid('', true) . '-' . $safeName;

            $dir = WWW_ROOT . 'img' . DS . 'avatars' . DS;
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file->moveTo($dir . $filename);
            $user->avatar = $filename; // <- STRING
        }

        if ($this->Users->save($user)) {
            $this->UserAudits = TableRegistry::getTableLocator()->get('UserAudits');
            $audit = $this->UserAudits->newEntity([
                'user_id'      => $user->id,
                'action'       => 'edit_profile',
                'old_data'     => $oldData,
                'new_data'     => json_encode($user->toArray()),
                'performed_by'=> $identity->getIdentifier(),
                'created'      => date('Y-m-d H:i:s'),
            ]);

            $this->UserAudits->save($audit);
            $this->Flash->success('Perfil actualizado correctamente');
            return $this->redirect(['action'=> 'profile']);
        }
        $this->Flash->error('Error al actualizar el perfil');
    }

    $this->set(compact('user'));
}

    public function changePassword()
    {
        $identity = $this->request->getAttribute('identity');
        $user = $this->Users->get($identity->getIdentifier());

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $hasher = new DefaultPasswordHasher();

            if (!$hasher->check($data['currentPassword'], $user->password)) {
                $this->Flash->error('Contraseña actual incorrecta');
                return;
            }
            $oldData = json_encode($user->toArray());
            $user = $this->Users->patchEntity($user, [
                'password' => $data['new_password']
            
            ]);
            if ($this->Users->save($user)) {
                $this->Authentication->logout();
                $this->Flash->success('Contraseña actualizada. inicia sesión con la nueva contraseña.');
                
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('Error al actualizar la contraseña');
        }
        $this->set(compact('user'));
    }
    // public function isAuthorized($user)
    // {
    //     return true; 
    // }
    
}
