<?php
include 'Calculadora/db.php'; // Incluir el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el ID del formulario
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Preparar la consulta de eliminación
    $sql = "DELETE FROM metas WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        // Redirigir de vuelta con éxito
        header("Location: your_page.php?page=" . $_GET['page']);
    } else {
        die("Error al eliminar el registro: " . $stmt->error);
    }
    
    $stmt->close();
}
$conn->close();
?>
