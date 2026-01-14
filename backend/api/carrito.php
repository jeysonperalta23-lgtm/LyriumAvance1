<?php
// C:\xampp\htdocs\schedule\backend\api\carrito.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/Conexion.php';

date_default_timezone_set('America/Lima');

function getConn(): PDO {
    // ⚠️ Asegúrate que Conexion use la BD `lyrium`
    // por ejemplo en Conexion.php: $dbname = 'lyrium';
    return Conexion::getConexion();
}

/**
 * Obtiene (y si no existe, crea) el carrito ABIERTO de un cliente.
 */
function obtenerOCrearCarrito(int $clienteId, string $moneda = 'PEN'): array {
    $pdo = getConn();

    $stmt = $pdo->prepare("
        SELECT *
        FROM carritos
        WHERE cliente_id = :cliente_id
          AND estado = 'abierto'
        LIMIT 1
    ");
    $stmt->execute([':cliente_id' => $clienteId]);
    $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($carrito) {
        return $carrito;
    }

    $stmt = $pdo->prepare("
        INSERT INTO carritos (cliente_id, estado, moneda, total_estimado, usuario_id_creador)
        VALUES (:cliente_id, 'abierto', :moneda, 0, NULL)
    ");
    $stmt->execute([
        ':cliente_id' => $clienteId,
        ':moneda'     => $moneda
    ]);

    $id = (int)$pdo->lastInsertId();

    $stmt = $pdo->prepare("SELECT * FROM carritos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

    return $carrito ?: [];
}

/**
 * Recalcula el total_estimado del carrito a partir de carritos_items
 */
function recalcularTotalCarrito(int $carritoId): void {
    $pdo = getConn();

    $stmt = $pdo->prepare("
        SELECT SUM(cantidad * precio_unitario) AS total
        FROM carritos_items
        WHERE carrito_id = :carrito_id
    ");
    $stmt->execute([':carrito_id' => $carritoId]);
    $total = (float)($stmt->fetchColumn() ?: 0);

    $stmt = $pdo->prepare("
        UPDATE carritos
        SET total_estimado = :total, fecha_hora_modificado = NOW()
        WHERE id = :id
    ");
    $stmt->execute([
        ':total' => $total,
        ':id'    => $carritoId
    ]);
}

/**
 * Devuelve carrito + items + info básica del producto
 */
function obtenerCarritoCompleto(int $clienteId, string $moneda = 'PEN'): array {
    $pdo = getConn();

    $carrito = obtenerOCrearCarrito($clienteId, $moneda);
    if (!$carrito) {
        return [
            'carrito' => null,
            'items'   => []
        ];
    }

    $stmt = $pdo->prepare("
        SELECT ci.*,
               p.nombre        AS producto_nombre,
               p.sku           AS producto_sku,
               p.moneda        AS producto_moneda,
               p.precio        AS producto_precio,
               p.precio_oferta AS producto_precio_oferta
        FROM carritos_items ci
        INNER JOIN productos p ON p.id = ci.producto_id
        WHERE ci.carrito_id = :carrito_id
        ORDER BY ci.id ASC
    ");
    $stmt->execute([':carrito_id' => $carrito['id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // opcional: aseguramos total_estimado consistente
    recalcularTotalCarrito((int)$carrito['id']);

    return [
        'carrito' => $carrito,
        'items'   => $items
    ];
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $clienteId = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
        $moneda    = isset($_GET['moneda']) ? $_GET['moneda'] : 'PEN';

        if ($clienteId <= 0) {
            echo json_encode([
                'success' => false,
                'error'   => 'cliente_id es obligatorio'
            ]);
            exit;
        }

        $data = obtenerCarritoCompleto($clienteId, $moneda);

        echo json_encode([
            'success' => true,
            'carrito' => $data['carrito'],
            'items'   => $data['items']
        ]);
        exit;
    }

    if ($method === 'PUT') {
        $raw  = file_get_contents('php://input');
        $body = json_decode($raw, true) ?: [];

        $carritoId = isset($body['carrito_id']) ? (int)$body['carrito_id'] : 0;
        $estado    = $body['estado'] ?? null;

        if ($carritoId <= 0 || !$estado) {
            echo json_encode([
                'success' => false,
                'error'   => 'carrito_id y estado son obligatorios'
            ]);
            exit;
        }

        $pdo = getConn();
        $stmt = $pdo->prepare("
            UPDATE carritos
            SET estado = :estado,
                fecha_hora_modificado = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            ':estado' => $estado,
            ':id'     => $carritoId
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Carrito actualizado'
        ]);
        exit;
    }

    echo json_encode([
        'success' => false,
        'error'   => 'Método no soportado'
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
