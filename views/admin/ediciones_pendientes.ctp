<div class="container-fluid">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Escuela</th>
                <th>Solicitante</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($ediciones as $ed): ?>
            <tr>
                <td><?php echo $ed['Base']['nombre']; ?></td>
                <td><?php echo $ed['Usuario']['nombre']; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($ed['EdicionPendiente']['created'])); ?></td>
                <td>
                    <button class="btn btn-sm btn-info" 
                            onclick="mostrarCambios(<?php echo $ed['EdicionPendiente']['id']; ?>)">
                        Ver cambios
                    </button>
                    <button class="btn btn-sm btn-success" 
                            onclick="aprobarEdicion(<?php echo $ed['EdicionPendiente']['id']; ?>)">
                        Aprobar
                    </button>
                    <button class="btn btn-sm btn-danger" 
                            onclick="rechazarEdicion(<?php echo $ed['EdicionPendiente']['id']; ?>)">
                        Rechazar
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function mostrarCambios(id) {
    pop_sun = sunrise({
        target: '/Bases/ver_cambios/' + id,
        ajax: true,
        width: '70%',
        height: '80%',
        title: 'Detalle de Cambios'
    });
}
</script>