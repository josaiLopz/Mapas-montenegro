<?php
include '../db.php'; // Incluir el archivo de conexión

// Consultar todos los usuarios en la base de datos
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Usuarios</title>
</head>
<body>
    <h1>Lista de Usuarios</h1>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Nombre de Usuario</th>
            <th>Acción</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td><a href='edit_user.php?user_id=" . urlencode($row['id']) . "'>Editar</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay usuarios registrados.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
