<h1>Permisos</h1>
<?= $this->Html->link('Agregar Permiso', ['action'=>'add'], ['class'=>'btn btn-primary']) ?>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Controlador</th>
            <th>Acción</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($permissions as $permission): ?>
        <tr>
            <td><?= $permission->id ?></td>
            <td><?= h($permission->controller) ?></td>
            <td><?= h($permission->action) ?></td>
            <td><?= h($permission->description) ?></td>
            <td>
                <?= $this->Html->link('Ver', ['action'=>'view',$permission->id]) ?>
                <?= $this->Html->link('Editar', ['action' => 'edit', $permission->id], ['class' => 'btn btn-sm btn-warning']) ?>
                <?= $this->Form->postLink('Eliminar', ['action' => 'delete', $permission->id], ['confirm' => '¿Estás seguro de que quieres eliminar este permiso?', 'class' => 'btn btn-sm btn-danger']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
