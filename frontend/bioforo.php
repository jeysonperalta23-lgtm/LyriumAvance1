<?php
// bioforo.php - VERSIÓN CORREGIDA Y UNIFICADA
session_start();

// ==========================================
// FUNCIÓN DE VALIDACIÓN DE CONTENIDO OFENSIVO
// ==========================================
function validarContenidoOfensivo($texto)
{
    $palabrasProhibidas = [
        'puta',
        'puto',
        'putx',
        'mierda',
        'cagada',
        'carajo',
        'coño',
        'cojudo',
        'huevón',
        'huevon',
        'webon',
        'weon',
        'cabron',
        'cabrón',
        'pendejo',
        'gilipollas',
        'idiota',
        'imbecil',
        'imbécil',
        'estúpido',
        'estupido',
        'tarado',
        'retrasado',
        'subnormal',
        'zorra',
        'perra',
        'marica',
        'maricón',
        'maricon',
        'joto',
        'culero',
        'culera',
        'verga',
        'pinga',
        'pija',
        'chingar',
        'joder',
        'follar',
        'coger',
        'negro de mierda',
        'cholo',
        'indio',
        'serrano',
        'mono',
        'simio',
        'nazi',
        'facho',
        'sudaca',
        'mojado',
        'ilegal',
        'hijo de puta',
        'hijueputa',
        'hijo de perra',
        'concha tu madre',
        'ctm',
        'hdp',
        'conchesumadre',
        'reconcha',
        'malparido',
        'gonorrea',
        'mrda',
        'mrd',
        'ptm',
        'ptmr',
        'csm',
        'csmare',
        'hp',
        'cdtm',
        'pndj',
        'pndjo',
        'cjd',
        'wbn',
        'wvn',
        'hevon',
        'hvn',
        'basura humana',
        'escoria',
        'lacra',
        'desgraciado miserable',
        'te mato',
        'muérete',
        'suicídate',
        'ojalá te mueras'
    ];

    $textoLimpio = mb_strtolower(trim($texto));
    $textoLimpio = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $textoLimpio);

    foreach ($palabrasProhibidas as $palabra) {
        if (stripos($textoLimpio, $palabra) !== false) {
            return [
                'valido' => false,
                'palabra' => $palabra
            ];
        }
    }

    // Patrones regex
    $patronesProhibidos = [
        '/p[u3][t7][a4@]/i',
        '/m[i1][e3]rd[a4@]/i',
        '/c[o0]ñ[o0]/i',
        '/h[u3][e3]v[o0]n/i',
        '/p[e3]nd[e3]j[o0]/i',
        '/\bc[\s\W_]*t[\s\W_]*m\b/i',
        '/\bh[\s\W_]*d[\s\W_]*p\b/i'
    ];

    foreach ($patronesProhibidos as $patron) {
        if (preg_match($patron, $textoLimpio)) {
            return [
                'valido' => false,
                'palabra' => 'contenido ofensivo detectado'
            ];
        }
    }

    return ['valido' => true];
}
// ==========================================
// CONFIGURACIÓN
// ==========================================
$usuario_id = $_SESSION['user_id'] ?? null;
$rol = $_SESSION['rol'] ?? 'anonimo';
$nombre = $_SESSION['nombre'] ?? 'Anónimo-' . rand(1000, 9999);
$avatar_url = $_SESSION['avatar_url'] ?? null;

if (!$usuario_id) {
    if (isset($_POST['anonimo_id']) && $_POST['anonimo_id']) {
        $anonimo_id = $_POST['anonimo_id'];
    } elseif (isset($_COOKIE['anonimo_id']) && $_COOKIE['anonimo_id']) {
        $anonimo_id = $_COOKIE['anonimo_id'];
    } else {
        $anonimo_id = 'anon_' . bin2hex(random_bytes(16));
        setcookie('anonimo_id', $anonimo_id, time() + (365 * 24 * 60 * 60), '/');
    }
} else {
    $anonimo_id = null;
}

try {
    $pdo = new PDO("mysql:host=localhost:3308;dbname=lyrium;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'error' => 'Error de conexión: ' . $e->getMessage()]));
}

// ==========================================
// FUNCIONES AUXILIARES CORREGIDAS
// ==========================================

function obtenerReaccionUsuario($pdo, $tema_id, $respuesta_id, $usuario_id, $anonimo_id)
{
    try {
        $sql = "SELECT tipo FROM bioforo_reacciones WHERE ";
        $params = [];

        if ($respuesta_id) {
            $sql .= "respuesta_id = ? ";
            $params[] = $respuesta_id;
        } else {
            $sql .= "tema_id = ? ";
            $params[] = $tema_id;
        }

        if ($usuario_id) {
            $sql .= "AND usuario_id = ? ";
            $params[] = $usuario_id;
        } elseif ($anonimo_id) {
            $sql .= "AND anonimo_id = ? ";
            $params[] = $anonimo_id;
        } else {
            return null;
        }

        $sql .= "LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['tipo'] : null;
    } catch (Exception $e) {
        error_log("Error obtenerReaccionUsuario: " . $e->getMessage());
        return null;
    }
}

function obtenerConteosReaccionesTema($pdo, $tema_id)
{
    try {
        $stmt = $pdo->prepare("
            SELECT 
                likes_count, love_count, haha_count, 
                wow_count, sad_count, angry_count, total_reacciones
            FROM bioforo_temas 
            WHERE id = ?
        ");
        $stmt->execute([$tema_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asegurar que todos los valores existan
        return array_merge([
            'likes_count' => 0,
            'love_count' => 0,
            'haha_count' => 0,
            'wow_count' => 0,
            'sad_count' => 0,
            'angry_count' => 0,
            'total_reacciones' => 0
        ], $result ?: []);
    } catch (Exception $e) {
        error_log("Error obtenerConteosReaccionesTema: " . $e->getMessage());
        return [
            'likes_count' => 0,
            'love_count' => 0,
            'haha_count' => 0,
            'wow_count' => 0,
            'sad_count' => 0,
            'angry_count' => 0,
            'total_reacciones' => 0
        ];
    }
}

function obtenerConteosReaccionesRespuesta($pdo, $respuesta_id)
{
    try {
        $stmt = $pdo->prepare("
            SELECT 
                likes_count, love_count, haha_count, 
                wow_count, sad_count, angry_count, total_reacciones
            FROM bioforo_respuestas 
            WHERE id = ?
        ");
        $stmt->execute([$respuesta_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asegurar que todos los valores existan
        return array_merge([
            'likes_count' => 0,
            'love_count' => 0,
            'haha_count' => 0,
            'wow_count' => 0,
            'sad_count' => 0,
            'angry_count' => 0,
            'total_reacciones' => 0
        ], $result ?: []);
    } catch (Exception $e) {
        error_log("Error obtenerConteosReaccionesRespuesta: " . $e->getMessage());
        return [
            'likes_count' => 0,
            'love_count' => 0,
            'haha_count' => 0,
            'wow_count' => 0,
            'sad_count' => 0,
            'angry_count' => 0,
            'total_reacciones' => 0
        ];
    }
}

// ==========================================
// API ENDPOINTS CORREGIDOS
// ==========================================
if (isset($_POST['action'])) {
    header('Content-Type: application/json');

    // OBTENER ESTADO DE REACCIONES
    if ($_POST['action'] === 'get_user_reactions') {
        $tema_ids = $_POST['tema_ids'] ?? [];
        $respuesta_ids = $_POST['respuesta_ids'] ?? [];
        $reactions = [];

        foreach ($tema_ids as $tema_id) {
            $reaction = obtenerReaccionUsuario($pdo, $tema_id, null, $usuario_id, $anonimo_id);
            if ($reaction) {
                $reactions['tema_' . $tema_id] = $reaction;
            }
        }

        foreach ($respuesta_ids as $respuesta_id) {
            $reaction = obtenerReaccionUsuario($pdo, null, $respuesta_id, $usuario_id, $anonimo_id);
            if ($reaction) {
                $reactions['respuesta_' . $respuesta_id] = $reaction;
            }
        }

        echo json_encode(['success' => true, 'reactions' => $reactions]);
        exit;
    }

    // TOGGLE REACCIÓN TEMA
    if ($_POST['action'] === 'toggle_reaccion_tema') {
        $tema_id = (int)$_POST['tema_id'];
        $tipo = $_POST['tipo'] ?? 'like';

        $tipos_validos = ['like', 'love', 'haha', 'wow', 'sad', 'angry'];
        if (!in_array($tipo, $tipos_validos)) {
            echo json_encode(['success' => false, 'error' => 'Tipo inválido']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("CALL sp_toggle_reaccion(?, NULL, ?, ?, ?)");
            $stmt->execute([$tema_id, $usuario_id, $anonimo_id, $tipo]);

            $action = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $user_reaction = ($action['action'] ?? '') === 'deleted' ? null : $tipo;

            echo json_encode([
                'success' => true,
                'action' => $action['action'] ?? 'unknown',
                'user_reaction' => $user_reaction,
                'counts' => [
                    'likes_count' => (int)($stats['likes_count'] ?? 0),
                    'love_count' => (int)($stats['love_count'] ?? 0),
                    'haha_count' => (int)($stats['haha_count'] ?? 0),
                    'wow_count' => (int)($stats['wow_count'] ?? 0),
                    'sad_count' => (int)($stats['sad_count'] ?? 0),
                    'angry_count' => (int)($stats['angry_count'] ?? 0),
                    'total_reacciones' => (int)($stats['total_reacciones'] ?? 0)
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error toggle_reaccion_tema: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // TOGGLE REACCIÓN RESPUESTA - SOLO LIKE Y ANGRY
    if ($_POST['action'] === 'toggle_reaccion_respuesta') {
        $respuesta_id = (int)$_POST['respuesta_id'];
        $tipo = $_POST['tipo'] ?? 'like';

        // Solo permitir like y angry para respuestas
        if ($tipo !== 'like' && $tipo !== 'angry') {
            echo json_encode(['success' => false, 'error' => 'Solo like/angry permitidos para respuestas']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("CALL sp_toggle_reaccion(NULL, ?, ?, ?, ?)");
            $stmt->execute([$respuesta_id, $usuario_id, $anonimo_id, $tipo]);

            $action = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $user_reaction = ($action['action'] ?? '') === 'deleted' ? null : $tipo;

            echo json_encode([
                'success' => true,
                'action' => $action['action'] ?? 'unknown',
                'user_reaction' => $user_reaction,
                'counts' => [
                    'likes_count' => (int)($stats['likes_count'] ?? 0),
                    'angry_count' => (int)($stats['angry_count'] ?? 0),
                    'total_reacciones' => (int)($stats['total_reacciones'] ?? 0)
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error toggle_reaccion_respuesta: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // CREAR TEMA
    if (isset($_POST['action'])) {
        header('Content-Type: application/json');

        // CREAR TEMA
        if ($_POST['action'] === 'nuevo_tema_ajax') {
            $titulo = trim($_POST['titulo'] ?? '');
            $contenido = trim($_POST['contenido'] ?? '');
            $cat_id = (int)($_POST['categoria'] ?? 0);

            // ✅ VALIDAR TÍTULO
            $validacionTitulo = validarContenidoOfensivo($titulo);
            if (!$validacionTitulo['valido']) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El título contiene palabras inapropiadas:  "' . $validacionTitulo['palabra'] . '"'
                ]);
                exit;
            }

            // ✅ VALIDAR CONTENIDO
            $validacionContenido = validarContenidoOfensivo($contenido);
            if (!$validacionContenido['valido']) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El contenido contiene palabras inapropiadas: "' . $validacionContenido['palabra'] .  '"'
                ]);
                exit;
            }

            if (mb_strlen($titulo) > 180) $titulo = mb_substr($titulo, 0, 180);
            if (mb_strlen($contenido) > 2000) $contenido = mb_substr($contenido, 0, 2000);

            if ($titulo && $contenido && $cat_id > 0) {
                try {
                    $pdo->beginTransaction();

                    $stmt = $pdo->prepare("
                    INSERT INTO bioforo_temas 
                    (categoria_id, usuario_id, anonimo_nombre, rol, titulo, contenido) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                    $stmt->execute([
                        $cat_id,
                        $usuario_id,
                        $usuario_id ?  null : $nombre,
                        $rol,
                        $titulo,
                        $contenido
                    ]);

                    $nuevo_tema_id = $pdo->lastInsertId();
                    $pdo->commit();

                    echo json_encode([
                        'success' => true,
                        'id' => $nuevo_tema_id,
                        'redirect' => '? tema=' . $nuevo_tema_id .  '#tema-' . $nuevo_tema_id
                    ]);
                } catch (Exception $e) {
                    $pdo->rollBack();
                    error_log("Error nuevo_tema_ajax: " . $e->getMessage());
                    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Campos incompletos']);
            }
            exit;
        }

        // CREAR RESPUESTA CON CITA - CORREGIDO
        if ($_POST['action'] === 'responder_ajax') {
            $tema_id = (int)$_POST['tema_id'];
            $contenido = trim($_POST['contenido'] ?? '');
            $cita_id = null;

            if (isset($_POST['cita_id']) && $_POST['cita_id'] !== '' && $_POST['cita_id'] !== '0') {
                $cita_id = (int)$_POST['cita_id'];
                if ($cita_id <= 0) $cita_id = null;
            }

            if (empty($contenido) || $tema_id <= 0) {
                echo json_encode(['success' => false, 'error' => 'Contenido vacío']);
                exit;
            }

            // ✅ VALIDAR RESPUESTA
            $validacion = validarContenidoOfensivo($contenido);
            if (!$validacion['valido']) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Tu respuesta contiene lenguaje ofensivo:  "' . $validacion['palabra'] . '"'
                ]);
                exit;
            }
        }

        if (mb_strlen($contenido) > 1500) {
            $contenido = mb_substr($contenido, 0, 1500);
        }

        try {
            $pdo->beginTransaction();

            // Verificar que el tema existe
            $stmt = $pdo->prepare("SELECT id FROM bioforo_temas WHERE id = ? AND estado = 'activo'");
            $stmt->execute([$tema_id]);
            if (!$stmt->fetch()) {
                throw new Exception("El tema no existe");
            }

            $respuesta_a_id = null;
            $cita_autor = null;
            $cita_contenido = null;

            // Si hay cita, obtener sus datos
            if ($cita_id !== null) {
                $stmt = $pdo->prepare("
                    SELECT r.id, COALESCE(u.username, r.anonimo_nombre) as autor, r.contenido
                    FROM bioforo_respuestas r
                    LEFT JOIN usuarios u ON u.id = r.usuario_id
                    WHERE r.id = ? AND r.tema_id = ? AND r.estado = 'activo'
                ");
                $stmt->execute([$cita_id, $tema_id]);
                $cita_data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($cita_data) {
                    $respuesta_a_id = $cita_id;
                    $cita_autor = $cita_data['autor'];
                    $cita_contenido = mb_substr($cita_data['contenido'], 0, 150);
                }
            }

            // Determinar rol y nombre
            if ($usuario_id) {
                $stmt = $pdo->prepare("SELECT username, rol FROM usuarios WHERE id = ?");
                $stmt->execute([$usuario_id]);
                $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
                $rol_usuario = $user_data['rol'] ?? 'usuario';
                $nombre_usuario = $user_data['username'] ?? 'Usuario';
            } else {
                $rol_usuario = 'anonimo';
                $nombre_usuario = $_SESSION['anonimo_nombre'] ?? 'Anónimo-' . rand(1000, 9999);
            }

            // Insertar respuesta
            $stmt = $pdo->prepare("
                INSERT INTO bioforo_respuestas 
                (tema_id, usuario_id, anonimo_nombre, rol, contenido, respuesta_a_id) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $tema_id,
                $usuario_id ?: null,
                $usuario_id ? null : $nombre_usuario,
                $rol_usuario,
                $contenido,
                $respuesta_a_id
            ]);

            $respuesta_id = $pdo->lastInsertId();

            // Obtener la respuesta recién insertada con datos de cita
            $resp = $pdo->prepare("
                SELECT r.*, 
                       COALESCE(u.username, r.anonimo_nombre) as autor,
                       u.avatar_url,
                       parent.id as cita_id,
                       COALESCE(u_parent.username, parent.anonimo_nombre) as cita_autor,
                       parent.contenido as cita_contenido
                FROM bioforo_respuestas r
                LEFT JOIN usuarios u ON u.id = r.usuario_id
                LEFT JOIN bioforo_respuestas parent ON parent.id = r.respuesta_a_id
                LEFT JOIN usuarios u_parent ON u_parent.id = parent.usuario_id
                WHERE r.id = ?
            ");
            $resp->execute([$respuesta_id]);
            $respuesta_data = $resp->fetch(PDO::FETCH_ASSOC);

            // Contar total de respuestas
            $count_total = $pdo->prepare("
                SELECT COUNT(*) as total 
                FROM bioforo_respuestas 
                WHERE tema_id = ? AND estado = 'activo'
            ");
            $count_total->execute([$tema_id]);
            $total_respuestas = $count_total->fetch(PDO::FETCH_ASSOC)['total'];

            $pdo->commit();

            echo json_encode([
                'success' => true,
                'respuesta_id' => $respuesta_id,
                'autor' => $respuesta_data['autor'] ?? 'Anónimo',
                'contenido' => $contenido,
                'fecha' => date('d M H:i'),
                'total_respuestas' => $total_respuestas,
                'cita_autor' => $respuesta_data['cita_autor'] ?? null,
                'cita_contenido' => $respuesta_data['cita_contenido'] ?? null,
                'cita_id' => $respuesta_data['cita_id'] ?? null
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error responder_ajax: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // CARGAR MÁS RESPUESTAS - CORREGIDO PARA MOSTRAR CITAS
    if ($_POST['action'] === 'cargar_mas_respuestas') {
        $tema_id = (int)$_POST['tema_id'];
        $offset = (int)($_POST['offset'] ?? 3);

        try {
            $stmt = $pdo->prepare("
                SELECT r.*, 
                       COALESCE(u.username, r.anonimo_nombre) as autor,
                       u.avatar_url,
                       parent.id as cita_id,
                       COALESCE(u_parent.username, parent.anonimo_nombre) as cita_autor,
                       parent.contenido as cita_contenido
                FROM bioforo_respuestas r
                LEFT JOIN usuarios u ON u.id = r.usuario_id
                LEFT JOIN bioforo_respuestas parent ON parent.id = r.respuesta_a_id
                LEFT JOIN usuarios u_parent ON u_parent.id = parent.usuario_id
                WHERE r.tema_id = ? AND r.estado = 'activo'
                ORDER BY r.creado_en DESC
                LIMIT ?, 1000
            ");
            $stmt->bindValue(1, $tema_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();

            $respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $html = '';

            foreach ($respuestas as $r) {
                $reaccion_usuario = obtenerReaccionUsuario($pdo, null, $r['id'], $usuario_id, $anonimo_id);
                $reacciones_data = obtenerConteosReaccionesRespuesta($pdo, $r['id']);

                $like_class = $reaccion_usuario === 'like' ? 'active-like' : '';
                $angry_class = $reaccion_usuario === 'angry' ? 'active-angry' : '';
                $like_icon = $reaccion_usuario === 'like' ? 'ph-thumbs-up-fill' : 'ph-thumbs-up';
                $angry_icon = $reaccion_usuario === 'angry' ? 'ph-thumbs-down-fill' : 'ph-thumbs-down';

                // Generar HTML de cita si existe
                $cita_html = '';
                if (!empty($r['cita_contenido'])) {
                    $cita_autor = $r['cita_autor'] ?? 'Anónimo';
                    $cita_contenido = mb_substr($r['cita_contenido'], 0, 100);
                    $cita_html = '
                    <div class="quote-box-whatsapp mb-2">
                        <div class="quote-author-whatsapp">' . htmlspecialchars($cita_autor) . '</div>
                        <div class="quote-text-whatsapp">' . nl2br(htmlspecialchars($cita_contenido)) . '</div>
                    </div>';
                }

                $html .= '
                <div class="bg-slate-50 rounded-lg p-3 border border-slate-200 respuesta-item" id="respuesta-' . $r['id'] . '">
                    <div class="flex items-start gap-2">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold flex-shrink-0 mt-0.5">
                            ' . strtoupper(substr($r['autor'], 0, 1)) . '
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-800 text-sm">' . htmlspecialchars($r['autor']) . '</span>
                                <span class="text-xs text-slate-500">' . date('d M H:i', strtotime($r['creado_en'])) . '</span>
                            </div>
                            ' . $cita_html . '
                            <p class="text-slate-600 text-sm mb-2">' . nl2br(htmlspecialchars($r['contenido'])) . '</p>
                            
                            <div class="flex items-center gap-3">
                                <button onclick="toggleReactionRespuesta(' . $r['id'] . ', \'like\')"
                                    class="flex items-center gap-1 text-xs text-slate-500 hover:text-blue-600 transition-colors ' . $like_class . '"
                                    id="like-btn-' . $r['id'] . '">
                                    <i class="' . $like_icon . '"></i>
                                    <span class="resp-likes-' . $r['id'] . '">' . ($reacciones_data['likes_count'] ?? 0) . '</span>
                                </button>
                                <button onclick="toggleReactionRespuesta(' . $r['id'] . ', \'angry\')"
                                    class="flex items-center gap-1 text-xs text-slate-500 hover:text-red-600 transition-colors ' . $angry_class . '"
                                    id="angry-btn-' . $r['id'] . '">
                                    <i class="' . $angry_icon . '"></i>
                                    <span class="resp-angry-' . $r['id'] . '">' . ($reacciones_data['angry_count'] ?? 0) . '</span>
                                </button>
                                <button onclick="citarRespuesta(' . $tema_id . ', ' . $r['id'] . ', \'' . htmlspecialchars($r['autor'], ENT_QUOTES) . '\', \'' . htmlspecialchars(addslashes($r['contenido']), ENT_QUOTES) . '\')"
                                    class="text-xs text-slate-500 hover:text-emerald-600 transition-colors">
                                    Responder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>';
            }

            echo json_encode([
                'success' => true,
                'html' => $html,
                'count' => count($respuestas)
            ]);
        } catch (Exception $e) {
            error_log("Error cargar_mas_respuestas: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // ✅ CARGAR TODOS LOS TEMAS RESTANTES
    if ($_POST['action'] === 'cargar_mas_temas') {
        $categoria_filtro = (int)($_POST['categoria'] ?? 0);
        $temas_ya_cargados = $_POST['temas_cargados'] ?? [];

        $where_categoria = $categoria_filtro > 0 ? "AND t.categoria_id = $categoria_filtro" : "";

        // Construir exclusión de IDs ya cargados
        $where_exclude = "";
        if (!empty($temas_ya_cargados) && is_array($temas_ya_cargados)) {
            $ids_safe = array_map('intval', $temas_ya_cargados);
            $where_exclude = "AND t.id NOT IN (" .  implode(',', $ids_safe) . ")";
        }

        try {
            $stmt = $pdo->prepare("
            SELECT t.*,
                   c.nombre AS cat_nombre,
                   COALESCE(u.username, t.anonimo_nombre) AS autor,
                   u.avatar_url,
                   TIMESTAMPDIFF(HOUR, t.creado_en, NOW()) as horas_desde_creacion
            FROM bioforo_temas t
            LEFT JOIN bioforo_categorias c ON c.id = t.categoria_id
            LEFT JOIN usuarios u ON u.id = t. usuario_id
            WHERE t.estado = 'activo' $where_categoria $where_exclude
            ORDER BY t.creado_en DESC
        ");
            $stmt->execute();

            $nuevos_temas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Cargar reacciones para cada tema
            foreach ($nuevos_temas as &$tema) {
                $tema['user_reaction'] = obtenerReaccionUsuario($pdo, $tema['id'], null, $usuario_id, $anonimo_id);
                $tema['reacciones'] = obtenerConteosReaccionesTema($pdo, $tema['id']);
            }

            // Generar HTML COMPLETO
            ob_start();
            foreach ($nuevos_temas as $tema):
                $reaccion_usuario_tema = $tema['user_reaction'];
                $reacciones_tema_data = $tema['reacciones'];
                $total_reacciones_tema = $reacciones_tema_data['total_reacciones'] ?? 0;

                $count_resp = $pdo->prepare("SELECT COUNT(*) as total FROM bioforo_respuestas WHERE tema_id = ?  AND estado = 'activo'");
                $count_resp->execute([$tema['id']]);
                $total_respuestas_tema = $count_resp->fetch(PDO::FETCH_ASSOC)['total'];
?>
                <div id="tema-<?= $tema['id'] ?>"
                    class="tema-item tema-cargado-despues bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-sm border border-slate-200 hover:shadow-md transition-all duration-300"
                    data-tema-extra="true"
                    style="animation: fadeIn 0.5s ease;">

                    <div class="tema-header flex items-start justify-between mb-4">
                        <div class="flex-1 flex items-start gap-3">
                            <div class="tema-avatar w-12 h-12 md:w-14 md:h-14 rounded-full bg-gradient-to-br from-blue-100 to-emerald-100 flex items-center justify-center text-blue-700 font-bold text-lg md:text-xl flex-shrink-0">
                                <?= strtoupper(substr($tema['autor'], 0, 1)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-base md:text-lg"><?= htmlspecialchars($tema['autor']) ?></h4>
                                        <div class="flex items-center gap-2 text-xs md:text-sm text-slate-500">
                                            <span><i class="ph-clock"></i> <?= date('d M Y H:i', strtotime($tema['creado_en'])) ?></span>
                                            <span class="hidden md:inline">•</span>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                <?= htmlspecialchars($tema['cat_nombre'] ?? 'General') ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tema-stats flex flex-row items-center gap-4">
                                        <div class="text-center">
                                            <div class="text-base md:text-lg font-bold text-slate-800" id="total-reactions-<?= $tema['id'] ?>">
                                                <?= $total_reacciones_tema ?>
                                            </div>
                                            <div class="text-xs text-slate-500">Reacciones</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-base md:text-lg font-bold text-slate-800" id="comment-count-display-<?= $tema['id'] ?>">
                                                <?= $total_respuestas_tema ?>
                                            </div>
                                            <div class="text-xs text-slate-500">Respuestas</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 md:mb-6">
                        <h3 class="text-lg md:text-xl font-bold text-slate-800 mb-3">
                            <?= htmlspecialchars($tema['titulo']) ?>
                        </h3>
                        <p class="text-slate-600 leading-relaxed text-sm md:text-base">
                            <?= nl2br(htmlspecialchars($tema['contenido'])) ?>
                        </p>
                    </div>

                    <div class="flex items-center justify-between tema-acciones-mobile border-t border-slate-100 pt-3 md:pt-4 gap-2">
                        <div class="relative group">
                            <button onclick="toggleReactionPopup(<?= $tema['id'] ?>)"
                                class="flex items-center gap-1.5 md:gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full bg-slate-50 hover:bg-slate-100 text-slate-700 transition-all reaction-btn-tema <?= $reaccion_usuario_tema ? 'reaction-' . $reaccion_usuario_tema : '' ?>"
                                id="reaction-btn-<?= $tema['id'] ?>"
                                data-reaction="<?= $reaccion_usuario_tema ?>">
                                <span class="text-base md:text-lg" id="reaction-icon-<?= $tema['id'] ?>">
                                    <i class="ph-heart"></i>
                                </span>
                                <span class="font-medium text-xs md:text-sm" id="reaction-label-<?= $tema['id'] ?>">
                                    Reaccionar
                                </span>
                            </button>
                            <div id="reactions-<?= $tema['id'] ?>" class="reaction-popup">
                                <div class="reaction-item" onclick="addReactionTema(<?= $tema['id'] ?>, 'like')" title="Me gusta">👍</div>
                                <div class="reaction-item" onclick="addReactionTema(<?= $tema['id'] ?>, 'love')" title="Me encanta">❤️</div>
                                <div class="reaction-item" onclick="addReactionTema(<?= $tema['id'] ?>, 'haha')" title="Me divierte">😂</div>
                                <div class="reaction-item" onclick="addReactionTema(<?= $tema['id'] ?>, 'wow')" title="Me asombra">😮</div>
                                <div class="reaction-item" onclick="addReactionTema(<?= $tema['id'] ?>, 'sad')" title="Me entristece">😢</div>
                                <div class="reaction-item" onclick="addReactionTema(<?= $tema['id'] ?>, 'angry')" title="Me enoja">😡</div>
                            </div>
                        </div>
                        <button onclick="toggleCommentField(<?= $tema['id'] ?>)"
                            class="flex items-center gap-1.5 md:gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 transition-all text-xs md:text-sm">
                            <i class="ph-chat-circle text-base"></i>
                            <span>Responder</span>
                        </button>

                        <button onclick="compartirTema(<?= $tema['id'] ?>)"
                            class="flex items-center gap-1.5 md:gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full bg-slate-50 hover:bg-slate-100 text-slate-700 transition-all text-xs md:text-sm">
                            <i class="ph-share-network text-base"></i>
                            <span>Compartir</span>
                        </button>
                    </div>
                    <?php if ($total_respuestas_tema > 0):
                        // Cargar TOP 3 respuestas
                        $stmt_resp = $pdo->prepare("
                            SELECT r.*, 
                                   COALESCE(u.username, r.anonimo_nombre) as autor,
                                   u.avatar_url,
                                   parent. id as cita_id,
                                   COALESCE(u_parent.username, parent.anonimo_nombre) as cita_autor,
                                   parent.contenido as cita_contenido
                            FROM bioforo_respuestas r
                            LEFT JOIN usuarios u ON u.id = r.usuario_id
                            LEFT JOIN bioforo_respuestas parent ON parent.id = r.respuesta_a_id
                            LEFT JOIN usuarios u_parent ON u_parent.id = parent.usuario_id
                            WHERE r. tema_id = ? AND r.estado = 'activo'
                            ORDER BY r.likes_count DESC, r.creado_en DESC
                            LIMIT 3
                        ");
                        $stmt_resp->execute([$tema['id']]);
                        $top_respuestas = $stmt_resp->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                        <div id="respuestas-container-<?= $tema['id'] ?>" class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-slate-100">
                            <h4 class="text-base md:text-lg font-semibold text-slate-800 mb-2 md:mb-3 flex items-center gap-2">
                                <i class="ph-chats-circle text-emerald-600"></i>
                                Respuestas TOP 3
                            </h4>

                            <div class="respuesta-minivista space-y-2 md:space-y-3 mb-2 md:mb-3" id="respuestas-list-<?= $tema['id'] ?>">
                                <?php foreach ($top_respuestas as $r):
                                    $reaccion_usuario_respuesta = obtenerReaccionUsuario($pdo, null, $r['id'], $usuario_id, $anonimo_id);
                                    $reacciones_respuesta_data = obtenerConteosReaccionesRespuesta($pdo, $r['id']);
                                    $like_class = $reaccion_usuario_respuesta === 'like' ? 'active-like' : '';
                                    $angry_class = $reaccion_usuario_respuesta === 'angry' ? 'active-angry' : '';
                                    $like_icon = $reaccion_usuario_respuesta === 'like' ?  'ph-thumbs-up-fill' : 'ph-thumbs-up';
                                    $angry_icon = $reaccion_usuario_respuesta === 'angry' ? 'ph-thumbs-down-fill' : 'ph-thumbs-down';
                                ?>
                                    <div class="bg-slate-50 rounded-lg p-2 md:p-3 border border-slate-200 respuesta-item top3-message" id="respuesta-<?= $r['id'] ?>">
                                        <div class="flex items-start gap-2">
                                            <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold flex-shrink-0 mt-0.5">
                                                <?= strtoupper(substr($r['autor'], 0, 1)) ?>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col md:flex-row md:items-center justify-between mb-1 gap-1 md:gap-0">
                                                    <span class="font-medium text-slate-800 text-xs md:text-sm truncate"><?= htmlspecialchars($r['autor']) ?></span>
                                                    <span class="text-xs text-slate-500"><?= date('d M H:i', strtotime($r['creado_en'])) ?></span>
                                                </div>
                                                <?php if (! empty($r['cita_contenido'])): ?>
                                                    <div class="quote-box-whatsapp mb-2">
                                                        <div class="quote-author-whatsapp"><?= htmlspecialchars($r['cita_autor']) ?></div>
                                                        <div class="quote-text-whatsapp"><?= nl2br(htmlspecialchars(mb_substr($r['cita_contenido'], 0, 150))) ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <p class="text-slate-600 text-xs md:text-sm mb-2"><?= nl2br(htmlspecialchars($r['contenido'])) ?></p>
                                                <div class="flex items-center gap-2 md:gap-3">
                                                    <button onclick="toggleReactionRespuesta(<?= $r['id'] ?>, 'like')"
                                                        class="flex items-center gap-1 text-xs text-slate-500 hover:text-blue-600 transition-colors <?= $like_class ?>"
                                                        id="like-btn-<?= $r['id'] ?>">
                                                        <i class="<?= $like_icon ?>"></i>
                                                        <span class="resp-likes-<?= $r['id'] ?>"><?= $reacciones_respuesta_data['likes_count'] ??  0 ?></span>
                                                    </button>
                                                    <button onclick="toggleReactionRespuesta(<?= $r['id'] ?>, 'angry')"
                                                        class="flex items-center gap-1 text-xs text-slate-500 hover:text-red-600 transition-colors <?= $angry_class ?>"
                                                        id="angry-btn-<?= $r['id'] ?>">
                                                        <i class="<?= $angry_icon ?>"></i>
                                                        <span class="resp-angry-<?= $r['id'] ?>"><?= $reacciones_respuesta_data['angry_count'] ?? 0 ?></span>
                                                    </button>
                                                    <button onclick="citarRespuesta(<?= $tema['id'] ?>, <?= $r['id'] ?>, '<?= htmlspecialchars($r['autor'], ENT_QUOTES) ?>', '<?= htmlspecialchars(str_replace(["\r", "\n"], ' ', $r['contenido']), ENT_QUOTES) ?>')"
                                                        class="text-xs text-slate-500 hover:text-emerald-600 transition-colors">
                                                        Responder
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($total_respuestas_tema > 3): ?>
                                <div class="text-center mt-2 md:mt-3">
                                    <button onclick="verMasRespuestas(<?= $tema['id'] ?>)"
                                        id="ver-mas-btn-<?= $tema['id'] ?>"
                                        class="text-xs md:text-sm text-emerald-600 hover:text-emerald-700 font-medium py-1 md:py-2 px-3 md:px-4 rounded-full border border-emerald-200 hover:bg-emerald-50 transition-all">
                                        Ver <?= $total_respuestas_tema - 3 ?> respuestas más
                                    </button>
                                    <button onclick="verMenosRespuestas(<?= $tema['id'] ?>)"
                                        id="ver-menos-btn-<?= $tema['id'] ?>"
                                        class="hidden text-xs md:text-sm text-emerald-600 hover: text-emerald-700 font-medium py-1 md: py-2 px-3 md:px-4 rounded-full border border-emerald-200 hover:bg-emerald-50 transition-all">
                                        Ver menos
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de respuesta -->
                    <div id="reply-form-<?= $tema['id'] ?>" class="hidden mt-4 md:mt-6 pt-4 md:pt-6 border-t border-slate-200">
                        <div id="quote-container-<?= $tema['id'] ?>"></div>
                        <form id="form-respuesta-<?= $tema['id'] ?>" onsubmit="return submitComment(<?= $tema['id'] ?>)">
                            <input type="hidden" id="cita-id-<?= $tema['id'] ?>" name="cita_id" value="">
                            <div class="mb-3 md:mb-4">
                                <textarea id="respuesta-input-<?= $tema['id'] ?>"
                                    name="contenido"
                                    placeholder="Escribe tu respuesta aquí..."
                                    rows="2"
                                    class="w-full px-3 md:px-4 py-2 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200 outline-none transition-all text-xs md:text-sm"></textarea>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                    onclick="cerrarFormularioRespuesta(<?= $tema['id'] ?>)"
                                    class="px-2 md:px-3 py-1 md:py-1.5 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors text-xs md:text-sm">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    id="submit-btn-<?= $tema['id'] ?>"
                                    class="px-3 md:px-4 py-1 md:py-1.5 bg-emerald-500 hover: bg-emerald-600 text-white rounded-lg font-medium transition-colors flex items-center gap-1 text-xs md: text-sm">
                                    <i class="ph-paper-plane-right"></i>
                                    <span id="submit-text-<?= $tema['id'] ?>">Publicar</span>
                                    <span id="submit-loading-<?= $tema['id'] ?>" class="hidden">
                                        <span class="loader-small"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
<?php endforeach;
            $html = ob_get_clean();

            echo json_encode([
                'success' => true,
                'html' => $html,
                'count' => count($nuevos_temas)
            ]);
        } catch (Exception $e) {
            error_log("Error cargar_mas_temas: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}

// ==========================================
// CARGAR DATOS PARA LA PÁGINA
// ==========================================

$categorias = $pdo->query("
    SELECT * FROM bioforo_categorias
    WHERE estado = 1
    ORDER BY orden
")->fetchAll(PDO::FETCH_ASSOC);

$categoria_filtro = (int)($_GET['categoria'] ?? 0);
$where_categoria = $categoria_filtro > 0 ? "AND t. categoria_id = $categoria_filtro" : "";

// ORDENAMIENTO: Temas nuevos (menos de 2 horas) primero, luego por reacciones
$temas = $pdo->query("
    SELECT t.*,
           c.nombre AS cat_nombre,
           COALESCE(u.username, t.anonimo_nombre) AS autor,
           u.avatar_url,
           TIMESTAMPDIFF(HOUR, t.creado_en, NOW()) as horas_desde_creacion
    FROM bioforo_temas t
    LEFT JOIN bioforo_categorias c ON c.id = t.categoria_id
    LEFT JOIN usuarios u ON u.id = t. usuario_id
    WHERE t. estado = 'activo' $where_categoria
    ORDER BY 
        CASE 
            WHEN TIMESTAMPDIFF(HOUR, t.creado_en, NOW()) < 2 THEN 0
            ELSE 1
        END ASC,
        t.total_reacciones DESC,
        t.creado_en DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Cargar reacciones de usuario para cada tema
foreach ($temas as &$tema) {
    $tema['user_reaction'] = obtenerReaccionUsuario($pdo, $tema['id'], null, $usuario_id, $anonimo_id);
    $tema['reacciones'] = obtenerConteosReaccionesTema($pdo, $tema['id']);
}
unset($tema);


$estadisticas = $pdo->query("
    SELECT
        COUNT(*) as total_temas,
        COUNT(DISTINCT CASE WHEN usuario_id IS NOT NULL THEN usuario_id END) as usuarios_registrados
    FROM bioforo_temas WHERE estado = 'activo'
")->fetch(PDO::FETCH_ASSOC);

$total_respuestas = $pdo->query("
    SELECT COUNT(*) as total
    FROM bioforo_respuestas
    WHERE estado = 'activo'
")->fetch(PDO::FETCH_ASSOC)['total'];

$usuarios_en_linea = 0;
$tema_hash = isset($_GET['tema']) ? '#tema-' . (int)$_GET['tema'] : '';


require_once __DIR__ . '/Bioforo_vista.php';
