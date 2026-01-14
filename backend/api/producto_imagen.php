<?php
// C:\xampp\htdocs\schedule\backend\api\producto_imagen.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/Conexion.php'; // aquí tienes $conn (PDO)

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {

        // ============================== GET ===============================
        // GET ?producto_id=XX  → lista de imágenes de ese producto
        case 'GET':
            $producto_id = isset($_GET['producto_id']) ? (int) $_GET['producto_id'] : 0;
            if ($producto_id <= 0) {
                echo json_encode([
                    "success" => false,
                    "error" => "producto_id es requerido"
                ]);
                break;
            }

            $stmt = $conn->prepare("
                SELECT 
                    id,
                    producto_id,
                    url,
                    es_principal,
                    orden,
                    fecha_hora_creado
                FROM productos_imagenes
                WHERE producto_id = :pid
                ORDER BY es_principal DESC, orden ASC, id ASC
            ");
            $stmt->execute([':pid' => $producto_id]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                "success" => true,
                "imagenes" => $rows
            ]);
            break;

        // ============================== POST ==============================
        // Crea una nueva imagen para producto
        // body JSON: { producto_id, url, es_principal (0/1), orden? }
        case 'POST':
            $data = json_decode(file_get_contents("php://input"));

            if (empty($data->producto_id) || empty($data->url)) {
                echo json_encode([
                    "success" => false,
                    "error" => "producto_id y url son obligatorios"
                ]);
                break;
            }

            $producto_id = (int) $data->producto_id;
            $url = trim($data->url);
            $esPrincipal = isset($data->es_principal) ? (int) $data->es_principal : 0;
            $orden = isset($data->orden) ? (int) $data->orden : 0;

            // Si es principal, poner las otras en 0
            if ($esPrincipal === 1) {
                $stmt = $conn->prepare("
                    UPDATE productos_imagenes
                       SET es_principal = 0
                     WHERE producto_id = :pid
                ");
                $stmt->execute([':pid' => $producto_id]);
            }

            // Si no viene orden, asignamos siguiente
            if ($orden === 0) {
                $stmt = $conn->prepare("
                    SELECT COALESCE(MAX(orden), 0) + 1 AS siguiente
                    FROM productos_imagenes
                    WHERE producto_id = :pid
                ");
                $stmt->execute([':pid' => $producto_id]);
                $orden = (int) $stmt->fetchColumn();
            }

            $stmt = $conn->prepare("
                INSERT INTO productos_imagenes (
                    producto_id,
                    url,
                    es_principal,
                    orden,
                    usuario_id_creador,
                    fecha_hora_creado
                )
                VALUES (
                    :producto_id,
                    :url,
                    :es_principal,
                    :orden,
                    :usuario_id_creador,
                    NOW()
                )
            ");

            $ok = $stmt->execute([
                ':producto_id' => $producto_id,
                ':url' => $url,
                ':es_principal' => $esPrincipal,
                ':orden' => $orden,
                ':usuario_id_creador' => null
            ]);

            echo json_encode(["success" => $ok]);
            break;

        // =============================== PUT ==============================
        // Actualiza es_principal u orden
        // body JSON: { id, es_principal?, orden? }
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));

            if (empty($data->id)) {
                echo json_encode([
                    "success" => false,
                    "error" => "id es requerido"
                ]);
                break;
            }

            $id = (int) $data->id;
            $es_principal = isset($data->es_principal) ? (int) $data->es_principal : null;
            $orden = isset($data->orden) ? (int) $data->orden : null;

            // Primero obtenemos la imagen para saber producto_id
            $stmt = $conn->prepare("SELECT * FROM productos_imagenes WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$img) {
                echo json_encode([
                    "success" => false,
                    "error" => "Imagen no encontrada"
                ]);
                break;
            }

            $producto_id = (int) $img['producto_id'];

            if ($es_principal === 1) {
                // ponemos las otras en 0
                $stmt = $conn->prepare("
                    UPDATE productos_imagenes
                       SET es_principal = 0
                     WHERE producto_id = :pid
                ");
                $stmt->execute([':pid' => $producto_id]);
            }

            $campos = [];
            $params = [':id' => $id];

            if ($es_principal !== null) {
                $campos[] = "es_principal = :es_principal";
                $params[':es_principal'] = $es_principal;
            }

            if ($orden !== null) {
                $campos[] = "orden = :orden";
                $params[':orden'] = $orden;
            }

            if (empty($campos)) {
                echo json_encode(["success" => true]); // nada que actualizar
                break;
            }

            $sql = "UPDATE productos_imagenes SET " . implode(", ", $campos) . " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $ok = $stmt->execute($params);

            echo json_encode(["success" => $ok]);
            break;

        // ============================= DELETE =============================
        // DELETE con body: id=XX  (x-www-form-urlencoded)
        case 'DELETE':
            parse_str(file_get_contents("php://input"), $data);

            if (empty($data['id'])) {
                echo json_encode([
                    "success" => false,
                    "error" => "id es requerido"
                ]);
                break;
            }

            $id = (int) $data['id'];

            $stmt = $conn->prepare("DELETE FROM productos_imagenes WHERE id = :id");
            $ok = $stmt->execute([':id' => $id]);

            echo json_encode(["success" => $ok]);
            break;

        default:
            http_response_code(405);
            echo json_encode([
                "success" => false,
                "error" => "Método no permitido"
            ]);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Error en el servidor: " . $e->getMessage()
    ]);
}
