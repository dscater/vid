-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 29-11-2025 a las 21:55:47
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
(1, 'VID S.A.', 'VID', 'logo.png', '2025-11-29 14:42:14', '2025-11-29 14:42:14');

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
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ROLE', '{\"id\": 2, \"nombre\": \"ASDASD\", \"created_at\": \"2025-11-29T21:37:47.000000Z\", \"updated_at\": \"2025-11-29T21:37:47.000000Z\"}', NULL, 'ROLES', '2025-11-29', '17:37:47', '2025-11-29 21:37:47', '2025-11-29 21:37:47'),
(2, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN ROLE', '{\"id\": 2, \"nombre\": \"ASDASD\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:37:47.000000Z\", \"updated_at\": \"2025-11-29T21:37:47.000000Z\"}', '{\"id\": 2, \"nombre\": \"ADMINISTRADOR\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:37:47.000000Z\", \"updated_at\": \"2025-11-29T21:46:12.000000Z\"}', 'ROLES', '2025-11-29', '17:46:12', '2025-11-29 21:46:12', '2025-11-29 21:46:12'),
(3, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN ROLE', '{\"id\": 2, \"nombre\": \"ADMINISTRADOR\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:37:47.000000Z\", \"updated_at\": \"2025-11-29T21:46:12.000000Z\"}', '{\"id\": 2, \"nombre\": \"ASDASD\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:37:47.000000Z\", \"updated_at\": \"2025-11-29T21:49:04.000000Z\"}', 'ROLES', '2025-11-29', '17:49:04', '2025-11-29 21:49:04', '2025-11-29 21:49:04'),
(4, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN ROLE', '{\"id\": 2, \"nombre\": \"ASDASD\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:37:47.000000Z\", \"updated_at\": \"2025-11-29T21:49:04.000000Z\"}', '{\"id\": 2, \"nombre\": \"ADMINISTRADOR\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:37:47.000000Z\", \"updated_at\": \"2025-11-29T21:49:20.000000Z\"}', 'ROLES', '2025-11-29', '17:49:20', '2025-11-29 21:49:20', '2025-11-29 21:49:20'),
(5, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ROLE', '{\"id\": 3, \"nombre\": \"AUXILIAR\", \"created_at\": \"2025-11-29T21:50:08.000000Z\", \"updated_at\": \"2025-11-29T21:50:08.000000Z\"}', NULL, 'ROLES', '2025-11-29', '17:50:08', '2025-11-29 21:50:08', '2025-11-29 21:50:08'),
(6, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN ROLE', '{\"id\": 3, \"nombre\": \"AUXILIAR\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:50:08.000000Z\", \"updated_at\": \"2025-11-29T21:50:08.000000Z\"}', NULL, 'ROLES', '2025-11-29', '17:51:53', '2025-11-29 21:51:53', '2025-11-29 21:51:53'),
(7, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ROLE', '{\"id\": 4, \"nombre\": \"AUXILIAR\", \"created_at\": \"2025-11-29T21:52:23.000000Z\", \"updated_at\": \"2025-11-29T21:52:23.000000Z\"}', NULL, 'ROLES', '2025-11-29', '17:52:23', '2025-11-29 21:52:23', '2025-11-29 21:52:23'),
(8, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN ROLE', '{\"id\": 4, \"nombre\": \"AUXILIAR\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:52:23.000000Z\", \"updated_at\": \"2025-11-29T21:52:23.000000Z\"}', NULL, 'ROLES', '2025-11-29', '17:52:27', '2025-11-29 21:52:27', '2025-11-29 21:52:27'),
(9, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ROLE', '{\"id\": 5, \"nombre\": \"AUXILIAR\", \"created_at\": \"2025-11-29T21:53:05.000000Z\", \"updated_at\": \"2025-11-29T21:53:05.000000Z\"}', NULL, 'ROLES', '2025-11-29', '17:53:05', '2025-11-29 21:53:05', '2025-11-29 21:53:05'),
(10, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN ROLE', '{\"id\": 5, \"nombre\": \"AUXILIAR\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:53:05.000000Z\", \"updated_at\": \"2025-11-29T21:53:05.000000Z\"}', NULL, 'ROLES', '2025-11-29', '17:53:07', '2025-11-29 21:53:07', '2025-11-29 21:53:07'),
(11, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ROLE', '{\"id\": 3, \"nombre\": \"AUXILIAR\", \"created_at\": \"2025-11-29T21:53:34.000000Z\", \"updated_at\": \"2025-11-29T21:53:34.000000Z\"}', NULL, 'ROLES', '2025-11-29', '17:53:34', '2025-11-29 21:53:34', '2025-11-29 21:53:34'),
(12, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN ROLE', '{\"id\": 3, \"nombre\": \"AUXILIAR\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:53:34.000000Z\", \"updated_at\": \"2025-11-29T21:53:34.000000Z\"}', '{\"id\": 3, \"nombre\": \"AUXILIARASD\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:53:34.000000Z\", \"updated_at\": \"2025-11-29T21:53:38.000000Z\"}', 'ROLES', '2025-11-29', '17:53:38', '2025-11-29 21:53:38', '2025-11-29 21:53:38'),
(13, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN ROLE', '{\"id\": 3, \"nombre\": \"AUXILIARASD\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:53:34.000000Z\", \"updated_at\": \"2025-11-29T21:53:38.000000Z\"}', '{\"id\": 3, \"nombre\": \"AUXILIAR\", \"status\": 1, \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-11-29T21:53:34.000000Z\", \"updated_at\": \"2025-11-29T21:53:41.000000Z\"}', 'ROLES', '2025-11-29', '17:53:41', '2025-11-29 21:53:41', '2025-11-29 21:53:41');

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
(6, '2024_11_02_153318_create_historial_accions_table', 1);

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
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permisos` int NOT NULL DEFAULT '0',
  `usuarios` int NOT NULL DEFAULT '1',
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `permisos`, `usuarios`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SUPER USUARIO', 1, 0, 1, NULL, NULL),
(2, 'ADMINISTRADOR', 0, 1, 1, '2025-11-29 21:37:47', '2025-11-29 21:49:20'),
(3, 'AUXILIAR', 0, 1, 1, '2025-11-29 21:53:34', '2025-11-29 21:53:41');

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
  `doc_adicional` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `acceso` int NOT NULL,
  `fecha_registro` date NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `grupo_san`, `sexo`, `nacionalidad`, `profesion`, `cel`, `fono`, `cel_dom`, `dir`, `latitud`, `longitud`, `correo`, `foto`, `carnet`, `doc_adicional`, `password`, `tipo`, `role_id`, `acceso`, `fecha_registro`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', '', '0', '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'ADMINISTRADOR', 1, 1, '2025-11-29', 1, '2025-11-29 14:42:14', '2025-11-29 14:42:14');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_accions_user_id_foreign` (`user_id`);

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
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permisos_role_id_foreign` (`role_id`),
  ADD KEY `permisos_modulo_id_foreign` (`modulo_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
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
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD CONSTRAINT `historial_accions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`),
  ADD CONSTRAINT `permisos_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
