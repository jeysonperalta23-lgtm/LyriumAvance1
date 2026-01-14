<?php
// C:\xampp\htdocs\schedule\backend\api\login.php

require_once '../config/Conexion.php';
require_once '../helpers/jwt.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$usernameOrEmail = $data["username"] ?? "";
$password        = $data["password"] ?? "";

if ($usernameOrEmail === "" || $password === "") {
    echo json_encode(["success" => false, "message" => "Campos obligatorios"]);
    exit;
}

try {
    // Solo traemos avatar_filename (avatar_url ya no se usa)
    $sql = "SELECT 
                id,
                persona_id,
                username,
                password,
                correo,
                avatar_color,
                avatar_filename,
                rol,
                estado
            FROM usuarios
            WHERE username = :u OR correo = :e
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':u' => $usernameOrEmail,
        ':e' => $usernameOrEmail
    ]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {

        $token = generarToken($usuario);

        echo json_encode([
            "success" => true,
            "token"   => $token,
            "usuario" => [
                "id"              => $usuario["id"],
                "username"        => $usuario["username"],
                "correo"          => $usuario["correo"],
                "rol"             => $usuario["rol"],
                "avatar_color"    => $usuario["avatar_color"],
                "avatar_filename" => $usuario["avatar_filename"],
                "estado"          => $usuario["estado"],
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Credenciales inválidas"]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error del servidor",
        "error"   => $e->getMessage()
    ]);
}
