-- ═══════════════════════════════════════════════════════════════════════════
-- SCRIPT SQL: SISTEMA DE TIENDAS - LYRIUM BIOMARKETPLACE
-- ═══════════════════════════════════════════════════════════════════════════
-- Fecha: 2026-01-09
-- Descripción: Crea todas las tablas necesarias para el sistema de tiendas
--              con soporte para planes Básico y Premium
-- ═══════════════════════════════════════════════════════════════════════════

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA PRINCIPAL: tiendas
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
CREATE TABLE IF NOT EXISTS `tiendas` (
  `IdTienda` INT AUTO_INCREMENT PRIMARY KEY,
  `IdUsuario` INT NOT NULL COMMENT 'Dueño de la tienda',
  `Nombre` VARCHAR(100) NOT NULL,
  `Slug` VARCHAR(120) UNIQUE NOT NULL COMMENT 'URL amigable: /tienda.php?slug=nombre-tienda',
  `Descripcion` TEXT,
  `Logo` VARCHAR(255),
  `Cover` VARCHAR(255) COMMENT 'Imagen de portada',
  `Plan` ENUM('basico', 'premium') DEFAULT 'basico',
  `Categoria` VARCHAR(50),
  `Telefono` VARCHAR(20),
  `Correo` VARCHAR(100),
  `Direccion` VARCHAR(255),
  `Actividad` VARCHAR(255) COMMENT 'Actividad empresarial',
  `Estado` ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
  
  -- ★ NUEVOS CAMPOS PARA PERSONALIZACIÓN PREMIUM ★
  `LayoutModelo` TINYINT DEFAULT 1 COMMENT '1=Sidebar Derecha, 2=Sidebar Izquierda, 3=Full Width',
  `Tema` VARCHAR(20) DEFAULT '' COMMENT 'tema-ocean, tema-dark, tema-minimal, o vacío para default',
  
  `FechaCreacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `FechaActualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios`(`IdUsuario`) ON DELETE CASCADE,
  INDEX idx_slug (`Slug`),
  INDEX idx_plan (`Plan`),
  INDEX idx_estado (`Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_banners
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena los banners del carrusel principal y sidebar
-- Límites: Básico = 2 principales, Premium = 4 principales + 3 sidebar
CREATE TABLE IF NOT EXISTS `tienda_banners` (
  `IdBanner` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Tipo` ENUM('principal', 'sidebar', 'horizontal') DEFAULT 'principal',
  `Url` VARCHAR(255) NOT NULL COMMENT 'URL de la imagen',
  `Titulo` VARCHAR(100),
  `Orden` TINYINT DEFAULT 0,
  `Estado` ENUM('activo', 'inactivo') DEFAULT 'activo',
  `FechaCreacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  INDEX idx_tienda_tipo (`IdTienda`, `Tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_redes_sociales
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena los enlaces a redes sociales de la tienda
-- Límites: Básico = 6, Premium = 10
CREATE TABLE IF NOT EXISTS `tienda_redes_sociales` (
  `IdRed` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Plataforma` ENUM('instagram', 'facebook', 'whatsapp', 'tiktok', 'youtube', 'twitter', 'linkedin', 'pinterest', 'telegram', 'web') NOT NULL,
  `Url` VARCHAR(255) NOT NULL,
  `Orden` TINYINT DEFAULT 0,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  UNIQUE KEY unique_tienda_plataforma (`IdTienda`, `Plataforma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_horarios
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena los horarios de atención por día de la semana
CREATE TABLE IF NOT EXISTS `tienda_horarios` (
  `IdHorario` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `DiaSemana` ENUM('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo') NOT NULL,
  `HoraApertura` TIME,
  `HoraCierre` TIME,
  `Cerrado` BOOLEAN DEFAULT FALSE,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  UNIQUE KEY unique_tienda_dia (`IdTienda`, `DiaSemana`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_sucursales
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena las sucursales físicas de la tienda
-- Límite: 8 sucursales por tienda
CREATE TABLE IF NOT EXISTS `tienda_sucursales` (
  `IdSucursal` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Nombre` VARCHAR(100) NOT NULL,
  `Direccion` VARCHAR(255),
  `Ciudad` VARCHAR(100),
  `Telefono` VARCHAR(20),
  `HorarioApertura` TIME,
  `HorarioCierre` TIME,
  `GoogleMapsUrl` VARCHAR(500),
  `EsPrincipal` BOOLEAN DEFAULT FALSE,
  `Orden` TINYINT DEFAULT 0,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  INDEX idx_tienda (`IdTienda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_fotos
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena las fotos de la galería de la tienda
-- Límites: Básico = 8, Premium = 30
CREATE TABLE IF NOT EXISTS `tienda_fotos` (
  `IdFoto` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Url` VARCHAR(255) NOT NULL,
  `Titulo` VARCHAR(100),
  `Orden` TINYINT DEFAULT 0,
  `FechaSubida` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  INDEX idx_tienda (`IdTienda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_opiniones
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena las opiniones/reseñas de los clientes
CREATE TABLE IF NOT EXISTS `tienda_opiniones` (
  `IdOpinion` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `IdUsuario` INT COMMENT 'Usuario que opina (puede ser NULL si es anónimo)',
  `Autor` VARCHAR(100) NOT NULL,
  `Rating` TINYINT NOT NULL CHECK (Rating BETWEEN 1 AND 5),
  `Comentario` TEXT NOT NULL,
  `VotosUtil` INT DEFAULT 0,
  `VotosNoUtil` INT DEFAULT 0,
  `Estado` ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
  `FechaCreacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios`(`IdUsuario`) ON DELETE SET NULL,
  INDEX idx_tienda_estado (`IdTienda`, `Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_terminos
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena las políticas y términos de la tienda
CREATE TABLE IF NOT EXISTS `tienda_terminos` (
  `IdTermino` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Tipo` ENUM('envio', 'devolucion', 'privacidad') NOT NULL,
  `Contenido` TEXT NOT NULL,
  `FechaActualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  UNIQUE KEY unique_tienda_tipo (`IdTienda`, `Tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_archivos_terminos
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena archivos PDF descargables (términos, políticas)
CREATE TABLE IF NOT EXISTS `tienda_archivos_terminos` (
  `IdArchivo` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Nombre` VARCHAR(150) NOT NULL,
  `Url` VARCHAR(255) NOT NULL,
  `Orden` TINYINT DEFAULT 0,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_rubros
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena los rubros/categorías de la tienda
CREATE TABLE IF NOT EXISTS `tienda_rubros` (
  `IdRubro` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Nombre` VARCHAR(50) NOT NULL,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  INDEX idx_tienda (`IdTienda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_seguidores
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena los usuarios que siguen a la tienda
CREATE TABLE IF NOT EXISTS `tienda_seguidores` (
  `IdSeguidor` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `IdUsuario` INT NOT NULL,
  `FechaSeguimiento` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios`(`IdUsuario`) ON DELETE CASCADE,
  UNIQUE KEY unique_seguimiento (`IdTienda`, `IdUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- TABLA: tienda_estadisticas
-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
-- Almacena estadísticas agregadas de la tienda
CREATE TABLE IF NOT EXISTS `tienda_estadisticas` (
  `IdEstadistica` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL UNIQUE,
  `ValoracionPositiva` DECIMAL(4,1) DEFAULT 0.0 COMMENT 'Porcentaje 0-100',
  `TotalSeguidores` INT DEFAULT 0,
  `VendidosUltimos180Dias` INT DEFAULT 0,
  `CompradoresHabituales` INT DEFAULT 0,
  `FechaApertura` DATE,
  `UltimaActualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ═══════════════════════════════════════════════════════════════════════════
-- DATOS DE PRUEBA (OPCIONAL - COMENTAR SI NO SE NECESITA)
-- ═══════════════════════════════════════════════════════════════════════════

-- Insertar tienda de prueba (asumiendo que existe IdUsuario = 1)
-- INSERT INTO tiendas (IdUsuario, Nombre, Slug, Descripcion, Plan, LayoutModelo, Tema) VALUES
-- (1, 'Vida Natural', 'vida-natural', 'Tienda de productos naturales y orgánicos', 'premium', 1, 'tema-minimal');

-- Insertar horarios de prueba
-- INSERT INTO tienda_horarios (IdTienda, DiaSemana, HoraApertura, HoraCierre, Cerrado) VALUES
-- (1, 'lunes', '09:00', '18:00', 0),
-- (1, 'martes', '09:00', '18:00', 0),
-- (1, 'miercoles', '09:00', '18:00', 0),
-- (1, 'jueves', '09:00', '18:00', 0),
-- (1, 'viernes', '09:00', '20:00', 0),
-- (1, 'sabado', '10:00', '14:00', 0),
-- (1, 'domingo', NULL, NULL, 1);

-- ═══════════════════════════════════════════════════════════════════════════
-- FIN DEL SCRIPT
-- ═══════════════════════════════════════════════════════════════════════════
