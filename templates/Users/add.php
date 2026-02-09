<h2>Agregar Usuario</h2>

<?= $this->Form->create($user) ?>

<?= $this->Form->control('name', ['label' => 'Nombre']) ?>
<?= $this->Form->control('apellido_paterno', ['label' => 'Apellido Paterno', 'required' => true]) ?>
<?= $this->Form->control('apellido_materno', ['label' => 'Apellido Materno', 'required' => true]) ?>
<?= $this->Form->control('email') ?>
<?= $this->Form->control('password') ?>
<?= $this->Form->control('role_id', [
    'label' => 'Rol',
    'options' => $roles
]) ?>
<?= $this->Form->control('activo', ['type' => 'checkbox', 'label' => 'Activo']) ?>


<?= $this->Form->button('Guardar') ?>

<?= $this->Form->end() ?>
