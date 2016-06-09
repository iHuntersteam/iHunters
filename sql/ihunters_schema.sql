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


ALTER TABLE persons DROP COLUMN rescan_needed;
ALTER TABLE pages DROP COLUMN rescan_needed;
ALTER TABLE keywords DROP COLUMN rescan_needed;

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


DELIMITER $$

CREATE TRIGGER Persons_BeforeInsert 
BEFORE INSERT ON persons
FOR EACH ROW
BEGIN
	SET NEW.name_hash = MD5(NEW.name);
END$$

CREATE TRIGGER Persons_AfterInsert 
AFTER INSERT ON persons
FOR EACH ROW
BEGIN
	INSERT INTO keywords(name, person_id)
	VALUES(NEW.name, NEW.id);
END$$

CREATE TRIGGER Persons_BeforeUpdate 
BEFORE UPDATE ON persons
FOR EACH ROW
BEGIN
	IF NEW.name != OLD.name || NEW.create_upd_date != OLD.create_upd_date THEN
		SET NEW.name_hash = MD5(NEW.name);
		SET NEW.rescan_needed = 1;
	END IF;
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
	IF NEW.name != OLD.name || NEW.person_id != OLD.person_id THEN
		SET NEW.name_hash = MD5(NEW.name);
		SET NEW.rescan_needed = 1;
		UPDATE persons 
		SET persons.rescan_needed = 1
		WHERE persons.id = NEW.person_id;
	END IF;
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
	IF NEW.url != OLD.url || 
		NEW.site_id != OLD.site_id || 
		NEW.found_date_time != OLD.found_date_time THEN
			SET NEW.url_hash = MD5(NEW.url);
			SET NEW.rescan_needed = 1;
	END IF;
END$$

CREATE TRIGGER PersonPageRank_AfterInsert
AFTER INSERT ON person_page_rank
FOR EACH ROW
BEGIN
	UPDATE pages SET last_scan_date=CURRENT_TIMESTAMP
	WHERE pages.id = NEW.page_id;
END$$

DELIMITER ;

