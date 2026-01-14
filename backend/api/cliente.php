<?php
// C:\xampp\htdocs\schedule\backend\api\cliente.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/Conexion.php'; // $conn = PDO...

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        // ====================== GET ======================
        case 'GET':
            $sql = "
                SELECT 
                    c.id,
                    c.usuario_id,
                    c.persona_id,
                    c.codigo_cliente,
                    c.tipo_cliente,
                    c.documento_tipo,
                    c.documento_numero,
                    c.estado,
                    c.puntos,
                    c.observaciones,
                    p.nombre_razon_social,
                    p.documento_identidad,
                    pc.direccion      AS direccion_contacto,
                    pc.ciudad         AS ciudad_contacto,
                    pc.correo         AS correo_contacto,
                    pc.telefono       AS telefono_contacto
                FROM clientes c
                INNER JOIN personas p ON c.persona_id = p.id
                LEFT JOIN personas_contactos pc ON pc.persona_id = p.id
            ";

            $params = [];
            $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

            if ($id > 0) {
                $sql .= " WHERE c.id = :id";
                $params[':id'] = $id;
            }

            $sql .= " ORDER BY c.id DESC";

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($id > 0 && count($clientes) === 1) {
                echo json_encode([
                    "success" => true,
                    "cliente" => $clientes[0]
                ]);
            } else {
                echo json_encode([
                    "success"  => true,
                    "clientes" => $clientes
                ]);
            }
            break;

        // ====================== POST ======================
        case 'POST':
            $data = json_decode(file_get_contents("php://input"));

            if (
                empty($data->nombre_razon_social) ||
                empty($data->tipo_cliente) ||
                empty($data->documento_tipo) ||
                empty($data->documento_numero)
            ) {
                echo json_encode([
                    "success" => false,
                    "error"   => "Nombre, tipo de cliente, tipo y número de documento son obligatorios."
                ]);
                break;
            }

            $conn->beginTransaction();

            $nombrePersona      = trim($data->nombre_razon_social);
            $tipoCliente        = trim($data->tipo_cliente);
            $documentoTipo      = trim($data->documento_tipo);
            $documentoNumero    = trim($data->documento_numero);

            $correoContacto     = !empty($data->correo_contacto) ? trim($data->correo_contacto) : null;
            $telefonoContacto   = !empty($data->telefono_contacto) ? trim($data->telefono_contacto) : null;
            $direccionContacto  = !empty($data->direccion_contacto) ? trim($data->direccion_contacto) : null;
            $ciudadContacto     = !empty($data->ciudad_contacto) ? trim($data->ciudad_contacto) : null;
            $observaciones      = !empty($data->observaciones) ? trim($data->observaciones) : null;

            // Normalizamos tipo_cliente
            if (!in_array($tipoCliente, ['Persona', 'Empresa'])) {
                $tipoCliente = 'Persona';
            }

            // Buscar persona por documento_identidad
            $stmt = $conn->prepare("SELECT id FROM personas WHERE documento_identidad = ?");
            $stmt->execute([$documentoNumero]);
            $persona = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($persona) {
                $personaId = (int)$persona['id'];
                // Actualizamos nombre/documento si cambió.
                $stmt = $conn->prepare("
                    UPDATE personas
                       SET nombre_razon_social = ?,
                           documento_identidad = ?
                     WHERE id = ?
                ");
                $stmt->execute([$nombrePersona, $documentoNumero, $personaId]);
            } else {
                // Crear nueva persona
                $stmt = $conn->prepare("
                    INSERT INTO personas (tipo_persona_id, nombre_razon_social, documento_identidad)
                    VALUES (1, ?, ?)
                ");
                $stmt->execute([$nombrePersona, $documentoNumero]);
                $personaId = (int)$conn->lastInsertId();
            }

            // Insertar/actualizar contacto principal (simple: insert nuevo)
            if ($correoContacto || $telefonoContacto || $direccionContacto || $ciudadContacto) {
                $stmt = $conn->prepare("
                    INSERT INTO personas_contactos (persona_id, direccion, ciudad, correo, telefono)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $personaId,
                    $direccionContacto,
                    $ciudadContacto,
                    $correoContacto,
                    $telefonoContacto
                ]);
            }

            // Insertar cliente
            $estado = isset($data->estado) ? (int)$data->estado : 1;
            $puntos = isset($data->puntos) ? (int)$data->puntos : 0;
            $codigoCliente = !empty($data->codigo_cliente) ? trim($data->codigo_cliente) : null;

            $stmt = $conn->prepare("
                INSERT INTO clientes (
                    usuario_id,
                    persona_id,
                    codigo_cliente,
                    tipo_cliente,
                    documento_tipo,
                    documento_numero,
                    estado,
                    puntos,
                    observaciones
                )
                VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $success = $stmt->execute([
                $personaId,
                $codigoCliente,
                $tipoCliente,
                $documentoTipo,
                $documentoNumero,
                $estado,
                $puntos,
                $observaciones
            ]);

            $conn->commit();

            echo json_encode(["success" => $success]);
            break;

        // ====================== PUT ======================
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));

            if (empty($data->id)) {
                echo json_encode(["success" => false, "error" => "ID de cliente requerido."]);
                break;
            }

            $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmt->execute([$data->id]);
            $clienteActual = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$clienteActual) {
                echo json_encode(["success" => false, "error" => "Cliente no encontrado."]);
                break;
            }

            $conn->beginTransaction();

            $tipoCliente      = isset($data->tipo_cliente) ? trim($data->tipo_cliente) : $clienteActual['tipo_cliente'];
            $documentoTipo    = isset($data->documento_tipo) ? trim($data->documento_tipo) : $clienteActual['documento_tipo'];
            $documentoNumero  = isset($data->documento_numero) ? trim($data->documento_numero) : $clienteActual['documento_numero'];

            if (!in_array($tipoCliente, ['Persona', 'Empresa'])) {
                $tipoCliente = $clienteActual['tipo_cliente'];
            }

            $estado = isset($data->estado) ? (int)$data->estado : (int)$clienteActual['estado'];
            $puntos = isset($data->puntos) ? (int)$data->puntos : (int)$clienteActual['puntos'];

            $observaciones = isset($data->observaciones)
                ? trim((string)$data->observaciones)
                : $clienteActual['observaciones'];

            $codigoCliente = isset($data->codigo_cliente)
                ? trim((string)$data->codigo_cliente)
                : $clienteActual['codigo_cliente'];

            // Actualización de persona asociada
            $personaId = (int)$clienteActual['persona_id'];

            if ($personaId > 0) {
                $stmt = $conn->prepare("SELECT * FROM personas WHERE id = ?");
                $stmt->execute([$personaId]);
                $personaActual = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($personaActual) {
                  $nombrePersona = isset($data->nombre_razon_social)
                    ? trim($data->nombre_razon_social)
                    : $personaActual['nombre_razon_social'];

                  $documentoIdentidad = $documentoNumero ?: $personaActual['documento_identidad'];

                  $stmt = $conn->prepare("
                    UPDATE personas
                       SET nombre_razon_social = ?,
                           documento_identidad = ?
                     WHERE id = ?
                  ");
                  $stmt->execute([
                    $nombrePersona,
                    $documentoIdentidad,
                    $personaId
                  ]);
                }
            }

            // Actualizar/insertar contacto (tomamos uno simple)
            $correoContacto    = isset($data->correo_contacto)    ? trim((string)$data->correo_contacto)    : null;
            $telefonoContacto  = isset($data->telefono_contacto)  ? trim((string)$data->telefono_contacto)  : null;
            $direccionContacto = isset($data->direccion_contacto) ? trim((string)$data->direccion_contacto) : null;
            $ciudadContacto    = isset($data->ciudad_contacto)    ? trim((string)$data->ciudad_contacto)    : null;

            if ($personaId > 0) {
                // Buscamos si ya tiene al menos un contacto
                $stmt = $conn->prepare("SELECT id FROM personas_contactos WHERE persona_id = ? LIMIT 1");
                $stmt->execute([$personaId]);
                $contacto = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($contacto) {
                    $contactoId = (int)$contacto['id'];

                    $stmt = $conn->prepare("
                        UPDATE personas_contactos
                           SET direccion = COALESCE(?, direccion),
                               ciudad    = COALESCE(?, ciudad),
                               correo    = COALESCE(?, correo),
                               telefono  = COALESCE(?, telefono)
                         WHERE id = ?
                    ");
                    $stmt->execute([
                        $direccionContacto ?: null,
                        $ciudadContacto    ?: null,
                        $correoContacto    ?: null,
                        $telefonoContacto  ?: null,
                        $contactoId
                    ]);
                } else {
                    if ($correoContacto || $telefonoContacto || $direccionContacto || $ciudadContacto) {
                        $stmt = $conn->prepare("
                            INSERT INTO personas_contactos (persona_id, direccion, ciudad, correo, telefono)
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([
                            $personaId,
                            $direccionContacto ?: null,
                            $ciudadContacto    ?: null,
                            $correoContacto    ?: null,
                            $telefonoContacto  ?: null
                        ]);
                    }
                }
            }

            // Actualizar cliente
            $stmt = $conn->prepare("
                UPDATE clientes
                   SET codigo_cliente   = ?,
                       tipo_cliente     = ?,
                       documento_tipo   = ?,
                       documento_numero = ?,
                       estado           = ?,
                       puntos           = ?,
                       observaciones    = ?
                 WHERE id = ?
            ");
            $success = $stmt->execute([
                $codigoCliente,
                $tipoCliente,
                $documentoTipo,
                $documentoNumero,
                $estado,
                $puntos,
                $observaciones,
                $data->id
            ]);

            $conn->commit();

            echo json_encode(["success" => $success]);
            break;

        // ====================== DELETE ======================
        case 'DELETE':
            parse_str(file_get_contents("php://input"), $data);

            if (empty($data['id'])) {
                echo json_encode(["success" => false, "error" => "ID de cliente requerido."]);
                break;
            }

            // Igual que usuarios.php: borrado físico
            $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
            $success = $stmt->execute([$data['id']]);

            echo json_encode(["success" => $success]);
            break;

        default:
            http_response_code(405);
            echo json_encode(["success" => false, "error" => "Método no permitido"]);
            break;
    }
} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error"   => "Error en el servidor: " . $e->getMessage()
    ]);
}
