-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-05-2024 a las 15:50:26
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
-- Base de datos: `ProyectoABD`
--

--
-- Volcado de datos para la tabla `canciones`
--

INSERT INTO `canciones` (`id`, `name`, `genero`, `artista`, `duracion`) VALUES
(1, 'Callejera', 'Pop', 'Delgao', '260'),
(2, 'Marihuana', 'Rock', 'SKP', '240'),
(3, 'Bohemian Rhapsody', 'Rock', 'Queen', '1860'),
(4, 'Imagine', 'Pop', 'John Lennon', '174'),
(5, 'Hey Jude', 'Pop', 'The Beatles', '218'),
(6, 'Billie Jean', 'Pop', 'Michael Jackson', '298'),
(7, 'Like a Virgin', 'Pop', 'Madonna', '357'),
(8, 'Smells Like Teen Spirit', 'Rock', 'Nirvana', '358'),
(9, 'Lose Yourself', 'Pop', 'Eminem', '252'),
(10, 'Thunderstruck', 'Rock', 'AC/DC', '367'),
(11, 'Symphony No. 5', 'Clasica', 'Beethoven', '1800'),
(12, 'Clair de Lune', 'Clasica', 'Claude Debussy', '450'),
(13, 'The Four Seasons', 'Clasica', 'Antonio Vivaldi', '2400'),
(14, 'Ride of the Valk', 'Clasica', 'Richard Wagner', '540'),
(15, 'Carmen Suite', 'Clasica', 'Georges Bizet', '1200'),
(16, 'Da Funk', 'Electronica', 'Daft Punk', '360'),
(17, 'Blue Monday', 'Electronica', 'New Order', '420'),
(18, 'The Box', 'Electronica', 'Orbital', '480'),
(19, 'Levitation', 'Electronica', 'The Chemical Brothers', '300'),
(20, 'Around the World', 'Electronica', 'Daft Punk', '540'),
(21, 'Lose Yourself', 'HipHop', 'Eminem', '240'),
(22, 'Still DRE', 'HipHop', 'Dr Dre', '300'),
(23, 'Nuthin But a G Thang', 'HipHop', 'Dr Dre', '270'),
(24, 'California Love', 'HipHop', '2Pac feat. Dr. Dre', '420'),
(25, 'Juicy', 'HipHop', 'The Notorious B.I.G.', '360'),
(26, 'Take Five', 'Jazz', 'Dave Brubeck Quartet', '480'),
(27, 'So What', 'Jazz', 'Miles Davis', '540'),
(28, 'A Night in Tunisia', 'Jazz', 'Dizzy Gillespie', '600'),
(29, 'Round Midnight', 'Jazz', 'Thelonious Monk', '300'),
(30, 'In a Sentimental Mood', 'Jazz', 'Duke Ellington', '420'),
(31, 'Billie Jean', 'Pop', 'Michael Jackson', '270'),
(32, 'Thriller', 'Pop', 'Michael Jackson', '540'),
(33, 'Like a Virgin', 'Pop', 'Madonna', '360'),
(34, 'I Will Always Love You', 'Pop', 'Whitney Houston', '420'),
(35, 'Bohemian Rhapsody', 'Pop', 'Queen', '540'),
(36, 'Gasolina', 'Reggaeton', 'Daddy Yankee', '240'),
(37, 'Despacito', 'Reggaeton', 'Luis Fonsi feat. Daddy Yankee', '300'),
(38, 'Mayores', 'Reggaeton', 'Becky G feat. Bad Bunny', '270'),
(39, 'Dura', 'Reggaeton', 'Daddy Yankee', '240'),
(40, 'Mi Gente', 'Reggaeton', 'J Balvin feat. Willy William', '270'),
(41, 'Mi primo dijo primo', 'Pop', 'Delgao', '420'),
(42, 'Vois sur ton chemin', 'Techno', 'BENNET', '510');

--
-- Volcado de datos para la tabla `cancionesFavoritas`
--

INSERT INTO `cancionesFavoritas` (`user_id`, `song_id`, `stars`) VALUES
(7, 1, 5),
(7, 10, 2),
(7, 11, 4),
(9, 36, 4),
(9, 19, 3),
(9, 2, 5),
(9, 12, 3),
(9, 28, 1),
(9, 1, 5),
(9, 3, 3),
(9, 5, 1),
(9, 4, 3),
(8, 1, 5),
(8, 2, 4),
(8, 3, 2),
(8, 4, 3),
(8, 6, 4),
(8, 40, 3),
(8, 41, 5),
(5, 1, 4),
(5, 2, 4),
(5, 3, 3),
(5, 4, 1),
(5, 6, 4),
(5, 42, 5),
(5, 9, 5),
(7, 2, 4),
(7, 42, 5),
(7, 9, 3),
(10, 1, 5),
(10, 42, 4),
(10, 9, 5),
(10, 2, 2),
(10, 3, 4),
(10, 4, 2),
(10, 5, 4),
(10, 6, 3),
(10, 41, 4),
(11, 1, 5),
(11, 41, 5),
(11, 2, 3),
(11, 3, 2),
(11, 5, 3),
(11, 32, 3);

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`genero`) VALUES
('Clasica'),
('Electronica'),
('HipHop'),
('Jazz'),
('Pop'),
('Reggaeton'),
('Rock'),
('Techno');

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `img`) VALUES
(5, 'juanita', '123456', '/mortadelaaaa.jpg'),
(7, 'lucasbueno', 'password', '/mota.jpeg'),
(8, 'admin', 'admin', '/images3.jpeg'),
(9, 'maria2', '1235', '/Sin título.jpeg'),
(10, 'albertito86', 'elpuma123', '/Sin título5.jpeg'),
(11, 'antonio16', 'antolin321', '/Sin título34.jpeg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
