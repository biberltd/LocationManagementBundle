/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50622
 Source Host           : localhost
 Source Database       : bbr_digiturk

 Target Server Type    : MySQL
 Target Server Version : 50622
 File Encoding         : utf-8

 Date: 07/09/2015 18:46:53 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `city`
-- ----------------------------
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `country` int(10) unsigned NOT NULL COMMENT 'Country where city is lcoated.',
  `state` int(10) unsigned DEFAULT NULL COMMENT 'State where city is located if there is any.',
  `code` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'City code.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_u_city_id` (`id`),
  KEY `idx_f_city_state_idx` (`state`),
  KEY `idx_f_city_country_idx` (`country`),
  CONSTRAINT `city_ibfk_1` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `city_ibfk_2` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1190 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `city_localization`
-- ----------------------------
DROP TABLE IF EXISTS `city_localization`;
CREATE TABLE `city_localization` (
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `city` int(10) unsigned NOT NULL COMMENT 'Localized city.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized name of city.',
  `url_key` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized url key of city.',
  PRIMARY KEY (`language`,`city`),
  UNIQUE KEY `idx_u_city_localization` (`language`,`city`),
  KEY `idx_f_city_localization_language` (`language`),
  KEY `idx_f_city_localization_cit` (`city`),
  KEY `idx_u_city_localization_name` (`name`,`language`,`city`),
  KEY `idx_u_city_localization_url_key` (`url_key`,`language`,`city`),
  CONSTRAINT `city_localization_ibfk_1` FOREIGN KEY (`city`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `city_localization_ibfk_2` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `country`
-- ----------------------------
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `code_iso` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Iso code of countyr.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_u_country_id` (`id`),
  UNIQUE KEY `idx_u_country_code_iso` (`code_iso`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `country_localization`
-- ----------------------------
DROP TABLE IF EXISTS `country_localization`;
CREATE TABLE `country_localization` (
  `country` int(10) unsigned NOT NULL COMMENT 'Localized country.',
  `language` int(10) unsigned NOT NULL COMMENT 'Localization language.',
  `name` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized name of country.',
  `url_key` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized url key of country.',
  PRIMARY KEY (`country`,`language`),
  UNIQUE KEY `idx_u_country_localization` (`country`,`language`),
  UNIQUE KEY `idx_u_country_localization_url_key` (`country`,`language`,`url_key`),
  KEY `idx_f_country_localization_language_idx` (`language`),
  KEY `idx_f_country_localization_country_idx` (`country`),
  KEY `idx_u_country_localization_name` (`country`,`language`,`name`),
  CONSTRAINT `country_localization_ibfk_1` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `country_localization_ibfk_2` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `office`
-- ----------------------------
DROP TABLE IF EXISTS `office`;
CREATE TABLE `office` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Name of addres, i.e. business',
  `url_key` varchar(155) COLLATE utf8_turkish_ci NOT NULL COMMENT 'URL key of office.',
  `address` text COLLATE utf8_turkish_ci COMMENT 'Street address of office.',
  `city` int(10) unsigned NOT NULL COMMENT 'City where office is located.',
  `state` int(10) unsigned DEFAULT NULL COMMENT 'State where office is located.',
  `country` int(10) unsigned NOT NULL COMMENT 'Country where office is located.',
  `lat` decimal(9,6) DEFAULT NULL COMMENT 'Lattitude.',
  `lon` decimal(9,6) DEFAULT NULL COMMENT 'Longtitude.',
  `phone` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL,
  `fax` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
  `site` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_u_office_id` (`id`),
  KEY `idx_f_office_country_idx` (`country`),
  KEY `idx_f_office_city_idx` (`city`),
  KEY `idx_f_office_state_idx` (`state`),
  KEY `idx_f_office_site` (`site`),
  KEY `idx_u_office_url_key` (`url_key`,`site`),
  CONSTRAINT `office_ibfk_1` FOREIGN KEY (`city`) REFERENCES `city` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `office_ibfk_2` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `office_ibfk_3` FOREIGN KEY (`site`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `office_ibfk_4` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `state`
-- ----------------------------
DROP TABLE IF EXISTS `state`;
CREATE TABLE `state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `country` int(10) unsigned NOT NULL COMMENT 'Country that state is located in.',
  `code_iso` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Iso code of state.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_u_state_id` (`id`),
  UNIQUE KEY `idx_u_state_code_iso` (`code_iso`),
  KEY `idx_f_state_country_idx` (`country`),
  CONSTRAINT `state_ibfk_1` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `state_localization`
-- ----------------------------
DROP TABLE IF EXISTS `state_localization`;
CREATE TABLE `state_localization` (
  `state` int(10) unsigned NOT NULL COMMENT 'Localized state.',
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `name` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized name of state.',
  `url_key` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized url key of state.',
  PRIMARY KEY (`state`,`language`),
  UNIQUE KEY `idx_u_state_localization` (`state`,`language`),
  UNIQUE KEY `idx_u_state_localization_url_key` (`state`,`language`,`url_key`),
  UNIQUE KEY `idx_u_state_localization_name` (`state`,`language`,`name`),
  KEY `idx_f_state_localization_language_idx` (`language`),
  KEY `idx_f_state_localization_state_idx` (`state`),
  CONSTRAINT `state_localization_ibfk_1` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `state_localization_ibfk_2` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

SET FOREIGN_KEY_CHECKS = 1;
