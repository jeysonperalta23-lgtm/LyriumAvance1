<?php
// C:\xampp\htdocs\schedule\backend\helpers\jwt.php

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// ⚠️ Usa una clave más segura en producción
define("JWT_SECRET", "Schedule");

function generarToken($usuario) {

    // Payload con los datos que necesitas en el frontend
    $payload = [
        "iat" => time(),               // Fecha de emisión
        "exp" => time() + (60 * 60),   // Expira en 1 hora
        "data" => [
            "id"              => $usuario["id"],
            "username"        => $usuario["username"],
            "correo"          => $usuario["correo"],
            "rol"             => $usuario["rol"],
            "avatar_filename" => $usuario["avatar_filename"] ?? null,
            "avatar_color"    => $usuario["avatar_color"] ?? null
        ]
    ];

    return JWT::encode($payload, JWT_SECRET, 'HS256');
}

function validarToken($token) {
    return JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
}
