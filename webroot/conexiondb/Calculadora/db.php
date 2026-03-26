<?php
$servername = "localhost";
$username = "sistemas"; // Cambia esto si tu usuario es diferente
$password = "montenegro"; // Cambia esto si tienes una contraseña
$dbname = "calculadora";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
