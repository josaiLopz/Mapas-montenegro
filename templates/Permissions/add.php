<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Permission $permission
 */
?>

<h1>Agregar Permiso</h1>

<?= $this->Form->create($permission) ?>

<fieldset>
    <legend>Datos del Permiso</legend>

    <?= $this->Form->control('controller', [
        'label' => 'Controller',
        'required' => true,
        'placeholder' => 'Users, Roles, Permissions...'
    ]) ?>

    <?= $this->Form->control('action', [
        'label' => 'Action',
        'required' => true,
        'placeholder' => 'index, add, edit, delete...'
    ]) ?>

    <?= $this->Form->control('description', [
        'label' => 'Descripción',
        'required' => true
    ]) ?>
</fieldset>

<?= $this->Form->button('Guardar Permiso', ['class' => 'button']) ?>
<?= $this->Html->link('Cancelar', ['action' => 'index'], ['class' => 'button button-outline']) ?>

<?= $this->Form->end() ?>
