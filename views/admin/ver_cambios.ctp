<?php
$original = json_decode($edicion['EdicionPendiente']['datos_originales'], true);
$propuesto = json_decode($edicion['EdicionPendiente']['datos_propuestos'], true);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h3>Valores Originales</h3>
            <table class="table table-bordered">
                <?php foreach($original as $key => $value): ?>
                <tr>
                    <th><?php echo $key; ?></th>
                    <td><?php echo is_array($value) ? implode(', ', $value) : $value; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="col-md-6">
            <h3>Cambios Propuestos</h3>
            <table class="table table-bordered">
                <?php foreach($propuesto as $key => $value): ?>
                <tr>
                    <th><?php echo $key; ?></th>
                    <td style="<?php echo ($original[$key] != $value) ? 'background-color: #dff0d8;' : ''; ?>">
                        <?php echo is_array($value) ? implode(', ', $value) : $value; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <div class="text-center">
        <button class="btn btn-success" onclick="aprobarEdicion(<?php echo $edicion['EdicionPendiente']['id']; ?>)">
            Aprobar Cambios
        </button>
        <button class="btn btn-danger" onclick="rechazarEdicion(<?php echo $edicion['EdicionPendiente']['id']; ?>)">
            Rechazar Cambios
        </button>
    </div>
</div>