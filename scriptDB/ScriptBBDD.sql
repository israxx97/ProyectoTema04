CREATE USER IF NOT EXISTS 'usuarioDAW202_DBDepartamentos'@'%' identified by 'paso';
GRANT ALL PRIVILEGES ON *.* TO 'usuarioDAW202_DBDepartamentos'@'%';
-- phpMyAdmin SQL Dump
-- version 4.6.6deb1+deb.cihar.com~xenial.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 25-03-2019 a las 13:03:28
-- Versión del servidor: 5.7.25-0ubuntu0.16.04.2
-- Versión de PHP: 7.2.14-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `DAW202_DBDepartamentos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Departamento`
--

CREATE TABLE `Departamento` (
  `CodDepartamento` varchar(3) NOT NULL,
  `DescDepartamento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Departamento`
--

INSERT INTO `Departamento` (`CodDepartamento`, `DescDepartamento`) VALUES
('AAA', 'Mi departamento AAA'),
('AAB', 'Mi departamento AAB'),
('AAC', 'Mi departamento AAC'),
('AAD', 'Mi departamento AAD'),
('AAE', 'Mi departamento AAE'),
('AAF', 'Mi departamento AAF'),
('AAG', 'Mi departamento AAG'),
('AAH', 'Mi departamento AAH'),
('AAI', 'Mi departamento AAI'),
('AAJ', 'Mi departamento AAJ'),
('AAK', 'Mi departamento AAK'),
('AAL', 'Mi departamento AAL'),
('AAM', 'Mi departamento AAM'),
('AAN', 'Mi departamento AAN'),
('AAO', 'Mi departamento AAO'),
('AAP', 'Mi departamento AAP'),
('AAQ', 'Mi departamento AAQ'),
('AAR', 'Mi departamento AAR'),
('AAS', 'Mi departamento AAS'),
('AAT', 'Mi departamento AAT'),
('AAU', 'Mi departamento AAU'),
('AAV', 'Mi departamento AAV'),
('AAW', 'Mi departamento AAW'),
('AAX', 'Mi departamento AAX'),
('AAY', 'Mi departamento AAY'),
('AAZ', 'Mi departamento AAZ');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Departamento`
--
ALTER TABLE `Departamento`
  ADD PRIMARY KEY (`CodDepartamento`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
