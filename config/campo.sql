-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-04-2026 a las 10:11:38
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `campo`
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
(10, 'Habitación caracol', 1400.00, 'images/actualizaciones/habitacion1.jpg', 'Habitacion con balcon', '2024-12-02 09:17:33', 'Estandar', 1),
(11, 'Habitación árbol', 1200.00, 'images/actualizaciones/arbol.jpg', 'Habitación con balcón', '2024-12-02 21:20:49', 'Estandar', 1),
(12, 'Habitación campo', 1300.00, 'images/actualizaciones/habitacioncampo.jpg', 'Amplia habitación con balcón', '2024-12-02 21:25:57', 'Estandar', 1);

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
(5, 13, 'Frida Pineda', '1111111111111111', '2024-12-31', '122', 1400.00, '2024-12-02 21:12:40');

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
  `fecha_reservacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservaciones`
--

INSERT INTO `reservaciones` (`id`, `habitacion_id`, `nombre`, `email`, `telefono`, `fecha_llegada`, `fecha_salida`, `fecha_reservacion`) VALUES
(13, 10, 'Mariana Pineda', 'mar@gmail.com', '997812738', '2024-12-03', '2024-12-04', '2024-12-02 15:12:40');

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
(3, 18, 'adda249272e555a280536a6610631992c3471accea00435495dbc833c13e713f', '2024-11-18 02:15:15', '2024-11-18 02:15:51', '::1'),
(4, 18, 'ffd0bd3901fc387aeb1031516a4622e0c16c532d2b509c384c755d11f94ba5b1', '2024-11-18 02:41:26', '2024-11-18 02:42:49', '::1'),
(5, 18, 'f19abc676f9e050196437596bcf7a6db554baaff402d35001e498a98f7cc6261', '2024-11-18 03:01:38', '2024-11-18 03:13:38', '::1'),
(6, 18, '90b18af019fe327e432145fd46210e8fcd9b74cef15c10f9415854f1d50ba120', '2024-11-18 03:15:12', '2024-11-18 03:16:53', '::1'),
(8, 18, '3738661e746d1bb74b19a35d63d14a39e4b96425ca5872f27116d205f7175451', '2024-11-18 03:50:06', '2024-11-18 03:52:38', '::1'),
(9, 18, 'e60174c41ddf0de5319ca4c2448a291556bebbc068ace579e3cc4e1e16a0ab44', '2024-11-18 03:53:24', '2024-11-18 03:54:58', '::1'),
(10, 18, 'e38e1a16464cb49d63bc48c6d6d54d19459b30619cf58cbd9c39699851990a20', '2024-11-18 04:06:27', '2024-11-18 04:13:13', '::1'),
(11, 18, 'cb983db36f869451ecf79e341df37b448abf7bcc42d2450ffc3f58e8319d00d2', '2024-11-18 07:20:58', '2024-11-18 07:24:45', '::1'),
(12, 18, '7f36f95d362f87d421294eeec1d28e622655d157363fac98777a3ba475cc128c', '2024-11-18 12:21:59', '2024-11-18 14:15:13', '::1'),
(13, 18, 'f8a2255874bf11d3ea431f64623c2439c08dfec3895b1d1ec2a3cb6b81286bd3', '2024-11-18 14:22:56', '2024-11-18 15:05:14', '::1'),
(14, 18, '6713cb9bdca3304afd630c597ec764b99166adc656e6fdaf5da52b606b1e3354', '2024-11-18 15:06:51', '2024-11-19 02:32:26', '::1'),
(15, 18, '7e73dc2e389f7270c7dc63755cf01081cecef7e684e01fe6f51bb0b8ed1588b2', '2024-11-19 02:37:14', '2024-11-19 03:08:46', '::1'),
(16, 18, '71b7acc0a5399671492c852c8e276f587a51d83d924910b1e6ce13c404b53032', '2024-11-19 03:09:00', '2024-11-19 03:44:00', '::1'),
(17, 18, '1143aeff1b66561631b77d7de89bf166af064f3ecc34ec4972c264ec81fa0415', '2024-11-19 03:45:39', '2024-11-19 07:13:39', '::1'),
(18, 18, 'ca54b74b0ed49c9c535e036ccf5d6bb31f0ae63cf79b373fe11207a24c383962', '2024-11-20 08:46:22', '2024-11-20 08:48:24', '::1'),
(19, 18, '7296f284aa4d82e39b0779a133d58b507c0e51b41a42a01823b25518d2c8b4ee', '2024-11-20 08:50:40', '2024-11-20 08:53:02', '::1'),
(20, 18, '98b0f4d9b7e7359eae20548574dd5b07f54b374aa385b1a7ad6131efa954dc90', '2024-11-22 02:35:21', '2024-11-22 02:35:36', '::1'),
(21, 18, 'aa48c081c36b5e727d24d9299b6c1b0fc9bf0f8e3313663b600c5193fcb85ad5', '2024-11-26 03:22:30', '2024-11-26 03:31:44', '::1'),
(22, 18, '10659b79a5284e091d66d29a435bbcc3091990812e2c6987413ebf85e0e8a379', '2024-11-26 03:56:43', '2024-11-26 04:16:52', '::1'),
(23, 18, '87443880be5940770c97aa3bc6b90d28445546856d6fe9966fb6fd095e92b187', '2024-11-26 05:01:32', NULL, '::1'),
(24, 18, '4e250b106a91f29e5a7d855b11c7adf5e225e66cbc8a9a20e0eb0a125b18e64a', '2024-11-30 17:36:52', NULL, '::1'),
(25, 18, '6afba7d66277bdda60a740bb4df584fd509a8e029a2c7a6071980dc0451fa4c9', '2024-12-01 02:51:15', '2024-12-01 08:45:14', '::1'),
(26, 20, '34bef7e7f6c7d2094f63aea3578841b4c58da2bb105b37632564ae915817bce6', '2024-12-01 08:57:37', '2024-12-01 08:59:32', '::1'),
(27, 20, 'ebbef58eff1ae25eb962ad765b638aa33a0dc8fe4d2b601f8ed1613fd0f32e93', '2024-12-01 08:59:42', '2024-12-01 09:56:05', '::1'),
(28, 20, '7438a3548ddc133613a68f52ce6dafb8f19e06df48e20eded06d1dbe9347cd5c', '2024-12-01 10:21:18', NULL, '::1'),
(29, 20, 'b256016ed7d5068dccb74e27d96f50a09543c1107becadd85898c43965b431b4', '2024-12-02 07:38:19', '2024-12-02 07:44:24', '::1'),
(30, 18, 'f896625713fd9e2b36d2e22beb9baf02fedef12a72477e6c2fb5865b00bfaf0e', '2024-12-02 07:44:44', NULL, '::1'),
(31, 18, 'ab9e85de2e174a9928a49ce8b199428a29fee865ef05cc6af5217753b209f3d5', '2024-12-02 10:56:37', NULL, '::1'),
(32, 18, '0021a6e89035cb9232751cdad208306829ac5bc2aecd3b0ecd3b804b43629b64', '2024-12-02 11:08:56', NULL, '::1'),
(33, 18, '2405b9e4148cf20dac20c3559742a68c836917401b4e4215d38b0c6faad041a3', '2024-12-02 11:09:43', '2024-12-02 12:38:38', '::1'),
(34, 20, 'cb6a401a0b82e2986cb3a8192d47e4b7efeca1fbc99a9e91c6771854b346a92c', '2024-12-02 12:39:56', '2024-12-02 14:29:45', '::1'),
(35, 20, '17db13fc634de38f4aed17b40c6ac66ad57122e5bc4c5a9a899951686cab2d0b', '2024-12-02 14:29:57', '2024-12-02 15:34:01', '::1'),
(36, 20, 'abccbb3622c2ca175e0a3451380f02ca532a1543a9f719f711b9e6ac193ffe3e', '2024-12-02 15:34:11', '2024-12-02 15:34:21', '::1'),
(37, 20, 'e7ba4586d03a25444472ae32fa42729976cf2c1ff4543c91ab955ac36bae5bf2', '2024-12-02 15:44:45', '2024-12-02 16:57:14', '::1'),
(38, 18, 'c887d0a6605c69ea8ae6f83ce878e63ef497846175e415a5a9eb00fa58d35d6e', '2024-12-02 16:57:32', NULL, '::1'),
(39, 18, 'c6bdada9287e92e56486efd317f666d0dd9d5bc44fd4e9406eac42ce96830a7c', '2024-12-02 23:04:57', NULL, '::1'),
(40, 20, '5115f13fd65f01736db3de75d1eccb435b259bc4cd7480af4b3a4c0a85b3828c', '2024-12-02 23:38:08', NULL, '::1'),
(41, 18, 'efbbdab674fbb1a292b4d07bd8df6f9b7a811f05cefed63f50debea7edba6045', '2024-12-03 01:45:09', NULL, '::1'),
(42, 18, '6b2fd779c14e2e0013439bd332e02795b0812d63556f371312b689f8bf6a0fa2', '2024-12-03 02:06:11', '2024-12-03 02:07:41', '::1'),
(43, 18, '2e19dbb8d77961f50875688c03490b2107c305b88f833d15fcdb6b5c539a3913', '2024-12-03 02:40:50', NULL, '::1'),
(44, 18, 'd2f4da2dbf51d69dddaf7e07bc60707abe0fe6460ec278349b3cae8eb851a182', '2024-12-03 03:10:01', NULL, '::1'),
(45, 18, '608ea234d8e956f154e5d8bbf893436cd3b33e11ae2e4716fd8c2478c8ba7b81', '2024-12-03 03:22:54', '2024-12-03 04:13:24', '::1'),
(46, 20, '06613a18afb48cb3fe058aa7dd56e13aadaaa2677256955f29c6ff688e8ca8c3', '2024-12-03 04:13:50', '2024-12-03 04:46:48', '::1'),
(47, 20, 'db7146ab79573485be63c0436648f59a11c622cfc68b6424c4329ca6d1b022fd', '2026-01-20 09:40:39', '2026-01-20 09:41:13', '::1'),
(48, 21, 'f7ce1c392f53412495799bccf4188a553b013b00aa1356389249650353ea94d1', '2026-04-07 08:36:05', NULL, '::1'),
(49, 21, 'd0ffd2a1b43d744700f9c9ced74ef78bc8929c1a428b3e5579bc3e30f3eec0c6', '2026-04-08 08:58:06', NULL, '::1'),
(50, 21, '46cb0527d7d0283b0235b4cb9c77d25951294c77413cb8e84f672effbddf9efd', '2026-04-08 10:05:38', NULL, '127.0.0.1'),
(51, 21, 'a6857af54c6f029d842e4e5125f7b015c0073d38420f71d0e49315ffb1754da5', '2026-04-08 10:10:53', NULL, '::1'),
(52, 21, '3d9dd4ccef8f27ebfeddc6cb87fb71da25507dd70df013ad6e88f39efd62ae6a', '2026-04-08 10:15:56', NULL, '::1'),
(53, 21, '63da925ed934a94c3116968d2c1c8f99113273446013ecc6a5e56ada76623967', '2026-04-08 10:59:51', NULL, '127.0.0.1'),
(54, 21, '72184201f5fd97c34a3724fb12a4828d1060b51a3c8753a492ce13dbd229516a', '2026-04-13 11:53:56', '2026-04-13 12:00:29', '::1'),
(55, 21, '35ab8e39e9acfe408a99e3844632ddfd3d1f97ec35813e1e9293f1f01dd18b6c', '2026-04-13 12:00:39', NULL, '::1'),
(56, 21, '15fa6c351d27cb701e9e9c65a308a331bd36de4fbafa905cc27c75f0a70f13ec', '2026-04-13 12:02:28', NULL, '127.0.0.1'),
(57, 21, '6b8dc66d76ecd0862e5db6b78a7015f2dae85ce7a144b235a88a874518bb08af', '2026-04-13 12:03:32', NULL, '127.0.0.1'),
(58, 20, 'fdd79734a18c5c588396a279ae062a1f00703055b59644a0de688d6b6115f437', '2026-04-15 10:50:03', '2026-04-15 10:58:13', '::1'),
(59, 20, '1d00c05f54c7bc833111349f003ec5167d44a66c7b9ebe1c4e6c682e546c1d30', '2026-04-15 10:58:37', '2026-04-15 10:59:02', '127.0.0.1'),
(60, 21, 'ca3b3f6e3a34e3eb4dfbf5d7ba37e0a32673a57437d8fccdef5ac90d82c5aef5', '2026-04-15 10:59:17', '2026-04-15 10:59:21', '127.0.0.1'),
(61, 20, '0de00934f9420820059c8c2ff603b4cc73855ebe3bca7292479924e327d21000', '2026-04-15 11:00:01', '2026-04-15 12:21:09', '127.0.0.1'),
(62, 20, '105a12393e3fbdae80c0e66f773110c5bb50c4e75386299a5da4fc222abe94a2', '2026-04-15 13:25:35', NULL, '::1'),
(66, 35, '28609a95bcc879eb956a454a3ec7f42e9a48c4376c7a51db6d712ced683425da', '2026-04-17 14:11:24', '2026-04-17 14:14:27', '::1'),
(67, 35, 'ebf475e2087dcef089327c3d7099dad0d07376a0a2f15d16a62e66734e6e29a6', '2026-04-17 14:49:50', '2026-04-17 14:49:52', '::1'),
(68, 35, 'b7632a079e739a68d7282f9c8cc0004699dbe178eb1b4afb96c7d43ae0f36d85', '2026-04-18 02:53:40', '2026-04-18 02:53:42', '::1'),
(69, 35, 'da19bc3809f3483dd5a373b440df924e8ac63a0b3a039315827d2088ce83cc5b', '2026-04-18 03:39:49', '2026-04-18 03:39:50', '::1');

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

--
-- Volcado de datos para la tabla `tokens_recuperacion`
--

INSERT INTO `tokens_recuperacion` (`id`, `id_usuario`, `token`, `expira_en`, `usado`, `creado_en`) VALUES
(1, 21, '31b867655d447b96fe6cae9858d3d336d1889f150fef57ac1ad1dd4d138d0ee8', '2026-04-17 04:28:47', 0, '2026-04-17 01:58:47'),
(2, 35, '204da60761de83d97c88042d0a477118f744c43db8bc85fee2f2c2d485c259e2', '2026-04-17 08:55:51', 0, '2026-04-17 06:25:51'),
(3, 35, 'a0571b5716abf683164577463eb5076db0aa6721ee11cd0afab240cd8797779a', '2026-04-17 09:08:02', 0, '2026-04-17 06:38:02'),
(4, 35, '323723e1e8c7fdb98d00dbae76e27976bd2fdc5012731d56f1e4539092e2dede', '2026-04-17 09:17:26', 0, '2026-04-17 06:47:26'),
(5, 35, '31e0664e7b7ec5b521c3282fc06a827b6ac630413561add145299dd4757d6170', '2026-04-17 09:18:16', 1, '2026-04-17 06:48:16'),
(6, 35, '7d4aeb9b1bd046ad0ebe6d0de4a69a53c6b255b91b0133f259c61e87a0317e15', '2026-04-17 09:18:19', 0, '2026-04-17 06:48:19'),
(7, 35, 'bd5f7819ae6c6d522314bdc5bf585e682cc18cd6e7fa70659f0b7ec404c318d3', '2026-04-17 21:22:41', 1, '2026-04-17 18:52:41'),
(8, 35, '9a50ecb04d07a3d7619f6a65df85feef76ac606c2b892ae4f900088371bc7317', '2026-04-17 21:26:33', 0, '2026-04-17 18:56:33');

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
(36, 'mimi', 'bttl.breaker@gmail.com', '$2y$10$.BnIkkjQEshDCV.bmeW5xO0FLYYc5PqHQlpXCRQTyNFdrQeO02QqG', 'prospecto', '2222222222', '2026-04-17 19:43:44', 1, 0, NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id_sesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `tokens_recuperacion`
--
ALTER TABLE `tokens_recuperacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
