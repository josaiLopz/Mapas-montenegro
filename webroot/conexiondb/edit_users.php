<?php
session_start();
include 'Calculadora/db.php'; // Incluir el archivo de conexión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: Index.php");
    exit();
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];

// Consultar el usuario en la base de datos
$sql = "SELECT id, username, nombre FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_nombre = $_POST['nombre'];
    $new_password = $_POST['password'];

    // Hash de la nueva contraseña si se proporciona
    $hashedPassword = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : $user['password'];

    // Preparar y ejecutar la consulta de actualización
    $sql = "UPDATE users SET username = ?, nombre = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $new_username, $new_nombre, $hashedPassword, $user['id']);

    if ($stmt->execute()) {
        echo "Información actualizada exitosamente.";
        $_SESSION['username'] = $new_username; // Actualizar el nombre de usuario en la sesión
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow:#328bc5;
            max-width: 60%;
            margin-left: 20%;
        }
        .card-header {
            background-color: #328bc5;
            color: white;
            font-size: 1.5rem;
            text-align: center;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
         
        }
        .card-body {
            padding: 2rem;
        }
        .btn-primary {
            background-color: #328bc5;
            border: none;
        }
        .btn-primary:hover {
            background-color: #328bc5;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-control {
            border-radius: 0.25rem;
            box-shadow: none;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
            border-color: #328bc5;
        }
        .btn-link {
            color: #007bff;
            text-decoration: none;
        }
        .btn-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Editar Información de Usuario
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Nombre de Usuario:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña Nueva</label>
                        <input type="password" id="password" name="password" placeholder="Dejar en blanco si no desea cambiarla" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Información</button>
                </form>
                <div class="mt-3">
                    <a href="Calculadora/Presentacion/MDAPrimaria.php" class="btn btn-link">Volver a las calculadoras</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
