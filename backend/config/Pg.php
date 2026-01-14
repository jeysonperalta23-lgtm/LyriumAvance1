<?php
// backend/config/Conexion.php

$host = "127.0.0.1";
$port = "5433"; // Puerto personalizado de PostgreSQL
$dbname = "Zomi1";
$user = "postgres";
$password = "123456";

try {
    // Importante: incluir el puerto en el DSN
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la conexión: " . $e->getMessage()
    ]);
    exit;
}
?>