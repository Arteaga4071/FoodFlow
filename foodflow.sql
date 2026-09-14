-- =========================================================
-- FoodFlow - Sistema de Pedidos para Restaurante
-- Base de datos: foodflow
-- Motor: MySQL / MariaDB (compatible con phpMyAdmin / XAMPP)
-- =========================================================

CREATE DATABASE IF NOT EXISTS `foodflow` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `foodflow`;

-- ---------------------------------------------------------
-- Tabla: usuarios (admin, mesero, cocina, cliente)
-- ---------------------------------------------------------
CREATE TABLE `usuarios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `usuario` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol` ENUM('admin','mesero','cocina','cliente') NOT NULL DEFAULT 'mesero',
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_unico` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabla: categorias del menú
-- ---------------------------------------------------------
CREATE TABLE `categorias` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabla: menu (platos y bebidas)
-- ---------------------------------------------------------
CREATE TABLE `menu_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `categoria_id` INT UNSIGNED NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `precio` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `imagen` VARCHAR(255) DEFAULT NULL,
  `disponible` TINYINT(1) NOT NULL DEFAULT 1,
  `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_categoria` (`categoria_id`),
  CONSTRAINT `fk_menu_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabla: mesas
-- ---------------------------------------------------------
CREATE TABLE `mesas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero` INT UNSIGNED NOT NULL,
  `capacidad` INT UNSIGNED NOT NULL DEFAULT 4,
  `estado` ENUM('libre','ocupada') NOT NULL DEFAULT 'libre',
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_unico` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabla: pedidos
-- ---------------------------------------------------------
CREATE TABLE `pedidos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `mesa_id` INT UNSIGNED NOT NULL,
  `mesero_id` INT UNSIGNED DEFAULT NULL,
  `cliente_id` INT UNSIGNED DEFAULT NULL,
  `estado` ENUM('pendiente','en_preparacion','listo','entregado','pagado') NOT NULL DEFAULT 'pendiente',
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_pedido_mesa` (`mesa_id`),
  KEY `fk_pedido_mesero` (`mesero_id`),
  KEY `fk_pedido_cliente` (`cliente_id`),
  CONSTRAINT `fk_pedido_mesa` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  CONSTRAINT `fk_pedido_mesero` FOREIGN KEY (`mesero_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_pedido_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabla: detalle de pedidos
-- ---------------------------------------------------------
CREATE TABLE `pedido_detalles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pedido_id` INT UNSIGNED NOT NULL,
  `menu_item_id` INT UNSIGNED NOT NULL,
  `cantidad` INT UNSIGNED NOT NULL DEFAULT 1,
  `precio_unitario` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_detalle_pedido` (`pedido_id`),
  KEY `fk_detalle_item` (`menu_item_id`),
  CONSTRAINT `fk_detalle_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detalle_item` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- DATOS INICIALES (seed)
-- =========================================================

-- Usuarios de prueba (password para todos: "123456")
-- Hash bcrypt válido, compatible con password_verify() de PHP
INSERT INTO `usuarios` (`nombre`, `usuario`, `password`, `rol`) VALUES
('Administrador FoodFlow', 'admin', '$2b$12$12q0vX6i.gG9uYMGswvCY.0K6WWiucqBX0EmxpVDRst7LfkFLSfnW', 'admin'),
('Carlos Mesero', 'mesero', '$2b$12$12q0vX6i.gG9uYMGswvCY.0K6WWiucqBX0EmxpVDRst7LfkFLSfnW', 'mesero'),
('Laura Cocina', 'cocina', '$2b$12$12q0vX6i.gG9uYMGswvCY.0K6WWiucqBX0EmxpVDRst7LfkFLSfnW', 'cocina'),
('Cliente Demo', 'cliente', '$2b$12$12q0vX6i.gG9uYMGswvCY.0K6WWiucqBX0EmxpVDRst7LfkFLSfnW', 'cliente');

-- Categorías
INSERT INTO `categorias` (`nombre`) VALUES
('Entrada'), ('Plato Fuerte'), ('Postre'), ('Bebida');

-- Menú de ejemplo
INSERT INTO `menu_items` (`categoria_id`, `nombre`, `descripcion`, `precio`, `disponible`) VALUES
(1, 'Patacones con Hogao', 'Plátano verde frito con salsa criolla', 12000, 1),
(1, 'Empanadas (x3)', 'Empanadas de carne con ají', 9000, 1),
(2, 'Bandeja Paisa', 'Frijoles, arroz, carne, chicharrón, huevo, arepa', 32000, 1),
(2, 'Pechuga a la Plancha', 'Con ensalada y papas', 28000, 1),
(2, 'Sancocho de Gallina', 'Porción individual con arroz y aguacate', 26000, 1),
(3, 'Flan de Caramelo', 'Postre casero', 8000, 1),
(3, 'Torta de Chocolate', 'Porción individual', 9000, 1),
(4, 'Limonada Natural', 'Vaso 400ml', 6000, 1),
(4, 'Gaseosa', 'Lata 350ml', 4500, 1),
(4, 'Jugo de Mora', 'Vaso 400ml', 6500, 1);

-- Mesas
INSERT INTO `mesas` (`numero`, `capacidad`, `estado`) VALUES
(1, 4, 'libre'), (2, 2, 'libre'), (3, 4, 'libre'),
(4, 6, 'libre'), (5, 2, 'libre'), (6, 4, 'libre');
