<?php
// backend/api/guias.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/Conexion.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        // ---------- LISTAR GUÍAS POR TAREA ----------
        case 'GET':
            $tareaId = isset($_GET['tarea_id']) ? (int)$_GET['tarea_id'] : 0;
            if ($tareaId <= 0) {
                echo json_encode(["success" => false, "message" => "tarea_id es obligatorio."]);
                exit;
            }

            $stmt = $conn->prepare("
                SELECT
                    g.id,
                    g.rapida,
                    g.completa,
                    g.observacion,
                    g.url,
                    g.tarea_id,
                    g.fecha_hora_creado,
                    u.username AS usuario_creador
                FROM guias g
                LEFT JOIN usuarios u ON u.id = g.usuario_id_creador
                WHERE g.tarea_id = :tarea_id
                ORDER BY g.id
            ");
            $stmt->execute([':tarea_id' => $tareaId]);
            $guias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(["success" => true, "guias" => $guias]);
            break;

        // ---------- CREAR GUÍA ----------
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true) ?? [];

            $tareaId     = isset($data['tarea_id']) ? (int)$data['tarea_id'] : 0;
            $rapida      = trim($data['rapida']      ?? '');
            $completa    = trim($data['completa']    ?? '');
            $observacion = trim($data['observacion'] ?? '');
            $url         = trim($data['url']         ?? '');
            $usuarioId   = isset($data['usuario_id']) ? (int)$data['usuario_id'] : 1;

            if ($tareaId <= 0 || $rapida === '') {
                echo json_encode(["success" => false, "message" => "tarea_id y rapida son obligatorios."]);
                exit;
            }

            $stmt = $conn->prepare("
                INSERT INTO guias (rapida, completa, observacion, url, usuario_id_creador, tarea_id)
                VALUES (:rapida, :completa, :observacion, :url, :usuario_creador, :tarea_id)
            ");
            $stmt->execute([
                ':rapida'          => $rapida,
                ':completa'        => $completa,
                ':observacion'     => $observacion,
                ':url'             => $url,
                ':usuario_creador' => $usuarioId,
                ':tarea_id'        => $tareaId
            ]);

            echo json_encode(["success" => true, "guia_id" => (int)$conn->lastInsertId()]);
            break;

        // ---------- ACTUALIZAR GUÍA ----------
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true) ?? [];

            $id          = isset($data['id']) ? (int)$data['id'] : 0;
            $rapida      = trim($data['rapida']      ?? '');
            $completa    = trim($data['completa']    ?? '');
            $observacion = trim($data['observacion'] ?? '');
            $url         = trim($data['url']         ?? '');
            $usuarioId   = isset($data['usuario_id']) ? (int)$data['usuario_id'] : null;

            if ($id <= 0 || $rapida === '') {
                echo json_encode(["success" => false, "message" => "id y rapida son obligatorios para actualizar."]);
                exit;
            }

            $stmt = $conn->prepare("
                UPDATE guias
                   SET rapida = :rapida,
                       completa = :completa,
                       observacion = :observacion,
                       url = :url,
                       usuario_id_modificador = :usuario_modificador
                 WHERE id = :id
            ");
            $stmt->execute([
                ':rapida'              => $rapida,
                ':completa'            => $completa,
                ':observacion'         => $observacion,
                ':url'                 => $url,
                ':usuario_modificador' => $usuarioId,
                ':id'                  => $id
            ]);

            echo json_encode(["success" => true]);
            break;

        // ---------- ELIMINAR GUÍA ----------
        case 'DELETE':
            $id = 0;

            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
            } else {
                parse_str(file_get_contents("php://input"), $body);
                if (isset($body['id'])) {
                    $id = (int)$body['id'];
                }
            }

            if ($id <= 0) {
                echo json_encode(["success" => false, "message" => "id es obligatorio para eliminar."]);
                exit;
            }

            $stmt = $conn->prepare("DELETE FROM guias WHERE id = :id");
            $stmt->execute([':id' => $id]);

            echo json_encode(["success" => true]);
            break;

        default:
            http_response_code(405);
            echo json_encode(["success" => false, "message" => "Método no permitido"]);
            break;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor",
        "error"   => $e->getMessage()
    ]);
}
