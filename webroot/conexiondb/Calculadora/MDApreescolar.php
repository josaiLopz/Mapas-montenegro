<?php
session_start();
include 'db.php'; // Incluir el archivo de conexión

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDA Preescolar</title>
    <link rel="icon" href="presentacion/blog.png">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&display=swap">
    <link rel="stylesheet" href="css/MDAPreescolar.css">

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
            <a href="Presentacion/MDAPrimaria.php" class="logo"><img style="max-width: 10%;"
                    src="Presentacion/logoMontenegro.png"></a>
            <ul class="nav-menu">
                <li><a href="Presentacion/MDAPrimaria.php">MDA Primaria</a></li>
                <li><a href="MDApreescolar.php">MDA Preescolar</a></li>
                <li><a href="Asomate.php">Asomate</a></li>
                <li><a href="RAP.php">RAP</a></li>
                <li><a href="Secundaria.php">Open Up</a></li>
                <li><a href="Open_up.php">Serie Entorno</a></li>
            </ul>
            <!-- <a href="http://pedidosmontenegro.montenegroeditores.com.mx/index.php" class="regresar-boton">Regresar</a> -->
            <a href="../edit_users.php" class="malos" title="Editar perfil" style="margin-right: 20px;;">
                Editar Perfil
            </a>
            <a href="../Index.php" class="malos1" title="Cerrar sesión" style="color:#ffffff;">
                Cerrar sesión </a>
        </nav>
    </header>
    <br><br><br><br><br><br><br>
        <div class="container">
        <div class="color-row left"></div>
        <div class="label-input-container">
        </div>
        <div class="label-input-container">
            <h1><img src="portadas/CALCULADORA-DE-DESCUENTOS_Logo_MDAPreescolar.png" alt="" style="max-width: 70%;"></h1>
            <h2 style="color:#1c1c1c;">Bienvenido, <?php echo htmlspecialchars($userName); ?>!</h2>
        </div><br>
        <div class="input-container">
            <label for="precioBase">PRECIO BASE MAESTRO $</label>
            <input type="number" id="precioBase" value="340.00" readonly>
        </div>
        <?php
            if ($metas->num_rows > 0) {
                while ($row = $metas->fetch_assoc()) {
                    echo '<div class="input-container">';
                    echo '<label for="metaFirmada' . htmlspecialchars($row['id']) . '">META :</label>';
                    // Coloca el valor de PHP dentro del atributo value del input
                    echo '<input type="number" id="metaFirmada' . htmlspecialchars($row['id']) . '" name="meta_preescolar[]" value="' . htmlspecialchars($row['meta_preescolar']) . '" oninput="calcularDescuentos()" style="background-color: #f5c8c8;">';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay metas registradas.</p>';
            }
            ?>
        <!-- <div class="input-container">
            <label for="metaFirmada">META :</label>
            <input type="number" id="metaFirmada" value="" oninput="calcularDescuentos()" style="background-color: #f5c8c8;">
        </div> -->
        <div class="input-container">
            <label for="campo1">UNIDADES COMPRADAS ANTICIPADAS EDICICIÓN 2024 :</label>
            <input type="number" id="campo1" value="" oninput="calcularDescuentos()">
        </div>
        <div class="input-container">
            <label for="campo2">UNIDADES COMPRADAS EDICICIÓN 2024</label>
            <input type="number" id="campo2" value="" oninput="calcularDescuentos()">
        </div>
        <div class="input-container">
            <label for="campo4">UNIDADES COMPRADAS EDICIÓN 2023:</label>
            <input type="number" id="campo4" value="block" oninput="calcularDescuentos()">
        </div>

        <p>VENTA TOTAL: <span id="total1">0</span></p>

        <!-- <div class="input-container" style="margin-bottom: 20px; margin-left: 10%;">
            <label for="metaFirmada">META :</label>
            <input type="number" id="meta" value="" oninput="calcularDescuentos()" style="margin-left: 62%;">
        </div> -->
        <!-- <div class="input-container" style="margin-bottom: 20px; margin-left: 10%;">
            <label for="metaPlus">META PLUS:</label>
            <input type="number" id="metaPlus" value="" oninput="calcularDescuentos()" style="margin-left: 54%;">
        </div> -->
        <div class="input-container">
            <label for="pagoPuntual">PAGO PUNTUAL:</label>
            <input type="checkbox" id="pagoPuntual" onchange="calcularDescuentos()">
        </div>
        <div class="input-container">
            <label for="prontoPago">PRONTO PAGO:</label>
            <input type="checkbox" id="prontoPago" onchange="calcularDescuentos()">
        </div>
        <div class="input-container">
            <label for="devolucion">NO DEVOLUCION</label>
            <input type="checkbox" id="devolucion" onchange="calcularDescuentos()">
        </div>
        <p style="font-size: 14px;">*No devolucion Sujeto a cumplimiento de meta</p>
        <br><br><br>
        <!-- Tabla de resultados -->
        <table>
            <thead>
                <tr style="background-color: #3C8DBC; color: #f8f8f8;">
                    <th style="background-color: #3C8DBC;">Concepto</th>
                    <th style="background-color: #3C8DBC;">Porcentaje Descuento</th>
                    <th style="background-color: #3C8DBC;">Descuento</th>
                    <th style="background-color: #3C8DBC;">Resultado con Descuento</th>
                    <th style="background-color: #3C8DBC;"><span id="unidadesPrecioRegular1"></span></th>
                </tr>
            </thead>
            <tbody id="tablaDescuentosBody">
                <tr>
                    <td>DESCUENTO BASE</td>
                    <td id="porcentajeBase">36%</td>
                    <td id="descuentoBase"></td>
                    <td id="resultadoBase"></td>
                    <td id="multi0"></td>
                </tr>
                <tr>
                    <td>PAGO PUNTUAL</td>
                    <td id="porcentajePagoPuntual"></td>
                    <td id="descuentoPagoPuntual"></td>
                    <td id="resultadoPagoPuntual"></td>
                    <td id="multi1"></td>
                </tr>
                <!-- <tr>
                    <td>Meta (4%)</td>
                    <td id="porcentajeMeta"></td>
                    <td id="descuentoMeta"></td>
                    <td id="resultadoMeta"></td>
                    <td id="multi2"></td>
                </tr> -->
                <tr>
                    <td>PRONTO PAGO</td>
                    <td id="porcentajeProntoPago"></td>
                    <td id="descuentoProntoPago"></td>
                    <td id="resultadoProntoPago"></td>
                    <td id="multi3"></td>
                </tr>
                <!-- <tr>
                    <td>Meta Plus (2%)</td>
                    <td id="porcentajeMetaPlus"></td>
                    <td id="descuentoMetaPlus"></td>
                    <td id="resultadoMetaPlus"></td>
                    <td id="multi5"></td>
                </tr> -->
                <tr id="contenido">
                    <td>NO DEVOLUCIÓN</td>
                    <td id="porcentajeNoDevolucion"></td>
                    <td id="descuentoNoDevolucion"></td>
                    <td id="resultadoNoDevolucion"></td>
                    <td id="multi6"></td>
                </tr>
                <tr>
                    <td>TOTAL DE DESCUENTO</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="total" style="background-color: rgb(27, 153, 226);font-size:20PX ;"></td>
                </tr>
            </tbody>
        </table>

        <!-- Resultado de la venta total -->
    </div>
<script src="js/MDAPreescolar.js"></script>
    <script>
     
    </script>
</body>

</html>