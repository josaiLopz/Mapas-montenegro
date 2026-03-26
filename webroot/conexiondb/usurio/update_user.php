<?php
include '../db.php'; // Incluir el archivo de conexión

// Obtener los datos del formulario
$user_id = $_POST['user_id'];
$nombre = $_POST['nombre'];
$username = $_POST['username'];
$password = $_POST['password'];

// Preparar la consulta de actualización
$sql = "UPDATE users SET nombre = ?, username = ?" . ($password ? ", password = ?" : "") . " WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("sssi", $nombre, $username, $hashedPassword, $user_id);
} else {
    $stmt->bind_param("ssi", $nombre, $username, $user_id);
}

if ($stmt->execute()) {
    echo "Usuario actualizado exitosamente.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
<a href="List_users.php">Volver a la lista de usuarios</a>
