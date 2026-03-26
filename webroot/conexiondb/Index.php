<?php
session_start();
include 'Calculadora/db.php'; // Incluir el archivo de conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar el usuario en la base de datos
    $sql = "SELECT * FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si el usuario existe y si la contraseña es correcta
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Contraseña correcta, iniciar sesión
                $_SESSION['username'] = $username;

                // Redirigir a la página de bienvenida
                header("Location: Calculadora/MDApreescolar.php");
                exit(); // Asegúrate de detener la ejecución después de redirigir
            } else {
                echo "<div class='alert alert-danger' role='alert'>Contraseña incorrecta.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Nombre de usuario no encontrado.</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error preparando la consulta: " . $conn->error . "</div>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #ffffff;
            text-align: center;
        }
        .login-container img {
            width: 300px;
            margin-bottom: 20px;
        }
        .login-container h1 {
            margin-bottom: 20px;
            color: #000;
            font-size: 1.5rem;
        }
        .input-group-text {
            background-color: #328bc5;
            color: #ffffff;
            border: none;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #328bc5;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #328bc5;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="img/logo.png" alt="Logo" > 
        <h1>Calculadora de Descuentos</h1>
       <p style="color:#f39c12;">Si no cuenta con los accesos comuniquese con su asesor o cuenta con la opción de usar la calculadora general.</p>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Nombre de Usuario:</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            <br> <br>
            <a href="http://pedidosmontenegro.montenegroeditores.com.mx/Calculadora/Presentacion/MDAPrimaria.html">Calculadora general</a>
        </form>
    </div>

    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Incluir FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
