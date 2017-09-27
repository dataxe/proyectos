-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 16-02-2016 a las 19:36:13
-- Versión del servidor: 5.5.8
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `desvare_moto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas_cargadas_stiker`
--

CREATE TABLE IF NOT EXISTS `facturas_cargadas_stiker` (
  `cod_facturas_cargadas_stiker` int(10) NOT NULL AUTO_INCREMENT,
  `cod_productos` varchar(20) DEFAULT NULL,
  `nombre_productos` varchar(100) NOT NULL,
  `unidades` double DEFAULT NULL,
  `cajas` double DEFAULT NULL,
  `unidades_total` double DEFAULT NULL,
  `unidades_vendidas` double DEFAULT NULL,
  `precio_compra` double NOT NULL,
  `precio_costo` double NOT NULL,
  `precio_venta` double NOT NULL,
  `vlr_total_venta` double DEFAULT NULL,
  `vlr_total_compra` double NOT NULL,
  `precio_compra_con_descuento` double NOT NULL,
  `detalles` varchar(20) DEFAULT NULL,
  `cod_proveedores` int(7) DEFAULT NULL,
  `tipo_pago` varchar(7) DEFAULT NULL,
  `descuento` int(10) NOT NULL,
  `dto1` int(10) DEFAULT NULL,
  `dto2` int(10) DEFAULT NULL,
  `iva` int(5) DEFAULT NULL,
  `iva_v` int(10) DEFAULT NULL,
  `valor_iva` int(10) DEFAULT NULL,
  `cod_factura` int(10) DEFAULT NULL,
  `cod_original` varchar(20) DEFAULT NULL,
  `codificacion` varchar(20) DEFAULT NULL,
  `porcentaje_vendedor` varchar(10) DEFAULT NULL,
  `ptj_ganancia` int(10) DEFAULT NULL,
  `tope_min` int(5) DEFAULT NULL,
  `vendedor` varchar(60) NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `fecha_mes` varchar(30) DEFAULT NULL,
  `fecha_anyo` varchar(20) DEFAULT NULL,
  `anyo` varchar(10) NOT NULL,
  `fecha_hora` varchar(20) NOT NULL,
  `fechas_vencimiento` varchar(20) DEFAULT NULL,
  `fechas_vencimiento_seg` int(30) DEFAULT NULL,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY (`cod_facturas_cargadas_stiker`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `facturas_cargadas_stiker`
--

