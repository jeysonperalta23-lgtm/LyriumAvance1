<?php
// C:\xampp\htdocs\schedule\backend\api\producto.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/Conexion.php'; // Aquí tienes $conn (PDO)

$method = $_SERVER['REQUEST_METHOD'];

/**
 * Genera un slug básico desde nombre
 */
function slugify($texto) {
    $texto = strtolower(trim($texto));
    // Reemplaza acentos
    $texto = strtr($texto, [
        'á' => 'a', 'é' => 'e', 'í' => 'i',
        'ó' => 'o', 'ú' => 'u', 'ñ' => 'n'
    ]);
    // Reemplaza todo lo que no sea letra/número por guión
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    // Quita guiones al inicio/fin
    $texto = trim($texto, '-');
    return $texto ?: 'producto';
}

/**
 * Genera slug único en base a la tabla productos
 */
function generarSlugUnico(PDO $conn, $nombreBase) {
    $base = slugify($nombreBase);

    // Cuenta cuántos slugs existentes empiezan igual
    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM productos
        WHERE slug = :slugBase OR slug LIKE :slugBaseLike
    ");
    $stmt->execute([
        ':slugBase'     => $base,
        ':slugBaseLike' => $base . '-%'
    ]);
    $cantidad = (int)$stmt->fetchColumn();

    if ($cantidad === 0) {
        return $base;
    }

    return $base . '-' . ($cantidad + 1);
}

try {
    switch ($method) {

        // ================================= GET =================================
        case 'GET':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

            $sql = "
                SELECT 
                    id,
                    vendedor_usuario_id,
                    categoria_id,
                    nombre,
                    slug,
                    descripcion_corta,
                    precio,
                    precio_oferta,
                    moneda,
                    igv_incluido,
                    unidad_medida,
                    sku,
                    stock,
                    stock_minimo,
                    estado_stock,
                    tipo_producto,
                    estado,
                    publicado,
                    destacado,
                    meta_titulo,
                    meta_descripcion,
                    meta_keywords,
                    fecha_hora_creado
                FROM productos
            ";

            $params = [];

            if ($id > 0) {
                $sql .= " WHERE id = :id";
                $params[':id'] = $id;
            }

            $sql .= " ORDER BY fecha_hora_creado DESC";

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($id > 0 && count($rows) === 1) {
                echo json_encode([
                    "success"  => true,
                    "producto" => $rows[0]
                ]);
            } else {
                echo json_encode([
                    "success"   => true,
                    "productos" => $rows
                ]);
            }
            break;

        // ================================ POST =================================
        // Crear producto
        case 'POST':
            $data = json_decode(file_get_contents("php://input"));

            if (empty($data->nombre) || !isset($data->precio)) {
                echo json_encode([
                    "success" => false,
                    "error"   => "Nombre y precio son obligatorios."
                ]);
                break;
            }

            $nombre            = trim($data->nombre);
            $slug              = generarSlugUnico($conn, $nombre);
            $descripcion_corta = !empty($data->descripcion_corta) ? trim($data->descripcion_corta) : null;
            $precio            = (float)$data->precio;
            $precio_oferta     = isset($data->precio_oferta) && $data->precio_oferta !== "" 
                                   ? (float)$data->precio_oferta 
                                   : null;
            $moneda            = !empty($data->moneda) ? $data->moneda : 'PEN';
            $sku               = !empty($data->sku) ? trim($data->sku) : null;
            $stock             = isset($data->stock) ? (int)$data->stock : 0;
            $stock_minimo      = isset($data->stock_minimo) ? (int)$data->stock_minimo : 0;
            $tipo_producto     = !empty($data->tipo_producto) ? $data->tipo_producto : 'simple';
            $estado_stock      = !empty($data->estado_stock)
                                    ? $data->estado_stock
                                    : ($stock > 0 ? 'in_stock' : 'out_of_stock');
            $estado            = isset($data->estado) ? (int)$data->estado : 1;
            $publicado         = isset($data->publicado) ? (int)$data->publicado : 1;
            $destacado         = isset($data->destacado) ? (int)$data->destacado : 0;

            $igv_incluido      = 1;
            $unidad_medida     = !empty($data->unidad_medida) ? $data->unidad_medida : 'NIU';

            $vendedor_usuario_id = isset($data->vendedor_usuario_id) ? (int)$data->vendedor_usuario_id : null;
            $categoria_id        = isset($data->categoria_id) ? (int)$data->categoria_id : null;

            $stmt = $conn->prepare("
                INSERT INTO productos (
                    vendedor_usuario_id,
                    categoria_id,
                    nombre,
                    slug,
                    descripcion_corta,
                    descripcion_larga,
                    precio,
                    precio_oferta,
                    moneda,
                    igv_incluido,
                    codigo_sunat,
                    unidad_medida,
                    sku,
                    stock,
                    stock_minimo,
                    estado_stock,
                    peso_kg,
                    alto_cm,
                    ancho_cm,
                    largo_cm,
                    tipo_producto,
                    estado,
                    publicado,
                    destacado,
                    meta_titulo,
                    meta_descripcion,
                    meta_keywords,
                    usuario_id_creador,
                    fecha_hora_creado
                )
                VALUES (
                    :vendedor_usuario_id,
                    :categoria_id,
                    :nombre,
                    :slug,
                    :descripcion_corta,
                    :descripcion_larga,
                    :precio,
                    :precio_oferta,
                    :moneda,
                    :igv_incluido,
                    :codigo_sunat,
                    :unidad_medida,
                    :sku,
                    :stock,
                    :stock_minimo,
                    :estado_stock,
                    :peso_kg,
                    :alto_cm,
                    :ancho_cm,
                    :largo_cm,
                    :tipo_producto,
                    :estado,
                    :publicado,
                    :destacado,
                    :meta_titulo,
                    :meta_descripcion,
                    :meta_keywords,
                    :usuario_id_creador,
                    NOW()
                )
            ");

            $ok = $stmt->execute([
                ':vendedor_usuario_id' => $vendedor_usuario_id,
                ':categoria_id'        => $categoria_id,
                ':nombre'              => $nombre,
                ':slug'                => $slug,
                ':descripcion_corta'   => $descripcion_corta,
                ':descripcion_larga'   => null,
                ':precio'              => $precio,
                ':precio_oferta'       => $precio_oferta,
                ':moneda'              => $moneda,
                ':igv_incluido'        => $igv_incluido,
                ':codigo_sunat'        => null,
                ':unidad_medida'       => $unidad_medida,
                ':sku'                 => $sku,
                ':stock'               => $stock,
                ':stock_minimo'        => $stock_minimo,
                ':estado_stock'        => $estado_stock,
                ':peso_kg'             => null,
                ':alto_cm'             => null,
                ':ancho_cm'            => null,
                ':largo_cm'            => null,
                ':tipo_producto'       => $tipo_producto,
                ':estado'              => $estado,
                ':publicado'           => $publicado,
                ':destacado'           => $destacado,
                ':meta_titulo'         => null,
                ':meta_descripcion'    => null,
                ':meta_keywords'       => null,
                ':usuario_id_creador'  => null
            ]);

            echo json_encode(["success" => $ok]);
            break;

        // ================================ PUT ==================================
        // Editar producto
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));

            if (empty($data->id)) {
                echo json_encode(["success" => false, "error" => "ID de producto requerido."]);
                break;
            }

            $id = (int)$data->id;

            // Traer registro actual
            $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            $actual = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$actual) {
                echo json_encode(["success" => false, "error" => "Producto no encontrado."]);
                break;
            }

            // Campos a actualizar con fallback
            $nombre            = isset($data->nombre)            ? trim($data->nombre)            : $actual['nombre'];
            $slug              = $actual['slug']; // mantenemos slug
            $descripcion_corta = isset($data->descripcion_corta) ? trim($data->descripcion_corta) : $actual['descripcion_corta'];
            $precio            = isset($data->precio)            ? (float)$data->precio           : (float)$actual['precio'];
            $precio_oferta     = (isset($data->precio_oferta) && $data->precio_oferta !== "")
                                    ? (float)$data->precio_oferta
                                    : $actual['precio_oferta'];
            $moneda            = isset($data->moneda)            ? $data->moneda                  : $actual['moneda'];
            $sku               = isset($data->sku)               ? trim($data->sku)               : $actual['sku'];
            $stock             = isset($data->stock)             ? (int)$data->stock              : (int)$actual['stock'];
            $stock_minimo      = isset($data->stock_minimo)      ? (int)$data->stock_minimo       : (int)$actual['stock_minimo'];
            $tipo_producto     = isset($data->tipo_producto)     ? $data->tipo_producto           : $actual['tipo_producto'];
            $estado_stock      = isset($data->estado_stock)
                                    ? $data->estado_stock
                                    : $actual['estado_stock'];
            $estado            = isset($data->estado)            ? (int)$data->estado             : (int)$actual['estado'];
            $publicado         = isset($data->publicado)         ? (int)$data->publicado          : (int)$actual['publicado'];
            $destacado         = isset($data->destacado)         ? (int)$data->destacado          : (int)$actual['destacado'];

            $vendedor_usuario_id = isset($data->vendedor_usuario_id)
                                        ? (int)$data->vendedor_usuario_id
                                        : $actual['vendedor_usuario_id'];
            $categoria_id        = isset($data->categoria_id)
                                        ? (int)$data->categoria_id
                                        : $actual['categoria_id'];

            // Si no mandan estado_stock pero stock cambió, lo ajustamos
            if (!isset($data->estado_stock)) {
                $estado_stock = $stock > 0 ? 'in_stock' : 'out_of_stock';
            }

            $stmt = $conn->prepare("
                UPDATE productos
                   SET vendedor_usuario_id = :vendedor_usuario_id,
                       categoria_id        = :categoria_id,
                       nombre              = :nombre,
                       slug                = :slug,
                       descripcion_corta   = :descripcion_corta,
                       precio              = :precio,
                       precio_oferta       = :precio_oferta,
                       moneda              = :moneda,
                       sku                 = :sku,
                       stock               = :stock,
                       stock_minimo        = :stock_minimo,
                       estado_stock        = :estado_stock,
                       tipo_producto       = :tipo_producto,
                       estado              = :estado,
                       publicado           = :publicado,
                       destacado           = :destacado,
                       fecha_hora_modificado = NOW()
                 WHERE id = :id
            ");

            $ok = $stmt->execute([
                ':vendedor_usuario_id' => $vendedor_usuario_id,
                ':categoria_id'        => $categoria_id,
                ':nombre'              => $nombre,
                ':slug'                => $slug,
                ':descripcion_corta'   => $descripcion_corta,
                ':precio'              => $precio,
                ':precio_oferta'       => $precio_oferta,
                ':moneda'              => $moneda,
                ':sku'                 => $sku,
                ':stock'               => $stock,
                ':stock_minimo'        => $stock_minimo,
                ':estado_stock'        => $estado_stock,
                ':tipo_producto'       => $tipo_producto,
                ':estado'              => $estado,
                ':publicado'           => $publicado,
                ':destacado'           => $destacado,
                ':id'                  => $id
            ]);

            echo json_encode(["success" => $ok]);
            break;

        // ============================== DELETE ================================
        case 'DELETE':
            parse_str(file_get_contents("php://input"), $data);

            if (empty($data['id'])) {
                echo json_encode(["success" => false, "error" => "ID de producto requerido."]);
                break;
            }

            $id = (int)$data['id'];

            $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
            $ok = $stmt->execute([$id]);

            echo json_encode(["success" => $ok]);
            break;

        // =====================================================================
        default:
            http_response_code(405);
            echo json_encode(["success" => false, "error" => "Método no permitido"]);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error"   => "Error en el servidor: " . $e->getMessage()
    ]);
}
