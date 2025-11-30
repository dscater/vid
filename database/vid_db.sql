-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-11-2025 a las 16:44:39
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
  `nombre_con` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono_cor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel_con` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `estado` int NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'VID S.A.', 'VID', 'logo.png', '2025-11-30 16:37:59', '2025-11-30 16:37:59');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ROLE', '{\"id\": 3, \"nombre\": \"AUXILIAR\", \"created_at\": \"2025-11-30T16:44:33.000000Z\", \"updated_at\": \"2025-11-30T16:44:33.000000Z\"}', NULL, 'ROLES', '2025-11-30', '12:44:33', '2025-11-30 16:44:33', '2025-11-30 16:44:33');

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
(35, '2025_11_30_115337_create_proveedor_contactos_table', 1);

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
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `forma_pago` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cs_f` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `precio_ppp` decimal(24,2) NOT NULL,
  `ppp` int NOT NULL DEFAULT '0',
  `unidad_medida_id` bigint UNSIGNED NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `forma_pago` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cs_f` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `categorias` json NOT NULL,
  `marcas` json NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor_contactos`
--

CREATE TABLE `proveedor_contactos` (
  `id` bigint UNSIGNED NOT NULL,
  `proveedor_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_corp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `user_id` bigint UNSIGNED NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal_productos`
--

CREATE TABLE `sucursal_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad_ideal` double NOT NULL,
  `cantidad_minima` double NOT NULL,
  `cantidad_m` double NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'admin', 'admin', 'admin', '', '0', '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'ADMINISTRADOR', 1, 1, '2025-11-30', 1, NULL, '2025-11-30 16:37:59', '2025-11-30 16:37:59');

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
-- Indices de la tabla `proveedor_contactos`
--
ALTER TABLE `proveedor_contactos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_contactos_proveedor_id_foreign` (`proveedor_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `certificados`
--
ALTER TABLE `certificados`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cuenta_cobrars`
--
ALTER TABLE `cuenta_cobrars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuenta_cobrar_detalles`
--
ALTER TABLE `cuenta_cobrar_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devolucion_stock_detalles`
--
ALTER TABLE `devolucion_stock_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_salidas`
--
ALTER TABLE `orden_salidas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_salida_detalles`
--
ALTER TABLE `orden_salida_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_ventas`
--
ALTER TABLE `orden_ventas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_venta_detalles`
--
ALTER TABLE `orden_venta_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proformas`
--
ALTER TABLE `proformas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proforma_detalles`
--
ALTER TABLE `proforma_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor_contactos`
--
ALTER TABLE `proveedor_contactos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitud_ingresos`
--
ALTER TABLE `solicitud_ingresos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_ingreso_detalles`
--
ALTER TABLE `solicitud_ingreso_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sub_categorias`
--
ALTER TABLE `sub_categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursals`
--
ALTER TABLE `sucursals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursal_productos`
--
ALTER TABLE `sucursal_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transferencias`
--
ALTER TABLE `transferencias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transferencia_detalles`
--
ALTER TABLE `transferencia_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unidad_medidas`
--
ALTER TABLE `unidad_medidas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Filtros para la tabla `proveedor_contactos`
--
ALTER TABLE `proveedor_contactos`
  ADD CONSTRAINT `proveedor_contactos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedors` (`id`);

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
