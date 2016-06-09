SET GLOBAL innodb_file_format = BARRACUDA;
SET GLOBAL innodb_large_prefix = ON;

CREATE DATABASE IF NOT EXISTS ihunters;

USE ihunters;

CREATE TABLE IF NOT EXISTS persons (
	id INT NOT NULL AUTO_INCREMENT, 
	name TEXT NOT NULL,
	name_hash CHAR(32) UNIQUE, 
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS sites (
	id INT NOT NULL AUTO_INCREMENT, 
	name NVARCHAR(256) NOT NULL UNIQUE, 
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS keywords (
	id INT NOT NULL AUTO_INCREMENT, 
	name TEXT NOT NULL,
	name_hash CHAR(32) UNIQUE, 
	person_id INT NOT NULL, 
	PRIMARY KEY (id), 
	FOREIGN KEY (person_id) 
		REFERENCES persons(id) 
		ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS pages (
	id INT NOT NULL AUTO_INCREMENT, 
	url TEXT NOT NULL, 
	site_id INT NOT NULL, 
	found_date_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
	last_scan_date TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
	url_hash CHAR(32) UNIQUE, 
	PRIMARY KEY (id), 
	FOREIGN KEY (site_id) 
		REFERENCES sites(id) 
		ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS person_page_rank (
	person_id INT NOT NULL, 
	page_id INT NOT NULL, 
	rank INT NOT NULL, 
	FOREIGN KEY (person_id) 
		REFERENCES persons(id) 
		ON DELETE CASCADE, 
	FOREIGN KEY (page_id) 
		REFERENCES pages(id) 
		ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

ALTER TABLE person_page_rank ADD scan_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE person_page_rank CHANGE COLUMN scan_date date_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

CREATE TABLE `pages_content` (
  `page_id` int(11) NOT NULL,
  `page_body_text` longtext,
  UNIQUE KEY `page_id_UNIQUE` (`page_id`),
  CONSTRAINT `id` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE keywords ADD COLUMN rescan_needed TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE pages ADD COLUMN rescan_needed TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE persons ADD COLUMN rescan_needed TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE sites ADD COLUMN rescan_needed TINYINT(1) NOT NULL DEFAULT 1;

DELIMITER $$

CREATE TRIGGER Persons_BeforeInsert
BEFORE INSERT ON persons
FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END$$

CREATE TRIGGER Persons_BeforeUpdate
BEFORE UPDATE ON persons
FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END$$

CREATE TRIGGER Keywords_BeforeInsert
BEFORE INSERT ON keywords
FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END$$

CREATE TRIGGER Keywords_BeforeUpdate
BEFORE UPDATE ON keywords
FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END$$

CREATE TRIGGER Pages_BeforeInsert
BEFORE INSERT ON pages
FOR EACH ROW
BEGIN
	SET NEW.url_hash = MD5(NEW.url);
END$$

CREATE TRIGGER Pages_BeforeUpdate
BEFORE UPDATE ON pages
FOR EACH ROW
BEGIN
	SET NEW.url_hash = MD5(NEW.url);
END$$

CREATE TRIGGER PersonPageRank_AfterInsert
AFTER INSERT ON person_page_rank
FOR EACH ROW
BEGIN
	UPDATE pages SET last_scan_date=NEW.date_modified
	WHERE pages.id = NEW.page_id;
END$$




DROP TRIGGER IF EXISTS Persons_BeforeUpdate;
DROP TRIGGER IF EXISTS Persons_BeforeInsert;
DROP TRIGGER IF EXISTS Keywords_BeforeInsert;
DROP TRIGGER IF EXISTS Keywords_BeforeUpdate;
DROP TRIGGER IF EXISTS Pages_BeforeInsert;
DROP TRIGGER IF EXISTS Pages_BeforeUpdate;
DROP TRIGGER IF EXISTS PersonPageRank_AfterInsert;
DROP TRIGGER IF EXISTS Persons_AfterUpdate;
DROP TRIGGER IF EXISTS Persons_AfterInsert;
DROP TRIGGER IF EXISTS Keywords_AfterInsert;
DROP TRIGGER IF EXISTS Keywords_AfterUpdate;
DROP TRIGGER IF EXISTS Pages_AfterUpdate;
DROP TRIGGER IF EXISTS Pages_AfterInsert;


CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Keywords_BeforeInsert` BEFORE INSERT ON ihunters.keywords FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END$$
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Keywords_BeforeUpdate` BEFORE UPDATE ON ihunters.keywords FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
        IF NEW.name != OLD.name THEN
		SET NEW.rescan_needed = 1;
    END IF;
END$$
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Pages_BeforeInsert` BEFORE INSERT ON ihunters.pages FOR EACH ROW
BEGIN
	SET NEW.url_hash = MD5(NEW.url);
END$$
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Pages_BeforeUpdate` BEFORE UPDATE ON ihunters.pages FOR EACH ROW
BEGIN
	SET NEW.url_hash = MD5(NEW.url);
END$$
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Persons_BeforeInsert` BEFORE INSERT ON ihunters.persons FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END$$
CREATE DEFINER=`root`@`%` TRIGGER `ihunters`.`Persons_BeforeUpdate` BEFORE UPDATE ON ihunters.persons FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
    IF NEW.name != OLD.name THEN
		SET NEW.rescan_needed = 1;
    END IF;
END$$
DELIMITER ;