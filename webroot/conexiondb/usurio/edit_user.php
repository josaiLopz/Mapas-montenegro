<?php
include '../db.php'; // Incluir el archivo de conexión

// Obtener el ID del usuario a editar
if (!isset($_GET['user_id'])) {
    echo "ID de usuario no especificado.";
    exit();
}

$user_id = $_GET['user_id'];

// Consultar los datos del usuario
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "Usuario no encontrado.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    <form method="post" action="update_user.php">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
        <br>
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password">
        <br>
        <input type="submit" value="Actualizar">
    </form>
    <a href="list_users.php">Volver a la lista de usuarios</a>
</body>
</html>
