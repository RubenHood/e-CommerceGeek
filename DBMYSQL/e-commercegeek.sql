-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-07-2019 a las 13:09:39
-- Versión del servidor: 10.1.34-MariaDB
-- Versión de PHP: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `e-commercegeek`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cesta`
--

CREATE TABLE `cesta` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `img` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `price`, `img`, `category`) VALUES
(1, 'Gafas Geek1', 'Estas son unas gafas muy populares entre los jovenes.', 29.99, 'gafa1.jpg', 'man'),
(2, 'Gafas Geek23', 'Estas son unas gafas muy populares entre los jovenes.', 22.99, 'gafa2.jpg', 'woman'),
(3, 'Gafas Geek3', 'Estas son unas gafas muy populares entre los jovenes.', 29.99, 'gafa3.jpg', 'ninja'),
(4, 'Gafas Geek4', 'Estas son unas gafas muy populares entre los jovenes.', 29.99, 'gafa4.jpg', 'frog'),
(5, 'Gafas Geek5', 'Estas son unas gafas muy populares entre los jovenes.', 29.99, 'gafa5.jpg', 'power'),
(6, 'Gafas Geek6', 'Estas son unas gafas muy populares entre los jovenes.', 29.99, 'gafa6.jpg', 'man');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `roles`) VALUES
(1, 'Ruben Vargas', 'rubenconb88@gmail.com', '$2y$13$rrm1hzEvgF9w3fehrsDqHugrYEHD.2EWYFLQX1OvlOAunTlUfLupG', '[\"ROLE_ADMIN\"]');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cesta`
--
ALTER TABLE `cesta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cesta`
--
ALTER TABLE `cesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
