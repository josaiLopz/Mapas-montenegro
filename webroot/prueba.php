<?php
// Incluir las clases de PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configuración de la base de datos
$dsn = 'odbc:sqlserver';
$username = "sa";
$password = "B1Admin";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $emailInput = trim($_POST['email']); // Correo del destinatario
    $cardCodeInput = trim($_POST['cardcode']); // CardCode

    // Depuración: imprimir el valor de CardCode ingresado
    //echo "CardCode ingresado: ";
    //var_dump($cardCodeInput);

    try {
        // Conexión a la base de datos
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       // echo "Conexión exitosa a la base de datos<br>"; // Confirmar conexión

        // Consulta para verificar si el CardCode existe y obtener CardCode y U_web_password
        $sql = "SELECT CardCode, U_web_password FROM dbo.OCRD WHERE UPPER(CardCode) = UPPER(:cardcode)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cardcode', $cardCodeInput, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) { // Verificar si se obtuvo algún resultado
            // echo "Registro encontrado: ";
            // print_r($row); // Imprimir el resultado completo para depurar
             $cardCode = $row['CardCode'];
             $webPassword = $row['U_web_password'];

            // Enviar el correo con PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'jose.intzin@montenegroeditores.net';
                $mail->Password   = 'mfgu xxyg pedf wube'; // Contraseña de tu correo de Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Destinatarios
                $mail->setFrom('jose.intzin@montenegroeditores.net', 'Recuperación de Información');
                $mail->addAddress($emailInput); // Usar el correo ingresado en el input

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Información de tu cuenta';
                $mail->Body    = "Hola,<br>Tu información:<br><strong>CardCode:</strong> $cardCode<br><strong>Contraseña:</strong> $webPassword";
                $mail->AltBody = "Hola,\nTu información:\nCardCode: $cardCode\nContraseña: $webPassword";

                $mail->send();
                echo 'La información ha sido enviada correctamente';
            } catch (Exception $e) {
                echo "El mensaje no se pudo enviar. Error de Mailer: {$mail->ErrorInfo}";
            }
        } else {
            echo "El CardCode no existe en la base de datos.";
        }

        // Cerrar conexión
        $conn = null;
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
    }
}
?>

<!-- Formulario HTML -->
<form method="POST" action="">
    <label for="cardcode">Ingresa tu CardCode:</label>
    <input type="text" id="cardcode" name="cardcode" required>
    
    <label for="email">Ingresa tu correo electrónico:</label>
    <input type="email" id="email" name="email" required>
    
    <button type="submit">Enviar</button>
</form>
