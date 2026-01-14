<?php
// backend/controllers/CategoriaController.php
require_once __DIR__ . '/../config/Conexion.php';

class CategoriaController
{
    /**
     * Obtiene las categorías activas de la base de datos
     * @param PDO $conn Conexión a la base de datos
     * @return array Lista de categorías
     */
    public static function getCategoriasActivas($conn)
    {
        try {
            $stmt = $conn->query("SELECT id, nombre FROM productos_categorias WHERE estado = 1 ORDER BY nombre ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CategoriaController: " . $e->getMessage());
            return [];
        }
    }
}
