-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 17-09-2024 a las 17:30:53
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

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 08-10-2024 a las 00:04:26
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

INSERT INTO `tbactivity` (`tbactivityid`, `tbactivityname`, `tbactivityservicecompanyid`, `tbactivityatributearray`, `tbactivitydataarray`, `tbactivityurl`, `tbactivitystatus`, `tbactivitydate`, `tbactivitylatitude`, `tbactivitylongitude`)
VALUES
(1, 'Senderismo en el Bosque', 2, 'Duración,Distancia', '2 horas,5 km', 'Cat03.jpg,pexels1.jpg', 1, '2024-10-01 09:00:00', 10.12345678, -84.12345678),
(2, 'Fotografía de Vida Silvestre', 3, 'Duración,Equipo Recomendado', '4 horas,Cámara con zoom', 'daisy.jpg,pexels2.jpg', 1, '2024-10-03 07:30:00', 9.87654321, -83.98765432),
(3, 'Kayak en el Río', 5, 'Duración,Nivel de Dificultad', '3 horas,Moderado', 'dog.jpg,pexels3.jpg', 1, '2024-10-05 11:00:00', 10.23456789, -84.23456789),
(4, 'Paseo en Caballo', 4, 'Duración,Tamaño del Grupo', '2 horas,10 personas', 'IMG_1312.PNG,uwu.jpeg', 1, '2024-10-07 08:00:00', 9.12345678, -83.12345678),
(5, 'Excursión Nocturna', 1, 'Duración,Equipo Necesario', '5 horas,Linterna y repelente', 'miAmigueCarlos.jpg,gerald.jpg', 1, '2024-10-09 18:00:00', 10.56789012, -84.56789012);

--
-- Volcado de datos para la tabla `tbactivity`
--



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


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
(1, 2, 'Calle Falsa 123, Ciudad', '', 0),
(2, 4, '', '', 0),
(3, 5, '', '', 1),
(4, 6, '', '', 1),
(5, 7, 'san luis', '', 1);

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
(8, 2, 'CR12345678909876543298', '', 1, 1);

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
  `tbservicetatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbservicecompany`
--

INSERT INTO `tbservicecompany` (`tbservicecompanyid`, `tbtouristcompanyid`, `tbserviceid`, `tbservicecompanyURL`, `tbservicetatus`) VALUES
(1, 9, '3,4', '10188600.jpg', 1),
(2, 8, '4', 'Cat03.jpg', 1),
(3, 8, '1', '', 1),
(4, 8, '1,3', '', 1),
(5, 9, '6', 'dog.jpg,Cat03.jpg', 1),
(6, 9, '4', '', 1),
(7, 9, '7,1,4', '', 1),
(8, 9, '', 'animals.jpeg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtouristcompany`
--

CREATE TABLE `tbbooking` (
  `tbbookingid` int(11) NOT NULL,
  `tbactivityid` int(11) NOT NULL,
  `tbuserid` int(11) NOT NULL,
  `tbbookingnumberpersons` int(11) NOT NULL,
  `tbbookingstatus` tinyint(1) NOT NULL,
  `tbbookingdate` tinyint(1) NOT NULL,
  `tbbookingatitude` decimal(12,8) NOT NULL,
  `tbbookinglongitude` decimal(12,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



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
(8, 'PRUEBAELIMINAR3', 'depende', 2, 1, 'dog.jpg', 0),
(9, 'EMPRESA2', 'Empresachida2', 2, 2, 'dog.jpg', 1),
(10, 'EMPRESA1', '', 3, 1, 'IMG_1312.PNG', 1),
(11, '', '', 1, 0, '', 0),
(12, 'PRUEBAELIMINAR3', '', 1, 1, '', 1),
(13, 'PRUEBAELIMINAR3', '', 2, 0, '', 0);

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
-- Estructura de tabla para la tabla `tbcustomizedtouristcompanytype`
--

CREATE TABLE `tbcustomizedtouristcompanytype` (
  `tbcustomizedtouristcompanytypeid` int(11) NOT NULL,
  `tbownerid` int(11) NOT NULL,
  `tbcustomizedtouristcompanytypename` varchar(200) NOT NULL,
  `tbcustomizedtouristcompanytypestatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;
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
(2, 'propietario', 'propietario', '402590268', '89571689', 'propietario@hotmail.com', 'propietario', '21be11e996807bcef07a0f66fc1ec17402e138fdda66913814b7c02b32fb24ad', 3, 0),
(3, 'turista', 'turista', '402590269', '86942578', 'turista@gmail.com', 'turista', 'd5672f037da8c5c5c277dca73331af8116f8e6fc5cdc0b4beb9268dd410c78c1', 2, 1),
(4, 'Daisy', 'Cedenio', '7638223', '87875628', 'prueba@gmail.com', 'Daisy', 'Daisy', 3, 0),
(5, 'Carlos', 'orellana', '789293819832', '87867234', 'carlos@gmail.com', '', '', 3, 1),
(6, 'Daisy', 'Cedenoio', '72837823782', '87323223', 'daisy@gmail.com', 'Daisy', 'Daisy', 3, 1),
(7, 'Glend', 'Rojas', '703040371', '98765432', 'glend@gmail.com', '', '', 3, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
