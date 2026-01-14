<?php
require_once '../config/Conexion.php';
// require_once '../helpers/JwtHelper.php'; // Solo si realmente lo necesitas
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        // Datos de la persona
        $nombre            = $data["nombre"] ?? "";
        $documento         = $data["documento_identidad"] ?? "";
        $fechaNacimiento   = $data["fecha_nacimiento"] ?? null;
        $sexo              = $data["sexo"] ?? "";
        $tipoPersonaId     = $data["tipo_persona_id"] ?? 1; // personas_tipos.id

        // Datos del usuario
        $username          = $data["username"] ?? "";
        $password          = $data["password"] ?? "";
        $correo            = $data["correo"] ?? "";
        $rol               = $data["rol"] ?? "Operador"; // valor por defecto válido

        // Validar rol contra enum de la BD
        $rolesValidos = ['Administrador', 'Operador', 'Visor'];
        if (!in_array($rol, $rolesValidos, true)) {
            $rol = 'Operador';
        }

        // Validar campos obligatorios
        if (empty($nombre) ||
            empty($documento) ||
            empty($username) ||
            empty($password) ||
            !in_array($sexo, ['M', 'F'], true)) {

            echo json_encode([
                "success" => false,
                "message" => "Faltan campos obligatorios o el sexo es inválido."
            ]);
            exit;
        }

        try {
            $conn->beginTransaction();

            // Validar documento_identidad único en personas
            $stmt = $conn->prepare("SELECT id FROM personas WHERE documento_identidad = :doc");
            $stmt->execute([':doc' => $documento]);
            if ($stmt->fetch()) {
                $conn->rollBack();
                echo json_encode([
                    "success" => false,
                    "message" => "El documento de identidad ya está registrado."
                ]);
                exit;
            }

            // Insertar persona
            $queryPersona = "
                INSERT INTO personas (
                    nombre_razon_social,
                    documento_identidad,
                    fecha_nacimiento,
                    sexo,
                    tipo_persona_id
                ) VALUES (
                    :nombre,
                    :documento,
                    :fechaNacimiento,
                    :sexo,
                    :tipoPersonaId
                )
            ";
            $stmt = $conn->prepare($queryPersona);
            $stmt->execute([
                ":nombre"        => $nombre,
                ":documento"     => $documento,
                ":fechaNacimiento"=> $fechaNacimiento ?: null,
                ":sexo"          => $sexo,
                ":tipoPersonaId" => $tipoPersonaId
            ]);
            $personaId = $conn->lastInsertId();

            // Validar si el username o correo ya existen en usuarios
            $stmt = $conn->prepare("
                SELECT id
                  FROM usuarios
                 WHERE username = :username
                    OR correo   = :correo
                LIMIT 1
            ");
            $stmt->execute([
                ':username' => $username,
                ':correo'   => $correo
            ]);

            if ($stmt->fetch()) {
                $conn->rollBack();
                echo json_encode([
                    "success" => false,
                    "message" => "El usuario o correo ya existe."
                ]);
                exit;
            }

            // Hash de la contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertar en usuarios (tabla plural, columnas de tu BD)
            $queryUsuario = "
                INSERT INTO usuarios (
                    persona_id,
                    username,
                    password,
                    correo,
                    rol,
                    estado
                ) VALUES (
                    :persona_id,
                    :username,
                    :password,
                    :correo,
                    :rol,
                    1
                )
            ";
            $stmt = $conn->prepare($queryUsuario);
            $stmt->execute([
                ":persona_id" => $personaId,
                ":username"   => $username,
                ":password"   => $hashedPassword,
                ":correo"     => $correo,
                ":rol"        => $rol
            ]);

            $conn->commit();
            echo json_encode([
                "success" => true,
                "message" => "Usuario registrado correctamente."
            ]);

        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            echo json_encode([
                "success" => false,
                "message" => "Error en el servidor.",
                "error"   => $e->getMessage()
            ]);
        }

        break;

    default:
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido"
        ]);
        break;
}
