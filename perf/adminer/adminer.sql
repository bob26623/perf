-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `perf` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `perf`;

CREATE TABLE `customer` (
  `customer` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `account` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `history` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `serial` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `year` varchar(4) NOT NULL,
  `type` varchar(3) NOT NULL,
  `Jan` int(11) DEFAULT NULL,
  `Feb` int(11) DEFAULT NULL,
  `Mar` int(11) DEFAULT NULL,
  `Apr` int(11) DEFAULT NULL,
  `May` int(11) DEFAULT NULL,
  `Jun` int(11) DEFAULT NULL,
  `Jul` int(11) DEFAULT NULL,
  `Aug` int(11) DEFAULT NULL,
  `Sep` int(11) DEFAULT NULL,
  `Oct` int(11) DEFAULT NULL,
  `Nov` int(11) DEFAULT NULL,
  `Dec` int(11) DEFAULT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;


CREATE TABLE `model` (
  `modelid` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`modelid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `serverdetail` (
  `gid` int(11) NOT NULL,
  `server` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `serial` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cpu` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `memory` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `os` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `application` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `software` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `servergroup` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(30) NOT NULL,
  `gname` varchar(30) NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;


CREATE TABLE `usertb` (
  `userid` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `passwd` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `priv` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `usertb` (`userid`, `fname`, `lname`, `passwd`, `priv`) VALUES
('root',	'super user',	'LAD',	'cecebe3ea1817a2840d24e58c13afb09cdf52c5c',	'staff');

-- 2020-05-29 10:03:42
