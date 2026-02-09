<h1>Usuarios</h1>
<?= $this->Html->link('Agregar Usuario', ['action'=>'add'], ['class'=>'btn btn-primary']) ?>


<?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index']]) ?>
<div class="row g-2 mb-3">
    <div class="col">
        <?= $this->Form->control('name', ['label' => 'Nombre', 'value' => $this->request->getQuery('name')]) ?>
    </div>
    <div class="col">
        <?= $this->Form->control('apellido_paterno', ['label' => 'Apellido Paterno', 'value' => $this->request->getQuery('apellido_paterno')]) ?>
    </div>
    <div class="col">
        <?= $this->Form->control('apellido_materno', ['label' => 'Apellido Materno', 'value' => $this->request->getQuery('apellido_materno')]) ?>
    </div>
    <div class="col">
        <?= $this->Form->control('email', ['label' => 'Email', 'value' => $this->request->getQuery('email')]) ?>
    </div>
    <div class="col">
        <?= $this->Form->control('role_id', ['label' => 'Rol', 'options' => $roles, 'empty' => 'Todos', 'value' => $this->request->getQuery('role_id')]) ?>
    </div>
    <div class="col">
        <?= $this->Form->control('activo', [
            'type' => 'select',
            'label' => 'Estado',
            'options' => [1 => 'Activo', 0 => 'Inactivo'],
            'empty' => 'Todos',
            'value' => $this->request->getQuery('activo')
        ]) ?>
    </div>
    <div class="col align-self-end">
        <?= $this->Form->button('Buscar', ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link('Limpiar', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
<?= $this->Form->end() ?>

<table class="table">
<tr>
    <th>ID</th>
    <th>Nombre</th>
        <th>Apellido Paterno</th>
        <th>Apellido Materno</th>
    <th>Email</th>
    <th>Rol</th>
    <th>Status</th>
    <th>Acciones</th>
</tr>
<?php foreach($users as $user): ?>
<tr>
    <td><?= $user->id ?></td>
    <td><?= h($user->name) ?></td>
        <td><?= $user->apellido_paterno ?></td>
        <td><?= $user->apellido_materno ?></td>
    <td><?= h($user->email) ?></td>
    <td><?= h($user->role->name) ?></td>
    <td><?= $user->activo ? 'Activo' : 'Inactivo' ?></td>
    <td>
        <a href="#" 
   class="btn btn-sm btn-info view-user"
   data-id="<?= $user->id ?>">
   Ver
</a>|
        <?= $this->Html->link('Editar', ['action'=>'edit',$user->id]) ?> |
        <?= $this->Form->postLink('Eliminar', ['action'=>'delete',$user->id], ['confirm'=>'¿Seguro que deseas eliminarlo?']) ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<div class="modal fade" id="userModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="userModalBody">
        Cargando...
      </div>
    </div>
  </div>
</div>
<script>
document.querySelectorAll('.view-user').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();

        const userId = this.dataset.id;

        fetch('/users/view/' + userId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('userModalBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('userModal')).show();
        });
    });
});
</script>
