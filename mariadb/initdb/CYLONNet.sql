-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 19-04-2025 a las 18:26:58
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `CYLONNet`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctfs`
--

CREATE TABLE `ctfs` (
  `id` int(5) NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` varchar(500) NOT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`tags`)),
  `difficulty` int(1) NOT NULL,
  `icon` varchar(70) NOT NULL,
  `dockerlocation` varchar(70) NOT NULL,
  `uflag` varchar(16) DEFAULT NULL,
  `rflag` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE `tags` (
  `tagname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tags`
--

INSERT INTO `tags` (`tagname`) VALUES
('Base64'),
('Bash'),
('PHP'),
('Python'),
('JavaScript'),
('HTML'),
('CSS'),
('XML'),
('JSON'),
('CSRF'),
('Injection'),
('LFI'),
('Misconfig'),
('OpenRedirect'),
('PrivilegeEsc'),
('RFI'),
('SessionHijacking'),
('SQLInjection'),
('XSS'),
('Bruteforce'),
('CommandInjection'),
('Cryptography'),
('DOS'),
('DirectoryTraversal'),
('FileUpload'),
('InsecureDeserialization'),
('LDAPInjection'),
('NoSQLInjection'),
('PathTraversal'),
('RaceCondition'),
('SSRF'),
('CVE'),
('XXE'),
('SSTI'),
('RCE'),
('XMLInjection'),
('BufferOverflow');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(80) NOT NULL,
  `xp` int(9) NOT NULL,
  `developer` tinyint(1) NOT NULL,
  `icon` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `users` (`id`, `username`, `email`, `password`, `xp`, `developer`, `icon`) VALUES (1, 'cylon_adm', 'cylon_adm@ucm.es', '$2y$10$aCzZSQ5FFTVelXWzfRN9Q.x0cEJUKziMBj.5EBeuIodtMSwe1/UuO', 0, 1, '/icon.gif');

-- --------------------------------------------------------

-- my_secure_password
-- Estructura de tabla para la tabla `userxctf`
--

CREATE TABLE `userxctf` (
  `id_user` int(5) NOT NULL,
  `id_ctf` int(5) NOT NULL,
  `ucompletado` tinyint(1) NOT NULL,
  `rcompletado` tinyint(1) NOT NULL,
  `creada` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ctfs`
--
ALTER TABLE `ctfs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tagname`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `userxctf`
--
ALTER TABLE `userxctf`
  ADD KEY `id_user` (`id_user`,`id_ctf`),
  ADD KEY `id_ctf` (`id_ctf`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `userxctf`
--
ALTER TABLE `userxctf`
  ADD CONSTRAINT `userxctf_ibfk_1` FOREIGN KEY (`id_ctf`) REFERENCES `ctfs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userxctf_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
