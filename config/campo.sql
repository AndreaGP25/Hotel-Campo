-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql103.infinityfree.com
-- Tiempo de generación: 19-05-2026 a las 21:16:00
-- Versión del servidor: 11.4.10-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_41730483_campo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `categoria` enum('Estandar','Superior') NOT NULL DEFAULT 'Estandar',
  `disponibilidad` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `titulo`, `precio`, `imagen`, `descripcion`, `fecha_creacion`, `categoria`, `disponibilidad`) VALUES
(10, 'Habitación caracol', '1500.00', 'images/actualizaciones/habitacion1.jpg', 'Habitacion con balcon', '2024-12-02 09:17:33', 'Estandar', 1),
(11, 'Habitación árbol', '1200.00', 'images/actualizaciones/arbol.jpg', 'Habitación con balcón', '2024-12-02 21:20:49', 'Estandar', 1),
(12, 'Habitación campo', '1300.00', 'images/actualizaciones/habitacioncampo.jpg', 'Amplia habitación con balcón', '2024-12-02 21:25:57', 'Estandar', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `reservacion_id` int(11) NOT NULL,
  `nombre_titular` varchar(255) NOT NULL,
  `numero_tarjeta` varchar(16) NOT NULL,
  `fecha_expiracion` date NOT NULL,
  `cvv` varchar(4) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `reservacion_id`, `nombre_titular`, `numero_tarjeta`, `fecha_expiracion`, `cvv`, `monto`, `fecha_pago`) VALUES
(5, 13, 'Frida Pineda', '1111111111111111', '2024-12-31', '122', '1400.00', '2024-12-02 21:12:40'),
(7, 15, 'lili', '1234567890123456', '2028-09-30', '123', '1200.00', '2026-05-06 22:51:49'),
(8, 16, 'mimi', '1234567890123456', '2028-09-30', '123', '1500.00', '2026-05-07 05:48:32'),
(9, 18, 'Mariana C B', '1234567891234567', '2027-12-31', '123', '18900.00', '2026-05-11 22:08:31'),
(10, 19, 'lili', '1234567890123456', '2028-09-30', '123', '1200.00', '2026-05-18 00:55:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservaciones`
--

CREATE TABLE `reservaciones` (
  `id` int(11) NOT NULL,
  `habitacion_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_llegada` date NOT NULL,
  `fecha_salida` date NOT NULL,
  `fecha_reservacion` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activa','cancelada') NOT NULL DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservaciones`
--

INSERT INTO `reservaciones` (`id`, `habitacion_id`, `nombre`, `email`, `telefono`, `fecha_llegada`, `fecha_salida`, `fecha_reservacion`, `estado`) VALUES
(13, 10, 'Mariana Pineda', 'mar@gmail.com', '997812738', '2024-12-03', '2024-12-04', '2024-12-02 15:12:40', 'activa'),
(15, 11, 'lili', 'crislilian2015@gmail.com', '3333333333', '2026-08-02', '2026-08-03', '2026-05-06 15:51:49', 'cancelada'),
(16, 10, 'mimi', 'bttl.breaker@gmail.com', '2222222222', '2026-09-05', '2026-09-06', '2026-05-06 22:48:32', 'activa'),
(17, 11, 'Mariana C', 'marianacabbel@gmail.com', '9993895484', '2026-05-20', '2026-05-27', '2026-05-11 15:08:31', 'cancelada'),
(18, 10, 'Mariana C', 'marianacabbel@gmail.com', '9993895484', '2026-05-20', '2026-05-27', '2026-05-11 15:08:31', 'cancelada'),
(19, 11, 'lili', 'crislilian2015@gmail.com', '3333333333', '2026-05-18', '2026-05-19', '2026-05-17 17:55:35', 'activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `titulo`) VALUES
(1, 'Bicicletas'),
(2, 'Senderismo Guiado'),
(3, 'Spa y Masajes'),
(4, 'Piscina Climatizada'),
(5, 'Cabalgatas'),
(6, 'Wi-Fi Gratis'),
(7, 'Servicio de Restaurante'),
(8, 'Parrillas y Asados'),
(9, 'Actividades de Pesca'),
(10, 'Alquiler de Kayaks'),
(11, 'Observación de Aves'),
(12, 'Tienda de Souvenirs'),
(13, 'Traslados al Aeropuerto'),
(14, 'Yoga y Meditación'),
(15, 'Salón de Juegos'),
(16, 'Zona de Picnic'),
(17, 'Ciclismo de Montaña'),
(18, 'Servicio de Masajes a Domicilio'),
(19, 'Clases de Cocina Local'),
(20, 'Excursiones en 4x4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id_sesion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_fin` timestamp NULL DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`id_sesion`, `id_usuario`, `token`, `fecha_inicio`, `fecha_fin`, `ip`) VALUES
(91, 23, '5969be1ea5f34dfe642e9dd90400647684766511cb11bb4ba7946bb6d219f41d', '2026-05-18 07:46:46', '2026-05-18 08:00:22', '189.203.87.104'),
(92, 23, '3cb0246594bcd3ddc0588c10c74945794cfcf3cf4a94c01b840c69f6f71227f6', '2026-05-18 09:07:37', '2026-05-18 09:07:43', '189.203.87.104'),
(93, 22, '23b5ef6803102f4ef33cb7541f6ef430982a3f8e91034e963aa7ca1ca9e6fcb1', '2026-05-18 09:07:52', '2026-05-18 09:08:03', '189.203.87.104'),
(94, 23, 'b06c6034f0b21f135cd1b96b3e34750edbb20c500158cde0ab9cd3c7c884fb73', '2026-05-18 09:08:10', NULL, '189.203.87.104'),
(95, 23, 'e6bf426f1ed382a3df08d3944b9df1178b6fd120b9188d1f9297fc431be410da', '2026-05-20 02:04:41', '2026-05-20 02:07:46', '189.203.87.136'),
(96, 23, '0f9ffbc22253fe858688847812ca8223f755e6ae0acad3b100e6b5d9a0a99cc2', '2026-05-20 02:07:51', '2026-05-20 03:49:21', '189.203.87.136'),
(97, 23, '39a7a00ed28097ed7fa421a656dac9cceb668ac91e96d9c3c895c37f85d36168', '2026-05-20 02:19:47', NULL, '189.203.87.136'),
(98, 23, '0640d9f98d3e90f418c31d271492a69a612b4faa12fcc02d04dfce7044468dfe', '2026-05-20 04:04:41', '2026-05-20 04:05:23', '189.203.87.136');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens_recuperacion`
--

CREATE TABLE `tokens_recuperacion` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expira_en` datetime NOT NULL,
  `usado` tinyint(1) NOT NULL DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','huésped','prospecto') NOT NULL DEFAULT 'prospecto',
  `telefono` varchar(12) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `verificado` tinyint(1) NOT NULL DEFAULT 0,
  `codigo_verificacion` varchar(6) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `password`, `rol`, `telefono`, `fecha_registro`, `activo`, `verificado`, `codigo_verificacion`, `codigo_expira`) VALUES
(1, 'Frida', 'a20214993@alumnos.uady.mx', '$2y$10$oaRW16ESkizCuqhhSk.M.uBrNrKihabFzh34eEMeOR8Ucf0uT3X0a', 'prospecto', '234567', '2024-11-17 07:59:43', 1, 1, NULL, NULL),
(7, 'fri', 'jjak@gmail.com', '$2y$10$s2vCke7cA/Cf/BNyivNNG.JQrYZG/O.Nr9f5IT7iba6Eoff2ySgR2', 'prospecto', '997812738', '2024-11-17 08:22:47', 1, 1, NULL, NULL),
(13, 'Emiliano Pineda', 'fridapineda348@gmail.com', '$2y$10$9v0UQOFfouemPNLlDD2Yie3usl6ns64e1k7dCm2nijtHs/d7pcZsG', 'prospecto', '998123456', '2024-11-17 08:51:56', 1, 1, NULL, NULL),
(17, 'Emiliano Alvarado', 'emi@gmail.com', '$2y$10$7l6dSbbBQ1XGaPLb0YroS.aQqAbg7ZXVoanDP67rRVPorxzpoP/T.', 'prospecto', '9991231234', '2024-11-17 18:05:46', 1, 1, NULL, NULL),
(18, 'Mariana Pineda', 'mar@gmail.com', '$2y$10$Rnkb5PMb9KRhqbUvJGg6heK53Mc2KgrrT3JSw82wimfiZ7d8wzY5y', 'prospecto', '997812738', '2024-11-17 18:36:06', 1, 1, NULL, NULL),
(20, 'Administrador', 'admin@hotel.com', '$2y$10$otf98knh7cuWzl.GdJQ...Pl6e9D97lz/G0vrlYfBZRONS/GGHjhy', 'admin', '1234567890', '2024-12-01 01:57:25', 1, 1, NULL, NULL),
(22, 'mimi', 'bttl.breaker@gmail.com', '$2y$10$8XglX5IFPjggM4JUf6Akcu0SIosHcRaUUz18qrkDHlIUtnC7nncXG', 'prospecto', '2222222222', '2026-05-04 04:29:06', 1, 1, NULL, NULL),
(23, 'lili', 'crislilian2015@gmail.com', '$2y$10$qO3Mg.Q8ZQi.Tdapco6yMuGSrk1d962774gaOlRO8QvT8fYpdR3x6', 'prospecto', '3333333333', '2026-05-06 22:33:18', 1, 1, NULL, NULL),
(25, 'Mariana C', 'marianacabbel@gmail.com', '$2y$10$8u4KXT5.uHhh9fBwk669U.AIKHI.DO3DJT6XjqdaucFMH75JcAwca', 'prospecto', '9993895484', '2026-05-11 22:06:04', 1, 1, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservacion_id` (`reservacion_id`);

--
-- Indices de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habitacion_id` (`habitacion_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id_sesion`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tokens_recuperacion`
--
ALTER TABLE `tokens_recuperacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `fk_token_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id_sesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de la tabla `tokens_recuperacion`
--
ALTER TABLE `tokens_recuperacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`reservacion_id`) REFERENCES `reservaciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD CONSTRAINT `reservaciones_ibfk_1` FOREIGN KEY (`habitacion_id`) REFERENCES `habitaciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tokens_recuperacion`
--
ALTER TABLE `tokens_recuperacion`
  ADD CONSTRAINT `fk_token_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
