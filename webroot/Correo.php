<?php
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

                 
                 header('Content-Type: application/json');
                 echo json_encode(['status' => 200, 'data' => ['cardCode' => $cardCode, 'webPassword' => $webPassword]]);
                 exit; 
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
