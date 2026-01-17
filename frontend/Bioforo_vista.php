<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioForo - Lyrium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="utils/css/bioforo.css?v=<?php echo time(); ?>">
</head>

<body class="bg-slate-50 min-h-screen font-['Inter']">

    <?php if (file_exists('header.php')) include 'header.php'; ?>

    <main class="w-full max-w-7xl mx-auto px-3 md:px-4 py-4 md:py-8 space-y-6 md:space-y-10">
        <!-- Hero Section -->
        <section class="relative mt-2 md:mt-8 rounded-xl md:rounded-[24px] overflow-hidden shadow-lg md:shadow-2xl min-h-[160px] md:min-h-[320px] bg-[#0b1220] group">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 transform group-hover:scale-105"
                style="background-image:url('https://lyriumbiomarketplace.com/wp-content/uploads/2025/06/bioforo_banner-scaled.jpg');">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 md:p-14 max-w-3xl text-white h-full flex flex-col justify-center">
                <h1 class="hero-title text-2xl md:text-6xl font-extrabold tracking-tight uppercase drop-shadow-xl mb-2 md:mb-4 leading-tight">
                    Conecta <span class="text-[#2ea8ff]">BioForo</span>
                </h1>
                <p class="hero-subtitle text-xs md:text-base font-medium text-slate-200 tracking-widest uppercase mb-4 md:mb-8 max-w-lg leading-relaxed opacity-90">
                    Explora foros destacados, nuevas ideas y una comunidad apasionada.
                </p>
                <div class="w-16 md:w-24 h-1 md:h-1.5 rounded-full bg-[#2ea8ff] shadow-[0_0_20px_rgba(46,168,255,0.6)]"></div>
            </div>
        </section>
        <!-- Intro Section Responsive -->
        <section class="grid md:grid-cols-2 gap-4 md:gap-8 items-center bg-white rounded-xl md:rounded-3xl p-4 md:p-8 shadow-sm border border-slate-100">
            <div class="rounded-lg md:rounded-2xl overflow-hidden shadow-md md:shadow-lg aspect-[4/3] relative group order-2 md:order-1">
                <img src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/Fondos_BioBlog-4.png" alt="BioForo Intro"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
            </div>
            <div class="flex flex-col gap-3 md:gap-6 order-1 md:order-2">
                <div class="flex items-start gap-3 md:gap-4">
                    <div class="flex-shrink-0 w-10 h-10 md:w-16 md:h-16 rounded-lg md:rounded-2xl bg-slate-50 border border-slate-200 grid place-items-center shadow-sm">
                        <img src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/Fondos_BioBlog-4.png" alt="Icon"
                            class="w-6 h-6 md:w-10 md:h-10 object-contain">
                    </div>
                    <div>
                        <h3 class="text-base md:text-xl font-bold text-slate-900 mb-1">Bienvenido a la comunidad</h3>
                        <p class="text-slate-600 leading-relaxed text-sm md:text-[15px]">
                            <strong class="text-slate-900">BioForo</strong> es el espacio donde expertos, emprendedores y entusiastas se conectan.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <div class="flex -space-x-2">
                        <div class="w-7 h-7 md:w-10 md:h-10 rounded-full border-2 border-white bg-slate-200"></div>
                        <div class="w-7 h-7 md:w-10 md:h-10 rounded-full border-2 border-white bg-slate-300"></div>
                        <div class="w-7 h-7 md:w-10 md:h-10 rounded-full border-2 border-white bg-slate-400"></div>
                    </div>
                    <span class="text-xs md:text-sm font-semibold text-slate-500">Únete a la conversación</span>
                </div>
            </div>
        </section>

        <!-- Estadísticas -->
        <div class="estadisticas-container grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6 mb-6 md:mb-8">
            <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-emerald-100 rounded-lg md:rounded-xl flex items-center justify-center">
                        <i class="ph-chart-bar text-lg md:text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm text-slate-500">Temas activos</p>
                        <p class="text-xl md:text-2xl font-bold text-slate-800"><?= $estadisticas['total_temas'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-blue-100 rounded-lg md:rounded-xl flex items-center justify-center">
                        <i class="ph-chat-circle-text text-lg md:text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm text-slate-500">Respuestas</p>
                        <p class="text-xl md:text-2xl font-bold text-slate-800"><?= $total_respuestas ?></p>
                    </div>
                </div>
            </div>
            <div class="col-span-2 md:col-span-1 bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-purple-100 rounded-lg md:rounded-xl flex items-center justify-center">
                        <i class="ph-users text-lg md:text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm text-slate-500">Usuarios en línea</p>
                        <p class="text-xl md:text-2xl font-bold text-slate-800 flex items-center">
                            <span class="w-2 h-2 md:w-3 md:h-3 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                            <?= $usuarios_en_linea ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y Acciones -->
        <div class="flex flex-col md:flex-row gap-3 md:gap-4 mb-6 md:mb-8 items-stretch md:items-center">
            <div class="flex-1 flex flex-col md:flex-row gap-2 md:items-center">
                <div class="relative w-full md:w-auto">
                    <button onclick="toggleFiltroDropdown()"
                        class="flex items-center justify-between md:justify-start gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 transition-all w-full md:w-auto">
                        <div class="flex items-center gap-2">
                            <i class="ph-funnel"></i>
                            <span class="truncate">Filtrar por categoría</span>
                        </div>
                        <i class="ph-caret-down"></i>
                    </button>

                    <div id="filtroDropdown" class="filtro-dropdown">
                        <a href="?categoria=0"
                            class="block px-4 py-2 hover:bg-slate-50 text-slate-700 <?= $categoria_filtro == 0 ? 'bg-emerald-50 text-emerald-700 font-medium' : '' ?>">
                            <div class="flex items-center gap-2">
                                <i class="ph-globe"></i>
                                <span>Todas las categorías</span>
                            </div>
                        </a>
                        <?php foreach ($categorias as $cat): ?>
                            <a href="?categoria=<?= $cat['id'] ?>"
                                class="block px-4 py-2 hover:bg-slate-50 text-slate-700 <?= $categoria_filtro == $cat['id'] ? 'bg-emerald-50 text-emerald-700 font-medium' : '' ?>">
                                <div class="flex items-center gap-2">
                                    <i class="ph-folder"></i>
                                    <span><?= htmlspecialchars($cat['nombre']) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($categoria_filtro > 0):
                    $cat_activa = array_filter($categorias, fn($c) => $c['id'] == $categoria_filtro);
                    if (!empty($cat_activa)):
                        $cat = reset($cat_activa);
                ?>
                        <div class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium flex items-center justify-between md:justify-start gap-2">
                            <div class="flex items-center gap-2">
                                <i class="ph-check"></i>
                                <span class="truncate"><?= htmlspecialchars($cat['nombre']) ?></span>
                            </div>
                            <a href="?categoria=0" class="text-emerald-500 hover:text-emerald-700">
                                <i class="ph-x"></i>
                            </a>
                        </div>
                <?php endif;
                endif; ?>
            </div>

            <button onclick="openCreateModal()"
                class="btn-crear-tema bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-4 py-3 rounded-full font-semibold shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 w-full md:w-auto">
                <i class="ph-pencil-simple-bold"></i>
                <span class="truncate">Crear Nuevo Tema</span>
            </button>
        </div>



        <?php
        // 🔍 DEBUG TEMPORAL
        error_log("===== RENDERIZANDO TEMAS EN VISTA =====");
        error_log("Total temas a renderizar: " . count($temas));
        foreach ($temas as $t) {
            error_log("  - Tema ID:  " . $t['id'] .  " | " . $t['titulo']);
        }
        error_log("===========================================");
        ?>

        <?php
        // 🔍 DEBUG:  Visible en código fuente HTML
        echo "\n<!-- ===== DEBUG TEMAS ===== -->\n";
        echo "<!-- Total temas para renderizar: " . count($temas) . " -->\n";
        echo "<!-- Categoría filtro: " . $categoria_filtro . " -->\n";
        foreach ($temas as $idx => $t) {
            echo "<!--   [$idx] ID: " . $t['id'] . " | Título: " . htmlspecialchars($t['titulo']) . " | Cat: " . htmlspecialchars($t['cat_nombre'] ?? 'NULL') . " -->\n";
        }
        echo "<!-- ===== FIN DEBUG ===== -->\n\n";
        ?>

        <!-- Lista de Temas -->
        <div class="space-y-4 md:space-y-6">
            <?php if (empty($temas)): ?>
                <div class="bg-white rounded-xl md:rounded-2xl p-8 md:p-12 text-center border border-slate-200">
                    <i class="ph-chats-teardrop text-4xl md:text-6xl text-slate-300 mb-3 md:mb-4 inline-block"></i>
                    <h3 class="text-lg md:text-xl font-semibold text-slate-700 mb-2">No hay temas aún</h3>
                    <p class="text-slate-500 mb-4 md:mb-6 text-sm md:text-base">Sé el primero en crear un tema de discusión</p>
                    <button onclick="openCreateModal()"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-full font-medium inline-flex items-center gap-2 text-sm md:text-base">
                        <i class="ph-plus-bold"></i>
                        Crear primer tema
                    </button>
                </div>
            <?php else: ?>
                <?php foreach ($temas as $tema):
                    // 🔍 DEBUG: Visible en código fuente HTML
                    echo "<!-- ========== TEMA ID: " . $tema['id'] . " INICIO ========== -->\n";

                    $reaccion_usuario_tema = $tema['user_reaction'];
                    $reacciones_tema_data = $tema['reacciones'];
                    $total_reacciones_tema = $reacciones_tema_data['total_reacciones'] ?? 0;

                    echo "<!-- Reacciones tema " . $tema['id'] . ": " . $total_reacciones_tema . " -->\n";


                    $reaccion_usuario_tema = $tema['user_reaction'];
                    $reacciones_tema_data = $tema['reacciones'];
                    $total_reacciones_tema = $reacciones_tema_data['total_reacciones'] ?? 0;

                    // Obtener TOP 3 respuestas (ordenadas por likes) CON DATOS DE CITAS
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
                        ORDER BY r.likes_count DESC, r.creado_en DESC
                        LIMIT 3
                    ");
                    $stmt->execute([$tema['id']]);
                    $top_respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Contar total de respuestas
                    $count_total = $pdo->prepare("SELECT COUNT(*) as total FROM bioforo_respuestas WHERE tema_id = ? AND estado = 'activo'");
                    $count_total->execute([$tema['id']]);
                    $total_respuestas_tema = $count_total->fetch(PDO::FETCH_ASSOC)['total'];
                ?>
                    <div id="tema-<?= $tema['id'] ?>"
                        class="tema-item bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-sm border border-slate-200 hover:shadow-md transition-all duration-300">

                        <!-- Encabezado -->
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

                        <!-- Título y Contenido -->
                        <div class="mb-4 md:mb-6">
                            <h3 class="text-lg md:text-xl font-bold text-slate-800 mb-3">
                                <?= htmlspecialchars($tema['titulo']) ?>
                            </h3>
                            <p class="text-slate-600 leading-relaxed text-sm md:text-base">
                                <?= nl2br(htmlspecialchars($tema['contenido'])) ?>
                            </p>
                        </div>

                        <!-- Acciones -->
                        <div class="flex items-center justify-between md:justify-between tema-acciones-mobile border-t border-slate-100 pt-3 md:pt-4 gap-2">
                            <div class="relative group">
                                <button onclick="toggleReactionPopup(<?= $tema['id'] ?>)"
                                    class="flex items-center gap-1.5 md:gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full bg-slate-50 hover:bg-slate-100 text-slate-700 transition-all reaction-btn-tema <?= $reaccion_usuario_tema ? 'reaction-' . $reaccion_usuario_tema : '' ?>"
                                    id="reaction-btn-<?= $tema['id'] ?>"
                                    data-reaction="<?= $reaccion_usuario_tema ?>">
                                    <span class="text-base md:text-lg" id="reaction-icon-<?= $tema['id'] ?>">
                                        <?php
                                        $icons = [
                                            'like' => '👍',
                                            'love' => '❤️',
                                            'haha' => '😂',
                                            'wow' => '😮',
                                            'sad' => '😢',
                                            'angry' => '😡'
                                        ];
                                        if ($reaccion_usuario_tema && isset($icons[$reaccion_usuario_tema])) {
                                            echo $icons[$reaccion_usuario_tema];
                                        } else {
                                            echo '<i class="ph-heart"></i>';
                                        }
                                        ?>
                                    </span>
                                    <span class="font-medium text-xs md:text-sm" id="reaction-label-<?= $tema['id'] ?>">
                                        <?php
                                        $labels = [
                                            'like' => 'Me gusta',
                                            'love' => 'Me encanta',
                                            'haha' => 'Me divierte',
                                            'wow' => 'Me asombra',
                                            'sad' => 'Me entristece',
                                            'angry' => 'Me enoja'
                                        ];
                                        echo $reaccion_usuario_tema ?
                                            ($labels[$reaccion_usuario_tema] ?? 'Reaccionar') :
                                            'Reaccionar';
                                        ?>
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

                        <!-- Respuestas -->
                        <?php if ($total_respuestas_tema > 0): ?>
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
                                        $like_icon = $reaccion_usuario_respuesta === 'like' ? 'ph-thumbs-up-fill' : 'ph-thumbs-up';
                                        $angry_icon = $reaccion_usuario_respuesta === 'angry' ? 'ph-thumbs-down-fill' : 'ph-thumbs-down';
                                    ?>
                                        <div class="bg-slate-50 rounded-lg p-2 md:p-3 border border-slate-200 respuesta-item <?= array_search($r, $top_respuestas) < 3 ? 'top3-message' : '' ?>" id="respuesta-<?= $r['id'] ?>">
                                            <div class="flex items-start gap-2">
                                                <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold flex-shrink-0 mt-0.5">
                                                    <?= strtoupper(substr($r['autor'], 0, 1)) ?>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-1 gap-1 md:gap-0">
                                                        <span class="font-medium text-slate-800 text-xs md:text-sm truncate"><?= htmlspecialchars($r['autor']) ?></span>
                                                        <span class="text-xs text-slate-500">
                                                            <?= date('d M H:i', strtotime($r['creado_en'])) ?>
                                                        </span>
                                                    </div>

                                                    <!-- CITA en respuestas TOP 3 -->
                                                    <?php if (!empty($r['cita_contenido'])): ?>
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
                                                            <span class="resp-likes-<?= $r['id'] ?>"><?= $reacciones_respuesta_data['likes_count'] ?? 0 ?></span>
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
                                            class="hidden text-xs md:text-sm text-emerald-600 hover:text-emerald-700 font-medium py-1 md:py-2 px-3 md:px-4 rounded-full border border-emerald-200 hover:bg-emerald-50 transition-all">
                                            Ver menos
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulario de Respuesta -->
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
                                        class="px-3 md:px-4 py-1 md:py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium transition-colors flex items-center gap-1 text-xs md:text-sm">
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

                    <?php echo "<!-- ========== TEMA ID: " .  $tema['id'] . " FIN ========== -->\n"; ?>

                <?php endforeach; ?>
            <?php endif; ?>
            <?php
            // Calcular temas según filtro
            if ($categoria_filtro > 0) {
                // Contar temas de la categoría seleccionada
                $stmt_cat = $pdo->prepare("
                    SELECT COUNT(*) as total 
                    FROM bioforo_temas 
                    WHERE estado = 'activo' AND categoria_id = ? 
                ");
                $stmt_cat->execute([$categoria_filtro]);
                $total_temas_filtro = $stmt_cat->fetch(PDO::FETCH_ASSOC)['total'];
            } else {
                $total_temas_filtro = $estadisticas['total_temas'];
            }

            $temas_cargados = count($temas);
            $temas_restantes = $total_temas_filtro - $temas_cargados;
            ?>

            <?php if ($temas_restantes > 0): ?>
                <div class="text-center mt-6 md:mt-8">
                    <!-- Botón VER MÁS -->
                    <button onclick="verMasTemas()"
                        id="ver-mas-temas-btn"
                        class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-6 py-3 rounded-full font-semibold shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 mx-auto text-sm md:text-base">
                        <i class="ph-arrow-down"></i>
                        <span id="ver-mas-temas-text">
                            <?php if ($categoria_filtro > 0): ?>
                                Ver todos los temas de esta categoría (<?= $temas_restantes ?> más)
                            <?php else:  ?>
                                Ver todos los temas (<?= $temas_restantes ?> más)
                            <?php endif; ?>
                        </span>
                        <span id="ver-mas-temas-loading" class="hidden flex items-center gap-2">
                            <span class="loader-small"></span>
                            <span>Cargando...</span>
                        </span>
                    </button>

                    <!-- Botón VER MENOS -->
                    <button onclick="verMenosTemas()"
                        id="ver-menos-temas-btn"
                        class="hidden bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-full font-semibold shadow-lg transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 mx-auto text-sm md:text-base">
                        <i class="ph-arrow-up"></i>
                        <span>Mostrar menos temas</span>
                    </button>

                    <p class="text-xs text-slate-500 mt-2">
                        <?php if ($categoria_filtro > 0): ?>
                            Mostrando <span id="temas-cargados-count"><?= $temas_cargados ?></span> de <?= $total_temas_filtro ?> temas en esta categoría
                        <?php else: ?>
                            Mostrando <span id="temas-cargados-count"><?= $temas_cargados ?></span> de <?= $total_temas_filtro ?> temas
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

        </div> <!-- Cierre de . space-y-4 -->
    </main>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>

    <!-- Modal crear tema -->
    <div id="createModal" class="fixed inset-0 z-50 hidden modal-overlay">
        <div class="modal-center bg-white rounded-xl md:rounded-2xl w-full max-w-2xl mx-2 md:mx-0">
            <div class="sticky top-0 bg-white border-b border-slate-200 p-4 md:p-6 rounded-t-xl md:rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 md:gap-3">
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg md:rounded-xl flex items-center justify-center">
                            <i class="ph-pencil-simple-line text-white text-lg md:text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-slate-800">Crear nuevo tema</h3>
                            <p class="text-slate-500 text-xs md:text-sm">Comparte tus ideas con la comunidad</p>
                        </div>
                    </div>
                    <button onclick="closeCreateModal()"
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-700 transition-colors">
                        <i class="ph-x text-lg md:text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="formNuevoTema" class="p-4 md:p-6">
                <div class="space-y-3 md:space-y-4">
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-slate-700 mb-1 md:mb-2">Categoría</label>
                        <select id="categoria" name="categoria" required
                            class="w-full px-3 md:px-4 py-2 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200 outline-none transition-all text-xs md:text-sm">
                            <option value="">Selecciona una categoría</option>
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-medium text-slate-700 mb-1 md:mb-2">Título del tema</label>
                        <input type="text"
                            id="titulo"
                            name="titulo"
                            maxlength="120"
                            required
                            placeholder="Escribe un título claro y descriptivo"
                            class="w-full px-3 md:px-4 py-2 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200 outline-none transition-all text-xs md:text-sm">
                        <div class="flex justify-between mt-1">
                            <p class="text-xs text-slate-500">Sé específico con tu pregunta o tema</p>
                            <span id="titulo-count" class="text-xs text-slate-400">0/120</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-medium text-slate-700 mb-1 md:mb-2">Contenido</label>
                        <textarea id="contenido"
                            name="contenido"
                            rows="4"
                            maxlength="2000"
                            required
                            placeholder="Describe tu tema con detalle..."
                            class="w-full px-3 md:px-4 py-2 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200 outline-none transition-all resize-none text-xs md:text-sm"></textarea>
                        <div class="flex justify-between mt-1">
                            <p class="text-xs text-slate-500">Incluye toda la información relevante (mínimo de caracteres: 10)</p>
                            <span id="contenido-count" class="text-xs text-slate-400">0/2000</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 md:gap-3 mt-4 md:mt-6 pt-4 md:pt-6 border-t border-slate-200">
                    <button type="button"
                        onclick="closeCreateModal()"
                        class="px-3 md:px-4 py-1.5 md:py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors font-medium text-xs md:text-sm">
                        Cancelar
                    </button>
                    <button type="submit"
                        id="submitNuevoTema"
                        class="px-3 md:px-5 py-1.5 md:py-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-lg font-medium transition-all flex items-center gap-1 text-xs md:text-sm">
                        <i class="ph-paper-plane-right"></i>
                        <span id="submitText">Publicar tema</span>
                        <span id="submitLoading" class="hidden">
                            <span class="loader-small"></span>
                            <span class="hidden md:inline"> Publicando...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="utils/js/bioforo.js?v=<?php echo time(); ?>"></script>
    <script src="utils/js/filtro_contenido.js?v=<?php echo time(); ?>"></script>

    <script>
        // ✅ SOLO código que NO está en bioforo.js

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('filtroDropdown');
            const button = event.target.closest('[onclick="toggleFiltroDropdown()"]');
            if (dropdown && !dropdown.contains(event.target) && !button) {
                dropdown.classList.remove('show');
            }
        });

        // Cerrar modal con Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('createModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeCreateModal();
                }
            }
        });

        // Cerrar modal al hacer clic fuera
        document.getElementById('createModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });
    </script>

</body>

</html>