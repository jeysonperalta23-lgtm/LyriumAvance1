<?php
// backend/api/tareas.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/Conexion.php';

date_default_timezone_set('America/Lima');

/**
 * Obtiene la fecha base (primer día de creación) para una frecuencia.
 * Nos permite usar esas tareas como “plantilla”.
 */
function obtenerFechaBaseCreacion(PDO $conn, string $frecuencia): ?string {
    $stmt = $conn->prepare("
        SELECT MIN(DATE(fecha_hora_creado)) AS base_fecha
        FROM tareas
        WHERE frecuencia = :freq
    ");
    $stmt->execute([':freq' => $frecuencia]);
    $base = $stmt->fetchColumn();
    return $base ? $base : null;
}

/* =========================================================
 * =============== HELPERS INTERNOS (NO echo) ===============
 * ======================================================= */

/**
 * Crea tareas DIARIAS para una fecha dada.
 * Usa fecha_hora_generada = fechaObjetivo 00:00:00
 * Y COPIA fecha_hora_ejecucion tal cual de la plantilla.
 * Regla extra: si Referencia contiene "YA NO SE EJECUTA" => estado = 'omitir'.
 */
function crearTareasDiarias(PDO $conn, string $fechaObjetivo, int $usuarioId): array {
    $hoySistema = date('Y-m-d');

    if ($fechaObjetivo > $hoySistema) {
        return [
            'success'      => false,
            'message'      => "No se pueden generar tareas diarias para una fecha futura.",
            'generadas'    => 0,
            'ya_existian'  => false
        ];
    }

    // Verificar si ya existen tareas diarias para esa fecha Schedule (fecha_hora_generada)
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM tareas
        WHERE frecuencia = 'dia'
          AND DATE(fecha_hora_generada) = ?
    ");
    $stmt->execute([$fechaObjetivo]);

    if ((int)$stmt->fetchColumn() > 0) {
        return [
            'success'      => false,
            'message'      => "Ya se generaron tareas diarias para la fecha $fechaObjetivo.",
            'generadas'    => 0,
            'ya_existian'  => true
        ];
    }

    // Tareas plantilla: la primera fecha de creación para frecuencia = 'dia'
    $baseFechaCrea = obtenerFechaBaseCreacion($conn, 'dia');
    if (!$baseFechaCrea) {
        return [
            'success'        => false,
            'message'        => "No se encontró una plantilla de tareas diarias.",
            'generadas'      => 0,
            'ya_existian'    => false,
            'sin_plantilla'  => true
        ];
    }

    $sqlPlantilla = "
        SELECT orden,
               descripcion,
               job,
               Referencia,
               frecuencia,
               estado,
               fecha_hora_ejecucion,
               usuario_id_creador
        FROM tareas
        WHERE frecuencia = 'dia'
          AND DATE(fecha_hora_creado) = :baseFechaCrea
        ORDER BY orden, id
    ";
    $stmt = $conn->prepare($sqlPlantilla);
    $stmt->execute([':baseFechaCrea' => $baseFechaCrea]);
    $plantillas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$plantillas) {
        return [
            'success'        => false,
            'message'        => "No se encontraron tareas diarias de plantilla.",
            'generadas'      => 0,
            'ya_existian'    => false,
            'sin_plantilla'  => true
        ];
    }

    // fecha_hora_generada: la fecha seleccionada en la modal (00:00:00)
    $fechaHoraGenerada = $fechaObjetivo . ' 00:00:00';

    // Insert con copia de fecha_hora_ejecucion de la plantilla
    $sqlInsert = "
        INSERT INTO tareas (
            orden,
            descripcion,
            job,
            Referencia,
            frecuencia,
            estado,
            fecha_hora_ejecucion,
            fecha_hora_ejecucion_inicio,
            fecha_hora_ejecucion_fin,
            fecha_hora_generada,
            usuario_id_creador,
            usuario_id_realizado
        ) VALUES (
            :orden,
            :descripcion,
            :job,
            :Referencia,
            :frecuencia,
            :estado,
            :fecha_hora_ejecucion,
            :fecha_hora_ejecucion_inicio,
            :fecha_hora_ejecucion_fin,
            :fecha_hora_generada,
            :usuario_id_creador,
            :usuario_id_realizado
        )
    ";
    $ins = $conn->prepare($sqlInsert);

    $contador = 0;

    foreach ($plantillas as $p) {
        $estadoPlantilla = strtolower((string)$p['estado']);
        // Normalizamos referencia para detectar "YA NO SE EJECUTA"
        $refNorm = strtoupper(trim((string)($p['Referencia'] ?? '')));

        if ($estadoPlantilla === 'omitir' || $refNorm === NULL) {
            $estadoInsert = 'omitir';
        } else {
            $estadoInsert = null;
        }

        $ins->execute([
            ':orden'                       => $p['orden'],
            ':descripcion'                 => $p['descripcion'],
            ':job'                         => $p['job'],
            ':Referencia'                  => $p['Referencia'] ?? '',
            ':frecuencia'                  => 'dia',
            ':estado'                      => $estadoInsert,
            // Copiamos tal cual la fecha_hora_ejecucion de la plantilla
            ':fecha_hora_ejecucion'        => $p['fecha_hora_ejecucion'],
            // Siempre NULL al generar
            ':fecha_hora_ejecucion_inicio' => null,
            ':fecha_hora_ejecucion_fin'    => null,
            ':fecha_hora_generada'         => $fechaHoraGenerada,
            ':usuario_id_creador'          => $usuarioId,
            ':usuario_id_realizado'        => null
        ]);

        $contador++;
    }

    return [
        'success'     => true,
        'message'     => "Se generaron $contador tareas diarias para la fecha $fechaObjetivo.",
        'generadas'   => $contador,
        'ya_existian' => false
    ];
}

/**
 * Crea tareas SEMANALES para la semana de una fecha dada.
 * Solo sábado/domingo del calendario.
 * fecha_hora_generada = fecha seleccionada (sáb/dom) 00:00:00
 * Y COPIA fecha_hora_ejecucion tal cual de la plantilla.
 * Regla extra: si Referencia contiene "YA NO SE EJECUTA" => estado = 'omitir'.
 */
function crearTareasSemanales(PDO $conn, string $fechaRaw, int $usuarioId): array {
    $hoySistema = date('Y-m-d');

    $fechaObj = DateTime::createFromFormat('Y-m-d', $fechaRaw);
    if (!$fechaObj) {
        return [
            'success'   => false,
            'message'   => "Fecha inválida para generación semanal.",
            'generadas' => 0
        ];
    }

    if ($fechaRaw > $hoySistema) {
        return [
            'success'   => false,
            'message'   => "No se pueden generar tareas semanales para una fecha futura.",
            'generadas' => 0
        ];
    }

    // N (1=lunes,...,6=sábado,7=domingo)
    $diaSemana = (int)$fechaObj->format('N');

    if ($diaSemana !== 6 && $diaSemana !== 7) {
        return [
            'success'            => false,
            'message'            => "Las tareas semanales solo se pueden generar sábado o domingo del calendario.",
            'generadas'          => 0,
            'no_corresponde_hoy' => true
        ];
    }

    // Calcular el lunes de esa semana (solo para mensaje)
    $inicioSemana = clone $fechaObj;
    $inicioSemana->modify('-' . ($diaSemana - 1) . ' days');
    $fechaSemana = $inicioSemana->format('Y-m-d');

    // Evitar duplicado por fecha Schedule semanal (fecha_hora_generada = sábado/domingo seleccionado)
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM tareas
        WHERE frecuencia = 'semana'
          AND DATE(fecha_hora_generada) = ?
    ");
    $stmt->execute([$fechaRaw]);

    if ((int)$stmt->fetchColumn() > 0) {
        return [
            'success'      => false,
            'message'      => "Ya se generaron tareas semanales para la semana que inicia el $fechaSemana.",
            'generadas'    => 0,
            'ya_existian'  => true
        ];
    }

    $baseFechaCrea = obtenerFechaBaseCreacion($conn, 'semana');
    if (!$baseFechaCrea) {
        return [
            'success'        => false,
            'message'        => "No se encontró una plantilla de tareas semanales.",
            'generadas'      => 0,
            'sin_plantilla'  => true
        ];
    }

    $sqlPlantilla = "
        SELECT orden,
               descripcion,
               job,
               Referencia,
               frecuencia,
               estado,
               fecha_hora_ejecucion,
               usuario_id_creador
        FROM tareas
        WHERE frecuencia = 'semana'
          AND DATE(fecha_hora_creado) = :baseFechaCrea
        ORDER BY orden, id
    ";
    $stmt = $conn->prepare($sqlPlantilla);
    $stmt->execute([':baseFechaCrea' => $baseFechaCrea]);
    $plantillas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$plantillas) {
        return [
            'success'        => false,
            'message'        => "No se encontraron tareas semanales de plantilla.",
            'generadas'      => 0,
            'sin_plantilla'  => true
        ];
    }

    // fecha_hora_generada: la fecha seleccionada (sáb/dom) a las 00:00:00
    $fechaHoraGenerada = $fechaRaw . ' 00:00:00';

    $sqlInsert = "
        INSERT INTO tareas (
            orden,
            descripcion,
            job,
            Referencia,
            frecuencia,
            estado,
            fecha_hora_ejecucion,
            fecha_hora_ejecucion_inicio,
            fecha_hora_ejecucion_fin,
            fecha_hora_generada,
            usuario_id_creador,
            usuario_id_realizado
        ) VALUES (
            :orden,
            :descripcion,
            :job,
            :Referencia,
            :frecuencia,
            :estado,
            :fecha_hora_ejecucion,
            :fecha_hora_ejecucion_inicio,
            :fecha_hora_ejecucion_fin,
            :fecha_hora_generada,
            :usuario_id_creador,
            :usuario_id_realizado
        )
    ";
    $ins = $conn->prepare($sqlInsert);

    $contador = 0;

    foreach ($plantillas as $p) {
        $estadoPlantilla = strtolower((string)$p['estado']);
        $refNorm = strtoupper(trim((string)($p['Referencia'] ?? '')));

        if ($estadoPlantilla === 'omitir' || $refNorm === NULL) {
            $estadoInsert = 'omitir';
        } else {
            $estadoInsert = null;
        }

        $ins->execute([
            ':orden'                       => $p['orden'],
            ':descripcion'                 => $p['descripcion'],
            ':job'                         => $p['job'],
            ':Referencia'                  => $p['Referencia'] ?? '',
            ':frecuencia'                  => 'semana',
            ':estado'                      => $estadoInsert,
            // Copiamos tal cual la fecha_hora_ejecucion de la plantilla
            ':fecha_hora_ejecucion'        => $p['fecha_hora_ejecucion'],
            // Siempre NULL al generar
            ':fecha_hora_ejecucion_inicio' => null,
            ':fecha_hora_ejecucion_fin'    => null,
            ':fecha_hora_generada'         => $fechaHoraGenerada,
            ':usuario_id_creador'          => $usuarioId,
            ':usuario_id_realizado'        => null
        ]);

        $contador++;
    }

    return [
        'success'     => true,
        'message'     => "Se generaron $contador tareas semanales para la semana que inicia el $fechaSemana.",
        'generadas'   => $contador,
        'ya_existian' => false
    ];
}

/**
 * Crea tareas MENSUALES para un mes dado (o mes actual si no se envía).
 * En manual: usarás 'mes' (YYYY-MM) desde el front.
 * fecha_hora_generada = primer día del mes 00:00:00
 * Y COPIA fecha_hora_ejecucion tal cual de la plantilla.
 * Regla extra: si Referencia contiene "YA NO SE EJECUTA" => estado = 'omitir'.
 */
function crearTareasMensuales(PDO $conn, int $usuarioId, ?string $mesSeleccionado = null): array {
    if ($mesSeleccionado !== null && preg_match('/^\d{4}-\d{2}$/', $mesSeleccionado)) {
        $anioActual = (int)substr($mesSeleccionado, 0, 4);
        $mesActual  = (int)substr($mesSeleccionado, 5, 2);
    } else {
        $hoy        = new DateTime('now');
        $anioActual = (int)$hoy->format('Y');
        $mesActual  = (int)$hoy->format('m');
    }

    $fechaInicioMes     = sprintf('%04d-%02d-01 00:00:00', $anioActual, $mesActual);
    $fechaHoraGenerada  = $fechaInicioMes; // mismo valor para campo generada

    // Verificar duplicado por mes de fecha Schedule (fecha_hora_generada)
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM tareas
        WHERE frecuencia = 'mensual'
          AND YEAR(fecha_hora_generada) = :anio
          AND MONTH(fecha_hora_generada) = :mes
    ");
    $stmt->execute([
        ':anio' => $anioActual,
        ':mes'  => $mesActual
    ]);

    if ((int)$stmt->fetchColumn() > 0) {
        return [
            'success'      => false,
            'message'      => "Ya se generaron tareas mensuales para este mes.",
            'generadas'    => 0,
            'ya_existian'  => true
        ];
    }

    $baseFechaCrea = obtenerFechaBaseCreacion($conn, 'mensual');
    if (!$baseFechaCrea) {
        return [
            'success'        => false,
            'message'        => "No se encontró una plantilla de tareas mensuales.",
            'generadas'      => 0,
            'sin_plantilla'  => true
        ];
    }

    $sqlPlantilla = "
        SELECT orden,
               descripcion,
               job,
               Referencia,
               frecuencia,
               estado,
               fecha_hora_ejecucion,
               usuario_id_creador
        FROM tareas
        WHERE frecuencia = 'mensual'
          AND DATE(fecha_hora_creado) = :baseFechaCrea
        ORDER BY orden, id
    ";
    $stmt = $conn->prepare($sqlPlantilla);
    $stmt->execute([':baseFechaCrea' => $baseFechaCrea]);
    $plantillas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$plantillas) {
        return [
            'success'        => false,
            'message'        => "No se encontraron tareas mensuales de plantilla.",
            'generadas'      => 0,
            'sin_plantilla'  => true
        ];
    }

    $sqlInsert = "
        INSERT INTO tareas (
            orden,
            descripcion,
            job,
            Referencia,
            frecuencia,
            estado,
            fecha_hora_ejecucion,
            fecha_hora_ejecucion_inicio,
            fecha_hora_ejecucion_fin,
            fecha_hora_generada,
            usuario_id_creador,
            usuario_id_realizado
        ) VALUES (
            :orden,
            :descripcion,
            :job,
            :Referencia,
            :frecuencia,
            :estado,
            :fecha_hora_ejecucion,
            :fecha_hora_ejecucion_inicio,
            :fecha_hora_ejecucion_fin,
            :fecha_hora_generada,
            :usuario_id_creador,
            :usuario_id_realizado
        )
    ";
    $ins = $conn->prepare($sqlInsert);

    $contador = 0;

    foreach ($plantillas as $p) {
        $estadoPlantilla = strtolower((string)$p['estado']);
        $refNorm = strtoupper(trim((string)($p['Referencia'] ?? '')));

        if ($estadoPlantilla === 'omitir' || $refNorm === NULL) {
            $estadoInsert = 'omitir';
        } else {
            $estadoInsert = null;
        }

        $ins->execute([
            ':orden'                       => $p['orden'],
            ':descripcion'                 => $p['descripcion'],
            ':job'                         => $p['job'],
            ':Referencia'                  => $p['Referencia'] ?? '',
            ':frecuencia'                  => 'mensual',
            ':estado'                      => $estadoInsert,
            // Copiamos tal cual la fecha_hora_ejecucion de la plantilla
            ':fecha_hora_ejecucion'        => $p['fecha_hora_ejecucion'],
            // Siempre NULL al generar
            ':fecha_hora_ejecucion_inicio' => null,
            ':fecha_hora_ejecucion_fin'    => null,
            ':fecha_hora_generada'         => $fechaHoraGenerada,
            ':usuario_id_creador'          => $usuarioId,
            ':usuario_id_realizado'        => null
        ]);

        $contador++;
    }

    $meses = [
        1  => 'enero',
        2  => 'febrero',
        3  => 'marzo',
        4  => 'abril',
        5  => 'mayo',
        6  => 'junio',
        7  => 'julio',
        8  => 'agosto',
        9  => 'setiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre'
    ];

    $nombreMes = $meses[$mesActual] ?? $mesActual;

    return [
        'success'     => true,
        'message'     => "Se generaron $contador tareas mensuales para $nombreMes de $anioActual.",
        'generadas'   => $contador,
        'ya_existian' => false
    ];
}

/**
 * AUTO-GENERAR (llamado por autoGenerarTareasAlIngresar en el front)
 */
function autoGenerarTareas(PDO $conn, array $data): void {
    $usuarioId = isset($data['usuario_id_creador']) ? (int)$data['usuario_id_creador'] : 1;
    $hoy       = date('Y-m-d');
    $diaN      = (int)date('N'); // 1=lun..7=dom
    $diaMes    = (int)date('j');

    $detalles       = [];
    $mensajesNuevos = [];
    $totalGeneradas = 0;

    // Diarias (lunes-viernes)
    if ($diaN >= 1 && $diaN <= 5) {
        $resDia           = crearTareasDiarias($conn, $hoy, $usuarioId);
        $detalles['dia']  = $resDia;

        if ($resDia['success'] && ($resDia['generadas'] ?? 0) > 0) {
            $mensajesNuevos[] = $resDia['message'];
            $totalGeneradas  += $resDia['generadas'];
        }
    }

    // Semanales (sábado/domingo)
    if ($diaN === 6 || $diaN === 7) {
        $resSem             = crearTareasSemanales($conn, $hoy, $usuarioId);
        $detalles['semana'] = $resSem;

        if ($resSem['success'] && ($resSem['generadas'] ?? 0) > 0) {
            $mensajesNuevos[] = $resSem['message'];
            $totalGeneradas  += $resSem['generadas'];
        }
    }

    // Mensuales (día 1)
    if ($diaMes === 1) {
        // mesSeleccionado = null -> usa mes actual
        $resMens             = crearTareasMensuales($conn, $usuarioId, null);
        $detalles['mensual'] = $resMens;

        if ($resMens['success'] && ($resMens['generadas'] ?? 0) > 0) {
            $mensajesNuevos[] = $resMens['message'];
            $totalGeneradas  += $resMens['generadas'];
        }
    }

    // success global: solo false si hay error "real"
    $successGlobal = true;
    foreach ($detalles as $tipo => $r) {
        if (!is_array($r)) continue;
        if (
            !$r['success'] &&
            empty($r['ya_existian']) &&
            empty($r['sin_plantilla']) &&
            empty($r['no_corresponde_hoy'])
        ) {
            $successGlobal = false;
            break;
        }
    }

    if ($totalGeneradas > 0) {
        $mensaje = implode(" | ", $mensajesNuevos);
    } else {
        $mensaje = "";
    }

    echo json_encode([
        'success'  => $successGlobal,
        'message'  => $mensaje,
        'detalles' => $detalles
    ]);
}

/* ============================
 * ========== ROUTER ==========
 * ========================== */

$method = $_SERVER['REQUEST_METHOD'];

try {

    switch ($method) {

        // ---------- LISTAR TAREAS ----------
        case 'GET':

            // Si quieres SOLO las tareas de la última fecha (por si acaso lo usas en otro lado)
            $soloUltima = isset($_GET['solo_ultima']) && $_GET['solo_ultima'] === '1';

            if ($soloUltima) {
                // MODO ANTIGUO: sólo última fecha_hora_generada
                $sql = "
                    SELECT
                        t.id,
                        t.orden,
                        t.descripcion,
                        t.job,
                        t.Referencia,
                        t.frecuencia,
                        t.estado,
                        t.fecha_hora_ejecucion,
                        t.fecha_hora_ejecucion_inicio,
                        t.fecha_hora_ejecucion_fin,
                        t.fecha_hora_generada,
                        t.fecha_hora_creado,
                        t.usuario_id_realizado,
                        u_crea.username  AS usuario_creador,
                        COALESCE(p_real.nombre_razon_social, u_real.username) AS usuario_realizado,
                        u_real.avatar_color AS usuario_color_hex
                    FROM tareas t
                    LEFT JOIN usuarios  u_crea ON u_crea.id = t.usuario_id_creador
                    LEFT JOIN usuarios  u_real ON u_real.id = t.usuario_id_realizado
                    LEFT JOIN personas  p_real ON p_real.id = u_real.persona_id
                    WHERE t.fecha_hora_generada = (
                        SELECT MAX(fecha_hora_generada) FROM tareas
                    )
                    ORDER BY t.orden, t.id
                ";
            } else {
                // NUEVO: TODAS las tareas con Fecha Schedule (para poder filtrar desde/hasta)
                $sql = "
                    SELECT
                        t.id,
                        t.orden,
                        t.descripcion,
                        t.job,
                        t.Referencia,
                        t.frecuencia,
                        t.estado,
                        t.fecha_hora_ejecucion,
                        t.fecha_hora_ejecucion_inicio,
                        t.fecha_hora_ejecucion_fin,
                        t.fecha_hora_generada,
                        t.fecha_hora_creado,
                        t.usuario_id_realizado,
                        u_crea.username  AS usuario_creador,
                        COALESCE(p_real.nombre_razon_social, u_real.username) AS usuario_realizado,
                        u_real.avatar_color AS usuario_color_hex
                    FROM tareas t
                    LEFT JOIN usuarios  u_crea ON u_crea.id = t.usuario_id_creador
                    LEFT JOIN usuarios  u_real ON u_real.id = t.usuario_id_realizado
                    LEFT JOIN personas  p_real ON p_real.id = u_real.persona_id
                    -- sólo registros que ya tienen Fecha Schedule
                    WHERE t.fecha_hora_generada IS NOT NULL
                    ORDER BY t.fecha_hora_generada DESC, t.orden, t.id
                ";
            }

            $stmt   = $conn->query($sql);
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                "success" => true,
                "tareas"  => $tareas
            ]);
            break;

        // ---------- CREAR / GENERAR ----------
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true) ?? [];

            // --- AUTO-GENERAR AL INGRESAR ---
            if (isset($data['accion']) && $data['accion'] === 'auto_generar') {
                autoGenerarTareas($conn, $data);
                break;
            }

            // --- GENERACIÓN MASIVA DESDE BOTÓN (dia/semana/mensual) ---
            if (isset($data['accion']) && $data['accion'] === 'generar') {
                $tipo      = $data['tipo'] ?? '';
                $usuarioId = isset($data['usuario_id_creador']) ? (int)$data['usuario_id_creador'] : 1;

                switch ($tipo) {
                    case 'dia':
                        // fecha enviada desde la modal (YYYY-MM-DD)
                        $fecha = $data['fecha_generada'] ?? $data['fecha'] ?? date('Y-m-d');
                        $res   = crearTareasDiarias($conn, $fecha, $usuarioId);
                        echo json_encode($res);
                        break;

                    case 'semana':
                        // fecha enviada desde la modal (YYYY-MM-DD) – sábado o domingo
                        $fecha = $data['fecha_generada'] ?? $data['fecha'] ?? date('Y-m-d');
                        $res   = crearTareasSemanales($conn, $fecha, $usuarioId);
                        echo json_encode($res);
                        break;

                    case 'mensual':
                        // mes (YYYY-MM) enviado desde la modal
                        $mesSeleccionado = $data['mes'] ?? null;
                        $res             = crearTareasMensuales($conn, $usuarioId, $mesSeleccionado);
                        echo json_encode($res);
                        break;

                    default:
                        echo json_encode([
                            "success" => false,
                            "message" => "Tipo de generación no reconocido."
                        ]);
                        break;
                }
                break;
            }

            // ---------- CREAR TAREA MANUAL ----------
            $orden       = trim($data['orden'] ?? '');
            $descripcion = trim($data['descripcion'] ?? '');

            // job OPCIONAL
            $job = null;
            if (array_key_exists('job', $data)) {
                $tmp = trim((string)$data['job']);
                $job = ($tmp !== '') ? $tmp : null;
            }

            // Referencia OPCIONAL pero NOT NULL en BD -> usamos '' si no viene
            $Referencia = '';
            if (array_key_exists('Referencia', $data)) {
                $tmp        = trim((string)$data['Referencia']);
                $Referencia = ($tmp !== '') ? $tmp : '';
            }

            $frecuencia = trim($data['frecuencia'] ?? 'dia');

            // Estado: solo respetamos 'omitir'; todo lo demás => NULL
            $estadoRaw = isset($data['estado']) ? strtolower(trim($data['estado'])) : null;
            $estado    = ($estadoRaw === 'omitir') ? 'omitir' : null;

            $usuarioId = isset($data['usuario_id_creador']) ? (int)$data['usuario_id_creador'] : 1;

            // usuario_id_realizado OPCIONAL
            $usuarioRealizado = null;
            if (array_key_exists('usuario_id_realizado', $data)) {
                $tmpUsuario = $data['usuario_id_realizado'];
                if ($tmpUsuario !== null && $tmpUsuario !== '') {
                    $usuarioRealizado = (int)$tmpUsuario;
                }
            }

            // Si el estado es 'omitir', sin realizado por
            if ($estado === 'omitir') {
                $usuarioRealizado = null;
            }

            // ====== HORAS OPCIONALES (INICIO / FIN) ======
            // Al crear, queremos INICIO siempre NULL
            $fechaInicio = null;

            $fechaFin = null;
            if (array_key_exists('fecha_hora_ejecucion_fin', $data)) {
                $tmp      = trim((string)$data['fecha_hora_ejecucion_fin']);
                $fechaFin = ($tmp !== '') ? $tmp : null;
            }

            // fecha_hora_ejecucion principal también NULL al crear manualmente
            $fechaEjecucion = null;

            // fecha_hora_generada para creación manual: la dejamos NULL
            $fechaGenerada = null;

            // VALIDACIÓN: SOLO ORDEN Y DESCRIPCIÓN OBLIGATORIOS
            if ($orden === '' || $descripcion === '') {
                echo json_encode([
                    "success" => false,
                    "message" => "Orden y descripción son obligatorios."
                ]);
                exit;
            }

            $stmt = $conn->prepare("
                INSERT INTO tareas (
                    orden,
                    descripcion,
                    job,
                    Referencia,
                    frecuencia,
                    estado,
                    fecha_hora_ejecucion,
                    fecha_hora_ejecucion_inicio,
                    fecha_hora_ejecucion_fin,
                    fecha_hora_generada,
                    usuario_id_creador,
                    usuario_id_realizado
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $orden,
                $descripcion,
                $job,
                $Referencia,
                $frecuencia,
                $estado,
                $fechaEjecucion,
                $fechaInicio,
                $fechaFin,
                $fechaGenerada,
                $usuarioId,
                $usuarioRealizado
            ]);

            $tareaId = (int)$conn->lastInsertId();

            echo json_encode([
                "success"  => true,
                "tarea_id" => $tareaId
            ]);
            break;

        // ---------- ACTUALIZAR TAREA ----------
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true) ?? [];

            $id = isset($data['id']) ? (int)$data['id'] : 0;

            if ($id <= 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "ID inválido para actualizar la tarea."
                ]);
                exit;
            }

            // Flags de qué campos vinieron en el JSON
            $tieneOrden       = array_key_exists('orden', $data);
            $tieneDescripcion = array_key_exists('descripcion', $data);
            $tieneJob         = array_key_exists('job', $data);
            $tieneFrecuencia  = array_key_exists('frecuencia', $data);
            $tieneReferencia  = array_key_exists('Referencia', $data);
            $tieneEstado      = array_key_exists('estado', $data);
            $tieneUsuarioReal = array_key_exists('usuario_id_realizado', $data);
            $tieneFechaEjec   = array_key_exists('fecha_hora_ejecucion', $data);
            $tieneInicio      = array_key_exists('fecha_hora_ejecucion_inicio', $data);
            $tieneFin         = array_key_exists('fecha_hora_ejecucion_fin', $data);
            $tieneFechaGen    = array_key_exists('fecha_hora_generada', $data);

            // Valores (solo si vienen)
            $orden       = $tieneOrden       ? trim((string)$data['orden'])       : null;
            $descripcion = $tieneDescripcion ? trim((string)$data['descripcion']) : null;
            $job         = $tieneJob         ? trim((string)$data['job'])         : null;
            $frecuencia  = $tieneFrecuencia  ? trim((string)$data['frecuencia'])  : null;

            $Referencia  = null;
            if ($tieneReferencia) {
                $tmp        = trim((string)$data['Referencia']);
                $Referencia = ($tmp !== '') ? $tmp : '';
            }

            $estado = null;
            if ($tieneEstado) {
                $tmp    = trim((string)$data['estado']);
                $estado = ($tmp !== '') ? strtolower($tmp) : null;
            }

            $usuarioMod = isset($data['usuario_id_modificador'])
                ? (int)$data['usuario_id_modificador']
                : null;

            // usuario_id_realizado
            $usuarioRealizado = null;
            if ($tieneUsuarioReal) {
                $tmp = $data['usuario_id_realizado'];
                if ($tmp !== null && $tmp !== '') {
                    $usuarioRealizado = (int)$tmp;
                } else {
                    $usuarioRealizado = null;
                }
            }

            // Si el estado viene y es 'omitir', forzar sin realizado por
            if ($tieneEstado && $estado === 'omitir') {
                $usuarioRealizado = null;
                $tieneUsuarioReal = true; // asegurar que lo incluimos en el UPDATE
            }

            // Fechas
            $fechaEjecucion = null;
            if ($tieneFechaEjec) {
                $tmp            = trim((string)($data['fecha_hora_ejecucion'] ?? ''));
                $fechaEjecucion = $tmp !== '' ? $tmp : null;
            }

            $fechaInicio = null;
            if ($tieneInicio) {
                $tmp         = trim((string)($data['fecha_hora_ejecucion_inicio'] ?? ''));
                $fechaInicio = $tmp !== '' ? $tmp : null;
            }

            $fechaFin = null;
            if ($tieneFin) {
                $tmp      = trim((string)($data['fecha_hora_ejecucion_fin'] ?? ''));
                $fechaFin = $tmp !== '' ? $tmp : null;
            }

            $fechaGenerada = null;
            if ($tieneFechaGen) {
                $tmp           = trim((string)($data['fecha_hora_generada'] ?? ''));
                $fechaGenerada = $tmp !== '' ? $tmp : null;
            }

            // Construimos dinámicamente el SET (solo lo que viene)
            $camposSet  = [];
            $parametros = [ ':id' => $id ];

            // Orden
            if ($tieneOrden) {
                if ($orden === '') {
                    echo json_encode([
                        "success" => false,
                        "message" => "El campo 'orden' no puede estar vacío si se envía."
                    ]);
                    exit;
                }
                $camposSet[]           = "orden = :orden";
                $parametros[':orden']  = $orden;
            }

            // Descripción
            if ($tieneDescripcion) {
                if ($descripcion === '') {
                    echo json_encode([
                        "success" => false,
                        "message" => "El campo 'descripcion' no puede estar vacío si se envía."
                    ]);
                    exit;
                }
                $camposSet[]                = "descripcion = :descripcion";
                $parametros[':descripcion'] = $descripcion;
            }

            // Job
            if ($tieneJob) {
                $camposSet[]        = "job = :job";
                $parametros[':job'] = ($job !== '') ? $job : null;
            }

            // Frecuencia
            if ($tieneFrecuencia) {
                $camposSet[]               = "frecuencia = :frecuencia";
                $parametros[':frecuencia'] = $frecuencia !== '' ? $frecuencia : null;
            }

            // Referencia
            if ($tieneReferencia) {
                $camposSet[]               = "Referencia = :Referencia";
                $parametros[':Referencia'] = $Referencia;
            }

            // Estado
            if ($tieneEstado) {
                $camposSet[]           = "estado = :estado";
                $parametros[':estado'] = $estado;
            }

            // usuario_id_realizado
            if ($tieneUsuarioReal) {
                $camposSet[]                      = "usuario_id_realizado = :usuario_realizado";
                $parametros[':usuario_realizado'] = $usuarioRealizado;
            }

            // fecha_hora_ejecucion
            if ($tieneFechaEjec) {
                $camposSet[]                         = "fecha_hora_ejecucion = :fecha_hora_ejecucion";
                $parametros[':fecha_hora_ejecucion'] = $fechaEjecucion;
            }

            // fecha_hora_ejecucion_inicio
            if ($tieneInicio) {
                $camposSet[]                                = "fecha_hora_ejecucion_inicio = :fecha_hora_ejecucion_inicio";
                $parametros[':fecha_hora_ejecucion_inicio'] = $fechaInicio;
            }

            // fecha_hora_ejecucion_fin
            if ($tieneFin) {
                $camposSet[]                             = "fecha_hora_ejecucion_fin = :fecha_hora_ejecucion_fin";
                $parametros[':fecha_hora_ejecucion_fin'] = $fechaFin;
            }

            // fecha_hora_generada
            if ($tieneFechaGen) {
                $camposSet[]                        = "fecha_hora_generada = :fecha_hora_generada";
                $parametros[':fecha_hora_generada'] = $fechaGenerada;
            }

            // Siempre registramos quién modificó (si viene)
            if ($usuarioMod !== null) {
                $camposSet[]                        = "usuario_id_modificador = :usuario_modificador";
                $parametros[':usuario_modificador'] = $usuarioMod;
            }

            // Si no hay nada que actualizar, devolvemos mensaje
            if (empty($camposSet)) {
                echo json_encode([
                    "success" => false,
                    "message" => "No se enviaron campos para actualizar."
                ]);
                exit;
            }

            $sql = "
                UPDATE tareas
                   SET " . implode(", ", $camposSet) . "
                 WHERE id = :id
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute($parametros);

            echo json_encode(["success" => true]);
            break;

        default:
            http_response_code(405);
            echo json_encode([
                "success" => false,
                "message" => "Método no permitido"
            ]);
            break;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor",
        "error"   => $e->getMessage()
    ]);
}
