# This two next options is default for mysql 5.7
SET GLOBAL innodb_file_format = BARRACUDA;
SET GLOBAL innodb_large_prefix = ON;

CREATE DATABASE IF NOT EXISTS ihunters;

USE ihunters;

CREATE TABLE IF NOT EXISTS `persons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `name_hash` char(32) DEFAULT NULL,
  `rescan_needed` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_hash` (`name_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `rescan_needed` tinyint(1) NOT NULL DEFAULT '1',
  `rate_limit` tinyint(2) NOT NULL DEFAULT '5',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `name_hash` char(32) DEFAULT NULL,
  `person_id` int(11) NOT NULL,
  `rescan_needed` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_hash` (`name_hash`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `keywords_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `persons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `site_id` int(11) NOT NULL,
  `found_date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_scan_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `url_hash` char(32) DEFAULT NULL,
  `rescan_needed` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_hash` (`url_hash`),
  KEY `site_id` (`site_id`),
  CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

CREATE TABLE `person_page_rank` (
  `person_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `person_id` (`person_id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `person_page_rank_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `persons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `person_page_rank_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

CREATE TABLE `pages_content` (
  `page_id` int(11) NOT NULL,
  `page_body_text` longtext,
  UNIQUE KEY `page_id_UNIQUE` (`page_id`),
  CONSTRAINT `id` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Keywords_BeforeInsert` BEFORE INSERT ON ihunters.keywords FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END;
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Keywords_BeforeUpdate` BEFORE UPDATE ON ihunters.keywords FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
        IF NEW.name != OLD.name THEN
		SET NEW.rescan_needed = 1;
    END IF;
END;
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Pages_BeforeInsert` BEFORE INSERT ON ihunters.pages FOR EACH ROW
BEGIN
	SET NEW.url_hash = MD5(NEW.url);
END;
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Pages_BeforeUpdate` BEFORE UPDATE ON ihunters.pages FOR EACH ROW
BEGIN
	SET NEW.url_hash = MD5(NEW.url);
END;
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Persons_BeforeInsert` BEFORE INSERT ON ihunters.persons FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END;
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Persons_BeforeUpdate` BEFORE UPDATE ON ihunters.persons FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
    IF NEW.name != OLD.name THEN
		SET NEW.rescan_needed = 1;
    END IF;
END;