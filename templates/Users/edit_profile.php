<h2> Editar Perfil</h2>

<?= $this->Form->create($user, ['type' => 'file']) ?>
    <?= $this->Form->control('name') ?>
    <?= $this->Form->control('email') ?>
    <?= $this->Form->control('avatar', ['type' => 'file']) ?>
    <?= $this->Form->button('Guardar cambios') ?>
    <?= $this->Form->end() ?>