<h2>Mi Perfil</h2>
<img src="/img/avatars/<?= h($user->avatar ?? 'default.png') ?>" width="120">
<p><b>Nombre:</b> <?= h($user->name) ?></p>
<p><b>Email:</b> <?= h($user->email) ?></p>
<p><b>Apellido Paterno:</b> <?= h($user->apellido_paterno)?></p>
<p><b>Apellido Materno:</b> <?= h($user->apellido_materno) ?></p>

<p>
    <?= $this->Html->link('Editar perfil', ['action' => 'editProfile']) ?>
</p>
<p>
    <?= $this->Html->link('Cambiar contraseña', ['action' => 'changePassword']) ?>
</p>

<?php
$permissions = [];

if (!empty($user->role) && !empty($user->role->permissions)) {
    $permissions = collection($user->role->permissions)
        ->map(fn($p) => $p->controller . ':' . $p->action)
        ->toList();
}
?>
