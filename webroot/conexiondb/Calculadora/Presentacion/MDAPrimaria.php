
<?php
session_start();
include '../db.php'; // Incluir el archivo de conexión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];

// Consultar el id del usuario
$sql = "SELECT id, nombre FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userName = $user['nombre'];
} else {
    echo "Usuario no encontrado.";
    exit();
}

// Consultar las metas del usuario
$sql = "SELECT meta, meta_plus, alumnado, descuento_base, antiguedad, meta_preescolar, meta_asomate, meta_rap, meta_escudaria FROM metas WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$metas = $stmt->get_result();

// Consultar el alumnado
$sqlAlumnado = "SELECT alumnado FROM metas WHERE user_id = ?";
$stmtAlumnado = $conn->prepare($sqlAlumnado);
$stmtAlumnado->bind_param("i", $userId);
$stmtAlumnado->execute();
$alumnado = $stmtAlumnado->get_result();

// Consultar el alumnado
$sqlAntiguedad = "SELECT antiguedad FROM metas WHERE user_id = ?";
$stmtAntiguedad = $conn->prepare($sqlAntiguedad);
$stmtAntiguedad->bind_param("i", $userId);
$stmtAntiguedad->execute();
$antiguedad = $stmtAntiguedad->get_result();
// Consultar el descuento base
$sqlDescuento = "SELECT descuento_base FROM metas WHERE user_id = ?";
$stmtDescuento = $conn->prepare($sqlDescuento);
$stmtDescuento->bind_param("i", $userId);
$stmtDescuento->execute();
$descuentos = $stmtDescuento->get_result();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de descuentos MDA Primaria</title>
    <link rel="icon" href="blog.png">
    <link rel="stylesheet" href="../css.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&display=swap">
    <link rel="stylesheet" href="../css/MDAPrimaria.css">
    <style>
       .malos{
        margin-left: 80%;
        color:#ffffff;
      }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <a href="MDAPrimaria.html" class="logo"><img style="max-width: 10%;" src="logoMontenegro.png"></a>
            <ul class="nav-menu">
                <li><a href="MDAPrimaria.php">MDA Primaria</a></li>
                <li><a href="../MDApreescolar.php">MDA Preescolar</a></li>
                <li><a href="../Asomate.php">Asomate</a></li>
                <li><a href="../RAP.php">RAP</a></li>
                <li><a href="../Secundaria.php">Open Up</a></li>
                <li><a href="../Open_up.php">Serie Entorno</a></li>
            </ul>
            <a href="../../edit_users.php" class="malos" title="Editar perfil" style="margin-right: 20px;;">
                Editar Perfil
            </a>
            <a href="../../Index.php" class="malos1" title="Cerrar sesión" style="color:#ffffff;">
                Cerrar sesión </a>
        </nav>
    </header>
    <br><br><br><br><br><br><br>
    <div class="container">
        <div class="color-row left"></div>
        <div class="label-input-container">
        </div>
        <div class="label-input-container">
            <h1><img src="../portadas/CALCULADORA-DE-DESCUENTOS_Logo_MDAPrimaria.png" alt="" style="max-width: 70%;"></h1>        
        </div>
        <div>
        <h2 style="color:#1c1c1c;margin-left:10%;"> <?php echo htmlspecialchars($userName); ?>!</h2>
        </div>
        <br>
        <div class="label-input-container">
            <label for="precioBase">PRECIO BASE MAESTRO ($):</label>
            <input type="number" id="precioBase" value="355.00" readonly class="input-half">
        </div>
        <?php
        if ($metas->num_rows > 0) {
                while ($row = $metas->fetch_assoc()) {
               echo '<div class="label-input-container1">';
               echo '<label for="metaFirmada' . htmlspecialchars($row['id']) . '">META FIRMADA 2024:</label>';

               echo     '<input type="number" id="metaFirmada' . htmlspecialchars($row['id']) . '" name="meta[]" value="' . htmlspecialchars($row['meta']) . '" oninput="calcularDescuentos()" style="background-color: #f5c8c8;">';
                echo    '<label class="metapl' . htmlspecialchars($row['id']) . '" for="metaPlus">META PLUS:</label>';
                echo    '<input type="number" id="metaPlus' . htmlspecialchars($row['id']) . '" name="meta_plus[]" value="' . htmlspecialchars($row['meta_plus']) . '" oninput="calcularDescuentos()" style="background-color: #f5c8c8;">';
                echo '</div>';
            }
        } else {
            echo '<p>No hay metas registradas.</p>';
        }
        ?>
        <br>
        <div class="label-input-container">
        <label for="descuentoSelect">DESCUENTO BASE EN CONTRATO:</label>
        <span id="descuentoSelect">0</span>
        <!-- <select id="descuentoSelect" onchange="calcularDescuentos()">
            <?php
            if ($descuentos->num_rows > 0) {
                while ($row = $descuentos->fetch_assoc()) {
                    $descuentoBase = $row['descuento_base'];
                    echo '<option value="' . htmlspecialchars($descuentoBase) . '">' . htmlspecialchars($descuentoBase * 100) . '%</option>';
                }
            } else {
                echo '<option value="0.20">20%</option>'; 
            }
            ?>
        </select> -->
    </div>
        <!-- <div class="label-input-container">
            <label for="descuentoSelect">DESCUENTO BASE EN CONTRATO:</label>
            <select id="descuentoSelect" onchange="calcularDescuentos()">
                <option value="0.20">0</option>
                <option value="0.20">20%</option>
                <option value="0.25">25%</option>
                <option value="0.30">30%</option>
                <option value="0.35">35%</option>
            </select>
        </div> -->
        
        <div class="label-input-container">
            <label for="campo1">VENTA ANTICIPADA EDICIÓN 2024:</label>
            <input type="number" id="campo1" value="" oninput="calcularDescuentos()" class="input-half">
        </div>
        <div class="label-input-container">
            <label for="campo2">UNIDADES COMPRADAS EDICIÓN 2024:</label>
            <input type="number" id="campo2" value="" oninput="calcularDescuentos()">
        </div>
        <div class="label-input-container" hidden>
            <label for="campo3">UNIDADES COMPRADAS EDICIÓN 2023:</label>
            <input type="number" id="campo3" value="" oninput="calcularDescuentos()">
        </div>
        <div class="label-input-container" hidden>
            <label for="campo4" hidden>UNIDADES 2024 COMPRADAS A PRECIO ESPECIAL: </label>
            <input type="number" id="campo4" value="block" oninput="calcularDescuentos()" hidden>
        </div>

        <p>VENTA TOTAL: <span id="resultadoVenta">0</span></p>

        <div class="label-input-container1">
            <label for="campo5">VENTA RAP:</label>
            <input type="number" id="campo5" value="" oninput="calcularDescuentos()">
            <label class="metapl" for="campo6">VENTA LUDOSAPIENS:</label>
            <input type="number" id="campo6" value="" oninput="calcularDescuentos()">
        </div>
        <br>
        <!-- <div class="label-input-container">
            <label for="campo6">VENTA LUDOSAPIENS:</label>
            <input type="number" id="campo6" value="" oninput="calcularDescuentos()" style="margin-left: 41%;">
        </div> -->
        <?php
            if ($metas->num_rows > 0) {
                while ($row = $alumnado->fetch_assoc()) {
                echo '<div class="label-input-container" hidden>';
                echo '<label for="alumnado' . htmlspecialchars($row['id']) . '" hidden>ALUMNADO:</label>';
                echo '<input type="number" id="alumnado' . htmlspecialchars($row['id']) . '" name="alumnado[]" value="' . htmlspecialchars($row['alumnado']) . '"  oninput="calcularDescuentos()" style="background-color: #f5c8c8;" hidden>';
                echo '</div>';
            }
        } else {
            echo '<p>No hay metas registradas.</p>';
        }
        ?>
        <p hidden>PORCENTAJE DE COBERTURA:
            <span id="resultadoCobertura">0</span>
        </p>
        <p style="font-size: 14px;" hidden>*Sujeto a cumplimiento de meta</p>
        <?php
        if ($metas->num_rows > 0){
            while ($row = $antiguedad->fetch_assoc())  {
               echo '<div class="label-input-container" hidden>';
               echo      '<label for="antiguedad' . htmlspecialchars($row['id']) . '" hidden>ANTIGÜEDAD (EN AÑOS):</label>';
               echo      '<input type="number" id="antiguedad' . htmlspecialchars($row['id']) . '" name="antiguedad[]" value="' . htmlspecialchars($row['antiguedad']) . '" oninput="calcularDescuentos()" style="background-color: #f5c8c8;" hidden>';
               echo '</div>';
            }
        }else {
            echo '<p hidden>No tiene antiguedad</p>';
        }
        ?>
        
        <div class="label-input-container" >
            <label for="ventaAnoPasado" hidden>VENTA AÑO PASADO:</label>
            <input type="number" id="ventaAnoPasado" value="" oninput="calcularDescuentos()" hidden>
        </div>

        <p hidden>CRECIMIENTO DE VENTAS:<span id="resultadoPorcentaje">0</span>
        </p>
        <p style="font-size: 14px;" hidden>*Sujeto a cumplimiento de meta</p>

        <div class="label-input-container">
            <label for="pagoPuntual">PAGO PUNTUAL:</label>
            <input type="checkbox" id="pagoPuntual" onchange="calcularDescuentos()">
        </div>

        <div class="label-input-container">
            <label for="descuentoExtra">PRONTO PAGO:</label>
            <input type="checkbox" id="descuentoExtra" onchange="calcularDescuentos()">
        </div>
        <div class="label-input-container">
            <label for="devolucion">NO DEVOLUCIÓN:</label>
            <input type="checkbox" id="devolucion" onchange="calcularDescuentos()">
        </div>
        <p style="font-size: 14px;">*Sujeto a cumplimiento de meta</p>

        <br><br>
        <table id="miTabla" class="table">
            <thead>
                <tr style="background-color:#3C8DBC; color:#ffffff;">
                    <th style="background-color:#3C8DBC;">Descripción</th>
                    <th style="background-color: #3C8DBC">Porcentaje de Descuento</th>
                    <th style="background-color: #3C8DBC">Descuento Aplicado</th>
                    <th style="background-color: #3C8DBC;">Resultado</th>
                    <th style="background-color:#3C8DBC;"><span id="unidadesPrecioRegular"></span></th>
                </tr>
            </thead>
            <tbody id="tablaDescuentos">
            </tbody>
        </table>
        <p style="font-size: 14px;">*Descuento base sujeto a meta firmada</p>
    </div>
    <footer>
        <p>Este es el pie de página</p>
        <script src="../js/MDAPrimaria.js"></script>

    </footer>
    <script>

    </script>
</body>

</html>