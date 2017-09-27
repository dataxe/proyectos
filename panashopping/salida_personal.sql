-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 01-09-2017 a las 18:13:06
-- Versión del servidor: 5.5.8
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pinsalud`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_personal`
--

CREATE TABLE IF NOT EXISTS `salida_personal` (
  `cod_salida_personal` int(10) NOT NULL AUTO_INCREMENT,
  `conceptos` varchar(100) NOT NULL,
  `costo` int(8) NOT NULL,
  `comentarios` varchar(1000) NOT NULL,
  `nombre_ccosto` varchar(30) NOT NULL,
  `fecha_dmy` varchar(12) NOT NULL,
  `fecha_mes` varchar(7) NOT NULL,
  `anyo` int(4) NOT NULL,
  `fecha_seg` int(20) NOT NULL,
  `fecha_time` int(20) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cuenta` varchar(60) NOT NULL,
  PRIMARY KEY (`cod_salida_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `salida_personal`
--

