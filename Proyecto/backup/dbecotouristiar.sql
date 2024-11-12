-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 12-11-2024 a las 15:54:04
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
  `tbactivityservicecompanyid` int(11) NOT NULL,
  `tbactivityatributearray` varchar(1000) DEFAULT NULL,
  `tbactivitydataarray` varchar(1000) NOT NULL,
  `tbactivityurl` text NOT NULL,
  `tbactivitystatus` tinyint(4) NOT NULL,
  `tbactivitydate` datetime NOT NULL,
  `tbactivitylatitude` decimal(12,8) NOT NULL,
  `tbactivitylongitude` decimal(12,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbactivity`
--

INSERT INTO `tbactivity` (`tbactivityid`, `tbactivityname`, `tbactivityservicecompanyid`, `tbactivityatributearray`, `tbactivitydataarray`, `tbactivityurl`, `tbactivitystatus`, `tbactivitydate`, `tbactivitylatitude`, `tbactivitylongitude`) VALUES
(1, 'Senderismo en el Bosque', 2, '', '', 'Cat03.jpg,pexels1.jpg', 1, '2024-11-28 09:00:00', 10.12345678, -84.12345678),
(2, 'Fotografía de Vida Silvestre', 1, '', '', 'pexels2.jpg', 1, '2024-11-22 07:30:00', 9.87654321, -83.98765432),
(3, 'Kayak en el Río', 5, 'Duración,Nivel de Dificultad', '3 horas,Moderado', 'dog.jpg,pexels3.jpg', 1, '2024-10-05 11:00:00', 10.23456789, -84.23456789),
(4, 'Paseo en Caballo', 4, 'Duración,Tamaño del Grupo', '2 horas,10 personas', 'IMG_1312.PNG,uwu.jpeg', 1, '2024-10-07 08:00:00', 9.12345678, -83.12345678),
(5, 'Excursión Nocturna', 1, 'Duración,Equipo Necesario', '5 horas,Linterna y repelente', 'miAmigueCarlos.jpg,gerald.jpg', 1, '2024-10-09 18:00:00', 10.56789012, -84.56789012),
(6, 'Prueba', 5, '', '', 'Screenshot from 2024-09-30 23-29-50.png,Screenshot from 2024-09-30 23-30-22.png,Screenshot from 2024-10-01 00-01-10.png,Screenshot from 2024-10-02 15-23-15.png', 1, '2024-11-14 11:54:00', 10.39097779, -83.74149866),
(7, 'Actividad1', 6, 'Atriubuto1D', 'Dato1', 'Screenshot from 2024-09-30 23-29-50.png,Screenshot from 2024-09-30 23-30-22.png,Screenshot from 2024-10-01 00-01-10.png,Screenshot from 2024-10-02 15-23-15.png,Screenshot from 2024-10-02 15-26-13.png', 1, '2024-11-29 08:35:00', 10.33153832, -84.42951746);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbbooking`
--

CREATE TABLE `tbbooking` (
  `tbbookingid` int(11) NOT NULL,
  `tbactivityid` int(11) NOT NULL,
  `tbuserid` int(11) NOT NULL,
  `tbbookingnumberpersons` int(11) NOT NULL,
  `tbbookingstatus` tinyint(1) NOT NULL,
  `tbbookingdate` date NOT NULL,
  `tbbookingconfirmation` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbbooking`
--

INSERT INTO `tbbooking` (`tbbookingid`, `tbactivityid`, `tbuserid`, `tbbookingnumberpersons`, `tbbookingstatus`, `tbbookingdate`, `tbbookingconfirmation`) VALUES
(1, 1, 1, 10, 1, '2024-10-19', b'0'),
(2, 1, 1, 100, 1, '2024-10-19', b'0'),
(3, 6, 1, 6, 1, '2024-10-19', b'0'),
(4, 6, 1, 22, 1, '2024-10-19', b'0'),
(5, 5, 1, 9, 1, '2024-10-19', b'0'),
(6, 5, 1, 3, 1, '2024-10-19', b'0'),
(7, 5, 1, 12, 1, '2024-10-19', b'0'),
(8, 4, 1, 22, 1, '2024-10-19', b'0'),
(9, 5, 38, 3, 1, '2024-11-12', b'0'),
(10, 1, 40, 3, 1, '2024-11-12', b'0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcompanycustomtype`
--

CREATE TABLE `tbcompanycustomtype` (
  `tbcompanycustomtypeid` int(11) NOT NULL,
  `tbtouristcompany` int(11) NOT NULL,
  `tbcompanycustomtype` varchar(100) NOT NULL,
  `tbcompanycustomtypestatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcustomizedtouristcompanytype`
--

CREATE TABLE `tbcustomizedtouristcompanytype` (
  `tbcustomizedtouristcompanytypeid` int(11) NOT NULL,
  `tbownerid` int(11) NOT NULL,
  `tbtouristcompanyid` int(11) NOT NULL,
  `tbcustomizedtouristcompanytypename` varchar(200) NOT NULL,
  `tbcustomizedtouristcompanytypestatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbowner`
--

CREATE TABLE `tbowner` (
  `tbownerid` int(11) NOT NULL,
  `tbuserid` int(11) NOT NULL,
  `tbownerdirection` varchar(500) NOT NULL,
  `tbownerphotourl` varchar(500) NOT NULL,
  `tbownerstatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbowner`
--

INSERT INTO `tbowner` (`tbownerid`, `tbuserid`, `tbownerdirection`, `tbownerphotourl`, `tbownerstatus`) VALUES
(1, 2, 'Calle Falsa 123, Ciudad', '', 1),
(2, 4, '', '', 1),
(3, 5, '', '', 1),
(4, 6, '', '', 1),
(5, 7, 'san luis', '', 1),
(6, 8, '', '', 1),
(7, 9, 'aqui4534', '../images/Screenshot from 2024-09-30 23-30-22.png', 0),
(8, 10, 'Anita Grande', '../images/Screenshot from 2024-10-02 16-01-44.png', 0),
(9, 12, '', '', 0),
(10, 13, '', '', 0),
(11, 14, '', '', 0),
(12, 15, '', '', 1),
(13, 16, '', '', 1),
(14, 17, '', '', 0),
(15, 18, '', '', 1),
(16, 19, 'Depende', '', 1),
(17, 20, 'EEUU', '../images/Screenshot from 2024-10-02 15-26-13.png', 1),
(18, 21, 'SAn Jose, curridabat', '../images/Screenshot from 2024-10-02 15-26-13.png', 1),
(19, 22, '', '', 0),
(20, 23, '', '', 0),
(21, 24, '', '', 0),
(22, 25, '', '', 0),
(23, 26, '', '', 0),
(24, 27, '', '', 1),
(25, 30, 'Cairo', '', 1),
(26, 31, 'Cairo', '', 1),
(27, 32, 'Cairo', '', 1),
(28, 34, 'Cairo', '', 0),
(29, 35, 'Cairo', '', 1),
(30, 36, 'Cairo', '', 1),
(31, 37, '', '../images/Screenshot from 2024-10-01 00-01-10.png', 1),
(32, 39, '', '../images/Screenshot from 2024-10-02 15-23-15.png', 1);

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
(1, 1, 'CR12345678909876543219', '84257618', 1, 1),
(2, 2, 'CR12345678909876543225', '84257615', 1, 1),
(3, 3, 'CR12345678909876543296', '84257617', 1, 1),
(4, 4, 'CR12345678909876543824', '89647812', 1, 1),
(5, 5, 'CR12345678909876543879', '86237087', 1, 1),
(6, 1, 'CR12345678909876543210', '', 1, 0),
(9, 5, 'CR12345678909876543210', '86237034', 1, 1),
(8, 2, 'CR12345678909876543298', '', 1, 0),
(10, 15, 'CR12345678909876543777', '62054312', 1, 1),
(11, 3, 'CR12345678909876543888', '89746682', 1, 1),
(12, 3, 'CR12345678909876543999', '', 1, 0),
(13, 3, 'CR12345678909876543999', '', 1, 0),
(14, 3, 'CR12345678909876543111', '', 1, 0),
(15, 3, 'CR12345678909876543222', '', 1, 0),
(16, 3, 'CR12345678909876543222', '', 1, 0),
(17, 3, 'CR12345678909876543111', '', 1, 0),
(18, 3, 'CR12345678909876543444', '87876522', 1, 1),
(19, 3, 'CR12345678909876543699', '', 1, 0),
(20, 31, 'CR12345678901234567444', '', 1, 1),
(21, 32, 'CR12345678901234567222', '87345634', 1, 1);

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
(3, 'Propietario', '', 1),
(4, 'nuevo', 'nuevaDescripcion', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbservice`
--

CREATE TABLE `tbservice` (
  `tbserviceid` int(11) NOT NULL,
  `tbservicename` varchar(255) NOT NULL,
  `tbservicedescription` text NOT NULL,
  `tbservicestatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbservice`
--

INSERT INTO `tbservice` (`tbserviceid`, `tbservicename`, `tbservicedescription`, `tbservicestatus`) VALUES
(1, 'Senderismo Guiado', 'Exploración de rutas naturales con guías expertos que proporcionan información sobre la flora y fauna local.', 1),
(2, 'Observación de Aves', 'Actividad que permite a los participantes observar y aprender sobre las diversas especies de aves en su hábitat natural.', 1),
(3, 'Kayak en Ríos y Lagos', 'Recorridos en kayak por ríos y lagos para disfrutar del paisaje y la vida silvestre desde una perspectiva única.', 1),
(4, 'Ciclismo de Montaña', 'Rutas de ciclismo por terrenos montañosos que ofrecen vistas impresionantes y un desafío físico.', 1),
(5, 'Camping Ecológico', 'Experiencia de camping en entornos naturales, con prácticas sostenibles y respeto por el medio ambiente.', 1),
(6, 'Trekking de Multi-Días', 'Recorridos a pie de varios días por paisajes naturales, con alojamiento en campamentos o refugios ecológicos.', 1),
(7, 'Paseos en Caballo', 'Recorridos a caballo por áreas naturales, permitiendo una conexión cercana con la naturaleza y los animales.', 1),
(8, 'Visita a Reservas Naturales', 'Visitas guiadas a reservas naturales protegidas, donde se puede aprender sobre la conservación y biodiversidad.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbservicecompany`
--

CREATE TABLE `tbservicecompany` (
  `tbservicecompanyid` int(11) NOT NULL,
  `tbtouristcompanyid` int(11) NOT NULL,
  `tbserviceid` text NOT NULL,
  `tbservicecompanyURL` text NOT NULL,
  `tbservicestatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbservicecompany`
--

INSERT INTO `tbservicecompany` (`tbservicecompanyid`, `tbtouristcompanyid`, `tbserviceid`, `tbservicecompanyURL`, `tbservicestatus`) VALUES
(1, 9, '4,1,7', '', 1),
(2, 10, '8,6,3,7', '', 1),
(3, 20, '2,5,6,8', 'Screenshot from 2024-09-30 23-29-50.png,Screenshot from 2024-09-30 23-30-22.png,Screenshot from 2024-10-01 00-01-10.png,Screenshot from 2024-10-02 15-23-15.png', 1),
(4, 12, '2,4', 'Screenshot from 2024-09-30 23-29-50.png,Screenshot from 2024-09-30 23-30-22.png,Screenshot from 2024-10-01 00-01-10.png,Screenshot from 2024-10-02 15-26-13.png', 1),
(5, 22, '8', 'Screenshot from 2024-09-30 23-29-50.png,Screenshot from 2024-09-30 23-30-22.png,Screenshot from 2024-10-01 00-01-10.png', 1),
(6, 23, '1,2', 'Screenshot from 2024-09-30 23-29-50.png,Screenshot from 2024-09-30 23-30-22.png,Screenshot from 2024-10-01 00-01-10.png,Screenshot from 2024-10-02 15-23-15.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtouristcompany`
--

CREATE TABLE `tbtouristcompany` (
  `tbtouristcompanyid` int(11) NOT NULL,
  `tbtouristcompanylegalname` varchar(255) NOT NULL,
  `tbtouristcompanymagicname` varchar(255) NOT NULL,
  `tbtouristcompanyowner` int(11) NOT NULL,
  `tbtouristcompanycompanyType` text NOT NULL,
  `tbtouristcompanyurl` text NOT NULL,
  `tbtouristcompanystatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbtouristcompany`
--

INSERT INTO `tbtouristcompany` (`tbtouristcompanyid`, `tbtouristcompanylegalname`, `tbtouristcompanymagicname`, `tbtouristcompanyowner`, `tbtouristcompanycompanyType`, `tbtouristcompanyurl`, `tbtouristcompanystatus`) VALUES
(1, 'EMPRESA1', 'EMPRESACHIDA1', 2, '2', '', 0),
(2, 'EMPRESA2', 'LAMASCHIDA2', 5, '5', '', 0),
(3, 'PRUEBAELIMINAR3', 'CALAVERA3', 4, '3', '', 0),
(6, 'No', '', 1, '0', 'foca.jpg', 0),
(7, 'No', '', 2, '0', 'IMG_1312.PNG', 0),
(8, 'PRUEBAELIMINAR3', 'depende', 2, '1', 'dog.jpg', 0),
(9, 'EMPRESA2', 'Empresachida2', 2, '2', '', 1),
(10, 'EMPRESA1', '', 3, '1', '', 1),
(12, 'PRUEBAELIMINAR3', '', 1, '1', '', 1),
(13, 'PRUEBAELIMINAR3', '', 2, '0', '', 0),
(14, 'ad', 'awfawf', 16, '3', '', 1),
(15, 'herhh', '', 5, '2', '', 0),
(17, 'AAAAAAA', '', 2, '4', '', 0),
(19, 'pruebaServiciosActividadesCarlos.SA', 'pruebaServiciosActividadesCarlos', 24, '2', '', 1),
(20, 'EmpresaRaw', 'Yo', 31, '2,4,6', 'Screenshot from 2024-10-02 15-23-15.png', 1),
(21, 'Prueba', 'a', 31, '2,5,4', 'Screenshot from 2024-09-30 23-29-50.png,Screenshot from 2024-09-30 23-30-22.png,Screenshot from 2024-10-02 15-26-13.png', 1),
(22, 'a', 'a', 31, '6', 'Screenshot from 2024-09-30 23-30-22.png,Screenshot from 2024-10-01 00-01-10.png', 1),
(23, 'dEPENDE', 'A', 32, '1,2,3', 'Screenshot from 2024-09-30 23-30-22.png,Screenshot from 2024-10-01 00-01-10.png,Screenshot from 2024-10-02 15-26-13.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtouristcompanytouristcompanytype`
--

CREATE TABLE `tbtouristcompanytouristcompanytype` (
  `tbtouristcompanytouristcompanytypeid` int(11) NOT NULL,
  `tbtouristcompany` int(11) NOT NULL,
  `tbtouristcompanytype` int(11) NOT NULL,
  `tbtouristcompanytouristcompanytypestatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbtouristcompanytouristcompanytype`
--

INSERT INTO `tbtouristcompanytouristcompanytype` (`tbtouristcompanytouristcompanytypeid`, `tbtouristcompany`, `tbtouristcompanytype`, `tbtouristcompanytouristcompanytypestatus`) VALUES
(1, 22, 6, 1);

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
(5, 'Actividades Recreativas', 'Organización de actividades recreativas y eventos para turistas.', 1),
(6, 'Rafting', '', 1),
(7, 'adfawff', '', 0);

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
(1, 'admin', 'admin', '123456789', '84004800', 'admin@gmail.com', 'admin', '$2b$12$q8mTRMOR4OoY1ZXfk0p6Pe37jZR/dXlhn1302U5BtR.XN2ebXHJg6', 1, 1),
(2, 'propietario', 'propietario', '402590268', '89571689', 'propietario@hotmail.com', 'propietario', '$2a$12$K3V6aiws5PuHGP6nT0nhpepenBrW9aQS4j/XEqpgrHVzTCm6tPFxG\r\n', 3, 1),
(3, 'turista', 'turista', '402590269', '86942578', 'turista@gmail.com', 'turista', 'd5672f037da8c5c5c277dca73331af8116f8e6fc5cdc0b4beb9268dd410c78c1', 2, 1),
(4, 'Daisy', 'Cedenio', '7638223', '87875628', 'prueba@gmail.com', 'Daisy', 'Daisy', 3, 1),
(5, 'Carlos', 'orellana', '789293819832', '87867234', 'carlos@gmail.com', '', '', 3, 1),
(6, 'Daisy', 'Cedenoio', '72837823782', '87323223', 'daisy@gmail.com', 'Daisy', 'Daisy', 3, 1),
(7, 'Glend', 'Rojas', '703040371', '98765432', 'glend@gmail.com', '', '', 3, 1),
(8, 'prueba', '', '389847634', '', 'prueb@gmail.com', 'prueba', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 3, 1),
(9, 'PruebaDef', 'Hola', 'DR2364', '78675688', 'AQUI@GMAIL.COM', 'PruebaDef', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 3, 0),
(10, 'Pedro', 'Perez', 'PR8734675823', '87888888', 'pedro@gmail.com', 'Pedro', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 3, 0),
(11, 'Gliturista', '', '789098765', '', 'gliturista@gmail.com', 'Gli2003', '$2y$10$4ZL3gFZPe36.2Rpatp2oM.zWz7QWQo7gJ8I8knmV2AetmDe22Uyoi', 2, 1),
(12, 'GliOwner', '', '999999999', '11111111', 'gliowner@gmail.com', 'GliOwner', '$2y$10$rO717fAbFe53DEtTV7LXAeu1XpY8arsdiXxN6t92BcSkBsMNGN3py', 3, 0),
(13, 'pfaf', '', '678545444', '', 'ooooo@gmail.com', 'pfaf', '$2y$10$bhqAhzGoOI9gx3BZmoMes.pXOWZ26Of4p2RePqFES28o8UacA11UW', 3, 0),
(14, 'GliOwner', '', '938572000', '', 'egfnj@gmail.com', 'GliOwner', '$2y$10$CQ60XtsXG2v7xbX3C8a5He/Sfkspu/wT24t887ra8uukPejOgSCQ6', 3, 0),
(15, 'Jose', '', '287364561', '', 'juan@gmail.com', '287364561', '$2y$10$HQDrWOZ.PteOg7.X7RsvCu2ct7I0pNf8dH.hGig0usPVMHSk.W1Aa', 3, 1),
(16, 'Ramon', '', '728361211', '', 'ramon@gmail.com', '728361211', '$2y$10$t3PNExCxY8oQ/pijZPF9ru.oSwdj1m1mJD7No0fsN8fHCMdYk1E/y', 3, 1),
(17, 'wfaf', '', '736451232', '', 'prueba777@gmail.com', '736451232', '$2y$10$IHHa921FMZMB8JxUmBM9K.xOO8UOBIyubX3/l6tfoLOy9chWl7yd6', 3, 0),
(18, 'beto', '', '827365432', '', 'beto@gmail.com', 'betoGame', '$2y$10$bixbYvod6lFRzHEEwj2u3eHCG5ioWN.Euz9Wgv908LHMPd2D7U34y', 3, 1),
(19, 'Keyla', 'Alvarado', '434343434', '87234321', 'key@gmail.com', 'key', '$2y$10$L59mmYppILLzSMNYx/3qtepveIoy3GlFZFA0iaEqsX9SXXqFHjXP6', 3, 1),
(20, 'Toliver', 'Fernandez', 'EEUU62378', '87234312', 'toli@gmail.com', 'don toliver', '$2y$10$LeQV9oP5gG0GoIlPKoiykuCUNh6AaetdCb91SRsNMnhbMm2IBlwEK', 3, 1),
(21, 'Pedro', 'Fernandez', '872343212', '87238934', 'pedro@gmail.com', 'PedritoPro2003', '$2y$10$l2SZukHtXpiZ3klvnnPeEuyomKN2vnWFVahlRPo3tYq0cp/0obtUu', 3, 1),
(22, 'awgfaf', '', '232323232', '', 'quien@gmail.com', 'afwafa', '$2y$10$w9hFf.CC67TFh.gjeXLohOqLTMSO/z2Kyqi8qoptIJqCA79m7eeVy', 3, 0),
(23, '', '', '293837773', '', 'roro@gmail.com', 'awdad', '$2y$10$krON28HSmpCl0MJpT.nBpuhnFkG8MdsbwWYE.J/256QfIapNshmEy', 3, 0),
(24, '', '', '292839484', '', 'fawfaf@gmail.com', 'fafawfawf', '$2y$10$LHk5fgjE0hVShDXmjyBVF.GAxgd/9cmjN70.tXu0MUblN/DZT5Diy', 3, 0),
(25, '', '', '999111232', '', 'yooo@gmail.com', 'wsegbwbwbs', '$2y$10$NhZakzIxkug/kfmuOfynFeB9ECc.MVmWHcIfi39YNUfImat9Pj3ou', 3, 0),
(26, 'awfawfahgag', '', '999882232', '', 'mlfa@gmail.com', 'awfawf', '$2y$10$RXNHTNealTvjq./4xWJMuOkJxtQAnqEZnPOpf4AJ0jGebVBMddqk.', 3, 0),
(27, 'carlitos', 'Orellana Obando', '402590264', '', 'charlorellana@gmail.com', 'charlitos', '$2y$10$kPvGUY7HoxkO1qWoxcd3eOs5pzI4cnQCMtJfWNo9rRPi/aYzTy2Le', 3, 1),
(28, 'Dilan', 'Gutierrez..', '878679876', '98647678', 'afaa@a.ksbhfgyj', 'dddd', '$2y$10$VMrrIUw2si6F9iAkkI4J1OctlDfD84PZcvDNRhhl6zKzOvY8DdB/K', 2, 1),
(29, 'Dilan', 'Gutierrez', '584933293', '43234534', 'afaa@aksbe.hf', 'ddd', '$2y$10$G.QNQ0iBs2B13iP477H67uYU26SBZtNPGJOqnQJg5OfBtQRrouFRy', 2, 1),
(30, 'JAMAL', 'Gutierrez', '478398764', '43343228', 'degutierrezh02@gmail.com3', 'JAMAL', '$2y$10$xYxDshrXQAWh/ruxNe3CuuDB9DcVPYnMUQgGMLAbPd9Z/GAPiq3Gu', 3, 1),
(31, 'Dilan', 'Gutierrez', '444433211', '61663697', 'degutierrezh02@gmail.com32', '22JAMAL', '$2y$10$BYMfJl1WUGEjcGFLyXA3nOr38ETjVkHGUNHQGN/8OxeUYNPiMNCrm', 3, 1),
(32, 'JAMAL', 'Gutierrez', '894736273', '89987657', 'degutierrezh02@gmail.comeee', 'hjvgj', '$2y$10$iFnJc.k37Py30.oaNyAgheMnQ.LGB4168xP77a8hKfyUOPDnAtSWy', 3, 1),
(33, 'Eduardo', 'Gutierrez', '123234323', '88888899', 'afaasa@aksbhf.sfa2124', 'edu', '$2y$10$Pd/DWEMtgXOqcCaq62Ocpu9HNb6W3FxPzaRqEOZwwVj3K5fyiPHWO', 2, 1),
(34, 'GLEEEEEND', 'Gutierrez', '435678907', '38928738', 'degutierrezh02@gmail.com32222', 'GLENDA', '$2y$10$leS15Y4x0RqLw1Komzr5W.cT2qShVUr7ZvxXNcEwtGNDojJNhR.Oq', 3, 0),
(35, 'asfa', 'SASAS', '376483762', '22112244', 'degutierrezh02@gmail.com1212', 'AD1', '$2y$10$.oWt34aCWD7giKclfWapTeSLpI9AsLzWtx5TVWTRdYmZhgeYwzyd.', 3, 1),
(36, 'kajjad', 'saad', '487637890', '33338111', 'degutierrezh02@gmail.com1111', 'asdas1', '$2y$10$/7Cv/588S/QU/TrahW8gLuRDsy7kOedrXZIxb5Edqqd2L0ywVEEda', 3, 1),
(37, 'Raw', '', 'D77773', '28372321', 'raw@gmail.com', 'Raw', '$2y$10$4UTEazt5bHHGrq9NxAwxxOudyP.Nwa89mCR5uS93an.0Rx1L/HSES', 3, 1),
(38, 'jamel', '', '298392837', '92839238', 'jamal@gmail.com', 'jamel', '$2y$10$fWTJDQA5ACUPZiK2UXyT8OiaUppI1PfJDWCEjr8DFJ95afX2asMu2', 2, 1),
(39, 'Alvaro', 'Diaz.', '289387453', '88887726', 'alvarito@gmail.com', 'Alvarito', '$2y$10$n3aQ7BdTOFiqqJYyQsZHV.LSdHIUxXrUzrPWTTHUaSy2RS..A7KWe', 3, 1),
(40, 'Jose', 'Jose.', 'D872321', '82828942', 'Jose@gmail.com', 'Jose', '$2y$10$T2dn/ss.58ztoWAoEetg7e2JprHUjf0MVRKkhXK7TSwS2zGmVmVIm', 2, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;