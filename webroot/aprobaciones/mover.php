<?php
include('conexion.php');

function redir($mensaje, $tipo = 'info') {
    header("Location: /Bases?mensaje=" . urlencode($mensaje) . "&tipo=$tipo");
    exit;
}

$id_historial = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id_historial) redir("ID no válido", "error");

$sql = "SELECT * FROM bases_historial WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_historial);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) redir("Registro no encontrado", "error");

$row = $result->fetch_assoc();
$id_original = $row['id_original'];

$campos_comunes = [
    'nombre', 'estado', 'municipio', 'estado_n', 'municipio_n',
    'tipo', 'sector', 'turno', 'cct', 'lat', 'lng', 'alumnos',
    'id_distribuidor', 'grupos', 'nombre_contacto', 'telefono_contacto', 'correo_contacto', 'notas','estatus', 'verificada'
];

$sql_check = "SELECT id FROM bases WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id_original);
$stmt_check->execute();
$res_check = $stmt_check->get_result();

if ($res_check->num_rows !== 1) redir("No existe el ID $id_original en la tabla bases", "error");

$campos_update = [];
$valores = [];

foreach ($campos_comunes as $campo) {
    if (!empty($row[$campo])) {
        $campos_update[] = "`$campo` = ?";
        $valores[] = $row[$campo];
    }
}

if (!empty($campos_update)) {
    $sql_update = "UPDATE bases SET " . implode(", ", $campos_update) . " WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $valores[] = $id_original;
    $tipos = str_repeat("s", count($valores) - 1) . "i";
    $stmt_update->bind_param($tipos, ...$valores);

    if (!$stmt_update->execute()) {
        redir("Error al actualizar: " . $stmt_update->error, "error");
    }

    // Registro en log
    $log = "[" . date("Y-m-d H:i:s") . "] MOVIDO ID: $id_historial → $id_original | Campos: " . implode(", ", $campos_update) . "\n";
    file_put_contents("mover.log", $log, FILE_APPEND);
}

// Eliminar de historial
$sql_delete = "DELETE FROM bases_historial WHERE id = ?";
$stmt_del = $conn->prepare($sql_delete);
$stmt_del->bind_param("i", $id_historial);
$stmt_del->execute();

redir("Registro movido correctamente", "success");
?>
