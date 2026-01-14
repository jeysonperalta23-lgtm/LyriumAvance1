-- ============================================
-- SCRIPT: Sistema de Tiendas para Lyrium
-- Versión: 1.0
-- Fecha: 2026-01-09
-- Descripción: Crea las tablas necesarias para el sistema de tiendas independientes
-- ============================================

-- ============================================
-- 1. TABLA PRINCIPAL: tiendas
-- ============================================
CREATE TABLE `tiendas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  
  -- AUTENTICACIÓN (La tienda tiene su propio login)
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL,
  
  -- INFORMACIÓN BÁSICA
  `nombre_tienda` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `favicon_url` varchar(255) DEFAULT NULL,
  
  -- PLAN Y SUSCRIPCIÓN
  `plan` enum('basico','premium') NOT NULL DEFAULT 'basico',
  `fecha_inicio_plan` datetime DEFAULT NULL,
  `fecha_fin_plan` datetime DEFAULT NULL,
  `estado_pago` enum('activo','vencido','cancelado') DEFAULT 'activo',
  
  -- CONTACTO
  `telefono` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT 'Perú',
  
  -- REDES SOCIALES (JSON)
  `redes_sociales` text DEFAULT NULL COMMENT 'JSON: {"instagram": "url", "facebook": "url", "tiktok": "url"}',
  
  -- CONFIGURACIÓN DE TIENDA
  `moneda` enum('PEN','USD') DEFAULT 'PEN',
  `permite_envios` tinyint(1) DEFAULT 1,
  `tiempo_preparacion_dias` int(11) DEFAULT 2,
  `horarios` text DEFAULT NULL COMMENT 'JSON: {"lunes": {"abre": "09:00", "cierra": "18:00"}}',
  
  -- PERSONALIZACIÓN (Solo Premium)
  `tema` varchar(50) DEFAULT NULL,
  `layout_modelo` int(11) DEFAULT 1,
  `colores_personalizados` text DEFAULT NULL COMMENT 'JSON: {"primario": "#hex", "secundario": "#hex"}',
  
  -- ESTADO Y VERIFICACIÓN
  `estado` enum('borrador','pendiente','activo','suspendido','rechazado') NOT NULL DEFAULT 'pendiente',
  `verificado` tinyint(1) NOT NULL DEFAULT 0,
  `motivo_rechazo` text DEFAULT NULL,
  
  -- COMISIONES Y PAGOS
  `comision_porcentaje` decimal(5,2) DEFAULT 10.00,
  `cuenta_bancaria_principal` int(11) DEFAULT NULL,
  
  -- ESTADÍSTICAS (Actualizadas automáticamente con triggers)
  `total_ventas` decimal(12,2) DEFAULT 0.00,
  `total_productos` int(11) DEFAULT 0,
  `total_pedidos` int(11) DEFAULT 0,
  `calificacion_promedio` decimal(3,2) DEFAULT 0.00,
  `total_resenas` int(11) DEFAULT 0,
  `visitas_totales` int(11) DEFAULT 0,
  
  -- LÍMITES POR PLAN
  `limite_productos` int(11) DEFAULT 50 COMMENT 'Básico: 50, Premium: ilimitado',
  `limite_imagenes_por_producto` int(11) DEFAULT 3 COMMENT 'Básico: 3, Premium: 10',
  `limite_categorias` int(11) DEFAULT 10 COMMENT 'Básico: 10, Premium: ilimitado',
  
  -- AUDITORÍA
  `usuario_admin_creador` int(11) DEFAULT NULL COMMENT 'ID del admin que creó la tienda',
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_admin_modificador` int(11) DEFAULT NULL COMMENT 'ID del admin que modificó',
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tienda_email` (`email`),
  UNIQUE KEY `uk_tienda_slug` (`slug`),
  KEY `idx_tienda_estado` (`estado`),
  KEY `idx_tienda_plan` (`plan`),
  KEY `idx_tienda_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla principal de tiendas del marketplace';

-- ============================================
-- 2. TABLA: tiendas_cuentas_bancarias
-- ============================================
CREATE TABLE `tiendas_cuentas_bancarias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tienda_id` int(11) NOT NULL,
  `banco` varchar(100) NOT NULL,
  `tipo_cuenta` enum('Ahorros','Corriente','CCI','Yape','Plin') NOT NULL DEFAULT 'Ahorros',
  `numero_cuenta` varchar(50) NOT NULL,
  `titular` varchar(150) NOT NULL,
  `documento_titular` varchar(20) DEFAULT NULL,
  `moneda` enum('PEN','USD') NOT NULL DEFAULT 'PEN',
  `es_principal` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_cuenta_tienda` (`tienda_id`),
  CONSTRAINT `fk_cuenta_tienda` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Cuentas bancarias de las tiendas para recibir pagos';

-- ============================================
-- 3. TABLA: tiendas_categorias
-- ============================================
CREATE TABLE `tiendas_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tienda_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `icono` varchar(50) DEFAULT NULL COMMENT 'Clase de icono Phosphor',
  `orden` int(11) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_categoria_tienda` (`tienda_id`),
  KEY `idx_categoria_orden` (`orden`),
  CONSTRAINT `fk_categoria_tienda` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Categorías de productos de cada tienda';

-- ============================================
-- 4. TABLA: tiendas_horarios
-- ============================================
CREATE TABLE `tiendas_horarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tienda_id` int(11) NOT NULL,
  `dia_semana` enum('lunes','martes','miercoles','jueves','viernes','sabado','domingo') NOT NULL,
  `hora_apertura` time DEFAULT NULL,
  `hora_cierre` time DEFAULT NULL,
  `cerrado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tienda_dia` (`tienda_id`, `dia_semana`),
  CONSTRAINT `fk_horario_tienda` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Horarios de atención de cada tienda';

-- ============================================
-- 5. TABLA: tiendas_galeria
-- ============================================
CREATE TABLE `tiendas_galeria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tienda_id` int(11) NOT NULL,
  `url_imagen` varchar(255) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_galeria_tienda` (`tienda_id`),
  KEY `idx_galeria_orden` (`orden`),
  CONSTRAINT `fk_galeria_tienda` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Galería de fotos de cada tienda';

-- ============================================
-- 6. TABLA: tiendas_sucursales
-- ============================================
CREATE TABLE `tiendas_sucursales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tienda_id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `horario_apertura` time DEFAULT NULL,
  `horario_cierre` time DEFAULT NULL,
  `google_maps_url` varchar(500) DEFAULT NULL,
  `es_principal` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sucursal_tienda` (`tienda_id`),
  CONSTRAINT `fk_sucursal_tienda` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Sucursales físicas de cada tienda';

-- ============================================
-- 7. TABLA: tiendas_tokens (Para autenticación)
-- ============================================
CREATE TABLE `tiendas_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tienda_id` int(11) NOT NULL,
  `token` varchar(500) NOT NULL,
  `tipo` enum('sesion','recuperacion','api') NOT NULL DEFAULT 'sesion',
  `fecha_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_token_tienda` (`tienda_id`),
  KEY `idx_token_activo` (`activo`),
  CONSTRAINT `fk_token_tienda` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tokens de autenticación de tiendas';

-- ============================================
-- MODIFICAR TABLAS EXISTENTES
-- ============================================

-- Modificar tabla productos para usar tienda_id
ALTER TABLE `productos` 
  ADD COLUMN `tienda_id` int(11) DEFAULT NULL AFTER `id`,
  ADD KEY `idx_producto_tienda` (`tienda_id`),
  ADD CONSTRAINT `fk_producto_tienda` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE;

-- Modificar tabla pedidos para usar tienda_id
ALTER TABLE `pedidos`
  ADD COLUMN `tienda_id` int(11) DEFAULT NULL AFTER `cliente_id`,
  ADD KEY `idx_pedido_tienda` (`tienda_id`),
  ADD CONSTRAINT `fk_pedido_tienda` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE SET NULL;

-- ============================================
-- DATOS DE EJEMPLO (OPCIONAL)
-- ============================================

-- Insertar tienda de ejemplo
INSERT INTO `tiendas` (
  `email`, 
  `password`, 
  `nombre_tienda`, 
  `slug`, 
  `descripcion`,
  `plan`,
  `estado`,
  `telefono`,
  `ciudad`,
  `fecha_inicio_plan`,
  `fecha_fin_plan`
) VALUES (
  'vidanatural@tienda.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
  'Vida Natural',
  'vida-natural',
  'Tienda especializada en productos naturales y suplementos para una vida saludable',
  'premium',
  'activo',
  '+51 912 345 678',
  'Piura',
  NOW(),
  DATE_ADD(NOW(), INTERVAL 1 YEAR)
);

-- Insertar horarios de ejemplo
INSERT INTO `tiendas_horarios` (`tienda_id`, `dia_semana`, `hora_apertura`, `hora_cierre`, `cerrado`) VALUES
(1, 'lunes', '09:00:00', '18:00:00', 0),
(1, 'martes', '09:00:00', '18:00:00', 0),
(1, 'miercoles', '09:00:00', '18:00:00', 0),
(1, 'jueves', '09:00:00', '18:00:00', 0),
(1, 'viernes', '09:00:00', '20:00:00', 0),
(1, 'sabado', '10:00:00', '14:00:00', 0),
(1, 'domingo', NULL, NULL, 1);

-- Actualizar productos existentes con tienda_id
UPDATE `productos` SET `tienda_id` = 1 WHERE `vendedor_usuario_id` = 22;

-- ============================================
-- TRIGGERS PARA ACTUALIZAR ESTADÍSTICAS
-- ============================================

DELIMITER $$

-- Trigger: Actualizar total_productos cuando se crea un producto
CREATE TRIGGER `trg_producto_insert_stats` 
AFTER INSERT ON `productos`
FOR EACH ROW
BEGIN
  UPDATE `tiendas` 
  SET `total_productos` = `total_productos` + 1
  WHERE `id` = NEW.tienda_id;
END$$

-- Trigger: Actualizar total_productos cuando se elimina un producto
CREATE TRIGGER `trg_producto_delete_stats` 
AFTER DELETE ON `productos`
FOR EACH ROW
BEGIN
  UPDATE `tiendas` 
  SET `total_productos` = GREATEST(`total_productos` - 1, 0)
  WHERE `id` = OLD.tienda_id;
END$$

-- Trigger: Actualizar estadísticas cuando se crea un pedido
CREATE TRIGGER `trg_pedido_insert_stats` 
AFTER INSERT ON `pedidos`
FOR EACH ROW
BEGIN
  IF NEW.tienda_id IS NOT NULL THEN
    UPDATE `tiendas` 
    SET 
      `total_pedidos` = `total_pedidos` + 1,
      `total_ventas` = `total_ventas` + NEW.total
    WHERE `id` = NEW.tienda_id;
  END IF;
END$$

DELIMITER ;

-- ============================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- ============================================

-- Índice para búsqueda de tiendas activas
CREATE INDEX `idx_tiendas_activas` ON `tiendas` (`estado`, `plan`);

-- Índice para búsqueda por ciudad
CREATE INDEX `idx_tiendas_ciudad` ON `tiendas` (`ciudad`);

-- ============================================
-- VISTAS ÚTILES
-- ============================================

-- Vista: Tiendas con estadísticas completas
CREATE OR REPLACE VIEW `v_tiendas_estadisticas` AS
SELECT 
  t.id,
  t.nombre_tienda,
  t.slug,
  t.email,
  t.plan,
  t.estado,
  t.total_productos,
  t.total_pedidos,
  t.total_ventas,
  t.calificacion_promedio,
  t.fecha_hora_creado,
  t.ultimo_acceso,
  COUNT(DISTINCT p.id) as productos_activos,
  COUNT(DISTINCT ped.id) as pedidos_mes_actual
FROM `tiendas` t
LEFT JOIN `productos` p ON t.id = p.tienda_id AND p.estado = 1
LEFT JOIN `pedidos` ped ON t.id = ped.tienda_id 
  AND MONTH(ped.fecha_hora_creado) = MONTH(CURRENT_DATE())
  AND YEAR(ped.fecha_hora_creado) = YEAR(CURRENT_DATE())
GROUP BY t.id;

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
