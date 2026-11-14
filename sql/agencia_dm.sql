-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2026 a las 23:39:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agencia_dm`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'usuario',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `archivado_en` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `dni`, `email`, `password_hash`, `rol`, `creado_en`, `archivado_en`) VALUES
(1, 'Wilson', 'Demo', '34222987', 'jajaja@hotmail.com', '$2y$10$6gt4677dWIAwAMhlGA7xm.r6EYYP2NRPVnIPxJcjJL.Ez/gGLahia', 'admin', '2026-11-11 21:25:32', NULL),
(5, 'Wilson', 'De Moliner', '51666777', 'jajaja@gmail.com', '$2y$10$e2y4qLwUaLqwtd.NFBcrx.HjG1Cd07mcptVz/JjBFhvsiRIYsA4s6', 'usuario', '2026-11-11 21:31:13', NULL),
(6, 'Fer', 'Demo', '1818188', 'aedjaj@jdaj.com', '$2y$10$fckB65FMhlF6WRjvvZqw5uDnklz5w0j0QFIY9hCZUlBrs2Sh3DA2.', 'usuario', '2026-11-11 22:29:55', NULL),
(8, 'kaka', 'demo', '18181889', 'dadadk@gmakak.com', '$2y$10$pbYf2Z8G/cJHkdBKTjI3j.P6IPtnyXc8lX7BBK66OsaPa5fl7e60a', 'usuario', '2026-11-11 22:32:12', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
