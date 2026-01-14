<?php
/**
 * Modelo: BloqueTienda
 * Gestiona la configuración de bloques dinámicos de las tiendas
 */

class BloqueTienda {
    
    /**
     * Obtener configuración de bloques de una tienda
     * @param int $idTienda
     * @return array|null
     */
    public static function obtenerBloques($idTienda) {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();
        
        try {
            $sql = "SELECT bloques_config, layout_modelo FROM tiendas WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $idTienda, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && !empty($result['bloques_config'])) {
                return json_decode($result['bloques_config'], true);
            }
            
            // Si no existe configuración, retornar configuración por defecto
            return self::obtenerBloquesDefault($result['layout_modelo'] ?? 1);
            
        } catch (PDOException $e) {
            error_log("Error al obtener bloques: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Guardar configuración de bloques
     * @param int $idTienda
     * @param array $bloques
     * @return bool
     */
    public static function guardarBloques($idTienda, $bloques) {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();
        
        try {
            $bloquesJson = json_encode($bloques);
            
            $sql = "UPDATE tiendas SET bloques_config = :bloques WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':bloques', $bloquesJson, PDO::PARAM_STR);
            $stmt->bindParam(':id', $idTienda, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error al guardar bloques: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener configuración por defecto según plantilla
     * @param int $layoutModelo (1, 2, 3)
     * @return array
     */
    public static function obtenerBloquesDefault($layoutModelo) {
        $bloquesBase = [
            [
                'id' => 'banner-principal',
                'tipo' => 'banner',
                'orden' => 1,
                'visible' => true,
                'nombre' => 'Banner Principal'
            ],
            [
                'id' => 'info-tienda',
                'tipo' => 'info-card',
                'orden' => 2,
                'visible' => true,
                'nombre' => 'Info Tienda'
            ],
            [
                'id' => 'productos-scroll',
                'tipo' => 'productos-scroll',
                'orden' => 3,
                'visible' => true,
                'nombre' => 'Productos Destacados'
            ],
            [
                'id' => 'grid-productos',
                'tipo' => 'productos-grid',
                'orden' => 4,
                'visible' => true,
                'nombre' => 'Selecciones Destacadas'
            ]
        ];
        
        // Agregar sidebar solo para plantillas 1 y 2
        if ($layoutModelo != 3) {
            $bloquesBase[] = [
                'id' => 'sidebar',
                'tipo' => 'sidebar',
                'orden' => 5,
                'visible' => true,
                'nombre' => 'Sidebar'
            ];
        }
        
        // Agregar bloques específicos de plantilla 3
        if ($layoutModelo == 3) {
            $bloquesBase[] = [
                'id' => 'banner-publicitario',
                'tipo' => 'anuncios',
                'orden' => 5,
                'visible' => true,
                'nombre' => 'Banner Publicitario'
            ];
            $bloquesBase[] = [
                'id' => 'ofertas-dia',
                'tipo' => 'productos-grid',
                'orden' => 6,
                'visible' => true,
                'nombre' => 'Ofertas del Día'
            ];
        }
        
        return ['bloques' => $bloquesBase];
    }
    
    /**
     * Reordenar bloques
     * @param int $idTienda
     * @param array $nuevoOrden - Array de IDs en el nuevo orden
     * @return bool
     */
    public static function reordenarBloques($idTienda, $nuevoOrden) {
        $bloquesActuales = self::obtenerBloques($idTienda);
        
        if (!$bloquesActuales || !isset($bloquesActuales['bloques'])) {
            return false;
        }
        
        // Crear mapa de bloques por ID
        $mapaBloque = [];
        foreach ($bloquesActuales['bloques'] as $bloque) {
            $mapaBloque[$bloque['id']] = $bloque;
        }
        
        // Reordenar según nuevo orden
        $bloquesReordenados = [];
        foreach ($nuevoOrden as $index => $id) {
            if (isset($mapaBloque[$id])) {
                $bloque = $mapaBloque[$id];
                $bloque['orden'] = $index + 1;
                $bloquesReordenados[] = $bloque;
            }
        }
        
        return self::guardarBloques($idTienda, ['bloques' => $bloquesReordenados]);
    }
}
