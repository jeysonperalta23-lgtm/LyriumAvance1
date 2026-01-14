<?php
/**
 * ═══════════════════════════════════════════════════════════════════════════
 * MODELO: Tienda
 * ═══════════════════════════════════════════════════════════════════════════
 * 
 * Maneja todas las operaciones relacionadas con tiendas en el sistema
 * Incluye soporte para planes Básico y Premium con sus respectivos límites
 * 
 * UBICACIÓN: models/Tienda.php
 * 
 * ═══════════════════════════════════════════════════════════════════════════
 */

class Tienda {
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // LÍMITES POR PLAN
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    const LIMITES = [
        'basico' => [
            'banners_principales' => 2,
            'banners_sidebar' => 0,
            'fotos' => 8,
            'productos_grid' => 15,  // 5 columnas × 3 filas
            'redes_sociales' => 6,
            'sucursales' => 8,
            'layouts' => 1,
            'temas' => 0
        ],
        'premium' => [
            'banners_principales' => 4,
            'banners_sidebar' => 3,
            'fotos' => 30,
            'productos_grid' => 25,  // 5 columnas × 5 filas
            'redes_sociales' => 10,
            'sucursales' => 8,
            'layouts' => 3,
            'temas' => 3
        ]
    ];
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // CRUD BÁSICO
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Obtener tienda por slug
     * 
     * @param string $slug - Slug único de la tienda
     * @return array|null - Datos de la tienda o null si no existe
     */
    public function obtener_por_slug($slug) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT * FROM tiendas WHERE Slug = ? AND Estado = 'activo'
        // 
        // Retornar:
        // [
        //   'id' => int,
        //   'nombre' => string,
        //   'slug' => string,
        //   'plan' => 'basico' | 'premium',
        //   'layout_modelo' => 1 | 2 | 3,
        //   'tema' => '' | 'tema-ocean' | 'tema-dark' | 'tema-minimal',
        //   ... (todos los campos)
        // ]
    }
    
    /**
     * Obtener tienda por ID
     * 
     * @param int $id_tienda
     * @return array|null
     */
    public function obtener_por_id($id_tienda) {
        // TODO: Implementar
        // Similar a obtener_por_slug pero con IdTienda
    }
    
    /**
     * Crear nueva tienda
     * 
     * @param array $datos - Datos de la tienda
     * @return int - ID de la tienda creada
     */
    public function crear($datos) {
        // TODO: Implementar
        // 
        // Validaciones:
        // - Slug único
        // - Usuario existe
        // - Plan válido ('basico' o 'premium')
        // 
        // SQL:
        // INSERT INTO tiendas (IdUsuario, Nombre, Slug, ...) VALUES (?, ?, ?, ...)
        // 
        // Crear también:
        // - Horarios por defecto (7 días)
        // - Estadísticas iniciales
    }
    
    /**
     * Actualizar tienda
     * 
     * @param int $id_tienda
     * @param array $datos
     * @return bool
     */
    public function actualizar($id_tienda, $datos) {
        // TODO: Implementar
        // 
        // SQL:
        // UPDATE tiendas SET Nombre = ?, Descripcion = ?, ... WHERE IdTienda = ?
    }
    
    /**
     * Eliminar tienda
     * 
     * @param int $id_tienda
     * @return bool
     */
    public function eliminar($id_tienda) {
        // TODO: Implementar
        // 
        // SQL:
        // UPDATE tiendas SET Estado = 'inactivo' WHERE IdTienda = ?
        // 
        // O si se quiere eliminación física:
        // DELETE FROM tiendas WHERE IdTienda = ?
        // (Esto eliminará en cascada todas las tablas relacionadas)
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // PERSONALIZACIÓN (PREMIUM)
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Actualizar modelo de layout
     * 
     * @param int $id_tienda
     * @param int $modelo - 1, 2 o 3
     * @return bool
     */
    public function actualizar_layout($id_tienda, $modelo) {
        // TODO: Implementar
        // 
        // Validaciones:
        // 1. Verificar que la tienda tenga plan Premium
        // 2. Verificar que $modelo sea 1, 2 o 3
        // 
        // SQL:
        // UPDATE tiendas SET LayoutModelo = ? WHERE IdTienda = ? AND Plan = 'premium'
        // 
        // Ejemplo:
        // $tienda = $this->obtener_por_id($id_tienda);
        // if ($tienda['plan'] !== 'premium') {
        //     throw new Exception('Esta función requiere Plan Premium');
        // }
        // if (!in_array($modelo, [1, 2, 3])) {
        //     throw new Exception('Modelo de layout inválido');
        // }
    }
    
    /**
     * Actualizar tema de color
     * 
     * @param int $id_tienda
     * @param string $tema - '', 'tema-ocean', 'tema-dark', 'tema-minimal'
     * @return bool
     */
    public function actualizar_tema($id_tienda, $tema) {
        // TODO: Implementar
        // 
        // Validaciones:
        // 1. Verificar que la tienda tenga plan Premium (si tema != '')
        // 2. Verificar que $tema sea válido
        // 
        // SQL:
        // UPDATE tiendas SET Tema = ? WHERE IdTienda = ?
        // 
        // Temas válidos: '', 'tema-ocean', 'tema-dark', 'tema-minimal'
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // BANNERS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Obtener banners de la tienda
     * 
     * @param int $id_tienda
     * @param string|null $tipo - 'principal', 'sidebar', 'horizontal' o null para todos
     * @return array
     */
    public function obtener_banners($id_tienda, $tipo = null) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT * FROM tienda_banners 
        // WHERE IdTienda = ? AND Estado = 'activo'
        // [AND Tipo = ?]
        // ORDER BY Orden ASC
        // 
        // Aplicar límites según plan:
        // - Básico: máximo 2 principales
        // - Premium: máximo 4 principales + 3 sidebar
    }
    
    /**
     * Agregar banner
     * 
     * @param int $id_tienda
     * @param array $datos - ['tipo' => string, 'url' => string, 'titulo' => string]
     * @return int - ID del banner creado
     */
    public function agregar_banner($id_tienda, $datos) {
        // TODO: Implementar
        // 
        // Validaciones:
        // 1. Verificar límite según plan
        // 2. Validar tipo de banner
        // 
        // SQL:
        // INSERT INTO tienda_banners (IdTienda, Tipo, Url, Titulo, Orden)
        // VALUES (?, ?, ?, ?, ?)
    }
    
    /**
     * Eliminar banner
     * 
     * @param int $id_banner
     * @return bool
     */
    public function eliminar_banner($id_banner) {
        // TODO: Implementar
        // 
        // SQL:
        // DELETE FROM tienda_banners WHERE IdBanner = ?
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // REDES SOCIALES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Obtener redes sociales
     * 
     * @param int $id_tienda
     * @return array - Array asociativo ['plataforma' => ['url' => string]]
     */
    public function obtener_redes_sociales($id_tienda) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT Plataforma, Url FROM tienda_redes_sociales
        // WHERE IdTienda = ?
        // ORDER BY Orden ASC
        // 
        // Retornar formato:
        // [
        //   'instagram' => ['url' => 'https://...'],
        //   'facebook' => ['url' => 'https://...'],
        //   ...
        // ]
    }
    
    /**
     * Actualizar redes sociales
     * 
     * @param int $id_tienda
     * @param array $redes - ['plataforma' => 'url', ...]
     * @return bool
     */
    public function actualizar_redes_sociales($id_tienda, $redes) {
        // TODO: Implementar
        // 
        // Estrategia:
        // 1. Eliminar todas las redes actuales
        // 2. Insertar las nuevas
        // 
        // Validar límite según plan (6 para básico, 10 para premium)
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // HORARIOS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Obtener horarios
     * 
     * @param int $id_tienda
     * @return array - Array asociativo ['dia' => ['apertura' => time, 'cierre' => time, 'cerrado' => bool]]
     */
    public function obtener_horarios($id_tienda) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT DiaSemana, HoraApertura, HoraCierre, Cerrado
        // FROM tienda_horarios
        // WHERE IdTienda = ?
        // 
        // Retornar formato:
        // [
        //   'lunes' => ['apertura' => '09:00', 'cierre' => '18:00', 'cerrado' => false],
        //   ...
        // ]
    }
    
    /**
     * Actualizar horarios
     * 
     * @param int $id_tienda
     * @param array $horarios
     * @return bool
     */
    public function actualizar_horarios($id_tienda, $horarios) {
        // TODO: Implementar
        // 
        // Usar INSERT ... ON DUPLICATE KEY UPDATE
        // para actualizar o insertar cada día
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // SUCURSALES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Obtener sucursales
     * 
     * @param int $id_tienda
     * @return array
     */
    public function obtener_sucursales($id_tienda) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT * FROM tienda_sucursales
        // WHERE IdTienda = ?
        // ORDER BY EsPrincipal DESC, Orden ASC
        // LIMIT 8
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // FOTOS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Obtener fotos de la galería
     * 
     * @param int $id_tienda
     * @return array
     */
    public function obtener_fotos($id_tienda) {
        // TODO: Implementar
        // 
        // Aplicar límite según plan:
        // - Básico: 8 fotos
        // - Premium: 30 fotos
        // 
        // SQL:
        // SELECT * FROM tienda_fotos
        // WHERE IdTienda = ?
        // ORDER BY Orden ASC
        // LIMIT ?
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // OPINIONES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Obtener opiniones
     * 
     * @param int $id_tienda
     * @param string $estado - 'aprobado', 'pendiente', 'rechazado'
     * @return array
     */
    public function obtener_opiniones($id_tienda, $estado = 'aprobado') {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT * FROM tienda_opiniones
        // WHERE IdTienda = ? AND Estado = ?
        // ORDER BY FechaCreacion DESC
    }
    
    /**
     * Votar opinión
     * 
     * @param int $id_opinion
     * @param string $tipo - 'util' o 'no_util'
     * @return bool
     */
    public function votar_opinion($id_opinion, $tipo) {
        // TODO: Implementar
        // 
        // SQL:
        // UPDATE tienda_opiniones
        // SET VotosUtil = VotosUtil + 1  (si tipo = 'util')
        // O   VotosNoUtil = VotosNoUtil + 1  (si tipo = 'no_util')
        // WHERE IdOpinion = ?
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // TÉRMINOS Y POLÍTICAS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Obtener términos y políticas
     * 
     * @param int $id_tienda
     * @return array
     */
    public function obtener_terminos($id_tienda) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT Tipo, Contenido FROM tienda_terminos
        // WHERE IdTienda = ?
        // 
        // Retornar formato:
        // [
        //   'envio' => 'texto...',
        //   'devolucion' => 'texto...',
        //   'privacidad' => 'texto...'
        // ]
    }
    
    /**
     * Obtener archivos de términos
     * 
     * @param int $id_tienda
     * @return array
     */
    public function obtener_archivos_terminos($id_tienda) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT Nombre, Url FROM tienda_archivos_terminos
        // WHERE IdTienda = ?
        // ORDER BY Orden ASC
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // ESTADÍSTICAS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Obtener estadísticas
     * 
     * @param int $id_tienda
     * @return array
     */
    public function obtener_estadisticas($id_tienda) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT * FROM tienda_estadisticas WHERE IdTienda = ?
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // SEGUIDORES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Seguir tienda
     * 
     * @param int $id_tienda
     * @param int $id_usuario
     * @return bool
     */
    public function seguir_tienda($id_tienda, $id_usuario) {
        // TODO: Implementar
        // 
        // SQL:
        // INSERT INTO tienda_seguidores (IdTienda, IdUsuario)
        // VALUES (?, ?)
        // ON DUPLICATE KEY UPDATE FechaSeguimiento = NOW()
    }
    
    /**
     * Dejar de seguir tienda
     * 
     * @param int $id_tienda
     * @param int $id_usuario
     * @return bool
     */
    public function dejar_seguir_tienda($id_tienda, $id_usuario) {
        // TODO: Implementar
        // 
        // SQL:
        // DELETE FROM tienda_seguidores
        // WHERE IdTienda = ? AND IdUsuario = ?
    }
    
    /**
     * Verificar si un usuario sigue la tienda
     * 
     * @param int $id_tienda
     * @param int $id_usuario
     * @return bool
     */
    public function esta_siguiendo($id_tienda, $id_usuario) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT COUNT(*) FROM tienda_seguidores
        // WHERE IdTienda = ? AND IdUsuario = ?
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // VALIDACIONES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Validar si la tienda tiene plan Premium
     * 
     * @param int $id_tienda
     * @return bool
     */
    public function validar_plan_premium($id_tienda) {
        // TODO: Implementar
        // 
        // SQL:
        // SELECT Plan FROM tiendas WHERE IdTienda = ?
        // 
        // Retornar: $plan === 'premium'
    }
    
    /**
     * Validar límite de banners
     * 
     * @param int $id_tienda
     * @param string $tipo - 'principal', 'sidebar', 'horizontal'
     * @return bool - true si puede agregar más, false si llegó al límite
     */
    public function validar_limite_banners($id_tienda, $tipo) {
        // TODO: Implementar
        // 
        // 1. Obtener plan de la tienda
        // 2. Contar banners actuales del tipo especificado
        // 3. Comparar con límite según plan
        // 
        // Ejemplo:
        // $tienda = $this->obtener_por_id($id_tienda);
        // $limite = self::LIMITES[$tienda['plan']]['banners_' . $tipo];
        // $actual = count($this->obtener_banners($id_tienda, $tipo));
        // return $actual < $limite;
    }
    
    /**
     * Validar límite de fotos
     * 
     * @param int $id_tienda
     * @return bool
     */
    public function validar_limite_fotos($id_tienda) {
        // TODO: Implementar
        // Similar a validar_limite_banners
    }
    
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // UTILIDADES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
    /**
     * Calcular estado actual (abierto/cerrado)
     * 
     * @param array $horarios
     * @return bool
     */
    public function calcular_estado_actual($horarios) {
        $diasSemana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        $diaActual = $diasSemana[date('w')];
        $horaActual = (int)date('H') * 60 + (int)date('i');
        
        $horarioHoy = $horarios[$diaActual] ?? null;
        
        if (!$horarioHoy || $horarioHoy['cerrado']) {
            return false;
        }
        
        list($horaApertura, $minApertura) = explode(':', $horarioHoy['apertura']);
        list($horaCierre, $minCierre) = explode(':', $horarioHoy['cierre']);
        
        $aperturaMins = (int)$horaApertura * 60 + (int)$minApertura;
        $cierreMins = (int)$horaCierre * 60 + (int)$minCierre;
        
        return $horaActual >= $aperturaMins && $horaActual < $cierreMins;
    }
}
