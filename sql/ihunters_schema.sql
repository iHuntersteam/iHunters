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

CREATE TABLE IF NOT EXISTS handler (
	id INT NOT NULL,
	need_scan_keys_pers BOOL NOT NULL DEFAULT 0,
	need_scan_pages BOOL NOT NULL DEFAULT 0,
	create_upd_date_pers_keys TIMESTAMP NULL,
	create_upd_date_pages TIMESTAMP NULL,
	last_scan_pers_keys TIMESTAMP NULL,
	last_scan_pages TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO handler(id) VALUES('1');

ALTER TABLE person_page_rank ADD scan_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE person_page_rank CHANGE COLUMN scan_date date_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE persons ADD create_upd_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE keywords ADD create_upd_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE pages ADD create_upd_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

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
	UPDATE pages SET last_scan_date=CURRENT_TIMESTAMP
	WHERE pages.id = NEW.page_id;
END$$

CREATE TRIGGER Persons_AfterUpdate
AFTER UPDATE ON persons
FOR EACH ROW
BEGIN
	UPDATE handler SET 
		create_upd_date_pers_keys = NEW.create_upd_date,
		need_scan_keys_pers = 1
	WHERE id = 1;
END$$

CREATE TRIGGER Persons_AfterInsert
AFTER INSERT ON persons
FOR EACH ROW
BEGIN
	UPDATE handler SET 
		create_upd_date_pers_keys = NEW.create_upd_date,
		need_scan_keys_pers = 1
	WHERE id = 1;
END$$

CREATE TRIGGER Keywords_AfterInsert
AFTER INSERT ON keywords
FOR EACH ROW
BEGIN
	UPDATE handler SET 
		create_upd_date_pers_keys = NEW.create_upd_date,
		need_scan_keys_pers = 1
	WHERE id = 1;
END$$

CREATE TRIGGER Keywords_AfterUpdate
AFTER UPDATE ON keywords
FOR EACH ROW
BEGIN
	UPDATE handler SET 
		create_upd_date_pers_keys = NEW.create_upd_date,
		need_scan_keys_pers = 1
	WHERE id = 1;
END$$

CREATE TRIGGER Pages_AfterUpdate
AFTER UPDATE ON pages
FOR EACH ROW
BEGIN
	UPDATE handler SET 
		create_upd_date_pers_keys = NEW.create_upd_date,
		need_scan_pages = 1
	WHERE id = 1;
END$$

CREATE TRIGGER Pages_AfterInsert
AFTER INSERT ON pages
FOR EACH ROW
BEGIN
	UPDATE handler SET 
		create_upd_date_pers_keys = NEW.create_upd_date,
		need_scan_pages = 1
	WHERE id = 1;
END$$

DELIMITER ;

