<style>
    /* Estilos para la tabla de historial */
.historial-general {
    padding: 20px;
    font-family: Arial, sans-serif;
}

.table-container {
    overflow-x: auto;
    margin: 20px 0;
}

.historial-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9em;
}

.historial-table th, 
.historial-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.historial-table th {
    background-color: #337ab7;
    color: white;
    position: sticky;
    top: 0;
}

.historial-table tr:hover {
    background-color: #f5f5f5;
}

.historial-table a {
    color: #337ab7;
    text-decoration: none;
}

.historial-table a:hover {
    text-decoration: underline;
}

.button {
    display: inline-block;
    padding: 10px 15px;
    background-color: #5cb85c;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 20px;
}

.button:hover {
    background-color: #4cae4c;
}
</style>
<div class="historial-general">
    <h2>Historial General de Modificaciones</h2>
    
    <div class="table-container">
        <table class="historial-table">
            <thead>
                <tr>
                    <th>ID Historial</th>
                    <th>Escuela ID</th>
                    <th>Nombre</th>
                    <th>CCT</th>
                    <th>Tipo</th>
                    <th>Sector</th>
                    <th>Estado</th>
                    <th>Municipio</th>
                    <th>Alumnos</th>
                    <th>Distribuidor</th>
                    <th>Modificado por</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $registro): ?>
                <tr>
                    <td><?php echo $registro['id']; ?></td>
                    <td>
                        <?php echo $this->Html->link(
                            $registro['id_original'],
                            array('action' => 'edit', $registro['id_original']),
                            array('title' => 'Editar esta escuela')
                        ); ?>
                    </td>
                    <td><?php echo utf8_encode($registro['nombre']); ?></td>
                    <td><?php echo $registro['cct']; ?></td>
                    <td><?php echo $registro['tipo']; ?></td>
                    <td><?php echo utf8_encode($registro['sector']); ?></td>
                    <td><?php echo utf8_encode($registro['estado_n']); ?></td>
                    <td><?php echo utf8_encode($registro['municipio_n']); ?></td>
                    <td><?php echo $registro['alumnos']; ?></td>
                    <td><?php echo $registro['id_distribuidor']; ?></td>
                    <td>
                        <?php echo isset($usuarios[$registro['usuario_modificacion']]) 
                            ? $usuarios[$registro['usuario_modificacion']] 
                            : 'ID '.$registro['usuario_modificacion']; ?>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($registro['fecha_modificacion'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="actions">
        <?php echo $this->Html->link(
            'Volver al listado principal',
            array('action' => 'index'),
            array('class' => 'button')
        ); ?>
    </div>
</div>