-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-04-2016 a las 05:56:08
-- Versión del servidor: 5.6.17
-- Versión de PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `ejercicio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE IF NOT EXISTS `cotizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) NOT NULL,
  `descuento` float DEFAULT NULL,
  `impuesto` float DEFAULT NULL,
  `cliente` varchar(45) NOT NULL,
  `ruc` varchar(45) NOT NULL,
  `vendedora_codigo` varchar(45) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendedora_id` (`vendedora_codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_productos`
--

CREATE TABLE IF NOT EXISTS `cotizacion_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cotizacion_id` int(11) NOT NULL,
  `paquete_codigo` varchar(45) DEFAULT NULL,
  `producto_codigo` varchar(45) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `paquete_codigo` (`paquete_codigo`),
  KEY `producto_codigo` (`producto_codigo`),
  KEY `cotizacion_id` (`cotizacion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquete`
--

CREATE TABLE IF NOT EXISTS `paquete` (
  `codigo` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `disponibilidad` int(11) NOT NULL,
  `precio` float NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE IF NOT EXISTS `producto` (
  `codigo` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `precio` float NOT NULL,
  `disponibilidad` int(11) NOT NULL,
  `tipo_producto_id` int(11) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `fk_producto_tipo_producto_idx` (`tipo_producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_paquete`
--

CREATE TABLE IF NOT EXISTS `producto_paquete` (
  `cantidad` int(11) NOT NULL,
  `producto_codigo` varchar(45) NOT NULL,
  `paquete_codigo` varchar(45) NOT NULL,
  PRIMARY KEY (`producto_codigo`,`paquete_codigo`),
  KEY `fk_producto_paquete_paquete1_idx` (`paquete_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

CREATE TABLE IF NOT EXISTS `tipo_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `nombre`, `username`, `codigo`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Administrador', 'A-01', '5D7epn4nAQzQELv1m8daZwrRNyqnov4T', '$2y$13$IeTFe8aLs6P/2CAfv7c0FudCqy3dwS.RamE32nDq/c1USiKBasOam', NULL, 'admi@gmail.com', 10, 1460159619, 1460159619);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cotizacion_productos`
--
ALTER TABLE `cotizacion_productos`
  ADD CONSTRAINT `cotizacion_productos_ibfk_2` FOREIGN KEY (`paquete_codigo`) REFERENCES `paquete` (`codigo`),
  ADD CONSTRAINT `cotizacion_productos_ibfk_3` FOREIGN KEY (`producto_codigo`) REFERENCES `producto` (`codigo`),
  ADD CONSTRAINT `cotizacion_productos_ibfk_4` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizacion` (`id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_tipo_producto` FOREIGN KEY (`tipo_producto_id`) REFERENCES `tipo_producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto_paquete`
--
ALTER TABLE `producto_paquete`
  ADD CONSTRAINT `fk_producto_paquete_producto1` FOREIGN KEY (`producto_codigo`) REFERENCES `producto` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_paquete_paquete1` FOREIGN KEY (`paquete_codigo`) REFERENCES `paquete` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
