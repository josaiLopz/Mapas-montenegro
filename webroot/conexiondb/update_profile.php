<?php
include 'Calculadora/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    
    // Construir la consulta SQL para actualizar
    $sql = "UPDATE users SET nombre='$nombre'";
    
    // Solo añadir la parte de la contraseña si no está vacía
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql .= ", password='$hashed_password'";
    }
    
    // Añadir la condición WHERE para la actualización
    $sql .= " WHERE id=$id";
    
    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        header("Location: edit_users_sin.php");  // Redirige a la lista de usuarios después de la actualización
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <style>
        .readonly {
            background-color: #f0f0f0;  /* Color de fondo para indicar que es solo lectura */
            border: 1px solid #ddd;     /* Borde para el campo solo lectura */
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Editar Usuario</h1>
    <form action="update_profile.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
        <br>
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" value="<?php echo htmlspecialchars($user['username']); ?>" readonly class="readonly">
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password">
        <br>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
