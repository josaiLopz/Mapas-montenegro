<?php
include 'Calculadora/db.php'; // Incluir el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $meta = isset($_POST['meta']) ? $_POST['meta'] : '';
    $meta_plus = isset($_POST['meta_plus']) ? $_POST['meta_plus'] : '';
    $alumnado = isset($_POST['alumnado']) ? $_POST['alumnado'] : '';
    $descuento_base = isset($_POST['descuento_base']) ? $_POST['descuento_base'] : '';
    $antiguedad = isset($_POST['antiguedad']) ? $_POST['antiguedad'] : '';
    $meta_preescolar = isset($_POST['meta_preescolar']) ? $_POST['meta_preescolar'] : '';
    $meta_asomate = isset($_POST['meta_asomate']) ? $_POST['meta_asomate'] : '';
    $meta_rap = isset($_POST['meta_rap']) ? $_POST['meta_rap'] : '';
    $meta_escudaria = isset($_POST['meta_escudaria']) ? $_POST['meta_escudaria'] : '';

    // Verifica que se hayan recibido todos los datos necesarios
    if (!$id || !$meta || !$meta_plus || !$alumnado || !$descuento_base || !$antiguedad || !$meta_preescolar || !$meta_asomate || !$meta_rap || !$meta_escudaria) {
        die("Error: Datos faltantes.");
    }

    // Preparar la consulta de actualización
    $sql = "UPDATE metas 
            SET meta = ?, meta_plus = ?, alumnado = ?, descuento_base = ?, antiguedad = ?, meta_preescolar = ?, meta_asomate = ?, meta_rap = ?, meta_escudaria = ?
            WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sssssssssi', $meta, $meta_plus, $alumnado, $descuento_base, $antiguedad, $meta_preescolar, $meta_asomate, $meta_rap, $meta_escudaria, $id);

        if ($stmt->execute()) {
            // Redirigir de vuelta con éxito
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            header("Location: welcome.php?page=" . $page);
            exit();
            
        } else {
            die("Error al actualizar el registro: " . $stmt->error);
        }
    } else {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->close();
}
$conn->close();
?>
