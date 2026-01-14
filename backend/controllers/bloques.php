<?php
/**
 * API: Bloques Dinámicos
 * Gestiona las operaciones CRUD de bloques de tienda
 */

require_once '../config/Conexion.php';
require_once '../models/BloqueTienda.php';

header('Content-Type: application/json');

$op = $_GET['op'] ?? '';

switch ($op) {
    case 'obtener':
        obtenerBloques();
        break;
        
    case 'guardar':
        guardarBloques();
        break;
        
    case 'reordenar':
        reordenarBloques();
        break;
        
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Operación no válida'
        ]);
}

/**
 * Obtener bloques de una tienda
 */
function obtenerBloques() {
    $idTienda = $_GET['id_tienda'] ?? null;
    
    if (!$idTienda) {
        echo json_encode([
            'success' => false,
            'message' => 'ID de tienda requerido'
        ]);
        return;
    }
    
    $bloques = BloqueTienda::obtenerBloques($idTienda);
    
    if ($bloques) {
        echo json_encode([
            'success' => true,
            'data' => $bloques
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al obtener bloques'
        ]);
    }
}

/**
 * Guardar configuración de bloques
 */
function guardarBloques() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $idTienda = $data['id_tienda'] ?? null;
    $bloques = $data['bloques'] ?? null;
    
    if (!$idTienda || !$bloques) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos'
        ]);
        return;
    }
    
    $resultado = BloqueTienda::guardarBloques($idTienda, $bloques);
    
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Bloques guardados correctamente' : 'Error al guardar bloques'
    ]);
}

/**
 * Reordenar bloques
 */
function reordenarBloques() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $idTienda = $data['id_tienda'] ?? null;
    $nuevoOrden = $data['orden'] ?? null;
    
    if (!$idTienda || !$nuevoOrden) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos'
        ]);
        return;
    }
    
    $resultado = BloqueTienda::reordenarBloques($idTienda, $nuevoOrden);
    
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Bloques reordenados correctamente' : 'Error al reordenar bloques'
    ]);
}
