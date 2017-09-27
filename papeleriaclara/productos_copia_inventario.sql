-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 01-09-2017 a las 18:15:09
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
-- Estructura de tabla para la tabla `productos_copia_inventario`
--

CREATE TABLE IF NOT EXISTS `productos_copia_inventario` (
  `cod_productos_copia_inventario` int(10) NOT NULL AUTO_INCREMENT,
  `cod_productos_var` varchar(20) NOT NULL,
  `nombre_productos` varchar(100) DEFAULT NULL,
  `cod_marcas` int(4) NOT NULL,
  `cod_proveedores` int(4) NOT NULL,
  `cod_nomenclatura` int(4) NOT NULL,
  `cod_tipo` int(4) NOT NULL,
  `cod_lineas` int(4) DEFAULT NULL,
  `cod_ccosto` int(5) DEFAULT NULL,
  `cod_paises` int(4) NOT NULL,
  `numero_factura` int(10) DEFAULT NULL,
  `unidades` double DEFAULT NULL,
  `cajas` double DEFAULT NULL,
  `unidades_total` double DEFAULT NULL,
  `unidades_faltantes` double DEFAULT NULL,
  `unidades_vendidas` double DEFAULT NULL,
  `und_orig` double DEFAULT NULL,
  `precio_compra` double DEFAULT NULL,
  `precio_costo` double NOT NULL,
  `precio_venta` double NOT NULL,
  `vlr_total_compra` double NOT NULL,
  `vlr_total_venta` double NOT NULL,
  `cod_interno` varchar(10) NOT NULL,
  `tope_minimo` int(10) DEFAULT NULL,
  `utilidad` int(10) DEFAULT NULL,
  `total_utilidad` int(10) NOT NULL,
  `total_mercancia` int(10) NOT NULL,
  `total_venta` int(10) NOT NULL,
  `gasto` int(10) NOT NULL,
  `descuento` int(10) NOT NULL,
  `tipo_pago` varchar(10) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `codificacion` varchar(30) DEFAULT NULL,
  `url` varchar(300) NOT NULL,
  `cod_original` varchar(20) DEFAULT NULL,
  `detalles` varchar(50) DEFAULT NULL,
  `descripcion` varchar(30) DEFAULT NULL,
  `dto1` int(5) DEFAULT NULL,
  `dto2` int(5) DEFAULT NULL,
  `iva` int(5) DEFAULT NULL,
  `iva_v` int(8) DEFAULT NULL,
  `fechas_dia` varchar(10) DEFAULT NULL,
  `fechas_mes` varchar(10) DEFAULT NULL,
  `fechas_anyo` varchar(20) DEFAULT NULL,
  `fechas_hora` varchar(20) DEFAULT NULL,
  `fechas_vencimiento` varchar(20) DEFAULT NULL,
  `porcentaje_vendedor` varchar(5) DEFAULT NULL,
  `fechas_vencimiento_seg` int(15) DEFAULT NULL,
  `fechas_agotado` varchar(20) DEFAULT NULL,
  `fechas_agotado_seg` int(30) DEFAULT NULL,
  `vendedor` varchar(50) DEFAULT NULL,
  `cuenta` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`cod_productos_copia_inventario`),
  UNIQUE KEY `cod_productos_var` (`cod_productos_var`),
  KEY `nombre_productos` (`nombre_productos`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `productos_copia_inventario`
--

