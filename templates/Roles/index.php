<h1>Roles</h1>
<?= $this->Html->link('Agregar Rol', ['action'=>'add'], ['class'=>'btn btn-primary']) ?>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($roles as $role): ?>
        <tr>
            <td><?= $role->id ?></td>
            <td><?= h($role->name) ?></td>
            <td>
                <?= implode(', ', collection($role->permissions)->extract('description')->toList()) ?>
            </td>
            <td>
                <?= $this->Html->link('Ver', ['action'=>'view',$role->id]) ?>
                <?= $this->Html->link('Editar', ['action' => 'edit', $role->id], ['class' => 'btn btn-sm btn-warning']) ?>
                <?= $this->Form->postLink('Eliminar', ['action' => 'delete', $role->id], ['confirm' => '¿Estás seguro de que quieres eliminar este rol?', 'class' => 'btn btn-sm btn-danger']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
