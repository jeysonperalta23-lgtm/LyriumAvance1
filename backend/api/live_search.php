<?php
// backend/api/live_search.php
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . '/../config/Conexion.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($query)) {
    echo json_encode(['categories' => [], 'category_products' => (object) [], 'products_match' => []]);
    exit;
}

try {
    // 1. Buscar Categorías que coinciden por nombre O que tienen productos que coinciden
    $stmtCat = $conn->prepare("
        SELECT DISTINCT cat.id, cat.nombre 
        FROM productos_categorias cat
        LEFT JOIN productos p ON cat.id = p.categoria_id
        WHERE (cat.nombre LIKE :q1 OR p.nombre LIKE :q2)
        AND cat.estado = 1
        LIMIT 10
    ");
    $stmtCat->execute([
        ':q1' => "%$query%",
        ':q2' => "%$query%"
    ]);
    $categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

    $categoryIds = array_column($categories, 'id');
    $category_products = [];

    // 2. Si hay categorías, buscar sus productos destacados que coincidan con la búsqueda
    if (!empty($categoryIds)) {
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $stmtProdCat = $conn->prepare("
            SELECT p.id, p.nombre, p.precio, p.precio_oferta, pi.url_imagen, p.categoria_id, cat.nombre as categoria_nombre
            FROM productos p
            INNER JOIN productos_categorias cat ON p.categoria_id = cat.id
            LEFT JOIN (
                SELECT producto_id, MIN(url) as url_imagen 
                FROM productos_imagenes 
                GROUP BY producto_id
            ) pi ON p.id = pi.producto_id
            WHERE p.categoria_id IN ($placeholders)
            AND p.estado = 1
            AND (p.nombre LIKE ? OR cat.nombre LIKE ?)
            LIMIT 30
        ");

        $params = array_merge($categoryIds, ["%$query%", "%$query%"]);
        $stmtProdCat->execute($params);

        while ($row = $stmtProdCat->fetch(PDO::FETCH_ASSOC)) {
            $category_products[$row['categoria_id']][] = $row;
        }
    }

    // 3. Buscar productos que coincidan directamente con el nombre
    $stmtProdMatch = $conn->prepare("
        SELECT p.id, p.nombre, p.precio, p.precio_oferta, pi.url_imagen, cat.nombre as categoria_nombre
        FROM productos p
        INNER JOIN productos_categorias cat ON p.categoria_id = cat.id
        LEFT JOIN (
            SELECT producto_id, MIN(url) as url_imagen 
            FROM productos_imagenes 
            GROUP BY producto_id
        ) pi ON p.id = pi.producto_id
        WHERE p.nombre LIKE :q3
        AND p.estado = 1
        LIMIT 8
    ");
    $stmtProdMatch->execute([':q3' => "%$query%"]);
    $products_match = $stmtProdMatch->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'categories' => $categories,
        'category_products' => (object) $category_products,
        'products_match' => $products_match
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
