-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3307
-- Время создания: Май 25 2016 г., 10:47
-- Версия сервера: 5.5.45
-- Версия PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ihunters`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Pages`
--

CREATE TABLE IF NOT EXISTS `Pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `siteID` int(11) NOT NULL,
  `FoundDateTime` datetime NOT NULL,
  `LastScanDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `siteID` (`siteID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `Pages`
--

INSERT INTO `Pages` (`id`, `url`, `siteID`, `FoundDateTime`, `LastScanDate`) VALUES
(1, 'lenta.ru/rubrics', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'lenta.ru/rz', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'mail.ru/category', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'yandex.ru/cat', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'yandex.ru/sad', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `PersonPageRank`
--

CREATE TABLE IF NOT EXISTS `PersonPageRank` (
  `PersonID` int(11) NOT NULL,
  `PageID` int(11) NOT NULL,
  `Rank` int(11) NOT NULL,
  KEY `PersonID` (`PersonID`,`PageID`),
  KEY `PageID` (`PageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `PersonPageRank`
--

INSERT INTO `PersonPageRank` (`PersonID`, `PageID`, `Rank`) VALUES
(1, 1, 500),
(2, 3, 20),
(1, 4, 30),
(2, 1, 200),
(1, 2, 700);

-- --------------------------------------------------------

--
-- Структура таблицы `Persons`
--

CREATE TABLE IF NOT EXISTS `Persons` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `Persons`
--

INSERT INTO `Persons` (`ID`, `Name`) VALUES
(2, 'Медведев'),
(4, 'Навальный'),
(1, 'Путин'),
(3, 'Шойгу');

-- --------------------------------------------------------

--
-- Структура таблицы `Sites`
--

CREATE TABLE IF NOT EXISTS `Sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `Sites`
--

INSERT INTO `Sites` (`id`, `name`) VALUES
(1, 'lenta.ru'),
(2, 'mail.ru'),
(4, 'vedomosti.ru'),
(3, 'yandex.ru');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Pages`
--
ALTER TABLE `Pages`
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`siteID`) REFERENCES `Sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `PersonPageRank`
--
ALTER TABLE `PersonPageRank`
  ADD CONSTRAINT `personpagerank_ibfk_1` FOREIGN KEY (`PersonID`) REFERENCES `Persons` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personpagerank_ibfk_2` FOREIGN KEY (`PageID`) REFERENCES `Pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
