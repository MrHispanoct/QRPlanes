-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2024 a las 23:01:37
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `qrplanes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `expediente` int(11) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido1` varchar(50) NOT NULL,
  `apellido2` varchar(50) DEFAULT NULL,
  `fecha_nac` date NOT NULL,
  `movil` varchar(9) DEFAULT NULL,
  `telefono_casa` varchar(9) DEFAULT NULL,
  `padre` varchar(75) DEFAULT NULL,
  `movil_padre` varchar(9) DEFAULT NULL,
  `madre` varchar(75) DEFAULT NULL,
  `movil_madre` varchar(9) DEFAULT NULL,
  `autoriza_salida` tinyint(1) NOT NULL DEFAULT 0,
  `autoriza_imagen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`expediente`, `dni`, `nombre`, `apellido1`, `apellido2`, `fecha_nac`, `movil`, `telefono_casa`, `padre`, `movil_padre`, `madre`, `movil_madre`, `autoriza_salida`, `autoriza_imagen`) VALUES
(234567, '12345678A', 'Jose', 'Contreras', 'Fulgencio', '2020-11-08', '620212121', NULL, 'Juan Carlos Contreras', '620132123', 'Maria Jose Fulgencio', '659784512', 1, 1),
(1234568, '87654321B', 'Juan', 'Pardi', 'Rodriguez', '2020-08-17', '631793164', '868741296', 'Antonio Pardi Abellan', '658730012', 'Carmen Maria Rodriguez Zamora', '698731597', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(200) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `nombres` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `perfil` tinyint(1) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `username`, `password`, `token`, `nombres`, `perfil`) VALUES
(100, 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MTU2MzM3NTYsImRhdGEiOnsiaWQiOiIxMDAiLCJub21icmVzIjoiQWRtaW5pc3RyYWRvciIsInBlcmZpbCI6IjEifX0.ImTEziKU4n6QyzV_LsTbvNjPUK40P3rZ7s0rpbsXA8g', 'Administrador', 1),
(200, 'prueba', '655e786674d9d3e77bc05ed1de37b4b6bc89f788829f9f3c679e7687b410c89b', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MTU2MzM3OTYsImRhdGEiOnsiaWQiOiIyMDAiLCJub21icmVzIjoiVXN1YXJpbyBQcnVlYmEiLCJwZXJmaWwiOiIyIn19.GXLrYIgxIi1p1nXyg_p3tbI6s_fpDHsDwzP1DCvKiPI', 'Usuario Prueba', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`expediente`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
