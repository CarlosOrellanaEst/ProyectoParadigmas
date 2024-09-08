  -- phpMyAdmin SQL Dump
  -- version 5.2.1
  -- https://www.phpmyadmin.net/
  --
  -- Servidor: localhost
  -- Tiempo de generación: 13-08-2024 a las 19:23:39
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
    `tbrollid` int(11) NOT NULL,
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
    `tbrollid` int(11) NOT NULL,
    `tbuserstatus` tinyint(1) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
  -- --------------------------------------------------------	
  -- Estructura de tabla para la tabla `tbtouristcompanytype`
  --

  CREATE TABLE `tbtouristcompanytype` (
    `tbtouristcompanytypeid` int(11) NOT NULL,
    `tbtouristcompanytypename` varchar(100) NOT NULL,
    `tbtouristcompanytypedescription` varchar(250) NOT NULL,
    `tbtouristcompanytypeisactive` tinyint(1) NOT NULL DEFAULT 1
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- --------------------------------------------------------
  -- Estructura de tabla para la tabla `tbtouristcompany`

  CREATE TABLE `tbtouristcompany` (
    `tbtouristcompanyid` int(11) NOT NULL,
    `tbtouristcompanylegalname` varchar(255) NOT NULL,
    `tbtouristcompanymagicname` varchar(255) NOT NULL,
    `tbtouristcompanyowner` int(11) NOT NULL,
    `tbtouristcompanycompanyType` int(11) NOT NULL,
    `tbtouristcompanyurl` TEXT  NOT NULL,
    `tbtouristcompanystatus` tinyint(1) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- ----------------------------------------------------------
  -- Estructura de tabla para la tabla `tbactivity`
  CREATE TABLE `tbactivity` (
    `tbactivityid` int(11) NOT NULL,
    `tbactivityname` varchar(255) NOT NULL,
    `tbactivityatributearray` varchar(255) NOT NULL,
    `tbactivitydataarray` varchar(255) NOT NULL,
    `tbactivitystatus` tinyint(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- --------------------------------------------------------
  --
  -- Volcado de datos para la tabla `tbbankaccount`
  --

  -- Inserción de datos en la tabla `tbbankaccount`
  INSERT INTO `tbbankaccount` (`tbbankaccountid`, `tbbankaccountownerid`, `tbbankaccountnumber`, `tbbankaccountbankname`, `tbbankaccountstatus`, `tbbankaccountisactive`) VALUES
  (1, 1, '1234567890123456', 'Bank of America', 1, 1),
  (2, 2, '2345678901234567', 'Chase Bank', 1, 1),
  (3, 3, '3456789012345678', 'Wells Fargo', 1, 1),
  (4, 4, '4567890123456789', 'Citibank', 1, 1),
  (5, 5, '5678901234567890', 'Goldman Sachs', 1, 1);
  COMMIT;

  -- --------------------------------------------------------
  --
  -- Volcado de datos para la tabla `tbroll`
  --
  INSERT INTO `tbroll` (`tbrollid`, `tbrollname`, `tbrolldescription`, `tbrollstatus`) VALUES
  (1, 'Administrador', 'Acceso completo a todas las funciones y configuraciones del sistema.', 1),
  (2, 'Turista', 'Potencial cliente de los negocios registrados en el sistema', 1),
  (3, 'Propietario', '', 1);
  COMMIT;

  --
  -- Volcado de datos para la tabla `tbuser`
  --

  INSERT INTO `tbuser` (`tbuserid`, `tbusername`, `tbuserlastname`, `tbuserpassword`, `tbuserphone`, `tbrollid`, `tbuserstatus`) VALUES

  (1, 'admin', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '1234567890', 1, 1),
  (2, 'propietario', 'propietario', '21be11e996807bcef07a0f66fc1ec17402e138fdda66913814b7c02b32fb24ad', '9876543210', 3, 1),
  (3, 'turista', 'turista', 'd5672f037da8c5c5c277dca73331af8116f8e6fc5cdc0b4beb9268dd410c78c1', '5432167890', 2, 1);
  COMMIT;

  --
  -- Volcado de datos para la tabla `tbtouristcompanytype`
  --

  INSERT INTO `tbtouristcompanytype` (`tbtouristcompanytypeid`, `tbtouristcompanytypename`, `tbtouristcompanytypedescription`, `tbtouristcompanytypeisactive`) VALUES
  (1, 'Agencia de Viajes', 'Agencia especializada en turismo nacional e internacional.', 1),
  (2, 'Guía Turístico', 'Servicios de guías turísticos especializados en diferentes regiones.', 1),
  (3, 'Transporte Turístico', 'Servicios de transporte para turistas, incluyendo autobuses y vans.', 1),
  (4, 'Alojamiento', 'Proveedores de alojamiento para turistas, como hoteles y hostales.', 1),
  (5, 'Actividades Recreativas', 'Organización de actividades recreativas y eventos para turistas.', 1);
  COMMIT;



  --
  -- Volcado de datos para la tabla `tbowner`
  --

  INSERT INTO `tbowner` (`tbownerid`, `tbownername`, `tbownersurnames`, `tbownerlegalidentification`, `tbownerphone`, `tbowneremail`, `tbownerdirection`, `tbrollid`, `tbownerstatus`) VALUES
  (1, 'Carlos', 'González Pérez', '12345678', '555-1234', 'carlos.gonzalez@example.com', 'Calle Falsa 123, Ciudad', 3, 1),
  (2, 'Ana', 'Martínez López', '87654321', '555-5678', 'ana.martinez@example.com', 'Avenida Siempre Viva 742, Ciudad', 3, 1),
  (3, 'Luis', 'Ramírez Fernández', '11223344', '555-9101', 'luis.ramirez@example.com', 'Boulevard Libertad 90, Ciudad', 3, 1),
  (4, 'Marta', 'Cruz García', '44332211', '555-1122', 'marta.cruz@example.com', 'Plaza Mayor 5, Ciudad', 3, 1),
  (5, 'José', 'Sánchez Romero', '55667788', '555-3344', 'jose.sanchez@example.com', 'Calle de los Naranjos 20, Ciudad', 3, 1);
  COMMIT;

  --
  -- Volcado de datos para la tabla `tbtouristcompany`
  --
  INSERT INTO `tbtouristcompany` (`tbtouristcompanyid`, `tbtouristcompanylegalname`, `tbtouristcompanymagicname`, `tbtouristcompanyowner`, `tbtouristcompanycompanyType`, `tbtouristcompanystatus`) VALUES
  (1, 'Caminatas Rida', 'Rida S.A', 2, 2, 1),
  (2, 'Sarapiquiar', 'Sarapiquiar S.A', 5, 5, 1),
  (3, 'Puravida Rafting', 'Puravida Rafting S.N.C', 4, 3, 0);
  COMMIT;

  CREATE TABLE `tbphoto` (
    `tbphotoid` int(11) NOT NULL,
    `tbphotourl` TEXT  NOT NULL,
    `tbphotoindex` TEXT  NOT NULL,
    `tbphotostatus` tinyint(1) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
  /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
