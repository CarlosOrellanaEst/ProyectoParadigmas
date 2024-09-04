-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-09-2024 a las 08:23:26
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbecotouristiar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbbankaccount`
--

CREATE TABLE `tbbankaccount` (
  `tbbankaccountid` int(11) NOT NULL,
  `tbbankaccountownerid` int(11) NOT NULL,
  `tbbankaccountnumber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbbankaccountbankname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbbankaccountstatus` tinyint(1) NOT NULL,
  `tbbankaccountisactive` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbbankaccount`
--

INSERT INTO `tbbankaccount` (`tbbankaccountid`, `tbbankaccountownerid`, `tbbankaccountnumber`, `tbbankaccountbankname`, `tbbankaccountstatus`, `tbbankaccountisactive`) VALUES
(1, 1, '1234567890123456', 'Bank of America', 1, 1),
(2, 2, '2345678901234567', 'Chase Bank', 1, 1),
(3, 3, '3456789012345678', 'Wells Fargo', 1, 1),
(4, 4, '4567890123456789', 'Citibank', 1, 1),
(5, 5, '5678901234567890', 'Goldman Sachs', 1, 1);

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
  `tbownerphotourl` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbownerstatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbowner`
--

INSERT INTO `tbowner` (`tbownerid`, `tbownername`, `tbownersurnames`, `tbownerlegalidentification`, `tbownerphone`, `tbowneremail`, `tbownerdirection`, `tbownerphotourl`, `tbownerstatus`) VALUES
(1, 'Carlos', 'González Pérez', '12345678', '555-1234', 'carlos.gonzalez@example.com', 'Calle Falsa 123, Ciudad', '', 0),
(2, 'Ana', 'Martínez López', '87654321', '555-5678', 'ana.martinez@example.com', 'Avenida Siempre Viva 742, Ciudad', '', 0),
(3, 'Luis', 'Ramírez Fernández', '11223344', '555-9101', 'luis.ramirez@example.com', 'Boulevard Libertad 90, Ciudad', '', 0),
(4, 'Marta', 'Cruz García', '44332211', '555-1122', 'marta.cruz@example.com', 'Plaza Mayor 5, Ciudad', '', 0),
(5, 'José', 'Sánchez Romero', '55667788', '555-3344', 'jose.sanchez@example.com', 'Calle de los Naranjos 20, Ciudad', '', 0),
(6, 'Dilan', 'Gutierrez', '987584983', '34532345', 'degutierrezh02@gmail.com', 'yadd', 'Cat03.jpg', 0),
(7, 'Dilan', 'Gutierrez Hernandez', '897564732', '12312345', 'safv13@sdajg.com', 'yadd', '../images/Cat03.jpg', 1),
(8, 'Dilan', 'Gutierrez Hernandez', '897564733', '76543214', 'carlo333@es.com', 'yadd', '../images/Cat03.jpg', 0),
(9, 'Carlos', 'Orellana', '857483987', '32345675', 'carlo33333@es.com', 'rf', '../images/dog.jpg', 0),
(10, 'FD', 'sf', '989584736', '61663697', 'asf214a@dd.com', 'sf', '../images/dog.jpg', 1),
(11, 'Dilan', 'asd', '376748376', '43567986', 'car3333333lo@es.com', 'yadd', '../images/dog.jpg', 0),
(12, 'Dilan', 'Orellana', '655434678', '43567854', 'degutierrezh44442@gmail.com', 'yadd', '../images/Cat03.jpg', 0),
(13, 'Elian', 'Gutierrez Hernandez', '654345678', '45323456', 'degutierrezh024444@gmail.com', 'yadd', '../images/Cat03.jpg', 0),
(14, 'Carlos', 'Orellana', '985749890', '34235678', 'safv14444523@sdajg.com', 'yadd', '../images/dog.jpg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbphoto`
--

CREATE TABLE `tbphoto` (
  `tbphotoid` int(11) NOT NULL,
  `tbphotourl` text NOT NULL,
  `tbphotoindex` text NOT NULL,
  `tbphotostatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbphoto`
--

INSERT INTO `tbphoto` (`tbphotoid`, `tbphotourl`, `tbphotoindex`, `tbphotostatus`) VALUES
(1, 'foca.jpg', '0', 1),
(2, 'pexels-jaden-van-heyningen-1257873595-27438918.jpg', '0', 1),
(3, 'IMG_1312.PNG', '0', 1),
(4, 'IMG_1312.PNG', '0', 1),
(5, 'WhatsApp Image 2024-08-12 at 17.24.09_7cd89334.jpg', '0', 1),
(6, 'pexels-jaden-van-heyningen-1257873595-27438918.jpg', '0', 1),
(7, 'dog.jpg', '0', 1);
-- --------------------------------------------------------
CREATE TABLE `tbactivity` (
  `tbactivityid` int(11) NOT NULL,
  `tbactivityname` varchar(255) NOT NULL,
  `tbactivityatributearray` varchar(255) NOT NULL,
  `tbactivitydataarray` varchar(255) NOT NULL,
  `tbactivitystatus` tinyint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
-- Estructura de tabla para la tabla `tbtouristcompany`
--

CREATE TABLE `tbtouristcompany` (
  `tbtouristcompanyid` int(11) NOT NULL,
  `tbtouristcompanylegalname` varchar(255) NOT NULL,
  `tbtouristcompanymagicname` varchar(255) NOT NULL,
  `tbtouristcompanyowner` int(11) NOT NULL,
  `tbtouristcompanycompanyType` int(11) NOT NULL,
  `tbphotoid` int(11) NOT NULL,
  `tbtouristcompanystatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbtouristcompany`
--

INSERT INTO `tbtouristcompany` (`tbtouristcompanyid`, `tbtouristcompanylegalname`, `tbtouristcompanymagicname`, `tbtouristcompanyowner`, `tbtouristcompanycompanyType`, `tbphotoid`, `tbtouristcompanystatus`) VALUES
(1, 'Ecoturistiar', 'Polloloko', 7, 1, 0, 1),
(2, 'iii', 'Polloloko', 7, 1, 0, 1),
(3, 'paana', 'Polloloko', 7, 1, 0, 1),
(4, 'Ecoturddd', 'ddd', 10, 1, 5, 1),
(5, 'Ecoturistooo', 'Polloloko', 7, 1, 6, 1),
(6, 'pppp', 'ddd', 7, 1, 7, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtouristcompanytype`
--

CREATE TABLE `tbtouristcompanytype` (
  `tbtouristcompanytypeid` int(11) NOT NULL,
  `tbtouristcompanytypename` varchar(100) NOT NULL,
  `tbtouristcompanytypedescription` varchar(250) NOT NULL,
  `tbtouristcompanytypeisactive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbtouristcompanytype`
--

INSERT INTO `tbtouristcompanytype` (`tbtouristcompanytypeid`, `tbtouristcompanytypename`, `tbtouristcompanytypedescription`, `tbtouristcompanytypeisactive`) VALUES
(1, 'Camping', 'senderismo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuser`
--

CREATE TABLE `tbuser` (
  `tbuserid` int(11) NOT NULL,
  `tbusername` varchar(255) NOT NULL,
  `tbuserlastname` varchar(255) NOT NULL,
  `tbuserpassword` varchar(255) NOT NULL,
  `tbuserphone` varchar(255) NOT NULL,
  `tbuserstatus` tinyint(1) NOT NULL,
  `tbusertype` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbuser`
--

INSERT INTO `tbuser` (`tbuserid`, `tbusername`, `tbuserlastname`, `tbuserpassword`, `tbuserphone`, `tbuserstatus`, `tbusertype`) VALUES
(1, 'admin', 'Admin', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '1234567890', 1, 'Administrador'),
(2, 'johndoe', 'Doe', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '9876543210', 1, 'Turista'),
(3, 'janedoe', 'Doe', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '5432167890', 1, 'Turista'),
(4, 'alice', 'Wonderland', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '7654321098', 1, 'Turista'),
(5, 'bob', 'Builder', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '8765432109', 1, 'Propietario');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
