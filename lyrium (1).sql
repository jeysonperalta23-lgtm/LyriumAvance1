-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-01-2026 a las 02:31:57
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bioforo_categorias`
--

CREATE TABLE `bioforo_categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bioforo_categorias`
--

INSERT INTO `bioforo_categorias` (`id`, `nombre`, `slug`, `creado_en`) VALUES
(1, 'Salud Alimentaria', 'salud-alimentaria', '2025-12-28 22:15:48'),
(2, 'Salud Emocional', 'salud-emocional', '2025-12-28 22:15:48'),
(3, 'Salud Ambiental', 'salud-ambiental', '2025-12-28 22:15:48');

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
  `likes` int(11) DEFAULT 0,
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
  `vistas` int(11) DEFAULT 0,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bioforo_temas`
--

INSERT INTO `bioforo_temas` (`id`, `categoria_id`, `usuario_id`, `anonimo_nombre`, `rol`, `titulo`, `contenido`, `likes`, `vistas`, `creado_en`) VALUES
(9, 1, NULL, 'Anónimo-1792', 'anonimo', 'hhh', 'ddddddddd', 0, 0, '2025-12-29 17:08:01'),
(11, 1, NULL, 'Anónimo-2565', 'anonimo', 'beneficios de usar botellas resiclabes', 'Usar botellas reciclables y reutilizables trae enormes beneficios: reduce drásticamente la contaminación (menos plástico en océanos y vertederos), conserva recursos naturales (petróleo) y energía, disminuye la huella de carbono, impulsa la economía circular, genera empleos verdes y, a largo plazo, es más económico para el consumidor al comprar menos desechables, promoviendo un estilo de vida más', 0, 0, '2025-12-29 18:06:46'),
(12, 1, NULL, 'Anónimo-9963', 'anonimo', 'beneficios de usar botellas resiclabes', 'Usar botellas reciclables y reutilizables trae enormes beneficios: reduce drásticamente la contaminación (menos plástico en océanos y vertederos), conserva recursos naturales (petróleo) y energía, disminuye la huella de carbono, impulsa la economía circular, genera empleos verdes y, a largo plazo, es más económico para el consumidor al comprar menos desechables, promoviendo un estilo de vida más', 0, 0, '2025-12-29 18:07:43'),
(0, 1, NULL, 'Anónimo-9281', 'anonimo', 'prueba', 'aaaaaa', 0, 0, '2026-01-09 20:23:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `estado` enum('abierto','convertido','expirado') NOT NULL DEFAULT 'abierto',
  `moneda` enum('PEN','USD') NOT NULL DEFAULT 'PEN',
  `total_estimado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `usuario_id_creador` int(11) DEFAULT NULL,
  `fecha_hora_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_modificador` int(11) DEFAULT NULL,
  `fecha_hora_modificado` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carritos`
--

INSERT INTO `carritos` (`id`, `cliente_id`, `estado`, `moneda`, `total_estimado`, `usuario_id_creador`, `fecha_hora_creado`, `usuario_id_modificador`, `fecha_hora_modificado`) VALUES
(1, 1, 'abierto', 'PEN', 0.00, NULL, '2025-12-07 11:28:51', NULL, '2025-12-07 11:28:51'),
(2, 2, 'abierto', 'PEN', 0.00, NULL, '2025-12-07 11:28:51', NULL, '2025-12-07 11:28:51'),
(3, 3, 'abierto', 'PEN', 0.00, NULL, '2025-12-07 11:28:51', NULL, '2025-12-07 11:28:51'),
(4, 4, 'abierto', 'PEN', 0.00, NULL, '2025-12-07 11:28:51', NULL, '2025-12-07 11:28:51'),
(5, 5, 'abierto', 'PEN', 0.00, NULL, '2025-12-07 11:28:51', NULL, '2025-12-07 11:28:51'),
(6, 6, 'abierto', 'PEN', 0.00, NULL, '2025-12-07 11:28:51', NULL, '2025-12-07 11:28:51');

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
(10, 1, 'Daniela Paredes', '81234567', '1993-10-22', 'F', 1, '2025-12-07 11:27:19', NULL, '2025-12-07 11:27:19');

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
(24, 6, 'MTORRES', '$2y$10$abc123hash', 'maria.torres@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(25, 7, 'JRIVAS', '$2y$10$abc123hash', 'jorge.rivas@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(26, 8, 'ALOPEZ', '$2y$10$abc123hash', 'ana.lopez@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29'),
(27, 9, 'PQUISPE', '$2y$10$abc123hash', 'pedro.quispe@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-14 01:29:29'),
(28, 10, 'DPAREDES', '$2y$10$abc123hash', 'daniela.paredes@gmail.com', '#4f46e5', NULL, NULL, 1, 'Cliente', 1, NULL, NULL, NULL, 1, '2025-12-07 11:27:29', NULL, '2025-12-07 11:27:29');

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

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_carrito_cliente` (`cliente_id`);

--
-- Indices de la tabla `carritos_items`
--
ALTER TABLE `carritos_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_carrito_item_carrito` (`carrito_id`),
  ADD KEY `idx_carrito_item_producto` (`producto_id`),
  ADD KEY `idx_carrito_item_variacion` (`variacion_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_clientes_usuario` (`usuario_id`),
  ADD UNIQUE KEY `uk_clientes_codigo` (`codigo_cliente`),
  ADD KEY `fk_clientes_persona` (`persona_id`);

--
-- Indices de la tabla `clientes_direcciones`
--
ALTER TABLE `clientes_direcciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_direccion_cliente` (`cliente_id`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_metodos_codigo` (`codigo`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pago_pedido` (`pedido_id`),
  ADD KEY `idx_pago_metodo` (`metodo_pago_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pedidos_codigo` (`codigo_pedido`),
  ADD KEY `idx_pedido_cliente` (`cliente_id`),
  ADD KEY `idx_pedido_vendedor` (`vendedor_usuario_id`),
  ADD KEY `idx_pedido_direccion_envio` (`direccion_envio_id`),
  ADD KEY `idx_pedido_direccion_facturacion` (`direccion_facturacion_id`);

--
-- Indices de la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_detalle_pedido` (`pedido_id`),
  ADD KEY `idx_detalle_producto` (`producto_id`),
  ADD KEY `idx_detalle_variacion` (`variacion_id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_personas_documento` (`documento_identidad`),
  ADD KEY `idx_personas_tipo` (`tipo_persona_id`);

--
-- Indices de la tabla `personas_contactos`
--
ALTER TABLE `personas_contactos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contacto_persona` (`persona_id`);

--
-- Indices de la tabla `personas_tipos`
--
ALTER TABLE `personas_tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_productos_slug` (`slug`),
  ADD UNIQUE KEY `uk_productos_sku` (`sku`),
  ADD KEY `idx_productos_categoria` (`categoria_id`),
  ADD KEY `idx_productos_vendedor` (`vendedor_usuario_id`);

--
-- Indices de la tabla `productos_atributos`
--
ALTER TABLE `productos_atributos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_atributo_producto` (`producto_id`);

--
-- Indices de la tabla `productos_categorias`
--
ALTER TABLE `productos_categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_categorias_slug` (`slug`),
  ADD KEY `idx_categoria_padre` (`categoria_padre`);

--
-- Indices de la tabla `productos_categorias_rel`
--
ALTER TABLE `productos_categorias_rel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_producto_categoria` (`producto_id`,`categoria_id`),
  ADD KEY `idx_rel_categoria` (`categoria_id`);

--
-- Indices de la tabla `productos_imagenes`
--
ALTER TABLE `productos_imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_imagen_producto` (`producto_id`);

--
-- Indices de la tabla `productos_resenas`
--
ALTER TABLE `productos_resenas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_resena_producto` (`producto_id`),
  ADD KEY `idx_resena_cliente` (`cliente_id`);

--
-- Indices de la tabla `productos_variaciones`
--
ALTER TABLE `productos_variaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_variaciones_sku` (`sku`),
  ADD KEY `idx_variacion_producto` (`producto_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_usuarios_username` (`username`),
  ADD UNIQUE KEY `uk_usuarios_correo` (`correo`),
  ADD KEY `idx_usuarios_persona` (`persona_id`);

--
-- Indices de la tabla `usuarios_tokens`
--
ALTER TABLE `usuarios_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_usuarios_tokens_token` (`token`),
  ADD KEY `idx_token_usuario` (`usuario_id`);

--
-- Indices de la tabla `vendedores_cuentas_bancarias`
--
ALTER TABLE `vendedores_cuentas_bancarias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cuenta_vendedor` (`vendedor_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `carritos_items`
--
ALTER TABLE `carritos_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clientes_direcciones`
--
ALTER TABLE `clientes_direcciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `personas_contactos`
--
ALTER TABLE `personas_contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas_tipos`
--
ALTER TABLE `personas_tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos_atributos`
--
ALTER TABLE `productos_atributos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos_categorias`
--
ALTER TABLE `productos_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos_categorias_rel`
--
ALTER TABLE `productos_categorias_rel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos_imagenes`
--
ALTER TABLE `productos_imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `productos_resenas`
--
ALTER TABLE `productos_resenas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos_variaciones`
--
ALTER TABLE `productos_variaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `usuarios_tokens`
--
ALTER TABLE `usuarios_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vendedores_cuentas_bancarias`
--
ALTER TABLE `vendedores_cuentas_bancarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `fk_carrito_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_clientes_persona` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_clientes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `clientes_direcciones`
--
ALTER TABLE `clientes_direcciones`
  ADD CONSTRAINT `fk_direccion_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pago_metodo` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pago` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pago_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedido_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedido_direccion_envio` FOREIGN KEY (`direccion_envio_id`) REFERENCES `clientes_direcciones` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedido_direccion_facturacion` FOREIGN KEY (`direccion_facturacion_id`) REFERENCES `clientes_direcciones` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedido_vendedor` FOREIGN KEY (`vendedor_usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `fk_persona_tipo` FOREIGN KEY (`tipo_persona_id`) REFERENCES `personas_tipos` (`id`);

--
-- Filtros para la tabla `personas_contactos`
--
ALTER TABLE `personas_contactos`
  ADD CONSTRAINT `fk_contacto_persona` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `productos_categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_productos_vendedor` FOREIGN KEY (`vendedor_usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_atributos`
--
ALTER TABLE `productos_atributos`
  ADD CONSTRAINT `fk_atributo_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_categorias_rel`
--
ALTER TABLE `productos_categorias_rel`
  ADD CONSTRAINT `fk_rel_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `productos_categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rel_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_imagenes`
--
ALTER TABLE `productos_imagenes`
  ADD CONSTRAINT `fk_imagen_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_resenas`
--
ALTER TABLE `productos_resenas`
  ADD CONSTRAINT `fk_resena_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_resena_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_variaciones`
--
ALTER TABLE `productos_variaciones`
  ADD CONSTRAINT `fk_variacion_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
