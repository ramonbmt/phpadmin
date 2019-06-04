-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 04-06-2019 a las 00:40:24
-- Versión del servidor: 5.7.26
-- Versión de PHP: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `punto_de_venta`
--
CREATE DATABASE IF NOT EXISTS `punto_de_venta` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `punto_de_venta`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `nombre_comercial` varchar(255) NOT NULL,
  `razon_social` varchar(255) NOT NULL,
  `rfc` varchar(255) NOT NULL,
  `calle` varchar(255) NOT NULL,
  `numero_exterior` varchar(255) NOT NULL,
  `numero_interior` varchar(255) NOT NULL,
  `colonia` varchar(255) NOT NULL,
  `municipio_o_delegacion` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `codigo_postal` varchar(255) NOT NULL,
  `pais` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `email_contacto` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `proveniencia` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id`, `nombre_comercial`, `razon_social`, `rfc`, `calle`, `numero_exterior`, `numero_interior`, `colonia`, `municipio_o_delegacion`, `estado`, `codigo_postal`, `pais`, `telefono`, `email_contacto`, `nombre`, `proveniencia`) VALUES
(1, 'bmtask', 'bmtask', '', '', '', '', '', '', '', '', '', '81', 'ramon.sepulveda@bmtask.com', 'bmtask', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gallery`
--

CREATE TABLE `gallery` (
  `id` int(32) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
(1, 'Usuarios'),
(2, 'Clientes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_permisos`
--

CREATE TABLE `tipos_permisos` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `suma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipos_permisos`
--

INSERT INTO `tipos_permisos` (`id`, `name`, `suma`) VALUES
(1, 'Ninguno', 0),
(2, 'Ver', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `password` varchar(123) NOT NULL,
  `last_login` datetime NOT NULL,
  `random` varchar(32) NOT NULL,
  `admin` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `change_password` varchar(32) NOT NULL,
  `date_change_request` datetime NOT NULL,
  `supervisor` tinyint(1) NOT NULL DEFAULT '0',
  `user_type` int(11) NOT NULL,
  `permiso` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `user_name`, `email`, `date`, `password`, `last_login`, `random`, `admin`, `status`, `change_password`, `date_change_request`, `supervisor`, `user_type`, `permiso`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', 'admin@admin.com', '2014-02-02 15:58:49', '$2y$11$FIj07PrhmqpPySlyTOG9f.U.T52e3Wn29FjuuXT1XW..jEbJc.kwG', '2019-06-03 21:14:05', '58d32b9bcea0d5ce8248b1fde139c793', 1, 0, 'b23eb46a007b504fa278ff130079e83e', '2014-04-19 12:15:18', 0, 2, 'a:15:{i:1;s:1:\"2\";i:2;s:1:\"2\";i:3;s:1:\"2\";i:4;s:1:\"2\";i:5;s:1:\"2\";i:6;s:1:\"2\";i:7;s:1:\"2\";i:8;s:1:\"2\";i:9;s:1:\"2\";i:10;s:1:\"2\";i:11;s:1:\"2\";i:12;s:1:\"2\";i:13;s:1:\"2\";i:14;s:1:\"2\";i:15;s:1:\"2\";}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permiso` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `user_type`
--

INSERT INTO `user_type` (`id`, `name`, `permiso`) VALUES
(1, 'General', ''),
(2, 'Administrador', ''),
(3, 'Alumno', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_permisos`
--
ALTER TABLE `tipos_permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipos_permisos`
--
ALTER TABLE `tipos_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
