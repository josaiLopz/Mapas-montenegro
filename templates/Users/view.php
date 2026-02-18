<h4>Detalle del Usuario</h4>

<table class="table table-sm">
    <tr>
        <th>ID</th>
        <td><?= h($user->id) ?></td>
    </tr>
    <tr>
        <th>Nombre</th>
        <td><?= h($user->name) ?></td>
    </tr>
    <tr>
        <th>Apellido Paterno</th>
        <td><?= h($user->apellido_paterno) ?></td>
    </tr>
    <tr>
        <th>Apellido Materno</th>
        <td><?= h($user->apellido_materno) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= h($user->email) ?></td>
    </tr>
    <tr>
        <th>Usuario</th>
        <td><?= h($user->usern) ?></td>
    </tr>
    <tr>
        <th>Rol</th>
        <td><?= h($user->role->name ?? 'Sin rol') ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td><?= $user->activo ? 'Activo' : 'Inactivo' ?></td>
    </tr>
</table>
