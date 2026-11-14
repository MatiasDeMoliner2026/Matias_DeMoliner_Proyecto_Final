-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2026 a las 05:43:04
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
-- Estructura de tabla para la tabla `jugadores`
--

CREATE TABLE `jugadores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `posicion` varchar(40) NOT NULL,
  `edad` tinyint(3) UNSIGNED NOT NULL,
  `club` varchar(80) NOT NULL,
  `foto` varchar(120) NOT NULL DEFAULT 'img/jugador.svg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `jugadores`
--

INSERT INTO `jugadores` (`id`, `nombre`, `apellido`, `posicion`, `edad`, `club`, `foto`) VALUES
(1, 'Lucas', 'Ibarra', 'Delantero', 17, 'Club Atlético Crucero del Norte', 'img/jugador.svg'),
(2, 'Ignacio', 'De Moliner', 'Mediocampista', 12, 'Comunicaciones', 'img/jugador.svg'),
(3, 'Franco', 'Mansilla', 'Defensor', 18, 'Unión de Sta. Fe', 'img/jugador.svg'),
(4, 'Ezequiel', 'Costa', 'Arquero', 17, 'Quilmes', 'img/jugador.svg'),
(5, 'Nahuel', 'Sanchez', 'Delantero', 16, 'Club Oeste', 'img/jugador.svg'),
(6, 'Bruno', 'Cáceres', 'Mediocampista', 18, 'Deportivo Morón', 'img/jugador.svg'),
(7, 'Iván', 'Acosta', 'Mediocampista', 16, 'Ferro', 'img/jugador.svg'),
(8, 'Gonzalo', 'Maidana', 'Delantero', 19, 'Temperley', 'img/jugador.svg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `jugadores`
--
ALTER TABLE `jugadores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `jugadores`
--
ALTER TABLE `jugadores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
