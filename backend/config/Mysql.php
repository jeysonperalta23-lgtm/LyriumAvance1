<?php
// backend/config/Conexion.php

$host = "127.0.0.1";
$port = "3306"; // Puerto de MySQL (por defecto)
$dbname = "sura";
$user = "root";
$password = "";
$charset = "utf8mb4";

try {
    // DSN correcto para MySQL con puerto y charset incluidos
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

    $conn = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errores como excepciones
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // resultados como arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false                   // prepara consultas nativas
    ]);

    // (Opcional) Establecer zona horaria si tu servidor MySQL no está en GMT-5
    // $conn->exec("SET time_zone = '-05:00'");

} catch (PDOException $e) {
    // Respuesta JSON limpia para APIs
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "success" => false,
        "message" => "Error en la conexión con la base de datos",
        "error"   => $e->getMessage()
    ]);
    exit;
}
