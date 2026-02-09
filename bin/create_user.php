<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/bootstrap.php';

use Cake\ORM\TableRegistry;

$usersTable = TableRegistry::getTableLocator()->get('Users');

$user = $usersTable->newEntity([
    'email' => 'administrador@test.com',
    'password' => '123456',
    'name' => 'Administrador',
    'role_id' => 1, // admin
]);

if ($usersTable->save($user)) {
    echo "Admin creado correctamente\n";
} else {
    print_r($user->getErrors());
}
