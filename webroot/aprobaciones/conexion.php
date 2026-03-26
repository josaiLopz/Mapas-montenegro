<?php
$host = "localhost"; // Cambia si es otro host
$usuario = "sistemas";
$contrasena = "montenegro";
$bd = "prueba"; // aunque accederemos a dos bases, aquí inicia con esta
$puerto = 3306;

$conn = new mysqli($host, $usuario, $contrasena, $bd, $puerto);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
