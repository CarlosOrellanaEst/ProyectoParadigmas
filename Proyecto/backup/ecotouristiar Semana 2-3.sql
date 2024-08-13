-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-08-2024 a las 05:26:52
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
-- Base de datos: `ecotouristiar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbbankaccount`
--

CREATE TABLE `tbbankaccount` (
  `tbbankAccountId` int(11) NOT NULL,
  `tbbankAccountOwnerId` int(11) NOT NULL,
  `tbbankAccountNumber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbbankAccountBankName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbbankAccountStatus` tinyint(1) NOT NULL,
  `tbbankAccountIsActive` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbbankaccount`
--

INSERT INTO `tbbankaccount` (`tbbankAccountId`, `tbbankAccountOwnerId`, `tbbankAccountNumber`, `tbbankAccountBankName`, `tbbankAccountStatus`, `tbbankAccountIsActive`) VALUES
(1, 10, '1111111', 'bcr', 1, 0),
(2, 2, '33', 'ddd', 0, 0),
(3, 10, '5', 'sdf', 1, 0),
(4, 10, '5645', 'dfgdfg', 0, 0),
(5, 10, '22', 'ddd', 1, 0),
(6, 10, '4300', 'na', 0, 0),
(7, 2, '736464', 'jdd', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbowner`
--

CREATE TABLE `tbowner` (
  `tbownerid` int(11) NOT NULL,
  `tbownername` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbownersurnames` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbownerlegalidentification` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbownerphone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbowneremail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbownerdirection` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbownerstatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbroll`
--

CREATE TABLE `tbroll` (
  `tbrollid` int(11) NOT NULL,
  `tbrollname` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbrolldescription` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbrollstatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbroll`
--

INSERT INTO `tbroll` (`tbrollid`, `tbrollname`, `tbrolldescription`, `tbrollstatus`) VALUES
(1, 'Administrador', 'Acceso completo a todas las funciones y configuraciones del sistema.', 1),
(2, 'Turista', 'Potencial cliente de los negocios registrados en el sistema', 1),
(3, 'Propietario', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuser`
--

CREATE TABLE `tbuser` (
  `tbuserid` int(11) NOT NULL,
  `tbuserName` varchar(255) NOT NULL,
  `tbuserLastName` varchar(255) NOT NULL,
  `tbuserpassword` varchar(255) NOT NULL,
  `tbuserphone` varchar(255) NOT NULL,
  `tbuserStatus` tinyint(1) NOT NULL,
  `tbuserType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbuser`
--

INSERT INTO `tbuser` (`tbuserid`, `tbuserName`, `tbuserLastName`, `tbuserpassword`, `tbuserphone`, `tbuserStatus`, `tbuserType`) VALUES
(1, 'admin', 'Admin', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '1234567890', 1, 'Administrador'),
(2, 'johndoe', 'Doe', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '9876543210', 1, 'Turista'),
(3, 'janedoe', 'Doe', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '5432167890', 1, 'Turista'),
(4, 'alice', 'Wonderland', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '7654321098', 1, 'Turista'),
(5, 'bob', 'Builder', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '8765432109', 1, 'Propietario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbowner`
--
ALTER TABLE `tbowner`
  ADD UNIQUE KEY `tbowneremail` (`tbowneremail`),
  ADD UNIQUE KEY `tbownerlegalidentification` (`tbownerlegalidentification`),
  ADD UNIQUE KEY `tbownerphone` (`tbownerphone`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;