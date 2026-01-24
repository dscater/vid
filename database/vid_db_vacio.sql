-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 24-01-2026 a las 18:38:35
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
-- Estructura de tabla para la tabla `ajustes`
--

CREATE TABLE `ajustes` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `sucursal_origen` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `motivo` varchar(900) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modelo_id` bigint UNSIGNED DEFAULT NULL,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registro_id` bigint UNSIGNED DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajuste_reposicions`
--

CREATE TABLE `ajuste_reposicions` (
  `id` bigint UNSIGNED NOT NULL,
  `ajuste_id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `razon_social` varchar(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_punto` varchar(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_prop` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_prop` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ranking` int DEFAULT NULL,
  `categoria` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` double(24,4) DEFAULT NULL,
  `factor` double(8,4) DEFAULT NULL,
  `total_credito` decimal(24,2) DEFAULT '0.00',
  `contactos` json DEFAULT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `credito` int NOT NULL DEFAULT '0',
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
  `nombre_sistema` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `cantidad_total` double NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `cantidad_total_v` double NOT NULL,
  `total_v` decimal(24,2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `user_ver` bigint UNSIGNED DEFAULT NULL,
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
  `cantidad_existente` double(8,2) NOT NULL,
  `cantidad` double NOT NULL,
  `cantidad_restante` double(8,2) NOT NULL,
  `cantidad_fisica` double NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `sucursal_ajuste` bigint UNSIGNED DEFAULT NULL,
  `motivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id` bigint UNSIGNED NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `accion` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` json DEFAULT NULL,
  `datos_nuevo` json DEFAULT NULL,
  `modulo` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_productos`
--

CREATE TABLE `kardex_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `tipo_registro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registro_id` bigint UNSIGNED DEFAULT NULL,
  `modulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `detalle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(24,2) DEFAULT NULL,
  `tipo_is` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad_ingreso` double DEFAULT NULL,
  `cantidad_salida` double DEFAULT NULL,
  `cantidad_saldo` double NOT NULL,
  `cu` decimal(24,2) NOT NULL,
  `monto_ingreso` decimal(24,2) DEFAULT NULL,
  `monto_salida` decimal(24,2) DEFAULT NULL,
  `monto_saldo` decimal(24,2) NOT NULL,
  `fecha` date NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
(35, '2025_12_05_104619_create_kardex_productos_table', 2),
(36, '2025_12_12_192658_create_parametro_clientes_table', 3),
(37, '2025_12_12_201441_add_index_cliente_fecha_to_orden_ventas_table', 4),
(38, '2025_12_12_202747_create_jobs_table', 5),
(39, '2025_12_12_202751_create_failed_jobs_table', 5),
(40, '2025_12_13_121456_create_notificacions_table', 6),
(41, '2025_12_13_121459_create_notificacion_users_table', 6),
(42, '2025_12_18_163351_create_proforma_productos_table', 7),
(43, '2025_12_18_163352_create_proforma_detalle_productos_table', 7),
(44, '2026_01_06_093651_create_ajustes_table', 8),
(45, '2026_01_06_094923_create_ajuste_reposicions_table', 9),
(46, '2026_01_07_092602_create_movimiento_horas_table', 10),
(47, '2026_01_07_093115_create_parametro_sucursals_table', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` bigint UNSIGNED NOT NULL,
  `modulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `modulo`, `nombre`, `accion`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Perfil de Usuario', 'profile.edit', 'EDITAR CONTRASEÑA', 'ACTUALIZAR CONTRASEÑA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(2, 'Gestión de usuarios', 'usuarios.index', 'VER', 'VER LA LISTA DE USUARIOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(3, 'Gestión de usuarios', 'usuarios.create', 'CREAR', 'CREAR USUARIOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(4, 'Gestión de usuarios', 'usuarios.edit', 'EDITAR', 'EDITAR USUARIOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(5, 'Gestión de usuarios', 'usuarios.destroy', 'ELIMINAR', 'ELIMINAR USUARIOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(6, 'Gestión de usuarios', 'usuarios.password', 'CAMBIAR CONTRASEÑA', 'CAMBIAR CONTRASEÑA DE USUARIOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(7, 'Roles y Permisos', 'roles.index', 'VER', 'VER LA LISTA DE ROLES Y PERMISOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(8, 'Roles y Permisos', 'roles.create', 'CREAR', 'CREAR ROLES Y PERMISOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(9, 'Roles y Permisos', 'roles.edit', 'EDITAR', 'EDITAR ROLES Y PERMISOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(10, 'Roles y Permisos', 'roles.destroy', 'ELIMINAR', 'ELIMINAR ROLES Y PERMISOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(11, 'Configuración', 'configuracions.index', 'VER', 'VER INFORMACIÓN DE LA CONFIGURACIÓN DEL SISTEMA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(12, 'Configuración', 'configuracions.edit', 'EDITAR', 'EDITAR LA CONFIGURACIÓN DEL SISTEMA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(13, 'Sucursales', 'sucursals.index', 'VER', 'VER LA LISTA DE SUCURSALES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(14, 'Sucursales', 'sucursals.create', 'CREAR', 'CREAR SUCURSALES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(15, 'Sucursales', 'sucursals.edit', 'EDITAR', 'EDITAR SUCURSALES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(16, 'Sucursales', 'sucursals.destroy', 'ELIMINAR', 'ELIMINAR SUCURSALES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(17, 'Productos Sucursal', 'sucursal_productos.index', 'VER', 'VER LA LISTA DE PRODUCTOS DE SUCURSAL', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(18, 'Productos Sucursal', 'sucursal_productos.edit', 'EDITAR', 'EDITAR PRODUCTOS DE SUCURSAL', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(19, 'Categorías', 'categorias.index', 'VER', 'VER LA LISTA DE CATEGORÍAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(20, 'Categorías', 'categorias.create', 'CREAR', 'CREAR CATEGORÍAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(21, 'Categorías', 'categorias.edit', 'EDITAR', 'EDITAR CATEGORÍAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(22, 'Categorías', 'categorias.destroy', 'ELIMINAR', 'ELIMINAR CATEGORÍAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(23, 'Subcategorías', 'sub_categorias.index', 'VER', 'VER LA LISTA DE SUBCATEGORÍAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(24, 'Subcategorías', 'sub_categorias.create', 'CREAR', 'CREAR SUBCATEGORÍAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(25, 'Subcategorías', 'sub_categorias.edit', 'EDITAR', 'EDITAR SUBCATEGORÍAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(26, 'Subcategorías', 'sub_categorias.destroy', 'ELIMINAR', 'ELIMINAR SUBCATEGORÍAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(27, 'Marcas', 'marcas.index', 'VER', 'VER LA LISTA DE MARCAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(28, 'Marcas', 'marcas.create', 'CREAR', 'CREAR MARCAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(29, 'Marcas', 'marcas.edit', 'EDITAR', 'EDITAR MARCAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(30, 'Marcas', 'marcas.destroy', 'ELIMINAR', 'ELIMINAR MARCAS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(31, 'Unidades de Medida', 'unidad_medidas.index', 'VER', 'VER LA LISTA DE UNIDADES DE MEDIDA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(32, 'Unidades de Medida', 'unidad_medidas.create', 'CREAR', 'CREAR UNIDADES DE MEDIDA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(33, 'Unidades de Medida', 'unidad_medidas.edit', 'EDITAR', 'EDITAR UNIDADES DE MEDIDA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(34, 'Unidades de Medida', 'unidad_medidas.destroy', 'ELIMINAR', 'ELIMINAR UNIDADES DE MEDIDA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(35, 'Productos', 'productos.index', 'VER', 'VER LA LISTA DE PRODUCTOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(36, 'Productos', 'productos.create', 'CREAR', 'CREAR PRODUCTOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(37, 'Productos', 'productos.edit', 'EDITAR', 'EDITAR PRODUCTOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(38, 'Productos', 'productos.destroy', 'ELIMINAR', 'ELIMINAR PRODUCTOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(39, 'Clientes', 'clientes.index', 'VER', 'VER LA LISTA DE CLIENTES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(40, 'Clientes', 'clientes.create', 'CREAR', 'CREAR CLIENTES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(41, 'Clientes', 'clientes.edit', 'EDITAR', 'EDITAR CLIENTES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(42, 'Clientes', 'clientes.destroy', 'ELIMINAR', 'ELIMINAR CLIENTES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(43, 'Clientes', 'clientes.parametro_clientes', 'EDITAR PARAMETROS', 'EDITAR LOS PARAMETROS PARA EL CÁLCULO DE RANKING', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(44, 'Proveedores', 'proveedors.index', 'VER', 'VER LA LISTA DE PROVEEDORES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(45, 'Proveedores', 'proveedors.create', 'CREAR', 'CREAR PROVEEDORES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(46, 'Proveedores', 'proveedors.edit', 'EDITAR', 'EDITAR PROVEEDORES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(47, 'Proveedores', 'proveedors.destroy', 'ELIMINAR', 'ELIMINAR PROVEEDORES', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(48, 'Solicitud de Ingresos', 'solicitud_ingresos.index', 'VER', 'VER LA LISTA DE SOLICITUD DE INGRESOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(49, 'Solicitud de Ingresos', 'solicitud_ingresos.create', 'CREAR', 'CREAR SOLICITUD DE INGRESOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(50, 'Solicitud de Ingresos', 'solicitud_ingresos.edit', 'EDITAR', 'EDITAR SOLICITUD DE INGRESOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(51, 'Solicitud de Ingresos', 'solicitud_ingresos.aprobar', 'APROBAR', 'APROBAR SOLICITUD DE INGRESOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(52, 'Solicitud de Ingresos', 'solicitud_ingresos.aprobar_costos', 'APROBAR COSTOS', 'APROBAR COSTOS SOLICITUD DE INGRESOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(53, 'Solicitud de Ingresos', 'solicitud_ingresos.destroy', 'ELIMINAR', 'ELIMINAR SOLICITUD DE INGRESOS', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(54, 'Ordenes de Salida', 'orden_salidas.index', 'VER', 'VER LA LISTA DE ORDENES DE SALIDA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(55, 'Ordenes de Salida', 'orden_salidas.create', 'CREAR', 'CREAR ORDENES DE SALIDA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(56, 'Ordenes de Salida', 'orden_salidas.edit', 'EDITAR', 'EDITAR ORDENES DE SALIDA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(57, 'Ordenes de Salida', 'orden_salidas.aprobar', 'APROBAR', 'APROBAR ORDENES DE SALIDA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(58, 'Ordenes de Salida', 'orden_salidas.destroy', 'ELIMINAR', 'ELIMINAR ORDENES DE SALIDA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(59, 'Devolución de Stock', 'devolucion_stocks.index', 'VER', 'VER LA LISTA DE DEVOLUCIÓN DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(60, 'Devolución de Stock', 'devolucion_stocks.create', 'CREAR', 'CREAR DEVOLUCIÓN DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(61, 'Devolución de Stock', 'devolucion_stocks.edit', 'EDITAR', 'EDITAR DEVOLUCIÓN DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(62, 'Devolución de Stock', 'devolucion_stocks.aprobar', 'APROBAR', 'APROBAR DEVOLUCIÓN DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(63, 'Devolución de Stock', 'devolucion_stocks.destroy', 'ELIMINAR', 'ELIMINAR DEVOLUCIÓN DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(64, 'Ordenes de Venta', 'orden_ventas.index', 'VER', 'VER LA LISTA DE ORDENDES DE VENTA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(65, 'Ordenes de Venta', 'orden_ventas.create', 'CREAR', 'CREAR ORDENDES DE VENTA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(66, 'Ordenes de Venta', 'orden_ventas.edit', 'EDITAR', 'EDITAR ORDENDES DE VENTA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(67, 'Ordenes de Venta', 'orden_ventas.aprobar_descuentos', 'APROBAR DESCUENTOS', 'APROBAR DESCUENTOS ORDENDES DE VENTA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(68, 'Ordenes de Venta', 'orden_ventas.destroy', 'ELIMINAR', 'ELIMINAR ORDENDES DE VENTA', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(69, 'Transferencias de Stock', 'transferencias.index', 'VER', 'VER LA LISTA DE TRANSFERENCIAS DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(70, 'Transferencias de Stock', 'transferencias.create', 'CREAR', 'CREAR TRANSFERENCIAS DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(71, 'Transferencias de Stock', 'transferencias.edit', 'EDITAR', 'EDITAR TRANSFERENCIAS DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(72, 'Transferencias de Stock', 'transferencias.aprobar', 'APROBAR', 'APROBAR TRANSFERENCIAS DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(73, 'Transferencias de Stock', 'transferencias.destroy', 'ELIMINAR', 'ELIMINAR TRANSFERENCIAS DE STOCK', '2026-01-16 19:10:40', '2026-01-16 19:10:40'),
(74, 'Devolución de Clientes', 'devolucion_clientes.index', 'VER', 'VER LA LISTA DE DEVOLUCIÓN DE CLIENTES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(75, 'Devolución de Clientes', 'devolucion_clientes.create', 'CREAR', 'CREAR DEVOLUCIÓN DE CLIENTES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(76, 'Devolución de Clientes', 'devolucion_clientes.edit', 'EDITAR', 'EDITAR DEVOLUCIÓN DE CLIENTES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(77, 'Devolución de Clientes', 'devolucion_clientes.destroy', 'ELIMINAR', 'ELIMINAR DEVOLUCIÓN DE CLIENTES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(78, 'Cuentas por Cobrar', 'cuenta_cobrars.index', 'VER', 'VER LA LISTA DE CUENTAS POR COBRAR', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(79, 'Cuentas por Cobrar', 'cuenta_cobrars.create', 'CREAR', 'CREAR CUENTAS POR COBRAR', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(80, 'Cuentas por Cobrar', 'orden_ventas.pago', 'REGISTRAR PAGO', 'REGISTRAR PAGOS DE CUENTAS POR COBRAR', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(81, 'Cuentas por Cobrar', 'cuenta_cobrars.destroy', 'ELIMINAR', 'ELIMINAR CUENTAS POR COBRAR', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(82, 'Registro de Gastos', 'gastos.index', 'VER', 'VER LA LISTA DE REGISTRO DE GASTOS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(83, 'Registro de Gastos', 'gastos.create', 'CREAR', 'CREAR REGISTRO DE GASTOS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(84, 'Registro de Gastos', 'gastos.edit', 'EDITAR', 'EDITAR REGISTRO DE GASTOS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(85, 'Registro de Gastos', 'gastos.destroy', 'ELIMINAR', 'ELIMINAR REGISTRO DE GASTOS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(86, 'Proformas', 'proformas.index', 'VER', 'VER LA LISTA DE PROFORMAS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(87, 'Proformas', 'proformas.create', 'CREAR', 'CREAR PROFORMAS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(88, 'Proformas', 'proformas.edit', 'EDITAR', 'EDITAR PROFORMAS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(89, 'Proformas', 'proformas.destroy', 'ELIMINAR', 'ELIMINAR PROFORMAS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(90, 'Ajustes', 'ajustes.index', 'VER', 'VER LA LISTA DE AJUSTES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(91, 'Ajustes', 'ajustes.edit', 'EDITAR', 'REPONER AJUSTES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(92, 'Notificaciones', 'notificacions.index', 'VER', 'VER Y RECIBIR NOTIFICACIONES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(93, 'Reportes', 'reportes.usuarios', 'REPORTE LISTA DE USUARIOS', 'GENERAR REPORTES DE LISTA DE USUARIOS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(94, 'Reportes', 'reportes.productos', 'REPORTE LISTA DE PRODUCTOS', 'GENERAR REPORTES DE LISTA DE PRODUCTOS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(95, 'Reportes', 'reportes.clientes', 'REPORTE LISTA DE CLIENTES', 'GENERAR REPORTES DE LISTA DE CLIENTES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(96, 'Reportes', 'reportes.proveedors', 'REPORTE LISTA DE PROVEEDORES', 'GENERAR REPORTES DE LISTA DE PROVEEDORES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(97, 'Reportes', 'reportes.movimiento_inventario', 'REPORTE DE MOVIMIENTO DE INVENTARIO', 'GENERAR REPORTES DE MOVIMIENTO DE INVENTARIO', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(98, 'Reportes', 'reportes.solicitud_ingresos', 'REPORTE DE SOLICITUDES DE INGRESO', 'GENERAR REPORTES DE SOLICITUDES DE INGRESO', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(99, 'Reportes', 'reportes.orden_salidas', 'REPORTE DE ORDENES DE SALIDA', 'GENERAR REPORTES DE ORDENES DE SALIDA', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(100, 'Reportes', 'reportes.devolucions', 'REPORTE DE DEVOLUCIONES', 'GENERAR REPORTES DE DEVOLUCIONES', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(101, 'Reportes', 'reportes.orden_ventas', 'REPORTE DE ORDENES DE VENTA', 'GENERAR REPORTES DE ORDENES DE VENTA', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(102, 'Reportes', 'reportes.utilidad_ordens', 'REPORTE DE UTILIDADES DE ORDENES DE VENTA', 'GENERAR REPORTES DE UTILIDADES DE ORDENES DE VENTA', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(103, 'Reportes', 'reportes.cuenta_cobrars', 'REPORTE DE CUENTAS POR COBRAR', 'GENERAR REPORTES DE CUENTAS POR COBRAR', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(104, 'Reportes', 'reportes.rotacion', 'REPORTE DE ROTACIÓN DE INVENTARIO', 'GENERAR REPORTES DE ROTACIÓN DE INVENTARIO', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(105, 'Reportes', 'reportes.gastos', 'REPORTE DE GASTOS', 'GENERAR REPORTES DE GASTOS', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(106, 'Reportes', 'reportes.diario_salidas', 'REPORTE DE DIARIO DE SALIDAS POR SUCURSAL', 'GENERAR REPORTES DE DIARIO DE SALIDAS POR SUCURSAL', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(107, 'Reportes', 'reportes.movimientos_abastecimiento', 'REPORTE DE SEMANAL DE MOVIMIENTOS Y ABASTECIMIENTO', 'GENERAR REPORTES DE SEMANAL DE MOVIMIENTOS Y ABASTECIMIENTO', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(108, 'Reportes', 'reportes.saldos_almacen_central', 'REPORTE DE SALDOS DEL ALMACÉN CENTRAL', 'GENERAR REPORTES DE SALDOS DEL ALMACÉN CENTRAL', '2026-01-16 19:10:41', '2026-01-16 19:10:41'),
(109, 'Reportes', 'reportes.diario_vehiculos', 'REPORTE DE CONTROL DIARIO DE VEHÍCULOS', 'GENERAR REPORTES DE CONTROL DIARIO DE VEHÍCULOS', '2026-01-16 19:10:41', '2026-01-16 19:10:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_horas`
--

CREATE TABLE `movimiento_horas` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `cantidad_inicial` double NOT NULL DEFAULT '0',
  `hora_inicial` time DEFAULT NULL,
  `cantidad_final` double NOT NULL DEFAULT '0',
  `hora_final` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacions`
--

CREATE TABLE `notificacions` (
  `id` bigint UNSIGNED NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modulo_id` bigint UNSIGNED DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion_users`
--

CREATE TABLE `notificacion_users` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `notificacion_id` bigint UNSIGNED NOT NULL,
  `visto` int NOT NULL DEFAULT '0',
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
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `user_sol` bigint UNSIGNED NOT NULL,
  `user_ap` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad_total` double(8,2) NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL,
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
  `motivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `descuento_sugerido` decimal(24,2) NOT NULL,
  `descuento` decimal(24,2) DEFAULT '0.00',
  `total_f` decimal(24,2) NOT NULL,
  `cancelado` decimal(24,2) DEFAULT '0.00',
  `con` int NOT NULL DEFAULT '0',
  `cancelado_c` decimal(24,2) DEFAULT '0.00',
  `qr` int NOT NULL DEFAULT '0',
  `cancelado_qr` decimal(24,2) DEFAULT NULL,
  `cre` int NOT NULL DEFAULT '0',
  `credito` decimal(24,2) DEFAULT NULL,
  `cambio` decimal(24,2) DEFAULT '0.00',
  `forma_pago` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cs_f` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Estructura de tabla para la tabla `parametro_clientes`
--

CREATE TABLE `parametro_clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `valor1` double NOT NULL,
  `valor2` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `parametro_clientes`
--

INSERT INTO `parametro_clientes` (`id`, `valor1`, `valor2`, `created_at`, `updated_at`) VALUES
(1, 0.089, 0.462, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametro_sucursals`
--

CREATE TABLE `parametro_sucursals` (
  `id` bigint UNSIGNED NOT NULL,
  `valor1` time NOT NULL,
  `valor2` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `parametro_sucursals`
--

INSERT INTO `parametro_sucursals` (`id`, `valor1`, `valor2`, `created_at`, `updated_at`) VALUES
(1, '10:28:00', '20:00:00', NULL, '2026-01-07 14:28:03');

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
  `codigo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidades_caja` int NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proformas`
--

CREATE TABLE `proformas` (
  `id` bigint UNSIGNED NOT NULL,
  `nro` bigint NOT NULL,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_ids` json NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `cantidad_total` double DEFAULT NULL,
  `total` decimal(24,2) DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `cliente_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `cantidad_entregada` double(8,2) NOT NULL DEFAULT '0.00',
  `total` decimal(24,2) NOT NULL,
  `saldo` decimal(24,2) NOT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proforma_detalle_productos`
--

CREATE TABLE `proforma_detalle_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `proforma_id` bigint UNSIGNED NOT NULL,
  `proforma_detalle_id` bigint UNSIGNED NOT NULL,
  `proforma_producto_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `unidad_medida_id` bigint UNSIGNED NOT NULL,
  `cantidad` double DEFAULT NULL,
  `cantidad_entregada` double DEFAULT NULL,
  `resta` double(8,2) NOT NULL DEFAULT '0.00',
  `precio` decimal(24,2) DEFAULT NULL,
  `subtotal` decimal(24,2) DEFAULT NULL,
  `verificado` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proforma_productos`
--

CREATE TABLE `proforma_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `proforma_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `precio` decimal(24,2) DEFAULT NULL,
  `unidad_medida_id` bigint UNSIGNED NOT NULL,
  `stock_actual` double DEFAULT NULL,
  `agregados` double(8,2) NOT NULL DEFAULT '0.00',
  `suma` double(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedors`
--

CREATE TABLE `proveedors` (
  `id` bigint UNSIGNED NOT NULL,
  `razon_social` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_com` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `moneda` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono_emp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorias` json DEFAULT NULL,
  `marcas` json DEFAULT NULL,
  `contactos` json DEFAULT NULL,
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
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
(3, 'ENCARGADO', 0, 1, 1, NULL, '2025-11-30 16:44:33', '2025-12-13 23:39:38'),
(4, 'VENDEDOR', 0, 1, 1, NULL, '2026-01-16 21:28:39', '2026-01-16 21:28:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_ingresos`
--

CREATE TABLE `solicitud_ingresos` (
  `id` bigint UNSIGNED NOT NULL,
  `nro` bigint NOT NULL,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `proveedor_id` bigint UNSIGNED NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `hora_ingreso` time NOT NULL,
  `fecha_sis` date NOT NULL,
  `hora_sis` time NOT NULL,
  `cs_f` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_cambio` decimal(24,2) DEFAULT NULL,
  `gastos` decimal(24,2) DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cantidad_total` double NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
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
  `calculado` decimal(24,2) NOT NULL DEFAULT '0.00',
  `pc_facturado` decimal(24,2) NOT NULL DEFAULT '0.00',
  `costo_facturado` decimal(24,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(24,2) NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
  `sucursal_ajuste` bigint UNSIGNED DEFAULT NULL,
  `motivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `nombre` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(800) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `vendedores` json DEFAULT NULL,
  `almacen` int NOT NULL DEFAULT '0',
  `estado` int NOT NULL DEFAULT '1',
  `monto_dia` decimal(30,0) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sucursals`
--

INSERT INTO `sucursals` (`id`, `nombre`, `direccion`, `fono`, `correo`, `user_id`, `vendedores`, `almacen`, `estado`, `monto_dia`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'ALMACÉN CENTRAL', '', '', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL),
(2, 'AJUSTES', '', '', NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, NULL);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencias`
--

CREATE TABLE `transferencias` (
  `id` bigint UNSIGNED NOT NULL,
  `nro` bigint NOT NULL,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `sucursal_destino` bigint UNSIGNED NOT NULL,
  `user_sol` bigint UNSIGNED NOT NULL,
  `user_ap` bigint UNSIGNED NOT NULL,
  `cantidad_total` double(8,2) NOT NULL,
  `cantidad_total_v` double(8,2) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `verificado` int NOT NULL DEFAULT '0',
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
  `motivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medidas`
--

CREATE TABLE `unidad_medidas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `grupo_san` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionalidad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profesion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel_dom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carnet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

INSERT INTO `users` (`id`, `usuario`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `grupo_san`, `sexo`, `nacionalidad`, `profesion`, `cel`, `fono`, `cel_dom`, `dir`, `ubicacion`, `correo`, `foto`, `carnet`, `password`, `tipo`, `role_id`, `acceso`, `fecha_registro`, `estado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', '', '0', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'ADMINISTRADOR', 1, 1, '2025-11-30', 1, NULL, '2025-11-30 16:37:59', '2025-11-30 16:37:59');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ajustes_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `ajustes_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `ajuste_reposicions`
--
ALTER TABLE `ajuste_reposicions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ajuste_reposicions_ajuste_id_foreign` (`ajuste_id`),
  ADD KEY `ajuste_reposicions_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `ajuste_reposicions_producto_id_foreign` (`producto_id`);

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
  ADD UNIQUE KEY `orden_venta_id` (`orden_venta_id`),
  ADD KEY `cuenta_cobrars_cliente_id_foreign` (`cliente_id`);

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
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

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
-- Indices de la tabla `movimiento_horas`
--
ALTER TABLE `movimiento_horas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movimiento_horas_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `movimiento_horas_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `notificacions`
--
ALTER TABLE `notificacions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificacion_users`
--
ALTER TABLE `notificacion_users`
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
  ADD KEY `orden_ventas_user_id_foreign` (`user_id`),
  ADD KEY `idx_ordenventas_cliente_fecha` (`cliente_id`,`fecha`);

--
-- Indices de la tabla `orden_venta_detalles`
--
ALTER TABLE `orden_venta_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_venta_detalles_orden_venta_id_foreign` (`orden_venta_id`),
  ADD KEY `orden_venta_detalles_producto_id_foreign` (`producto_id`),
  ADD KEY `orden_venta_detalles_unidad_medida_id_foreign` (`unidad_medida_id`);

--
-- Indices de la tabla `parametro_clientes`
--
ALTER TABLE `parametro_clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `parametro_sucursals`
--
ALTER TABLE `parametro_sucursals`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `proformas_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `proforma_detalles`
--
ALTER TABLE `proforma_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proforma_detalles_proforma_id_foreign` (`proforma_id`);

--
-- Indices de la tabla `proforma_detalle_productos`
--
ALTER TABLE `proforma_detalle_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proforma_detalle_productos_proforma_id_foreign` (`proforma_id`),
  ADD KEY `proforma_detalle_productos_proforma_detalle_id_foreign` (`proforma_detalle_id`),
  ADD KEY `proforma_detalle_productos_proforma_producto_id_foreign` (`proforma_producto_id`),
  ADD KEY `proforma_detalle_productos_producto_id_foreign` (`producto_id`),
  ADD KEY `proforma_detalle_productos_unidad_medida_id_foreign` (`unidad_medida_id`);

--
-- Indices de la tabla `proforma_productos`
--
ALTER TABLE `proforma_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proforma_productos_proforma_id_foreign` (`proforma_id`),
  ADD KEY `proforma_productos_producto_id_foreign` (`producto_id`),
  ADD KEY `proforma_productos_unidad_medida_id_foreign` (`unidad_medida_id`);

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
-- AUTO_INCREMENT de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ajuste_reposicions`
--
ALTER TABLE `ajuste_reposicions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `kardex_productos`
--
ALTER TABLE `kardex_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT de la tabla `movimiento_horas`
--
ALTER TABLE `movimiento_horas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacions`
--
ALTER TABLE `notificacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion_users`
--
ALTER TABLE `notificacion_users`
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
-- AUTO_INCREMENT de la tabla `parametro_clientes`
--
ALTER TABLE `parametro_clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `parametro_sucursals`
--
ALTER TABLE `parametro_sucursals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT de la tabla `proforma_detalle_productos`
--
ALTER TABLE `proforma_detalle_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proforma_productos`
--
ALTER TABLE `proforma_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Filtros para la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD CONSTRAINT `ajustes_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `ajustes_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`);

--
-- Filtros para la tabla `ajuste_reposicions`
--
ALTER TABLE `ajuste_reposicions`
  ADD CONSTRAINT `ajuste_reposicions_ajuste_id_foreign` FOREIGN KEY (`ajuste_id`) REFERENCES `ajustes` (`id`),
  ADD CONSTRAINT `ajuste_reposicions_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `ajuste_reposicions_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`);

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
-- Filtros para la tabla `movimiento_horas`
--
ALTER TABLE `movimiento_horas`
  ADD CONSTRAINT `movimiento_horas_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `movimiento_horas_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`);

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
  ADD CONSTRAINT `proformas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `proforma_detalles`
--
ALTER TABLE `proforma_detalles`
  ADD CONSTRAINT `proforma_detalles_proforma_id_foreign` FOREIGN KEY (`proforma_id`) REFERENCES `proformas` (`id`);

--
-- Filtros para la tabla `proforma_detalle_productos`
--
ALTER TABLE `proforma_detalle_productos`
  ADD CONSTRAINT `proforma_detalle_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `proforma_detalle_productos_proforma_detalle_id_foreign` FOREIGN KEY (`proforma_detalle_id`) REFERENCES `proforma_detalles` (`id`),
  ADD CONSTRAINT `proforma_detalle_productos_proforma_id_foreign` FOREIGN KEY (`proforma_id`) REFERENCES `proformas` (`id`),
  ADD CONSTRAINT `proforma_detalle_productos_proforma_producto_id_foreign` FOREIGN KEY (`proforma_producto_id`) REFERENCES `proforma_productos` (`id`),
  ADD CONSTRAINT `proforma_detalle_productos_unidad_medida_id_foreign` FOREIGN KEY (`unidad_medida_id`) REFERENCES `unidad_medidas` (`id`);

--
-- Filtros para la tabla `proforma_productos`
--
ALTER TABLE `proforma_productos`
  ADD CONSTRAINT `proforma_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `proforma_productos_proforma_id_foreign` FOREIGN KEY (`proforma_id`) REFERENCES `proformas` (`id`),
  ADD CONSTRAINT `proforma_productos_unidad_medida_id_foreign` FOREIGN KEY (`unidad_medida_id`) REFERENCES `unidad_medidas` (`id`);

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
