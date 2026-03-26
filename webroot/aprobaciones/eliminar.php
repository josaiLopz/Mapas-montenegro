<?php
include('conexion.php');

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: /Bases?mensaje=" . urlencode("ID no válido") . "&tipo=error");
    exit;
}

$sql = "DELETE FROM bases_historial WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: /Bases?mensaje=" . urlencode("Registro eliminado con éxito") . "&tipo=success");
} else {
    header("Location: /Bases?mensaje=" . urlencode("Error al eliminar: " . $conn->error) . "&tipo=error");
}

$conn->close();
?>
