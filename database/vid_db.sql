-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-12-2025 a las 21:54:32
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vid_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'CATEGORIA 1', NULL, '2025-12-02 13:09:38', '2025-12-02 13:09:38'),
(2, 'CATEGORIA 2', NULL, '2025-12-02 13:11:42', '2025-12-02 13:11:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificados`
--

CREATE TABLE `certificados` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `certificados`
--

INSERT INTO `certificados` (`id`, `user_id`, `file`, `created_at`, `updated_at`) VALUES
(5, 15, '0151764537188.pdf', '2025-11-30 21:13:08', '2025-11-30 21:13:08'),
(6, 15, '1151764537188.pdf', '2025-11-30 21:13:08', '2025-11-30 21:13:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `razon_social` varchar(700) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_punto` varchar(700) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_prop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_prop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitud` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitud` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contactos` json DEFAULT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `razon_social`, `tipo`, `nit`, `nombre_punto`, `nombre_prop`, `ci_prop`, `correo`, `cel`, `fono`, `dir`, `latitud`, `longitud`, `ciudad`, `contactos`, `estado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'CLIENTE 1 S.A.', 'EMPRESA', '111111111111', 'PUNTO VENTA C 1', 'JUAN PEREZ', '121212121', 'juanperez@gmail.com', '6767676767', '22222', 'LOS PEDREGALES', '111111111', '11111111111', 'LA PAZ', '[{\"cel\": \"7777777\", \"fono\": \"6767676767\", \"nombre\": \"JUAN GONZALES\", \"observacion\": \"OBS. CONTACTO 1\"}, {\"cel\": \"7866786\", \"fono\": \"667567567\", \"nombre\": \"JORGE RAMIRES\", \"observacion\": null}]', 1, NULL, '2025-12-03 15:46:45', '2025-12-03 15:51:27'),
(2, 'CLIENTE 2', 'PERSONA', '1111111111111', 'CLIENTE 2 PV', 'MARIA MAMANI', '23123123', NULL, '657756', '222', 'LOS PEDREAGLES1', '111', '2222', 'EL ALTO', '[{\"cel\": \"6757567\", \"fono\": \"222222\", \"nombre\": \"CONTACTO 1\", \"observacion\": null}]', 1, NULL, '2025-12-03 15:52:12', '2025-12-03 15:52:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'SISTEMA VID S.A.', 'VID', '11764632003.png', '2025-11-30 16:37:59', '2025-12-01 23:33:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_cobrars`
--

CREATE TABLE `cuenta_cobrars` (
  `id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `orden_venta_id` bigint UNSIGNED NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `cancelado` decimal(24,2) NOT NULL,
  `saldo` decimal(24,2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuenta_cobrars`
--

INSERT INTO `cuenta_cobrars` (`id`, `cliente_id`, `orden_venta_id`, `total`, `cancelado`, `saldo`, `fecha`, `hora`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 1935.00, 400.00, 1535.00, '2025-12-08', '16:48:08', NULL, '2025-12-08 20:48:08', '2025-12-08 21:39:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_cobrar_detalles`
--

CREATE TABLE `cuenta_cobrar_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `cuenta_cobrar_id` bigint UNSIGNED NOT NULL,
  `cancelado` decimal(24,2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuenta_cobrar_detalles`
--

INSERT INTO `cuenta_cobrar_detalles` (`id`, `cuenta_cobrar_id`, `cancelado`, `fecha`, `hora`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 200.00, '2025-12-08', '17:36:50', NULL, '2025-12-08 21:36:50', '2025-12-08 21:36:50'),
(2, 1, 200.00, '2025-12-08', '17:39:10', NULL, '2025-12-08 21:39:10', '2025-12-08 21:39:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion_clientes`
--

CREATE TABLE `devolucion_clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `cantidad_total` double NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion_cliente_detalles`
--

CREATE TABLE `devolucion_cliente_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `devolucion_cliente_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion_stocks`
--

CREATE TABLE `devolucion_stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `nro` bigint NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `cantidad_total` double NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `cantidad_total_v` double NOT NULL,
  `total_v` decimal(24,2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `devolucion_stocks`
--

INSERT INTO `devolucion_stocks` (`id`, `nro`, `codigo`, `sucursal_id`, `cantidad_total`, `total`, `cantidad_total_v`, `total_v`, `fecha`, `hora`, `observaciones`, `estado`, `user_id`, `verificado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 1, 'DEV.1', 2, 6, 1935.00, 6, 1935.00, '2025-12-06', '11:08:00', '', 'APROBADO', 1, 1, NULL, '2025-12-06 15:14:42', '2025-12-06 15:20:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion_stock_detalles`
--

CREATE TABLE `devolucion_stock_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `devolucion_stock_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `cantidad_fisica` double NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `sucursal_ajuste` bigint UNSIGNED DEFAULT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `devolucion_stock_detalles`
--

INSERT INTO `devolucion_stock_detalles` (`id`, `devolucion_stock_id`, `producto_id`, `cantidad`, `cantidad_fisica`, `costo`, `subtotal`, `verificado`, `sucursal_ajuste`, `motivo`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 3, 3, 300.00, 900.00, 1, NULL, NULL, '2025-12-06 15:14:42', '2025-12-06 15:20:26'),
(2, 2, 4, 3, 3, 345.00, 1035.00, 1, NULL, NULL, '2025-12-06 15:14:42', '2025-12-06 15:20:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id`, `user_id`, `file`, `created_at`, `updated_at`) VALUES
(5, 15, '0151764537188.jpeg', '2025-11-30 21:13:08', '2025-11-30 21:13:08'),
(6, 15, '1151764537188.pdf', '2025-11-30 21:13:08', '2025-11-30 21:13:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id` bigint UNSIGNED NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` decimal(24,2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` json DEFAULT NULL,
  `datos_nuevo` json DEFAULT NULL,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ROLE', '{\"id\": 2, \"nombre\": \"ADMINISTRADOR\", \"created_at\": \"2025-11-30T16:44:28.000000Z\", \"updated_at\": \"2025-11-30T16:44:28.000000Z\"}', NULL, 'ROLES', '2025-11-30', '12:44:28', '2025-11-30 16:44:28', '2025-11-30 16:44:28'),
(2, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ROLE', '{\"id\": 3, \"nombre\": \"AUXILIAR\", \"created_at\": \"2025-11-30T16:44:33.000000Z\", \"updated_at\": \"2025-11-30T16:44:33.000000Z\"}', NULL, 'ROLES', '2025-11-30', '12:44:33', '2025-11-30 16:44:33', '2025-11-30 16:44:33'),
(3, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', '{\"ci\": \"123456\", \"id\": 15, \"cel\": \"777777\", \"dir\": \"LOS PEDREGALES\", \"fono\": \"22222\", \"foto\": \"151764537188.jpeg\", \"sexo\": \"MASCULINO\", \"tipo\": \"USUARIO\", \"acceso\": \"1\", \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"nombre\": \"JUAN\", \"cel_dom\": \"78\", \"latitud\": \"111111111\", \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"role_id\": \"2\", \"usuario\": \"juan@gmail.com\", \"longitud\": \"1000000000\", \"grupo_san\": \"ORH+\", \"profesion\": \"PROFESION\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"documentos\": [{\"id\": 5, \"ext\": \"jpeg\", \"file\": \"0151764537188.jpeg\", \"name\": \"0151764537188.jpeg\", \"user_id\": 15, \"url_file\": \"http://vid.test/files/documentos/0151764537188.jpeg\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\"}], \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"certificados\": [{\"id\": 5, \"ext\": \"pdf\", \"file\": \"0151764537188.pdf\", \"name\": \"0151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\"}], \"nacionalidad\": \"BOLIVIANO\", \"fecha_registro\": \"2025-11-30\"}', NULL, 'USUARIOS', '2025-11-30', '17:13:08', '2025-11-30 21:13:08', '2025-11-30 21:13:08'),
(4, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN USUARIO', '{\"ci\": \"123456\", \"id\": 15, \"cel\": \"777777\", \"dir\": \"LOS PEDREGALES\", \"fono\": \"22222\", \"foto\": \"151764537188.jpeg\", \"sexo\": \"MASCULINO\", \"tipo\": \"USUARIO\", \"acceso\": 1, \"carnet\": null, \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"estado\": 1, \"nombre\": \"JUAN\", \"cel_dom\": \"78\", \"latitud\": \"111111111\", \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"role_id\": 2, \"usuario\": \"juan@gmail.com\", \"longitud\": \"1000000000\", \"grupo_san\": \"ORH+\", \"profesion\": \"PROFESION\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"deleted_at\": null, \"documentos\": [{\"id\": 5, \"ext\": \"jpeg\", \"file\": \"0151764537188.jpeg\", \"name\": \"0151764537188.jpeg\", \"user_id\": 15, \"url_file\": \"http://vid.test/files/documentos/0151764537188.jpeg\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/0151764537188.jpeg\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/1151764537188.pdf\"}], \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"certificados\": [{\"id\": 5, \"ext\": \"pdf\", \"file\": \"0151764537188.pdf\", \"name\": \"0151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/0151764537188.pdf\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/1151764537188.pdf\"}], \"nacionalidad\": \"BOLIVIANO\", \"fecha_registro\": \"2025-11-30\"}', '{\"ci\": \"123456\", \"id\": 15, \"cel\": \"777777\", \"dir\": \"LOS PEDREGALES\", \"fono\": \"22222\", \"foto\": \"151764595251.jpg\", \"sexo\": \"MASCULINO\", \"tipo\": \"USUARIO\", \"acceso\": \"1\", \"carnet\": null, \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"estado\": 1, \"nombre\": \"JUAN\", \"cel_dom\": \"78\", \"latitud\": \"111111111\", \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"role_id\": \"2\", \"usuario\": \"juan@gmail.com\", \"longitud\": \"1000000000\", \"grupo_san\": \"ORH+\", \"profesion\": \"PROFESION\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"deleted_at\": null, \"documentos\": [{\"id\": 5, \"ext\": \"jpeg\", \"file\": \"0151764537188.jpeg\", \"name\": \"0151764537188.jpeg\", \"user_id\": 15, \"url_file\": \"http://vid.test/files/documentos/0151764537188.jpeg\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/0151764537188.jpeg\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/1151764537188.pdf\"}], \"updated_at\": \"2025-12-01T13:20:51.000000Z\", \"certificados\": [{\"id\": 5, \"ext\": \"pdf\", \"file\": \"0151764537188.pdf\", \"name\": \"0151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/0151764537188.pdf\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/1151764537188.pdf\"}], \"nacionalidad\": \"BOLIVIANO\", \"fecha_registro\": \"2025-12-01\"}', 'USUARIOS', '2025-12-01', '09:20:51', '2025-12-01 13:20:51', '2025-12-01 13:20:51'),
(5, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN USUARIO', '{\"ci\": \"123456\", \"id\": 15, \"cel\": \"777777\", \"dir\": \"LOS PEDREGALES\", \"fono\": \"22222\", \"foto\": \"151764595251.jpg\", \"sexo\": \"MASCULINO\", \"tipo\": \"USUARIO\", \"acceso\": 1, \"carnet\": null, \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"estado\": 1, \"nombre\": \"JUAN\", \"cel_dom\": \"78\", \"latitud\": \"111111111\", \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"role_id\": 2, \"usuario\": \"juan@gmail.com\", \"longitud\": \"1000000000\", \"grupo_san\": \"ORH+\", \"profesion\": \"PROFESION\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"deleted_at\": null, \"documentos\": [{\"id\": 5, \"ext\": \"jpeg\", \"file\": \"0151764537188.jpeg\", \"name\": \"0151764537188.jpeg\", \"user_id\": 15, \"url_file\": \"http://vid.test/files/documentos/0151764537188.jpeg\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/0151764537188.jpeg\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/1151764537188.pdf\"}], \"updated_at\": \"2025-12-01T13:25:20.000000Z\", \"certificados\": [{\"id\": 5, \"ext\": \"pdf\", \"file\": \"0151764537188.pdf\", \"name\": \"0151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/0151764537188.pdf\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/1151764537188.pdf\"}], \"nacionalidad\": \"BOLIVIANO\", \"fecha_registro\": \"2025-12-01\"}', '{\"ci\": \"123456\", \"id\": 15, \"cel\": \"777777\", \"dir\": \"LOS PEDREGALES\", \"fono\": \"22222\", \"foto\": \"151764595251.jpg\", \"sexo\": \"MASCULINO\", \"tipo\": \"USUARIO\", \"acceso\": \"0\", \"carnet\": null, \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"estado\": 1, \"nombre\": \"JUAN\", \"cel_dom\": \"78\", \"latitud\": \"111111111\", \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"role_id\": \"2\", \"usuario\": \"juan@gmail.com\", \"longitud\": \"1000000000\", \"grupo_san\": \"ORH+\", \"profesion\": \"PROFESION\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"deleted_at\": null, \"documentos\": [{\"id\": 5, \"ext\": \"jpeg\", \"file\": \"0151764537188.jpeg\", \"name\": \"0151764537188.jpeg\", \"user_id\": 15, \"url_file\": \"http://vid.test/files/documentos/0151764537188.jpeg\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/0151764537188.jpeg\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/1151764537188.pdf\"}], \"updated_at\": \"2025-12-01T13:26:56.000000Z\", \"certificados\": [{\"id\": 5, \"ext\": \"pdf\", \"file\": \"0151764537188.pdf\", \"name\": \"0151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/0151764537188.pdf\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/1151764537188.pdf\"}], \"nacionalidad\": \"BOLIVIANO\", \"fecha_registro\": \"2025-12-01\"}', 'USUARIOS', '2025-12-01', '09:26:56', '2025-12-01 13:26:56', '2025-12-01 13:26:56'),
(6, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN USUARIO', '{\"ci\": \"123456\", \"id\": 15, \"cel\": \"777777\", \"dir\": \"LOS PEDREGALES\", \"fono\": \"22222\", \"foto\": \"151764595251.jpg\", \"sexo\": \"MASCULINO\", \"tipo\": \"USUARIO\", \"acceso\": 0, \"carnet\": null, \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"estado\": 1, \"nombre\": \"JUAN\", \"cel_dom\": \"78\", \"latitud\": \"111111111\", \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"role_id\": 2, \"usuario\": \"juan@gmail.com\", \"longitud\": \"1000000000\", \"grupo_san\": \"ORH+\", \"profesion\": \"PROFESION\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"deleted_at\": null, \"documentos\": [{\"id\": 5, \"ext\": \"jpeg\", \"file\": \"0151764537188.jpeg\", \"name\": \"0151764537188.jpeg\", \"user_id\": 15, \"url_file\": \"http://vid.test/files/documentos/0151764537188.jpeg\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/0151764537188.jpeg\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/1151764537188.pdf\"}], \"updated_at\": \"2025-12-01T13:29:56.000000Z\", \"certificados\": [{\"id\": 5, \"ext\": \"pdf\", \"file\": \"0151764537188.pdf\", \"name\": \"0151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/0151764537188.pdf\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/1151764537188.pdf\"}], \"nacionalidad\": \"BOLIVIANO\", \"fecha_registro\": \"2025-12-01\"}', '{\"ci\": \"123456\", \"id\": 15, \"cel\": \"777777\", \"dir\": \"LOS PEDREGALES\", \"fono\": \"22222\", \"foto\": \"151764595251.jpg\", \"sexo\": \"MASCULINO\", \"tipo\": \"USUARIO\", \"acceso\": \"1\", \"carnet\": null, \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"estado\": 1, \"nombre\": \"JUAN\", \"cel_dom\": \"78\", \"latitud\": \"111111111\", \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"role_id\": \"2\", \"usuario\": \"juan@gmail.com\", \"longitud\": \"1000000000\", \"grupo_san\": \"ORH+\", \"profesion\": \"PROFESION\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"deleted_at\": null, \"documentos\": [{\"id\": 5, \"ext\": \"jpeg\", \"file\": \"0151764537188.jpeg\", \"name\": \"0151764537188.jpeg\", \"user_id\": 15, \"url_file\": \"http://vid.test/files/documentos/0151764537188.jpeg\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/0151764537188.jpeg\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/documentos/1151764537188.pdf\"}], \"updated_at\": \"2025-12-01T13:30:08.000000Z\", \"certificados\": [{\"id\": 5, \"ext\": \"pdf\", \"file\": \"0151764537188.pdf\", \"name\": \"0151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/0151764537188.pdf\"}, {\"id\": 6, \"ext\": \"pdf\", \"file\": \"1151764537188.pdf\", \"name\": \"1151764537188.pdf\", \"user_id\": 15, \"url_file\": \"http://vid.test/imgs/attach.png\", \"created_at\": \"2025-11-30T21:13:08.000000Z\", \"updated_at\": \"2025-11-30T21:13:08.000000Z\", \"url_archivo\": \"http://vid.test/files/certificados/1151764537188.pdf\"}], \"nacionalidad\": \"BOLIVIANO\", \"fecha_registro\": \"2025-12-01\"}', 'USUARIOS', '2025-12-01', '09:30:08', '2025-12-01 13:30:08', '2025-12-01 13:30:08'),
(7, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ LA CONFIGURACIÓN DEL SISTEMA', '{\"id\": 1, \"logo\": \"logo.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-11-30T16:37:59.000000Z\", \"nombre_sistema\": \"VID S.A.\"}', '{\"id\": 1, \"logo\": \"logo.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:30:26.000000Z\", \"nombre_sistema\": \"sistema VID S.A.\"}', 'CONFIGURACIÓN SISTEMA', '2025-12-01', '19:30:26', '2025-12-01 23:30:26', '2025-12-01 23:30:26'),
(8, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ LA CONFIGURACIÓN DEL SISTEMA', '{\"id\": 1, \"logo\": \"logo.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:30:26.000000Z\", \"nombre_sistema\": \"sistema VID S.A.\"}', '{\"id\": 1, \"logo\": \"logo.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:31:47.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.\"}', 'CONFIGURACIÓN SISTEMA', '2025-12-01', '19:31:47', '2025-12-01 23:31:47', '2025-12-01 23:31:47'),
(9, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ LA CONFIGURACIÓN DEL SISTEMA', '{\"id\": 1, \"logo\": \"logo.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:31:47.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.\"}', '{\"id\": 1, \"logo\": \"11764631979.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:32:59.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.\"}', 'CONFIGURACIÓN SISTEMA', '2025-12-01', '19:32:59', '2025-12-01 23:32:59', '2025-12-01 23:32:59'),
(10, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ LA CONFIGURACIÓN DEL SISTEMA', '{\"id\": 1, \"logo\": \"11764631979.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:32:59.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.\"}', '{\"id\": 1, \"logo\": \"11764632003.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:33:23.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.\"}', 'CONFIGURACIÓN SISTEMA', '2025-12-01', '19:33:23', '2025-12-01 23:33:23', '2025-12-01 23:33:23'),
(11, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ LA CONFIGURACIÓN DEL SISTEMA', '{\"id\": 1, \"logo\": \"11764632003.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:33:23.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.\"}', '{\"id\": 1, \"logo\": \"11764632003.png\", \"alias\": \"VID S\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:33:36.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.\"}', 'CONFIGURACIÓN SISTEMA', '2025-12-01', '19:33:36', '2025-12-01 23:33:36', '2025-12-01 23:33:36'),
(12, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ LA CONFIGURACIÓN DEL SISTEMA', '{\"id\": 1, \"logo\": \"11764632003.png\", \"alias\": \"VID S\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:33:36.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.\"}', '{\"id\": 1, \"logo\": \"11764632003.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:33:40.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.S\"}', 'CONFIGURACIÓN SISTEMA', '2025-12-01', '19:33:40', '2025-12-01 23:33:40', '2025-12-01 23:33:40'),
(13, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ LA CONFIGURACIÓN DEL SISTEMA', '{\"id\": 1, \"logo\": \"11764632003.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:33:40.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.S\"}', '{\"id\": 1, \"logo\": \"11764632003.png\", \"alias\": \"VID\", \"created_at\": \"2025-11-30T16:37:59.000000Z\", \"updated_at\": \"2025-12-01T23:33:43.000000Z\", \"nombre_sistema\": \"SISTEMA VID S.A.\"}', 'CONFIGURACIÓN SISTEMA', '2025-12-01', '19:33:43', '2025-12-01 23:33:43', '2025-12-01 23:33:43'),
(14, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUCURSAL', '{\"id\": 1, \"fono\": \"6777777\", \"correo\": null, \"nombre\": \"SUCURSAL 1\", \"user_id\": 15, \"direccion\": \"LOS PEDREGALES\", \"created_at\": \"2025-12-01T23:54:27.000000Z\", \"updated_at\": \"2025-12-01T23:54:27.000000Z\"}', NULL, 'SUCURSALES', '2025-12-01', '19:54:27', '2025-12-01 23:54:27', '2025-12-01 23:54:27'),
(15, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUCURSAL', '{\"id\": 2, \"fono\": \"67676767\", \"correo\": null, \"nombre\": \"SUCURSAL 2\", \"user_id\": 15, \"direccion\": \"DIR 2\", \"created_at\": \"2025-12-02T00:03:36.000000Z\", \"updated_at\": \"2025-12-02T00:03:36.000000Z\"}', NULL, 'SUCURSALES', '2025-12-01', '20:03:36', '2025-12-02 00:03:36', '2025-12-02 00:03:36'),
(16, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUCURSAL', '{\"id\": 3, \"fono\": \"67676767\", \"correo\": null, \"estado\": 0, \"nombre\": \"SUCURSAL 3\", \"user_id\": 15, \"direccion\": \"DIR 3\", \"created_at\": \"2025-12-02T00:06:41.000000Z\", \"updated_at\": \"2025-12-02T00:06:41.000000Z\"}', NULL, 'SUCURSALES', '2025-12-01', '20:06:41', '2025-12-02 00:06:41', '2025-12-02 00:06:41'),
(17, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', '{\"ci\": \"12312312\", \"id\": 16, \"cel\": \"67676767\", \"dir\": \"LOS OLIVOS\", \"fono\": \"22232323\", \"sexo\": \"FEMENINO\", \"tipo\": \"USUARIO\", \"acceso\": \"1\", \"ci_exp\": \"LP\", \"correo\": \"maria@gmail.com\", \"nombre\": \"MARIA\", \"cel_dom\": \"676767\", \"latitud\": \"11111\", \"materno\": \"\", \"paterno\": \"GONZALES\", \"role_id\": \"3\", \"usuario\": \"maria@gmail.com\", \"longitud\": \"11111\", \"grupo_san\": \"ORH+\", \"profesion\": \"\", \"created_at\": \"2025-12-02T00:07:39.000000Z\", \"documentos\": [], \"updated_at\": \"2025-12-02T00:07:39.000000Z\", \"certificados\": [], \"nacionalidad\": \"BOLIVIANA\", \"fecha_registro\": \"2025-12-01\"}', NULL, 'USUARIOS', '2025-12-01', '20:07:39', '2025-12-02 00:07:39', '2025-12-02 00:07:39'),
(18, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', '{\"ci\": \"453543\", \"id\": 17, \"cel\": \"67676767\", \"dir\": \"LOS PEDRAGLES\", \"fono\": \"22322332\", \"sexo\": \"MASCULINO\", \"tipo\": \"USUARIO\", \"acceso\": \"1\", \"ci_exp\": \"LP\", \"correo\": \"jorge@gmail.com\", \"nombre\": \"JORGE\", \"cel_dom\": \"65665\", \"latitud\": \"111\", \"materno\": \"\", \"paterno\": \"GONZALES\", \"role_id\": \"3\", \"usuario\": \"jorge@gmail.com\", \"longitud\": \"111\", \"grupo_san\": \"ORH+\", \"profesion\": \"\", \"created_at\": \"2025-12-02T00:08:20.000000Z\", \"documentos\": [], \"updated_at\": \"2025-12-02T00:08:20.000000Z\", \"certificados\": [], \"nacionalidad\": \"BOLIVIANO\", \"fecha_registro\": \"2025-12-01\"}', NULL, 'USUARIOS', '2025-12-01', '20:08:20', '2025-12-02 00:08:20', '2025-12-02 00:08:20'),
(19, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SUCURSAL', '{\"id\": 2, \"fono\": \"67676767\", \"correo\": null, \"estado\": 1, \"nombre\": \"SUCURSAL 2\", \"user_id\": 15, \"direccion\": \"DIR 2\", \"created_at\": \"2025-12-02T00:03:36.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T00:03:36.000000Z\"}', '{\"id\": 2, \"fono\": \"67676767\", \"correo\": null, \"estado\": 1, \"nombre\": \"SUCURSAL 2\", \"user_id\": 16, \"direccion\": \"DIR 2\", \"created_at\": \"2025-12-02T00:03:36.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T00:08:39.000000Z\"}', 'SUCURSALES', '2025-12-01', '20:08:39', '2025-12-02 00:08:39', '2025-12-02 00:08:39'),
(20, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SUCURSAL', '{\"id\": 3, \"fono\": \"67676767\", \"correo\": null, \"estado\": 0, \"nombre\": \"SUCURSAL 3\", \"user_id\": 15, \"direccion\": \"DIR 3\", \"created_at\": \"2025-12-02T00:06:41.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T00:06:41.000000Z\"}', '{\"id\": 3, \"fono\": \"67676767\", \"correo\": null, \"estado\": 0, \"nombre\": \"SUCURSAL 3\", \"user_id\": 17, \"direccion\": \"DIR 3\", \"created_at\": \"2025-12-02T00:06:41.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T00:08:43.000000Z\"}', 'SUCURSALES', '2025-12-01', '20:08:43', '2025-12-02 00:08:43', '2025-12-02 00:08:43'),
(21, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SUCURSAL', '{\"id\": 1, \"fono\": \"6777777\", \"correo\": null, \"estado\": 1, \"nombre\": \"SUCURSAL 1\", \"user_id\": 15, \"direccion\": \"LOS PEDREGALES\", \"created_at\": \"2025-12-01T23:54:27.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-01T23:54:27.000000Z\"}', '{\"id\": 1, \"fono\": \"6777777\", \"correo\": \"correo@gmail.com\", \"estado\": 1, \"nombre\": \"SUCURSAL 1\", \"user_id\": 15, \"direccion\": \"LOS PEDREGALES\", \"created_at\": \"2025-12-01T23:54:27.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T00:09:09.000000Z\"}', 'SUCURSALES', '2025-12-01', '20:09:09', '2025-12-02 00:09:09', '2025-12-02 00:09:09'),
(22, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA SUCURSAL', '{\"id\": 3, \"fono\": \"67676767\", \"correo\": null, \"estado\": 0, \"nombre\": \"SUCURSAL 3\", \"user_id\": 17, \"direccion\": \"DIR 3\", \"created_at\": \"2025-12-02T00:06:41.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T00:08:43.000000Z\"}', NULL, 'SUCURSALES', '2025-12-01', '20:09:15', '2025-12-02 00:09:15', '2025-12-02 00:09:15'),
(23, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA CATEGORÍA', '{\"id\": 1, \"nombre\": \"CATEGORIA 1\", \"created_at\": \"2025-12-02T13:09:38.000000Z\", \"updated_at\": \"2025-12-02T13:09:38.000000Z\"}', NULL, 'CATEGORÍAS', '2025-12-02', '09:09:38', '2025-12-02 13:09:38', '2025-12-02 13:09:38'),
(24, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA CATEGORÍA', '{\"id\": 2, \"nombre\": \"CATEGORIA 2\", \"created_at\": \"2025-12-02T13:11:42.000000Z\", \"updated_at\": \"2025-12-02T13:11:42.000000Z\"}', NULL, 'CATEGORÍAS', '2025-12-02', '09:11:42', '2025-12-02 13:11:42', '2025-12-02 13:11:42'),
(25, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA CATEGORÍA', '{\"id\": 2, \"nombre\": \"CATEGORIA 2\", \"created_at\": \"2025-12-02T13:11:42.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:11:42.000000Z\"}', NULL, 'CATEGORÍAS', '2025-12-02', '09:11:57', '2025-12-02 13:11:57', '2025-12-02 13:11:57'),
(26, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUBCATEGORÍA', '{\"id\": 1, \"nombre\": \"SUBCATEGORIA 1\", \"created_at\": \"2025-12-02T13:16:52.000000Z\", \"updated_at\": \"2025-12-02T13:16:52.000000Z\", \"categoria_id\": 1}', NULL, 'SUBCATEGORÍAS', '2025-12-02', '09:16:52', '2025-12-02 13:16:52', '2025-12-02 13:16:52'),
(27, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SUBCATEGORÍA', '{\"id\": 1, \"nombre\": \"SUBCATEGORIA 1\", \"created_at\": \"2025-12-02T13:16:52.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:16:52.000000Z\", \"categoria_id\": 1}', '{\"id\": 1, \"nombre\": \"SUBCATEGORIA 1\", \"created_at\": \"2025-12-02T13:16:52.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:16:57.000000Z\", \"categoria_id\": 2}', 'SUBCATEGORÍAS', '2025-12-02', '09:16:57', '2025-12-02 13:16:57', '2025-12-02 13:16:57'),
(28, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUBCATEGORÍA', '{\"id\": 2, \"nombre\": \"SUBCATEGORIA 2\", \"created_at\": \"2025-12-02T13:20:44.000000Z\", \"updated_at\": \"2025-12-02T13:20:44.000000Z\", \"categoria_id\": 2}', NULL, 'SUBCATEGORÍAS', '2025-12-02', '09:20:44', '2025-12-02 13:20:44', '2025-12-02 13:20:44'),
(29, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SUBCATEGORÍA', '{\"id\": 2, \"nombre\": \"SUBCATEGORIA 2\", \"created_at\": \"2025-12-02T13:20:44.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:20:44.000000Z\", \"categoria_id\": 2}', '{\"id\": 2, \"nombre\": \"SUBCATEGORIA 2ASD\", \"created_at\": \"2025-12-02T13:20:44.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:20:50.000000Z\", \"categoria_id\": 2}', 'SUBCATEGORÍAS', '2025-12-02', '09:20:50', '2025-12-02 13:20:50', '2025-12-02 13:20:50'),
(30, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA SUBCATEGORÍA', '{\"id\": 2, \"nombre\": \"SUBCATEGORIA 2ASD\", \"created_at\": \"2025-12-02T13:20:44.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:20:50.000000Z\", \"categoria_id\": 2}', NULL, 'SUBCATEGORÍAS', '2025-12-02', '09:20:53', '2025-12-02 13:20:53', '2025-12-02 13:20:53'),
(31, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA MARCA', '{\"id\": 1, \"nombre\": \"MARCA 1\", \"created_at\": \"2025-12-02T13:21:58.000000Z\", \"updated_at\": \"2025-12-02T13:21:58.000000Z\"}', NULL, 'MARCAS', '2025-12-02', '09:21:58', '2025-12-02 13:21:58', '2025-12-02 13:21:58'),
(32, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA MARCA', '{\"id\": 2, \"nombre\": \"MARCA 2\", \"created_at\": \"2025-12-02T13:22:11.000000Z\", \"updated_at\": \"2025-12-02T13:22:11.000000Z\"}', NULL, 'MARCAS', '2025-12-02', '09:22:11', '2025-12-02 13:22:11', '2025-12-02 13:22:11'),
(33, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA MARCA', '{\"id\": 2, \"nombre\": \"MARCA 2\", \"created_at\": \"2025-12-02T13:22:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:22:11.000000Z\"}', '{\"id\": 2, \"nombre\": \"MARCA 2ASD\", \"created_at\": \"2025-12-02T13:22:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:22:29.000000Z\"}', 'MARCAS', '2025-12-02', '09:22:29', '2025-12-02 13:22:29', '2025-12-02 13:22:29'),
(34, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA MARCA', '{\"id\": 2, \"nombre\": \"MARCA 2ASD\", \"created_at\": \"2025-12-02T13:22:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:22:29.000000Z\"}', NULL, 'MARCAS', '2025-12-02', '09:22:32', '2025-12-02 13:22:32', '2025-12-02 13:22:32'),
(35, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA UNIDAD DE MEDIDA', '{\"id\": 1, \"nombre\": \"UNIDAD 1\", \"created_at\": \"2025-12-02T13:24:33.000000Z\", \"updated_at\": \"2025-12-02T13:24:33.000000Z\"}', NULL, 'UNIDAD DE MEDIDA', '2025-12-02', '09:24:33', '2025-12-02 13:24:33', '2025-12-02 13:24:33'),
(36, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA UNIDAD DE MEDIDA', '{\"id\": 2, \"nombre\": \"UNIDAD 2\", \"created_at\": \"2025-12-02T13:24:38.000000Z\", \"updated_at\": \"2025-12-02T13:24:38.000000Z\"}', NULL, 'UNIDAD DE MEDIDA', '2025-12-02', '09:24:38', '2025-12-02 13:24:38', '2025-12-02 13:24:38'),
(37, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA UNIDAD DE MEDIDA', '{\"id\": 2, \"nombre\": \"UNIDAD 2\", \"created_at\": \"2025-12-02T13:24:38.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:24:38.000000Z\"}', '{\"id\": 2, \"nombre\": \"UNIDAD 2ASD\", \"created_at\": \"2025-12-02T13:24:38.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:24:41.000000Z\"}', 'UNIDAD DE MEDIDA', '2025-12-02', '09:24:41', '2025-12-02 13:24:41', '2025-12-02 13:24:41'),
(38, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA UNIDAD DE MEDIDA', '{\"id\": 2, \"nombre\": \"UNIDAD 2ASD\", \"created_at\": \"2025-12-02T13:24:38.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:24:41.000000Z\"}', NULL, 'UNIDAD DE MEDIDA', '2025-12-02', '09:24:44', '2025-12-02 13:24:44', '2025-12-02 13:24:44'),
(39, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SUBCATEGORÍA', '{\"id\": 2, \"nombre\": \"SUBCATEGORIA 2ASD\", \"created_at\": \"2025-12-02T13:20:44.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:20:53.000000Z\", \"categoria_id\": 2}', '{\"id\": 2, \"nombre\": \"SUBCATEGORIA 2\", \"created_at\": \"2025-12-02T13:20:44.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:28:34.000000Z\", \"categoria_id\": 2}', 'SUBCATEGORÍAS', '2025-12-02', '09:28:34', '2025-12-02 13:28:34', '2025-12-02 13:28:34'),
(40, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA MARCA', '{\"id\": 2, \"nombre\": \"MARCA 2ASD\", \"created_at\": \"2025-12-02T13:22:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:22:32.000000Z\"}', '{\"id\": 2, \"nombre\": \"MARCA 2A\", \"created_at\": \"2025-12-02T13:22:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:28:51.000000Z\"}', 'MARCAS', '2025-12-02', '09:28:51', '2025-12-02 13:28:51', '2025-12-02 13:28:51'),
(41, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA MARCA', '{\"id\": 2, \"nombre\": \"MARCA 2A\", \"created_at\": \"2025-12-02T13:22:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:28:51.000000Z\"}', '{\"id\": 2, \"nombre\": \"MARCA 2\", \"created_at\": \"2025-12-02T13:22:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:28:54.000000Z\"}', 'MARCAS', '2025-12-02', '09:28:54', '2025-12-02 13:28:54', '2025-12-02 13:28:54'),
(42, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA UNIDAD DE MEDIDA', '{\"id\": 2, \"nombre\": \"UNIDAD 2ASD\", \"created_at\": \"2025-12-02T13:24:38.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:24:44.000000Z\"}', '{\"id\": 2, \"nombre\": \"UNIDAD 2A\", \"created_at\": \"2025-12-02T13:24:38.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-02T13:29:13.000000Z\"}', 'UNIDAD DE MEDIDA', '2025-12-02', '09:29:13', '2025-12-02 13:29:13', '2025-12-02 13:29:13'),
(43, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PRODUCTO', '{\"id\": 3, \"codigo\": \"P001\", \"estado\": \"1\", \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300\", \"marca_id\": \"1\", \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"updated_at\": \"2025-12-02T13:39:51.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": \"1\", \"unidades_caja\": \"20\", \"unidad_medida_id\": \"1\"}', NULL, 'PRODUCTOS', '2025-12-02', '09:39:51', '2025-12-02 13:39:51', '2025-12-02 13:39:51'),
(44, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN PRODUCTO', '{\"id\": 3, \"ppp\": 0, \"codigo\": \"P001\", \"estado\": 1, \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300.00\", \"marca_id\": 1, \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"deleted_at\": null, \"precio_ppp\": null, \"updated_at\": \"2025-12-02T13:39:51.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": 1, \"unidades_caja\": 20, \"unidad_medida_id\": 1}', '{\"id\": 3, \"ppp\": 0, \"codigo\": \"P001\", \"estado\": \"0\", \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300.00\", \"marca_id\": \"1\", \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"deleted_at\": null, \"precio_ppp\": null, \"updated_at\": \"2025-12-02T13:45:11.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": \"1\", \"unidades_caja\": \"20\", \"unidad_medida_id\": \"1\"}', 'PRODUCTOS', '2025-12-02', '09:45:11', '2025-12-02 13:45:11', '2025-12-02 13:45:11'),
(45, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN PRODUCTO', '{\"id\": 3, \"ppp\": 0, \"codigo\": \"P001\", \"estado\": 0, \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300.00\", \"marca_id\": 1, \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"deleted_at\": null, \"precio_ppp\": null, \"updated_at\": \"2025-12-02T13:45:11.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": 1, \"unidades_caja\": 20, \"unidad_medida_id\": 1}', '{\"id\": 3, \"ppp\": 0, \"codigo\": \"P001\", \"estado\": \"1\", \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300.00\", \"marca_id\": \"1\", \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"deleted_at\": null, \"precio_ppp\": null, \"updated_at\": \"2025-12-02T13:45:17.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": \"1\", \"unidades_caja\": \"20\", \"unidad_medida_id\": \"1\"}', 'PRODUCTOS', '2025-12-02', '09:45:17', '2025-12-02 13:45:17', '2025-12-02 13:45:17'),
(46, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN PRODUCTO', '{\"id\": 3, \"ppp\": 0, \"codigo\": \"P001\", \"estado\": 1, \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300.00\", \"marca_id\": 1, \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"deleted_at\": null, \"precio_ppp\": null, \"updated_at\": \"2025-12-02T13:45:17.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": 1, \"unidades_caja\": 20, \"unidad_medida_id\": 1}', '{\"id\": 3, \"ppp\": 0, \"codigo\": \"P001\", \"estado\": \"0\", \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300.00\", \"marca_id\": \"1\", \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"deleted_at\": null, \"precio_ppp\": null, \"updated_at\": \"2025-12-02T13:46:02.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": \"1\", \"unidades_caja\": \"20\", \"unidad_medida_id\": \"1\"}', 'PRODUCTOS', '2025-12-02', '09:46:02', '2025-12-02 13:46:02', '2025-12-02 13:46:02'),
(47, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN PRODUCTO', '{\"id\": 3, \"ppp\": 0, \"codigo\": \"P001\", \"estado\": 0, \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300.00\", \"marca_id\": 1, \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"deleted_at\": null, \"precio_ppp\": null, \"updated_at\": \"2025-12-02T13:46:02.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": 1, \"unidades_caja\": 20, \"unidad_medida_id\": 1}', '{\"id\": 3, \"ppp\": 0, \"codigo\": \"P001\", \"estado\": \"1\", \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300.00\", \"marca_id\": \"1\", \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"deleted_at\": null, \"precio_ppp\": null, \"updated_at\": \"2025-12-02T13:46:05.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": \"1\", \"unidades_caja\": \"20\", \"unidad_medida_id\": \"1\"}', 'PRODUCTOS', '2025-12-02', '09:46:05', '2025-12-02 13:46:05', '2025-12-02 13:46:05'),
(48, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN PRODUCTO', '{\"id\": 3, \"ppp\": 0, \"codigo\": \"P001\", \"estado\": 1, \"imagen\": \"31764682791.png\", \"nombre\": \"PRODUCTO 1\", \"precio\": \"300.00\", \"marca_id\": 1, \"created_at\": \"2025-12-02T13:39:51.000000Z\", \"deleted_at\": null, \"precio_ppp\": null, \"updated_at\": \"2025-12-02T13:46:05.000000Z\", \"descripcion\": \"DESCRIPCION\", \"categoria_id\": 1, \"unidades_caja\": 20, \"unidad_medida_id\": 1}', NULL, 'PRODUCTOS', '2025-12-02', '09:46:07', '2025-12-02 13:46:07', '2025-12-02 13:46:07'),
(49, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN CLIENTE', '{\"id\": 1, \"cel\": \"6767676767\", \"dir\": \"LOS PEDREGALES\", \"nit\": \"111111111111\", \"fono\": \"22222\", \"tipo\": \"EMPRESA\", \"ciudad\": \"LA PAZ\", \"correo\": \"juanperez@gmail.com\", \"estado\": \"1\", \"ci_prop\": \"121212121\", \"latitud\": \"111111111\", \"longitud\": \"11111111111\", \"contactos\": [{\"cel\": \"7777777\", \"fono\": \"6767676767\", \"nombre\": \"JUAN PERES\", \"observacion\": \"OBS. 1\"}, {\"cel\": \"7866786\", \"fono\": \"667567567\", \"nombre\": \"JORGE RAMIRES\", \"observacion\": null}], \"created_at\": \"2025-12-03T15:46:45.000000Z\", \"updated_at\": \"2025-12-03T15:46:45.000000Z\", \"nombre_prop\": \"JUAN PEREZ\", \"nombre_punto\": \"PUNTO VENTA C 1\", \"razon_social\": \"CLIENTE 1 S.A.\"}', NULL, 'CLIENTES', '2025-12-03', '11:46:45', '2025-12-03 15:46:45', '2025-12-03 15:46:45'),
(50, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN CLIENTE', '{\"id\": 1, \"cel\": \"6767676767\", \"dir\": \"LOS PEDREGALES\", \"nit\": \"111111111111\", \"fono\": \"22222\", \"tipo\": \"EMPRESA\", \"ciudad\": \"LA PAZ\", \"correo\": \"juanperez@gmail.com\", \"estado\": 1, \"ci_prop\": \"121212121\", \"latitud\": \"111111111\", \"longitud\": \"11111111111\", \"contactos\": [{\"cel\": \"7777777\", \"fono\": \"6767676767\", \"nombre\": \"JUAN PERES\", \"observacion\": \"OBS. 1\"}, {\"cel\": \"7866786\", \"fono\": \"667567567\", \"nombre\": \"JORGE RAMIRES\", \"observacion\": null}], \"created_at\": \"2025-12-03T15:46:45.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-03T15:46:45.000000Z\", \"nombre_prop\": \"JUAN PEREZ\", \"nombre_punto\": \"PUNTO VENTA C 1\", \"razon_social\": \"CLIENTE 1 S.A.\"}', '{\"id\": 1, \"cel\": \"6767676767\", \"dir\": \"LOS PEDREGALES\", \"nit\": \"111111111111\", \"fono\": \"22222\", \"tipo\": \"EMPRESA\", \"ciudad\": \"LA PAZ\", \"correo\": \"juanperez@gmail.com\", \"estado\": \"1\", \"ci_prop\": \"121212121\", \"latitud\": \"111111111\", \"longitud\": \"11111111111\", \"contactos\": [{\"cel\": \"7777777\", \"fono\": \"6767676767\", \"nombre\": \"JUAN GONZALES\", \"observacion\": \"OBS. CONTACTO 1\"}, {\"cel\": \"7866786\", \"fono\": \"667567567\", \"nombre\": \"JORGE RAMIRES\", \"observacion\": null}], \"created_at\": \"2025-12-03T15:46:45.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-03T15:50:13.000000Z\", \"nombre_prop\": \"JUAN PEREZ\", \"nombre_punto\": \"PUNTO VENTA C 1\", \"razon_social\": \"CLIENTE 1 S.A.\"}', 'CLIENTES', '2025-12-03', '11:50:13', '2025-12-03 15:50:13', '2025-12-03 15:50:13'),
(51, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN CLIENTE', '{\"id\": 1, \"cel\": \"6767676767\", \"dir\": \"LOS PEDREGALES\", \"nit\": \"111111111111\", \"fono\": \"22222\", \"tipo\": \"EMPRESA\", \"ciudad\": \"LA PAZ\", \"correo\": \"juanperez@gmail.com\", \"estado\": 1, \"ci_prop\": \"121212121\", \"latitud\": \"111111111\", \"longitud\": \"11111111111\", \"contactos\": [{\"cel\": \"7777777\", \"fono\": \"6767676767\", \"nombre\": \"JUAN GONZALES\", \"observacion\": \"OBS. CONTACTO 1\"}, {\"cel\": \"7866786\", \"fono\": \"667567567\", \"nombre\": \"JORGE RAMIRES\", \"observacion\": null}], \"created_at\": \"2025-12-03T15:46:45.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-03T15:50:13.000000Z\", \"nombre_prop\": \"JUAN PEREZ\", \"nombre_punto\": \"PUNTO VENTA C 1\", \"razon_social\": \"CLIENTE 1 S.A.\"}', '{\"id\": 1, \"cel\": \"6767676767\", \"dir\": \"LOS PEDREGALES\", \"nit\": \"111111111111\", \"fono\": \"22222\", \"tipo\": \"EMPRESA\", \"ciudad\": \"LA PAZ\", \"correo\": \"juanperez@gmail.com\", \"estado\": \"0\", \"ci_prop\": \"121212121\", \"latitud\": \"111111111\", \"longitud\": \"11111111111\", \"contactos\": [{\"cel\": \"7777777\", \"fono\": \"6767676767\", \"nombre\": \"JUAN GONZALES\", \"observacion\": \"OBS. CONTACTO 1\"}, {\"cel\": \"7866786\", \"fono\": \"667567567\", \"nombre\": \"JORGE RAMIRES\", \"observacion\": null}], \"created_at\": \"2025-12-03T15:46:45.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-03T15:50:30.000000Z\", \"nombre_prop\": \"JUAN PEREZ\", \"nombre_punto\": \"PUNTO VENTA C 1\", \"razon_social\": \"CLIENTE 1 S.A.\"}', 'CLIENTES', '2025-12-03', '11:50:30', '2025-12-03 15:50:30', '2025-12-03 15:50:30'),
(52, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN CLIENTE', '{\"id\": 1, \"cel\": \"6767676767\", \"dir\": \"LOS PEDREGALES\", \"nit\": \"111111111111\", \"fono\": \"22222\", \"tipo\": \"EMPRESA\", \"ciudad\": \"LA PAZ\", \"correo\": \"juanperez@gmail.com\", \"estado\": 0, \"ci_prop\": \"121212121\", \"latitud\": \"111111111\", \"longitud\": \"11111111111\", \"contactos\": [{\"cel\": \"7777777\", \"fono\": \"6767676767\", \"nombre\": \"JUAN GONZALES\", \"observacion\": \"OBS. CONTACTO 1\"}, {\"cel\": \"7866786\", \"fono\": \"667567567\", \"nombre\": \"JORGE RAMIRES\", \"observacion\": null}], \"created_at\": \"2025-12-03T15:46:45.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-03T15:50:30.000000Z\", \"nombre_prop\": \"JUAN PEREZ\", \"nombre_punto\": \"PUNTO VENTA C 1\", \"razon_social\": \"CLIENTE 1 S.A.\"}', '{\"id\": 1, \"cel\": \"6767676767\", \"dir\": \"LOS PEDREGALES\", \"nit\": \"111111111111\", \"fono\": \"22222\", \"tipo\": \"EMPRESA\", \"ciudad\": \"LA PAZ\", \"correo\": \"juanperez@gmail.com\", \"estado\": \"1\", \"ci_prop\": \"121212121\", \"latitud\": \"111111111\", \"longitud\": \"11111111111\", \"contactos\": [{\"cel\": \"7777777\", \"fono\": \"6767676767\", \"nombre\": \"JUAN GONZALES\", \"observacion\": \"OBS. CONTACTO 1\"}, {\"cel\": \"7866786\", \"fono\": \"667567567\", \"nombre\": \"JORGE RAMIRES\", \"observacion\": null}], \"created_at\": \"2025-12-03T15:46:45.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-03T15:51:27.000000Z\", \"nombre_prop\": \"JUAN PEREZ\", \"nombre_punto\": \"PUNTO VENTA C 1\", \"razon_social\": \"CLIENTE 1 S.A.\"}', 'CLIENTES', '2025-12-03', '11:51:27', '2025-12-03 15:51:27', '2025-12-03 15:51:27'),
(53, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN CLIENTE', '{\"id\": 2, \"cel\": \"657756\", \"dir\": \"LOS PEDREAGLES1\", \"nit\": \"1111111111111\", \"fono\": \"222\", \"tipo\": \"PERSONA\", \"ciudad\": \"EL ALTO\", \"correo\": null, \"estado\": \"1\", \"ci_prop\": \"23123123\", \"latitud\": \"111\", \"longitud\": \"2222\", \"contactos\": [{\"cel\": \"6757567\", \"fono\": \"222222\", \"nombre\": \"CONTACTO 1\", \"observacion\": null}], \"created_at\": \"2025-12-03T15:52:12.000000Z\", \"updated_at\": \"2025-12-03T15:52:12.000000Z\", \"nombre_prop\": \"MARIA MAMANI\", \"nombre_punto\": \"CLIENTE 2 PV\", \"razon_social\": \"CLIENTE 2\"}', NULL, 'CLIENTES', '2025-12-03', '11:52:12', '2025-12-03 15:52:12', '2025-12-03 15:52:12'),
(54, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN CLIENTE', '{\"id\": 2, \"cel\": \"657756\", \"dir\": \"LOS PEDREAGLES1\", \"nit\": \"1111111111111\", \"fono\": \"222\", \"tipo\": \"PERSONA\", \"ciudad\": \"EL ALTO\", \"correo\": null, \"estado\": 1, \"ci_prop\": \"23123123\", \"latitud\": \"111\", \"longitud\": \"2222\", \"contactos\": [{\"cel\": \"6757567\", \"fono\": \"222222\", \"nombre\": \"CONTACTO 1\", \"observacion\": null}], \"created_at\": \"2025-12-03T15:52:12.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-03T15:52:12.000000Z\", \"nombre_prop\": \"MARIA MAMANI\", \"nombre_punto\": \"CLIENTE 2 PV\", \"razon_social\": \"CLIENTE 2\"}', NULL, 'CLIENTES', '2025-12-03', '11:52:38', '2025-12-03 15:52:38', '2025-12-03 15:52:38'),
(55, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PROVEEDOR', '{\"id\": 1, \"dir\": \"LOS OLIVOS #22\", \"nit\": \"11111111\", \"tipo\": \"PRODUCTOS\", \"ciudad\": \"LA PAZ\", \"correo\": \"proveedor1@gmail.com\", \"estado\": 1, \"marcas\": [1], \"moneda\": \"bolivianos\", \"fono_emp\": \"222222\", \"contactos\": [{\"cel\": \"67676767\", \"fono\": \"74454545\", \"nombre\": \"EDUARDO PEREZ\", \"observacion\": null}], \"categorias\": [1, 2], \"created_at\": \"2025-12-03T16:11:34.000000Z\", \"nombre_com\": \"PROVEEDOR S.A.\", \"updated_at\": \"2025-12-03T16:11:34.000000Z\", \"razon_social\": \"PROVEEDOR 1 S.A.\", \"observaciones\": \"OBSERVACIONES\"}', NULL, 'PROVEEDORES', '2025-12-03', '12:11:34', '2025-12-03 16:11:34', '2025-12-03 16:11:34'),
(56, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN PROVEEDOR', '{\"id\": 1, \"dir\": \"LOS OLIVOS #22\", \"nit\": \"11111111\", \"tipo\": \"PRODUCTOS\", \"ciudad\": \"LA PAZ\", \"correo\": \"proveedor1@gmail.com\", \"estado\": 1, \"marcas\": [1], \"moneda\": \"bolivianos\", \"fono_emp\": \"222222\", \"contactos\": [{\"cel\": \"67676767\", \"fono\": \"74454545\", \"nombre\": \"EDUARDO PEREZ\", \"observacion\": null}], \"categorias\": [1, 2], \"created_at\": \"2025-12-03T16:11:34.000000Z\", \"deleted_at\": null, \"nombre_com\": \"PROVEEDOR S.A.\", \"updated_at\": \"2025-12-03T16:11:34.000000Z\", \"razon_social\": \"PROVEEDOR 1 S.A.\", \"observaciones\": \"OBSERVACIONES\"}', '{\"id\": 1, \"dir\": \"LOS OLIVOS #22\", \"nit\": \"11111111\", \"tipo\": \"PRODUCTOS\", \"ciudad\": \"LA PAZ\", \"correo\": \"proveedor1@gmail.com\", \"estado\": 1, \"marcas\": [1], \"moneda\": \"bolivianos\", \"fono_emp\": \"222222\", \"contactos\": [{\"cel\": \"67676767\", \"fono\": \"74454545\", \"nombre\": \"EDUARDO PEREZ\", \"observacion\": null}], \"categorias\": [1, 2], \"created_at\": \"2025-12-03T16:11:34.000000Z\", \"deleted_at\": null, \"nombre_com\": \"\", \"updated_at\": \"2025-12-03T16:15:58.000000Z\", \"razon_social\": \"PROVEEDOR 1 S.A.\", \"observaciones\": \"OBSERVACIONES\"}', 'PROVEEDORES', '2025-12-03', '12:15:58', '2025-12-03 16:15:58', '2025-12-03 16:15:58'),
(57, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN PROVEEDOR', '{\"id\": 1, \"dir\": \"LOS OLIVOS #22\", \"nit\": \"11111111\", \"tipo\": \"PRODUCTOS\", \"ciudad\": \"LA PAZ\", \"correo\": \"proveedor1@gmail.com\", \"estado\": 1, \"marcas\": [1], \"moneda\": \"bolivianos\", \"fono_emp\": \"222222\", \"contactos\": [{\"cel\": \"67676767\", \"fono\": \"74454545\", \"nombre\": \"EDUARDO PEREZ\", \"observacion\": null}], \"categorias\": [1, 2], \"created_at\": \"2025-12-03T16:11:34.000000Z\", \"deleted_at\": null, \"nombre_com\": \"\", \"updated_at\": \"2025-12-03T16:15:58.000000Z\", \"razon_social\": \"PROVEEDOR 1 S.A.\", \"observaciones\": \"OBSERVACIONES\"}', '{\"id\": 1, \"dir\": \"LOS OLIVOS #22\", \"nit\": \"11111111\", \"tipo\": \"PRODUCTOS\", \"ciudad\": \"LA PAZ\", \"correo\": \"proveedor1@gmail.com\", \"estado\": 1, \"marcas\": [1], \"moneda\": \"bolivianos\", \"fono_emp\": \"222222\", \"contactos\": [{\"cel\": \"67676767\", \"fono\": \"74454545\", \"nombre\": \"EDUARDO PEREZ\", \"observacion\": null}], \"categorias\": [1, 2], \"created_at\": \"2025-12-03T16:11:34.000000Z\", \"deleted_at\": null, \"nombre_com\": \"PROVEEDOR S.A.\", \"updated_at\": \"2025-12-03T16:16:02.000000Z\", \"razon_social\": \"PROVEEDOR 1 S.A.\", \"observaciones\": \"OBSERVACIONES\"}', 'PROVEEDORES', '2025-12-03', '12:16:02', '2025-12-03 16:16:02', '2025-12-03 16:16:02'),
(58, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PROVEEDOR', '{\"id\": 2, \"dir\": \"LOS OLIVOS #23\", \"nit\": \"121231\", \"tipo\": \"MIXTO\", \"ciudad\": \"EL ALTO\", \"correo\": \"prove2@gmail.com\", \"estado\": 1, \"marcas\": [2], \"moneda\": \"boliviano\", \"fono_emp\": \"234234234\", \"contactos\": [{\"cel\": \"7878787878\", \"fono\": \"MAMANI\", \"nombre\": \"JUAN\", \"observacion\": null}], \"categorias\": [2], \"created_at\": \"2025-12-03T16:16:35.000000Z\", \"nombre_com\": \"\", \"updated_at\": \"2025-12-03T16:16:35.000000Z\", \"razon_social\": \"PROVEEDOR 2 S.R.L\", \"observaciones\": \"\"}', NULL, 'PROVEEDORES', '2025-12-03', '12:16:35', '2025-12-03 16:16:35', '2025-12-03 16:16:35');
INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(59, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PRODUCTO', '{\"id\": 4, \"codigo\": \"P002\", \"estado\": \"1\", \"nombre\": \"PRODUCTO 2\", \"precio\": \"345\", \"marca_id\": \"2\", \"created_at\": \"2025-12-04T22:16:21.000000Z\", \"updated_at\": \"2025-12-04T22:16:21.000000Z\", \"descripcion\": \"\", \"categoria_id\": \"2\", \"unidades_caja\": \"20\", \"unidad_medida_id\": \"2\"}', NULL, 'PRODUCTOS', '2025-12-04', '18:16:21', '2025-12-04 22:16:21', '2025-12-04 22:16:21'),
(60, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SOLICITUD DE INGRESO', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": 4950, \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": 0, \"user_id\": 1, \"hora_sis\": \"18:59\", \"fecha_sis\": \"2025-12-04\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"descripcion\": \"\", \"tipo_cambio\": 6.98, \"hora_ingreso\": \"18:48\", \"proveedor_id\": \"1\", \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"\", \"cantidad_total\": 15}', NULL, 'SOLICITUD DE INGRESO', '2025-12-04', '18:59:25', '2025-12-04 22:59:25', '2025-12-04 22:59:25'),
(61, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SOLICITUD DE INGRESO', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"4950.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"18:59:00\", \"fecha_sis\": \"2025-12-04\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"descripcion\": \"\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": 1, \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"\", \"cantidad_total\": 15, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 5, \"subtotal\": \"1500.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 5, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"4950.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"19:12\", \"fecha_sis\": \"2025-12-04\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-04T23:12:21.000000Z\", \"descripcion\": \"\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": \"1\", \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"\", \"cantidad_total\": 15, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 5, \"subtotal\": \"1500.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 5, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', 'SOLICITUD DE INGRESO', '2025-12-04', '19:12:21', '2025-12-04 23:12:21', '2025-12-04 23:12:21'),
(62, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SOLICITUD DE INGRESO', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"4950.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"19:12:00\", \"fecha_sis\": \"2025-12-04\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-04T23:12:21.000000Z\", \"descripcion\": \"\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": 1, \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"\", \"cantidad_total\": 15, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 5, \"subtotal\": \"1500.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 5, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"4950.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"19:12\", \"fecha_sis\": \"2025-12-04\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-04T23:12:43.000000Z\", \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": \"1\", \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 15, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 5, \"subtotal\": \"1500.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 5, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', 'SOLICITUD DE INGRESO', '2025-12-04', '19:12:43', '2025-12-04 23:12:43', '2025-12-04 23:12:43'),
(63, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SOLICITUD DE INGRESO', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"4950.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"19:12:00\", \"fecha_sis\": \"2025-12-04\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-04T23:12:43.000000Z\", \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": 1, \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 15, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 5, \"subtotal\": \"1500.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 5, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": 6450, \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"19:12\", \"fecha_sis\": \"2025-12-04\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-04T23:12:52.000000Z\", \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": \"1\", \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 20, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3000.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T23:12:52.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', 'SOLICITUD DE INGRESO', '2025-12-04', '19:12:52', '2025-12-04 23:12:52', '2025-12-04 23:12:52'),
(64, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA SOLICITUD DE INGRESO', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"6450.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"19:12:00\", \"fecha_sis\": \"2025-12-04\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-04T23:12:52.000000Z\", \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": 1, \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 20, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3000.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T23:12:52.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', NULL, 'SOLICITUD DE INGRESO', '2025-12-04', '19:13:17', '2025-12-04 23:13:17', '2025-12-04 23:13:17'),
(65, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA SOLICITUD DE INGRESO', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"6450.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"19:12:00\", \"fecha_sis\": \"2025-12-04\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-04T23:13:17.000000Z\", \"verificado\": 0, \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": 1, \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 20, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3000.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T23:12:52.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"6450.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"09:46\", \"fecha_sis\": \"2025-12-05\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T13:46:40.000000Z\", \"verificado\": 0, \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": \"1\", \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 20, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3000.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T23:12:52.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-04T22:59:25.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', 'SOLICITUD DE INGRESO', '2025-12-05', '09:46:40', '2025-12-05 13:46:40', '2025-12-05 13:46:40'),
(66, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA SOLICITUD DE INGRESO', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"6450.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"09:46:00\", \"fecha_sis\": \"2025-12-05\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T13:46:40.000000Z\", \"verificado\": 0, \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": 1, \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 20, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3000.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-05T13:48:43.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-05T13:48:43.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"6450.00\", \"codigo\": \"SOL.1\", \"estado\": \"APROBADO\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"09:46:00\", \"fecha_sis\": \"2025-12-05\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T14:12:16.000000Z\", \"verificado\": 1, \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": 1, \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 20, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3000.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-05T13:48:43.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-05T13:48:43.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', 'SOLICITUD DE INGRESO', '2025-12-05', '10:12:16', '2025-12-05 14:12:16', '2025-12-05 14:12:16'),
(67, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SOLICITUD DE INGRESO', '{\"id\": 2, \"nro\": 2, \"cs_f\": \"SIN FATURA\", \"total\": 10500, \"codigo\": \"SOL.2\", \"estado\": \"PENDIENTE\", \"gastos\": 0, \"user_id\": 1, \"hora_sis\": \"10:15\", \"fecha_sis\": \"2025-12-05\", \"created_at\": \"2025-12-05T14:15:19.000000Z\", \"updated_at\": \"2025-12-05T14:15:19.000000Z\", \"descripcion\": \"\", \"tipo_cambio\": 6.98, \"hora_ingreso\": \"10:15\", \"proveedor_id\": \"2\", \"fecha_ingreso\": \"2025-12-05\", \"observaciones\": \"\", \"cantidad_total\": 35}', NULL, 'SOLICITUD DE INGRESO', '2025-12-05', '10:15:19', '2025-12-05 14:15:19', '2025-12-05 14:15:19'),
(68, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUCURSAL', '{\"id\": 2, \"fono\": \"43535335\", \"correo\": null, \"estado\": 1, \"nombre\": \"SUCURSAL 1\", \"user_id\": 15, \"direccion\": \"LOS OLIVOS\", \"created_at\": \"2025-12-05T14:26:18.000000Z\", \"updated_at\": \"2025-12-05T14:26:18.000000Z\"}', NULL, 'SUCURSALES', '2025-12-05', '10:26:18', '2025-12-05 14:26:18', '2025-12-05 14:26:18'),
(69, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SUCURSAL', '{\"id\": 3, \"fono\": \"7877878\", \"correo\": null, \"estado\": 1, \"nombre\": \"SUCURSAL 2\", \"user_id\": 16, \"direccion\": \"DIR SUC 2\", \"created_at\": \"2025-12-05T14:27:08.000000Z\", \"updated_at\": \"2025-12-05T14:27:08.000000Z\"}', NULL, 'SUCURSALES', '2025-12-05', '10:27:08', '2025-12-05 14:27:08', '2025-12-05 14:27:08'),
(70, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA SOLICITUD DE INGRESO', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"6450.00\", \"codigo\": \"SOL.1\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"09:46:00\", \"fecha_sis\": \"2025-12-05\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T14:12:16.000000Z\", \"verificado\": 0, \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": 1, \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 20, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3000.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-05T13:48:43.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-05T13:48:43.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', '{\"id\": 1, \"nro\": 1, \"cs_f\": \"CON FATURA\", \"total\": \"6450.00\", \"codigo\": \"SOL.1\", \"estado\": \"APROBADO\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"09:46:00\", \"fecha_sis\": \"2025-12-05\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T15:07:00.000000Z\", \"verificado\": 1, \"descripcion\": \"DESC\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"18:48:00\", \"proveedor_id\": 1, \"fecha_ingreso\": \"2025-12-04\", \"observaciones\": \"OBS\", \"cantidad_total\": 20, \"solicitud_ingreso_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3000.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-05T13:48:43.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 10, \"subtotal\": \"3450.00\", \"created_at\": \"2025-12-04T22:59:25.000000Z\", \"updated_at\": \"2025-12-05T13:48:43.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 10, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 1}]}', 'SOLICITUD DE INGRESO', '2025-12-05', '11:07:00', '2025-12-05 15:07:00', '2025-12-05 15:07:00'),
(71, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN PRODUCTO DE SUCURSAL', '{\"id\": 1, \"created_at\": \"2025-12-05T15:07:00.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T15:07:00.000000Z\", \"producto_id\": 3, \"sucursal_id\": 1, \"stock_actual\": 10, \"cantidad_ideal\": 0, \"cantidad_minima\": 0}', '{\"id\": 1, \"created_at\": \"2025-12-05T15:07:00.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T16:14:51.000000Z\", \"producto_id\": 3, \"sucursal_id\": 1, \"stock_actual\": 10, \"cantidad_ideal\": 5, \"cantidad_minima\": 5}', 'SUCURSAL PRODUCTO', '2025-12-05', '12:14:51', '2025-12-05 16:14:51', '2025-12-05 16:14:51'),
(72, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN PRODUCTO DE SUCURSAL', '{\"id\": 4, \"created_at\": \"2025-12-05T16:08:14.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T16:08:14.000000Z\", \"producto_id\": 3, \"sucursal_id\": 2, \"stock_actual\": 0, \"cantidad_ideal\": 0, \"cantidad_minima\": 0}', '{\"id\": 4, \"created_at\": \"2025-12-05T16:08:14.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T16:14:57.000000Z\", \"producto_id\": 3, \"sucursal_id\": 2, \"stock_actual\": 0, \"cantidad_ideal\": 3, \"cantidad_minima\": 3}', 'SUCURSAL PRODUCTO', '2025-12-05', '12:14:57', '2025-12-05 16:14:57', '2025-12-05 16:14:57'),
(73, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN PRODUCTO DE SUCURSAL', '{\"id\": 5, \"created_at\": \"2025-12-05T16:14:58.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T16:14:58.000000Z\", \"producto_id\": 4, \"sucursal_id\": 2, \"stock_actual\": 0, \"cantidad_ideal\": 0, \"cantidad_minima\": 0}', '{\"id\": 5, \"created_at\": \"2025-12-05T16:14:58.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T16:15:00.000000Z\", \"producto_id\": 4, \"sucursal_id\": 2, \"stock_actual\": 0, \"cantidad_ideal\": 2, \"cantidad_minima\": 2}', 'SUCURSAL PRODUCTO', '2025-12-05', '12:15:00', '2025-12-05 16:15:00', '2025-12-05 16:15:00'),
(74, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE SALIDA', '{\"id\": 1, \"nro\": 1, \"hora\": \"10:15\", \"fecha\": \"2025-12-06\", \"total\": 1635, \"codigo\": \"SAL.1\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-06T14:15:14.000000Z\", \"updated_at\": \"2025-12-06T14:15:14.000000Z\", \"sucursal_id\": 2, \"observaciones\": \"OBSERVACIONES\", \"cantidad_total\": 5}', NULL, 'ORDEN DE SALIDA', '2025-12-06', '10:15:14', '2025-12-06 14:15:14', '2025-12-06 14:15:14'),
(75, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA ORDEN DE SALIDA', '{\"id\": 1, \"nro\": 1, \"hora\": \"10:15:00\", \"fecha\": \"2025-12-06\", \"total\": \"1635.00\", \"codigo\": \"SAL.1\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-06T14:15:14.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:15:14.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"OBSERVACIONES\", \"cantidad_total\": 5, \"orden_salida_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 2, \"subtotal\": \"600.00\", \"created_at\": \"2025-12-06T14:15:14.000000Z\", \"updated_at\": \"2025-12-06T14:15:14.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 2, \"orden_salida_id\": 1, \"sucursal_ajuste\": null}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-06T14:15:14.000000Z\", \"updated_at\": \"2025-12-06T14:15:14.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 3, \"orden_salida_id\": 1, \"sucursal_ajuste\": null}]}', '{\"id\": 1, \"nro\": 1, \"hora\": \"10:15:00\", \"fecha\": \"2025-12-06\", \"total\": \"1635.00\", \"codigo\": \"SAL.1\", \"estado\": \"APROBADO\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-06T14:15:14.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:28:41.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"observaciones\": \"OBSERVACIONES\", \"cantidad_total\": 5, \"orden_salida_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 2, \"subtotal\": \"600.00\", \"created_at\": \"2025-12-06T14:15:14.000000Z\", \"updated_at\": \"2025-12-06T14:15:14.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 2, \"orden_salida_id\": 1, \"sucursal_ajuste\": null}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-06T14:15:14.000000Z\", \"updated_at\": \"2025-12-06T14:15:14.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 3, \"orden_salida_id\": 1, \"sucursal_ajuste\": null}]}', 'ORDEN DE SALIDA', '2025-12-06', '10:28:41', '2025-12-06 14:28:41', '2025-12-06 14:28:41'),
(76, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE SALIDA', '{\"id\": 2, \"nro\": 2, \"hora\": \"10:30\", \"fecha\": \"2025-12-06\", \"total\": 1200, \"codigo\": \"SAL.2\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_id\": 1, \"user_sol\": 16, \"created_at\": \"2025-12-06T14:30:25.000000Z\", \"updated_at\": \"2025-12-06T14:30:25.000000Z\", \"sucursal_id\": 3, \"observaciones\": \"OBS\", \"cantidad_total\": 4}', NULL, 'ORDEN DE SALIDA', '2025-12-06', '10:30:25', '2025-12-06 14:30:25', '2025-12-06 14:30:25'),
(77, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA ORDEN DE SALIDA', '{\"id\": 2, \"nro\": 2, \"hora\": \"10:30:00\", \"fecha\": \"2025-12-06\", \"total\": \"1200.00\", \"codigo\": \"SAL.2\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_id\": 1, \"user_sol\": 16, \"created_at\": \"2025-12-06T14:30:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:30:25.000000Z\", \"verificado\": 0, \"sucursal_id\": 3, \"observaciones\": \"OBS\", \"cantidad_total\": 4, \"orden_salida_detalles\": [{\"id\": 3, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 4, \"subtotal\": \"1200.00\", \"created_at\": \"2025-12-06T14:30:25.000000Z\", \"updated_at\": \"2025-12-06T14:30:25.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 4, \"orden_salida_id\": 2, \"sucursal_ajuste\": null}]}', '{\"id\": 2, \"nro\": 2, \"hora\": \"10:30:00\", \"fecha\": \"2025-12-06\", \"total\": \"1200.00\", \"codigo\": \"SAL.2\", \"estado\": \"APROBADO\", \"user_ap\": 16, \"user_id\": 1, \"user_sol\": 16, \"created_at\": \"2025-12-06T14:30:25.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:30:40.000000Z\", \"verificado\": 1, \"sucursal_id\": 3, \"observaciones\": \"OBS\", \"cantidad_total\": 4, \"orden_salida_detalles\": [{\"id\": 3, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 4, \"subtotal\": \"1200.00\", \"created_at\": \"2025-12-06T14:30:25.000000Z\", \"updated_at\": \"2025-12-06T14:30:40.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 4, \"orden_salida_id\": 2, \"sucursal_ajuste\": null}]}', 'ORDEN DE SALIDA', '2025-12-06', '10:30:40', '2025-12-06 14:30:40', '2025-12-06 14:30:40'),
(78, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE SALIDA', '{\"id\": 3, \"nro\": 3, \"hora\": \"10:31\", \"fecha\": \"2025-12-06\", \"total\": 345, \"codigo\": \"SAL.3\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_id\": 1, \"user_sol\": 16, \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"updated_at\": \"2025-12-06T14:31:13.000000Z\", \"sucursal_id\": 3, \"observaciones\": \"\", \"cantidad_total\": 1}', NULL, 'ORDEN DE SALIDA', '2025-12-06', '10:31:13', '2025-12-06 14:31:13', '2025-12-06 14:31:13'),
(79, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA ORDEN DE SALIDA', '{\"id\": 3, \"nro\": 3, \"hora\": \"10:31:00\", \"fecha\": \"2025-12-06\", \"total\": \"345.00\", \"codigo\": \"SAL.3\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_id\": 1, \"user_sol\": 16, \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:31:13.000000Z\", \"verificado\": 0, \"sucursal_id\": 3, \"observaciones\": \"\", \"cantidad_total\": 1, \"orden_salida_detalles\": [{\"id\": 4, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 1, \"subtotal\": \"345.00\", \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"updated_at\": \"2025-12-06T14:31:13.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 1, \"orden_salida_id\": 3, \"sucursal_ajuste\": null}]}', NULL, 'ORDEN DE SALIDA', '2025-12-06', '10:31:15', '2025-12-06 14:31:15', '2025-12-06 14:31:15'),
(80, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA ORDEN DE SALIDA', '{\"id\": 3, \"nro\": 3, \"hora\": \"10:31:00\", \"fecha\": \"2025-12-06\", \"total\": \"345.00\", \"codigo\": \"SAL.3\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_id\": 1, \"user_sol\": 16, \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:31:15.000000Z\", \"verificado\": 0, \"sucursal_id\": 3, \"observaciones\": \"\", \"cantidad_total\": 1, \"orden_salida_detalles\": [{\"id\": 4, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 1, \"subtotal\": \"345.00\", \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"updated_at\": \"2025-12-06T14:31:13.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 1, \"orden_salida_id\": 3, \"sucursal_ajuste\": null}]}', '{\"id\": 3, \"nro\": 3, \"hora\": \"10:31\", \"fecha\": \"2025-12-06\", \"total\": 1035, \"codigo\": \"SAL.3\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_id\": 1, \"user_sol\": 16, \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:31:29.000000Z\", \"verificado\": 0, \"sucursal_id\": 3, \"observaciones\": \"\", \"cantidad_total\": 3, \"orden_salida_detalles\": [{\"id\": 4, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"updated_at\": \"2025-12-06T14:31:29.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 3, \"orden_salida_id\": 3, \"sucursal_ajuste\": null}]}', 'ORDEN DE SALIDA', '2025-12-06', '10:31:29', '2025-12-06 14:31:29', '2025-12-06 14:31:29'),
(81, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA ORDEN DE SALIDA', '{\"id\": 3, \"nro\": 3, \"hora\": \"10:31:00\", \"fecha\": \"2025-12-06\", \"total\": \"1035.00\", \"codigo\": \"SAL.3\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_id\": 1, \"user_sol\": 16, \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:31:29.000000Z\", \"verificado\": 0, \"sucursal_id\": 3, \"observaciones\": \"\", \"cantidad_total\": 3, \"orden_salida_detalles\": [{\"id\": 4, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"updated_at\": \"2025-12-06T14:31:29.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 3, \"orden_salida_id\": 3, \"sucursal_ajuste\": null}]}', '{\"id\": 3, \"nro\": 3, \"hora\": \"10:31:00\", \"fecha\": \"2025-12-06\", \"total\": \"1035.00\", \"codigo\": \"SAL.3\", \"estado\": \"APROBADO\", \"user_ap\": 16, \"user_id\": 1, \"user_sol\": 16, \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:31:33.000000Z\", \"verificado\": 1, \"sucursal_id\": 3, \"observaciones\": \"\", \"cantidad_total\": 3, \"orden_salida_detalles\": [{\"id\": 4, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-06T14:31:13.000000Z\", \"updated_at\": \"2025-12-06T14:31:33.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 3, \"orden_salida_id\": 3, \"sucursal_ajuste\": null}]}', 'ORDEN DE SALIDA', '2025-12-06', '10:31:33', '2025-12-06 14:31:33', '2025-12-06 14:31:33'),
(82, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE SALIDA', '{\"id\": 4, \"nro\": 4, \"hora\": \"10:58\", \"fecha\": \"2025-12-06\", \"total\": 300, \"codigo\": \"SAL.4\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-06T14:58:18.000000Z\", \"updated_at\": \"2025-12-06T14:58:18.000000Z\", \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 1}', NULL, 'ORDEN DE SALIDA', '2025-12-06', '10:58:18', '2025-12-06 14:58:18', '2025-12-06 14:58:18'),
(83, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA ORDEN DE SALIDA', '{\"id\": 4, \"nro\": 4, \"hora\": \"10:58:00\", \"fecha\": \"2025-12-06\", \"total\": \"300.00\", \"codigo\": \"SAL.4\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-06T14:58:18.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:58:18.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 1, \"orden_salida_detalles\": [{\"id\": 5, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 1, \"subtotal\": \"300.00\", \"created_at\": \"2025-12-06T14:58:18.000000Z\", \"updated_at\": \"2025-12-06T14:58:18.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 1, \"orden_salida_id\": 4, \"sucursal_ajuste\": null}]}', '{\"id\": 4, \"nro\": 4, \"hora\": \"10:58:00\", \"fecha\": \"2025-12-06\", \"total\": \"300.00\", \"codigo\": \"SAL.4\", \"estado\": \"APROBADO\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-06T14:58:18.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T14:58:25.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 1, \"orden_salida_detalles\": [{\"id\": 5, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 1, \"subtotal\": \"300.00\", \"created_at\": \"2025-12-06T14:58:18.000000Z\", \"updated_at\": \"2025-12-06T14:58:25.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 1, \"orden_salida_id\": 4, \"sucursal_ajuste\": null}]}', 'ORDEN DE SALIDA', '2025-12-06', '10:58:25', '2025-12-06 14:58:25', '2025-12-06 14:58:25'),
(84, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA DEVOLUCIÓN DE STOCK', '{\"id\": 2, \"nro\": 1, \"hora\": \"11:08\", \"fecha\": \"2025-12-06\", \"total\": 645, \"codigo\": \"DEV.1\", \"estado\": \"PENDIENTE\", \"total_v\": 645, \"user_id\": 1, \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"updated_at\": \"2025-12-06T15:14:42.000000Z\", \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 2, \"cantidad_total_v\": 2}', NULL, 'DEVOLUCIÓN DE STOCK', '2025-12-06', '11:14:42', '2025-12-06 15:14:42', '2025-12-06 15:14:42'),
(85, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA DEVOLUCIÓN DE STOCK', '{\"id\": 2, \"nro\": 1, \"hora\": \"11:08:00\", \"fecha\": \"2025-12-06\", \"total\": \"645.00\", \"codigo\": \"DEV.1\", \"estado\": \"PENDIENTE\", \"total_v\": \"645.00\", \"user_id\": 1, \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T15:14:42.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 2, \"cantidad_total_v\": 2, \"devolucion_stock_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 1, \"subtotal\": \"300.00\", \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"updated_at\": \"2025-12-06T15:14:42.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 1, \"sucursal_ajuste\": null, \"devolucion_stock_id\": 2}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 1, \"subtotal\": \"345.00\", \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"updated_at\": \"2025-12-06T15:14:42.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 1, \"sucursal_ajuste\": null, \"devolucion_stock_id\": 2}]}', '{\"id\": 2, \"nro\": 1, \"hora\": \"11:08:00\", \"fecha\": \"2025-12-06\", \"total\": 1935, \"codigo\": \"DEV.1\", \"estado\": \"PENDIENTE\", \"total_v\": 1935, \"user_id\": 1, \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T15:18:47.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 6, \"cantidad_total_v\": 6, \"devolucion_stock_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"900.00\", \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"updated_at\": \"2025-12-06T15:18:47.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 3, \"sucursal_ajuste\": null, \"devolucion_stock_id\": 2}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"updated_at\": \"2025-12-06T15:18:47.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 3, \"sucursal_ajuste\": null, \"devolucion_stock_id\": 2}]}', 'DEVOLUCIÓN DE STOCK', '2025-12-06', '11:18:47', '2025-12-06 15:18:47', '2025-12-06 15:18:47'),
(86, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA DEVOLUCIÓN DE STOCK', '{\"id\": 2, \"nro\": 1, \"hora\": \"11:08:00\", \"fecha\": \"2025-12-06\", \"total\": \"1935.00\", \"codigo\": \"DEV.1\", \"estado\": \"PENDIENTE\", \"total_v\": \"1935.00\", \"user_id\": 1, \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T15:18:47.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 6, \"cantidad_total_v\": 6, \"devolucion_stock_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"900.00\", \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"updated_at\": \"2025-12-06T15:18:47.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 3, \"sucursal_ajuste\": null, \"devolucion_stock_id\": 2}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"updated_at\": \"2025-12-06T15:18:47.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 3, \"sucursal_ajuste\": null, \"devolucion_stock_id\": 2}]}', '{\"id\": 2, \"nro\": 1, \"hora\": \"11:08:00\", \"fecha\": \"2025-12-06\", \"total\": \"1935.00\", \"codigo\": \"DEV.1\", \"estado\": \"APROBADO\", \"total_v\": \"1935.00\", \"user_id\": 1, \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T15:20:26.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 6, \"cantidad_total_v\": 6, \"devolucion_stock_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"900.00\", \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"updated_at\": \"2025-12-06T15:20:26.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 3, \"sucursal_ajuste\": null, \"devolucion_stock_id\": 2}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-06T15:14:42.000000Z\", \"updated_at\": \"2025-12-06T15:20:26.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 3, \"sucursal_ajuste\": null, \"devolucion_stock_id\": 2}]}', 'DEVOLUCIÓN DE STOCK', '2025-12-06', '11:20:26', '2025-12-06 15:20:26', '2025-12-06 15:20:26'),
(87, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE SALIDA', '{\"id\": 5, \"nro\": 5, \"hora\": \"16:01\", \"fecha\": \"2025-12-06\", \"total\": 600, \"codigo\": \"SAL.5\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-06T20:01:49.000000Z\", \"updated_at\": \"2025-12-06T20:01:49.000000Z\", \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 2}', NULL, 'ORDEN DE SALIDA', '2025-12-06', '16:01:49', '2025-12-06 20:01:49', '2025-12-06 20:01:49'),
(88, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA ORDEN DE SALIDA', '{\"id\": 5, \"nro\": 5, \"hora\": \"16:01:00\", \"fecha\": \"2025-12-06\", \"total\": \"600.00\", \"codigo\": \"SAL.5\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-06T20:01:49.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T20:01:49.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 2, \"orden_salida_detalles\": [{\"id\": 6, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 2, \"subtotal\": \"600.00\", \"created_at\": \"2025-12-06T20:01:49.000000Z\", \"updated_at\": \"2025-12-06T20:01:49.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 2, \"orden_salida_id\": 5, \"sucursal_ajuste\": null}]}', '{\"id\": 5, \"nro\": 5, \"hora\": \"16:01:00\", \"fecha\": \"2025-12-06\", \"total\": \"600.00\", \"codigo\": \"SAL.5\", \"estado\": \"APROBADO\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-06T20:01:49.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-06T20:01:54.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 2, \"orden_salida_detalles\": [{\"id\": 6, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 2, \"subtotal\": \"600.00\", \"created_at\": \"2025-12-06T20:01:49.000000Z\", \"updated_at\": \"2025-12-06T20:01:54.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 2, \"orden_salida_id\": 5, \"sucursal_ajuste\": null}]}', 'ORDEN DE SALIDA', '2025-12-06', '16:01:54', '2025-12-06 20:01:54', '2025-12-06 20:01:54'),
(89, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE VENTA', '{\"id\": 4, \"nro\": 1, \"cs_f\": \"CON FACTURA\", \"hora\": \"15:54\", \"fecha\": \"2025-12-06\", \"total\": 300, \"cambio\": 0, \"codigo\": \"OV.1\", \"total_f\": 300, \"user_id\": 1, \"cancelado\": 300, \"cliente_id\": 1, \"created_at\": \"2025-12-06T20:01:57.000000Z\", \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-06T20:01:57.000000Z\", \"sucursal_id\": 2, \"cantidad_total\": 1}', NULL, 'ORDEN DE VENTA', '2025-12-06', '16:01:57', '2025-12-06 20:01:57', '2025-12-06 20:01:57'),
(90, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE VENTA', '{\"id\": 5, \"nro\": 2, \"cs_f\": \"CON FACTURA\", \"hora\": \"15:54\", \"fecha\": \"2025-12-06\", \"total\": 300, \"cambio\": 0, \"codigo\": \"OV.2\", \"total_f\": 300, \"user_id\": 1, \"cancelado\": 300, \"cliente_id\": 1, \"created_at\": \"2025-12-06T20:02:05.000000Z\", \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-06T20:02:05.000000Z\", \"sucursal_id\": 2, \"cantidad_total\": 1}', NULL, 'ORDEN DE VENTA', '2025-12-06', '16:02:05', '2025-12-06 20:02:05', '2025-12-06 20:02:05'),
(91, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE VENTA', '{\"id\": 7, \"nro\": 3, \"cs_f\": \"SIN FACTURA\", \"hora\": \"09:32\", \"fecha\": \"2025-12-08\", \"total\": 645, \"cambio\": 45, \"codigo\": \"OV.3\", \"estado\": \"FINALIZADO\", \"total_f\": 625, \"user_id\": 1, \"cancelado\": 670, \"descuento\": 20, \"cliente_id\": 2, \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T13:40:38.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"cantidad_total\": 2, \"monto_solicitud\": 20, \"solicitud_descuento\": 1}', NULL, 'ORDEN DE VENTA', '2025-12-08', '09:40:38', '2025-12-08 13:40:38', '2025-12-08 13:40:38'),
(92, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO EL DESCUENTO DE UNA ORDEN DE VENTA', '{\"id\": 7, \"nro\": 3, \"cs_f\": \"SIN FACTURA\", \"hora\": \"09:32:00\", \"fecha\": \"2025-12-08\", \"total\": \"645.00\", \"cambio\": \"45.00\", \"codigo\": \"OV.3\", \"estado\": \"PENDIENTE\", \"total_f\": \"625.00\", \"user_ap\": 0, \"user_id\": 1, \"total_st\": \"0.00\", \"cancelado\": \"670.00\", \"descuento\": \"20.00\", \"cliente_id\": 2, \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T13:40:38.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 2, \"monto_solicitud\": \"20.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 6, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T13:40:38.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 7, \"unidad_medida_id\": 1}, {\"id\": 7, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T13:40:38.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 7, \"unidad_medida_id\": 2}]}', '{\"id\": 7, \"nro\": 3, \"cs_f\": \"SIN FACTURA\", \"hora\": \"09:32:00\", \"fecha\": \"2025-12-08\", \"total\": \"645.00\", \"cambio\": \"45.00\", \"codigo\": \"OV.3\", \"estado\": \"APROBADO\", \"total_f\": \"625.00\", \"user_ap\": 1, \"user_id\": 1, \"total_st\": \"0.00\", \"cancelado\": \"670.00\", \"descuento\": 20, \"cliente_id\": 2, \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T20:08:57.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 2, \"monto_solicitud\": \"20.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 6, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T13:40:38.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 7, \"unidad_medida_id\": 1}, {\"id\": 7, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T13:40:38.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 7, \"unidad_medida_id\": 2}]}', 'ORDEN DE VENTA', '2025-12-08', '16:08:57', '2025-12-08 20:08:57', '2025-12-08 20:08:57'),
(93, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE SALIDA', '{\"id\": 6, \"nro\": 6, \"hora\": \"16:16\", \"fecha\": \"2025-12-08\", \"total\": 900, \"codigo\": \"SAL.6\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-08T20:16:41.000000Z\", \"updated_at\": \"2025-12-08T20:16:41.000000Z\", \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 3}', NULL, 'ORDEN DE SALIDA', '2025-12-08', '16:16:41', '2025-12-08 20:16:41', '2025-12-08 20:16:41'),
(94, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA ORDEN DE SALIDA', '{\"id\": 6, \"nro\": 6, \"hora\": \"16:16:00\", \"fecha\": \"2025-12-08\", \"total\": \"900.00\", \"codigo\": \"SAL.6\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-08T20:16:41.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:16:41.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 3, \"orden_salida_detalles\": [{\"id\": 7, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"900.00\", \"created_at\": \"2025-12-08T20:16:41.000000Z\", \"updated_at\": \"2025-12-08T20:16:41.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 3, \"orden_salida_id\": 6, \"sucursal_ajuste\": null}]}', '{\"id\": 6, \"nro\": 6, \"hora\": \"16:16:00\", \"fecha\": \"2025-12-08\", \"total\": \"900.00\", \"codigo\": \"SAL.6\", \"estado\": \"APROBADO\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-08T20:16:41.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:16:54.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 3, \"orden_salida_detalles\": [{\"id\": 7, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"900.00\", \"created_at\": \"2025-12-08T20:16:41.000000Z\", \"updated_at\": \"2025-12-08T20:16:54.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 3, \"orden_salida_id\": 6, \"sucursal_ajuste\": null}]}', 'ORDEN DE SALIDA', '2025-12-08', '16:16:54', '2025-12-08 20:16:54', '2025-12-08 20:16:54'),
(95, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE SALIDA', '{\"id\": 7, \"nro\": 7, \"hora\": \"16:17\", \"fecha\": \"2025-12-08\", \"total\": 1035, \"codigo\": \"SAL.7\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-08T20:17:20.000000Z\", \"updated_at\": \"2025-12-08T20:17:20.000000Z\", \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 3}', NULL, 'ORDEN DE SALIDA', '2025-12-08', '16:17:20', '2025-12-08 20:17:20', '2025-12-08 20:17:20');
INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(96, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA ORDEN DE SALIDA', '{\"id\": 7, \"nro\": 7, \"hora\": \"16:17:00\", \"fecha\": \"2025-12-08\", \"total\": \"1035.00\", \"codigo\": \"SAL.7\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-08T20:17:20.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:17:20.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 3, \"orden_salida_detalles\": [{\"id\": 8, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-08T20:17:20.000000Z\", \"updated_at\": \"2025-12-08T20:17:20.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 3, \"orden_salida_id\": 7, \"sucursal_ajuste\": null}]}', '{\"id\": 7, \"nro\": 7, \"hora\": \"16:17:00\", \"fecha\": \"2025-12-08\", \"total\": \"1035.00\", \"codigo\": \"SAL.7\", \"estado\": \"APROBADO\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-08T20:17:20.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:17:25.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 3, \"orden_salida_detalles\": [{\"id\": 8, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 3, \"subtotal\": \"1035.00\", \"created_at\": \"2025-12-08T20:17:20.000000Z\", \"updated_at\": \"2025-12-08T20:17:25.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 3, \"orden_salida_id\": 7, \"sucursal_ajuste\": null}]}', 'ORDEN DE SALIDA', '2025-12-08', '16:17:25', '2025-12-08 20:17:25', '2025-12-08 20:17:25'),
(97, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA ORDEN DE VENTA', '{\"id\": 7, \"nro\": 3, \"cs_f\": \"SIN FACTURA\", \"hora\": \"09:32:00\", \"fecha\": \"2025-12-08\", \"total\": \"645.00\", \"cambio\": \"45.00\", \"codigo\": \"OV.3\", \"estado\": \"APROBADO\", \"total_f\": \"625.00\", \"user_ap\": 1, \"user_id\": 1, \"total_st\": \"0.00\", \"cancelado\": \"670.00\", \"descuento\": \"20.00\", \"cliente_id\": 2, \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T20:08:57.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"solicitud_sw\": 1, \"observaciones\": null, \"cantidad_total\": 2, \"monto_solicitud\": \"20.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 6, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T13:40:38.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 7, \"unidad_medida_id\": 1}, {\"id\": 7, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T13:40:38.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 7, \"unidad_medida_id\": 2}]}', '{\"id\": 7, \"nro\": 3, \"cs_f\": \"SIN FACTURA\", \"hora\": \"09:32:00\", \"fecha\": \"2025-12-08\", \"total\": 645, \"cambio\": 0, \"codigo\": \"OV.3\", \"estado\": \"FINALIZADO\", \"total_f\": 625, \"user_ap\": 1, \"user_id\": 1, \"total_st\": 645, \"cancelado\": 625, \"descuento\": \"20.00\", \"cliente_id\": 2, \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T20:17:28.000000Z\", \"verificado\": 2, \"sucursal_id\": 2, \"solicitud_sw\": 1, \"observaciones\": null, \"cantidad_total\": 2, \"monto_solicitud\": \"20.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 6, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T20:17:28.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 7, \"unidad_medida_id\": 1}, {\"id\": 7, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T13:40:38.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T13:40:38.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 7, \"unidad_medida_id\": 2}]}', 'ORDEN DE VENTA', '2025-12-08', '16:17:28', '2025-12-08 20:17:28', '2025-12-08 20:17:28'),
(98, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA SOLICITUD DE INGRESO', '{\"id\": 3, \"nro\": 3, \"cs_f\": \"CON FATURA\", \"total\": 64500, \"codigo\": \"SOL.3\", \"estado\": \"PENDIENTE\", \"gastos\": 0, \"user_id\": 1, \"hora_sis\": \"16:45\", \"fecha_sis\": \"2025-12-08\", \"created_at\": \"2025-12-08T20:45:57.000000Z\", \"updated_at\": \"2025-12-08T20:45:57.000000Z\", \"descripcion\": \"\", \"tipo_cambio\": 6.98, \"hora_ingreso\": \"16:45\", \"proveedor_id\": 2, \"fecha_ingreso\": \"2025-12-08\", \"observaciones\": \"\", \"cantidad_total\": 200}', NULL, 'SOLICITUD DE INGRESO', '2025-12-08', '16:45:57', '2025-12-08 20:45:57', '2025-12-08 20:45:57'),
(99, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA SOLICITUD DE INGRESO', '{\"id\": 2, \"nro\": 2, \"cs_f\": \"SIN FATURA\", \"total\": \"10500.00\", \"codigo\": \"SOL.2\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"10:15:00\", \"fecha_sis\": \"2025-12-05\", \"created_at\": \"2025-12-05T14:15:19.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-05T14:15:19.000000Z\", \"verificado\": 0, \"descripcion\": \"\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"10:15:00\", \"proveedor_id\": 2, \"fecha_ingreso\": \"2025-12-05\", \"observaciones\": \"\", \"cantidad_total\": 35, \"solicitud_ingreso_detalles\": [{\"id\": 3, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 35, \"subtotal\": \"10500.00\", \"created_at\": \"2025-12-05T14:15:19.000000Z\", \"updated_at\": \"2025-12-05T14:15:19.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 35, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 2}]}', '{\"id\": 2, \"nro\": 2, \"cs_f\": \"SIN FATURA\", \"total\": \"10500.00\", \"codigo\": \"SOL.2\", \"estado\": \"APROBADO\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"10:15:00\", \"fecha_sis\": \"2025-12-05\", \"created_at\": \"2025-12-05T14:15:19.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:46:04.000000Z\", \"verificado\": 1, \"descripcion\": \"\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"10:15:00\", \"proveedor_id\": 2, \"fecha_ingreso\": \"2025-12-05\", \"observaciones\": \"\", \"cantidad_total\": 35, \"solicitud_ingreso_detalles\": [{\"id\": 3, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 35, \"subtotal\": \"10500.00\", \"created_at\": \"2025-12-05T14:15:19.000000Z\", \"updated_at\": \"2025-12-08T20:46:04.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 35, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 2}]}', 'SOLICITUD DE INGRESO', '2025-12-08', '16:46:04', '2025-12-08 20:46:04', '2025-12-08 20:46:04'),
(100, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA SOLICITUD DE INGRESO', '{\"id\": 3, \"nro\": 3, \"cs_f\": \"CON FATURA\", \"total\": \"64500.00\", \"codigo\": \"SOL.3\", \"estado\": \"PENDIENTE\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"16:45:00\", \"fecha_sis\": \"2025-12-08\", \"created_at\": \"2025-12-08T20:45:57.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:45:57.000000Z\", \"verificado\": 0, \"descripcion\": \"\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"16:45:00\", \"proveedor_id\": 2, \"fecha_ingreso\": \"2025-12-08\", \"observaciones\": \"\", \"cantidad_total\": 200, \"solicitud_ingreso_detalles\": [{\"id\": 4, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 100, \"subtotal\": \"30000.00\", \"created_at\": \"2025-12-08T20:45:57.000000Z\", \"updated_at\": \"2025-12-08T20:45:57.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 100, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 3}, {\"id\": 5, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 100, \"subtotal\": \"34500.00\", \"created_at\": \"2025-12-08T20:45:57.000000Z\", \"updated_at\": \"2025-12-08T20:45:57.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 100, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 3}]}', '{\"id\": 3, \"nro\": 3, \"cs_f\": \"CON FATURA\", \"total\": \"64500.00\", \"codigo\": \"SOL.3\", \"estado\": \"APROBADO\", \"gastos\": \"0.00\", \"user_id\": 1, \"hora_sis\": \"16:45:00\", \"fecha_sis\": \"2025-12-08\", \"created_at\": \"2025-12-08T20:45:57.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:46:08.000000Z\", \"verificado\": 1, \"descripcion\": \"\", \"tipo_cambio\": \"6.98\", \"hora_ingreso\": \"16:45:00\", \"proveedor_id\": 2, \"fecha_ingreso\": \"2025-12-08\", \"observaciones\": \"\", \"cantidad_total\": 200, \"solicitud_ingreso_detalles\": [{\"id\": 4, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 100, \"subtotal\": \"30000.00\", \"created_at\": \"2025-12-08T20:45:57.000000Z\", \"updated_at\": \"2025-12-08T20:46:08.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 100, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 3}, {\"id\": 5, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 100, \"subtotal\": \"34500.00\", \"created_at\": \"2025-12-08T20:45:57.000000Z\", \"updated_at\": \"2025-12-08T20:46:08.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 100, \"sucursal_ajuste\": null, \"solicitud_ingreso_id\": 3}]}', 'SOLICITUD DE INGRESO', '2025-12-08', '16:46:08', '2025-12-08 20:46:08', '2025-12-08 20:46:08'),
(101, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE SALIDA', '{\"id\": 8, \"nro\": 8, \"hora\": \"16:46\", \"fecha\": \"2025-12-08\", \"total\": 25800, \"codigo\": \"SAL.8\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-08T20:46:28.000000Z\", \"updated_at\": \"2025-12-08T20:46:28.000000Z\", \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 80}', NULL, 'ORDEN DE SALIDA', '2025-12-08', '16:46:28', '2025-12-08 20:46:28', '2025-12-08 20:46:28'),
(102, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA ORDEN DE SALIDA', '{\"id\": 8, \"nro\": 8, \"hora\": \"16:46:00\", \"fecha\": \"2025-12-08\", \"total\": \"25800.00\", \"codigo\": \"SAL.8\", \"estado\": \"PENDIENTE\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-08T20:46:28.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:46:28.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 80, \"orden_salida_detalles\": [{\"id\": 9, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 40, \"subtotal\": \"12000.00\", \"created_at\": \"2025-12-08T20:46:28.000000Z\", \"updated_at\": \"2025-12-08T20:46:28.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 40, \"orden_salida_id\": 8, \"sucursal_ajuste\": null}, {\"id\": 10, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 40, \"subtotal\": \"13800.00\", \"created_at\": \"2025-12-08T20:46:28.000000Z\", \"updated_at\": \"2025-12-08T20:46:28.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 40, \"orden_salida_id\": 8, \"sucursal_ajuste\": null}]}', '{\"id\": 8, \"nro\": 8, \"hora\": \"16:46:00\", \"fecha\": \"2025-12-08\", \"total\": \"25800.00\", \"codigo\": \"SAL.8\", \"estado\": \"APROBADO\", \"user_ap\": 15, \"user_id\": 1, \"user_sol\": 15, \"created_at\": \"2025-12-08T20:46:28.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:46:33.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 80, \"orden_salida_detalles\": [{\"id\": 9, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 40, \"subtotal\": \"12000.00\", \"created_at\": \"2025-12-08T20:46:28.000000Z\", \"updated_at\": \"2025-12-08T20:46:33.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 40, \"orden_salida_id\": 8, \"sucursal_ajuste\": null}, {\"id\": 10, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 40, \"subtotal\": \"13800.00\", \"created_at\": \"2025-12-08T20:46:28.000000Z\", \"updated_at\": \"2025-12-08T20:46:33.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 40, \"orden_salida_id\": 8, \"sucursal_ajuste\": null}]}', 'ORDEN DE SALIDA', '2025-12-08', '16:46:33', '2025-12-08 20:46:33', '2025-12-08 20:46:33'),
(103, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE VENTA', '{\"id\": 8, \"nro\": 4, \"cs_f\": \"CON FACTURA\", \"hora\": \"16:46\", \"fecha\": \"2025-12-08\", \"total\": 1935, \"cambio\": 0, \"codigo\": \"OV.4\", \"estado\": \"FINALIZADO\", \"total_f\": 1935, \"user_id\": 1, \"total_st\": 1935, \"cancelado\": 0, \"descuento\": null, \"cliente_id\": 1, \"created_at\": \"2025-12-08T20:48:08.000000Z\", \"forma_pago\": \"CRÉDITO\", \"updated_at\": \"2025-12-08T20:48:08.000000Z\", \"verificado\": 2, \"sucursal_id\": 2, \"solicitud_sw\": null, \"cantidad_total\": 6, \"monto_solicitud\": null, \"solicitud_descuento\": 0}', NULL, 'ORDEN DE VENTA', '2025-12-08', '16:48:08', '2025-12-08 20:48:08', '2025-12-08 20:48:08'),
(104, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO EL PAGO DE UNA CUENTA POR COBRAR', '{\"id\": 1, \"hora\": \"16:48:08\", \"fecha\": \"2025-12-08\", \"saldo\": \"1935.00\", \"total\": \"1935.00\", \"cancelado\": \"0.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T20:48:08.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T20:48:08.000000Z\", \"orden_venta_id\": 8, \"cuenta_cobrar_detalles\": [{\"id\": 1, \"hora\": \"17:36:50\", \"fecha\": \"2025-12-08\", \"fecha_c\": \"08/12/2025 17:36\", \"cancelado\": \"200.00\", \"created_at\": \"2025-12-08T21:36:50.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T21:36:50.000000Z\", \"cuenta_cobrar_id\": 1}]}', NULL, 'CUENTAS POR COBRAR', '2025-12-08', '17:36:50', '2025-12-08 21:36:50', '2025-12-08 21:36:50'),
(105, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO EL PAGO DE UNA CUENTA POR COBRAR', '{\"id\": 1, \"hora\": \"16:48:08\", \"fecha\": \"2025-12-08\", \"saldo\": 1535, \"total\": \"1935.00\", \"cancelado\": 400, \"cliente_id\": 1, \"created_at\": \"2025-12-08T20:48:08.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T21:39:10.000000Z\", \"orden_venta_id\": 8, \"cuenta_cobrar_detalles\": [{\"id\": 1, \"hora\": \"17:36:50\", \"fecha\": \"2025-12-08\", \"fecha_c\": \"08/12/2025 17:36\", \"cancelado\": \"200.00\", \"created_at\": \"2025-12-08T21:36:50.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T21:36:50.000000Z\", \"cuenta_cobrar_id\": 1}, {\"id\": 2, \"hora\": \"17:39:10\", \"fecha\": \"2025-12-08\", \"fecha_c\": \"08/12/2025 17:39\", \"cancelado\": \"200.00\", \"created_at\": \"2025-12-08T21:39:10.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-08T21:39:10.000000Z\", \"cuenta_cobrar_id\": 1}]}', NULL, 'CUENTAS POR COBRAR', '2025-12-08', '17:39:10', '2025-12-08 21:39:10', '2025-12-08 21:39:10'),
(106, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE VENTA', '{\"id\": 9, \"nro\": 5, \"cs_f\": \"CON FACTURA\", \"hora\": \"18:38\", \"fecha\": \"2025-12-08\", \"total\": 300, \"cambio\": 0, \"codigo\": \"OV.5\", \"estado\": \"PENDIENTE\", \"total_f\": 290, \"user_id\": 1, \"total_st\": 300, \"cancelado\": 290, \"descuento\": 10, \"cliente_id\": 1, \"created_at\": \"2025-12-08T22:38:56.000000Z\", \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T22:38:56.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"cantidad_total\": 1, \"monto_solicitud\": 10, \"solicitud_descuento\": 1}', NULL, 'ORDEN DE VENTA', '2025-12-08', '18:38:56', '2025-12-08 22:38:56', '2025-12-08 22:38:56'),
(107, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA ORDEN DE VENTA', '{\"id\": 9, \"nro\": 5, \"cs_f\": \"CON FACTURA\", \"hora\": \"18:38:00\", \"fecha\": \"2025-12-08\", \"total\": \"300.00\", \"cambio\": \"0.00\", \"codigo\": \"OV.5\", \"estado\": \"PENDIENTE\", \"total_f\": \"290.00\", \"user_ap\": null, \"user_id\": 1, \"total_st\": \"300.00\", \"cancelado\": \"290.00\", \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T22:38:56.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T22:38:56.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 1, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 10, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T22:38:56.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T22:38:56.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 9, \"unidad_medida_id\": 1}]}', '{\"id\": 9, \"nro\": 5, \"cs_f\": \"CON FACTURA\", \"hora\": \"18:38:00\", \"fecha\": \"2025-12-08\", \"total\": 645, \"cambio\": 0, \"codigo\": \"OV.5\", \"estado\": \"PENDIENTE\", \"total_f\": 645, \"user_ap\": null, \"user_id\": 1, \"total_st\": 645, \"cancelado\": 645, \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T22:38:56.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T22:39:25.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 2, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 10, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T22:38:56.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T22:38:56.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 9, \"unidad_medida_id\": 1}, {\"id\": 11, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T22:39:25.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T22:39:25.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 9, \"unidad_medida_id\": 2}]}', 'ORDEN DE VENTA', '2025-12-08', '18:39:25', '2025-12-08 22:39:25', '2025-12-08 22:39:25'),
(108, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA ORDEN DE VENTA', '{\"id\": 10, \"nro\": 6, \"cs_f\": \"CON FACTURA\", \"hora\": \"19:18\", \"fecha\": \"2025-12-08\", \"total\": 300, \"cambio\": 0, \"codigo\": \"OV.6\", \"estado\": \"PENDIENTE\", \"total_f\": 290, \"user_id\": 1, \"total_st\": 300, \"cancelado\": 0, \"descuento\": 10, \"cliente_id\": 1, \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:19:09.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"cantidad_total\": 1, \"monto_solicitud\": 10, \"solicitud_descuento\": 1}', NULL, 'ORDEN DE VENTA', '2025-12-08', '19:19:09', '2025-12-08 23:19:09', '2025-12-08 23:19:09'),
(109, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA ORDEN DE VENTA', '{\"id\": 9, \"nro\": 5, \"cs_f\": \"CON FACTURA\", \"hora\": \"18:38:00\", \"fecha\": \"2025-12-08\", \"total\": \"645.00\", \"cambio\": \"0.00\", \"codigo\": \"OV.5\", \"estado\": \"PENDIENTE\", \"total_f\": \"645.00\", \"user_ap\": null, \"user_id\": 1, \"total_st\": \"645.00\", \"cancelado\": \"645.00\", \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T22:38:56.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T22:39:25.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 2, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 10, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T22:38:56.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T22:38:56.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 9, \"unidad_medida_id\": 1}, {\"id\": 11, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T22:39:25.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T22:39:25.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 9, \"unidad_medida_id\": 2}]}', '{\"id\": 9, \"nro\": 5, \"cs_f\": \"CON FACTURA\", \"hora\": \"18:38:00\", \"fecha\": \"2025-12-08\", \"total\": \"645.00\", \"cambio\": 0, \"codigo\": \"OV.5\", \"estado\": \"PENDIENTE\", \"total_f\": 635, \"user_ap\": null, \"user_id\": 1, \"total_st\": 645, \"cancelado\": 635, \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T22:38:56.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:19:31.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 2, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 10, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T22:38:56.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T22:38:56.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 9, \"unidad_medida_id\": 1}, {\"id\": 11, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T22:39:25.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T22:39:25.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 9, \"unidad_medida_id\": 2}]}', 'ORDEN DE VENTA', '2025-12-08', '19:19:31', '2025-12-08 23:19:31', '2025-12-08 23:19:31'),
(110, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA ORDEN DE VENTA', '{\"id\": 10, \"nro\": 6, \"cs_f\": \"CON FACTURA\", \"hora\": \"19:18:00\", \"fecha\": \"2025-12-08\", \"total\": \"300.00\", \"cambio\": \"0.00\", \"codigo\": \"OV.6\", \"estado\": \"PENDIENTE\", \"total_f\": \"290.00\", \"user_ap\": null, \"user_id\": 1, \"total_st\": \"300.00\", \"cancelado\": \"0.00\", \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:19:09.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 1, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 12, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T23:19:09.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 10, \"unidad_medida_id\": 1}]}', '{\"id\": 10, \"nro\": 6, \"cs_f\": \"CON FACTURA\", \"hora\": \"19:18:00\", \"fecha\": \"2025-12-08\", \"total\": 645, \"cambio\": 0, \"codigo\": \"OV.6\", \"estado\": \"PENDIENTE\", \"total_f\": 635, \"user_ap\": null, \"user_id\": 1, \"total_st\": 645, \"cancelado\": \"0.00\", \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:21:06.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 2, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 12, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T23:19:09.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 10, \"unidad_medida_id\": 1}, {\"id\": 13, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:21:06.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T23:21:06.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 10, \"unidad_medida_id\": 2}]}', 'ORDEN DE VENTA', '2025-12-08', '19:21:06', '2025-12-08 23:21:06', '2025-12-08 23:21:06'),
(111, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA ORDEN DE VENTA', '{\"id\": 10, \"nro\": 6, \"cs_f\": \"CON FACTURA\", \"hora\": \"19:18:00\", \"fecha\": \"2025-12-08\", \"total\": \"645.00\", \"cambio\": \"0.00\", \"codigo\": \"OV.6\", \"estado\": \"PENDIENTE\", \"total_f\": \"635.00\", \"user_ap\": null, \"user_id\": 1, \"total_st\": \"645.00\", \"cancelado\": \"0.00\", \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:21:06.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 2, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 12, \"precio\": \"300.00\", \"cantidad\": 1, \"subtotal\": \"300.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"300.00\", \"updated_at\": \"2025-12-08T23:19:09.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 10, \"unidad_medida_id\": 1}, {\"id\": 13, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:21:06.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T23:21:06.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 10, \"unidad_medida_id\": 2}]}', '{\"id\": 10, \"nro\": 6, \"cs_f\": \"CON FACTURA\", \"hora\": \"19:18:00\", \"fecha\": \"2025-12-08\", \"total\": 945, \"cambio\": 0, \"codigo\": \"OV.6\", \"estado\": \"PENDIENTE\", \"total_f\": 935, \"user_ap\": null, \"user_id\": 1, \"total_st\": 945, \"cancelado\": \"0.00\", \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:23:56.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 3, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 12, \"precio\": \"300.00\", \"cantidad\": 2, \"subtotal\": \"600.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"600.00\", \"updated_at\": \"2025-12-08T23:23:56.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 10, \"unidad_medida_id\": 1}, {\"id\": 13, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:21:06.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T23:21:06.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 10, \"unidad_medida_id\": 2}]}', 'ORDEN DE VENTA', '2025-12-08', '19:23:56', '2025-12-08 23:23:56', '2025-12-08 23:23:56'),
(112, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO EL DESCUENTO DE UNA ORDEN DE VENTA', '{\"id\": 10, \"nro\": 6, \"cs_f\": \"CON FACTURA\", \"hora\": \"19:18:00\", \"fecha\": \"2025-12-08\", \"total\": \"945.00\", \"cambio\": \"0.00\", \"codigo\": \"OV.6\", \"estado\": \"PENDIENTE\", \"total_f\": \"935.00\", \"user_ap\": null, \"user_id\": 1, \"total_st\": \"945.00\", \"cancelado\": \"0.00\", \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:23:56.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"solicitud_sw\": 0, \"observaciones\": null, \"cantidad_total\": 3, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 12, \"precio\": \"300.00\", \"cantidad\": 2, \"subtotal\": \"600.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"600.00\", \"updated_at\": \"2025-12-08T23:23:56.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 10, \"unidad_medida_id\": 1}, {\"id\": 13, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:21:06.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T23:21:06.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 10, \"unidad_medida_id\": 2}]}', '{\"id\": 10, \"nro\": 6, \"cs_f\": \"CON FACTURA\", \"hora\": \"19:18:00\", \"fecha\": \"2025-12-08\", \"total\": \"945.00\", \"cambio\": \"0.00\", \"codigo\": \"OV.6\", \"estado\": \"APROBADO\", \"total_f\": \"935.00\", \"user_ap\": 1, \"user_id\": 1, \"total_st\": \"945.00\", \"cancelado\": \"0.00\", \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:24:04.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"solicitud_sw\": 1, \"observaciones\": null, \"cantidad_total\": 3, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 12, \"precio\": \"300.00\", \"cantidad\": 2, \"subtotal\": \"600.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"600.00\", \"updated_at\": \"2025-12-08T23:23:56.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 10, \"unidad_medida_id\": 1}, {\"id\": 13, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:21:06.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T23:21:06.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 10, \"unidad_medida_id\": 2}]}', 'ORDEN DE VENTA', '2025-12-08', '19:24:04', '2025-12-08 23:24:04', '2025-12-08 23:24:04'),
(113, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA ORDEN DE VENTA', '{\"id\": 10, \"nro\": 6, \"cs_f\": \"CON FACTURA\", \"hora\": \"19:18:00\", \"fecha\": \"2025-12-08\", \"total\": \"945.00\", \"cambio\": \"0.00\", \"codigo\": \"OV.6\", \"estado\": \"APROBADO\", \"total_f\": \"935.00\", \"user_ap\": 1, \"user_id\": 1, \"total_st\": \"945.00\", \"cancelado\": \"0.00\", \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:24:04.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"solicitud_sw\": 1, \"observaciones\": null, \"cantidad_total\": 3, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 12, \"precio\": \"300.00\", \"cantidad\": 2, \"subtotal\": \"600.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"600.00\", \"updated_at\": \"2025-12-08T23:23:56.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 10, \"unidad_medida_id\": 1}, {\"id\": 13, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:21:06.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T23:21:06.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 10, \"unidad_medida_id\": 2}]}', '{\"id\": 10, \"nro\": 6, \"cs_f\": \"CON FACTURA\", \"hora\": \"19:18:00\", \"fecha\": \"2025-12-08\", \"total\": \"945.00\", \"cambio\": 0, \"codigo\": \"OV.6\", \"estado\": \"FINALIZADO\", \"total_f\": \"935.00\", \"user_ap\": 1, \"user_id\": 1, \"total_st\": 945, \"cancelado\": 935, \"descuento\": \"10.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-08T23:24:33.000000Z\", \"verificado\": 2, \"sucursal_id\": 2, \"solicitud_sw\": 1, \"observaciones\": null, \"cantidad_total\": 3, \"monto_solicitud\": \"10.00\", \"solicitud_descuento\": 1, \"orden_venta_detalles\": [{\"id\": 12, \"precio\": \"300.00\", \"cantidad\": 2, \"subtotal\": \"600.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:19:09.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"600.00\", \"updated_at\": \"2025-12-08T23:23:56.000000Z\", \"producto_id\": 3, \"orden_venta_id\": 10, \"unidad_medida_id\": 1}, {\"id\": 13, \"precio\": \"345.00\", \"cantidad\": 1, \"subtotal\": \"345.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-08T23:21:06.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"345.00\", \"updated_at\": \"2025-12-08T23:21:06.000000Z\", \"producto_id\": 4, \"orden_venta_id\": 10, \"unidad_medida_id\": 2}]}', 'ORDEN DE VENTA', '2025-12-08', '19:24:33', '2025-12-08 23:24:33', '2025-12-08 23:24:33'),
(114, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA PROFORMA', '{\"id\": 3, \"nro\": 1, \"cs_f\": \"CON FACTURA\", \"hora\": \"16:25\", \"fecha\": \"2025-12-09\", \"total\": 3870, \"codigo\": \"PF.1\", \"total_f\": 3850, \"user_id\": 1, \"total_st\": 3870, \"descuento\": 20, \"cliente_id\": 1, \"created_at\": \"2025-12-09T20:30:52.000000Z\", \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-09T20:30:52.000000Z\", \"sucursal_id\": 2, \"cantidad_total\": 12, \"solicitud_descuento\": 1}', NULL, 'PROFORMA', '2025-12-09', '16:30:52', '2025-12-09 20:30:52', '2025-12-09 20:30:52'),
(115, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA PROFORMA', '{\"id\": 3, \"nro\": 1, \"cs_f\": \"CON FACTURA\", \"hora\": \"16:25:00\", \"fecha\": \"2025-12-09\", \"total\": \"3870.00\", \"codigo\": \"PF.1\", \"total_f\": \"3850.00\", \"user_id\": 1, \"total_st\": \"3870.00\", \"descuento\": \"20.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-09T20:30:52.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-09T20:30:52.000000Z\", \"sucursal_id\": 2, \"observaciones\": null, \"cantidad_total\": 12, \"proforma_detalles\": [{\"id\": 1, \"precio\": \"300.00\", \"cantidad\": 6, \"subtotal\": \"1800.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-09T20:30:52.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"1800.00\", \"updated_at\": \"2025-12-09T20:30:52.000000Z\", \"producto_id\": 3, \"proforma_id\": 3, \"unidad_medida_id\": 1}, {\"id\": 2, \"precio\": \"345.00\", \"cantidad\": 6, \"subtotal\": \"2070.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-09T20:30:52.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"2070.00\", \"updated_at\": \"2025-12-09T20:30:52.000000Z\", \"producto_id\": 4, \"proforma_id\": 3, \"unidad_medida_id\": 2}], \"solicitud_descuento\": 1}', '{\"id\": 3, \"nro\": 1, \"cs_f\": \"CON FACTURA\", \"hora\": \"16:25:00\", \"fecha\": \"2025-12-09\", \"total\": 5250, \"codigo\": \"PF.1\", \"total_f\": 5230, \"user_id\": 1, \"total_st\": 5250, \"descuento\": \"20.00\", \"cliente_id\": 1, \"created_at\": \"2025-12-09T20:30:52.000000Z\", \"deleted_at\": null, \"forma_pago\": \"EFECTIVO\", \"updated_at\": \"2025-12-09T21:15:52.000000Z\", \"sucursal_id\": 2, \"observaciones\": null, \"cantidad_total\": 16, \"proforma_detalles\": [{\"id\": 1, \"precio\": \"300.00\", \"cantidad\": 6, \"subtotal\": \"1800.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-09T20:30:52.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"1800.00\", \"updated_at\": \"2025-12-09T20:30:52.000000Z\", \"producto_id\": 3, \"proforma_id\": 3, \"unidad_medida_id\": 1}, {\"id\": 2, \"precio\": \"345.00\", \"cantidad\": 10, \"subtotal\": \"3450.00\", \"descuento\": \"0.00\", \"created_at\": \"2025-12-09T20:30:52.000000Z\", \"deleted_at\": null, \"subtotal_f\": \"3450.00\", \"updated_at\": \"2025-12-09T21:15:52.000000Z\", \"producto_id\": 4, \"proforma_id\": 3, \"unidad_medida_id\": 2}], \"solicitud_descuento\": 1}', 'PROFORMA', '2025-12-09', '17:15:52', '2025-12-09 21:15:52', '2025-12-09 21:15:52'),
(116, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA TRANSFERENCIA', '{\"id\": 3, \"nro\": 1, \"hora\": \"17:44\", \"fecha\": \"2025-12-09\", \"codigo\": \"T.1\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_sol\": 15, \"created_at\": \"2025-12-09T21:45:57.000000Z\", \"updated_at\": \"2025-12-09T21:45:57.000000Z\", \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 2, \"cantidad_total_v\": 2, \"sucursal_destino\": 3}', NULL, 'TRANSFERENCIA', '2025-12-09', '17:45:57', '2025-12-09 21:45:57', '2025-12-09 21:45:57'),
(117, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UNA TRANSFERENCIA', '{\"id\": 3, \"nro\": 1, \"hora\": \"17:44:00\", \"fecha\": \"2025-12-09\", \"codigo\": \"T.1\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_sol\": 15, \"created_at\": \"2025-12-09T21:45:57.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-09T21:45:57.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 2, \"cantidad_total_v\": 2, \"sucursal_destino\": 3, \"transferencia_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 2, \"subtotal\": \"600.00\", \"created_at\": \"2025-12-09T21:45:57.000000Z\", \"updated_at\": \"2025-12-09T21:45:57.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 2, \"sucursal_ajuste\": null, \"transferencia_id\": 3}]}', '{\"id\": 3, \"nro\": 1, \"hora\": \"17:44:00\", \"fecha\": \"2025-12-09\", \"codigo\": \"T.1\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_sol\": 15, \"created_at\": \"2025-12-09T21:45:57.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-09T21:52:06.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 3, \"cantidad_total_v\": 3, \"sucursal_destino\": 3, \"transferencia_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 2, \"subtotal\": \"600.00\", \"created_at\": \"2025-12-09T21:45:57.000000Z\", \"updated_at\": \"2025-12-09T21:45:57.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 2, \"sucursal_ajuste\": null, \"transferencia_id\": 3}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 1, \"subtotal\": \"345.00\", \"created_at\": \"2025-12-09T21:52:06.000000Z\", \"updated_at\": \"2025-12-09T21:52:06.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 1, \"sucursal_ajuste\": null, \"transferencia_id\": 3}]}', 'TRANSFERENCIA', '2025-12-09', '17:52:06', '2025-12-09 21:52:06', '2025-12-09 21:52:06'),
(118, 1, 'MODIFICACIÓN', 'EL USUARIO admin APROBO UNA TRANSFERENCIA', '{\"id\": 3, \"nro\": 1, \"hora\": \"17:44:00\", \"fecha\": \"2025-12-09\", \"codigo\": \"T.1\", \"estado\": \"PENDIENTE\", \"user_ap\": 16, \"user_sol\": 15, \"created_at\": \"2025-12-09T21:45:57.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-09T21:52:06.000000Z\", \"verificado\": 0, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 3, \"cantidad_total_v\": 3, \"sucursal_destino\": 3, \"transferencia_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 2, \"subtotal\": \"600.00\", \"created_at\": \"2025-12-09T21:45:57.000000Z\", \"updated_at\": \"2025-12-09T21:45:57.000000Z\", \"verificado\": 0, \"producto_id\": 3, \"cantidad_fisica\": 2, \"sucursal_ajuste\": null, \"transferencia_id\": 3}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 1, \"subtotal\": \"345.00\", \"created_at\": \"2025-12-09T21:52:06.000000Z\", \"updated_at\": \"2025-12-09T21:52:06.000000Z\", \"verificado\": 0, \"producto_id\": 4, \"cantidad_fisica\": 1, \"sucursal_ajuste\": null, \"transferencia_id\": 3}]}', '{\"id\": 3, \"nro\": 1, \"hora\": \"17:44:00\", \"fecha\": \"2025-12-09\", \"codigo\": \"T.1\", \"estado\": \"APROBADO\", \"user_ap\": 16, \"user_sol\": 15, \"created_at\": \"2025-12-09T21:45:57.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-09T21:53:43.000000Z\", \"verificado\": 1, \"sucursal_id\": 2, \"observaciones\": \"\", \"cantidad_total\": 3, \"cantidad_total_v\": 3, \"sucursal_destino\": 3, \"transferencia_detalles\": [{\"id\": 1, \"costo\": \"300.00\", \"motivo\": null, \"cantidad\": 2, \"subtotal\": \"600.00\", \"created_at\": \"2025-12-09T21:45:57.000000Z\", \"updated_at\": \"2025-12-09T21:53:43.000000Z\", \"verificado\": 1, \"producto_id\": 3, \"cantidad_fisica\": 2, \"sucursal_ajuste\": null, \"transferencia_id\": 3}, {\"id\": 2, \"costo\": \"345.00\", \"motivo\": null, \"cantidad\": 1, \"subtotal\": \"345.00\", \"created_at\": \"2025-12-09T21:52:06.000000Z\", \"updated_at\": \"2025-12-09T21:53:43.000000Z\", \"verificado\": 1, \"producto_id\": 4, \"cantidad_fisica\": 1, \"sucursal_ajuste\": null, \"transferencia_id\": 3}]}', 'TRANSFERENCIA', '2025-12-09', '17:53:43', '2025-12-09 21:53:43', '2025-12-09 21:53:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_productos`
--

CREATE TABLE `kardex_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `tipo_registro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registro_id` bigint UNSIGNED DEFAULT NULL,
  `modulo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `detalle` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(24,2) DEFAULT NULL,
  `tipo_is` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad_ingreso` double DEFAULT NULL,
  `cantidad_salida` double DEFAULT NULL,
  `cantidad_saldo` double NOT NULL,
  `cu` decimal(24,2) NOT NULL,
  `monto_ingreso` decimal(24,2) DEFAULT NULL,
  `monto_salida` decimal(24,2) DEFAULT NULL,
  `monto_saldo` decimal(24,2) NOT NULL,
  `fecha` date NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `kardex_productos`
--

INSERT INTO `kardex_productos` (`id`, `sucursal_id`, `tipo_registro`, `registro_id`, `modulo`, `producto_id`, `detalle`, `precio`, `tipo_is`, `cantidad_ingreso`, `cantidad_salida`, `cantidad_saldo`, `cu`, `monto_ingreso`, `monto_salida`, `monto_saldo`, `fecha`, `status`, `created_at`, `updated_at`) VALUES
(6, 1, 'SOLICITUD INGRESO', 1, 'SolicitudIngresoDetalle', 3, 'VALOR INICIAL', 300.00, 'INGRESO', 10, NULL, 10, 300.00, 3000.00, NULL, 3000.00, '2025-12-05', 1, '2025-12-05 15:07:00', '2025-12-05 15:07:00'),
(7, 1, 'SOLICITUD INGRESO', 2, 'SolicitudIngresoDetalle', 4, 'VALOR INICIAL', 345.00, 'INGRESO', 10, NULL, 10, 345.00, 3450.00, NULL, 3450.00, '2025-12-05', 1, '2025-12-05 15:07:00', '2025-12-05 15:07:00'),
(8, 1, 'ORDEN DE SALIDA', 1, 'OrdenSalidaDetalle', 3, 'EGRESO POR ORDEN DE SALIDA', 300.00, 'EGRESO', NULL, 2, 8, 300.00, NULL, 600.00, 2400.00, '2025-12-06', 1, '2025-12-06 14:28:41', '2025-12-06 14:28:41'),
(9, 2, 'ORDEN DE SALIDA', 1, 'OrdenSalidaDetalle', 3, 'VALOR INICIAL', 300.00, 'INGRESO', 2, NULL, 2, 300.00, 600.00, NULL, 600.00, '2025-12-06', 1, '2025-12-06 14:28:41', '2025-12-06 14:28:41'),
(10, 1, 'ORDEN DE SALIDA', 2, 'OrdenSalidaDetalle', 4, 'EGRESO POR ORDEN DE SALIDA', 345.00, 'EGRESO', NULL, 3, 7, 345.00, NULL, 1035.00, 2415.00, '2025-12-06', 1, '2025-12-06 14:28:41', '2025-12-06 14:28:41'),
(11, 2, 'ORDEN DE SALIDA', 2, 'OrdenSalidaDetalle', 4, 'VALOR INICIAL', 345.00, 'INGRESO', 3, NULL, 3, 345.00, 1035.00, NULL, 1035.00, '2025-12-06', 1, '2025-12-06 14:28:41', '2025-12-06 14:28:41'),
(12, 1, 'ORDEN DE SALIDA', 3, 'OrdenSalidaDetalle', 3, 'EGRESO POR ORDEN DE SALIDA', 300.00, 'EGRESO', NULL, 4, 4, 300.00, NULL, 1200.00, 1200.00, '2025-12-06', 1, '2025-12-06 14:30:40', '2025-12-06 14:30:40'),
(13, 3, 'ORDEN DE SALIDA', 3, 'OrdenSalidaDetalle', 3, 'VALOR INICIAL', 300.00, 'INGRESO', 4, NULL, 4, 300.00, 1200.00, NULL, 1200.00, '2025-12-06', 1, '2025-12-06 14:30:40', '2025-12-06 14:30:40'),
(14, 1, 'ORDEN DE SALIDA', 4, 'OrdenSalidaDetalle', 4, 'EGRESO POR ORDEN DE SALIDA', 345.00, 'EGRESO', NULL, 3, 4, 345.00, NULL, 1035.00, 1380.00, '2025-12-06', 1, '2025-12-06 14:31:33', '2025-12-06 14:31:33'),
(15, 3, 'ORDEN DE SALIDA', 4, 'OrdenSalidaDetalle', 4, 'VALOR INICIAL', 345.00, 'INGRESO', 3, NULL, 3, 345.00, 1035.00, NULL, 1035.00, '2025-12-06', 1, '2025-12-06 14:31:33', '2025-12-06 14:31:33'),
(16, 1, 'ORDEN DE SALIDA', 5, 'OrdenSalidaDetalle', 3, 'EGRESO POR ORDEN DE SALIDA', 300.00, 'EGRESO', NULL, 1, 3, 300.00, NULL, 300.00, 900.00, '2025-12-06', 1, '2025-12-06 14:58:25', '2025-12-06 14:58:25'),
(17, 2, 'ORDEN DE SALIDA', 5, 'OrdenSalidaDetalle', 3, 'INGRESO POR ORDEN DE SALIDA', 300.00, 'INGRESO', 1, NULL, 3, 300.00, 300.00, NULL, 900.00, '2025-12-06', 1, '2025-12-06 14:58:25', '2025-12-06 14:58:25'),
(18, 2, 'DEVOLUCIÓN DE STOCK', 1, 'DevolucionStockDetalle', 3, 'EGRESO POR DEVOLUCIÓN DE STOCK', 300.00, 'EGRESO', NULL, 3, 0, 300.00, NULL, 900.00, 0.00, '2025-12-06', 1, '2025-12-06 15:20:26', '2025-12-06 15:20:26'),
(19, 1, 'DEVOLUCIÓN DE STOCK', 1, 'DevolucionStockDetalle', 3, 'INGRESO POR DEVOLUCIÓN DE STOCK', 300.00, 'INGRESO', 3, NULL, 6, 300.00, 900.00, NULL, 1800.00, '2025-12-06', 1, '2025-12-06 15:20:26', '2025-12-06 15:20:26'),
(20, 2, 'DEVOLUCIÓN DE STOCK', 2, 'DevolucionStockDetalle', 4, 'EGRESO POR DEVOLUCIÓN DE STOCK', 345.00, 'EGRESO', NULL, 3, 0, 345.00, NULL, 1035.00, 0.00, '2025-12-06', 1, '2025-12-06 15:20:26', '2025-12-06 15:20:26'),
(21, 1, 'DEVOLUCIÓN DE STOCK', 2, 'DevolucionStockDetalle', 4, 'INGRESO POR DEVOLUCIÓN DE STOCK', 345.00, 'INGRESO', 3, NULL, 7, 345.00, 1035.00, NULL, 2415.00, '2025-12-06', 1, '2025-12-06 15:20:26', '2025-12-06 15:20:26'),
(22, 1, 'ORDEN DE SALIDA', 6, 'OrdenSalidaDetalle', 3, 'EGRESO POR ORDEN DE SALIDA', 300.00, 'EGRESO', NULL, 2, 4, 300.00, NULL, 600.00, 1200.00, '2025-12-06', 1, '2025-12-06 20:01:54', '2025-12-06 20:01:54'),
(23, 2, 'ORDEN DE SALIDA', 6, 'OrdenSalidaDetalle', 3, 'INGRESO POR ORDEN DE SALIDA', 300.00, 'INGRESO', 2, NULL, 2, 300.00, 600.00, NULL, 600.00, '2025-12-06', 1, '2025-12-06 20:01:54', '2025-12-06 20:01:54'),
(24, 2, 'ORDEN DE VENTA', 3, 'OrdenVentaDetalle', 3, 'EGRESO POR ORDEN DE VENTA', 300.00, 'EGRESO', NULL, 1, 1, 300.00, NULL, 300.00, 300.00, '2025-12-06', 1, '2025-12-06 20:01:57', '2025-12-06 20:01:57'),
(25, 2, 'ORDEN DE VENTA', 4, 'OrdenVentaDetalle', 3, 'EGRESO POR ORDEN DE VENTA', 300.00, 'EGRESO', NULL, 1, 0, 300.00, NULL, 300.00, 0.00, '2025-12-06', 1, '2025-12-06 20:02:05', '2025-12-06 20:02:05'),
(26, 1, 'ORDEN DE SALIDA', 7, 'OrdenSalidaDetalle', 3, 'EGRESO POR ORDEN DE SALIDA', 300.00, 'EGRESO', NULL, 3, 1, 300.00, NULL, 900.00, 300.00, '2025-12-08', 1, '2025-12-08 20:16:54', '2025-12-08 20:16:54'),
(27, 2, 'ORDEN DE SALIDA', 7, 'OrdenSalidaDetalle', 3, 'INGRESO POR ORDEN DE SALIDA', 300.00, 'INGRESO', 3, NULL, 3, 300.00, 900.00, NULL, 900.00, '2025-12-08', 1, '2025-12-08 20:16:54', '2025-12-08 20:16:54'),
(29, 1, 'ORDEN DE SALIDA', 8, 'OrdenSalidaDetalle', 4, 'EGRESO POR ORDEN DE SALIDA', 345.00, 'EGRESO', NULL, 3, 4, 345.00, NULL, 1035.00, 1380.00, '2025-12-08', 1, '2025-12-08 20:17:25', '2025-12-08 20:17:25'),
(30, 2, 'ORDEN DE SALIDA', 8, 'OrdenSalidaDetalle', 4, 'INGRESO POR ORDEN DE SALIDA', 345.00, 'INGRESO', 3, NULL, 3, 345.00, 1035.00, NULL, 1035.00, '2025-12-08', 1, '2025-12-08 20:17:25', '2025-12-08 20:17:25'),
(31, 2, 'ORDEN DE VENTA', 6, 'OrdenVentaDetalle', 3, 'EGRESO POR ORDEN DE VENTA', 300.00, 'EGRESO', NULL, 1, 2, 300.00, NULL, 300.00, 600.00, '2025-12-08', 1, '2025-12-08 20:17:28', '2025-12-08 20:17:28'),
(32, 2, 'ORDEN DE VENTA', 7, 'OrdenVentaDetalle', 4, 'EGRESO POR ORDEN DE VENTA', 345.00, 'EGRESO', NULL, 1, 2, 345.00, NULL, 345.00, 690.00, '2025-12-08', 1, '2025-12-08 20:17:28', '2025-12-08 20:17:28'),
(33, 1, 'SOLICITUD INGRESO', 3, 'SolicitudIngresoDetalle', 3, 'INGRESO POR SOLICITUD', 300.00, 'INGRESO', 35, NULL, 36, 300.00, 10500.00, NULL, 10800.00, '2025-12-08', 1, '2025-12-08 20:46:04', '2025-12-08 20:46:04'),
(34, 1, 'SOLICITUD INGRESO', 4, 'SolicitudIngresoDetalle', 3, 'INGRESO POR SOLICITUD', 300.00, 'INGRESO', 100, NULL, 136, 300.00, 30000.00, NULL, 40800.00, '2025-12-08', 1, '2025-12-08 20:46:08', '2025-12-08 20:46:08'),
(35, 1, 'SOLICITUD INGRESO', 5, 'SolicitudIngresoDetalle', 4, 'INGRESO POR SOLICITUD', 345.00, 'INGRESO', 100, NULL, 104, 345.00, 34500.00, NULL, 35880.00, '2025-12-08', 1, '2025-12-08 20:46:08', '2025-12-08 20:46:08'),
(36, 1, 'ORDEN DE SALIDA', 9, 'OrdenSalidaDetalle', 3, 'EGRESO POR ORDEN DE SALIDA', 300.00, 'EGRESO', NULL, 40, 96, 300.00, NULL, 12000.00, 28800.00, '2025-12-08', 1, '2025-12-08 20:46:33', '2025-12-08 20:46:33'),
(37, 2, 'ORDEN DE SALIDA', 9, 'OrdenSalidaDetalle', 3, 'INGRESO POR ORDEN DE SALIDA', 300.00, 'INGRESO', 40, NULL, 42, 300.00, 12000.00, NULL, 12600.00, '2025-12-08', 1, '2025-12-08 20:46:33', '2025-12-08 20:46:33'),
(38, 1, 'ORDEN DE SALIDA', 10, 'OrdenSalidaDetalle', 4, 'EGRESO POR ORDEN DE SALIDA', 345.00, 'EGRESO', NULL, 40, 64, 345.00, NULL, 13800.00, 22080.00, '2025-12-08', 1, '2025-12-08 20:46:33', '2025-12-08 20:46:33'),
(39, 2, 'ORDEN DE SALIDA', 10, 'OrdenSalidaDetalle', 4, 'INGRESO POR ORDEN DE SALIDA', 345.00, 'INGRESO', 40, NULL, 42, 345.00, 13800.00, NULL, 14490.00, '2025-12-08', 1, '2025-12-08 20:46:33', '2025-12-08 20:46:33'),
(40, 2, 'ORDEN DE VENTA', 8, 'OrdenVentaDetalle', 3, 'EGRESO POR ORDEN DE VENTA', 300.00, 'EGRESO', NULL, 3, 39, 300.00, NULL, 900.00, 11700.00, '2025-12-08', 1, '2025-12-08 20:48:08', '2025-12-08 20:48:08'),
(41, 2, 'ORDEN DE VENTA', 9, 'OrdenVentaDetalle', 4, 'EGRESO POR ORDEN DE VENTA', 345.00, 'EGRESO', NULL, 3, 39, 345.00, NULL, 1035.00, 13455.00, '2025-12-08', 1, '2025-12-08 20:48:08', '2025-12-08 20:48:08'),
(42, 2, 'ORDEN DE VENTA', 12, 'OrdenVentaDetalle', 3, 'EGRESO POR ORDEN DE VENTA', 300.00, 'EGRESO', NULL, 2, 37, 300.00, NULL, 600.00, 11100.00, '2025-12-08', 1, '2025-12-08 23:24:33', '2025-12-08 23:24:33'),
(43, 2, 'ORDEN DE VENTA', 13, 'OrdenVentaDetalle', 4, 'EGRESO POR ORDEN DE VENTA', 345.00, 'EGRESO', NULL, 1, 38, 345.00, NULL, 345.00, 13110.00, '2025-12-08', 1, '2025-12-08 23:24:33', '2025-12-08 23:24:33'),
(44, 2, 'TRANSFERENCIA', 1, 'TransferenciaDetalle', 3, 'EGRESO POR TRANSFERENCIA', 300.00, 'EGRESO', NULL, 2, 35, 300.00, NULL, 600.00, 10500.00, '2025-12-09', 1, '2025-12-09 21:53:43', '2025-12-09 21:53:43'),
(45, 3, 'TRANSFERENCIA', 1, 'TransferenciaDetalle', 3, 'INGRESO POR TRANSFERENCIA', 300.00, 'INGRESO', 2, NULL, 6, 300.00, 600.00, NULL, 1800.00, '2025-12-09', 1, '2025-12-09 21:53:43', '2025-12-09 21:53:43'),
(46, 2, 'TRANSFERENCIA', 2, 'TransferenciaDetalle', 4, 'EGRESO POR TRANSFERENCIA', 345.00, 'EGRESO', NULL, 1, 37, 345.00, NULL, 345.00, 12765.00, '2025-12-09', 1, '2025-12-09 21:53:43', '2025-12-09 21:53:43'),
(47, 3, 'TRANSFERENCIA', 2, 'TransferenciaDetalle', 4, 'INGRESO POR TRANSFERENCIA', 345.00, 'INGRESO', 1, NULL, 4, 345.00, 345.00, NULL, 1380.00, '2025-12-09', 1, '2025-12-09 21:53:43', '2025-12-09 21:53:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'MARCA 1', NULL, '2025-12-02 13:21:58', '2025-12-02 13:21:58'),
(2, 'MARCA 2', NULL, '2025-12-02 13:22:11', '2025-12-02 13:28:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_31_165641_create_configuracions_table', 1),
(2, '2024_11_02_153309_create_roles_table', 1),
(3, '2024_11_02_153315_create_modulos_table', 1),
(4, '2024_11_02_153316_create_permisos_table', 1),
(5, '2024_11_02_153317_create_users_table', 1),
(6, '2024_11_02_153318_create_historial_accions_table', 1),
(7, '2025_11_30_112336_create_certificados_table', 1),
(8, '2025_11_30_112340_create_documentos_table', 1),
(9, '2025_11_30_112452_create_sucursals_table', 1),
(10, '2025_11_30_112713_create_categorias_table', 1),
(11, '2025_11_30_112809_create_sub_categorias_table', 1),
(12, '2025_11_30_112821_create_marcas_table', 1),
(13, '2025_11_30_112831_create_unidad_medidas_table', 1),
(14, '2025_11_30_112840_create_productos_table', 1),
(15, '2025_11_30_112900_create_clientes_table', 1),
(16, '2025_11_30_112913_create_proveedors_table', 1),
(17, '2025_11_30_112922_create_solicitud_ingresos_table', 1),
(18, '2025_11_30_112936_create_solicitud_ingreso_detalles_table', 1),
(19, '2025_11_30_112953_create_orden_salidas_table', 1),
(20, '2025_11_30_112956_create_orden_salida_detalles_table', 1),
(21, '2025_11_30_113012_create_devolucion_stocks_table', 1),
(22, '2025_11_30_113015_create_devolucion_stock_detalles_table', 1),
(23, '2025_11_30_113021_create_orden_ventas_table', 1),
(24, '2025_11_30_113024_create_orden_venta_detalles_table', 1),
(25, '2025_11_30_113042_create_transferencias_table', 1),
(26, '2025_11_30_113044_create_transferencia_detalles_table', 1),
(27, '2025_11_30_113050_create_devolucion_clientes_table', 1),
(28, '2025_11_30_113052_create_devolucion_cliente_detalles_table', 1),
(29, '2025_11_30_113101_create_cuenta_cobrars_table', 1),
(30, '2025_11_30_113104_create_cuenta_cobrar_detalles_table', 1),
(31, '2025_11_30_113114_create_gastos_table', 1),
(32, '2025_11_30_113119_create_proformas_table', 1),
(33, '2025_11_30_113122_create_proforma_detalles_table', 1),
(34, '2025_11_30_113745_create_sucursal_productos_table', 1),
(35, '2025_12_05_104619_create_kardex_productos_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` bigint UNSIGNED NOT NULL,
  `modulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `modulo`, `nombre`, `accion`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Gestión de usuarios', 'usuarios.index', 'VER', 'VER LA LISTA DE USUARIOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(2, 'Gestión de usuarios', 'usuarios.create', 'CREAR', 'CREAR USUARIOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(3, 'Gestión de usuarios', 'usuarios.edit', 'EDITAR', 'EDITAR USUARIOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(4, 'Gestión de usuarios', 'usuarios.destroy', 'ELIMINAR', 'ELIMINAR USUARIOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(5, 'Roles y Permisos', 'roles.index', 'VER', 'VER LA LISTA DE ROLES Y PERMISOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(6, 'Roles y Permisos', 'roles.create', 'CREAR', 'CREAR ROLES Y PERMISOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(7, 'Roles y Permisos', 'roles.edit', 'EDITAR', 'EDITAR ROLES Y PERMISOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(8, 'Roles y Permisos', 'roles.destroy', 'ELIMINAR', 'ELIMINAR ROLES Y PERMISOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(9, 'Configuración', 'configuracions.index', 'VER', 'VER INFORMACIÓN DE LA CONFIGURACIÓN DEL SISTEMA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(10, 'Configuración', 'configuracions.edit', 'EDITAR', 'EDITAR LA CONFIGURACIÓN DEL SISTEMA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(11, 'Sucursales', 'sucursals.index', 'VER', 'VER LA LISTA DE SUCURSALES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(12, 'Sucursales', 'sucursals.create', 'CREAR', 'CREAR SUCURSALES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(13, 'Sucursales', 'sucursals.edit', 'EDITAR', 'EDITAR SUCURSALES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(14, 'Sucursales', 'sucursals.destroy', 'ELIMINAR', 'ELIMINAR SUCURSALES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(15, 'Productos Sucursal', 'sucursal_productos.index', 'VER', 'VER LA LISTA DE PRODUCTOS DE SUCURSAL', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(16, 'Productos Sucursal', 'sucursal_productos.edit', 'EDITAR', 'EDITAR PRODUCTOS DE SUCURSAL', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(17, 'Categorías', 'categorias.index', 'VER', 'VER LA LISTA DE CATEGORÍAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(18, 'Categorías', 'categorias.create', 'CREAR', 'CREAR CATEGORÍAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(19, 'Categorías', 'categorias.edit', 'EDITAR', 'EDITAR CATEGORÍAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(20, 'Categorías', 'categorias.destroy', 'ELIMINAR', 'ELIMINAR CATEGORÍAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(21, 'Subcategorías', 'sub_categorias.index', 'VER', 'VER LA LISTA DE SUBCATEGORÍAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(22, 'Subcategorías', 'sub_categorias.create', 'CREAR', 'CREAR SUBCATEGORÍAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(23, 'Subcategorías', 'sub_categorias.edit', 'EDITAR', 'EDITAR SUBCATEGORÍAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(24, 'Subcategorías', 'sub_categorias.destroy', 'ELIMINAR', 'ELIMINAR SUBCATEGORÍAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(25, 'Marcas', 'marcas.index', 'VER', 'VER LA LISTA DE MARCAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(26, 'Marcas', 'marcas.create', 'CREAR', 'CREAR MARCAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(27, 'Marcas', 'marcas.edit', 'EDITAR', 'EDITAR MARCAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(28, 'Marcas', 'marcas.destroy', 'ELIMINAR', 'ELIMINAR MARCAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(29, 'Unidades de Medida', 'unidad_medidas.index', 'VER', 'VER LA LISTA DE UNIDADES DE MEDIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(30, 'Unidades de Medida', 'unidad_medidas.create', 'CREAR', 'CREAR UNIDADES DE MEDIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(31, 'Unidades de Medida', 'unidad_medidas.edit', 'EDITAR', 'EDITAR UNIDADES DE MEDIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(32, 'Unidades de Medida', 'unidad_medidas.destroy', 'ELIMINAR', 'ELIMINAR UNIDADES DE MEDIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(33, 'Productos', 'productos.index', 'VER', 'VER LA LISTA DE PRODUCTOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(34, 'Productos', 'productos.create', 'CREAR', 'CREAR PRODUCTOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(35, 'Productos', 'productos.edit', 'EDITAR', 'EDITAR PRODUCTOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(36, 'Productos', 'productos.destroy', 'ELIMINAR', 'ELIMINAR PRODUCTOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(37, 'Clientes', 'clientes.index', 'VER', 'VER LA LISTA DE CLIENTES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(38, 'Clientes', 'clientes.create', 'CREAR', 'CREAR CLIENTES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(39, 'Clientes', 'clientes.edit', 'EDITAR', 'EDITAR CLIENTES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(40, 'Clientes', 'clientes.destroy', 'ELIMINAR', 'ELIMINAR CLIENTES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(41, 'Proveedores', 'proveedors.index', 'VER', 'VER LA LISTA DE PROVEEDORES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(42, 'Proveedores', 'proveedors.create', 'CREAR', 'CREAR PROVEEDORES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(43, 'Proveedores', 'proveedors.edit', 'EDITAR', 'EDITAR PROVEEDORES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(44, 'Proveedores', 'proveedors.destroy', 'ELIMINAR', 'ELIMINAR PROVEEDORES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(45, 'Solicitud de Ingresos', 'solicitud_ingresos.index', 'VER', 'VER LA LISTA DE SOLICITUD DE INGRESOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(46, 'Solicitud de Ingresos', 'solicitud_ingresos.create', 'CREAR', 'CREAR SOLICITUD DE INGRESOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(47, 'Solicitud de Ingresos', 'solicitud_ingresos.edit', 'EDITAR', 'EDITAR SOLICITUD DE INGRESOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(48, 'Solicitud de Ingresos', 'solicitud_ingresos.aprobar', 'APROBAR', 'APROBAR SOLICITUD DE INGRESOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(49, 'Solicitud de Ingresos', 'solicitud_ingresos.destroy', 'ELIMINAR', 'ELIMINAR SOLICITUD DE INGRESOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(50, 'Ordenes de Salida', 'orden_salidas.index', 'VER', 'VER LA LISTA DE ORDENES DE SALIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(51, 'Ordenes de Salida', 'orden_salidas.create', 'CREAR', 'CREAR ORDENES DE SALIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(52, 'Ordenes de Salida', 'orden_salidas.edit', 'EDITAR', 'EDITAR ORDENES DE SALIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(53, 'Ordenes de Salida', 'orden_salidas.aprobar', 'APROBAR', 'APROBAR ORDENES DE SALIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(54, 'Ordenes de Salida', 'orden_salidas.destroy', 'ELIMINAR', 'ELIMINAR ORDENES DE SALIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(55, 'Devolución de Stock', 'devolucion_stocks.index', 'VER', 'VER LA LISTA DE DEVOLUCIÓN DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(56, 'Devolución de Stock', 'devolucion_stocks.create', 'CREAR', 'CREAR DEVOLUCIÓN DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(57, 'Devolución de Stock', 'devolucion_stocks.edit', 'EDITAR', 'EDITAR DEVOLUCIÓN DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(58, 'Devolución de Stock', 'devolucion_stocks.aprobar', 'APROBAR', 'APROBAR DEVOLUCIÓN DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(59, 'Devolución de Stock', 'devolucion_stocks.destroy', 'ELIMINAR', 'ELIMINAR DEVOLUCIÓN DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(60, 'Ordenes de Venta', 'orden_ventas.index', 'VER', 'VER LA LISTA DE ORDENDES DE VENTA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(61, 'Ordenes de Venta', 'orden_ventas.create', 'CREAR', 'CREAR ORDENDES DE VENTA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(62, 'Ordenes de Venta', 'orden_ventas.edit', 'EDITAR', 'EDITAR ORDENDES DE VENTA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(63, 'Ordenes de Venta', 'orden_ventas.aprobar_descuentos', 'APROBAR DESCUENTOS', 'APROBAR DESCUENTOS ORDENDES DE VENTA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(64, 'Ordenes de Venta', 'orden_ventas.destroy', 'ELIMINAR', 'ELIMINAR ORDENDES DE VENTA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(65, 'Transferencias de Stock', 'transferencias.index', 'VER', 'VER LA LISTA DE TRANSFERENCIAS DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(66, 'Transferencias de Stock', 'transferencias.create', 'CREAR', 'CREAR TRANSFERENCIAS DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(67, 'Transferencias de Stock', 'transferencias.edit', 'EDITAR', 'EDITAR TRANSFERENCIAS DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(68, 'Transferencias de Stock', 'transferencias.aprobar', 'APROBAR', 'APROBAR TRANSFERENCIAS DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(69, 'Transferencias de Stock', 'transferencias.destroy', 'ELIMINAR', 'ELIMINAR TRANSFERENCIAS DE STOCK', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(70, 'Devolución de Clientes', 'devolucion_clientes.index', 'VER', 'VER LA LISTA DE DEVOLUCIÓN DE CLIENTES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(71, 'Devolución de Clientes', 'devolucion_clientes.create', 'CREAR', 'CREAR DEVOLUCIÓN DE CLIENTES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(72, 'Devolución de Clientes', 'devolucion_clientes.edit', 'EDITAR', 'EDITAR DEVOLUCIÓN DE CLIENTES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(73, 'Devolución de Clientes', 'devolucion_clientes.destroy', 'ELIMINAR', 'ELIMINAR DEVOLUCIÓN DE CLIENTES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(74, 'Cuentas por Cobrar', 'cuenta_cobrars.index', 'VER', 'VER LA LISTA DE CUENTAS POR COBRAR', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(75, 'Cuentas por Cobrar', 'cuenta_cobrars.create', 'CREAR', 'CREAR CUENTAS POR COBRAR', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(76, 'Cuentas por Cobrar', 'cuenta_cobrars.edit', 'EDITAR', 'EDITAR CUENTAS POR COBRAR', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(77, 'Cuentas por Cobrar', 'cuenta_cobrars.destroy', 'ELIMINAR', 'ELIMINAR CUENTAS POR COBRAR', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(78, 'Registro de Gastos', 'gastos.index', 'VER', 'VER LA LISTA DE REGISTRO DE GASTOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(79, 'Registro de Gastos', 'gastos.create', 'CREAR', 'CREAR REGISTRO DE GASTOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(80, 'Registro de Gastos', 'gastos.edit', 'EDITAR', 'EDITAR REGISTRO DE GASTOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(81, 'Registro de Gastos', 'gastos.destroy', 'ELIMINAR', 'ELIMINAR REGISTRO DE GASTOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(82, 'Proformas', 'proformas.index', 'VER', 'VER LA LISTA DE PROFORMAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(83, 'Proformas', 'proformas.create', 'CREAR', 'CREAR PROFORMAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(84, 'Proformas', 'proformas.edit', 'EDITAR', 'EDITAR PROFORMAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(85, 'Proformas', 'proformas.destroy', 'ELIMINAR', 'ELIMINAR PROFORMAS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(86, 'Reportes', 'reportes.usuarios', 'REPORTE LISTA DE USUARIOS', 'GENERAR REPORTES DE LISTA DE USUARIOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(87, 'Reportes', 'reportes.productos', 'REPORTE LISTA DE PRODUCTOS', 'GENERAR REPORTES DE LISTA DE PRODUCTOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(88, 'Reportes', 'reportes.sucursals', 'REPORTE LISTA DE SUCURSALES', 'GENERAR REPORTES DE LISTA DE SUCURSALES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(89, 'Reportes', 'reportes.clientes', 'REPORTE LISTA DE CLIENTES', 'GENERAR REPORTES DE LISTA DE CLIENTES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(90, 'Reportes', 'reportes.proveedors', 'REPORTE LISTA DE PROVEEDORES', 'GENERAR REPORTES DE LISTA DE PROVEEDORES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(91, 'Reportes', 'reportes.inventario', 'REPORTE DE INVENTARIO', 'GENERAR REPORTES DE INVENTARIO', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(92, 'Reportes', 'reportes.movimiento_inventario', 'REPORTE DE MOVIMIENTO DE INVENTARIO', 'GENERAR REPORTES DE MOVIMIENTO DE INVENTARIO', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(93, 'Reportes', 'reportes.solicitud_ingresos', 'REPORTE DE SOLICITUDES DE INGRESO', 'GENERAR REPORTES DE SOLICITUDES DE INGRESO', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(94, 'Reportes', 'reportes.orden_salidas', 'REPORTE DE ORDENES DE SALIDA', 'GENERAR REPORTES DE ORDENES DE SALIDA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(95, 'Reportes', 'reportes.devolucions', 'REPORTE DE DEVOLUCIONES', 'GENERAR REPORTES DE DEVOLUCIONES', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(96, 'Reportes', 'reportes.orden_ventas', 'REPORTE DE ORDENES DE VENTA', 'GENERAR REPORTES DE ORDENES DE VENTA', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(97, 'Reportes', 'reportes.ejecutivos', 'REPORTE DE EJECUTIVOS/RESUMEN', 'GENERAR REPORTES DE EJECUTIVOS/RESUMEN', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(98, 'Reportes', 'reportes.diario_salidas', 'REPORTE DE DIARIO DE SALIDAS POR SUCURSAL', 'GENERAR REPORTES DE DIARIO DE SALIDAS POR SUCURSAL', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(99, 'Reportes', 'reportes.movimientos_abastecimiento', 'REPORTE DE SEMANAL DE MOVIMIENTOS Y ABASTECIMIENTO', 'GENERAR REPORTES DE SEMANAL DE MOVIMIENTOS Y ABASTECIMIENTO', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(100, 'Reportes', 'reportes.saldos_almacen_central', 'REPORTE DE SALDOS DEL ALMACÉN CENTRAL', 'GENERAR REPORTES DE SALDOS DEL ALMACÉN CENTRAL', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(101, 'Reportes', 'reportes.diario_vehiculos', 'REPORTE DE CONTROL DIARIO DE VEHÍCULOS', 'GENERAR REPORTES DE CONTROL DIARIO DE VEHÍCULOS', '2025-12-08 19:36:26', '2025-12-08 19:36:26'),
(102, 'Reportes', 'reportes.notas_entrega', 'REPORTE DE NOTAS DE ENTREGA', 'GENERAR REPORTES DE NOTAS DE ENTREGA', '2025-12-08 19:36:26', '2025-12-08 19:36:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_salidas`
--

CREATE TABLE `orden_salidas` (
  `id` bigint UNSIGNED NOT NULL,
  `nro` bigint NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `user_sol` bigint UNSIGNED NOT NULL,
  `user_ap` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad_total` double(8,2) NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orden_salidas`
--

INSERT INTO `orden_salidas` (`id`, `nro`, `codigo`, `sucursal_id`, `user_sol`, `user_ap`, `fecha`, `hora`, `observaciones`, `cantidad_total`, `total`, `estado`, `verificado`, `user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'SAL.1', 2, 15, 15, '2025-12-06', '10:15:00', 'OBSERVACIONES', 5.00, 1635.00, 'APROBADO', 1, 1, NULL, '2025-12-06 14:15:14', '2025-12-06 14:28:41'),
(2, 2, 'SAL.2', 3, 16, 16, '2025-12-06', '10:30:00', 'OBS', 4.00, 1200.00, 'APROBADO', 1, 1, NULL, '2025-12-06 14:30:25', '2025-12-06 14:30:40'),
(3, 3, 'SAL.3', 3, 16, 16, '2025-12-06', '10:31:00', '', 3.00, 1035.00, 'APROBADO', 1, 1, NULL, '2025-12-06 14:31:13', '2025-12-06 14:31:33'),
(4, 4, 'SAL.4', 2, 15, 15, '2025-12-06', '10:58:00', '', 1.00, 300.00, 'APROBADO', 1, 1, NULL, '2025-12-06 14:58:18', '2025-12-06 14:58:25'),
(5, 5, 'SAL.5', 2, 15, 15, '2025-12-06', '16:01:00', '', 2.00, 600.00, 'APROBADO', 1, 1, NULL, '2025-12-06 20:01:49', '2025-12-06 20:01:54'),
(6, 6, 'SAL.6', 2, 15, 15, '2025-12-08', '16:16:00', '', 3.00, 900.00, 'APROBADO', 1, 1, NULL, '2025-12-08 20:16:41', '2025-12-08 20:16:54'),
(7, 7, 'SAL.7', 2, 15, 15, '2025-12-08', '16:17:00', '', 3.00, 1035.00, 'APROBADO', 1, 1, NULL, '2025-12-08 20:17:20', '2025-12-08 20:17:25'),
(8, 8, 'SAL.8', 2, 15, 15, '2025-12-08', '16:46:00', '', 80.00, 25800.00, 'APROBADO', 1, 1, NULL, '2025-12-08 20:46:28', '2025-12-08 20:46:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_salida_detalles`
--

CREATE TABLE `orden_salida_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `orden_salida_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `cantidad_fisica` double NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `sucursal_ajuste` bigint UNSIGNED DEFAULT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orden_salida_detalles`
--

INSERT INTO `orden_salida_detalles` (`id`, `orden_salida_id`, `producto_id`, `cantidad`, `cantidad_fisica`, `costo`, `subtotal`, `verificado`, `sucursal_ajuste`, `motivo`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 2, 2, 300.00, 600.00, 1, NULL, NULL, '2025-12-06 14:15:14', '2025-12-06 14:15:14'),
(2, 1, 4, 3, 3, 345.00, 1035.00, 1, NULL, NULL, '2025-12-06 14:15:14', '2025-12-06 14:15:14'),
(3, 2, 3, 4, 4, 300.00, 1200.00, 1, NULL, NULL, '2025-12-06 14:30:25', '2025-12-06 14:30:40'),
(4, 3, 4, 3, 3, 345.00, 1035.00, 1, NULL, NULL, '2025-12-06 14:31:13', '2025-12-06 14:31:33'),
(5, 4, 3, 1, 1, 300.00, 300.00, 1, NULL, NULL, '2025-12-06 14:58:18', '2025-12-06 14:58:25'),
(6, 5, 3, 2, 2, 300.00, 600.00, 1, NULL, NULL, '2025-12-06 20:01:49', '2025-12-06 20:01:54'),
(7, 6, 3, 3, 3, 300.00, 900.00, 1, NULL, NULL, '2025-12-08 20:16:41', '2025-12-08 20:16:54'),
(8, 7, 4, 3, 3, 345.00, 1035.00, 1, NULL, NULL, '2025-12-08 20:17:20', '2025-12-08 20:17:25'),
(9, 8, 3, 40, 40, 300.00, 12000.00, 1, NULL, NULL, '2025-12-08 20:46:28', '2025-12-08 20:46:33'),
(10, 8, 4, 40, 40, 345.00, 13800.00, 1, NULL, NULL, '2025-12-08 20:46:28', '2025-12-08 20:46:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_ventas`
--

CREATE TABLE `orden_ventas` (
  `id` bigint UNSIGNED NOT NULL,
  `nro` bigint NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `cantidad_total` double NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `total_st` decimal(24,2) NOT NULL,
  `solicitud_descuento` int NOT NULL DEFAULT '0',
  `solicitud_sw` int DEFAULT '0',
  `user_ap` bigint UNSIGNED DEFAULT NULL,
  `monto_solicitud` decimal(24,2) DEFAULT '0.00',
  `descuento` decimal(24,2) DEFAULT '0.00',
  `total_f` decimal(24,2) NOT NULL,
  `cancelado` decimal(24,2) DEFAULT '0.00',
  `cambio` decimal(24,2) DEFAULT '0.00',
  `forma_pago` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cs_f` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orden_ventas`
--

INSERT INTO `orden_ventas` (`id`, `nro`, `codigo`, `sucursal_id`, `cliente_id`, `fecha`, `hora`, `cantidad_total`, `total`, `total_st`, `solicitud_descuento`, `solicitud_sw`, `user_ap`, `monto_solicitud`, `descuento`, `total_f`, `cancelado`, `cambio`, `forma_pago`, `cs_f`, `observaciones`, `estado`, `verificado`, `user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(4, 1, 'OV.1', 2, 1, '2025-12-06', '15:54:00', 1, 300.00, 0.00, 0, 0, NULL, 0.00, 0.00, 300.00, 300.00, 0.00, 'EFECTIVO', 'CON FACTURA', NULL, 'FINALIZADO', 2, 1, NULL, '2025-12-06 20:01:57', '2025-12-06 20:01:57'),
(5, 2, 'OV.2', 2, 1, '2025-12-06', '15:54:00', 1, 300.00, 0.00, 0, 0, NULL, 0.00, 0.00, 300.00, 300.00, 0.00, 'EFECTIVO', 'CON FACTURA', NULL, 'FINALIZADO', 2, 1, NULL, '2025-12-06 20:02:05', '2025-12-06 20:02:05'),
(7, 3, 'OV.3', 2, 2, '2025-12-08', '09:32:00', 2, 645.00, 645.00, 1, 1, 1, 20.00, 20.00, 625.00, 625.00, 0.00, 'EFECTIVO', 'SIN FACTURA', NULL, 'FINALIZADO', 2, 1, NULL, '2025-12-08 13:40:38', '2025-12-08 20:17:28'),
(8, 4, 'OV.4', 2, 1, '2025-12-08', '16:46:00', 6, 1935.00, 1935.00, 0, NULL, NULL, NULL, NULL, 1935.00, 0.00, 0.00, 'CRÉDITO', 'CON FACTURA', NULL, 'FINALIZADO', 2, 1, NULL, '2025-12-08 20:48:08', '2025-12-08 20:48:08'),
(9, 5, 'OV.5', 2, 1, '2025-12-08', '18:38:00', 2, 645.00, 645.00, 1, 0, NULL, 10.00, 10.00, 635.00, 635.00, 0.00, 'EFECTIVO', 'CON FACTURA', NULL, 'PENDIENTE', 0, 1, NULL, '2025-12-08 22:38:56', '2025-12-08 23:19:31'),
(10, 6, 'OV.6', 2, 1, '2025-12-08', '19:18:00', 3, 945.00, 945.00, 1, 1, 1, 10.00, 10.00, 935.00, 935.00, 0.00, 'EFECTIVO', 'CON FACTURA', NULL, 'FINALIZADO', 2, 1, NULL, '2025-12-08 23:19:09', '2025-12-08 23:24:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_venta_detalles`
--

CREATE TABLE `orden_venta_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `orden_venta_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `unidad_medida_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `descuento` decimal(24,2) NOT NULL,
  `subtotal_f` decimal(24,2) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orden_venta_detalles`
--

INSERT INTO `orden_venta_detalles` (`id`, `orden_venta_id`, `producto_id`, `unidad_medida_id`, `cantidad`, `precio`, `subtotal`, `descuento`, `subtotal_f`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 4, 3, 1, 1, 300.00, 300.00, 0.00, 300.00, NULL, '2025-12-06 20:01:57', '2025-12-06 20:01:57'),
(4, 5, 3, 1, 1, 300.00, 300.00, 0.00, 300.00, NULL, '2025-12-06 20:02:05', '2025-12-06 20:02:05'),
(6, 7, 3, 1, 1, 300.00, 300.00, 0.00, 300.00, NULL, '2025-12-08 13:40:38', '2025-12-08 20:17:28'),
(7, 7, 4, 2, 1, 345.00, 345.00, 0.00, 345.00, NULL, '2025-12-08 13:40:38', '2025-12-08 13:40:38'),
(8, 8, 3, 1, 3, 300.00, 900.00, 0.00, 900.00, NULL, '2025-12-08 20:48:08', '2025-12-08 20:48:08'),
(9, 8, 4, 2, 3, 345.00, 1035.00, 0.00, 1035.00, NULL, '2025-12-08 20:48:08', '2025-12-08 20:48:08'),
(10, 9, 3, 1, 1, 300.00, 300.00, 0.00, 300.00, NULL, '2025-12-08 22:38:56', '2025-12-08 22:38:56'),
(11, 9, 4, 2, 1, 345.00, 345.00, 0.00, 345.00, NULL, '2025-12-08 22:39:25', '2025-12-08 22:39:25'),
(12, 10, 3, 1, 2, 300.00, 600.00, 0.00, 600.00, NULL, '2025-12-08 23:19:09', '2025-12-08 23:23:56'),
(13, 10, 4, 2, 1, 345.00, 345.00, 0.00, 345.00, NULL, '2025-12-08 23:21:06', '2025-12-08 23:21:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `modulo_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint UNSIGNED NOT NULL,
  `codigo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidades_caja` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `marca_id` bigint UNSIGNED NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `precio_ppp` decimal(24,2) DEFAULT NULL,
  `ppp` int NOT NULL DEFAULT '0',
  `unidad_medida_id` bigint UNSIGNED NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `unidades_caja`, `descripcion`, `categoria_id`, `marca_id`, `precio`, `precio_ppp`, `ppp`, `unidad_medida_id`, `estado`, `imagen`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 'P001', 'PRODUCTO 1', 20, 'DESCRIPCION', 1, 1, 300.00, NULL, 0, 1, 1, '31764682791.png', NULL, '2025-12-02 13:39:51', '2025-12-02 13:46:07'),
(4, 'P002', 'PRODUCTO 2', 20, '', 2, 2, 345.00, NULL, 0, 2, 1, NULL, NULL, '2025-12-04 22:16:21', '2025-12-04 22:16:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proformas`
--

CREATE TABLE `proformas` (
  `id` bigint UNSIGNED NOT NULL,
  `nro` bigint NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `cantidad_total` double NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `total_st` decimal(24,2) NOT NULL,
  `solicitud_descuento` int DEFAULT '0',
  `descuento` decimal(24,2) NOT NULL,
  `total_f` decimal(24,2) NOT NULL,
  `forma_pago` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cs_f` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proformas`
--

INSERT INTO `proformas` (`id`, `nro`, `codigo`, `sucursal_id`, `cliente_id`, `fecha`, `hora`, `cantidad_total`, `total`, `total_st`, `solicitud_descuento`, `descuento`, `total_f`, `forma_pago`, `cs_f`, `observaciones`, `user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 1, 'PF.1', 2, 1, '2025-12-09', '16:25:00', 16, 5250.00, 5250.00, 1, 20.00, 5230.00, 'EFECTIVO', 'CON FACTURA', NULL, 1, NULL, '2025-12-09 20:30:52', '2025-12-09 21:15:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proforma_detalles`
--

CREATE TABLE `proforma_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `proforma_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `unidad_medida_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `descuento` decimal(24,2) NOT NULL,
  `subtotal_f` decimal(24,2) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proforma_detalles`
--

INSERT INTO `proforma_detalles` (`id`, `proforma_id`, `producto_id`, `unidad_medida_id`, `cantidad`, `precio`, `subtotal`, `descuento`, `subtotal_f`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, 3, 1, 6, 300.00, 1800.00, 0.00, 1800.00, NULL, '2025-12-09 20:30:52', '2025-12-09 20:30:52'),
(2, 3, 4, 2, 10, 345.00, 3450.00, 0.00, 3450.00, NULL, '2025-12-09 20:30:52', '2025-12-09 21:15:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedors`
--

CREATE TABLE `proveedors` (
  `id` bigint UNSIGNED NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_com` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moneda` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono_emp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `observaciones` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorias` json DEFAULT NULL,
  `marcas` json DEFAULT NULL,
  `contactos` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedors`
--

INSERT INTO `proveedors` (`id`, `razon_social`, `nombre_com`, `nit`, `moneda`, `fono_emp`, `correo`, `dir`, `ciudad`, `tipo`, `estado`, `observaciones`, `categorias`, `marcas`, `contactos`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'PROVEEDOR 1 S.A.', 'PROVEEDOR S.A.', '11111111', 'bolivianos', '222222', 'proveedor1@gmail.com', 'LOS OLIVOS #22', 'LA PAZ', 'PRODUCTOS', 1, 'OBSERVACIONES', '[1, 2]', '[1]', '[{\"cel\": \"67676767\", \"fono\": \"74454545\", \"nombre\": \"EDUARDO PEREZ\", \"observacion\": null}]', NULL, '2025-12-03 16:11:34', '2025-12-03 16:16:02'),
(2, 'PROVEEDOR 2 S.R.L', '', '121231', 'boliviano', '234234234', 'prove2@gmail.com', 'LOS OLIVOS #23', 'EL ALTO', 'MIXTO', 1, '', '[2]', '[2]', '[{\"cel\": \"7878787878\", \"fono\": \"MAMANI\", \"nombre\": \"JUAN\", \"observacion\": null}]', NULL, '2025-12-03 16:16:35', '2025-12-03 16:16:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permisos` int NOT NULL DEFAULT '0',
  `usuarios` int NOT NULL DEFAULT '1',
  `estado` int NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `permisos`, `usuarios`, `estado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'SUPER USUARIO', 1, 0, 1, NULL, NULL, NULL),
(2, 'ADMINISTRADOR', 0, 1, 1, NULL, '2025-11-30 16:44:28', '2025-11-30 16:44:28'),
(3, 'AUXILIAR', 0, 1, 1, NULL, '2025-11-30 16:44:33', '2025-11-30 16:44:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_ingresos`
--

CREATE TABLE `solicitud_ingresos` (
  `id` bigint UNSIGNED NOT NULL,
  `nro` bigint NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proveedor_id` bigint UNSIGNED NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `hora_ingreso` time NOT NULL,
  `fecha_sis` date NOT NULL,
  `hora_sis` time NOT NULL,
  `cs_f` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cambio` decimal(24,2) NOT NULL,
  `gastos` decimal(24,2) NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `cantidad_total` double NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `solicitud_ingresos`
--

INSERT INTO `solicitud_ingresos` (`id`, `nro`, `codigo`, `proveedor_id`, `fecha_ingreso`, `hora_ingreso`, `fecha_sis`, `hora_sis`, `cs_f`, `tipo_cambio`, `gastos`, `observaciones`, `descripcion`, `cantidad_total`, `total`, `estado`, `user_id`, `verificado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'SOL.1', 1, '2025-12-04', '18:48:00', '2025-12-05', '09:46:00', 'CON FATURA', 6.98, 0.00, 'OBS', 'DESC', 20, 6450.00, 'APROBADO', 1, 1, NULL, '2025-12-04 22:59:25', '2025-12-05 15:07:00'),
(2, 2, 'SOL.2', 2, '2025-12-05', '10:15:00', '2025-12-05', '10:15:00', 'SIN FATURA', 6.98, 0.00, '', '', 35, 10500.00, 'APROBADO', 1, 1, NULL, '2025-12-05 14:15:19', '2025-12-08 20:46:04'),
(3, 3, 'SOL.3', 2, '2025-12-08', '16:45:00', '2025-12-08', '16:45:00', 'CON FATURA', 6.98, 0.00, '', '', 200, 64500.00, 'APROBADO', 1, 1, NULL, '2025-12-08 20:45:57', '2025-12-08 20:46:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_ingreso_detalles`
--

CREATE TABLE `solicitud_ingreso_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `solicitud_ingreso_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `cantidad_fisica` double NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `sucursal_ajuste` bigint UNSIGNED DEFAULT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `solicitud_ingreso_detalles`
--

INSERT INTO `solicitud_ingreso_detalles` (`id`, `solicitud_ingreso_id`, `producto_id`, `cantidad`, `cantidad_fisica`, `costo`, `subtotal`, `verificado`, `sucursal_ajuste`, `motivo`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 10, 10, 300.00, 3000.00, 1, NULL, NULL, '2025-12-04 22:59:25', '2025-12-05 13:48:43'),
(2, 1, 4, 10, 10, 345.00, 3450.00, 1, NULL, NULL, '2025-12-04 22:59:25', '2025-12-05 13:48:43'),
(3, 2, 3, 35, 35, 300.00, 10500.00, 1, NULL, NULL, '2025-12-05 14:15:19', '2025-12-08 20:46:04'),
(4, 3, 3, 100, 100, 300.00, 30000.00, 1, NULL, NULL, '2025-12-08 20:45:57', '2025-12-08 20:46:08'),
(5, 3, 4, 100, 100, 345.00, 34500.00, 1, NULL, NULL, '2025-12-08 20:45:57', '2025-12-08 20:46:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_categorias`
--

CREATE TABLE `sub_categorias` (
  `id` bigint UNSIGNED NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sub_categorias`
--

INSERT INTO `sub_categorias` (`id`, `categoria_id`, `nombre`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'SUBCATEGORIA 1', NULL, '2025-12-02 13:16:52', '2025-12-02 13:16:57'),
(2, 2, 'SUBCATEGORIA 2', NULL, '2025-12-02 13:20:44', '2025-12-02 13:28:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursals`
--

CREATE TABLE `sucursals` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(800) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `almacen` int NOT NULL DEFAULT '0',
  `estado` int NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sucursals`
--

INSERT INTO `sucursals` (`id`, `nombre`, `direccion`, `fono`, `correo`, `user_id`, `almacen`, `estado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'ALMACEN', '', '', NULL, NULL, 1, 1, NULL, NULL, NULL),
(2, 'SUCURSAL 1', 'LOS OLIVOS', '43535335', NULL, 15, 0, 1, NULL, '2025-12-05 14:26:18', '2025-12-05 14:26:18'),
(3, 'SUCURSAL 2', 'DIR SUC 2', '7877878', NULL, 16, 0, 1, NULL, '2025-12-05 14:27:08', '2025-12-05 14:27:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal_productos`
--

CREATE TABLE `sucursal_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad_ideal` double NOT NULL DEFAULT '0',
  `cantidad_minima` double NOT NULL DEFAULT '0',
  `stock_actual` double NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sucursal_productos`
--

INSERT INTO `sucursal_productos` (`id`, `sucursal_id`, `producto_id`, `cantidad_ideal`, `cantidad_minima`, `stock_actual`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 5, 5, 96, NULL, '2025-12-05 15:07:00', '2025-12-08 20:46:33'),
(2, 1, 4, 0, 0, 64, NULL, '2025-12-05 15:07:00', '2025-12-08 20:46:33'),
(4, 2, 3, 3, 3, 35, NULL, '2025-12-05 16:08:14', '2025-12-09 21:53:43'),
(5, 2, 4, 2, 2, 37, NULL, '2025-12-05 16:14:58', '2025-12-09 21:53:43'),
(6, 3, 3, 0, 0, 6, NULL, '2025-12-06 14:30:40', '2025-12-09 21:53:43'),
(7, 3, 4, 0, 0, 4, NULL, '2025-12-06 14:31:33', '2025-12-09 21:53:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencias`
--

CREATE TABLE `transferencias` (
  `id` bigint UNSIGNED NOT NULL,
  `nro` bigint NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `sucursal_destino` bigint UNSIGNED NOT NULL,
  `user_sol` bigint UNSIGNED NOT NULL,
  `user_ap` bigint UNSIGNED NOT NULL,
  `cantidad_total` double(8,2) NOT NULL,
  `cantidad_total_v` double(8,2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transferencias`
--

INSERT INTO `transferencias` (`id`, `nro`, `codigo`, `sucursal_id`, `sucursal_destino`, `user_sol`, `user_ap`, `cantidad_total`, `cantidad_total_v`, `fecha`, `hora`, `observaciones`, `estado`, `verificado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 1, 'T.1', 2, 3, 15, 16, 3.00, 3.00, '2025-12-09', '17:44:00', '', 'APROBADO', 1, NULL, '2025-12-09 21:45:57', '2025-12-09 21:53:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencia_detalles`
--

CREATE TABLE `transferencia_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `transferencia_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `cantidad_fisica` double NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `sucursal_ajuste` bigint UNSIGNED DEFAULT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transferencia_detalles`
--

INSERT INTO `transferencia_detalles` (`id`, `transferencia_id`, `producto_id`, `cantidad`, `cantidad_fisica`, `costo`, `subtotal`, `verificado`, `sucursal_ajuste`, `motivo`, `created_at`, `updated_at`) VALUES
(1, 3, 3, 2, 2, 300.00, 600.00, 1, NULL, NULL, '2025-12-09 21:45:57', '2025-12-09 21:53:43'),
(2, 3, 4, 1, 1, 345.00, 345.00, 1, NULL, NULL, '2025-12-09 21:52:06', '2025-12-09 21:53:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medidas`
--

CREATE TABLE `unidad_medidas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `unidad_medidas`
--

INSERT INTO `unidad_medidas` (`id`, `nombre`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'UNIDAD 1', NULL, '2025-12-02 13:24:33', '2025-12-02 13:24:33'),
(2, 'UNIDAD 2A', NULL, '2025-12-02 13:24:38', '2025-12-02 13:29:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grupo_san` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionalidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profesion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel_dom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitud` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitud` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carnet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `acceso` int NOT NULL,
  `fecha_registro` date NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `grupo_san`, `sexo`, `nacionalidad`, `profesion`, `cel`, `fono`, `cel_dom`, `dir`, `latitud`, `longitud`, `correo`, `foto`, `carnet`, `password`, `tipo`, `role_id`, `acceso`, `fecha_registro`, `estado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', '', '0', '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'ADMINISTRADOR', 1, 1, '2025-11-30', 1, NULL, '2025-11-30 16:37:59', '2025-11-30 16:37:59'),
(15, 'juan@gmail.com', 'JUAN', 'PERES', 'MAMANI', '123456', 'LP', 'ORH+', 'MASCULINO', 'BOLIVIANO', 'PROFESION', '777777', '22222', '78', 'LOS PEDREGALES', '111111111', '1000000000', 'juan@gmail.com', '1764597654_15.jpg', NULL, '$2y$12$x0H.S52203ur4Vgu7POWP.UhZ73..PjPRYyYwToPadVmyH29DbhLO', 'USUARIO', 2, 1, '2025-12-01', 1, NULL, '2025-11-30 21:13:08', '2025-12-01 14:06:53'),
(16, 'maria@gmail.com', 'MARIA', 'GONZALES', '', '12312312', 'LP', 'ORH+', 'FEMENINO', 'BOLIVIANA', '', '67676767', '22232323', '676767', 'LOS OLIVOS', '11111', '11111', 'maria@gmail.com', NULL, NULL, '$2y$12$Br/h92SuGVk1alSb5xDlQOpbJBmH1n0xAc.rSGIWchdkqbmJv3m2O', 'USUARIO', 3, 1, '2025-12-01', 1, NULL, '2025-12-02 00:07:39', '2025-12-02 00:07:39'),
(17, 'jorge@gmail.com', 'JORGE', 'GONZALES', '', '453543', 'LP', 'ORH+', 'MASCULINO', 'BOLIVIANO', '', '67676767', '22322332', '65665', 'LOS PEDRAGLES', '111', '111', 'jorge@gmail.com', NULL, NULL, '$2y$12$sPwrwOPrLhuui2zoRLCFyOPfcBUx.4FkGRTtu8XeJxUAHCQscinau', 'USUARIO', 3, 1, '2025-12-01', 1, NULL, '2025-12-02 00:08:20', '2025-12-02 00:08:20');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificados_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuenta_cobrars`
--
ALTER TABLE `cuenta_cobrars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuenta_cobrars_cliente_id_foreign` (`cliente_id`),
  ADD KEY `cuenta_cobrars_orden_venta_id_foreign` (`orden_venta_id`);

--
-- Indices de la tabla `cuenta_cobrar_detalles`
--
ALTER TABLE `cuenta_cobrar_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuenta_cobrar_detalles_cuenta_cobrar_id_foreign` (`cuenta_cobrar_id`);

--
-- Indices de la tabla `devolucion_clientes`
--
ALTER TABLE `devolucion_clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devolucion_clientes_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `devolucion_clientes_cliente_id_foreign` (`cliente_id`),
  ADD KEY `devolucion_clientes_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `devolucion_cliente_detalles`
--
ALTER TABLE `devolucion_cliente_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devolucion_cliente_detalles_devolucion_cliente_id_foreign` (`devolucion_cliente_id`),
  ADD KEY `devolucion_cliente_detalles_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `devolucion_stocks`
--
ALTER TABLE `devolucion_stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `devolucion_stocks_codigo_unique` (`codigo`),
  ADD KEY `devolucion_stocks_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `devolucion_stocks_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `devolucion_stock_detalles`
--
ALTER TABLE `devolucion_stock_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devolucion_stock_detalles_devolucion_stock_id_foreign` (`devolucion_stock_id`),
  ADD KEY `devolucion_stock_detalles_producto_id_foreign` (`producto_id`),
  ADD KEY `devolucion_stock_detalles_sucursal_ajuste_foreign` (`sucursal_ajuste`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documentos_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_accions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kardex_productos_producto_id_foreign` (`producto_id`),
  ADD KEY `kardex_productos_sucursal_id_foreign` (`sucursal_id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden_salidas`
--
ALTER TABLE `orden_salidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orden_salidas_codigo_unique` (`codigo`),
  ADD KEY `orden_salidas_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `orden_salidas_user_sol_foreign` (`user_sol`),
  ADD KEY `orden_salidas_user_ap_foreign` (`user_ap`);

--
-- Indices de la tabla `orden_salida_detalles`
--
ALTER TABLE `orden_salida_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_salida_detalles_orden_salida_id_foreign` (`orden_salida_id`),
  ADD KEY `orden_salida_detalles_producto_id_foreign` (`producto_id`),
  ADD KEY `orden_salida_detalles_sucursal_ajuste_foreign` (`sucursal_ajuste`);

--
-- Indices de la tabla `orden_ventas`
--
ALTER TABLE `orden_ventas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orden_ventas_codigo_unique` (`codigo`),
  ADD KEY `orden_ventas_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `orden_ventas_cliente_id_foreign` (`cliente_id`),
  ADD KEY `orden_ventas_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `orden_venta_detalles`
--
ALTER TABLE `orden_venta_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_venta_detalles_orden_venta_id_foreign` (`orden_venta_id`),
  ADD KEY `orden_venta_detalles_producto_id_foreign` (`producto_id`),
  ADD KEY `orden_venta_detalles_unidad_medida_id_foreign` (`unidad_medida_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permisos_role_id_foreign` (`role_id`),
  ADD KEY `permisos_modulo_id_foreign` (`modulo_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productos_codigo_unique` (`codigo`),
  ADD KEY `productos_categoria_id_foreign` (`categoria_id`),
  ADD KEY `productos_marca_id_foreign` (`marca_id`),
  ADD KEY `productos_unidad_medida_id_foreign` (`unidad_medida_id`);

--
-- Indices de la tabla `proformas`
--
ALTER TABLE `proformas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proformas_codigo_unique` (`codigo`),
  ADD KEY `proformas_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `proformas_cliente_id_foreign` (`cliente_id`),
  ADD KEY `proformas_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `proforma_detalles`
--
ALTER TABLE `proforma_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proforma_detalles_proforma_id_foreign` (`proforma_id`),
  ADD KEY `proforma_detalles_producto_id_foreign` (`producto_id`),
  ADD KEY `proforma_detalles_unidad_medida_id_foreign` (`unidad_medida_id`);

--
-- Indices de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitud_ingresos`
--
ALTER TABLE `solicitud_ingresos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `solicitud_ingresos_codigo_unique` (`codigo`),
  ADD KEY `solicitud_ingresos_proveedor_id_foreign` (`proveedor_id`),
  ADD KEY `solicitud_ingresos_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `solicitud_ingreso_detalles`
--
ALTER TABLE `solicitud_ingreso_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud_ingreso_detalles_solicitud_ingreso_id_foreign` (`solicitud_ingreso_id`),
  ADD KEY `solicitud_ingreso_detalles_producto_id_foreign` (`producto_id`),
  ADD KEY `solicitud_ingreso_detalles_sucursal_ajuste_foreign` (`sucursal_ajuste`);

--
-- Indices de la tabla `sub_categorias`
--
ALTER TABLE `sub_categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_categorias_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `sucursals`
--
ALTER TABLE `sucursals`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sucursal_productos`
--
ALTER TABLE `sucursal_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sucursal_productos_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `sucursal_productos_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `transferencias`
--
ALTER TABLE `transferencias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transferencias_codigo_unique` (`codigo`),
  ADD KEY `transferencias_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `transferencias_sucursal_destino_foreign` (`sucursal_destino`),
  ADD KEY `transferencias_user_sol_foreign` (`user_sol`),
  ADD KEY `transferencias_user_ap_foreign` (`user_ap`);

--
-- Indices de la tabla `transferencia_detalles`
--
ALTER TABLE `transferencia_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transferencia_detalles_transferencia_id_foreign` (`transferencia_id`),
  ADD KEY `transferencia_detalles_producto_id_foreign` (`producto_id`),
  ADD KEY `transferencia_detalles_sucursal_ajuste_foreign` (`sucursal_ajuste`);

--
-- Indices de la tabla `unidad_medidas`
--
ALTER TABLE `unidad_medidas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `certificados`
--
ALTER TABLE `certificados`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cuenta_cobrars`
--
ALTER TABLE `cuenta_cobrars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cuenta_cobrar_detalles`
--
ALTER TABLE `cuenta_cobrar_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `devolucion_clientes`
--
ALTER TABLE `devolucion_clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devolucion_cliente_detalles`
--
ALTER TABLE `devolucion_cliente_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devolucion_stocks`
--
ALTER TABLE `devolucion_stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `devolucion_stock_detalles`
--
ALTER TABLE `devolucion_stock_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT de la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de la tabla `orden_salidas`
--
ALTER TABLE `orden_salidas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `orden_salida_detalles`
--
ALTER TABLE `orden_salida_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `orden_ventas`
--
ALTER TABLE `orden_ventas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `orden_venta_detalles`
--
ALTER TABLE `orden_venta_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `proformas`
--
ALTER TABLE `proformas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `proforma_detalles`
--
ALTER TABLE `proforma_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitud_ingresos`
--
ALTER TABLE `solicitud_ingresos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitud_ingreso_detalles`
--
ALTER TABLE `solicitud_ingreso_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sub_categorias`
--
ALTER TABLE `sub_categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sucursals`
--
ALTER TABLE `sucursals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sucursal_productos`
--
ALTER TABLE `sucursal_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `transferencias`
--
ALTER TABLE `transferencias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `transferencia_detalles`
--
ALTER TABLE `transferencia_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `unidad_medidas`
--
ALTER TABLE `unidad_medidas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `certificados`
--
ALTER TABLE `certificados`
  ADD CONSTRAINT `certificados_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `cuenta_cobrars`
--
ALTER TABLE `cuenta_cobrars`
  ADD CONSTRAINT `cuenta_cobrars_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `cuenta_cobrars_orden_venta_id_foreign` FOREIGN KEY (`orden_venta_id`) REFERENCES `orden_ventas` (`id`);

--
-- Filtros para la tabla `cuenta_cobrar_detalles`
--
ALTER TABLE `cuenta_cobrar_detalles`
  ADD CONSTRAINT `cuenta_cobrar_detalles_cuenta_cobrar_id_foreign` FOREIGN KEY (`cuenta_cobrar_id`) REFERENCES `cuenta_cobrars` (`id`);

--
-- Filtros para la tabla `devolucion_clientes`
--
ALTER TABLE `devolucion_clientes`
  ADD CONSTRAINT `devolucion_clientes_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `devolucion_clientes_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `devolucion_clientes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `devolucion_cliente_detalles`
--
ALTER TABLE `devolucion_cliente_detalles`
  ADD CONSTRAINT `devolucion_cliente_detalles_devolucion_cliente_id_foreign` FOREIGN KEY (`devolucion_cliente_id`) REFERENCES `devolucion_clientes` (`id`),
  ADD CONSTRAINT `devolucion_cliente_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `devolucion_stocks`
--
ALTER TABLE `devolucion_stocks`
  ADD CONSTRAINT `devolucion_stocks_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `devolucion_stocks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `devolucion_stock_detalles`
--
ALTER TABLE `devolucion_stock_detalles`
  ADD CONSTRAINT `devolucion_stock_detalles_devolucion_stock_id_foreign` FOREIGN KEY (`devolucion_stock_id`) REFERENCES `devolucion_stocks` (`id`),
  ADD CONSTRAINT `devolucion_stock_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `devolucion_stock_detalles_sucursal_ajuste_foreign` FOREIGN KEY (`sucursal_ajuste`) REFERENCES `sucursals` (`id`);

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD CONSTRAINT `historial_accions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  ADD CONSTRAINT `kardex_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `kardex_productos_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`);

--
-- Filtros para la tabla `orden_salidas`
--
ALTER TABLE `orden_salidas`
  ADD CONSTRAINT `orden_salidas_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `orden_salidas_user_ap_foreign` FOREIGN KEY (`user_ap`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orden_salidas_user_sol_foreign` FOREIGN KEY (`user_sol`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `orden_salida_detalles`
--
ALTER TABLE `orden_salida_detalles`
  ADD CONSTRAINT `orden_salida_detalles_orden_salida_id_foreign` FOREIGN KEY (`orden_salida_id`) REFERENCES `orden_salidas` (`id`),
  ADD CONSTRAINT `orden_salida_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `orden_salida_detalles_sucursal_ajuste_foreign` FOREIGN KEY (`sucursal_ajuste`) REFERENCES `sucursals` (`id`);

--
-- Filtros para la tabla `orden_ventas`
--
ALTER TABLE `orden_ventas`
  ADD CONSTRAINT `orden_ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `orden_ventas_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `orden_ventas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `orden_venta_detalles`
--
ALTER TABLE `orden_venta_detalles`
  ADD CONSTRAINT `orden_venta_detalles_orden_venta_id_foreign` FOREIGN KEY (`orden_venta_id`) REFERENCES `orden_ventas` (`id`),
  ADD CONSTRAINT `orden_venta_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `orden_venta_detalles_unidad_medida_id_foreign` FOREIGN KEY (`unidad_medida_id`) REFERENCES `unidad_medidas` (`id`);

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`),
  ADD CONSTRAINT `permisos_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  ADD CONSTRAINT `productos_unidad_medida_id_foreign` FOREIGN KEY (`unidad_medida_id`) REFERENCES `unidad_medidas` (`id`);

--
-- Filtros para la tabla `proformas`
--
ALTER TABLE `proformas`
  ADD CONSTRAINT `proformas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `proformas_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `proformas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `proforma_detalles`
--
ALTER TABLE `proforma_detalles`
  ADD CONSTRAINT `proforma_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `proforma_detalles_proforma_id_foreign` FOREIGN KEY (`proforma_id`) REFERENCES `proformas` (`id`),
  ADD CONSTRAINT `proforma_detalles_unidad_medida_id_foreign` FOREIGN KEY (`unidad_medida_id`) REFERENCES `unidad_medidas` (`id`);

--
-- Filtros para la tabla `solicitud_ingresos`
--
ALTER TABLE `solicitud_ingresos`
  ADD CONSTRAINT `solicitud_ingresos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedors` (`id`),
  ADD CONSTRAINT `solicitud_ingresos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `solicitud_ingreso_detalles`
--
ALTER TABLE `solicitud_ingreso_detalles`
  ADD CONSTRAINT `solicitud_ingreso_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `solicitud_ingreso_detalles_solicitud_ingreso_id_foreign` FOREIGN KEY (`solicitud_ingreso_id`) REFERENCES `solicitud_ingresos` (`id`),
  ADD CONSTRAINT `solicitud_ingreso_detalles_sucursal_ajuste_foreign` FOREIGN KEY (`sucursal_ajuste`) REFERENCES `sucursals` (`id`);

--
-- Filtros para la tabla `sub_categorias`
--
ALTER TABLE `sub_categorias`
  ADD CONSTRAINT `sub_categorias_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `sucursal_productos`
--
ALTER TABLE `sucursal_productos`
  ADD CONSTRAINT `sucursal_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `sucursal_productos_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`);

--
-- Filtros para la tabla `transferencias`
--
ALTER TABLE `transferencias`
  ADD CONSTRAINT `transferencias_sucursal_destino_foreign` FOREIGN KEY (`sucursal_destino`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `transferencias_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `transferencias_user_ap_foreign` FOREIGN KEY (`user_ap`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transferencias_user_sol_foreign` FOREIGN KEY (`user_sol`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `transferencia_detalles`
--
ALTER TABLE `transferencia_detalles`
  ADD CONSTRAINT `transferencia_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `transferencia_detalles_sucursal_ajuste_foreign` FOREIGN KEY (`sucursal_ajuste`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `transferencia_detalles_transferencia_id_foreign` FOREIGN KEY (`transferencia_id`) REFERENCES `transferencias` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
