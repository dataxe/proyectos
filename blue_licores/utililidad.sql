-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-03-2016 a las 20:46:06
-- Versión del servidor: 5.5.8
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `editaxe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `utililidad`
--

CREATE TABLE IF NOT EXISTS `utililidad` (
  `cod_utililidad` int(1) NOT NULL AUTO_INCREMENT,
  `contrasena` varchar(100) NOT NULL,
  PRIMARY KEY (`cod_utililidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `utililidad`
--

INSERT INTO `utililidad` (`cod_utililidad`, `contrasena`) VALUES
(1, '390555e6d1fc17610469e1ed9b19e1da457b5446');
