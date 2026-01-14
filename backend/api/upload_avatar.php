<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

try {
    // -------------------------------
    // 1) VALIDAR SI LLEGÓ EL ARCHIVO
    // -------------------------------
    if (!isset($_FILES["avatar"])) {
        echo json_encode([
            "success" => false,
            "error" => "No se recibió archivo de avatar."
        ]);
        exit;
    }

    $file = $_FILES["avatar"];

    if ($file["error"] !== UPLOAD_ERR_OK) {
        echo json_encode([
            "success" => false,
            "error" => "Error al subir archivo. Código: " . $file["error"]
        ]);
        exit;
    }

    // -------------------------------
    // 2) VALIDAR EXTENSIÓN SEGURA
    // -------------------------------
    $permitidas = ["jpg", "jpeg", "png", "gif", "webp"];
    $nombreOriginal = $file["name"];
    $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

    if (!in_array($ext, $permitidas)) {
        echo json_encode([
            "success" => false,
            "error" => "Formato no permitido. Solo JPG, PNG, GIF o WEBP."
        ]);
        exit;
    }

    // -------------------------------
    // 3) CREAR CARPETA SI NO EXISTE
    // -------------------------------
    $rutaBase = __DIR__ . "/../../uploads/avatars/";
    if (!is_dir($rutaBase)) {
        mkdir($rutaBase, 0777, true);
    }

    // -------------------------------
    // 4) GENERAR NOMBRE ÚNICO
    // -------------------------------
    $nombreNuevo = uniqid("avatar_", true) . "." . $ext;
    $rutaDestino = $rutaBase . $nombreNuevo;

    // -------------------------------
    // 5) MOVER EL ARCHIVO SUBIDO
    // -------------------------------
    if (!move_uploaded_file($file["tmp_name"], $rutaDestino)) {
        echo json_encode([
            "success" => false,
            "error" => "No se pudo guardar el archivo en el servidor."
        ]);
        exit;
    }

    // -------------------------------
    // 6) URL PÚBLICA PARA GUARDAR EN BD
    // -------------------------------
    $urlPublica = "/schedule/uploads/avatars/" . $nombreNuevo;

    echo json_encode([
        "success" => true,
        "message" => "Avatar subido correctamente.",
        "filename" => $nombreNuevo,
        "url" => $urlPublica
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => "Error en servidor: " . $e->getMessage()
    ]);
}
