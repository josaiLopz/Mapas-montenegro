<?php
declare(strict_types=1);

namespace App\Controller;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Mailer\MailerAwareTrait;

use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Cake\Routing\Router;
use Cake\I18n\FrozenTime;
/**
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 *
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    use MailerAwareTrait;
    /**
 * @property \App\Model\Table\UserAuditsTable $UserAudits
 */
protected $UserAudits;
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
        $this->UserAudits = $this->fetchTable('UserAudits');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // id del rol Administrador
        $adminRoleId = $this->Users->Roles->find()
            ->select(['id'])
            ->where(['name' => 'Administrador'])
            ->first()?->id;
    
        // role del usuario logueado
        $identity = $this->request->getAttribute('identity'); // <- si no usas Authentication, cambia esta línea
        $loggedRoleId = $identity ? (int)$identity->get('role_id') : null;
    
        // Roles para filtro
        $roles = $this->Users->Roles->find('list')->toArray();
        if ($adminRoleId && (int)$loggedRoleId !== (int)$adminRoleId) {
            unset($roles[$adminRoleId]); // no-admin no puede ni filtrar por Admin
        }
    
        $query = $this->Users->find()->contain(['Roles']);
    
        // EXCLUIR admins solo si NO soy admin
        if ($adminRoleId && (int)$loggedRoleId !== (int)$adminRoleId) {
            $query->where(['Users.role_id !=' => $adminRoleId]);
        }
    
        // Filtros
        $filters = $this->request->getQueryParams();
    
        if (!empty($filters['nombre_completo'])) {
            $fullName = trim((string)$filters['nombre_completo']);
            $parts = preg_split('/\s+/', $fullName) ?: [];
    
            foreach ($parts as $part) {
                $term = trim((string)$part);
                if ($term === '') continue;
    
                $likeTerm = '%' . $term . '%';
                $query->where([
                    'OR' => [
                        'Users.name LIKE' => $likeTerm,
                        'Users.apellido_paterno LIKE' => $likeTerm,
                        'Users.apellido_materno LIKE' => $likeTerm,
                    ],
                ]);
            }
        }
    
        if (!empty($filters['email'])) {
            $query->where(['Users.email LIKE' => '%' . $filters['email'] . '%']);
        }
    
        if (!empty($filters['usern'])) {
            $query->where(['Users.usern LIKE' => '%' . $filters['usern'] . '%']);
        }
    
        if (!empty($filters['role_id'])) {
            $query->where(['Users.role_id' => $filters['role_id']]);
        }
    
        if (isset($filters['activo']) && $filters['activo'] !== '') {
            $query->where(['Users.activo' => $filters['activo']]);
        }
    
        $users = $this->paginate($query);
        $this->set(compact('users', 'roles'));
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
    
        $adminRoleId = $this->Users->Roles->find()
            ->select(['id'])
            ->where(['name' => 'Administrador'])
            ->first()?->id;
    
        $identity = $this->request->getAttribute('identity'); // <- ajusta si no usas Authentication
        $loggedRoleId = $identity ? (int)$identity->get('role_id') : null;
    
        $roles = $this->Users->Roles->find('list')->toArray();
        if ($adminRoleId && (int)$loggedRoleId !== (int)$adminRoleId) {
            unset($roles[$adminRoleId]);
        }
    
        if ($this->request->is('post')) {
            $data = $this->request->getData();
    
            // Bloqueo de seguridad: no-admin no puede asignar Administrador aunque lo envíe a mano
            if ($adminRoleId && (int)$loggedRoleId !== (int)$adminRoleId) {
                if ((int)($data['role_id'] ?? 0) === (int)$adminRoleId) {
                    $this->Flash->error('No tienes permisos para asignar el rol Administrador.');
                    return $this->redirect(['action' => 'index']);
                }
            }
    
            $user = $this->Users->patchEntity($user, $data);
    
            if ($this->Users->save($user)) {
                $this->Flash->success('Usuario creado correctamente');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Error al crear usuario');
        }
    
        $this->set(compact('user', 'roles'));
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
    
        $adminRoleId = $this->Users->Roles->find()
            ->select(['id'])
            ->where(['name' => 'Administrador'])
            ->first()?->id;
    
        $identity = $this->request->getAttribute('identity'); // <- ajusta si no usas Authentication
        $loggedRoleId = $identity ? (int)$identity->get('role_id') : null;
    
        $roles = $this->Users->Roles->find('list')->toArray();
        if ($adminRoleId && (int)$loggedRoleId !== (int)$adminRoleId) {
            unset($roles[$adminRoleId]);
        }
    
        if ($this->request->is(['patch','post','put'])) {
            $data = $this->request->getData();
    
            // Bloqueo de seguridad
            if ($adminRoleId && (int)$loggedRoleId !== (int)$adminRoleId) {
                if ((int)($data['role_id'] ?? 0) === (int)$adminRoleId) {
                    $this->Flash->error('No tienes permisos para asignar el rol Administrador.');
                    return $this->redirect(['action' => 'index']);
                }
            }
    
            $newPassword = trim((string)($data['new_password'] ?? ''));
            $confirmPassword = trim((string)($data['confirm_password'] ?? ''));
    
            unset($data['new_password'], $data['confirm_password']);
    
            if ($newPassword !== '') {
                if (strlen($newPassword) < 8) {
                    $this->Flash->error('La contraseña debe tener mínimo 8 caracteres.');
                    $this->set(compact('user', 'roles'));
                    return;
                }
    
                if ($newPassword !== $confirmPassword) {
                    $this->Flash->error('Las contraseñas no coinciden.');
                    $this->set(compact('user', 'roles'));
                    return;
                }
    
                $data['password'] = $newPassword;
            }
    
            $user = $this->Users->patchEntity($user, $data);
    
            if ($this->Users->save($user)) {
                $this->Flash->success('Usuario actualizado correctamente');
                return $this->redirect(['action'=>'index']);
            }
    
            $this->Flash->error('Error al actualizar usuario');
        }
    
        $this->set(compact('user', 'roles'));
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
        $this->request->allowMethod(['post', 'delete']);

        // --- borrar ---
        $user = $this->Users->get($id);
    
        if ($this->Users->delete($user)) {
            $this->Flash->success('Usuario eliminado');
        } else {
            $this->Flash->error('Error al eliminar usuario');
        }
    
        // volver a la misma pagina/filtros si venia redirect
        $redirect = (string)$this->request->getQuery('redirect');
        if ($redirect !== '' && str_starts_with($redirect, '/')) {
            return $this->redirect($redirect);
        }
    
        return $this->redirect(['action' => 'index']);
    }
    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful login, renders view otherwise.
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);

        if ($this->request->is('get') && $this->request->getQuery('redirect')) {
            $this->Flash->error('Necesitas iniciar sesion para continuar.');
        }

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
        $this->Authentication->addUnauthenticatedActions(['login', 'forgotPassword', 'resetPassword']);
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
        $hasher = new DefaultPasswordHasher();
        $canSave = true;
        $passwordFields = ['current_password', 'new_password', 'confirm_password'];

        // Saca avatar del patch para que NUNCA intente guardarse como UploadedFile
        $file = $data['avatar'] ?? null;
        unset($data['avatar']);

        $currentPassword = trim((string)($data['current_password'] ?? ''));
        $newPassword = trim((string)($data['new_password'] ?? ''));
        $confirmPassword = trim((string)($data['confirm_password'] ?? ''));
        $wantsPasswordChange = $currentPassword !== '' || $newPassword !== '' || $confirmPassword !== '';

        foreach ($passwordFields as $passwordField) {
            unset($data[$passwordField]);
        }

        $fields = ['name', 'apellido_paterno', 'apellido_materno', 'email', 'usern'];

        if ($wantsPasswordChange) {
            if ($currentPassword === '' || !$hasher->check($currentPassword, (string)$user->password)) {
                $this->Flash->error('La contrasena actual es incorrecta.');
                $canSave = false;
            }

            if ($newPassword === '' || strlen($newPassword) < 8) {
                $this->Flash->error('La nueva contrasena debe tener al menos 8 caracteres.');
                $canSave = false;
            }

            if ($newPassword !== $confirmPassword) {
                $this->Flash->error('La confirmacion de contrasena no coincide.');
                $canSave = false;
            }

            if ($canSave) {
                $data['password'] = $newPassword;
                $fields[] = 'password';
            }
        }

        $user = $this->Users->patchEntity($user, $data, [
            'fields' => $fields
        ]);

        if (!$canSave) {
            $this->set(compact('user'));
            return;
        }

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
     public function forgotPassword()
{
    if ($this->request->is('post')) {
        $email = mb_strtolower(trim((string)$this->request->getData('email')), 'UTF-8');

        // Validación básica
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->Flash->success('Si tu correo electrónico se encuentra en nuestros registros, recibirás un enlace para restablecer tu contraseña.');
            return $this->redirect(['action' => 'login']);
        }

        // Búsqueda case-insensitive (funciona bien en MySQL)
        $user = $this->Users->find()
            ->where(['LOWER(Users.email) =' => $email])
            ->first();

        if ($user) {
            $token = Security::randomString(64);

            $passwordResetTokensTable = $this->fetchTable('PasswordResetTokens');
            $passwordResetTokensTable->deleteAll(['user_id' => $user->id]);

            $resetToken = $passwordResetTokensTable->newEntity([
                'user_id' => $user->id,
                'token' => hash('sha256', $token),
                'expires_at' => new FrozenTime('+1 hour'),
            ]);

            if ($passwordResetTokensTable->save($resetToken)) {
                $resetLink = Router::url(['controller' => 'Users', 'action' => 'resetPassword', $token], true);
                $this->getMailer('User')->send('passwordReset', [$user, $resetLink]);
            }
        }

        $this->Flash->success('Si tu correo electrónico se encuentra en nuestros registros, recibirás un enlace para restablecer tu contraseña.');
        return $this->redirect(['action' => 'login']);
    }
}

    public function resetPassword($token = null)
    {
        if (!$token) {
            $this->Flash->error('Token no válido o ausente.');
            return $this->redirect(['action' => 'login']);
        }

        $hashedToken = hash('sha256', $token);
        $passwordResetTokensTable = $this->fetchTable('PasswordResetTokens');
        $resetToken = $passwordResetTokensTable->find()
            ->where([
                'token' => $hashedToken,
                'expires_at >' => FrozenTime::now()
            ])
            ->contain(['Users'])
            ->first();

        if (!$resetToken || !$resetToken->user) {
            $this->Flash->error('El token de recuperación es inválido o ha expirado.');
            return $this->redirect(['action' => 'login']);
        }

        $user = $resetToken->user;

        if ($this->request->is(['post', 'put'])) {
            // patchEntity usará las reglas de validación de la entidad User
            // para verificar que la contraseña y su confirmación coinciden.
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
                // Eliminar el token usado
                $passwordResetTokensTable->delete($resetToken);
                $this->Flash->success('Tu contraseña ha sido restablecida. Ahora puedes iniciar sesión.');
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('No se pudo cambiar la contraseña. Por favor, intenta de nuevo.');
        }

        $this->set('user', $user);
    }
}
