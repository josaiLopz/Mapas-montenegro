<?php
header('Content-Type: text/html; charset=UTF-8');
include('conexion.php');

$conn->set_charset("utf8");
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null;
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'info';

$sql = "SELECT * FROM bases_historial";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bases Historial</title>
    <style>
        table, th, td {
            border: 0.2px ; /* Bordes negros */
        }
        
        th, td {
            padding: 10px;
            text-align: center; /* Texto centrado */
            vertical-align: middle; /* Alineación vertical centrada */
        }
        
        th {
            background-color:rgba(242, 242, 242, 0.77); /* Fondo gris claro para cabeceras */
            font-weight: bold;
        }
    </style>
     
</head>
<body>

    <?php if ($mensaje): ?>
        <div class="mensaje <?= htmlspecialchars($tipo) ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <?php if ($resultado->num_rows > 0): ?>
    <table>
        <tr>
           <th>ID Original</th><th>Nombre</th><th>CCT</th><th>Turno</th>
            <th>Tipo</th><th>Sector</th><th>Estado</th><th>Municipio</th><th>Alumnos</th>
            <th>Acciones</th>
        </tr>
        <?php while($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $fila['id_original'] ?></td>
            <td><?= $fila['nombre'] ?></td>
            <td><?= $fila['cct'] ?></td>
            <td><?= $fila['turno'] ?></td>
            <td><?= $fila['tipo'] ?></td>
            <td><?= htmlspecialchars($fila['sector'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= $fila['estado'] ?></td>
            <td><?= $fila['municipio'] ?></td>
            <td><?= $fila['alumnos'] ?></td>
            
            <td>
                <a class="boton eliminar" href="/aprobaciones/eliminar.php?id=<?= $fila['id'] ?>">Rechazar</a>
                <a class="boton mover" href="/aprobaciones/mover.php?id=<?= $fila['id'] ?>">Autorizar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>No hay registros por autorizar.</p>
    <?php endif; ?>

</body>
</html>
