<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dsn = 'odbc:sqlserver';
$username = "sa";
$password = "B1Admin";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $emailInput = trim($_POST['email']);
    $cardCodeInput = trim($_POST['cardcode']); 

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT CardCode, U_web_password FROM dbo.OCRD WHERE UPPER(CardCode) = UPPER(:cardcode)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cardcode', $cardCodeInput, PDO::PARAM_STR);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) { 
             $cardCode = $row['CardCode'];
             $webPassword = $row['U_web_password'];

            $mail = new PHPMailer(true);

            try {
                
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'jose.intzin@montenegroeditores.net';
                $mail->Password   = 'mfgu xxyg pedf wube'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('jose.intzin@montenegroeditores.net', 'Recuperación de Información');
                $mail->addAddress($emailInput); 

                $mail->isHTML(true);
                $mail->Subject = 'Información de tu cuenta';
                $mail->Body    = "Hola,<br>Tu informacion:<br><strong>CardCode:</strong> $cardCode<br><strong>Contraseña:</strong> $webPassword";
                $mail->AltBody = "Hola,\nTu información:\nCardCode: $cardCode\nContraseña: $webPassword";

                $mail->send();
                
                header('Content-Type: application/json');
                echo json_encode(['status' => 200, 'data' => "La información ha sido enviada correctamente"]);
                exit; 
            } 
            catch (Exception $e) {
                
                header('Content-Type: application/json');
                echo json_encode(['status' => 400, 'data' => "El mensaje no se pudo enviar. Error de Mailer: {$mail->ErrorInfo}"]);
                exit; 
            }
        } 
        else {
           
            header('Content-Type: application/json');
            echo json_encode(['status' => 400, 'data' => "El CardCode no existe en la base de datos."]);
            exit; 
        }
        $conn = null;
    } 
    catch (PDOException $e) {
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 400, 'data' => "Error en la conexión: ".$e->getMessage()]);
        exit; 
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
