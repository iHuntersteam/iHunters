SET GLOBAL innodb_file_format = BARRACUDA;
SET GLOBAL innodb_large_prefix = ON;

CREATE DATABASE IF NOT EXISTS ihunters;

USE ihunters;

CREATE TABLE IF NOT EXISTS `persons` (
	`id` INT(11) NOT NULL AUTO_INCREMENT, 
	`name` TEXT NOT NULL,
	`name_hash` CHAR(32) UNIQUE, 
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `sites` (
	`id` INT(11) NOT NULL AUTO_INCREMENT, 
	`name` NVARCHAR(256) NOT NULL UNIQUE, 
	`url` NVARCHAR(256) NOT NULL UNIQUE,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `keywords` (
	`id` INT(11) NOT NULL AUTO_INCREMENT, 
	`name` TEXT NOT NULL,
	`name_hash` CHAR(32) UNIQUE, 
	`person_id` INT(11) NOT NULL, 
	PRIMARY KEY (`id`), 
	FOREIGN KEY (`person_id`) 
		REFERENCES persons(`id`) 
		ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `pages` (
	`id` INT(11) NOT NULL AUTO_INCREMENT, 
	`url` TEXT NOT NULL, 
	`site_id` INT(11) NOT NULL, 
	`found_date_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
	`last_scan_date` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
	`url_hash` CHAR(32) UNIQUE, 
	PRIMARY KEY (`id`), 
	FOREIGN KEY (`site_id`) 
		REFERENCES sites(`id`) 
		ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `person_page_rank` (
	`person_id` INT(11) NOT NULL, 
	`page_id` INT(11) NOT NULL, 
	`rank` INT(11) NOT NULL, 
	FOREIGN KEY (`person_id`) 
		REFERENCES `persons`(`id`) 
		ON DELETE CASCADE, 
	FOREIGN KEY (`page_id`) 
		REFERENCES pages(`id`) 
		ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


ALTER TABLE persons DROP COLUMN rescan_needed;

ALTER TABLE person_page_rank ADD scan_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE person_page_rank CHANGE COLUMN scan_date date_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE pages ADD rescan_needed BOOL NOT NULL DEFAULT 1;
ALTER TABLE keywords ADD rescan_needed BOOL NOT NULL DEFAULT 1;
DROP TRIGGER IF EXISTS Pages_AfterInsert;
DROP TRIGGER IF EXISTS Pages_AfterUpdate;
DROP TRIGGER IF EXISTS Keywords_AfterUpdate;
DROP TRIGGER IF EXISTS Keywords_AfterInsert;
DROP TRIGGER IF EXISTS Persons_AfterInsert;
DROP TRIGGER IF EXISTS Persons_AfterUpdate;
DROP TRIGGER IF EXISTS Persons_BeforeUpdate;
DROP TRIGGER IF EXISTS Persons_BeforeInsert;
DROP TRIGGER IF EXISTS Keywords_BeforeInsert;
DROP TRIGGER IF EXISTS Keywords_BeforeUpdate;
DROP TRIGGER IF EXISTS Pages_BeforeUpdate;
DROP TRIGGER IF EXISTS PersonPageRank_AfterInsert;
DROP TABLE IF EXISTS handler;



CREATE TABLE IF NOT EXISTS `handler` (
	`id` INT(11) NOT NULL,
	`need_scan_keys_pers` BOOL NOT NULL DEFAULT 0,
	`need_scan_pages` BOOL NOT NULL DEFAULT 0,
	`create_upd_date_pers_keys` TIMESTAMP NULL,
	`create_upd_date_pages` TIMESTAMP NULL,
	`last_scan_pers_keys` TIMESTAMP NULL,
	`last_scan_pages` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO handler(id) VALUES('1');

CREATE TABLE IF NOT EXISTS `pages_content` (
  `page_id` INT(11) NOT NULL,
  `page_body_text` longtext,
  UNIQUE KEY `page_id_UNIQUE` (`page_id`),
  CONSTRAINT `id` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE sites ADD COLUMN rate_limit TINYINT(2) NOT NULL DEFAULT 5 AFTER rescan_needed;


ALTER TABLE keywords ADD COLUMN rescan_needed TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE pages ADD COLUMN rescan_needed TINYINT(1) NOT NULL DEFAULT 1;


DELIMITER $$
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
DROP TRIGGER IF EXISTS Sites_AfterInsert;


CREATE TRIGGER `ihunters`.`Keywords_BeforeInsert` BEFORE INSERT ON ihunters.keywords FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END$$

CREATE TRIGGER `ihunters`.`Keywords_BeforeUpdate` BEFORE UPDATE ON ihunters.keywords FOR EACH ROW
BEGIN
    IF NEW.name != OLD.name || NEW.person_id != OLD.person_id THEN
        SET NEW.name_hash = MD5(NEW.name);
		SET NEW.rescan_needed = 1;
        SET @maxId = (SELECT MAX(id)+1 FROM keywords);
        SET NEW.id = @maxId;
    END IF;

END$$

CREATE TRIGGER `ihunters`.`Pages_BeforeInsert` BEFORE INSERT ON ihunters.pages FOR EACH ROW
BEGIN
	SET NEW.url_hash = MD5(NEW.url);
END$$

CREATE TRIGGER `ihunters`.`Pages_BeforeUpdate` BEFORE UPDATE ON ihunters.pages FOR EACH ROW
BEGIN
	SET NEW.url_hash = MD5(NEW.url);
END$$

CREATE TRIGGER `ihunters`.`Persons_BeforeInsert` BEFORE INSERT ON ihunters.persons FOR EACH ROW
BEGIN
	IF NEW.name != OLD.name || NEW.person_id != OLD.person_id THEN
		SET NEW.name_hash = MD5(NEW.name);
		SET NEW.rescan_needed = 1;
		UPDATE persons 
		SET persons.rescan_needed = 1
		WHERE persons.id = NEW.person_id;
	END IF;
END$$

CREATE TRIGGER `ihunters`.`Persons_BeforeUpdate` BEFORE UPDATE ON ihunters.persons FOR EACH ROW
BEGIN
    IF NEW.name != OLD.name THEN
    	SET NEW.name_hash = MD5(NEW.name);
    END IF;
END$$

CREATE TRIGGER `ihunters`.`Persons_AfterInsert` AFTER INSERT ON ihunters.persons FOR EACH ROW
BEGIN
	IF NEW.url != OLD.url || 
		NEW.site_id != OLD.site_id || 
		NEW.found_date_time != OLD.found_date_time THEN
			SET NEW.url_hash = MD5(NEW.url);
			SET NEW.rescan_needed = 1;
	END IF;
END$$

CREATE TRIGGER `ihunters`.`Sites_AfterInsert` AFTER INSERT ON ihunters.sites FOR EACH ROW
BEGIN

	UPDATE pages SET last_scan_date=CURRENT_TIMESTAMP
	WHERE pages.id = NEW.page_id;
END$$

DELIMITER ;

