<?php
// backend/api/resetear-pass.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");

require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$method = $_SERVER['REQUEST_METHOD'];
$data   = json_decode(file_get_contents("php://input"), true) ?? [];

switch ($method) {
  case 'POST':

    // 1) CAMBIAR CONTRASEÑA (token + new_password)
    if (isset($data['new_password']) && isset($data['token'])) {
      $newPassword = trim($data['new_password']);
      $token       = trim($data['token']);

      if ($token === "" || $newPassword === "") {
        echo json_encode(["success" => false, "message" => "Token o nueva contraseña no proporcionados."]);
        exit;
      }

      try {
        $stmt = $conn->prepare("
          SELECT *
            FROM usuarios
           WHERE token_temporal = :token
             AND expiracion_token_temporal > NOW()
          LIMIT 1
        ");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
          $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

          $updateStmt = $conn->prepare("
            UPDATE usuarios
               SET password = :password,
                   token_temporal = NULL,
                   expiracion_token_temporal = NULL
             WHERE id = :id
          ");
          $updateStmt->bindParam(':password', $hashedPassword);
          $updateStmt->bindParam(':id', $usuario['id']);
          $updateStmt->execute();

          echo json_encode(["success" => true, "message" => "Contraseña actualizada correctamente."]);
        } else {
          echo json_encode(["success" => false, "message" => "Token inválido o expirado."]);
        }
      } catch (PDOException $e) {
        echo json_encode([
          "success" => false,
          "message" => "Error de base de datos al actualizar contraseña: " . $e->getMessage()
        ]);
      }
      exit;
    }

    // 2) ENVIAR CORREO CON ENLACE (email)
    if (isset($data['email'])) {
      $email = trim($data['email']);

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Correo electrónico no válido."]);
        exit;
      }

      try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = :correo LIMIT 1");
        $stmt->bindParam(':correo', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
          $tokenTemporal   = bin2hex(random_bytes(32));
          $expiracionToken = date("Y-m-d H:i:s", strtotime('+1 hour'));

          $updateStmt = $conn->prepare("
            UPDATE usuarios
               SET token_temporal = :token,
                   expiracion_token_temporal = :expiracion
             WHERE id = :id
          ");
          $updateStmt->bindParam(':token', $tokenTemporal);
          $updateStmt->bindParam(':expiracion', $expiracionToken);
          $updateStmt->bindParam(':id', $usuario['id']);
          $updateStmt->execute();

          // OJO: ajusta esta URL si tu ruta cambia
          $link = "http://localhost/schedule/frontend/actualizar-pass.php?token=$tokenTemporal";

          $mail = new PHPMailer(true);
          try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sendtojunior@gmail.com';
            $mail->Password   = 'uuht jdwt emmj jylr'; // contraseña de app
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('sendtojunior@gmail.com', 'Soporte de credenciales');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de credenciales';
            $mail->Body    = "Haz clic en el siguiente enlace para restablecer tu contraseña:<br><a href='$link'>$link</a>";

            $mail->send();
            echo json_encode(["success" => true, "message" => "Correo enviado con éxito."]);
          } catch (Exception $e) {
            echo json_encode([
              "success" => false,
              "message" => "Error al enviar correo: " . $mail->ErrorInfo
            ]);
          }
        } else {
          echo json_encode(["success" => false, "message" => "El correo no está registrado."]);
        }
      } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error de base de datos: " . $e->getMessage()]);
      }
      exit;
    }

    echo json_encode(["success" => false, "message" => "Datos insuficientes."]);
    break;

  default:
    echo json_encode(["success" => false, "message" => "Método $method no permitido."]);
    break;
}
