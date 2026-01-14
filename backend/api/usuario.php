<?php
// backend/api/usuario.php

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

        // ---------------------- GET ----------------------
        case 'GET':
            // Si viene ?id=, filtramos por ese usuario; si no, traemos todos
            $sql = "
                SELECT 
                    u.id,
                    u.persona_id,
                    u.username,
                    u.correo,
                    u.rol,
                    u.estado,
                    u.avatar_color,
                    u.avatar_filename, 
                    u.config_schedule_columnas, 
                    u.Confirmar_cambios_Estado_Realizado_por AS confirmar_cambios_estado_realizado_por,
                    p.nombre_razon_social,
                    p.documento_identidad,
                    p.fecha_nacimiento,
                    p.sexo
                FROM usuarios u
                LEFT JOIN personas p ON u.persona_id = p.id
            ";

            $params = [];
            $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

            if ($id > 0) {
                $sql .= " WHERE u.id = :id";
                $params[':id'] = $id;
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($id > 0 && count($usuarios) === 1) {
                echo json_encode([
                    "success" => true,
                    "usuario" => $usuarios[0]
                ]);
            } else {
                echo json_encode([
                    "success"  => true,
                    "usuarios" => $usuarios
                ]);
            }
            break;

        // ---------------------- POST ----------------------
        case 'POST':
            $data = json_decode(file_get_contents("php://input"));

            if (
                empty($data->correo) ||
                empty($data->username) ||
                empty($data->contrasena) ||
                empty($data->rol) ||
                empty($data->nombre_razon_social) ||
                empty($data->documento_identidad)
            ) {
                echo json_encode([
                    "success" => false,
                    "error"   => "Los campos nombre, documento, correo, username, contraseña y rol son obligatorios."
                ]);
                break;
            }

            // Normalizamos datos de persona
            $nombrePersona      = trim($data->nombre_razon_social);
            $documentoIdentidad = trim($data->documento_identidad);
            $fechaNacimiento    = !empty($data->fecha_nacimiento) ? $data->fecha_nacimiento : null;
            $sexo               = !empty($data->sexo) ? $data->sexo : null;

            // Avatar
            $avatarColor    = !empty($data->avatar_color) ? trim($data->avatar_color) : '#4f46e5';
            $avatarFilename = !empty($data->avatar_filename) ? trim($data->avatar_filename) : null;

            // Config columnas Schedule (array de strings desde el frontend)
            $configSchedule = null;
            if (!empty($data->config_schedule_columnas) && is_array($data->config_schedule_columnas)) {
                // Guardamos como JSON
                $configSchedule = json_encode($data->config_schedule_columnas);
            }

            // NUEVO: Confirmar cambios Estado / Realizado por
            $confirmarCambios = isset($data->confirmar_cambios_estado_realizado_por)
                ? (int)$data->confirmar_cambios_estado_realizado_por
                : 1; // por defecto sí confirma

            // Buscamos persona por documento para reutilizar si ya existe
            $stmt = $conn->prepare("SELECT id FROM personas WHERE documento_identidad = ?");
            $stmt->execute([$documentoIdentidad]);
            $persona = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($persona) {
                $personaId = (int)$persona['id'];
            } else {
                // Creamos nueva persona
                $stmt = $conn->prepare("
                    INSERT INTO personas (tipo_persona_id, nombre_razon_social, documento_identidad, fecha_nacimiento, sexo)
                    VALUES (1, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $nombrePersona,
                    $documentoIdentidad,
                    $fechaNacimiento,
                    $sexo
                ]);
                $personaId = (int)$conn->lastInsertId();
            }

            // Ahora creamos el usuario
            $hashedPassword = password_hash($data->contrasena, PASSWORD_DEFAULT);
            $rol    = in_array($data->rol, ['Administrador','Cliente','Vendedor']) ? $data->rol : 'Cliente';
            $estado = isset($data->estado) ? (int)$data->estado : 1;

            $stmt = $conn->prepare("
                INSERT INTO usuarios (
                    persona_id,
                    correo,
                    avatar_color,
                    avatar_filename,
                    config_schedule_columnas,
                    Confirmar_cambios_Estado_Realizado_por,
                    username,
                    password,
                    rol,
                    estado
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $success = $stmt->execute([
                $personaId,
                $data->correo,
                $avatarColor,
                $avatarFilename,
                $configSchedule,
                $confirmarCambios,
                $data->username,
                $hashedPassword,
                $rol,
                $estado
            ]);

            echo json_encode(["success" => $success]);
            break;

        // ---------------------- PUT ----------------------
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));

            if (empty($data->id)) {
                echo json_encode(["success" => false, "error" => "ID de usuario requerido."]);
                break;
            }

            // Recuperamos usuario actual + persona_id
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$data->id]);
            $actual = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$actual) {
                echo json_encode(["success" => false, "error" => "Usuario no encontrado."]);
                break;
            }

            $correo   = isset($data->correo)   ? trim($data->correo)   : $actual['correo'];
            $username = isset($data->username) ? trim($data->username) : $actual['username'];
            $rol      = isset($data->rol)      ? trim($data->rol)      : $actual['rol'];
            $estado   = isset($data->estado)   ? (int)$data->estado    : $actual['estado'];

            if (!in_array($rol, ['Administrador','Cliente','Vendedor'])) {
                $rol = $actual['rol'];
            }

            // Avatar
            $avatarColor = isset($data->avatar_color)
                ? trim($data->avatar_color)
                : ($actual['avatar_color'] ?? '#4f46e5');

            // Permitir limpiar avatar enviando cadena vacía
            if (isset($data->avatar_filename)) {
                $avatarFilename = trim((string)$data->avatar_filename);
                if ($avatarFilename === '') {
                    $avatarFilename = null;
                }
            } else {
                $avatarFilename = $actual['avatar_filename'];
            }

            // Config columnas Schedule
            $configSchedule = $actual['config_schedule_columnas']; // valor actual por defecto
            if (isset($data->config_schedule_columnas)) {
                if (is_array($data->config_schedule_columnas)) {
                    $configSchedule = json_encode($data->config_schedule_columnas);
                } elseif ($data->config_schedule_columnas === null || $data->config_schedule_columnas === '') {
                    $configSchedule = null;
                }
            }

            // NUEVO: Confirmar cambios Estado / Realizado por
            $confirmarCambios = isset($data->confirmar_cambios_estado_realizado_por)
                ? (int)$data->confirmar_cambios_estado_realizado_por
                : (int)($actual['Confirmar_cambios_Estado_Realizado_por'] ?? 1);

            // Actualización de PERSONA (si vienen datos)
            $personaIdActual = $actual['persona_id'] ? (int)$actual['persona_id'] : null;

            $nombrePersona      = isset($data->nombre_razon_social) ? trim($data->nombre_razon_social) : null;
            $documentoIdentidad = isset($data->documento_identidad) ? trim($data->documento_identidad) : null;
            $fechaNacimiento    = isset($data->fecha_nacimiento)    ? $data->fecha_nacimiento : null;
            $sexo               = isset($data->sexo)                ? $data->sexo : null;

            if ($nombrePersona || $documentoIdentidad || $fechaNacimiento || $sexo) {
                // Hay datos de persona en el payload
                if ($personaIdActual) {
                    // Actualizamos persona existente
                    $stmt = $conn->prepare("SELECT * FROM personas WHERE id = ?");
                    $stmt->execute([$personaIdActual]);
                    $personaActual = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($personaActual) {
                        $nombrePersonaFinal      = $nombrePersona      ?? $personaActual['nombre_razon_social'];
                        $documentoIdentidadFinal = $documentoIdentidad ?? $personaActual['documento_identidad'];
                        $fechaNacimientoFinal    = $fechaNacimiento    ?? $personaActual['fecha_nacimiento'];
                        $sexoFinal               = $sexo               ?? $personaActual['sexo'];

                        $stmt = $conn->prepare("
                            UPDATE personas
                               SET nombre_razon_social = ?,
                                   documento_identidad = ?,
                                   fecha_nacimiento    = ?,
                                   sexo                = ?
                             WHERE id = ?
                        ");
                        $stmt->execute([
                            $nombrePersonaFinal,
                            $documentoIdentidadFinal,
                            $fechaNacimientoFinal,
                            $sexoFinal,
                            $personaIdActual
                        ]);
                    }
                } else {
                    // El usuario no tiene persona asociada, creamos una nueva
                    $stmt = $conn->prepare("
                        INSERT INTO personas (tipo_persona_id, nombre_razon_social, documento_identidad, fecha_nacimiento, sexo)
                        VALUES (1, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $nombrePersona ?? '',
                        $documentoIdentidad ?? '',
                        $fechaNacimiento,
                        $sexo
                    ]);
                    $personaIdActual = (int)$conn->lastInsertId();

                    // Actualizamos usuario para enlazar la nueva persona
                    $stmt = $conn->prepare("UPDATE usuarios SET persona_id = ? WHERE id = ?");
                    $stmt->execute([$personaIdActual, $data->id]);
                }
            }

            // Armamos actualización de USUARIO
            $campos  = [
                "correo = ?",
                "username = ?",
                "rol = ?",
                "estado = ?",
                "avatar_color = ?",
                "avatar_filename = ?",
                "config_schedule_columnas = ?",
                "Confirmar_cambios_Estado_Realizado_por = ?"
            ];
            $valores = [
                $correo,
                $username,
                $rol,
                $estado,
                $avatarColor,
                $avatarFilename,
                $configSchedule,
                $confirmarCambios
            ];

            if (!empty($data->contrasena)) {
                $hashedPassword = password_hash($data->contrasena, PASSWORD_DEFAULT);
                $campos[]  = "password = ?";
                $valores[] = $hashedPassword;
            }

            $valores[] = $data->id;

            $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $success = $stmt->execute($valores);

            echo json_encode(["success" => $success]);
            break;

        // ---------------------- DELETE ----------------------
        case 'DELETE':
            parse_str(file_get_contents("php://input"), $data);

            if (empty($data['id'])) {
                echo json_encode(["success" => false, "error" => "ID de usuario requerido."]);
                break;
            }

            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
            $success = $stmt->execute([$data['id']]);

            echo json_encode(["success" => $success]);
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
        "error"   => "Error en el servidor: " . $e->getMessage()
    ]);
}
