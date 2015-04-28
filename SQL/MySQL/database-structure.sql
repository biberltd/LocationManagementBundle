/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : bod_core

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2015-04-27 15:51:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for city
-- ----------------------------
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `country` int(10) unsigned NOT NULL COMMENT 'Country where city is lcoated.',
  `state` int(10) unsigned DEFAULT NULL COMMENT 'State where city is located if there is any.',
  `code` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'City code.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_u_city_id` (`id`) USING BTREE,
  UNIQUE KEY `idx_u_city_code` (`country`,`state`,`code`) USING BTREE,
  KEY `idx_f_city_state_idx` (`state`) USING BTREE,
  KEY `idx_f_city_country_idx` (`country`) USING BTREE,
  CONSTRAINT `idx_f_city_country` FOREIGN KEY (`country`) REFERENCES `country` (`id`),
  CONSTRAINT `idx_f_city_state` FOREIGN KEY (`state`) REFERENCES `state` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for city_localization
-- ----------------------------
DROP TABLE IF EXISTS `city_localization`;
CREATE TABLE `city_localization` (
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `city` int(10) unsigned NOT NULL COMMENT 'Localized city.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized name of city.',
  `url_key` varchar(155) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized url key of city.',
  PRIMARY KEY (`language`,`city`),
  UNIQUE KEY `idx_u_city_localization` (`language`,`city`) USING BTREE,
  UNIQUE KEY `idx_u_city_localization_name` (`name`) USING BTREE,
  UNIQUE KEY `idx_u_city_localization_url_key` (`url_key`) USING BTREE,
  KEY `idx_f_city_localization_language` (`language`) USING BTREE,
  KEY `idx_f_city_localization_cit` (`city`) USING BTREE,
  CONSTRAINT `idx_f_city_localization_city` FOREIGN KEY (`city`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_f_city_localization_language` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for country
-- ----------------------------
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `code_iso` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Iso code of countyr.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_u_country_id` (`id`) USING BTREE,
  UNIQUE KEY `idx_u_country_code_iso` (`code_iso`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for country_localization
-- ----------------------------
DROP TABLE IF EXISTS `country_localization`;
CREATE TABLE `country_localization` (
  `country` int(10) unsigned NOT NULL COMMENT 'Localized country.',
  `language` int(10) unsigned NOT NULL COMMENT 'Localization language.',
  `name` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized name of country.',
  `url_key` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized url key of country.',
  PRIMARY KEY (`country`,`language`),
  UNIQUE KEY `idx_u_country_localization` (`country`,`language`) USING BTREE,
  UNIQUE KEY `idx_u_country_localization_url_key` (`country`,`language`,`url_key`) USING BTREE,
  KEY `idx_f_country_localization_language_idx` (`language`) USING BTREE,
  KEY `idx_f_country_localization_country_idx` (`country`) USING BTREE,
  KEY `idx_u_country_localization_name` (`country`,`language`,`name`) USING BTREE,
  CONSTRAINT `idx_f_country_localization_country` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_f_country_localization_language` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for office
-- ----------------------------
DROP TABLE IF EXISTS `office`;
CREATE TABLE `office` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Name of addres, i.e. business',
  `url_key` varchar(155) COLLATE utf8_turkish_ci NOT NULL COMMENT 'URL key of office.',
  `address` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Street address of office.',
  `city` int(10) unsigned NOT NULL COMMENT 'City where office is located.',
  `state` int(10) unsigned DEFAULT NULL COMMENT 'State where office is located.',
  `country` int(10) unsigned NOT NULL COMMENT 'Country where office is located.',
  `lat` decimal(9,6) DEFAULT NULL COMMENT 'latitude.',
  `lon` decimal(9,0) DEFAULT NULL COMMENT 'longtitude.',
  `phone` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL,
  `fax` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
  `site` int(10) unsigned NOT NULL,
  `type` char(1) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'h:head quarters, o: office, b:branch, d: distributor, l:dealer',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_u_office_id` (`id`) USING BTREE,
  KEY `idx_f_office_country_idx` (`country`) USING BTREE,
  KEY `idx_f_office_city_idx` (`city`) USING BTREE,
  KEY `idx_f_office_state_idx` (`state`) USING BTREE,
  KEY `idx_f_office_site` (`site`) USING BTREE,
  KEY `idx_u_office_url_key` (`url_key`,`site`) USING BTREE,
  CONSTRAINT `idx_f_office_city` FOREIGN KEY (`city`) REFERENCES `city` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `idx_f_office_country` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `idx_f_office_site` FOREIGN KEY (`site`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_f_office_state` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for state
-- ----------------------------
DROP TABLE IF EXISTS `state`;
CREATE TABLE `state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `country` int(10) unsigned NOT NULL COMMENT 'Country that state is located in.',
  `code_iso` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Iso code of state.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_u_state_id` (`id`) USING BTREE,
  UNIQUE KEY `idx_u_state_code_iso` (`code_iso`) USING BTREE,
  KEY `idx_f_state_country_idx` (`country`) USING BTREE,
  CONSTRAINT `idx_f_state_country` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for state_localization
-- ----------------------------
DROP TABLE IF EXISTS `state_localization`;
CREATE TABLE `state_localization` (
  `state` int(10) unsigned NOT NULL COMMENT 'Localized state.',
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `name` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized name of state.',
  `url_key` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized url key of state.',
  PRIMARY KEY (`state`,`language`),
  UNIQUE KEY `idx_u_state_localization` (`state`,`language`) USING BTREE,
  UNIQUE KEY `idx_u_state_localization_url_key` (`state`,`language`,`url_key`) USING BTREE,
  UNIQUE KEY `idx_u_state_localization_name` (`state`,`language`,`name`) USING BTREE,
  KEY `idx_f_state_localization_language_idx` (`language`) USING BTREE,
  KEY `idx_f_state_localization_state_idx` (`state`) USING BTREE,
  CONSTRAINT `idx_f_state_localization_language` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_f_state_localization_state` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;
