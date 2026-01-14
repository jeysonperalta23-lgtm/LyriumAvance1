<?php
// backend/api/tienda.php
// API para gestión de tiendas del marketplace

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/Conexion.php'; // $conn (PDO)

$method = $_SERVER['REQUEST_METHOD'];
$op = isset($_GET['op']) ? $_GET['op'] : '';

/**
 * Genera un slug único para tienda
 */
function generarSlugTienda(PDO $conn, $nombreBase, $tiendaId = null) {
    $base = strtolower(trim($nombreBase));
    $base = strtr($base, [
        'á' => 'a', 'é' => 'e', 'í' => 'i',
        'ó' => 'o', 'ú' => 'u', 'ñ' => 'n'
    ]);
    $base = preg_replace('/[^a-z0-9]+/', '-', $base);
    $base = trim($base, '-');
    
    if (empty($base)) $base = 'tienda';
    
    // Verificar si existe
    $sql = "SELECT COUNT(*) FROM tiendas WHERE slug = :slug";
    $params = [':slug' => $base];
    
    if ($tiendaId) {
        $sql .= " AND id != :id";
        $params[':id'] = $tiendaId;
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $count = (int)$stmt->fetchColumn();
    
    if ($count === 0) {
        return $base;
    }
    
    // Si existe, agregar número
    $contador = 1;
    while (true) {
        $slugNuevo = $base . '-' . $contador;
        $stmt = $conn->prepare($sql);
        $params[':slug'] = $slugNuevo;
        $stmt->execute($params);
        
        if ($stmt->fetchColumn() === 0) {
            return $slugNuevo;
        }
        $contador++;
    }
}

try {
    
    // ========================================================================
    // OPERACIONES ESPECIALES (con parámetro ?op=)
    // ========================================================================
    
    if (!empty($op)) {
        switch ($op) {
            
            // ================================================================
            // LOGIN DE TIENDA
            // ================================================================
            case 'login':
                if ($method !== 'POST') {
                    echo json_encode(["success" => false, "error" => "Método no permitido"]);
                    exit;
                }
                
                $data = json_decode(file_get_contents("php://input"));
                
                if (empty($data->email) || empty($data->password)) {
                    echo json_encode([
                        "success" => false,
                        "error" => "Email y contraseña son requeridos"
                    ]);
                    exit;
                }
                
                $stmt = $conn->prepare("
                    SELECT id, email, password, nombre_tienda, slug, plan, estado
                    FROM tiendas
                    WHERE email = :email
                ");
                $stmt->execute([':email' => $data->email]);
                $tienda = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$tienda) {
                    echo json_encode([
                        "success" => false,
                        "error" => "Credenciales incorrectas"
                    ]);
                    exit;
                }
                
                // Verificar password
                if (!password_verify($data->password, $tienda['password'])) {
                    echo json_encode([
                        "success" => false,
                        "error" => "Credenciales incorrectas"
                    ]);
                    exit;
                }
                
                // Verificar estado
                if ($tienda['estado'] !== 'activo') {
                    $mensajes = [
                        'pendiente' => 'Tu tienda está pendiente de aprobación',
                        'suspendido' => 'Tu tienda ha sido suspendida',
                        'rechazado' => 'Tu solicitud de tienda fue rechazada',
                        'borrador' => 'Tu tienda está en borrador'
                    ];
                    echo json_encode([
                        "success" => false,
                        "error" => $mensajes[$tienda['estado']] ?? 'Tu tienda no está activa'
                    ]);
                    exit;
                }
                
                // Actualizar último acceso
                $stmt = $conn->prepare("UPDATE tiendas SET ultimo_acceso = NOW() WHERE id = ?");
                $stmt->execute([$tienda['id']]);
                
                // Iniciar sesión
                session_start();
                $_SESSION['tienda_id'] = $tienda['id'];
                $_SESSION['tienda_email'] = $tienda['email'];
                $_SESSION['tienda_nombre'] = $tienda['nombre_tienda'];
                $_SESSION['tienda_slug'] = $tienda['slug'];
                $_SESSION['tienda_plan'] = $tienda['plan'];
                
                unset($tienda['password']);
                
                echo json_encode([
                    "success" => true,
                    "mensaje" => "Login exitoso",
                    "tienda" => $tienda
                ]);
                exit;
            
            // ================================================================
            // REGISTRO DE TIENDA (Público)
            // ================================================================
            case 'registrar':
                if ($method !== 'POST') {
                    echo json_encode(["success" => false, "error" => "Método no permitido"]);
                    exit;
                }
                
                $data = json_decode(file_get_contents("php://input"));
                
                // Validaciones
                if (empty($data->email) || empty($data->password) || empty($data->nombre_tienda)) {
                    echo json_encode([
                        "success" => false,
                        "error" => "Email, contraseña y nombre de tienda son requeridos"
                    ]);
                    exit;
                }
                
                // Verificar si email ya existe
                $stmt = $conn->prepare("SELECT COUNT(*) FROM tiendas WHERE email = ?");
                $stmt->execute([$data->email]);
                if ($stmt->fetchColumn() > 0) {
                    echo json_encode([
                        "success" => false,
                        "error" => "Este email ya está registrado"
                    ]);
                    exit;
                }
                
                $slug = generarSlugTienda($conn, $data->nombre_tienda);
                $passwordHash = password_hash($data->password, PASSWORD_DEFAULT);
                $plan = isset($data->plan) && $data->plan === 'premium' ? 'premium' : 'basico';
                
                // Límites según plan
                $limites = [
                    'basico' => ['productos' => 50, 'imagenes' => 3, 'categorias' => 10],
                    'premium' => ['productos' => -1, 'imagenes' => 10, 'categorias' => -1]
                ];
                
                $stmt = $conn->prepare("
                    INSERT INTO tiendas (
                        email,
                        password,
                        nombre_tienda,
                        slug,
                        descripcion,
                        plan,
                        estado,
                        telefono,
                        ciudad,
                        limite_productos,
                        limite_imagenes_por_producto,
                        limite_categorias,
                        fecha_hora_creado
                    ) VALUES (
                        :email,
                        :password,
                        :nombre_tienda,
                        :slug,
                        :descripcion,
                        :plan,
                        'pendiente',
                        :telefono,
                        :ciudad,
                        :limite_productos,
                        :limite_imagenes,
                        :limite_categorias,
                        NOW()
                    )
                ");
                
                $ok = $stmt->execute([
                    ':email' => $data->email,
                    ':password' => $passwordHash,
                    ':nombre_tienda' => $data->nombre_tienda,
                    ':slug' => $slug,
                    ':descripcion' => $data->descripcion ?? null,
                    ':plan' => $plan,
                    ':telefono' => $data->telefono ?? null,
                    ':ciudad' => $data->ciudad ?? null,
                    ':limite_productos' => $limites[$plan]['productos'],
                    ':limite_imagenes' => $limites[$plan]['imagenes'],
                    ':limite_categorias' => $limites[$plan]['categorias']
                ]);
                
                if ($ok) {
                    $tiendaId = $conn->lastInsertId();
                    
                    // TODO: Enviar email de confirmación
                    
                    echo json_encode([
                        "success" => true,
                        "mensaje" => "Tienda registrada exitosamente. Recibirás un email cuando sea aprobada.",
                        "tienda_id" => $tiendaId,
                        "slug" => $slug
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "error" => "Error al registrar tienda"
                    ]);
                }
                exit;
            
            // ================================================================
            // ACTUALIZAR CONFIGURACIÓN (Layout y Tema)
            // ================================================================
            case 'actualizar_configuracion':
                if ($method !== 'POST') {
                    echo json_encode(["success" => false, "error" => "Método no permitido"]);
                    exit;
                }
                
                session_start();
                if (!isset($_SESSION['tienda_id'])) {
                    echo json_encode([
                        "success" => false,
                        "error" => "No autenticado"
                    ]);
                    exit;
                }
                
                $data = json_decode(file_get_contents("php://input"));
                $tiendaId = $_SESSION['tienda_id'];
                
                // Obtener plan actual
                $stmt = $conn->prepare("SELECT plan FROM tiendas WHERE id = ?");
                $stmt->execute([$tiendaId]);
                $tienda = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$tienda) {
                    echo json_encode([
                        "success" => false,
                        "error" => "Tienda no encontrada"
                    ]);
                    exit;
                }
                
                $layout = isset($data->layout_modelo) ? (int)$data->layout_modelo : 1;
                $tema = isset($data->tema) ? $data->tema : '';
                
                // Verificar permisos según plan
                if ($tienda['plan'] === 'basico') {
                    if ($layout !== 1) {
                        echo json_encode([
                            "success" => false,
                            "error" => "Los layouts 2 y 3 requieren Plan Premium"
                        ]);
                        exit;
                    }
                    if (!empty($tema)) {
                        echo json_encode([
                            "success" => false,
                            "error" => "Los temas personalizados requieren Plan Premium"
                        ]);
                        exit;
                    }
                }
                
                $stmt = $conn->prepare("
                    UPDATE tiendas
                    SET layout_modelo = :layout,
                        tema = :tema,
                        fecha_hora_modificado = NOW()
                    WHERE id = :id
                ");
                
                $ok = $stmt->execute([
                    ':layout' => $layout,
                    ':tema' => $tema,
                    ':id' => $tiendaId
                ]);
                
                echo json_encode([
                    "success" => $ok,
                    "mensaje" => $ok ? "Configuración actualizada" : "Error al actualizar"
                ]);
                exit;
            
            // ================================================================
            // LOGOUT
            // ================================================================
            case 'logout':
                session_start();
                session_destroy();
                echo json_encode([
                    "success" => true,
                    "mensaje" => "Sesión cerrada"
                ]);
                exit;
            
            default:
                echo json_encode([
                    "success" => false,
                    "error" => "Operación no válida"
                ]);
                exit;
        }
    }
    
    // ========================================================================
    // CRUD ESTÁNDAR (sin parámetro ?op=)
    // ========================================================================
    
    switch ($method) {
        
        // ====================================================================
        // GET - Obtener tienda(s)
        // ====================================================================
        case 'GET':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
            $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
            
            $sql = "
                SELECT 
                    id,
                    email,
                    nombre_tienda,
                    slug,
                    descripcion,
                    logo_url,
                    banner_url,
                    plan,
                    fecha_inicio_plan,
                    fecha_fin_plan,
                    estado_pago,
                    telefono,
                    whatsapp,
                    direccion,
                    ciudad,
                    pais,
                    redes_sociales,
                    moneda,
                    permite_envios,
                    tiempo_preparacion_dias,
                    horarios,
                    tema,
                    layout_modelo,
                    estado,
                    verificado,
                    total_ventas,
                    total_productos,
                    total_pedidos,
                    calificacion_promedio,
                    total_resenas,
                    visitas_totales,
                    limite_productos,
                    limite_imagenes_por_producto,
                    limite_categorias,
                    fecha_hora_creado,
                    ultimo_acceso
                FROM tiendas
            ";
            
            $params = [];
            
            if ($id > 0) {
                $sql .= " WHERE id = :id";
                $params[':id'] = $id;
            } elseif (!empty($slug)) {
                $sql .= " WHERE slug = :slug";
                $params[':slug'] = $slug;
            }
            
            $sql .= " ORDER BY fecha_hora_creado DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (($id > 0 || !empty($slug)) && count($rows) === 1) {
                echo json_encode([
                    "success" => true,
                    "tienda" => $rows[0]
                ]);
            } else {
                echo json_encode([
                    "success" => true,
                    "tiendas" => $rows
                ]);
            }
            break;
        
        // ====================================================================
        // POST - Crear tienda (Admin)
        // ====================================================================
        case 'POST':
            // Similar a registrar pero desde admin
            echo json_encode([
                "success" => false,
                "error" => "Usa ?op=registrar para crear tiendas"
            ]);
            break;
        
        // ====================================================================
        // PUT - Actualizar tienda
        // ====================================================================
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));
            
            if (empty($data->id)) {
                echo json_encode(["success" => false, "error" => "ID de tienda requerido"]);
                break;
            }
            
            $id = (int)$data->id;
            
            // Obtener datos actuales
            $stmt = $conn->prepare("SELECT * FROM tiendas WHERE id = ?");
            $stmt->execute([$id]);
            $actual = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$actual) {
                echo json_encode(["success" => false, "error" => "Tienda no encontrada"]);
                break;
            }
            
            // Campos actualizables
            $nombre_tienda = isset($data->nombre_tienda) ? trim($data->nombre_tienda) : $actual['nombre_tienda'];
            $descripcion = isset($data->descripcion) ? trim($data->descripcion) : $actual['descripcion'];
            $telefono = isset($data->telefono) ? $data->telefono : $actual['telefono'];
            $whatsapp = isset($data->whatsapp) ? $data->whatsapp : $actual['whatsapp'];
            $direccion = isset($data->direccion) ? $data->direccion : $actual['direccion'];
            $ciudad = isset($data->ciudad) ? $data->ciudad : $actual['ciudad'];
            
            $stmt = $conn->prepare("
                UPDATE tiendas
                SET nombre_tienda = :nombre_tienda,
                    descripcion = :descripcion,
                    telefono = :telefono,
                    whatsapp = :whatsapp,
                    direccion = :direccion,
                    ciudad = :ciudad,
                    fecha_hora_modificado = NOW()
                WHERE id = :id
            ");
            
            $ok = $stmt->execute([
                ':nombre_tienda' => $nombre_tienda,
                ':descripcion' => $descripcion,
                ':telefono' => $telefono,
                ':whatsapp' => $whatsapp,
                ':direccion' => $direccion,
                ':ciudad' => $ciudad,
                ':id' => $id
            ]);
            
            echo json_encode(["success" => $ok]);
            break;
        
        // ====================================================================
        // DELETE - Eliminar tienda
        // ====================================================================
        case 'DELETE':
            parse_str(file_get_contents("php://input"), $data);
            
            if (empty($data['id'])) {
                echo json_encode(["success" => false, "error" => "ID de tienda requerido"]);
                break;
            }
            
            $id = (int)$data['id'];
            
            $stmt = $conn->prepare("DELETE FROM tiendas WHERE id = ?");
            $ok = $stmt->execute([$id]);
            
            echo json_encode(["success" => $ok]);
            break;
        
        default:
            http_response_code(405);
            echo json_encode(["success" => false, "error" => "Método no permitido"]);
            break;
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Error en el servidor: " . $e->getMessage()
    ]);
}
