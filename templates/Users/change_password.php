<h2> Cambiar Contraseña</h2>

<?= $this->Form->create() ?>
<?= $this->Form->control('currentPassword', [
'type' => 'password',
'label' => 'Contraseña actual'
]) ?>
<?= $this->Form->control('new_password', [
'type' => 'password',
'label' => 'Nueva contraseña'
]) ?>

<?= $this->Form->button('Cambiar contraseña') ?>
<?= $this->Form->end() ?>