# SQL Manager 2010 for MySQL 4.5.0.9
# ---------------------------------------
# Host     : 192.168.2.13
# Port     : 3306
# Database : Medic


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS `Medic`;

CREATE DATABASE `Medic`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_bin';

USE `Medic`;

#
# Structure for the `Logs` table : 
#

DROP TABLE IF EXISTS `Logs`;

CREATE TABLE `Logs` (
  `id` varchar(36) NOT NULL,
  `SessionId` varchar(36) DEFAULT NULL,
  `DateTime` datetime DEFAULT NULL,
  `Head` varchar(100) DEFAULT NULL,
  `Message` text,
  PRIMARY KEY (`id`),
  KEY `SessionId` (`SessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `Manufacturer` table : 
#

DROP TABLE IF EXISTS `Manufacturer`;

CREATE TABLE `Manufacturer` (
  `id` varchar(36) NOT NULL,
  `Name` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `Prices` table : 
#

DROP TABLE IF EXISTS `Prices`;

CREATE TABLE `Prices` (
  `id` varchar(36) DEFAULT NULL,
  `FileName` varchar(500) DEFAULT NULL,
  `DateTime` datetime DEFAULT NULL,
  `UploadUserId` varchar(36) DEFAULT NULL,
  `ProcessStatus` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `Products` table : 
#

DROP TABLE IF EXISTS `Products`;

CREATE TABLE `Products` (
  `id` varchar(36) DEFAULT NULL,
  `Number` varchar(50) DEFAULT NULL,
  `Name` varchar(1000) DEFAULT NULL,
  `ManufacturerId` varchar(36) DEFAULT NULL,
  `Price1` double(15,8) DEFAULT NULL,
  `Price2` double(15,8) DEFAULT NULL,
  `Count` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `ProductsSearch` table : 
#

DROP TABLE IF EXISTS `ProductsSearch`;

CREATE TABLE `ProductsSearch` (
  `ProductId` varchar(36) NOT NULL,
  `SearchString` text,
  PRIMARY KEY (`ProductId`),
  FULLTEXT KEY `SearchString_idx` (`SearchString`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `Session` table : 
#

DROP TABLE IF EXISTS `Session`;

CREATE TABLE `Session` (
  `id` varchar(36) NOT NULL,
  `UserId` varchar(36) DEFAULT NULL,
  `CreateDate` datetime DEFAULT NULL,
  `ExpireDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `UserId` (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `Users` table : 
#

DROP TABLE IF EXISTS `Users`;

CREATE TABLE `Users` (
  `id` varchar(36) COLLATE utf8_bin NOT NULL,
  `Login` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `Password` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `Hash` tinyint(1) DEFAULT NULL,
  `FirstName` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `LastName` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `Permission` int(1) DEFAULT NULL,
  UNIQUE KEY `idxLogin` (`Login`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users list';



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;