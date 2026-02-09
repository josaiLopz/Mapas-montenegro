<h1>Agregar Rol</h1>

<?= $this->Form->create($role) ?>

<fieldset>
    <legend>Información del Rol</legend>

    <?= $this->Form->control('name', [
        'label' => 'Nombre del Rol',
        'required' => true
    ]) ?>

    <?= $this->Form->control('description', [
        'label' => 'Descripción del Rol',
        'required' => true
    ]) ?>
</fieldset>

<fieldset>
    <legend>Permisos</legend>

    <?php foreach ($permissionsGrouped as $controller => $permissions): ?>
        <div style="margin-bottom:15px">
            <strong><?= h($controller) ?></strong>

            <div style="margin-left:20px">
                <?php foreach ($permissions as $permission): ?>
                    <label style="display:block">
                        <?= $this->Form->checkbox(
                            'permissions._ids[]',
                            [
                                'value' => $permission->id,
                                'checked' => in_array(
                                    $permission->id,
                                    $role->permissions ? collection($role->permissions)->extract('id')->toList() : []
                                )
                            ]
                        ) ?>
                        <?= h($permission->action) ?>
                        <small style="color:#777">
                            — <?= h($permission->description) ?>
                        </small>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

</fieldset>

<?= $this->Form->button('Guardar Rol') ?>
<?= $this->Html->link('Cancelar', ['action' => 'index'], ['class' => 'button button-outline']) ?>

<?= $this->Form->end() ?>
