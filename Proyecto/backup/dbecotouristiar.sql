-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 10-09-2024 a las 09:19:55
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
-- Base de datos: `dbecotouristiar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbactivity`
--

CREATE TABLE `tbactivity` (
  `tbactivityid` int(11) NOT NULL,
  `tbactivityname` varchar(255) NOT NULL,
  `tbservicecompanyid` int(11) NOT NULL,
  `tbactivityatributearray` varchar(255) NOT NULL,
  `tbactivitydataarray` varchar(255) NOT NULL,
  `tbactivityurl` text NOT NULL,
  `tbactivitystatus` tinyint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbactivity`
--

INSERT INTO `tbactivity` (`tbactivityid`, `tbactivityname`, `tbservicecompanyid`, `tbactivityatributearray`, `tbactivitydataarray`, `tbactivityurl`, `tbactivitystatus`) VALUES
(1, 'juegos', 1, '', '', '', 0),
(2, 'rnhbsg', 2, 'awdfaga,bwsbsb', 'gagag,f', 'foca.jpg,dog.jpg', 0),
(3, 'vvvvv', 1, '', '', 'Cat03.jpg', 0),
(4, 'nooooo', 1, '', '', 'IMG_1312.PNG,foca.jpg', 0),
(5, 'zzzzz', 1, '', '', 'dog.jpg', 1),
(6, 'Hola', 1, '', '', 'hola.jpg', 0),
(7, 'zzzzz', 1, '', '', 'foca.jpg', 0),
(8, 'Turismo', 3, 'Natacion,Estudio,meditacion', '3 metros 15,3 horas,20 min', '', 0),
(9, 'hhhhh', 1, '', '', '10188600.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbowner`
--

CREATE TABLE `tbowner` (
  `tbownerid` int(11) NOT NULL,
  `tbuserid` int(11) NOT NULL,
  `tbownerdirection` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbownerphotourl` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbownerstatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbowner`
--

INSERT INTO `tbowner` (`tbownerid`, `tbuserid`, `tbownerdirection`, `tbownerphotourl`, `tbownerstatus`) VALUES
(1, 2, 'Calle Falsa 123, Ciudad', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbpaymenttype`
--

CREATE TABLE `tbpaymenttype` (
  `tbpaymenttypeid` int(11) NOT NULL,
  `tbownerid` int(11) NOT NULL,
  `tbpaymenttypenumber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbpaymenttypesinpenumber` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tbpaymenttypestatus` tinyint(1) NOT NULL DEFAULT 1,
  `tbpaymenttypeisactive` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbpaymenttype`
--

INSERT INTO `tbpaymenttype` (`tbpaymenttypeid`, `tbownerid`, `tbpaymenttypenumber`, `tbpaymenttypesinpenumber`, `tbpaymenttypestatus`, `tbpaymenttypeisactive`) VALUES
(1, 1, '1234567890123456', 'Bank of America', 1, 1),
(2, 2, '2345678901234567', 'Chase Bank', 1, 1),
(3, 3, '3456789012345678', 'Wells Fargo', 1, 1),
(4, 4, '4567890123456789', 'Citibank', 1, 1),
(5, 5, '5678901234567890', 'Goldman Sachs', 1, 1),
(6, 1, 'CR12345678909876543210', '', 1, 0),
(7, 1, 'CR12345678909876543210', '', 1, 0),
(8, 5, 'CR12345678909876543210', '86237034', 1, 1);

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
-- Estructura de tabla para la tabla `tbservice`
--

CREATE TABLE `tbservice` (
  `tbserviceid` int(11) NOT NULL,
  `tbservicename` varchar(255) NOT NULL,
  `tbservicedescription` text NOT NULL,
  `tbservicetatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbservice`
--

INSERT INTO `tbservice` (`tbserviceid`, `tbservicename`, `tbservicedescription`, `tbservicetatus`) VALUES
(1, 'Consulta General', 'Servicio de consulta médica general para evaluar el estado de salud del paciente.', 1),
(2, 'Examen de Laboratorio', 'Servicio de análisis de sangre, orina y otros exámenes de laboratorio.', 1),
(3, 'Vacunación', 'Servicio de vacunación para prevenir enfermedades comunes como la gripe y el tétanos.', 1),
(4, 'Consulta Pediátrica', 'Consulta especializada en el tratamiento de niños y adolescentes.', 1),
(5, 'Revisión Dental', 'Servicio de revisión y limpieza dental para mantener una buena salud bucal.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbservicecompany`
--

CREATE TABLE `tbservicecompany` (
  `tbservicecompanyid` int(11) NOT NULL,
  `tbtouristcompanyid` int(11) NOT NULL,
  `tbserviceid` text NOT NULL,
  `tbservicecompanyURL` text NOT NULL,
  `tbservicetatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbservicecompany`
--

INSERT INTO `tbservicecompany` (`tbservicecompanyid`, `tbtouristcompanyid`, `tbserviceid`, `tbservicecompanyURL`, `tbservicetatus`) VALUES
(1, 9, '3,4', '10188600.jpg', 1),
(2, 8, '4', 'Cat03.jpg', 1);

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
  `tbtouristcompanyurl` text NOT NULL,
  `tbtouristcompanystatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbtouristcompany`
--

INSERT INTO `tbtouristcompany` (`tbtouristcompanyid`, `tbtouristcompanylegalname`, `tbtouristcompanymagicname`, `tbtouristcompanyowner`, `tbtouristcompanycompanyType`, `tbtouristcompanyurl`, `tbtouristcompanystatus`) VALUES
(1, 'EMPRESA1', 'EMPRESACHIDA1', 2, 2, '', 0),
(2, 'EMPRESA2', 'LAMASCHIDA2', 5, 5, '', 0),
(3, 'PRUEBAELIMINAR3', 'CALAVERA3', 4, 3, '', 0),
(4, '', '', 2, 0, 'dog.jpg', 0),
(5, '', '', 1, 0, '', 0),
(6, 'No', '', 1, 0, 'foca.jpg', 0),
(7, 'No', '', 2, 0, 'IMG_1312.PNG', 0),
(8, 'PRUEBAELIMINAR3', 'depende', 2, 1, 'dog.jpg', 1),
(9, 'EMPRESA2', 'Hola', 3, 4, 'dog.jpg,Cat03.jpg', 1),
(10, 'EMPRESA1', '', 3, 1, 'IMG_1312.PNG', 1);

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
(1, 'Agencia de Viajes', 'Agencia especializada en turismo nacional e internacional.', 1),
(2, 'Guía Turístico', 'Servicios de guías turísticos especializados en diferentes regiones.', 1),
(3, 'Transporte Turístico', 'Servicios de transporte para turistas, incluyendo autobuses y vans.', 1),
(4, 'Alojamiento', 'Proveedores de alojamiento para turistas, como hoteles y hostales.', 1),
(5, 'Actividades Recreativas', 'Organización de actividades recreativas y eventos para turistas.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuser`
--

CREATE TABLE `tbuser` (
  `tbuserid` int(11) NOT NULL,
  `tbusername` varchar(255) NOT NULL,
  `tbusersurnames` varchar(255) NOT NULL,
  `tbuserlegalidentification` varchar(255) NOT NULL,
  `tbuserphone` varchar(255) NOT NULL,
  `tbuseremail` varchar(255) NOT NULL,
  `tbusernickname` varchar(255) NOT NULL,
  `tbuserpassword` varchar(255) NOT NULL,
  `tbrollid` int(11) NOT NULL,
  `tbuserstatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbuser`
--

INSERT INTO `tbuser` (`tbuserid`, `tbusername`, `tbusersurnames`, `tbuserlegalidentification`, `tbuserphone`, `tbuseremail`, `tbusernickname`, `tbuserpassword`, `tbrollid`, `tbuserstatus`) VALUES
(1, 'admin', 'admin', '123456789', '84004800', 'admin@gmail.com', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 1, 1),
(2, 'propietario', 'propietario', '402590268', '89571689', 'propietario@hotmail.com', 'propietario', '21be11e996807bcef07a0f66fc1ec17402e138fdda66913814b7c02b32fb24ad', 3, 1),
(3, 'turista', 'turista', '402590269', '86942578', 'turista@gmail.com', 'turista', 'd5672f037da8c5c5c277dca73331af8116f8e6fc5cdc0b4beb9268dd410c78c1', 2, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;