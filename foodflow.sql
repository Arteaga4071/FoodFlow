-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-09-2026 a las 05:31:27
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
-- Base de datos: `foodflow`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Entrada'),
(2, 'Plato Fuerte'),
(3, 'Postre'),
(4, 'Bebida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `imagen` varchar(255) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menu_items`
--

INSERT INTO `menu_items` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `imagen`, `disponible`, `creado_en`) VALUES
(1, 1, 'Patacones con Hogao', 'Plátano verde frito con salsa criolla', 12000.00, NULL, 1, '2026-09-13 20:50:41'),
(2, 1, 'Empanadas (x3)', 'Empanadas de carne con ají', 9000.00, NULL, 1, '2026-09-13 20:50:41'),
(3, 2, 'Bandeja Paisa', 'Frijoles, arroz, carne, chicharrón, huevo, arepa', 32000.00, NULL, 1, '2026-09-13 20:50:41'),
(4, 2, 'Pechuga a la Plancha', 'Con ensalada y papas', 28000.00, NULL, 1, '2026-09-13 20:50:41'),
(5, 2, 'Sancocho de Gallina', 'Porción individual con arroz y aguacate', 26000.00, NULL, 1, '2026-09-13 20:50:41'),
(6, 3, 'Flan de Caramelo', 'Postre casero', 8000.00, NULL, 1, '2026-09-13 20:50:41'),
(7, 3, 'Torta de Chocolate', 'Porción individual', 9000.00, NULL, 1, '2026-09-13 20:50:41'),
(8, 4, 'Limonada Natural', 'Vaso 400ml', 6000.00, NULL, 1, '2026-09-13 20:50:41'),
(9, 4, 'Gaseosa', 'Lata 350ml', 4500.00, NULL, 1, '2026-09-13 20:50:41'),
(10, 4, 'Jugo de Mora', 'Vaso 400ml', 6500.00, NULL, 1, '2026-09-13 20:50:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(10) UNSIGNED NOT NULL,
  `numero` int(10) UNSIGNED NOT NULL,
  `capacidad` int(10) UNSIGNED NOT NULL DEFAULT 4,
  `estado` enum('libre','ocupada') NOT NULL DEFAULT 'libre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numero`, `capacidad`, `estado`) VALUES
(1, 1, 4, 'ocupada'),
(2, 2, 2, 'libre'),
(3, 3, 4, 'libre'),
(4, 4, 6, 'libre'),
(5, 5, 2, 'libre'),
(6, 6, 4, 'libre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `mesa_id` int(10) UNSIGNED NOT NULL,
  `mesero_id` int(10) UNSIGNED DEFAULT NULL,
  `cliente_id` int(10) UNSIGNED DEFAULT NULL,
  `estado` enum('pendiente','en_preparacion','listo','entregado','pagado') NOT NULL DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `mesa_id`, `mesero_id`, `cliente_id`, `estado`, `total`, `creado_en`, `actualizado_en`) VALUES
(4, 1, NULL, 4, 'pendiente', 41000.00, '2026-09-13 22:09:21', '2026-09-13 22:11:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalles`
--

CREATE TABLE `pedido_detalles` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `menu_item_id` int(10) UNSIGNED NOT NULL,
  `cantidad` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_detalles`
--

INSERT INTO `pedido_detalles` (`id`, `pedido_id`, `menu_item_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(3, 4, 2, 1, 9000.00, 9000.00),
(4, 4, 3, 1, 32000.00, 32000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','mesero','cocina','cliente') NOT NULL DEFAULT 'mesero',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`, `activo`, `creado_en`) VALUES
(1, 'Administrador FoodFlow', 'admin', '$2b$12$12q0vX6i.gG9uYMGswvCY.0K6WWiucqBX0EmxpVDRst7LfkFLSfnW', 'admin', 1, '2026-09-13 20:50:41'),
(2, 'Carlos Mesero', 'mesero', '$2b$12$12q0vX6i.gG9uYMGswvCY.0K6WWiucqBX0EmxpVDRst7LfkFLSfnW', 'mesero', 1, '2026-09-13 20:50:41'),
(3, 'Laura Cocina', 'cocina', '$2b$12$12q0vX6i.gG9uYMGswvCY.0K6WWiucqBX0EmxpVDRst7LfkFLSfnW', 'cocina', 1, '2026-09-13 20:50:41'),
(4, 'Cliente Demo', 'cliente', '$2b$12$12q0vX6i.gG9uYMGswvCY.0K6WWiucqBX0EmxpVDRst7LfkFLSfnW', 'cliente', 1, '2026-09-13 22:04:23');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_categoria` (`categoria_id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_unico` (`numero`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedido_mesa` (`mesa_id`),
  ADD KEY `fk_pedido_mesero` (`mesero_id`),
  ADD KEY `fk_pedido_cliente` (`cliente_id`);

--
-- Indices de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_pedido` (`pedido_id`),
  ADD KEY `fk_detalle_item` (`menu_item_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_unico` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menu_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedido_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_pedido_mesa` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  ADD CONSTRAINT `fk_pedido_mesero` FOREIGN KEY (`mesero_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD CONSTRAINT `fk_detalle_item` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`),
  ADD CONSTRAINT `fk_detalle_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
