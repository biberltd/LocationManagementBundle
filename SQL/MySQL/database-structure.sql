/**
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com) (C) 2015
 * @license     GPLv3
 *
 * @date        14.12.2015
 */

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for checkin_logs
-- ----------------------------
DROP TABLE IF EXISTS `checkin_logs`;
CREATE TABLE `checkin_logs` (
  `id` int(10) unsigned NOT NULL COMMENT 'System given id.',
  `date_checkin` datetime NOT NULL COMMENT 'Date when the member has checkedin.',
  `date_checkout` datetime DEFAULT NULL COMMENT 'Date when the member has checkedout.',
  `lat_checkin` decimal(10,0) unsigned DEFAULT NULL COMMENT 'Latitude of checkin.',
  `lat_checkout` decimal(10,0) unsigned DEFAULT NULL COMMENT 'Latitiude of checkout.',
  `lon_checkout` decimal(10,0) unsigned DEFAULT NULL COMMENT 'Longitude of checkout.',
  `lon_checkin` decimal(10,0) unsigned DEFAULT NULL COMMENT 'Longitude of checkin.',
  `office` int(10) unsigned NOT NULL COMMENT 'Checked in office.',
  `member` int(15) unsigned NOT NULL COMMENT 'Checkedin member.',
  `date_added` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  `date_removed` datetime DEFAULT NULL,
  `checkout_type` char(1) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 's:system, u:user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxUCheckinId` (`id`),
  UNIQUE KEY `idxUMemberCheckins` (`date_checkin`,`date_checkout`,`office`,`member`),
  KEY `idxFCheckedInMember` (`member`),
  KEY `idxFCheckedInOffice` (`office`),
  CONSTRAINT `idxFCheckedInMember` FOREIGN KEY (`member`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFCheckedInOffice` FOREIGN KEY (`office`) REFERENCES `office` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

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
  UNIQUE KEY `idxUCityId` (`id`) USING BTREE,
  KEY `idxFStateOfCity` (`state`) USING BTREE,
  KEY `idxFCountryOfCity` (`country`) USING BTREE,
  CONSTRAINT `idxFCountryOfCity` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFStateOfCity` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for city_localization
-- ----------------------------
DROP TABLE IF EXISTS `city_localization`;
CREATE TABLE `city_localization` (
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `city` int(10) unsigned NOT NULL COMMENT 'Localized city.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized name of city.',
  `url_key` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized url key of city.',
  PRIMARY KEY (`language`,`city`),
  UNIQUE KEY `idxUCityLocalization` (`language`,`city`) USING BTREE,
  KEY `idxFCityLocalizationLanguage` (`language`) USING BTREE,
  KEY `idxFLocalizedCity` (`city`) USING BTREE,
  KEY `idxULocalizedCityName` (`name`,`language`,`city`) USING BTREE,
  KEY `idxULocalizedCityUrlKey` (`url_key`,`language`,`city`) USING BTREE,
  CONSTRAINT `idxFCityLocalizationLanguage` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFLocalizedCity` FOREIGN KEY (`city`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for country
-- ----------------------------
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `code_iso` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Iso code of countyr.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxUCountryId` (`id`) USING BTREE,
  UNIQUE KEY `idxUCountryIsoCode` (`code_iso`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

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
  UNIQUE KEY `idxUCountryLocalization` (`country`,`language`) USING BTREE,
  UNIQUE KEY `idxULocalizedUrlKeyOfCountry` (`country`,`language`,`url_key`) USING BTREE,
  KEY `idxFCountryLocalizationLanguage` (`language`) USING BTREE,
  KEY `idxFLocalizedCountry` (`country`) USING BTREE,
  KEY `idxULocalizedNameOfCountry` (`country`,`language`,`name`) USING BTREE,
  CONSTRAINT `idxFCountryLocalizationLanguage` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFLocalizedCountry` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for district
-- ----------------------------
DROP TABLE IF EXISTS `district`;
CREATE TABLE `district` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `city` int(10) unsigned NOT NULL COMMENT 'City where district is located.',
  `zip` varchar(10) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Zip code of district.',
  PRIMARY KEY (`id`),
  KEY `idxFCityOfDistrict` (`city`),
  CONSTRAINT `idxFCityOfDistrict` FOREIGN KEY (`city`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for district_localization
-- ----------------------------
DROP TABLE IF EXISTS `district_localization`;
CREATE TABLE `district_localization` (
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `district` int(10) unsigned NOT NULL COMMENT 'Localized district.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized name of city.',
  `url_key` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized url key of city.',
  PRIMARY KEY (`language`,`district`),
  UNIQUE KEY `idxUDistrictLocalization` (`language`,`district`) USING BTREE,
  KEY `idxFDistrictLocalizationLanguage` (`language`) USING BTREE,
  KEY `idxFLocalizedDistrict` (`district`) USING BTREE,
  KEY `idxULocalizedDistrictName` (`name`,`language`,`district`) USING BTREE,
  KEY `idxULocalizedDistrictUrlKey` (`url_key`,`language`,`district`) USING BTREE,
  CONSTRAINT `idxFDistrictLocalizationLanguage` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFLocalizedDistrict` FOREIGN KEY (`district`) REFERENCES `district` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for neighborhood
-- ----------------------------
DROP TABLE IF EXISTS `neighborhood`;
CREATE TABLE `neighborhood` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `district` int(10) unsigned NOT NULL COMMENT 'District where neighborhood is located.',
  `zip` varchar(10) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Zip code of district.',
  PRIMARY KEY (`id`),
  KEY `idxFDistrictOfNeighborhood` (`district`),
  CONSTRAINT `idxFDistrictOfNeighborhood` FOREIGN KEY (`district`) REFERENCES `district` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for neighborhood_localization
-- ----------------------------
DROP TABLE IF EXISTS `neighborhood_localization`;
CREATE TABLE `neighborhood_localization` (
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `neighborhood` int(10) unsigned NOT NULL COMMENT 'Localized city.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized name of city.',
  `url_key` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized url key of city.',
  PRIMARY KEY (`language`,`neighborhood`),
  UNIQUE KEY `idxUNeighborhoodLocalization` (`language`,`neighborhood`) USING BTREE,
  KEY `idxFNeighborhoodLocalizationLanguage` (`language`) USING BTREE,
  KEY `idxFLocalizedNeighborhood` (`neighborhood`) USING BTREE,
  KEY `idxULocalizedNeighborhoodName` (`name`,`language`,`neighborhood`) USING BTREE,
  KEY `idxULocalizedNeighborhoodUrlKey` (`url_key`,`language`,`neighborhood`) USING BTREE,
  CONSTRAINT `neighborhood_localization_ibfk_1` FOREIGN KEY (`neighborhood`) REFERENCES `neighborhood` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `neighborhood_localization_ibfk_2` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for office
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
  `site` int(10) unsigned NOT NULL COMMENT 'Associated site.',
  `date_added` datetime NOT NULL COMMENT 'Date when the entry is first added.',
  `date_update` datetime NOT NULL COMMENT 'Date when the entry is last updated.',
  `date_removed` datetime DEFAULT NULL COMMENT 'Date when the entry is last removed.',
  `member` int(10) unsigned DEFAULT NULL COMMENT 'Owner of office.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxUOfficeId` (`id`) USING BTREE,
  KEY `idxFOfficeCountry` (`country`) USING BTREE,
  KEY `idxFOfficeCity` (`city`) USING BTREE,
  KEY `idxFOfficeState` (`state`) USING BTREE,
  KEY `idxFOfficeOfSite` (`site`) USING BTREE,
  KEY `idxUOfficeUelKey` (`url_key`,`site`) USING BTREE,
  KEY `idxFOwnerOfOffice` (`member`),
  CONSTRAINT `idxFCityOfOffice` FOREIGN KEY (`city`) REFERENCES `city` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `idxFCountryOfOffice` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `idxFOwnerOfOffice` FOREIGN KEY (`member`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFSiteOfOffice` FOREIGN KEY (`site`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFStateOfOffice` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON UPDATE CASCADE
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
  UNIQUE KEY `idx_u_state_id` (`id`),
  UNIQUE KEY `idx_u_state_code_iso` (`code_iso`),
  KEY `idx_f_state_country_idx` (`country`),
  CONSTRAINT `state_ibfk_1` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

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
  UNIQUE KEY `idx_u_state_localization` (`state`,`language`),
  UNIQUE KEY `idx_u_state_localization_url_key` (`state`,`language`,`url_key`),
  UNIQUE KEY `idx_u_state_localization_name` (`state`,`language`,`name`),
  KEY `idx_f_state_localization_language_idx` (`language`),
  KEY `idx_f_state_localization_state_idx` (`state`),
  CONSTRAINT `state_localization_ibfk_1` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `state_localization_ibfk_2` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;