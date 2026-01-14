<?php
// backend/api/carrito_item.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/Conexion.php';

date_default_timezone_set('America/Lima');

function getConn(): PDO
{
    return Conexion::getConexion();
}

function obtenerOCrearCarrito(int $clienteId, string $moneda = 'PEN'): array
{
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
        ':moneda' => $moneda
    ]);

    $id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT * FROM carritos WHERE id = :id");
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}

function recalcularTotalCarrito(int $carritoId): void
{
    $pdo = getConn();

    $stmt = $pdo->prepare("
        SELECT SUM(cantidad * precio_unitario) AS total
        FROM carritos_items
        WHERE carrito_id = :carrito_id
    ");
    $stmt->execute([':carrito_id' => $carritoId]);
    $total = (float) ($stmt->fetchColumn() ?: 0);

    $stmt = $pdo->prepare("
        UPDATE carritos
        SET total_estimado = :total, fecha_hora_modificado = NOW()
        WHERE id = :id
    ");
    $stmt->execute([
        ':total' => $total,
        ':id' => $carritoId
    ]);
}

function obtenerPrecioProducto(int $productoId, ?int $variacionId = null): array
{
    $pdo = getConn();

    if ($variacionId) {
        $stmt = $pdo->prepare("
            SELECT v.precio, p.moneda
            FROM productos_variaciones v
            INNER JOIN productos p ON p.id = v.producto_id
            WHERE v.id = :vid AND v.producto_id = :pid
        ");
        $stmt->execute([
            ':vid' => $variacionId,
            ':pid' => $productoId
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return [
                'precio' => (float) $row['precio'],
                'moneda' => $row['moneda']
            ];
        }
    }

    $stmt = $pdo->prepare("
        SELECT precio, moneda
        FROM productos
        WHERE id = :pid
    ");
    $stmt->execute([':pid' => $productoId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Producto no encontrado");
    }

    return [
        'precio' => (float) $row['precio'],
        'moneda' => $row['moneda']
    ];
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        // Agregar item al carrito
        /*
           JSON esperado:
           {
             "cliente_id": 1,
             "producto_id": 10,
             "variacion_id": null,
             "cantidad": 2
           }
        */
        $raw = file_get_contents('php://input');
        $body = json_decode($raw, true) ?: [];

        $clienteId = isset($body['cliente_id']) ? (int) $body['cliente_id'] : 0;
        $productoId = isset($body['producto_id']) ? (int) $body['producto_id'] : 0;
        $variacionId = isset($body['variacion_id']) ? (int) $body['variacion_id'] : null;
        $cantidad = isset($body['cantidad']) ? (int) $body['cantidad'] : 1;

        if ($clienteId <= 0 || $productoId <= 0 || $cantidad <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'cliente_id, producto_id y cantidad > 0 son obligatorios'
            ]);
            exit;
        }

        $pdo = getConn();

        // Carrito abierto
        $carrito = obtenerOCrearCarrito($clienteId);
        if (!$carrito) {
            throw new Exception("No se pudo obtener o crear carrito");
        }

        // Precio
        $precioData = obtenerPrecioProducto($productoId, $variacionId);
        $precioUnit = $precioData['precio'];
        $moneda = $precioData['moneda'];

        // ¿Existe ya el item con ese producto/variación?
        $stmt = $pdo->prepare("
            SELECT id, cantidad
            FROM carritos_items
            WHERE carrito_id = :carrito_id
              AND producto_id = :producto_id
              AND ( (variacion_id IS NULL AND :variacion_id IS NULL) 
                    OR (variacion_id = :variacion_id) )
            LIMIT 1
        ");
        $stmt->execute([
            ':carrito_id' => $carrito['id'],
            ':producto_id' => $productoId,
            ':variacion_id' => $variacionId
        ]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Sumar cantidad
            $nuevaCantidad = (int) $item['cantidad'] + $cantidad;
            $stmt = $pdo->prepare("
                UPDATE carritos_items
                SET cantidad = :cantidad,
                    fecha_hora_modificado = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                ':cantidad' => $nuevaCantidad,
                ':id' => $item['id']
            ]);
        } else {
            // Nuevo item
            $stmt = $pdo->prepare("
                INSERT INTO carritos_items
                (carrito_id, producto_id, variacion_id, cantidad, precio_unitario, moneda, usuario_id_creador)
                VALUES
                (:carrito_id, :producto_id, :variacion_id, :cantidad, :precio_unitario, :moneda, NULL)
            ");
            $stmt->execute([
                ':carrito_id' => $carrito['id'],
                ':producto_id' => $productoId,
                ':variacion_id' => $variacionId,
                ':cantidad' => $cantidad,
                ':precio_unitario' => $precioUnit,
                ':moneda' => $moneda
            ]);
        }

        // Recalcular total carrito
        recalcularTotalCarrito((int) $carrito['id']);

        echo json_encode([
            'success' => true,
            'message' => 'Item agregado al carrito',
            'carrito_id' => (int) $carrito['id']
        ]);
        exit;
    }

    if ($method === 'PUT') {
        // Actualizar cantidad de un item
        /*
           JSON esperado:
           {
             "item_id": 5,
             "cantidad": 3
           }
        */
        $raw = file_get_contents('php://input');
        $body = json_decode($raw, true) ?: [];

        $itemId = isset($body['item_id']) ? (int) $body['item_id'] : 0;
        $cantidad = isset($body['cantidad']) ? (int) $body['cantidad'] : 0;

        if ($itemId <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'item_id es obligatorio'
            ]);
            exit;
        }

        $pdo = getConn();

        // Obtener carrito_id desde el item
        $stmt = $pdo->prepare("SELECT carrito_id FROM carritos_items WHERE id = :id");
        $stmt->execute([':id' => $itemId]);
        $carritoId = (int) ($stmt->fetchColumn() ?: 0);

        if ($carritoId <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'No se encontró el item/carrito'
            ]);
            exit;
        }

        if ($cantidad <= 0) {
            // Si cantidad <= 0, elimino el item
            $stmt = $pdo->prepare("DELETE FROM carritos_items WHERE id = :id");
            $stmt->execute([':id' => $itemId]);
        } else {
            // Actualizo la cantidad
            $stmt = $pdo->prepare("
                UPDATE carritos_items
                SET cantidad = :cantidad,
                    fecha_hora_modificado = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                ':cantidad' => $cantidad,
                ':id' => $itemId
            ]);
        }

        // Recalcular total
        recalcularTotalCarrito($carritoId);

        echo json_encode([
            'success' => true,
            'message' => 'Item actualizado'
        ]);
        exit;
    }

    if ($method === 'DELETE') {
        // Eliminar item del carrito
        // DELETE con body: id=XX
        parse_str(file_get_contents('php://input'), $body);
        $itemId = isset($body['id']) ? (int) $body['id'] : 0;

        if ($itemId <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'id (item) es obligatorio'
            ]);
            exit;
        }

        $pdo = getConn();

        // Obtener carrito_id
        $stmt = $pdo->prepare("SELECT carrito_id FROM carritos_items WHERE id = :id");
        $stmt->execute([':id' => $itemId]);
        $carritoId = (int) ($stmt->fetchColumn() ?: 0);

        if ($carritoId <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'Item no encontrado'
            ]);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM carritos_items WHERE id = :id");
        $stmt->execute([':id' => $itemId]);

        recalcularTotalCarrito($carritoId);

        echo json_encode([
            'success' => true,
            'message' => 'Item eliminado'
        ]);
        exit;
    }

    echo json_encode([
        'success' => false,
        'error' => 'Método no soportado'
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
