<p>Bienvenido <?= h($user->name) ?></p>

<p>
    <?= $this->Html->link('Cerrar sesión', ['controller' => 'Users', 'action' => 'logout']) ?>
</p>

