-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-01-2026 a las 19:53:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lyrium`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_agregar_reaccion` (IN `p_tema_id` INT, IN `p_respuesta_id` INT, IN `p_usuario_id` INT, IN `p_anonimo_id` VARCHAR(50), IN `p_tipo_reaccion` ENUM('like','love','haha','wow','sad','angry'))   BEGIN
    DECLARE v_existing_id INT;
    DECLARE v_existing_type VARCHAR(20);
    
    -- Verificar si ya existe una reacción
    IF p_usuario_id IS NOT NULL THEN
        IF p_respuesta_id IS NOT NULL THEN
            SELECT id, tipo_reaccion INTO v_existing_id, v_existing_type
            FROM bioforo_reacciones_unificadas
            WHERE respuesta_id = p_respuesta_id AND usuario_id = p_usuario_id;
        ELSE
            SELECT id, tipo_reaccion INTO v_existing_id, v_existing_type
            FROM bioforo_reacciones_unificadas
            WHERE tema_id = p_tema_id AND usuario_id = p_usuario_id;
        END IF;
    ELSE
        IF p_respuesta_id IS NOT NULL THEN
            SELECT id, tipo_reaccion INTO v_existing_id, v_existing_type
            FROM bioforo_reacciones_unificadas
            WHERE respuesta_id = p_respuesta_id AND anonimo_id = p_anonimo_id;
        ELSE
            SELECT id, tipo_reaccion INTO v_existing_id, v_existing_type
            FROM bioforo_reacciones_unificadas
            WHERE tema_id = p_tema_id AND anonimo_id = p_anonimo_id;
        END IF;
    END IF;
    
    -- Si ya existe y es el mismo tipo, eliminar (toggle)
    IF v_existing_id IS NOT NULL THEN
        IF v_existing_type = p_tipo_reaccion THEN
            DELETE FROM bioforo_reacciones_unificadas WHERE id = v_existing_id;
        ELSE
            -- Cambiar tipo de reacción
            UPDATE bioforo_reacciones_unificadas 
            SET tipo_reaccion = p_tipo_reaccion, 
                actualizado_en = NOW()
            WHERE id = v_existing_id;
        END IF;
    ELSE
        -- Insertar nueva reacción
        INSERT INTO bioforo_reacciones_unificadas 
            (tema_id, respuesta_id, usuario_id, anonimo_id, tipo_reaccion)
        VALUES 
            (p_tema_id, p_respuesta_id, p_usuario_id, p_anonimo_id, p_tipo_reaccion);
    END IF;
    
    -- Devolver estadísticas actualizadas
    IF p_respuesta_id IS NOT NULL THEN
        SELECT 
            total_reacciones,
            likes_count,
            love_count,
            haha_count,
            wow_count,
            sad_count,
            angry_count
        FROM bioforo_respuestas
        WHERE id = p_respuesta_id;
    ELSE
        SELECT 
            total_reacciones,
            likes_count,
            love_count,
            haha_count,
            wow_count,
            sad_count,
            angry_count
        FROM bioforo_temas
        WHERE id = p_tema_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_reaccion_usuario` (IN `p_tema_id` INT, IN `p_respuesta_id` INT, IN `p_usuario_id` INT, IN `p_anonimo_id` VARCHAR(50))   BEGIN
    SELECT 
        tipo,
        creado_en
    FROM bioforo_reacciones
    WHERE (
        (p_respuesta_id IS NOT NULL AND respuesta_id = p_respuesta_id) OR
        (p_tema_id IS NOT NULL AND tema_id = p_tema_id)
    ) AND (
        (p_usuario_id IS NOT NULL AND usuario_id = p_usuario_id) OR
        (p_anonimo_id IS NOT NULL AND anonimo_id = p_anonimo_id)
    )
    LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_top_respuestas` (IN `p_tema_id` INT)   BEGIN
    SELECT 
        r.id,
        r.contenido,
        r.creado_en,
        IF(r.usuario_id IS NULL, r.anonimo_nombre, u.username) as autor,
        r.likes_count,
        r.total_reacciones,
        r.love_count,
        r.haha_count,
        r.wow_count,
        r.sad_count,
        r.angry_count,
        -- Puntaje simple: likes tienen más peso
        (r.likes_count * 2) + r.total_reacciones as ranking_score
    FROM bioforo_respuestas r
    LEFT JOIN usuarios u ON u.id = r.usuario_id
    WHERE r.tema_id = p_tema_id 
        AND r.estado = 'activo'
        AND r.respuesta_a_id IS NULL
    ORDER BY r.likes_count DESC, r.total_reacciones DESC, r.creado_en DESC
    LIMIT 3;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_migrar_reacciones_existentes` ()   BEGIN
    -- Migrar desde bioforo_reacciones_unificadas
    INSERT INTO bioforo_reacciones 
        (tema_id, respuesta_id, usuario_id, anonimo_id, tipo, creado_en)
    SELECT 
        tema_id, 
        respuesta_id, 
        CASE WHEN usuario_id = -1 THEN NULL ELSE usuario_id END,
        CASE WHEN anonimo_id = '' THEN NULL ELSE anonimo_id END,
        tipo_reaccion,
        creado_en
    FROM bioforo_reacciones_unificadas
    WHERE (tema_id IS NOT NULL OR respuesta_id IS NOT NULL)
    ON DUPLICATE KEY UPDATE 
        tipo = VALUES(tipo),
        actualizado_en = NOW();
    
    -- Recalcular contadores después de migrar
    CALL sp_reparar_todos_contadores();
    
    SELECT 'Migración completada' as resultado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reparar_todos_contadores` ()   BEGIN
    -- Resetear todos los contadores a 0 primero
    UPDATE bioforo_temas 
    SET 
        likes_count = 0,
        love_count = 0,
        haha_count = 0,
        wow_count = 0,
        sad_count = 0,
        angry_count = 0,
        total_reacciones = 0;
    
    UPDATE bioforo_respuestas 
    SET 
        likes_count = 0,
        love_count = 0,
        haha_count = 0,
        wow_count = 0,
        sad_count = 0,
        angry_count = 0,
        total_reacciones = 0;
    
    -- Recalcular desde bioforo_reacciones
    UPDATE bioforo_temas t
    JOIN (
        SELECT 
            tema_id,
            COUNT(CASE WHEN tipo = 'like' THEN 1 END) as calc_likes,
            COUNT(CASE WHEN tipo = 'love' THEN 1 END) as calc_love,
            COUNT(CASE WHEN tipo = 'haha' THEN 1 END) as calc_haha,
            COUNT(CASE WHEN tipo = 'wow' THEN 1 END) as calc_wow,
            COUNT(CASE WHEN tipo = 'sad' THEN 1 END) as calc_sad,
            COUNT(CASE WHEN tipo = 'angry' THEN 1 END) as calc_angry,
            COUNT(*) as calc_total
        FROM bioforo_reacciones
        WHERE tema_id IS NOT NULL
        GROUP BY tema_id
    ) calc ON calc.tema_id = t.id
    SET 
        t.likes_count = calc.calc_likes,
        t.love_count = calc.calc_love,
        t.haha_count = calc.calc_haha,
        t.wow_count = calc.calc_wow,
        t.sad_count = calc.calc_sad,
        t.angry_count = calc.calc_angry,
        t.total_reacciones = calc.calc_total,
        t.actualizado_en = NOW();
    
    UPDATE bioforo_respuestas r
    JOIN (
        SELECT 
            respuesta_id,
            COUNT(CASE WHEN tipo = 'like' THEN 1 END) as calc_likes,
            COUNT(CASE WHEN tipo = 'love' THEN 1 END) as calc_love,
            COUNT(CASE WHEN tipo = 'haha' THEN 1 END) as calc_haha,
            COUNT(CASE WHEN tipo = 'wow' THEN 1 END) as calc_wow,
            COUNT(CASE WHEN tipo = 'sad' THEN 1 END) as calc_sad,
            COUNT(CASE WHEN tipo = 'angry' THEN 1 END) as calc_angry,
            COUNT(*) as calc_total
        FROM bioforo_reacciones
        WHERE respuesta_id IS NOT NULL
        GROUP BY respuesta_id
    ) calc ON calc.respuesta_id = r.id
    SET 
        r.likes_count = calc.calc_likes,
        r.love_count = calc.calc_love,
        r.haha_count = calc.calc_haha,
        r.wow_count = calc.calc_wow,
        r.sad_count = calc.calc_sad,
        r.angry_count = calc.calc_angry,
        r.total_reacciones = calc.calc_total,
        r.actualizado_en = NOW();
    
    SELECT 'Contadores reparados' as resultado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_toggle_reaccion` (IN `p_tema_id` INT, IN `p_respuesta_id` INT, IN `p_usuario_id` INT, IN `p_anonimo_id` VARCHAR(50), IN `p_tipo` ENUM('like','love','haha','wow','sad','angry'))   BEGIN
    DECLARE v_existing_id INT;
    DECLARE v_existing_tipo VARCHAR(20);
    
    -- Buscar reacción existente
    IF p_usuario_id IS NOT NULL THEN
        SELECT id, tipo INTO v_existing_id, v_existing_tipo
        FROM bioforo_reacciones
        WHERE (
            (p_respuesta_id IS NOT NULL AND respuesta_id = p_respuesta_id) OR
            (p_tema_id IS NOT NULL AND tema_id = p_tema_id)
        ) AND usuario_id = p_usuario_id
        LIMIT 1;
    ELSE
        SELECT id, tipo INTO v_existing_id, v_existing_tipo
        FROM bioforo_reacciones
        WHERE (
            (p_respuesta_id IS NOT NULL AND respuesta_id = p_respuesta_id) OR
            (p_tema_id IS NOT NULL AND tema_id = p_tema_id)
        ) AND anonimo_id = p_anonimo_id
        LIMIT 1;
    END IF;
    
    -- Lógica de toggle
    IF v_existing_id IS NOT NULL THEN
        IF v_existing_tipo = p_tipo THEN
            -- Mismo tipo: eliminar (toggle off)
            DELETE FROM bioforo_reacciones WHERE id = v_existing_id;
            SELECT 'deleted' as action;
        ELSE
            -- Tipo diferente: actualizar
            UPDATE bioforo_reacciones 
            SET tipo = p_tipo, actualizado_en = NOW()
            WHERE id = v_existing_id;
            SELECT 'updated' as action;
        END IF;
    ELSE
        -- Nueva reacción
        INSERT INTO bioforo_reacciones 
            (tema_id, respuesta_id, usuario_id, anonimo_id, tipo)
        VALUES 
            (p_tema_id, p_respuesta_id, p_usuario_id, p_anonimo_id, p_tipo);
        SELECT 'inserted' as action;
    END IF;
    
    -- Devolver estadísticas actualizadas
    IF p_respuesta_id IS NOT NULL THEN
        SELECT 
            id,
            total_reacciones,
            likes_count,
            love_count,
            haha_count,
            wow_count,
            sad_count,
            angry_count
        FROM bioforo_respuestas
        WHERE id = p_respuesta_id;
    ELSE
        SELECT 
            id,
            total_reacciones,
            likes_count,
            love_count,
            haha_count,
            wow_count,
            sad_count,
            angry_count
        FROM bioforo_temas
        WHERE id = p_tema_id;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_categorias`
--

CREATE TABLE `bioforo_categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `estado` tinyint(1) DEFAULT 1,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bioforo_categorias`
--

INSERT INTO `bioforo_categorias` (`id`, `nombre`, `slug`, `descripcion`, `orden`, `estado`, `creado_en`) VALUES
(1, 'Salud Alimentaria', 'salud-alimentaria', 'Todo sobre alimentación saludable y nutrición', 1, 1, '2026-01-12 15:42:50'),
(2, 'Salud Emocional', 'salud-emocional', 'Bienestar emocional y mental', 2, 1, '2026-01-12 15:42:50'),
(3, 'Salud Ambiental', 'salud-ambiental', 'Medio ambiente y sostenibilidad', 3, 1, '2026-01-12 15:42:50'),
(4, 'Biotecnología', 'biotecnologia', 'Innovación biotecnológica', 4, 1, '2026-01-12 15:42:50'),
(5, 'Bioemprendimiento', 'bioemprendimiento', 'Negocios y emprendimiento en biocomercio', 5, 1, '2026-01-12 15:42:50'),
(6, 'General', 'general', 'Temas generales de la comunidad', 6, 1, '2026-01-12 15:42:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_jobs_log`
--

CREATE TABLE `bioforo_jobs_log` (
  `id` int(11) NOT NULL,
  `job_name` varchar(50) DEFAULT NULL,
  `registros_corregidos` int(11) DEFAULT NULL,
  `ejecutado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_reacciones`
--

CREATE TABLE `bioforo_reacciones` (
  `id` int(11) NOT NULL,
  `tema_id` int(11) DEFAULT NULL,
  `respuesta_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `anonimo_id` varchar(50) DEFAULT NULL,
  `tipo` enum('like','love','haha','wow','sad','angry') NOT NULL DEFAULT 'like',
  `creado_en` datetime DEFAULT current_timestamp(),
  `actualizado_en` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bioforo_reacciones`
--

INSERT INTO `bioforo_reacciones` (`id`, `tema_id`, `respuesta_id`, `usuario_id`, `anonimo_id`, `tipo`, `creado_en`, `actualizado_en`) VALUES
(85, NULL, 83, NULL, 'anon_ody743rjo', 'angry', '2026-01-15 00:07:29', '2026-01-15 00:07:32'),
(89, NULL, 88, NULL, 'anon_ody743rjo', 'angry', '2026-01-15 00:19:35', '2026-01-15 00:19:43'),
(90, NULL, 84, NULL, 'anon_ody743rjo', 'like', '2026-01-15 00:20:03', '2026-01-15 00:20:03'),
(100, 50, NULL, NULL, 'anon_ody743rjo', 'wow', '2026-01-15 00:52:59', '2026-01-15 00:52:59'),
(101, NULL, 97, NULL, 'anon_ody743rjo', 'like', '2026-01-15 01:00:10', '2026-01-15 01:00:10'),
(104, NULL, 102, NULL, 'anon_ody743rjo', 'like', '2026-01-15 01:46:07', '2026-01-15 01:46:07'),
(105, 49, NULL, NULL, 'anon_ody743rjo', 'wow', '2026-01-15 01:46:18', '2026-01-15 15:32:49'),
(106, NULL, 95, NULL, 'anon_ody743rjo', 'angry', '2026-01-15 15:12:12', '2026-01-15 15:12:13'),
(107, NULL, 96, NULL, 'anon_ody743rjo', 'angry', '2026-01-15 15:32:41', '2026-01-15 15:32:42'),
(108, 53, NULL, NULL, 'anon_ody743rjo', 'wow', '2026-01-15 15:50:31', '2026-01-15 15:50:31'),
(109, NULL, 109, NULL, 'anon_ody743rjo', 'like', '2026-01-15 16:36:20', '2026-01-15 16:36:21'),
(110, 93, NULL, NULL, 'anon_ody743rjo', 'haha', '2026-01-15 16:45:01', '2026-01-15 17:32:26'),
(111, NULL, 110, NULL, 'anon_ody743rjo', 'like', '2026-01-15 16:45:10', '2026-01-15 18:24:25'),
(112, NULL, 111, NULL, 'anon_81zvaxx78', 'like', '2026-01-15 16:52:28', '2026-01-15 16:52:28'),
(113, NULL, 112, NULL, 'anon_81zvaxx78', 'angry', '2026-01-15 16:52:58', '2026-01-15 16:52:58'),
(114, NULL, 98, NULL, 'anon_ody743rjo', 'like', '2026-01-15 17:05:12', '2026-01-15 17:05:12'),
(116, 59, NULL, NULL, 'anon_ody743rjo', 'sad', '2026-01-15 17:36:09', '2026-01-15 17:36:11'),
(117, NULL, 108, NULL, 'anon_ody743rjo', 'angry', '2026-01-15 17:36:28', '2026-01-15 17:36:28'),
(118, 93, NULL, NULL, 'anon_81zvaxx78', 'haha', '2026-01-15 17:42:44', '2026-01-15 17:42:44'),
(119, NULL, 114, NULL, 'anon_81zvaxx78', 'like', '2026-01-15 18:26:55', '2026-01-15 18:26:55'),
(120, 57, NULL, NULL, 'anon_ody743rjo', 'wow', '2026-01-15 18:27:56', '2026-01-15 18:27:56'),
(121, NULL, 115, NULL, 'anon_ody743rjo', 'like', '2026-01-15 18:48:43', '2026-01-15 18:48:43'),
(122, 67, NULL, NULL, 'anon_ody743rjo', 'angry', '2026-01-15 18:49:02', '2026-01-15 18:49:02'),
(123, NULL, 117, NULL, 'anon_ody743rjo', 'like', '2026-01-15 18:50:21', '2026-01-15 18:50:21'),
(124, NULL, 119, NULL, 'anon_ody743rjo', 'like', '2026-01-15 18:50:52', '2026-01-15 18:50:54'),
(125, 61, NULL, NULL, 'anon_ody743rjo', 'wow', '2026-01-15 18:50:57', '2026-01-15 18:50:57'),
(126, NULL, 82, NULL, 'anon_ody743rjo', 'like', '2026-01-15 18:51:09', '2026-01-15 18:51:09'),
(131, 95, NULL, NULL, 'anon_ody743rjo', 'wow', '2026-01-15 18:53:26', '2026-01-15 18:53:26'),
(132, 88, NULL, NULL, 'anon_ody743rjo', 'wow', '2026-01-15 18:55:14', '2026-01-15 18:55:14'),
(134, NULL, 121, NULL, 'anon_81zvaxx78', 'like', '2026-01-15 19:10:22', '2026-01-15 19:10:22'),
(135, 94, NULL, NULL, 'anon_81zvaxx78', 'haha', '2026-01-15 19:10:32', '2026-01-15 19:10:32'),
(136, 96, NULL, NULL, 'anon_81zvaxx78', 'sad', '2026-01-15 19:17:18', '2026-01-15 19:17:18'),
(139, NULL, 114, NULL, 'anon_ody743rjo', 'angry', '2026-01-16 03:48:56', '2026-01-16 03:48:58'),
(140, NULL, 120, NULL, 'anon_ody743rjo', 'like', '2026-01-16 03:52:42', '2026-01-16 03:52:42'),
(141, 98, NULL, NULL, 'anon_ody743rjo', 'like', '2026-01-16 03:57:08', '2026-01-16 03:57:08'),
(145, 96, NULL, NULL, 'anon_ody743rjo', 'haha', '2026-01-16 12:28:02', '2026-01-16 12:28:02');

--
-- Disparadores `bioforo_reacciones`
--
DELIMITER $$
CREATE TRIGGER `trg_reaccion_delete_simple` AFTER DELETE ON `bioforo_reacciones` FOR EACH ROW BEGIN
    IF OLD.tema_id IS NOT NULL THEN
        -- TEMA: Decrementar contador específico
        UPDATE bioforo_temas 
        SET 
            total_reacciones = GREATEST(0, total_reacciones - 1),
            likes_count = GREATEST(0, likes_count - (OLD.tipo = 'like')),
            love_count = GREATEST(0, love_count - (OLD.tipo = 'love')),
            haha_count = GREATEST(0, haha_count - (OLD.tipo = 'haha')),
            wow_count = GREATEST(0, wow_count - (OLD.tipo = 'wow')),
            sad_count = GREATEST(0, sad_count - (OLD.tipo = 'sad')),
            angry_count = GREATEST(0, angry_count - (OLD.tipo = 'angry')),
            actualizado_en = NOW()
        WHERE id = OLD.tema_id;
    ELSE
        -- RESPUESTA: Decrementar contador específico
        UPDATE bioforo_respuestas 
        SET 
            total_reacciones = GREATEST(0, total_reacciones - 1),
            likes_count = GREATEST(0, likes_count - (OLD.tipo = 'like')),
            love_count = GREATEST(0, love_count - (OLD.tipo = 'love')),
            haha_count = GREATEST(0, haha_count - (OLD.tipo = 'haha')),
            wow_count = GREATEST(0, wow_count - (OLD.tipo = 'wow')),
            sad_count = GREATEST(0, sad_count - (OLD.tipo = 'sad')),
            angry_count = GREATEST(0, angry_count - (OLD.tipo = 'angry')),
            actualizado_en = NOW()
        WHERE id = OLD.respuesta_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_reaccion_insert_simple` AFTER INSERT ON `bioforo_reacciones` FOR EACH ROW BEGIN
    IF NEW.tema_id IS NOT NULL THEN
        -- TEMA: Incrementar contador específico (1 operación)
        UPDATE bioforo_temas 
        SET 
            total_reacciones = total_reacciones + 1,
            likes_count = likes_count + (NEW.tipo = 'like'),
            love_count = love_count + (NEW.tipo = 'love'),
            haha_count = haha_count + (NEW.tipo = 'haha'),
            wow_count = wow_count + (NEW.tipo = 'wow'),
            sad_count = sad_count + (NEW.tipo = 'sad'),
            angry_count = angry_count + (NEW.tipo = 'angry'),
            actualizado_en = NOW()
        WHERE id = NEW.tema_id;
    ELSE
        -- RESPUESTA: Incrementar contador específico (1 operación)
        UPDATE bioforo_respuestas 
        SET 
            total_reacciones = total_reacciones + 1,
            likes_count = likes_count + (NEW.tipo = 'like'),
            love_count = love_count + (NEW.tipo = 'love'),
            haha_count = haha_count + (NEW.tipo = 'haha'),
            wow_count = wow_count + (NEW.tipo = 'wow'),
            sad_count = sad_count + (NEW.tipo = 'sad'),
            angry_count = angry_count + (NEW.tipo = 'angry'),
            actualizado_en = NOW()
        WHERE id = NEW.respuesta_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_reaccion_update_simple` AFTER UPDATE ON `bioforo_reacciones` FOR EACH ROW BEGIN
    -- Solo si cambió el tipo de reacción
    IF OLD.tipo != NEW.tipo THEN
        IF NEW.tema_id IS NOT NULL THEN
            -- TEMA: Restar viejo, sumar nuevo
            UPDATE bioforo_temas 
            SET 
                likes_count = likes_count - (OLD.tipo = 'like') + (NEW.tipo = 'like'),
                love_count = love_count - (OLD.tipo = 'love') + (NEW.tipo = 'love'),
                haha_count = haha_count - (OLD.tipo = 'haha') + (NEW.tipo = 'haha'),
                wow_count = wow_count - (OLD.tipo = 'wow') + (NEW.tipo = 'wow'),
                sad_count = sad_count - (OLD.tipo = 'sad') + (NEW.tipo = 'sad'),
                angry_count = angry_count - (OLD.tipo = 'angry') + (NEW.tipo = 'angry'),
                actualizado_en = NOW()
            WHERE id = NEW.tema_id;
        ELSE
            -- RESPUESTA: Restar viejo, sumar nuevo
            UPDATE bioforo_respuestas 
            SET 
                likes_count = likes_count - (OLD.tipo = 'like') + (NEW.tipo = 'like'),
                love_count = love_count - (OLD.tipo = 'love') + (NEW.tipo = 'love'),
                haha_count = haha_count - (OLD.tipo = 'haha') + (NEW.tipo = 'haha'),
                wow_count = wow_count - (OLD.tipo = 'wow') + (NEW.tipo = 'wow'),
                sad_count = sad_count - (OLD.tipo = 'sad') + (NEW.tipo = 'sad'),
                angry_count = angry_count - (OLD.tipo = 'angry') + (NEW.tipo = 'angry'),
                actualizado_en = NOW()
            WHERE id = NEW.respuesta_id;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_reacciones_respuestas`
--

CREATE TABLE `bioforo_reacciones_respuestas` (
  `id` int(11) NOT NULL,
  `respuesta_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `anonimo_id` varchar(100) DEFAULT NULL,
  `tipo_reaccion` enum('like','dislike') DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_reacciones_temas`
--

CREATE TABLE `bioforo_reacciones_temas` (
  `id` int(11) NOT NULL,
  `tema_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `anonimo_id` varchar(100) DEFAULT NULL,
  `tipo_reaccion` enum('like','love','haha','wow','sad','angry') DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_reacciones_unificadas`
--

CREATE TABLE `bioforo_reacciones_unificadas` (
  `id` int(11) NOT NULL,
  `tema_id` int(11) DEFAULT NULL,
  `respuesta_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `anonimo_id` varchar(50) DEFAULT NULL,
  `tipo_reaccion` enum('like','love','haha','wow','sad','angry') DEFAULT 'like',
  `creado_en` datetime DEFAULT current_timestamp(),
  `actualizado_en` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `bioforo_reacciones_unificadas`
--
DELIMITER $$
CREATE TRIGGER `after_reaccion_unificada_delete` AFTER DELETE ON `bioforo_reacciones_unificadas` FOR EACH ROW BEGIN
    -- Si es reacción a un TEMA
    IF OLD.tema_id IS NOT NULL AND OLD.respuesta_id IS NULL THEN
        -- Decrementar contador específico según tipo de reacción
        CASE OLD.tipo_reaccion
            WHEN 'like' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    likes_count = GREATEST(0, likes_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.tema_id;
            WHEN 'love' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    love_count = GREATEST(0, love_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.tema_id;
            WHEN 'haha' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    haha_count = GREATEST(0, haha_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.tema_id;
            WHEN 'wow' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    wow_count = GREATEST(0, wow_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.tema_id;
            WHEN 'sad' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    sad_count = GREATEST(0, sad_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.tema_id;
            WHEN 'angry' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    angry_count = GREATEST(0, angry_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.tema_id;
        END CASE;
    
    -- Si es reacción a una RESPUESTA
    ELSEIF OLD.respuesta_id IS NOT NULL THEN
        -- Decrementar contador específico según tipo de reacción
        CASE OLD.tipo_reaccion
            WHEN 'like' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    likes_count = GREATEST(0, likes_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.respuesta_id;
            WHEN 'love' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    love_count = GREATEST(0, love_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.respuesta_id;
            WHEN 'haha' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    haha_count = GREATEST(0, haha_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.respuesta_id;
            WHEN 'wow' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    wow_count = GREATEST(0, wow_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.respuesta_id;
            WHEN 'sad' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    sad_count = GREATEST(0, sad_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.respuesta_id;
            WHEN 'angry' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = GREATEST(0, total_reacciones - 1),
                    angry_count = GREATEST(0, angry_count - 1),
                    actualizado_en = NOW()
                WHERE id = OLD.respuesta_id;
        END CASE;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_reaccion_unificada_insert` AFTER INSERT ON `bioforo_reacciones_unificadas` FOR EACH ROW BEGIN
    -- Si es reacción a un TEMA
    IF NEW.tema_id IS NOT NULL AND NEW.respuesta_id IS NULL THEN
        -- Incrementar contador específico según tipo de reacción
        CASE NEW.tipo_reaccion
            WHEN 'like' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = total_reacciones + 1,
                    likes_count = likes_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.tema_id;
            WHEN 'love' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = total_reacciones + 1,
                    love_count = love_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.tema_id;
            WHEN 'haha' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = total_reacciones + 1,
                    haha_count = haha_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.tema_id;
            WHEN 'wow' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = total_reacciones + 1,
                    wow_count = wow_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.tema_id;
            WHEN 'sad' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = total_reacciones + 1,
                    sad_count = sad_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.tema_id;
            WHEN 'angry' THEN
                UPDATE bioforo_temas 
                SET total_reacciones = total_reacciones + 1,
                    angry_count = angry_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.tema_id;
        END CASE;
    
    -- Si es reacción a una RESPUESTA
    ELSEIF NEW.respuesta_id IS NOT NULL THEN
        -- Incrementar contador específico según tipo de reacción
        CASE NEW.tipo_reaccion
            WHEN 'like' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = total_reacciones + 1,
                    likes_count = likes_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.respuesta_id;
            WHEN 'love' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = total_reacciones + 1,
                    love_count = love_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.respuesta_id;
            WHEN 'haha' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = total_reacciones + 1,
                    haha_count = haha_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.respuesta_id;
            WHEN 'wow' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = total_reacciones + 1,
                    wow_count = wow_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.respuesta_id;
            WHEN 'sad' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = total_reacciones + 1,
                    sad_count = sad_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.respuesta_id;
            WHEN 'angry' THEN
                UPDATE bioforo_respuestas 
                SET total_reacciones = total_reacciones + 1,
                    angry_count = angry_count + 1,
                    actualizado_en = NOW()
                WHERE id = NEW.respuesta_id;
        END CASE;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_reaccion_unificada_update` AFTER UPDATE ON `bioforo_reacciones_unificadas` FOR EACH ROW BEGIN
    -- Solo procesar si cambió el tipo de reacción
    IF OLD.tipo_reaccion != NEW.tipo_reaccion THEN
        -- Si es reacción a un TEMA
        IF NEW.tema_id IS NOT NULL AND NEW.respuesta_id IS NULL THEN
            -- Decrementar contador del tipo viejo
            CASE OLD.tipo_reaccion
                WHEN 'like' THEN
                    UPDATE bioforo_temas 
                    SET likes_count = GREATEST(0, likes_count - 1)
                    WHERE id = NEW.tema_id;
                WHEN 'love' THEN
                    UPDATE bioforo_temas 
                    SET love_count = GREATEST(0, love_count - 1)
                    WHERE id = NEW.tema_id;
                WHEN 'haha' THEN
                    UPDATE bioforo_temas 
                    SET haha_count = GREATEST(0, haha_count - 1)
                    WHERE id = NEW.tema_id;
                WHEN 'wow' THEN
                    UPDATE bioforo_temas 
                    SET wow_count = GREATEST(0, wow_count - 1)
                    WHERE id = NEW.tema_id;
                WHEN 'sad' THEN
                    UPDATE bioforo_temas 
                    SET sad_count = GREATEST(0, sad_count - 1)
                    WHERE id = NEW.tema_id;
                WHEN 'angry' THEN
                    UPDATE bioforo_temas 
                    SET angry_count = GREATEST(0, angry_count - 1)
                    WHERE id = NEW.tema_id;
            END CASE;
            
            -- Incrementar contador del tipo nuevo
            CASE NEW.tipo_reaccion
                WHEN 'like' THEN
                    UPDATE bioforo_temas 
                    SET likes_count = likes_count + 1
                    WHERE id = NEW.tema_id;
                WHEN 'love' THEN
                    UPDATE bioforo_temas 
                    SET love_count = love_count + 1
                    WHERE id = NEW.tema_id;
                WHEN 'haha' THEN
                    UPDATE bioforo_temas 
                    SET haha_count = haha_count + 1
                    WHERE id = NEW.tema_id;
                WHEN 'wow' THEN
                    UPDATE bioforo_temas 
                    SET wow_count = wow_count + 1
                    WHERE id = NEW.tema_id;
                WHEN 'sad' THEN
                    UPDATE bioforo_temas 
                    SET sad_count = sad_count + 1
                    WHERE id = NEW.tema_id;
                WHEN 'angry' THEN
                    UPDATE bioforo_temas 
                    SET angry_count = angry_count + 1
                    WHERE id = NEW.tema_id;
            END CASE;
            
            UPDATE bioforo_temas SET actualizado_en = NOW() WHERE id = NEW.tema_id;
        
        -- Si es reacción a una RESPUESTA
        ELSEIF NEW.respuesta_id IS NOT NULL THEN
            -- Decrementar contador del tipo viejo
            CASE OLD.tipo_reaccion
                WHEN 'like' THEN
                    UPDATE bioforo_respuestas 
                    SET likes_count = GREATEST(0, likes_count - 1)
                    WHERE id = NEW.respuesta_id;
                WHEN 'love' THEN
                    UPDATE bioforo_respuestas 
                    SET love_count = GREATEST(0, love_count - 1)
                    WHERE id = NEW.respuesta_id;
                WHEN 'haha' THEN
                    UPDATE bioforo_respuestas 
                    SET haha_count = GREATEST(0, haha_count - 1)
                    WHERE id = NEW.respuesta_id;
                WHEN 'wow' THEN
                    UPDATE bioforo_respuestas 
                    SET wow_count = GREATEST(0, wow_count - 1)
                    WHERE id = NEW.respuesta_id;
                WHEN 'sad' THEN
                    UPDATE bioforo_respuestas 
                    SET sad_count = GREATEST(0, sad_count - 1)
                    WHERE id = NEW.respuesta_id;
                WHEN 'angry' THEN
                    UPDATE bioforo_respuestas 
                    SET angry_count = GREATEST(0, angry_count - 1)
                    WHERE id = NEW.respuesta_id;
            END CASE;
            
            -- Incrementar contador del tipo nuevo
            CASE NEW.tipo_reaccion
                WHEN 'like' THEN
                    UPDATE bioforo_respuestas 
                    SET likes_count = likes_count + 1
                    WHERE id = NEW.respuesta_id;
                WHEN 'love' THEN
                    UPDATE bioforo_respuestas 
                    SET love_count = love_count + 1
                    WHERE id = NEW.respuesta_id;
                WHEN 'haha' THEN
                    UPDATE bioforo_respuestas 
                    SET haha_count = haha_count + 1
                    WHERE id = NEW.respuesta_id;
                WHEN 'wow' THEN
                    UPDATE bioforo_respuestas 
                    SET wow_count = wow_count + 1
                    WHERE id = NEW.respuesta_id;
                WHEN 'sad' THEN
                    UPDATE bioforo_respuestas 
                    SET sad_count = sad_count + 1
                    WHERE id = NEW.respuesta_id;
                WHEN 'angry' THEN
                    UPDATE bioforo_respuestas 
                    SET angry_count = angry_count + 1
                    WHERE id = NEW.respuesta_id;
            END CASE;
            
            UPDATE bioforo_respuestas SET actualizado_en = NOW() WHERE id = NEW.respuesta_id;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_respuestas`
--

CREATE TABLE `bioforo_respuestas` (
  `id` int(11) NOT NULL,
  `tema_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `anonimo_nombre` varchar(50) DEFAULT NULL,
  `rol` enum('admin','especialista','usuario','anonimo') DEFAULT 'anonimo',
  `contenido` text NOT NULL,
  `total_reacciones` int(11) NOT NULL DEFAULT 0,
  `respuesta_a_id` int(11) DEFAULT NULL,
  `estado` enum('activo','oculto','eliminado') DEFAULT 'activo',
  `creado_en` datetime DEFAULT current_timestamp(),
  `actualizado_en` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `love_count` int(11) NOT NULL DEFAULT 0,
  `haha_count` int(11) NOT NULL DEFAULT 0,
  `wow_count` int(11) NOT NULL DEFAULT 0,
  `sad_count` int(11) NOT NULL DEFAULT 0,
  `angry_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bioforo_respuestas`
--

INSERT INTO `bioforo_respuestas` (`id`, `tema_id`, `usuario_id`, `anonimo_nombre`, `rol`, `contenido`, `total_reacciones`, `respuesta_a_id`, `estado`, `creado_en`, `actualizado_en`, `likes_count`, `love_count`, `haha_count`, `wow_count`, `sad_count`, `angry_count`) VALUES
(80, 49, NULL, 'Anónimo-3359', 'anonimo', 'nnnnnn', 0, NULL, 'activo', '2026-01-15 00:03:48', '2026-01-15 01:37:07', 0, 0, 0, 0, 0, 0),
(81, 49, NULL, 'Anónimo-8308', 'anonimo', 'kkkkkkkkkkkkk', 0, 80, 'activo', '2026-01-15 00:04:05', '2026-01-15 00:20:12', 0, 0, 0, 0, 0, 0),
(82, 50, NULL, 'Anónimo-9246', 'anonimo', 'kkkk', 1, NULL, 'activo', '2026-01-15 00:04:49', '2026-01-15 18:51:09', 1, 0, 0, 0, 0, 0),
(83, 50, NULL, 'Anónimo-8243', 'anonimo', 'jjjjjjjjjjjjjjjjjjj', 1, NULL, 'activo', '2026-01-15 00:05:13', '2026-01-15 00:07:32', 0, 0, 0, 0, 0, 1),
(84, 50, NULL, 'Anónimo-2028', 'anonimo', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkk', 1, NULL, 'activo', '2026-01-15 00:05:31', '2026-01-15 00:20:03', 1, 0, 0, 0, 0, 0),
(85, 49, NULL, 'Anónimo-5298', 'anonimo', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk', 0, NULL, 'activo', '2026-01-15 00:05:39', '2026-01-15 01:37:03', 0, 0, 0, 0, 0, 0),
(86, 49, NULL, 'Anónimo-5429', 'anonimo', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk', 0, NULL, 'activo', '2026-01-15 00:05:51', '2026-01-15 01:37:06', 0, 0, 0, 0, 0, 0),
(87, 50, NULL, 'Anónimo-4869', 'anonimo', 'kkkkkkkkkkkkkkkkkkkkk', 0, NULL, 'activo', '2026-01-15 00:06:07', '2026-01-15 00:06:07', 0, 0, 0, 0, 0, 0),
(88, 50, NULL, 'Anónimo-6346', 'anonimo', 'ooooooooooooooooooooooooooooooooooooooooooooooooo', 1, NULL, 'activo', '2026-01-15 00:13:25', '2026-01-15 00:19:43', 0, 0, 0, 0, 0, 1),
(89, 51, NULL, 'Anónimo-2005', 'anonimo', 'gggggggggggggg', 0, NULL, 'activo', '2026-01-15 00:20:42', '2026-01-15 00:20:42', 0, 0, 0, 0, 0, 0),
(90, 49, NULL, 'Anónimo-8667', 'anonimo', 'lopi', 0, NULL, 'activo', '2026-01-15 00:48:29', '2026-01-15 00:48:29', 0, 0, 0, 0, 0, 0),
(91, 49, NULL, 'Anónimo-3932', 'anonimo', 'ñop', 0, NULL, 'activo', '2026-01-15 00:49:10', '2026-01-15 00:49:10', 0, 0, 0, 0, 0, 0),
(92, 49, NULL, 'Anónimo-3854', 'anonimo', 'ñp', 0, 85, 'activo', '2026-01-15 00:49:26', '2026-01-15 00:49:26', 0, 0, 0, 0, 0, 0),
(93, 49, NULL, 'Anónimo-9247', 'anonimo', 'ppppppppppppppppppppppppppppppppppppppp', 0, NULL, 'activo', '2026-01-15 00:50:12', '2026-01-15 00:50:12', 0, 0, 0, 0, 0, 0),
(94, 51, NULL, 'Anónimo-2906', 'anonimo', 'pñpñpñ', 0, NULL, 'activo', '2026-01-15 00:52:14', '2026-01-15 00:52:14', 0, 0, 0, 0, 0, 0),
(95, 51, NULL, 'Anónimo-8609', 'anonimo', 'lopuitu', 1, NULL, 'activo', '2026-01-15 00:52:35', '2026-01-15 15:12:13', 0, 0, 0, 0, 0, 1),
(96, 51, NULL, 'Anónimo-7216', 'anonimo', 'pñpñp', 1, 95, 'activo', '2026-01-15 00:52:45', '2026-01-15 15:32:42', 0, 0, 0, 0, 0, 1),
(97, 52, NULL, 'Anónimo-2707', 'anonimo', 'hu', 1, NULL, 'activo', '2026-01-15 00:59:53', '2026-01-15 01:00:10', 1, 0, 0, 0, 0, 0),
(98, 52, NULL, 'Anónimo-2427', 'anonimo', 'jii', 1, 97, 'activo', '2026-01-15 01:00:00', '2026-01-15 17:05:12', 1, 0, 0, 0, 0, 0),
(99, 52, NULL, 'Anónimo-4074', 'anonimo', 'lll', 0, NULL, 'activo', '2026-01-15 01:35:41', '2026-01-15 01:35:41', 0, 0, 0, 0, 0, 0),
(100, 52, NULL, 'Anónimo-1860', 'anonimo', 'kkkkkkkkkkkkkkkkkkk', 0, NULL, 'activo', '2026-01-15 01:35:56', '2026-01-15 01:35:56', 0, 0, 0, 0, 0, 0),
(101, 52, NULL, 'Anónimo-6881', 'anonimo', 'llllllllllllll', 0, 97, 'activo', '2026-01-15 01:36:07', '2026-01-15 01:36:07', 0, 0, 0, 0, 0, 0),
(102, 49, NULL, 'Anónimo-6410', 'anonimo', 'ddddddddddddd', 1, 93, 'activo', '2026-01-15 01:46:02', '2026-01-15 01:46:07', 1, 0, 0, 0, 0, 0),
(103, 51, NULL, 'Anónimo-5743', 'anonimo', 'eeeee', 0, 96, 'activo', '2026-01-15 15:12:20', '2026-01-15 15:12:20', 0, 0, 0, 0, 0, 0),
(104, 50, NULL, 'Anónimo-4220', 'anonimo', 'rrrrrrrrrrrrrrrrrrrrrrrrr', 0, 84, 'activo', '2026-01-15 15:32:56', '2026-01-15 15:32:56', 0, 0, 0, 0, 0, 0),
(105, 51, NULL, 'Anónimo-6777', 'anonimo', 'molo', 0, 95, 'activo', '2026-01-15 15:44:17', '2026-01-15 15:44:17', 0, 0, 0, 0, 0, 0),
(106, 58, NULL, 'Anónimo-4248', 'anonimo', 'dddddddddddddddddd', 0, NULL, 'activo', '2026-01-15 16:00:57', '2026-01-15 16:00:57', 0, 0, 0, 0, 0, 0),
(107, 58, NULL, 'Anónimo-3773', 'anonimo', 'xxxxxxxxxxxxxxxx', 0, 106, 'activo', '2026-01-15 16:01:10', '2026-01-15 16:01:10', 0, 0, 0, 0, 0, 0),
(108, 60, NULL, 'Anónimo-7147', 'anonimo', 'mopuuuuu', 1, NULL, 'activo', '2026-01-15 16:36:04', '2026-01-15 17:36:28', 0, 0, 0, 0, 0, 1),
(109, 60, NULL, 'Anónimo-2684', 'anonimo', 'uuuuuuu', 1, 108, 'activo', '2026-01-15 16:36:14', '2026-01-15 16:36:21', 1, 0, 0, 0, 0, 0),
(110, 93, NULL, 'Anónimo-7597', 'anonimo', 'ji', 1, NULL, 'activo', '2026-01-15 16:45:06', '2026-01-15 18:24:25', 1, 0, 0, 0, 0, 0),
(111, 59, NULL, 'Anónimo-5443', 'anonimo', 'El pepe', 1, NULL, 'activo', '2026-01-15 16:52:16', '2026-01-15 16:52:28', 1, 0, 0, 0, 0, 0),
(112, 59, NULL, 'Anónimo-6704', 'anonimo', 'Oño', 1, 111, 'activo', '2026-01-15 16:52:39', '2026-01-15 16:52:58', 0, 0, 0, 0, 0, 1),
(113, 59, NULL, 'Anónimo-8306', 'anonimo', 'No por favor', 0, 111, 'activo', '2026-01-15 18:26:05', '2026-01-15 18:26:05', 0, 0, 0, 0, 0, 0),
(114, 94, NULL, 'Anónimo-5228', 'anonimo', 'Xap', 2, NULL, 'activo', '2026-01-15 18:26:31', '2026-01-16 03:48:58', 1, 0, 0, 0, 0, 1),
(115, 67, NULL, 'Anónimo-1748', 'anonimo', 'jjjjjjjjjjjjj', 1, NULL, 'activo', '2026-01-15 18:48:33', '2026-01-15 18:48:43', 1, 0, 0, 0, 0, 0),
(116, 67, NULL, 'Anónimo-5849', 'anonimo', 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjnnnnnnnnnnnnnmmmmmmmmmm', 0, 115, 'activo', '2026-01-15 18:48:53', '2026-01-15 18:48:53', 0, 0, 0, 0, 0, 0),
(117, 69, NULL, 'Anónimo-8777', 'anonimo', 'xxxx', 1, NULL, 'activo', '2026-01-15 18:50:11', '2026-01-15 18:50:21', 1, 0, 0, 0, 0, 0),
(118, 69, NULL, 'Anónimo-2430', 'anonimo', 'ap', 0, 117, 'activo', '2026-01-15 18:50:29', '2026-01-15 18:50:29', 0, 0, 0, 0, 0, 0),
(119, 63, NULL, 'Anónimo-1209', 'anonimo', 'ien', 1, NULL, 'activo', '2026-01-15 18:50:44', '2026-01-15 18:50:54', 1, 0, 0, 0, 0, 0),
(120, 94, NULL, 'Anónimo-8441', 'anonimo', 'ok', 1, 114, 'activo', '2026-01-15 18:51:33', '2026-01-16 03:52:42', 1, 0, 0, 0, 0, 0),
(121, 95, NULL, 'Anónimo-1127', 'anonimo', 'bien', 1, NULL, 'activo', '2026-01-15 18:53:18', '2026-01-16 13:38:24', 1, 0, 0, 0, 0, 0),
(122, 95, NULL, 'Anónimo-1359', 'anonimo', 'funciona', 0, 121, 'activo', '2026-01-15 18:53:36', '2026-01-15 18:53:36', 0, 0, 0, 0, 0, 0),
(123, 88, NULL, 'Anónimo-2993', 'anonimo', 'bien', 0, NULL, 'activo', '2026-01-15 18:55:33', '2026-01-15 18:55:59', 0, 0, 0, 0, 0, 0),
(124, 94, NULL, 'Anónimo-1950', 'anonimo', 'lop', 0, 114, 'activo', '2026-01-16 03:49:29', '2026-01-16 03:49:29', 0, 0, 0, 0, 0, 0),
(125, 94, NULL, 'Anónimo-7433', 'anonimo', 'bueno', 0, 120, 'activo', '2026-01-16 03:53:30', '2026-01-16 03:53:30', 0, 0, 0, 0, 0, 0),
(126, 61, NULL, 'Anónimo-4162', 'anonimo', 'No tengo idea', 0, NULL, 'activo', '2026-01-16 03:55:00', '2026-01-16 03:55:00', 0, 0, 0, 0, 0, 0);

--
-- Disparadores `bioforo_respuestas`
--
DELIMITER $$
CREATE TRIGGER `after_respuesta_insert` AFTER INSERT ON `bioforo_respuestas` FOR EACH ROW BEGIN
    UPDATE bioforo_temas 
    SET respuestas_count = respuestas_count + 1,
        actualizado_en = NOW()
    WHERE id = NEW.tema_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_respuestas_likes`
--

CREATE TABLE `bioforo_respuestas_likes` (
  `id` int(11) NOT NULL,
  `respuesta_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `anonimo_id` varchar(50) DEFAULT NULL,
  `tipo` enum('like','dislike') NOT NULL DEFAULT 'like',
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_temas`
--

CREATE TABLE `bioforo_temas` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `anonimo_nombre` varchar(50) DEFAULT NULL,
  `rol` enum('admin','especialista','usuario','anonimo') DEFAULT 'anonimo',
  `titulo` varchar(180) NOT NULL,
  `contenido` text NOT NULL,
  `likes` int(11) DEFAULT 0,
  `total_reacciones` int(11) NOT NULL DEFAULT 0,
  `vistas` int(11) DEFAULT 0,
  `respuestas_count` int(11) DEFAULT 0,
  `estado` enum('activo','oculto','eliminado') DEFAULT 'activo',
  `fijado` tinyint(1) DEFAULT 0,
  `creado_en` datetime DEFAULT current_timestamp(),
  `actualizado_en` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `love_count` int(11) NOT NULL DEFAULT 0,
  `haha_count` int(11) NOT NULL DEFAULT 0,
  `wow_count` int(11) NOT NULL DEFAULT 0,
  `sad_count` int(11) NOT NULL DEFAULT 0,
  `angry_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bioforo_temas`
--

INSERT INTO `bioforo_temas` (`id`, `categoria_id`, `usuario_id`, `anonimo_nombre`, `rol`, `titulo`, `contenido`, `likes`, `total_reacciones`, `vistas`, `respuestas_count`, `estado`, `fijado`, `creado_en`, `actualizado_en`, `likes_count`, `love_count`, `haha_count`, `wow_count`, `sad_count`, `angry_count`) VALUES
(49, 2, NULL, 'Anónimo-3700', 'anonimo', 'rrrrrrrrrr', 'rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr', 0, 1, 0, 9, 'activo', 0, '2026-01-14 22:54:55', '2026-01-15 15:32:49', 0, 0, 0, 1, 0, 0),
(50, 1, NULL, 'Anónimo-1372', 'anonimo', 'dddddddddddd', 'ddddddddddddddd', 0, 1, 0, 6, 'activo', 0, '2026-01-14 22:56:57', '2026-01-15 17:33:25', 0, 0, 0, 1, 0, 0),
(51, 3, NULL, 'Anónimo-4869', 'anonimo', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk', 0, 0, 0, 6, 'activo', 0, '2026-01-15 00:06:27', '2026-01-15 15:44:17', 0, 0, 0, 0, 0, 0),
(52, 6, NULL, 'Anónimo-2425', 'anonimo', 'no lo see', '123456789123', 0, 0, 0, 5, 'activo', 0, '2026-01-15 00:59:44', '2026-01-15 01:36:42', 0, 0, 0, 0, 0, 0),
(53, 1, NULL, 'hhhhhhhhhhhhh', 'anonimo', '¿Cuáles son los mejores superalimentos para incluir en la dieta?', 'Hola comunidad, me gustaría saber qué superalimentos recomiendan para mejorar la salud inmunológica. ¿Alguien tiene experiencia con la maca, camu camu o algún otro superalimento peruano?', 0, 1, 0, 16, 'activo', 0, '2026-01-10 15:42:50', '2026-01-06 15:50:31', 0, 0, 0, 1, 0, 0),
(54, 1, NULL, 'Anónimo-1234', 'anonimo', '¿Beneficios de la quinoa?', 'He leído mucho sobre la quinoa pero no sé si realmente vale la pena incluirla en mi dieta diaria.  ¿Alguien tiene experiencia? ', 0, 0, 0, 0, 'activo', 0, '2026-01-10 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(55, 1, NULL, 'Anónimo-5678', 'anonimo', 'Superalimentos peruanos más efectivos', '¿Cuáles son los superalimentos peruanos que realmente funcionan?  He probado maca y camu camu, ¿qué otros recomiendan?', 0, 0, 0, 0, 'activo', 0, '2026-01-11 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(56, 2, NULL, 'Anónimo-9012', 'anonimo', 'Mejores prácticas para agricultura orgánica', 'Quiero iniciar un cultivo orgánico en casa.  ¿Qué consejos me pueden dar para empezar?', 0, 0, 0, 0, 'activo', 0, '2026-01-12 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(57, 1, NULL, 'Anónimo-3456', 'anonimo', '¿La chía realmente ayuda a bajar de peso?', 'He visto muchas dietas que incluyen chía, pero ¿realmente funciona para adelgazar?', 0, 1, 0, 0, 'activo', 0, '2026-01-13 15:59:14', '2026-01-15 18:27:56', 0, 0, 0, 1, 0, 0),
(58, 3, NULL, 'Anónimo-7890', 'anonimo', 'Diferencia entre stevia y otros endulzantes', '¿La stevia es realmente más saludable que el azúcar refinada?  ¿Qué pasa con la miel?', 0, 0, 0, 2, 'activo', 0, '2026-01-14 15:59:14', '2026-01-15 16:01:10', 0, 0, 0, 0, 0, 0),
(59, 1, NULL, 'Anónimo-2345', 'anonimo', 'Propiedades del aguaje', 'Escuché que el aguaje es excelente para la salud hormonal. ¿Alguien lo ha probado?', 0, 1, 0, 3, 'activo', 0, '2026-01-15 09:59:14', '2026-01-15 18:26:05', 0, 0, 0, 0, 1, 0),
(60, 2, NULL, 'Anónimo-6789', 'anonimo', 'Cultivo de moringa en clima tropical', '¿Alguien tiene experiencia cultivando moringa?  ¿Qué cuidados necesita?', 0, 0, 0, 2, 'activo', 0, '2026-01-15 03:59:14', '2026-01-15 16:36:14', 0, 0, 0, 0, 0, 0),
(61, 1, NULL, 'Anónimo-1357', 'anonimo', '¿El açaí es tan bueno como dicen?', 'Veo el açaí en todos lados, pero ¿realmente tiene tantos beneficios?', 0, 1, 0, 1, 'activo', 0, '2026-01-14 21:59:14', '2026-01-16 03:55:00', 0, 0, 0, 1, 0, 0),
(62, 3, NULL, 'Anónimo-2468', 'anonimo', 'Recetas saludables con kiwicha', 'Tengo kiwicha en casa y no sé cómo prepararla. ¿Alguien tiene recetas? ', 0, 0, 0, 0, 'activo', 0, '2026-01-14 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(63, 1, NULL, 'Anónimo-3579', 'anonimo', 'Beneficios del sacha inchi', '¿El sacha inchi es mejor que otras fuentes de omega-3?', 0, 0, 0, 1, 'activo', 0, '2026-01-13 15:59:14', '2026-01-15 18:50:44', 0, 0, 0, 0, 0, 0),
(64, 2, NULL, 'Anónimo-4680', 'anonimo', 'Compostaje casero para principiantes', 'Quiero hacer compost en casa pero no sé por dónde empezar. ¿Consejos?', 0, 0, 0, 0, 'activo', 0, '2026-01-12 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(65, 1, NULL, 'Anónimo-5791', 'anonimo', '¿La spirulina tiene contraindicaciones?', 'Quiero tomar spirulina pero tengo hipotiroidismo. ¿Es seguro?', 0, 0, 0, 0, 'activo', 0, '2026-01-11 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(66, 3, NULL, 'Anónimo-6802', 'anonimo', 'Smoothies verdes para desayunar', '¿Cuál es la mejor combinación de ingredientes para un smoothie nutritivo?', 0, 0, 0, 0, 'activo', 0, '2026-01-10 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(67, 1, NULL, 'Anónimo-7913', 'anonimo', 'Propiedades del yacón', 'He escuchado que el yacón es bueno para diabéticos. ¿Es cierto?', 0, 1, 0, 2, 'activo', 0, '2026-01-09 15:59:14', '2026-01-15 18:49:02', 0, 0, 0, 0, 0, 1),
(68, 2, NULL, 'Anónimo-8024', 'anonimo', 'Hidroponía vs cultivo tradicional', '¿Qué sistema es más eficiente para cultivar en espacios pequeños?', 0, 0, 0, 0, 'activo', 0, '2026-01-08 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(69, 1, NULL, 'Anónimo-9135', 'anonimo', 'Alimentos ricos en antioxidantes', '¿Cuáles son los alimentos con más antioxidantes naturales?', 0, 0, 0, 2, 'activo', 0, '2026-01-07 15:59:14', '2026-01-15 18:50:29', 0, 0, 0, 0, 0, 0),
(70, 3, NULL, 'Anónimo-0246', 'anonimo', 'Dieta vegana balanceada', '¿Cómo asegurarme de obtener todas las proteínas en una dieta vegana?', 0, 0, 0, 0, 'activo', 0, '2026-01-06 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(71, 1, NULL, 'Anónimo-1357', 'anonimo', 'Beneficios del cacao puro', '¿El cacao sin azúcar realmente tiene beneficios para la salud?', 0, 0, 0, 0, 'activo', 0, '2026-01-05 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(72, 2, NULL, 'Anónimo-2468', 'anonimo', 'Rotación de cultivos en huerto casero', '¿Cada cuánto debo rotar los cultivos en mi huerto?', 0, 0, 0, 0, 'activo', 0, '2026-01-04 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(73, 1, NULL, 'Anónimo-3579', 'anonimo', '¿La lucuma engorda? ', 'Me encanta la lucuma pero tengo miedo de subir de peso. ¿Opiniones?', 0, 0, 0, 0, 'activo', 0, '2026-01-03 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(74, 3, NULL, 'Anónimo-4680', 'anonimo', 'Snacks saludables para llevar', '¿Qué snacks nutritivos puedo llevar al trabajo? ', 0, 0, 0, 0, 'activo', 0, '2026-01-02 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(75, 1, NULL, 'Anónimo-5791', 'anonimo', 'Propiedades del maíz morado', '¿El maíz morado tiene propiedades anticancerígenas?', 0, 0, 0, 0, 'activo', 0, '2026-01-01 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(76, 2, NULL, 'Anónimo-6802', 'anonimo', 'Control de plagas orgánico', '¿Cómo controlar plagas sin usar químicos?', 0, 0, 0, 0, 'activo', 0, '2025-12-31 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(77, 1, NULL, 'Anónimo-7913', 'anonimo', 'Alimentos antiinflamatorios naturales', 'Tengo artritis y busco alimentos que ayuden con la inflamación. ', 0, 0, 0, 0, 'activo', 0, '2025-12-30 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(78, 3, NULL, 'Anónimo-8024', 'anonimo', 'Meal prep saludable', '¿Cómo organizar las comidas de la semana de forma nutritiva?', 0, 0, 0, 0, 'activo', 0, '2025-12-29 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(79, 1, NULL, 'Anónimo-9135', 'anonimo', '¿El polen de abeja es seguro? ', 'Quiero probar polen de abeja pero tengo alergias. ¿Riesgos?', 0, 0, 0, 0, 'activo', 0, '2025-12-28 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(80, 2, NULL, 'Anónimo-0246', 'anonimo', 'Permacultura para principiantes', '¿Qué es la permacultura y cómo empezar?', 0, 0, 0, 0, 'activo', 0, '2025-12-27 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(81, 1, NULL, 'Anónimo-1357', 'anonimo', 'Beneficios del aceite de coco', '¿El aceite de coco es realmente saludable o es puro marketing?', 0, 0, 0, 0, 'activo', 0, '2025-12-26 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(82, 3, NULL, 'Anónimo-2468', 'anonimo', 'Desayunos sin gluten nutritivos', 'Soy celíaco y busco opciones de desayuno variadas. ', 0, 0, 0, 0, 'activo', 0, '2025-12-25 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(83, 1, NULL, 'Anónimo-3579', 'anonimo', '¿Las algas son buena fuente de proteína?', 'He leído sobre las algas marinas como superalimento. ¿Opiniones?', 0, 0, 0, 0, 'activo', 0, '2025-12-24 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(84, 2, NULL, 'Anónimo-4680', 'anonimo', 'Cultivo vertical en departamento', '¿Cómo maximizar espacio para cultivar en un depa pequeño?', 0, 0, 0, 0, 'activo', 0, '2025-12-23 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(85, 1, NULL, 'Anónimo-5791', 'anonimo', 'Probióticos naturales vs suplementos', '¿Es mejor consumir probióticos de alimentos o cápsulas?', 0, 0, 0, 0, 'activo', 0, '2025-12-22 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(86, 3, NULL, 'Anónimo-6802', 'anonimo', 'Cenas ligeras pero nutritivas', '¿Qué puedo cenar que sea ligero pero que me llene?', 0, 0, 0, 0, 'activo', 0, '2025-12-21 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(87, 1, NULL, 'Anónimo-7913', 'anonimo', 'Propiedades del jengibre', '¿El jengibre realmente ayuda con las náuseas?', 0, 0, 0, 0, 'activo', 0, '2025-12-20 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(88, 2, NULL, 'Anónimo-8024', 'anonimo', 'Semillas para germinar en casa', '¿Qué semillas son mejores para hacer germinados caseros?', 0, 1, 0, 1, 'activo', 0, '2025-12-19 15:59:14', '2026-01-15 18:55:33', 0, 0, 0, 1, 0, 0),
(89, 1, NULL, 'Anónimo-9135', 'anonimo', 'Beneficios de la cúrcuma', '¿La cúrcuma con pimienta negra es más efectiva? ', 0, 0, 0, 0, 'activo', 0, '2025-12-18 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(90, 3, NULL, 'Anónimo-0246', 'anonimo', 'Bebidas naturales energizantes', '¿Qué bebidas naturales dan energía sin cafeína?', 0, 0, 0, 0, 'activo', 0, '2025-12-17 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(91, 1, NULL, 'Anónimo-1357', 'anonimo', 'Alimentos para mejorar la memoria', '¿Qué alimentos ayudan a la concentración y memoria?', 0, 0, 0, 0, 'activo', 0, '2025-12-16 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(92, 2, NULL, 'Anónimo-2468', 'anonimo', 'Riego eficiente en huertos urbanos', '¿Cómo optimizar el agua en cultivos caseros?', 0, 0, 0, 0, 'activo', 0, '2025-12-15 15:59:14', '2026-01-15 15:59:14', 0, 0, 0, 0, 0, 0),
(93, 5, NULL, 'Anónimo-8433', 'anonimo', 'elpepito', 'nomuiolpoiru', 0, 2, 0, 1, 'activo', 0, '2026-01-15 16:44:55', '2026-01-15 17:42:44', 0, 0, 2, 0, 0, 0),
(94, 4, NULL, 'Anónimo-8215', 'anonimo', 'pruebiy123567889', 'ffffffffffffffffffff', 0, 1, 0, 4, 'activo', 0, '2026-01-15 18:24:45', '2026-01-16 03:53:30', 0, 0, 1, 0, 0, 0),
(95, 4, NULL, 'Anónimo-2553', 'anonimo', 'Prueba 2', 'Moringa, folololo', 0, 1, 0, 2, 'activo', 0, '2026-01-15 18:52:43', '2026-01-15 18:53:36', 0, 0, 0, 1, 0, 0),
(96, 4, NULL, 'Anónimo-9513', 'anonimo', 'Verificación', 'Nada que decir', 0, 2, 0, 0, 'activo', 0, '2026-01-15 19:13:10', '2026-01-16 12:28:02', 0, 0, 1, 0, 1, 0),
(97, 5, NULL, 'Anónimo-6506', 'anonimo', 'lalo34', 'iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', 0, 0, 0, 0, 'activo', 0, '2026-01-15 19:33:34', '2026-01-15 19:33:34', 0, 0, 0, 0, 0, 0),
(98, 4, NULL, 'Anónimo-6898', 'anonimo', 'Kilo231222', 'qwertyuiop14562554', 0, 1, 0, 0, 'activo', 0, '2026-01-16 03:56:43', '2026-01-16 03:57:08', 1, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos_items`
--

CREATE TABLE `carritos_items` (
  `id` int(11) NOT NULL,
  `carrito_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `variacion_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  `moneda` enum('PEN','USD') NOT NULL DEFAULT 'PEN',
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carritos_items`
--

INSERT INTO `carritos_items` (`id`, `carrito_id`, `producto_id`, `variacion_id`, `cantidad`, `precio_unitario`, `moneda`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 1, 1, NULL, 1, 89.90, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(2, 1, 3, NULL, 1, 199.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(3, 2, 2, NULL, 1, 159.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(4, 2, 6, NULL, 1, 129.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(5, 3, 4, NULL, 1, 3299.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(6, 3, 1, NULL, 1, 89.90, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(7, 4, 5, NULL, 1, 499.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(8, 4, 6, NULL, 1, 129.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(9, 5, 3, NULL, 1, 199.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(10, 5, 1, NULL, 2, 89.90, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(11, 6, 2, NULL, 1, 159.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(12, 6, 5, NULL, 1, 499.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(1, 1, 1, NULL, 1, 89.90, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(2, 1, 3, NULL, 1, 199.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(3, 2, 2, NULL, 1, 159.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(4, 2, 6, NULL, 1, 129.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(5, 3, 4, NULL, 1, 3299.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(6, 3, 1, NULL, 1, 89.90, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(7, 4, 5, NULL, 1, 499.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(8, 4, 6, NULL, 1, 129.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(9, 5, 3, NULL, 1, 199.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(10, 5, 1, NULL, 2, 89.90, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(11, 6, 2, NULL, 1, 159.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00'),
(12, 6, 5, NULL, 1, 499.00, 'PEN', NULL, '2025-12-07 11:29:00', NULL, '2025-12-07 11:29:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `codigo_cliente` varchar(50) DEFAULT NULL,
  `tipo_cliente` enum('Persona','Empresa') NOT NULL DEFAULT 'Persona',
  `documento_tipo` enum('DNI','CE','RUC','PASAPORTE') DEFAULT 'DNI',
  `documento_numero` varchar(20) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `puntos` int(11) NOT NULL DEFAULT 0,
  `observaciones` varchar(300) DEFAULT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `usuario_id`, `persona_id`, `codigo_cliente`, `tipo_cliente`, `documento_tipo`, `documento_numero`, `estado`, `puntos`, `observaciones`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 23, 5, 'CLI-0001', 'Persona', 'DNI', '74851236', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37'),
(2, 24, 6, 'CLI-0002', 'Persona', 'DNI', '89562314', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37'),
(3, 25, 7, 'CLI-0003', 'Persona', 'DNI', '77441236', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37'),
(4, 26, 8, 'CLI-0004', 'Persona', 'DNI', '55663214', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37'),
(5, 27, 9, 'CLI-0005', 'Persona', 'DNI', '74125896', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:33:36'),
(6, 28, 10, 'CLI-0006', 'Persona', 'DNI', '81234567', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37'),
(1, 23, 5, 'CLI-0001', 'Persona', 'DNI', '74851236', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37'),
(2, 24, 6, 'CLI-0002', 'Persona', 'DNI', '89562314', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37'),
(3, 25, 7, 'CLI-0003', 'Persona', 'DNI', '77441236', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37'),
(4, 26, 8, 'CLI-0004', 'Persona', 'DNI', '55663214', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37'),
(5, 27, 9, 'CLI-0005', 'Persona', 'DNI', '74125896', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:33:36'),
(6, 28, 10, 'CLI-0006', 'Persona', 'DNI', '81234567', 1, 0, NULL, 1, '2025-12-07 11:27:37', NULL, '2025-12-07 11:27:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_direcciones`
--

CREATE TABLE `clientes_direcciones` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `tipo_direccion` enum('envio','facturacion','otro') NOT NULL DEFAULT 'envio',
  `alias` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) NOT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `distrito` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(20) DEFAULT NULL,
  `pais` varchar(100) NOT NULL DEFAULT 'Perú',
  `telefono_contacto` varchar(20) DEFAULT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT 0,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes_direcciones`
--

INSERT INTO `clientes_direcciones` (`id`, `cliente_id`, `tipo_direccion`, `alias`, `direccion`, `referencia`, `distrito`, `provincia`, `departamento`, `codigo_postal`, `pais`, `telefono_contacto`, `es_principal`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 1, 'envio', 'Casa', 'Av. Los Álamos 123', 'Cerca a parque', 'Miraflores', 'Lima', 'Lima', '15074', 'Perú', '987654321', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(2, 2, 'envio', 'Casa', 'Jr. Las Flores 456', 'Frente al colegio', 'Surco', 'Lima', 'Lima', '15039', 'Perú', '912345678', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(3, 3, 'envio', 'Casa', 'Calle Real 789', 'Altura cuadra 5', 'San Borja', 'Lima', 'Lima', '15036', 'Perú', '976543210', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(4, 4, 'envio', 'Casa', 'Av. Primavera 321', 'Al costado del banco', 'Surco', 'Lima', 'Lima', '15039', 'Perú', '998877665', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(5, 5, 'envio', 'Casa', 'Jr. Libertad 654', 'Frente al mercado', 'Comas', 'Lima', 'Lima', '15311', 'Perú', '934567812', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(6, 6, 'envio', 'Casa', 'Av. La Paz 159', 'A 2 cuadras del hospital', 'Callao', 'Callao', 'Callao', '07001', 'Perú', '900112233', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(1, 1, 'envio', 'Casa', 'Av. Los Álamos 123', 'Cerca a parque', 'Miraflores', 'Lima', 'Lima', '15074', 'Perú', '987654321', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(2, 2, 'envio', 'Casa', 'Jr. Las Flores 456', 'Frente al colegio', 'Surco', 'Lima', 'Lima', '15039', 'Perú', '912345678', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(3, 3, 'envio', 'Casa', 'Calle Real 789', 'Altura cuadra 5', 'San Borja', 'Lima', 'Lima', '15036', 'Perú', '976543210', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(4, 4, 'envio', 'Casa', 'Av. Primavera 321', 'Al costado del banco', 'Surco', 'Lima', 'Lima', '15039', 'Perú', '998877665', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(5, 5, 'envio', 'Casa', 'Jr. Libertad 654', 'Frente al mercado', 'Comas', 'Lima', 'Lima', '15311', 'Perú', '934567812', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45'),
(6, 6, 'envio', 'Casa', 'Av. La Paz 159', 'A 2 cuadras del hospital', 'Callao', 'Callao', 'Callao', '07001', 'Perú', '900112233', 1, 1, '2025-12-07 11:28:45', NULL, '2025-12-07 11:28:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `tipo` enum('tarjeta','billetera','transferencia','contraentrega','otro') NOT NULL DEFAULT 'tarjeta',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `config_json` text DEFAULT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre`, `codigo`, `tipo`, `activo`, `config_json`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 'Tarjeta VISA', 'VISA', 'tarjeta', 1, NULL, NULL, '2025-12-07 11:29:21', NULL, '2025-12-07 11:29:21'),
(2, 'Yape', 'YAPE', 'billetera', 1, NULL, NULL, '2025-12-07 11:29:21', NULL, '2025-12-07 11:29:21'),
(3, 'Transferencia Bancaria BCP', 'BCP', 'transferencia', 1, NULL, NULL, '2025-12-07 11:29:21', NULL, '2025-12-07 11:29:21'),
(1, 'Tarjeta VISA', 'VISA', 'tarjeta', 1, NULL, NULL, '2025-12-07 11:29:21', NULL, '2025-12-07 11:29:21'),
(2, 'Yape', 'YAPE', 'billetera', 1, NULL, NULL, '2025-12-07 11:29:21', NULL, '2025-12-07 11:29:21'),
(3, 'Transferencia Bancaria BCP', 'BCP', 'transferencia', 1, NULL, NULL, '2025-12-07 11:29:21', NULL, '2025-12-07 11:29:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `metodo_pago_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `moneda` enum('PEN','USD') NOT NULL DEFAULT 'PEN',
  `estado_pago` enum('pendiente','autorizado','pagado','fallido','revertido') NOT NULL DEFAULT 'pendiente',
  `referencia_externa` varchar(255) DEFAULT NULL,
  `mensaje_respuesta` varchar(500) DEFAULT NULL,
  `fecha_autorizacion` datetime DEFAULT NULL,
  `fecha_confirmacion` datetime DEFAULT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `pedido_id`, `metodo_pago_id`, `monto`, `moneda`, `estado_pago`, `referencia_externa`, `mensaje_respuesta`, `fecha_autorizacion`, `fecha_confirmacion`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 1, 1, 340.90, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(2, 2, 2, 339.84, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(3, 3, 3, 3998.70, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(4, 4, 1, 741.04, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(5, 5, 2, 446.98, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(6, 6, 1, 776.44, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(1, 1, 1, 340.90, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(2, 2, 2, 339.84, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(3, 3, 3, 3998.70, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(4, 4, 1, 741.04, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(5, 5, 2, 446.98, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28'),
(6, 6, 1, 776.44, 'PEN', 'pagado', NULL, NULL, NULL, NULL, NULL, '2025-12-07 11:29:28', NULL, '2025-12-07 11:29:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigo_pedido` varchar(50) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `vendedor_usuario_id` int(11) DEFAULT NULL,
  `direccion_envio_id` int(11) DEFAULT NULL,
  `direccion_facturacion_id` int(11) DEFAULT NULL,
  `estado_pedido` enum('pendiente','pagado','en_proceso','enviado','entregado','cancelado','devuelto') NOT NULL DEFAULT 'pendiente',
  `moneda` enum('PEN','USD') NOT NULL DEFAULT 'PEN',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descuento_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `igv_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `costo_envio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `metodo_envio` varchar(100) DEFAULT NULL,
  `tracking_envio` varchar(100) DEFAULT NULL,
  `tipo_comprobante` enum('BOLETA','FACTURA','NINGUNO') NOT NULL DEFAULT 'NINGUNO',
  `serie_comprobante` varchar(10) DEFAULT NULL,
  `numero_comprobante` varchar(20) DEFAULT NULL,
  `canal_venta` enum('web','mobile','manual') NOT NULL DEFAULT 'web',
  `observaciones` varchar(500) DEFAULT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigo_pedido`, `cliente_id`, `vendedor_usuario_id`, `direccion_envio_id`, `direccion_facturacion_id`, `estado_pedido`, `moneda`, `subtotal`, `descuento_total`, `igv_total`, `total`, `costo_envio`, `metodo_envio`, `tracking_envio`, `tipo_comprobante`, `serie_comprobante`, `numero_comprobante`, `canal_venta`, `observaciones`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 'PED-0001', 1, NULL, 1, NULL, 'pagado', 'PEN', 288.90, 0.00, 52.00, 340.90, 10.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(2, 'PED-0002', 2, NULL, 2, NULL, 'pagado', 'PEN', 288.00, 0.00, 51.84, 339.84, 10.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(3, 'PED-0003', 3, NULL, 3, NULL, 'pagado', 'PEN', 3388.90, 0.00, 609.80, 3998.70, 20.00, NULL, NULL, 'FACTURA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(4, 'PED-0004', 4, NULL, 4, NULL, 'enviado', 'PEN', 628.00, 0.00, 113.04, 741.04, 15.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(5, 'PED-0005', 5, NULL, 5, NULL, 'entregado', 'PEN', 378.80, 0.00, 68.18, 446.98, 12.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(6, 'PED-0006', 6, NULL, 6, NULL, 'pagado', 'PEN', 658.00, 0.00, 118.44, 776.44, 15.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(1, 'PED-0001', 1, NULL, 1, NULL, 'pagado', 'PEN', 288.90, 0.00, 52.00, 340.90, 10.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(2, 'PED-0002', 2, NULL, 2, NULL, 'pagado', 'PEN', 288.00, 0.00, 51.84, 339.84, 10.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(3, 'PED-0003', 3, NULL, 3, NULL, 'pagado', 'PEN', 3388.90, 0.00, 609.80, 3998.70, 20.00, NULL, NULL, 'FACTURA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(4, 'PED-0004', 4, NULL, 4, NULL, 'enviado', 'PEN', 628.00, 0.00, 113.04, 741.04, 15.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(5, 'PED-0005', 5, NULL, 5, NULL, 'entregado', 'PEN', 378.80, 0.00, 68.18, 446.98, 12.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07'),
(6, 'PED-0006', 6, NULL, 6, NULL, 'pagado', 'PEN', 658.00, 0.00, 118.44, 776.44, 15.00, NULL, NULL, 'BOLETA', NULL, NULL, 'web', NULL, 1, '2025-12-07 11:29:07', NULL, '2025-12-07 11:29:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_detalle`
--

CREATE TABLE `pedidos_detalle` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `variacion_id` int(11) DEFAULT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL DEFAULT 0.00,
  `igv` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_detalle`
--

INSERT INTO `pedidos_detalle` (`id`, `pedido_id`, `producto_id`, `variacion_id`, `nombre_producto`, `sku`, `cantidad`, `precio_unitario`, `descuento`, `igv`, `subtotal`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 1, 1, NULL, 'Mouse Gamer RGB', 'SKU-MOU-001', 1, 89.90, 0.00, 16.18, 106.08, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(2, 1, 3, NULL, 'Auriculares Inalámbricos', 'SKU-AUD-003', 1, 199.00, 0.00, 35.82, 234.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(3, 2, 2, NULL, 'Teclado Mecánico Blue', 'SKU-TEC-002', 1, 159.00, 0.00, 28.62, 187.62, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(4, 2, 6, NULL, 'Webcam Full HD 1080p', 'SKU-WEB-006', 1, 129.00, 0.00, 23.22, 152.22, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(5, 3, 4, NULL, 'Laptop Ultrabook 14\"', 'SKU-LAP-004', 1, 3299.00, 0.00, 593.82, 3892.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(6, 3, 1, NULL, 'Mouse Gamer RGB', 'SKU-MOU-001', 1, 89.90, 0.00, 16.18, 106.08, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(7, 4, 5, NULL, 'Silla Ergonómica Pro', 'SKU-SIL-005', 1, 499.00, 0.00, 89.82, 588.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(8, 4, 6, NULL, 'Webcam Full HD 1080p', 'SKU-WEB-006', 1, 129.00, 0.00, 23.22, 152.22, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(9, 5, 3, NULL, 'Auriculares Inalámbricos', 'SKU-AUD-003', 1, 199.00, 0.00, 35.82, 234.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(10, 5, 1, NULL, 'Mouse Gamer RGB', 'SKU-MOU-001', 2, 89.90, 0.00, 16.18, 212.16, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(11, 6, 2, NULL, 'Teclado Mecánico Blue', 'SKU-TEC-002', 1, 159.00, 0.00, 28.62, 187.62, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(12, 6, 5, NULL, 'Silla Ergonómica Pro', 'SKU-SIL-005', 1, 499.00, 0.00, 89.82, 588.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(1, 1, 1, NULL, 'Mouse Gamer RGB', 'SKU-MOU-001', 1, 89.90, 0.00, 16.18, 106.08, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(2, 1, 3, NULL, 'Auriculares Inalámbricos', 'SKU-AUD-003', 1, 199.00, 0.00, 35.82, 234.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(3, 2, 2, NULL, 'Teclado Mecánico Blue', 'SKU-TEC-002', 1, 159.00, 0.00, 28.62, 187.62, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(4, 2, 6, NULL, 'Webcam Full HD 1080p', 'SKU-WEB-006', 1, 129.00, 0.00, 23.22, 152.22, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(5, 3, 4, NULL, 'Laptop Ultrabook 14\"', 'SKU-LAP-004', 1, 3299.00, 0.00, 593.82, 3892.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(6, 3, 1, NULL, 'Mouse Gamer RGB', 'SKU-MOU-001', 1, 89.90, 0.00, 16.18, 106.08, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(7, 4, 5, NULL, 'Silla Ergonómica Pro', 'SKU-SIL-005', 1, 499.00, 0.00, 89.82, 588.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(8, 4, 6, NULL, 'Webcam Full HD 1080p', 'SKU-WEB-006', 1, 129.00, 0.00, 23.22, 152.22, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(9, 5, 3, NULL, 'Auriculares Inalámbricos', 'SKU-AUD-003', 1, 199.00, 0.00, 35.82, 234.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(10, 5, 1, NULL, 'Mouse Gamer RGB', 'SKU-MOU-001', 2, 89.90, 0.00, 16.18, 212.16, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(11, 6, 2, NULL, 'Teclado Mecánico Blue', 'SKU-TEC-002', 1, 159.00, 0.00, 28.62, 187.62, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14'),
(12, 6, 5, NULL, 'Silla Ergonómica Pro', 'SKU-SIL-005', 1, 499.00, 0.00, 89.82, 588.82, NULL, '2025-12-07 11:29:14', NULL, '2025-12-07 11:29:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `tipo_persona_id` int(11) NOT NULL,
  `nombre_razon_social` varchar(200) NOT NULL,
  `documento_identidad` varchar(20) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('M','F') DEFAULT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `tipo_persona_id`, `nombre_razon_social`, `documento_identidad`, `fecha_nacimiento`, `sexo`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 1, 'Juan Abad', '75418704', '1994-06-19', 'M', NULL, '2025-11-09 20:20:07', NULL, '2025-11-26 13:13:57'),
(3, 1, 'Vendedor 1', '75418705', '2025-11-18', 'M', NULL, '2025-11-18 22:17:50', NULL, '2025-12-06 12:08:28'),
(4, 1, 'Luis Enrique', '12345678', '2025-11-20', 'M', NULL, '2025-11-20 00:26:01', NULL, '2025-12-06 12:08:17'),
(5, 1, 'Carlos Medina', '74851236', '1990-08-11', 'M', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(6, 1, 'María Torres', '89562314', '1992-02-25', 'F', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(7, 1, 'Jorge Rivas', '77441236', '1988-07-30', 'M', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(8, 1, 'Ana López', '55663214', '1995-03-12', 'F', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(9, 1, 'Pedro Quispe', '74125896', '1991-09-19', 'M', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(10, 1, 'Daniela Paredes', '81234567', '1993-10-22', 'F', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(1, 1, 'Juan Abad', '75418704', '1994-06-19', 'M', NULL, '2025-11-09 20:20:07', NULL, '2025-11-26 13:13:57'),
(3, 1, 'Vendedor 1', '75418705', '2025-11-18', 'M', NULL, '2025-11-18 22:17:50', NULL, '2025-12-06 12:08:28'),
(4, 1, 'Luis Enrique', '12345678', '2025-11-20', 'M', NULL, '2025-11-20 00:26:01', NULL, '2025-12-06 12:08:17'),
(5, 1, 'Carlos Medina', '74851236', '1990-08-11', 'M', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(6, 1, 'María Torres', '89562314', '1992-02-25', 'F', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(7, 1, 'Jorge Rivas', '77441236', '1988-07-30', 'M', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(8, 1, 'Ana López', '55663214', '1995-03-12', 'F', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(9, 1, 'Pedro Quispe', '74125896', '1991-09-19', 'M', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(10, 1, 'Daniela Paredes', '81234567', '1993-10-22', 'F', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19'),
(0, 1, 'po', '1245698789', '2006-03-19', 'M', NULL, '2026-01-15 16:38:50', NULL, '2026-01-15 16:38:50'),
(0, 1, 'pouio', '369852147', '2026-01-01', 'M', NULL, '2026-01-15 16:41:50', NULL, '2026-01-15 16:41:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas_contactos`
--

CREATE TABLE `personas_contactos` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas_tipos`
--

CREATE TABLE `personas_tipos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas_tipos`
--

INSERT INTO `personas_tipos` (`id`, `descripcion`) VALUES
(1, 'Persona natural'),
(1, 'Persona natural');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `vendedor_usuario_id` int(11) DEFAULT NULL,
  `vendedor_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion_corta` varchar(500) DEFAULT NULL,
  `descripcion_larga` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `precio_oferta` decimal(10,2) DEFAULT NULL,
  `moneda` enum('PEN','USD') NOT NULL DEFAULT 'PEN',
  `igv_incluido` tinyint(1) NOT NULL DEFAULT 1,
  `codigo_sunat` varchar(50) DEFAULT NULL,
  `unidad_medida` varchar(10) NOT NULL DEFAULT 'NIU',
  `sku` varchar(100) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 0,
  `estado_stock` enum('in_stock','out_of_stock','preorder') NOT NULL DEFAULT 'in_stock',
  `peso_kg` decimal(10,2) DEFAULT NULL,
  `alto_cm` decimal(10,2) DEFAULT NULL,
  `ancho_cm` decimal(10,2) DEFAULT NULL,
  `largo_cm` decimal(10,2) DEFAULT NULL,
  `tipo_producto` enum('simple','variable','servicio') NOT NULL DEFAULT 'simple',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `publicado` tinyint(1) NOT NULL DEFAULT 1,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `meta_titulo` varchar(255) DEFAULT NULL,
  `meta_descripcion` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `vendedor_usuario_id`, `vendedor_id`, `categoria_id`, `nombre`, `slug`, `descripcion_corta`, `descripcion_larga`, `precio`, `precio_oferta`, `moneda`, `igv_incluido`, `codigo_sunat`, `unidad_medida`, `sku`, `stock`, `stock_minimo`, `estado_stock`, `peso_kg`, `alto_cm`, `ancho_cm`, `largo_cm`, `tipo_producto`, `estado`, `publicado`, `destacado`, `meta_titulo`, `meta_descripcion`, `meta_keywords`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 22, NULL, 2, 'Mouse Gamer RGB', 'mouse-gamer-rgb', 'Mouse ergonómico con luces RGB', 'Mouse profesional de alta precisión con 6 botones.', 89.90, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-MOU-001', 50, 5, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16'),
(2, 22, NULL, 2, 'Teclado Mecánico Blue Switch', 'teclado-mecanico-blue', 'Teclado mecánico con switches blue', 'Construcción metálica, RGB personalizable.', 159.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-TEC-002', 40, 5, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16'),
(3, 22, NULL, 2, 'Auriculares Inalámbricos', 'auriculares-inalambricos', 'Audífonos bluetooth de alta fidelidad', 'Hasta 30h de batería, estuche de carga.', 199.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-AUD-003', 60, 10, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:42:03'),
(4, 22, NULL, 1, 'Laptop Ultrabook 14\"', 'laptop-ultrabook-14', 'Laptop ligera y potente', 'Procesador Ryzen 7, 16GB RAM, SSD 512GB.', 3299.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-LAP-004', 10, 2, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16'),
(5, 22, NULL, 3, 'Silla Ergonomica Pro', 'silla-ergonomica-pro', 'Silla profesional para oficina', 'Soporte lumbar, materiales premium.', 499.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-SIL-005', 20, 2, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16'),
(6, 22, NULL, 2, 'Webcam Full HD 1080p', 'webcam-fullhd-1080p', 'Cámara para videoconferencias', 'Incluye micrófono y corrección de luz.', 129.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-WEB-006', 35, 5, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16'),
(1, 22, NULL, 2, 'Mouse Gamer RGB', 'mouse-gamer-rgb', 'Mouse ergonómico con luces RGB', 'Mouse profesional de alta precisión con 6 botones.', 89.90, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-MOU-001', 50, 5, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16'),
(2, 22, NULL, 2, 'Teclado Mecánico Blue Switch', 'teclado-mecanico-blue', 'Teclado mecánico con switches blue', 'Construcción metálica, RGB personalizable.', 159.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-TEC-002', 40, 5, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16'),
(3, 22, NULL, 2, 'Auriculares Inalámbricos', 'auriculares-inalambricos', 'Audífonos bluetooth de alta fidelidad', 'Hasta 30h de batería, estuche de carga.', 199.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-AUD-003', 60, 10, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:42:03'),
(4, 22, NULL, 1, 'Laptop Ultrabook 14\"', 'laptop-ultrabook-14', 'Laptop ligera y potente', 'Procesador Ryzen 7, 16GB RAM, SSD 512GB.', 3299.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-LAP-004', 10, 2, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16'),
(5, 22, NULL, 3, 'Silla Ergonomica Pro', 'silla-ergonomica-pro', 'Silla profesional para oficina', 'Soporte lumbar, materiales premium.', 499.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-SIL-005', 20, 2, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16'),
(6, 22, NULL, 2, 'Webcam Full HD 1080p', 'webcam-fullhd-1080p', 'Cámara para videoconferencias', 'Incluye micrófono y corrección de luz.', 129.00, NULL, 'PEN', 1, NULL, 'NIU', 'SKU-WEB-006', 35, 5, 'in_stock', NULL, NULL, NULL, NULL, 'simple', 1, 1, 0, NULL, NULL, NULL, 1, '2025-12-07 11:27:45', NULL, '2025-12-07 11:28:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_atributos`
--

CREATE TABLE `productos_atributos` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_atributos`
--

INSERT INTO `productos_atributos` (`id`, `producto_id`, `nombre`, `valor`, `usuario_id_creador`, `fecha_hora_creado`) VALUES
(1, 1, 'Color', 'Negro', NULL, '2025-12-07 11:28:24'),
(2, 1, 'Conexión', 'USB', NULL, '2025-12-07 11:28:24'),
(3, 2, 'Switch', 'Blue', NULL, '2025-12-07 11:28:24'),
(4, 3, 'Bluetooth', '5.1', NULL, '2025-12-07 11:28:24'),
(5, 4, 'Procesador', 'Ryzen 7', NULL, '2025-12-07 11:28:24'),
(6, 5, 'Material', 'Cuero sintético', NULL, '2025-12-07 11:28:24'),
(1, 1, 'Color', 'Negro', NULL, '2025-12-07 11:28:24'),
(2, 1, 'Conexión', 'USB', NULL, '2025-12-07 11:28:24'),
(3, 2, 'Switch', 'Blue', NULL, '2025-12-07 11:28:24'),
(4, 3, 'Bluetooth', '5.1', NULL, '2025-12-07 11:28:24'),
(5, 4, 'Procesador', 'Ryzen 7', NULL, '2025-12-07 11:28:24'),
(6, 5, 'Material', 'Cuero sintético', NULL, '2025-12-07 11:28:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_categorias`
--

CREATE TABLE `productos_categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `categoria_padre` int(11) DEFAULT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_categorias`
--

INSERT INTO `productos_categorias` (`id`, `nombre`, `slug`, `descripcion`, `categoria_padre`, `icono`, `imagen_url`, `estado`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 'Tecnología', 'tecnologia', 'Productos electrónicos y gadgets', NULL, NULL, NULL, 1, 1, '2025-12-07 11:28:07', NULL, '2025-12-07 11:28:07'),
(2, 'Periféricos', 'perifericos', 'Accesorios de computadoras', NULL, NULL, NULL, 1, 1, '2025-12-07 11:28:07', NULL, '2025-12-07 11:28:07'),
(3, 'Oficina', 'oficina', 'Muebles y equipos de oficina', NULL, NULL, NULL, 1, 1, '2025-12-07 11:28:07', NULL, '2025-12-07 11:28:07'),
(1, 'Tecnología', 'tecnologia', 'Productos electrónicos y gadgets', NULL, NULL, NULL, 1, 1, '2025-12-07 11:28:07', NULL, '2025-12-07 11:28:07'),
(2, 'Periféricos', 'perifericos', 'Accesorios de computadoras', NULL, NULL, NULL, 1, 1, '2025-12-07 11:28:07', NULL, '2025-12-07 11:28:07'),
(3, 'Oficina', 'oficina', 'Muebles y equipos de oficina', NULL, NULL, NULL, 1, 1, '2025-12-07 11:28:07', NULL, '2025-12-07 11:28:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_categorias_rel`
--

CREATE TABLE `productos_categorias_rel` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_imagenes`
--

CREATE TABLE `productos_imagenes` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT 0,
  `orden` int(11) NOT NULL DEFAULT 0,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_imagenes`
--

INSERT INTO `productos_imagenes` (`id`, `producto_id`, `url`, `es_principal`, `orden`, `usuario_id_creador`, `fecha_hora_creado`) VALUES
(1, 1, 'https://promart.vteximg.com.br/arquivos/ids/521373/120687.jpg?v=637351162485970000', 1, 1, NULL, '2025-12-07 11:28:31'),
(2, 2, 'https://m.media-amazon.com/images/I/71SscvTzh+L._AC_SL1500_.jpg', 0, 1, NULL, '2025-12-07 11:28:31'),
(3, 3, 'https://plazavea.vteximg.com.br/arquivos/ids/29787706-1000-1000/image-0.jpg?v=638654678112970000', 1, 1, NULL, '2025-12-07 11:28:31'),
(4, 4, 'https://oechsle.vteximg.com.br/arquivos/ids/20202529/image-9df735b4b27240b180395cedf43463d1.jpg?v=638700918599530000', 1, 1, NULL, '2025-12-07 11:28:31'),
(5, 5, 'https://colineal.pe/cdn/shop/files/7861223916041_3.jpg?v=1757204786', 1, 1, NULL, '2025-12-07 11:28:31'),
(6, 6, 'https://oechsle.vteximg.com.br/arquivos/ids/18525713/image-a2dbaabf15284fe5ad93d212dc90a969.jpg?v=638593221973070000', 1, 1, NULL, '2025-12-07 11:28:31'),
(7, 2, 'https://m.media-amazon.com/images/I/81pX3hsr7yL._AC_SL1500_.jpg', 0, 2, NULL, '2025-12-07 12:02:33'),
(8, 2, 'https://m.media-amazon.com/images/I/71BKLtSkEjL._AC_SL1500_.jpg', 1, 3, NULL, '2025-12-07 12:03:03'),
(9, 1, 'https://promart.vteximg.com.br/arquivos/ids/521376-1000-1000/120687_4.jpg?v=637351162494730000', 0, 2, NULL, '2025-12-07 12:04:06'),
(10, 1, 'https://promart.vteximg.com.br/arquivos/ids/521374-1000-1000/120687_1.jpg?v=637351162489730000', 0, 3, NULL, '2025-12-07 12:04:14'),
(1, 1, 'https://promart.vteximg.com.br/arquivos/ids/521373/120687.jpg?v=637351162485970000', 1, 1, NULL, '2025-12-07 11:28:31'),
(2, 2, 'https://m.media-amazon.com/images/I/71SscvTzh+L._AC_SL1500_.jpg', 0, 1, NULL, '2025-12-07 11:28:31'),
(3, 3, 'https://plazavea.vteximg.com.br/arquivos/ids/29787706-1000-1000/image-0.jpg?v=638654678112970000', 1, 1, NULL, '2025-12-07 11:28:31'),
(4, 4, 'https://oechsle.vteximg.com.br/arquivos/ids/20202529/image-9df735b4b27240b180395cedf43463d1.jpg?v=638700918599530000', 1, 1, NULL, '2025-12-07 11:28:31'),
(5, 5, 'https://colineal.pe/cdn/shop/files/7861223916041_3.jpg?v=1757204786', 1, 1, NULL, '2025-12-07 11:28:31'),
(6, 6, 'https://oechsle.vteximg.com.br/arquivos/ids/18525713/image-a2dbaabf15284fe5ad93d212dc90a969.jpg?v=638593221973070000', 1, 1, NULL, '2025-12-07 11:28:31'),
(7, 2, 'https://m.media-amazon.com/images/I/81pX3hsr7yL._AC_SL1500_.jpg', 0, 2, NULL, '2025-12-07 12:02:33'),
(8, 2, 'https://m.media-amazon.com/images/I/71BKLtSkEjL._AC_SL1500_.jpg', 1, 3, NULL, '2025-12-07 12:03:03'),
(9, 1, 'https://promart.vteximg.com.br/arquivos/ids/521376-1000-1000/120687_4.jpg?v=637351162494730000', 0, 2, NULL, '2025-12-07 12:04:06'),
(10, 1, 'https://promart.vteximg.com.br/arquivos/ids/521374-1000-1000/120687_1.jpg?v=637351162489730000', 0, 3, NULL, '2025-12-07 12:04:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_resenas`
--

CREATE TABLE `productos_resenas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `calificacion` tinyint(1) NOT NULL,
  `titulo` varchar(150) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `estado` enum('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_variaciones`
--

CREATE TABLE `productos_variaciones` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `detalles_json` text DEFAULT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_variaciones`
--

INSERT INTO `productos_variaciones` (`id`, `producto_id`, `nombre`, `sku`, `precio`, `stock`, `estado`, `detalles_json`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 1, 'Mouse RGB – Rojo', 'VAR-MOU-001-R', 94.90, 20, 1, NULL, NULL, '2025-12-07 11:28:38', NULL, '2025-12-07 11:28:38'),
(2, 1, 'Mouse RGB – Azul', 'VAR-MOU-001-A', 94.90, 15, 1, NULL, NULL, '2025-12-07 11:28:38', NULL, '2025-12-07 11:28:38'),
(3, 2, 'Teclado – Switch Blue', 'VAR-TEC-002-B', 159.00, 20, 1, NULL, NULL, '2025-12-07 11:28:38', NULL, '2025-12-07 11:28:38'),
(4, 2, 'Teclado – Switch Red', 'VAR-TEC-002-R', 159.00, 20, 1, NULL, NULL, '2025-12-07 11:28:38', NULL, '2025-12-07 11:28:38'),
(1, 1, 'Mouse RGB – Rojo', 'VAR-MOU-001-R', 94.90, 20, 1, NULL, NULL, '2025-12-07 11:28:38', NULL, '2025-12-07 11:28:38'),
(2, 1, 'Mouse RGB – Azul', 'VAR-MOU-001-A', 94.90, 15, 1, NULL, NULL, '2025-12-07 11:28:38', NULL, '2025-12-07 11:28:38'),
(3, 2, 'Teclado – Switch Blue', 'VAR-TEC-002-B', 159.00, 20, 1, NULL, NULL, '2025-12-07 11:28:38', NULL, '2025-12-07 11:28:38'),
(4, 2, 'Teclado – Switch Red', 'VAR-TEC-002-R', 159.00, 20, 1, NULL, NULL, '2025-12-07 11:28:38', NULL, '2025-12-07 11:28:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `avatar_color` varchar(20) DEFAULT '#4f46e5',
  `avatar_filename` varchar(255) DEFAULT NULL,
  `config_schedule_columnas` text DEFAULT NULL,
  `Confirmar_cambios_Estado_Realizado_por` tinyint(1) NOT NULL DEFAULT 1,
  `rol` enum('Administrador','Cliente','Vendedor') NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `avatar_url` varchar(255) DEFAULT NULL,
  `token_temporal` text DEFAULT NULL,
  `expiracion_token_temporal` datetime DEFAULT NULL,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificado` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `persona_id`, `username`, `password`, `correo`, `avatar_color`, `avatar_filename`, `config_schedule_columnas`, `Confirmar_cambios_Estado_Realizado_por`, `rol`, `estado`, `avatar_url`, `token_temporal`, `expiracion_token_temporal`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificado`, `fecha_hora_modificado`) VALUES
(1, 1, 'NP392247', '$2y$10$Mejn27ovfd62oihCRlOFuulWJc.GjGsl5VTw8BoF9rWLaVRJeBprS', 'juanabad@gmail.com', '#fdd681', 'avatar_69345f825de233.39637464.webp', '[\"orden\",\"descripcion\",\"Referencia\",\"job\",\"frecuencia\",\"estado\",\"usuario_realizado\",\"fecha_hora_ejecucion\",\"fecha_hora_generada\",\"fecha_hora_ejecucion_inicio\",\"fecha_hora_ejecucion_fin\",\"promedio_tiempo\",\"fecha_hora_creado\",\"id\",\"acciones\"]', 0, 'Administrador', 1, NULL, '370a97eb7e1dcc7b69fa7480c7cfa1f029cabd99d3f619825294522fb84e6074', '2025-11-20 07:32:27', NULL, '2025-11-09 20:20:07', NULL, '2025-12-06 12:26:59'),
(21, 4, 'LENRIQUE', '$2y$10$x0h5InTWvj.uITmCj0WrouJIBcql./6GxvIq0PQyzrtd0K/UnLa1C', 'lenrique@gmail.com', '#84b5f5', 'avatar_692b4336151984.58407036.png', '[\"orden\",\"descripcion\",\"Referencia\",\"job\",\"frecuencia\",\"estado\",\"usuario_realizado\",\"fecha_hora_ejecucion\",\"fecha_hora_generada\",\"fecha_hora_ejecucion_inicio\",\"fecha_hora_ejecucion_fin\",\"promedio_tiempo\",\"fecha_hora_creado\",\"id\",\"acciones\"]', 1, 'Administrador', 1, NULL, NULL, NULL, NULL, '2025-11-18 19:39:33', NULL, '2025-12-14 01:29:54'),
(22, 3, 'VENDEDO', '$2y$10$cb9ttGfC4.ZugUKLQb5Eb.mMwx382BPFpFvedryb3erRLnnRSDraW', 'juancarlos@gmail.com', '#b6e78d', NULL, '[\"orden\",\"descripcion\",\"Referencia\",\"job\",\"frecuencia\",\"estado\",\"usuario_realizado\",\"fecha_hora_ejecucion\",\"fecha_hora_generada\",\"fecha_hora_ejecucion_inicio\",\"fecha_hora_ejecucion_fin\",\"promedio_tiempo\",\"fecha_hora_creado\",\"id\",\"acciones\"]', 1, 'Vendedor', 1, NULL, NULL, NULL, NULL, '2025-11-18 22:17:50', NULL, '2025-12-06 12:25:39'),
(23, 5, 'CMEDINA', '$2y$10$abc123hash', 'carlos.medina@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(24, 6, 'MTORRES', '$2y$10$abc123hash', 'maria.torres@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2026-01-15 16:39:43'),
(25, 7, 'JRIVAS', '$2y$10$abc123hash', 'jorge.rivas@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(26, 8, 'ALOPEZ', '$2y$10$abc123hash', 'ana.lopez@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(27, 9, 'PQUISPE', '$2y$10$abc123hash', 'pedro.quispe@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-14 01:29:29'),
(28, 10, 'DPAREDES', '$2y$10$abc123hash', 'daniela.paredes@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(1, 1, 'NP392247', '$2y$10$Mejn27ovfd62oihCRlOFuulWJc.GjGsl5VTw8BoF9rWLaVRJeBprS', 'juanabad@gmail.com', '#fdd681', 'avatar_69345f825de233.39637464.webp', '[\"orden\",\"descripcion\",\"Referencia\",\"job\",\"frecuencia\",\"estado\",\"usuario_realizado\",\"fecha_hora_ejecucion\",\"fecha_hora_generada\",\"fecha_hora_ejecucion_inicio\",\"fecha_hora_ejecucion_fin\",\"promedio_tiempo\",\"fecha_hora_creado\",\"id\",\"acciones\"]', 0, 'Administrador', 1, NULL, '370a97eb7e1dcc7b69fa7480c7cfa1f029cabd99d3f619825294522fb84e6074', '2025-11-20 07:32:27', NULL, '2025-11-09 20:20:07', NULL, '2025-12-06 12:26:59'),
(21, 4, 'LENRIQUE', '$2y$10$x0h5InTWvj.uITmCj0WrouJIBcql./6GxvIq0PQyzrtd0K/UnLa1C', 'lenrique@gmail.com', '#84b5f5', 'avatar_692b4336151984.58407036.png', '[\"orden\",\"descripcion\",\"Referencia\",\"job\",\"frecuencia\",\"estado\",\"usuario_realizado\",\"fecha_hora_ejecucion\",\"fecha_hora_generada\",\"fecha_hora_ejecucion_inicio\",\"fecha_hora_ejecucion_fin\",\"promedio_tiempo\",\"fecha_hora_creado\",\"id\",\"acciones\"]', 1, 'Administrador', 1, NULL, NULL, NULL, NULL, '2025-11-18 19:39:33', NULL, '2025-12-14 01:29:54'),
(22, 3, 'VENDEDO', '$2y$10$cb9ttGfC4.ZugUKLQb5Eb.mMwx382BPFpFvedryb3erRLnnRSDraW', 'juancarlos@gmail.com', '#b6e78d', NULL, '[\"orden\",\"descripcion\",\"Referencia\",\"job\",\"frecuencia\",\"estado\",\"usuario_realizado\",\"fecha_hora_ejecucion\",\"fecha_hora_generada\",\"fecha_hora_ejecucion_inicio\",\"fecha_hora_ejecucion_fin\",\"promedio_tiempo\",\"fecha_hora_creado\",\"id\",\"acciones\"]', 1, 'Vendedor', 1, NULL, NULL, NULL, NULL, '2025-11-18 22:17:50', NULL, '2025-12-06 12:25:39'),
(23, 5, 'CMEDINA', '$2y$10$abc123hash', 'carlos.medina@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(24, 6, 'MTORRES', '$2y$10$abc123hash', 'maria.torres@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2026-01-15 16:39:43'),
(25, 7, 'JRIVAS', '$2y$10$abc123hash', 'jorge.rivas@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(26, 8, 'ALOPEZ', '$2y$10$abc123hash', 'ana.lopez@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(27, 9, 'PQUISPE', '$2y$10$abc123hash', 'pedro.quispe@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-14 01:29:29'),
(28, 10, 'DPAREDES', '$2y$10$abc123hash', 'daniela.paredes@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(0, 0, 'el pepe', '$2y$10$Vsb3Z2I.mVTBNmoyHSlPOOjqNpymvWlv7Ktyi8nbNwt7QYf3xcRLe', 'cowiki7713@jparksky.com', '#4f46e5', NULL, NULL, 1, '', 1, NULL, NULL, NULL, NULL, '2026-01-15 16:38:50', NULL, '2026-01-15 16:38:50'),
(0, 0, 'cowiki7713@jparksky.com', '$2y$10$pvXRmqvc6pckMMkGJy6NW.nHYtklYM74SVz0iqi0XrCO.xWVx3EXa', 'luio@gmail.com', '#4f46e5', NULL, NULL, 1, '', 1, NULL, NULL, NULL, NULL, '2026-01-15 16:41:50', NULL, '2026-01-15 16:41:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_tokens`
--

CREATE TABLE `usuarios_tokens` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(500) NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedores_cuentas_bancarias`
--

CREATE TABLE `vendedores_cuentas_bancarias` (
  `id` int(11) NOT NULL,
  `vendedor_id` int(11) NOT NULL,
  `banco` varchar(100) NOT NULL,
  `tipo_cuenta` enum('Ahorros','Corriente','CCI','Otro') NOT NULL DEFAULT 'Ahorros',
  `numero_cuenta` varchar(50) NOT NULL,
  `titular` varchar(150) NOT NULL,
  `documento_titular` varchar(20) DEFAULT NULL,
  `moneda` enum('PEN','USD') NOT NULL DEFAULT 'PEN',
  `es_principal` tinyint(1) NOT NULL DEFAULT 1,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_estadisticas_reacciones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_estadisticas_reacciones` (
`fecha` date
,`tipo` enum('like','love','haha','wow','sad','angry')
,`total` bigint(21)
,`usuarios_logueados` bigint(21)
,`anonimos_unicos` bigint(21)
,`temas_afectados` bigint(21)
,`respuestas_afectadas` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_respuestas_con_reacciones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_respuestas_con_reacciones` (
`id` int(11)
,`tema_id` int(11)
,`usuario_id` int(11)
,`anonimo_nombre` varchar(50)
,`rol` enum('admin','especialista','usuario','anonimo')
,`contenido` text
,`total_reacciones` int(11)
,`respuesta_a_id` int(11)
,`estado` enum('activo','oculto','eliminado')
,`creado_en` datetime
,`actualizado_en` datetime
,`likes_count` int(11)
,`love_count` int(11)
,`haha_count` int(11)
,`wow_count` int(11)
,`sad_count` int(11)
,`angry_count` int(11)
,`tema_titulo` varchar(180)
,`categoria_id` int(11)
,`likes_total` int(11)
,`love_total` int(11)
,`haha_total` int(11)
,`wow_total` int(11)
,`sad_total` int(11)
,`angry_total` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_temas_con_reacciones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_temas_con_reacciones` (
`id` int(11)
,`categoria_id` int(11)
,`usuario_id` int(11)
,`anonimo_nombre` varchar(50)
,`rol` enum('admin','especialista','usuario','anonimo')
,`titulo` varchar(180)
,`contenido` text
,`likes` int(11)
,`total_reacciones` int(11)
,`vistas` int(11)
,`respuestas_count` int(11)
,`estado` enum('activo','oculto','eliminado')
,`fijado` tinyint(1)
,`creado_en` datetime
,`actualizado_en` datetime
,`likes_count` int(11)
,`love_count` int(11)
,`haha_count` int(11)
,`wow_count` int(11)
,`sad_count` int(11)
,`angry_count` int(11)
,`categoria_nombre` varchar(100)
,`categoria_slug` varchar(120)
,`likes_total` int(11)
,`love_total` int(11)
,`haha_total` int(11)
,`wow_total` int(11)
,`sad_total` int(11)
,`angry_total` int(11)
,`reaccion_popular` varchar(5)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_estadisticas_reacciones`
--
DROP TABLE IF EXISTS `v_estadisticas_reacciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_estadisticas_reacciones`  AS SELECT cast(`bioforo_reacciones`.`creado_en` as date) AS `fecha`, `bioforo_reacciones`.`tipo` AS `tipo`, count(0) AS `total`, count(distinct `bioforo_reacciones`.`usuario_id`) AS `usuarios_logueados`, count(distinct `bioforo_reacciones`.`anonimo_id`) AS `anonimos_unicos`, count(distinct `bioforo_reacciones`.`tema_id`) AS `temas_afectados`, count(distinct `bioforo_reacciones`.`respuesta_id`) AS `respuestas_afectadas` FROM `bioforo_reacciones` WHERE `bioforo_reacciones`.`creado_en` >= current_timestamp() - interval 30 day GROUP BY cast(`bioforo_reacciones`.`creado_en` as date), `bioforo_reacciones`.`tipo` ORDER BY cast(`bioforo_reacciones`.`creado_en` as date) DESC, count(0) DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_respuestas_con_reacciones`
--
DROP TABLE IF EXISTS `v_respuestas_con_reacciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_respuestas_con_reacciones`  AS SELECT `r`.`id` AS `id`, `r`.`tema_id` AS `tema_id`, `r`.`usuario_id` AS `usuario_id`, `r`.`anonimo_nombre` AS `anonimo_nombre`, `r`.`rol` AS `rol`, `r`.`contenido` AS `contenido`, `r`.`total_reacciones` AS `total_reacciones`, `r`.`respuesta_a_id` AS `respuesta_a_id`, `r`.`estado` AS `estado`, `r`.`creado_en` AS `creado_en`, `r`.`actualizado_en` AS `actualizado_en`, `r`.`likes_count` AS `likes_count`, `r`.`love_count` AS `love_count`, `r`.`haha_count` AS `haha_count`, `r`.`wow_count` AS `wow_count`, `r`.`sad_count` AS `sad_count`, `r`.`angry_count` AS `angry_count`, `t`.`titulo` AS `tema_titulo`, `t`.`categoria_id` AS `categoria_id`, coalesce(`r`.`likes_count`,0) AS `likes_total`, coalesce(`r`.`love_count`,0) AS `love_total`, coalesce(`r`.`haha_count`,0) AS `haha_total`, coalesce(`r`.`wow_count`,0) AS `wow_total`, coalesce(`r`.`sad_count`,0) AS `sad_total`, coalesce(`r`.`angry_count`,0) AS `angry_total` FROM (`bioforo_respuestas` `r` left join `bioforo_temas` `t` on(`t`.`id` = `r`.`tema_id`)) WHERE `r`.`estado` = 'activo' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_temas_con_reacciones`
--
DROP TABLE IF EXISTS `v_temas_con_reacciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temas_con_reacciones`  AS SELECT `t`.`id` AS `id`, `t`.`categoria_id` AS `categoria_id`, `t`.`usuario_id` AS `usuario_id`, `t`.`anonimo_nombre` AS `anonimo_nombre`, `t`.`rol` AS `rol`, `t`.`titulo` AS `titulo`, `t`.`contenido` AS `contenido`, `t`.`likes` AS `likes`, `t`.`total_reacciones` AS `total_reacciones`, `t`.`vistas` AS `vistas`, `t`.`respuestas_count` AS `respuestas_count`, `t`.`estado` AS `estado`, `t`.`fijado` AS `fijado`, `t`.`creado_en` AS `creado_en`, `t`.`actualizado_en` AS `actualizado_en`, `t`.`likes_count` AS `likes_count`, `t`.`love_count` AS `love_count`, `t`.`haha_count` AS `haha_count`, `t`.`wow_count` AS `wow_count`, `t`.`sad_count` AS `sad_count`, `t`.`angry_count` AS `angry_count`, `c`.`nombre` AS `categoria_nombre`, `c`.`slug` AS `categoria_slug`, coalesce(`t`.`likes_count`,0) AS `likes_total`, coalesce(`t`.`love_count`,0) AS `love_total`, coalesce(`t`.`haha_count`,0) AS `haha_total`, coalesce(`t`.`wow_count`,0) AS `wow_total`, coalesce(`t`.`sad_count`,0) AS `sad_total`, coalesce(`t`.`angry_count`,0) AS `angry_total`, CASE WHEN `t`.`likes_count` >= `t`.`love_count` AND `t`.`likes_count` >= `t`.`haha_count` AND `t`.`likes_count` >= `t`.`wow_count` AND `t`.`likes_count` >= `t`.`sad_count` AND `t`.`likes_count` >= `t`.`angry_count` THEN 'like' WHEN `t`.`love_count` >= `t`.`haha_count` AND `t`.`love_count` >= `t`.`wow_count` AND `t`.`love_count` >= `t`.`sad_count` AND `t`.`love_count` >= `t`.`angry_count` THEN 'love' WHEN `t`.`haha_count` >= `t`.`wow_count` AND `t`.`haha_count` >= `t`.`sad_count` AND `t`.`haha_count` >= `t`.`angry_count` THEN 'haha' WHEN `t`.`wow_count` >= `t`.`sad_count` AND `t`.`wow_count` >= `t`.`angry_count` THEN 'wow' WHEN `t`.`sad_count` >= `t`.`angry_count` THEN 'sad' ELSE 'angry' END AS `reaccion_popular` FROM (`bioforo_temas` `t` left join `bioforo_categorias` `c` on(`c`.`id` = `t`.`categoria_id`)) WHERE `t`.`estado` = 'activo' ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bioforo_categorias`
--
ALTER TABLE `bioforo_categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_categorias_nombre` (`nombre`),
  ADD UNIQUE KEY `uk_categorias_slug` (`slug`);

--
-- Indices de la tabla `bioforo_jobs_log`
--
ALTER TABLE `bioforo_jobs_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ejecutado` (`ejecutado_en`);

--
-- Indices de la tabla `bioforo_reacciones`
--
ALTER TABLE `bioforo_reacciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_tema_usuario` (`tema_id`,`usuario_id`),
  ADD UNIQUE KEY `uk_respuesta_usuario` (`respuesta_id`,`usuario_id`),
  ADD UNIQUE KEY `uk_tema_anonimo` (`tema_id`,`anonimo_id`),
  ADD UNIQUE KEY `uk_respuesta_anonimo` (`respuesta_id`,`anonimo_id`),
  ADD KEY `idx_tema_id` (`tema_id`),
  ADD KEY `idx_respuesta_id` (`respuesta_id`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_creado_en` (`creado_en`),
  ADD KEY `idx_respuesta_usuario` (`respuesta_id`,`usuario_id`),
  ADD KEY `idx_tema_usuario` (`tema_id`,`usuario_id`),
  ADD KEY `idx_respuesta_anonimo` (`respuesta_id`,`anonimo_id`),
  ADD KEY `idx_tema_anonimo` (`tema_id`,`anonimo_id`);

--
-- Indices de la tabla `bioforo_reacciones_respuestas`
--
ALTER TABLE `bioforo_reacciones_respuestas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_reaccion_respuesta` (`respuesta_id`,`usuario_id`,`anonimo_id`);

--
-- Indices de la tabla `bioforo_reacciones_temas`
--
ALTER TABLE `bioforo_reacciones_temas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_reaccion_tema` (`tema_id`,`usuario_id`,`anonimo_id`);

--
-- Indices de la tabla `bioforo_reacciones_unificadas`
--
ALTER TABLE `bioforo_reacciones_unificadas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_reaccion_usuario_tema` (`tema_id`,`usuario_id`),
  ADD UNIQUE KEY `uk_reaccion_anonimo_tema` (`tema_id`,`anonimo_id`),
  ADD UNIQUE KEY `uk_reaccion_usuario_respuesta` (`respuesta_id`,`usuario_id`),
  ADD UNIQUE KEY `uk_reaccion_anonimo_respuesta` (`respuesta_id`,`anonimo_id`),
  ADD KEY `idx_reaccion_tema` (`tema_id`),
  ADD KEY `idx_reaccion_respuesta` (`respuesta_id`),
  ADD KEY `idx_reaccion_usuario` (`usuario_id`),
  ADD KEY `idx_reaccion_anonimo` (`anonimo_id`);

--
-- Indices de la tabla `bioforo_respuestas`
--
ALTER TABLE `bioforo_respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `respuesta_a_id` (`respuesta_a_id`),
  ADD KEY `idx_respuesta_tema` (`tema_id`),
  ADD KEY `idx_respuesta_fecha` (`creado_en`),
  ADD KEY `idx_respuesta_usuario` (`usuario_id`),
  ADD KEY `idx_respuesta_total_reacciones` (`total_reacciones`),
  ADD KEY `idx_respuesta_likes` (`likes_count`),
  ADD KEY `idx_respuesta_estado` (`estado`),
  ADD KEY `idx_respuesta_tema_reacciones` (`tema_id`,`total_reacciones`,`creado_en`);

--
-- Indices de la tabla `bioforo_respuestas_likes`
--
ALTER TABLE `bioforo_respuestas_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_resp_like_usuario` (`respuesta_id`,`usuario_id`),
  ADD UNIQUE KEY `uk_resp_like_anonimo` (`respuesta_id`,`anonimo_id`),
  ADD KEY `idx_resp_like_respuesta` (`respuesta_id`),
  ADD KEY `idx_resp_like_usuario` (`usuario_id`);

--
-- Indices de la tabla `bioforo_temas`
--
ALTER TABLE `bioforo_temas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tema_categoria` (`categoria_id`),
  ADD KEY `idx_tema_fecha` (`creado_en`),
  ADD KEY `idx_tema_likes` (`likes`),
  ADD KEY `idx_tema_usuario` (`usuario_id`),
  ADD KEY `idx_tema_total_reacciones` (`total_reacciones`),
  ADD KEY `idx_tema_estado` (`estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bioforo_categorias`
--
ALTER TABLE `bioforo_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `bioforo_jobs_log`
--
ALTER TABLE `bioforo_jobs_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bioforo_reacciones`
--
ALTER TABLE `bioforo_reacciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT de la tabla `bioforo_reacciones_respuestas`
--
ALTER TABLE `bioforo_reacciones_respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `bioforo_reacciones_temas`
--
ALTER TABLE `bioforo_reacciones_temas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `bioforo_reacciones_unificadas`
--
ALTER TABLE `bioforo_reacciones_unificadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `bioforo_respuestas`
--
ALTER TABLE `bioforo_respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT de la tabla `bioforo_respuestas_likes`
--
ALTER TABLE `bioforo_respuestas_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `bioforo_temas`
--
ALTER TABLE `bioforo_temas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bioforo_reacciones`
--
ALTER TABLE `bioforo_reacciones`
  ADD CONSTRAINT `bioforo_reacciones_ibfk_1` FOREIGN KEY (`tema_id`) REFERENCES `bioforo_temas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bioforo_reacciones_ibfk_2` FOREIGN KEY (`respuesta_id`) REFERENCES `bioforo_respuestas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `bioforo_reacciones_respuestas`
--
ALTER TABLE `bioforo_reacciones_respuestas`
  ADD CONSTRAINT `bioforo_reacciones_respuestas_ibfk_1` FOREIGN KEY (`respuesta_id`) REFERENCES `bioforo_respuestas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `bioforo_reacciones_temas`
--
ALTER TABLE `bioforo_reacciones_temas`
  ADD CONSTRAINT `bioforo_reacciones_temas_ibfk_1` FOREIGN KEY (`tema_id`) REFERENCES `bioforo_temas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `bioforo_reacciones_unificadas`
--
ALTER TABLE `bioforo_reacciones_unificadas`
  ADD CONSTRAINT `fk_reacciones_respuesta` FOREIGN KEY (`respuesta_id`) REFERENCES `bioforo_respuestas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reacciones_tema` FOREIGN KEY (`tema_id`) REFERENCES `bioforo_temas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `bioforo_respuestas`
--
ALTER TABLE `bioforo_respuestas`
  ADD CONSTRAINT `bioforo_respuestas_ibfk_1` FOREIGN KEY (`tema_id`) REFERENCES `bioforo_temas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bioforo_respuestas_ibfk_2` FOREIGN KEY (`respuesta_a_id`) REFERENCES `bioforo_respuestas` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `bioforo_respuestas_likes`
--
ALTER TABLE `bioforo_respuestas_likes`
  ADD CONSTRAINT `fk_resp_likes_respuesta` FOREIGN KEY (`respuesta_id`) REFERENCES `bioforo_respuestas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `bioforo_temas`
--
ALTER TABLE `bioforo_temas`
  ADD CONSTRAINT `bioforo_temas_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `bioforo_categorias` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `job_reparar_contadores` ON SCHEDULE EVERY 1 DAY STARTS '2026-01-14 03:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DECLARE v_corregidos INT DEFAULT 0;
    
    -- ... (tu código de UPDATE) ...
    
    -- Log correcto
    INSERT INTO bioforo_jobs_log (job_name, registros_corregidos)
    VALUES ('reparar_contadores', v_corregidos);
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
