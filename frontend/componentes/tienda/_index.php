<?php
/**
 * COMPONENTES DE TIENDA - ÍNDICE
 * 
 * Este directorio contiene los componentes reutilizables para la vista pública
 * de tiendas (tienda.php). Todos los componentes están diseñados para ser
 * incluidos con las variables necesarias ya definidas.
 * 
 * ===============================================
 * ESTRUCTURA DE ARCHIVOS
 * ===============================================
 * 
 * componentes/tienda/
 * ├── _index.php              → Este archivo (documentación)
 * ├── producto-card.php       → Tarjeta de producto individual
 * ├── productos-scroll.php    → Sección con scroll horizontal
 * ├── productos-grid.php      → Grid de productos (3 o 4 columnas)
 * ├── productos-filtros.php   → Barra de filtros y búsqueda
 * ├── tienda-descripcion.php  → Caja con info de la tienda
 * ├── ad-banner-horizontal.php → Banner publicitario horizontal
 * └── ad-banner-grande.php    → Banner publicitario grande
 * 
 * ===============================================
 * USO BÁSICO
 * ===============================================
 * 
 * 1. TARJETA DE PRODUCTO:
 *    $producto = ['id' => 1, 'nombre' => 'Producto', ...];
 *    include 'componentes/tienda/producto-card.php';
 * 
 * 2. SCROLL DE PRODUCTOS:
 *    $productos = [...]; // Array de productos
 *    $titulo = 'Productos Destacados';
 *    $icono = 'ph-star-fill';
 *    include 'componentes/tienda/productos-scroll.php';
 * 
 * 3. GRID DE PRODUCTOS:
 *    $productos = [...];
 *    $titulo = 'Todos los Productos';
 *    $grid_cols = '4'; // '3' o '4' columnas
 *    include 'componentes/tienda/productos-grid.php';
 * 
 * 4. FILTROS:
 *    $categorias = [['nombre' => 'Cat 1', 'slug' => 'cat-1'], ...];
 *    include 'componentes/tienda/productos-filtros.php';
 * 
 * ===============================================
 * VARIABLES GLOBALES ESPERADAS
 * ===============================================
 * 
 * Estos componentes esperan que las siguientes variables
 * estén definidas en el contexto padre:
 * 
 * - $tienda: Array con datos de la tienda
 * - $plan: 'basico' o 'premium'
 * - $productos: Array de productos
 * - $categorias: Array de categorías (para filtros)
 * 
 * ===============================================
 * NOTAS PARA DESARROLLADORES
 * ===============================================
 * 
 * - Los componentes limpian sus variables locales al final
 *   para evitar conflictos en bucles
 * - Usar __DIR__ para includes relativos dentro de componentes
 * - Las rutas de imágenes son relativas a frontend/
 * 
 * @see frontend/tienda.php - Archivo principal que usa estos componentes
 * @see frontend/utils/css/tienda.css - Estilos de los componentes
 */

// Este archivo es solo documentación, no ejecutar
return;
