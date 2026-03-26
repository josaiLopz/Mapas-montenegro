<?php
$host = 'localhost'; // o la IP de tu servidor de BD
$db   = 'mapas_new';
$user = 'sistemas';
$pass = 'montenegro';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "¡Conexión a la base de datos mapas_new exitosa!";
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}