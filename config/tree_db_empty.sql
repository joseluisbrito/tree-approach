
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

# DROP TABLE IF EXISTS `node`;
CREATE TABLE IF NOT EXISTS `node` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


# DROP TABLE IF EXISTS `parent_nodes`;
CREATE TABLE IF NOT EXISTS `parent_nodes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `node_id` int DEFAULT NULL,
  `parentNode_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D10C915F460D9FD7` (`node_id`),
  KEY `IDX_D10C915F3F90820E` (`parentNode_id`),
  CONSTRAINT `FK_D10C915F3F90820E` FOREIGN KEY (`parentNode_id`) REFERENCES `node` (`id`),
  CONSTRAINT `FK_D10C915F460D9FD7` FOREIGN KEY (`node_id`) REFERENCES `node` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

