<?php
// c:\xampp\htdocs\lyrium\frontend\buscar.php
session_start();

// 1. Incluir archivos necesarios
require_once __DIR__ . '/../backend/config/Conexion.php';
include 'header.php';

// 2. Obtener parámetros de búsqueda y filtros
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$catId = isset($_GET['category']) && !empty($_GET['category']) ? (int) $_GET['category'] : null;
$maxPrice = isset($_GET['max_price']) && !empty($_GET['max_price']) ? (float) $_GET['max_price'] : null;

// 3. Preparar la consulta
$productos = [];
try {
    $sql = "
        SELECT DISTINCT p.*, pi.url_imagen, cat.nombre as categoria_nombre
        FROM productos p
        LEFT JOIN (
            SELECT producto_id, MIN(url) as url_imagen 
            FROM productos_imagenes 
            GROUP BY producto_id
        ) pi ON p.id = pi.producto_id
        LEFT JOIN productos_categorias_rel rel ON p.id = rel.producto_id
        LEFT JOIN productos_categorias cat ON rel.categoria_id = cat.id
        WHERE p.estado = 1
    ";

    $params = [];

    // Filtro por texto
    if (!empty($query)) {
        $sql .= " AND (p.nombre LIKE :query OR p.descripcion_corta LIKE :query OR p.meta_keywords LIKE :query)";
        $params[':query'] = "%$query%";
    }

    // Filtro por categoría
    if ($catId) {
        $sql .= " AND rel.categoria_id = :cat_id";
        $params[':cat_id'] = $catId;
    }

    // Filtro por precio (toma el menor entre precio y precio_oferta)
    if ($maxPrice) {
        $sql .= " AND (
            (p.precio_oferta > 0 AND p.precio_oferta <= :max_price) 
            OR (p.precio_oferta <= 0 AND p.precio <= :max_price)
        )";
        $params[':max_price'] = $maxPrice;
    }

    $sql .= " ORDER BY p.fecha_hora_creado DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error al realizar la búsqueda: " . $e->getMessage();
}
?>

<main class="max-w-7xl mx-auto px-4 py-10 flex-1 space-y-8">

    <!-- Encabezado de Resultados -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Resultados para: <span class="text-sky-600">"<?php echo htmlspecialchars($query); ?>"</span>
            </h1>
            <p class="text-gray-500 mt-1">
                <?php echo count($productos); ?> productos encontrados
            </p>
        </div>

        <!-- Botón Volver -->
        <a href="index.php" class="inline-flex items-center gap-2 text-sky-600 font-semibold hover:underline">
            <i class="ph-arrow-left"></i>
            Volver al inicio
        </a>
    </div>

    <!-- Rejilla de Productos -->
    <?php if (!empty($productos)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($productos as $p): ?>
                <article
                    class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                    <!-- Imagen -->
                    <div class="h-60 bg-gray-50 relative overflow-hidden">
                        <?php
                        $img = !empty($p['url_imagen']) ? $p['url_imagen'] : 'img/placeholder.png';
                        // Ajuste de ruta si es necesario (asumiendo que url_imagen es relativa a la raíz o absoluta)
                        ?>
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($p['nombre']); ?>"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <?php if ($p['precio_oferta']): ?>
                            <span
                                class="absolute top-4 left-4 bg-lime-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                                Oferta
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Contenido -->
                    <div class="p-5 space-y-3">
                        <div class="flex flex-col gap-1">
                            <?php if (!empty($p['categoria_nombre'])): ?>
                                <span class="text-[10px] font-bold text-sky-500 uppercase tracking-wider">
                                    <?php echo htmlspecialchars($p['categoria_nombre']); ?>
                                </span>
                            <?php endif; ?>
                            <h3 class="text-sm font-bold text-gray-900 line-clamp-2">
                                <?php echo htmlspecialchars($p['nombre']); ?>
                            </h3>
                        </div>

                        <p class="text-[11px] text-gray-500 line-clamp-2">
                            <?php echo htmlspecialchars($p['descripcion_corta']); ?>
                        </p>

                        <div class="pt-2 flex items-center justify-between">
                            <div class="flex flex-col">
                                <?php if ($p['precio_oferta']): ?>
                                    <span class="text-xs text-gray-400 line-through">S/
                                        <?php echo number_format($p['precio'], 2); ?></span>
                                    <span class="text-lg font-bold text-sky-600">S/
                                        <?php echo number_format($p['precio_oferta'], 2); ?></span>
                                <?php else: ?>
                                    <span class="text-lg font-bold text-sky-600">S/
                                        <?php echo number_format($p['precio'], 2); ?></span>
                                <?php endif; ?>
                            </div>

                            <button class="bg-sky-500 hover:bg-sky-600 text-white p-2.5 rounded-full transition-colors"
                                title="Agregar al carrito">
                                <i class="ph-shopping-cart-simple text-xl"></i>
                            </button>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- No hay resultados -->
        <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center text-gray-300">
                <i class="ph-smiley-blank text-6xl"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900">No encontramos lo que buscas</h2>
            <p class="text-gray-500 max-w-sm">
                Intenta con otras palabras clave o revisa nuestras categorías principales.
            </p>
            <a href="index.php"
                class="bg-gray-900 text-white px-8 py-3 rounded-full font-bold hover:scale-105 transition-transform">
                Regresar a la tienda
            </a>
        </div>
    <?php endif; ?>

</main>

<?php include 'footer.php'; ?>