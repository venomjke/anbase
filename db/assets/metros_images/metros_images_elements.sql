-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 07 2012 г., 08:27
-- Версия сервера: 5.5.16
-- Версия PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `anbase`
--

-- --------------------------------------------------------

--
-- Структура таблицы `metros_images_elements`
--

CREATE TABLE IF NOT EXISTS `metros_images_elements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `metro_image_id` int(10) unsigned NOT NULL,
  `metro_id` int(10) unsigned DEFAULT NULL,
  `type` enum('station','line') NOT NULL DEFAULT 'station',
  `line` tinyint(3) unsigned DEFAULT NULL,
  `coords` varchar(512) NOT NULL DEFAULT '',
  `shape` enum('circle','default','poly','rect') NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  KEY `idx_metro_image_id` (`metro_image_id`),
  KEY `idx_metro_id` (`metro_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=240 ;

--
-- Дамп данных таблицы `metros_images_elements`
--

INSERT INTO `metros_images_elements` (`id`, `metro_image_id`, `metro_id`, `type`, `line`, `coords`, `shape`) VALUES
(3, 1, 5, 'station', NULL, '120,464,6', 'circle'),
(4, 1, 18, 'station', NULL, '355,67,6.7', 'circle'),
(5, 1, 8, 'station', NULL, '120,399,6', 'circle'),
(6, 1, 45, 'station', NULL, '65,247.5,6.5', 'circle'),
(8, 1, 10, 'station', NULL, '355,292,6.7', 'circle'),
(9, 1, 54, 'station', NULL, '283.5,433.5,6.7', 'circle'),
(12, 1, 14, 'station', NULL, '355,153,6.7', 'circle'),
(13, 1, 29, 'station', NULL, '235.5,197.5,6.7', 'circle'),
(14, 1, 44, 'station', NULL, '235.5,248,6.5', 'circle'),
(15, 1, 19, 'station', NULL, '355,45,6.7', 'circle'),
(16, 1, 20, 'station', NULL, '355,23,6.7', 'circle'),
(17, 1, 41, 'station', NULL, '423,391,6.5', 'circle'),
(18, 1, 2, 'station', NULL, '235.7,507,6.7', 'circle'),
(19, 1, 56, 'station', NULL, '272,349,6.7', 'circle'),
(20, 1, 6, 'station', NULL, '120,442,6', 'circle'),
(21, 1, 62, 'station', NULL, '144,61,6.7', 'circle'),
(22, 1, 60, 'station', NULL, '144,147,6.7', 'circle'),
(23, 1, 1, 'station', NULL, '235.6,528.4,6.7', 'circle'),
(24, 1, 49, 'station', NULL, '545,401,6.5', 'circle'),
(25, 1, 4, 'station', NULL, '120,485,6', 'circle'),
(26, 1, 15, 'station', NULL, '355,132,6.7', 'circle'),
(27, 1, 52, 'station', NULL, '391,309,6.5', 'circle'),
(28, 1, 40, 'station', NULL, '423,420,6.5', 'circle'),
(29, 1, 43, 'station', NULL, '355,248,6.5', 'circle'),
(30, 1, 21, 'station', NULL, '235.5,485,6.7', 'circle'),
(31, 1, 24, 'station', NULL, '235.5,420,6.7', 'circle'),
(32, 1, 7, 'station', NULL, '120,420,6', 'circle'),
(33, 1, 28, 'station', NULL, '235.5,230.5,6.7', 'circle'),
(34, 1, 50, 'station', NULL, '545,372,6.5', 'circle'),
(35, 1, 55, 'station', NULL, '283.5,405,6.7', 'circle'),
(36, 1, 38, 'station', NULL, '423,478,6.5', 'circle'),
(37, 1, 34, 'station', NULL, '235.5,53,6.7', 'circle'),
(38, 1, 22, 'station', NULL, '235.5,463.5,6.7', 'circle'),
(39, 1, 36, 'station', NULL, '235.5,10,6.7', 'circle'),
(40, 1, 30, 'station', NULL, '235.5,175.5,6.7', 'circle'),
(41, 1, 32, 'station', NULL, '235.5,96.5,6.7', 'circle'),
(42, 1, 42, 'station', NULL, '423,309,6.5', 'circle'),
(43, 1, 11, 'station', NULL, '355,231,6.7', 'circle'),
(44, 1, 13, 'station', NULL, '355,175,6.7', 'circle'),
(45, 1, 16, 'station', NULL, '355,110,6.7', 'circle'),
(46, 1, 17, 'station', NULL, '355,88,6.7', 'circle'),
(47, 1, 46, 'station', NULL, '65,212.5,6.7', 'circle'),
(48, 1, 39, 'station', NULL, '423,449,6.5', 'circle'),
(49, 1, 48, 'station', NULL, '545,429,6.5', 'circle'),
(50, 1, 3, 'station', NULL, '120,507,6', 'circle'),
(51, 1, 35, 'station', NULL, '235.5,31.5,6.7', 'circle'),
(52, 1, 9, 'station', NULL, '284,361,6', 'circle'),
(53, 1, 37, 'station', NULL, '423,506.5,6.5', 'circle'),
(54, 1, 57, 'station', NULL, '227,308,6.7', 'circle'),
(55, 1, 27, 'station', NULL, '235.5,294.5,6.7', 'circle'),
(56, 1, 53, 'station', NULL, '245,308,6.7', 'circle'),
(57, 1, 58, 'station', NULL, '144,190,6.7', 'circle'),
(58, 1, 61, 'station', NULL, '144,97,6.7', 'circle'),
(59, 1, 26, 'station', NULL, '236,361,6.5', 'circle'),
(60, 1, 33, 'station', NULL, '235.5,74.5,6.7', 'circle'),
(61, 1, 47, 'station', NULL, '545,458,6.5', 'circle'),
(62, 1, 25, 'station', NULL, '235.5,398.5,6.7', 'circle'),
(63, 1, 31, 'station', NULL, '235.6,118,6.7', 'circle'),
(64, 1, 12, 'station', NULL, '355,196,6.7', 'circle'),
(65, 1, 59, 'station', NULL, '144,169,6.7', 'circle'),
(66, 1, 23, 'station', NULL, '235.5,441.5,6.7', 'circle'),
(67, 1, 63, 'station', NULL, '176,279,6.7', 'circle'),
(68, 1, 64, 'station', NULL, '355,309,6.7', 'circle'),
(156, 2, 1, 'station', NULL, '128,378,5', 'circle'),
(157, 2, 2, 'station', NULL, '128,363,5', 'circle'),
(158, 2, 3, 'station', NULL, '66,348,5', 'circle'),
(159, 2, 4, 'station', NULL, '66,331,5', 'circle'),
(160, 2, 5, 'station', NULL, '66,317,5', 'circle'),
(161, 2, 6, 'station', NULL, '66,303,5', 'circle'),
(162, 2, 7, 'station', NULL, '85,282,5', 'circle'),
(163, 2, 8, 'station', NULL, '100,267,5', 'circle'),
(164, 2, 9, 'station', NULL, '159,254,5', 'circle'),
(165, 2, 10, 'station', NULL, '159,223,5', 'circle'),
(166, 2, 11, 'station', NULL, '190,192,5', 'circle'),
(167, 2, 12, 'station', NULL, '190,161,5', 'circle'),
(168, 2, 13, 'station', NULL, '190,145,5', 'circle'),
(169, 2, 14, 'station', NULL, '190,131,5', 'circle'),
(170, 2, 15, 'station', NULL, '190,114,5', 'circle'),
(171, 2, 16, 'station', NULL, '190,99,5', 'circle'),
(172, 2, 17, 'station', NULL, '190,83,5', 'circle'),
(173, 2, 18, 'station', NULL, '190,67,5', 'circle'),
(174, 2, 19, 'station', NULL, '190,52,5', 'circle'),
(175, 2, 20, 'station', NULL, '190,37,5', 'circle'),
(176, 2, 21, 'station', NULL, '128,347,5', 'circle'),
(177, 2, 22, 'station', NULL, '128,331,5', 'circle'),
(178, 2, 23, 'station', NULL, '128,316,5', 'circle'),
(179, 2, 24, 'station', NULL, '128,302,5', 'circle'),
(180, 2, 25, 'station', NULL, '128,286,5', 'circle'),
(181, 2, 26, 'station', NULL, '128,254,5', 'circle'),
(182, 2, 27, 'station', NULL, '128,223,5', 'circle'),
(183, 2, 28, 'station', NULL, '128,191,5', 'circle'),
(184, 2, 29, 'station', NULL, '128,160,5', 'circle'),
(185, 2, 30, 'station', NULL, '128,144,5', 'circle'),
(186, 2, 31, 'station', NULL, '128,129,5', 'circle'),
(187, 2, 32, 'station', NULL, '128,114,5', 'circle'),
(188, 2, 33, 'station', NULL, '128,97,5', 'circle'),
(189, 2, 34, 'station', NULL, '128,82,5', 'circle'),
(190, 2, 35, 'station', NULL, '128,67,5', 'circle'),
(191, 2, 36, 'station', NULL, '128,51,5', 'circle'),
(192, 2, 37, 'station', NULL, '237,347,5', 'circle'),
(193, 2, 38, 'station', NULL, '237,329,5', 'circle'),
(194, 2, 39, 'station', NULL, '237,315,5', 'circle'),
(195, 2, 40, 'station', NULL, '237,285,5', 'circle'),
(196, 2, 41, 'station', NULL, '237,270,5', 'circle'),
(197, 2, 42, 'station', NULL, '221,223,5', 'circle'),
(198, 2, 43, 'station', NULL, '190,191,5', 'circle'),
(199, 2, 44, 'station', NULL, '128,191,5', 'circle'),
(200, 2, 45, 'station', NULL, '73,191,5', 'circle'),
(201, 2, 46, 'station', NULL, '36,191,5', 'circle'),
(202, 2, 47, 'station', NULL, '283,314,5', 'circle'),
(203, 2, 48, 'station', NULL, '283,299,5', 'circle'),
(204, 2, 49, 'station', NULL, '283,284,5', 'circle'),
(205, 2, 50, 'station', NULL, '283,270,5', 'circle'),
(206, 2, 52, 'station', NULL, '190,223,5', 'circle'),
(207, 2, 53, 'station', NULL, '128,223,5', 'circle'),
(208, 2, 54, 'station', NULL, '190,323,5', 'circle'),
(209, 2, 55, 'station', NULL, '190,300,5', 'circle'),
(210, 2, 56, 'station', NULL, '159,254,5', 'circle'),
(211, 2, 57, 'station', NULL, '128,223,5', 'circle'),
(212, 2, 58, 'station', NULL, '66,160,5', 'circle'),
(213, 2, 59, 'station', NULL, '66,144,5', 'circle'),
(214, 2, 60, 'station', NULL, '66,130,5', 'circle'),
(215, 2, 61, 'station', NULL, '66,114,5', 'circle'),
(216, 2, 62, 'station', NULL, '66,99,5', 'circle'),
(217, 2, 63, 'station', NULL, '81,176,5', 'circle'),
(218, 2, 64, 'station', NULL, '159,223,5', 'circle'),
(219, 1, NULL, 'line', 1, '17,570,39,579', 'rect'),
(222, 1, NULL, 'line', 2, '105,570,127,579', 'rect'),
(223, 1, NULL, 'line', 3, '194,570,216,579', 'rect'),
(224, 1, NULL, 'line', 4, '284,570,306,579', 'rect'),
(225, 1, NULL, 'line', 5, '398,571,419,579', 'rect'),
(226, 2, NULL, 'line', 1, '57,355,75,367', 'rect'),
(227, 2, NULL, 'line', 1, '181,17,200,30', 'rect'),
(228, 2, NULL, 'line', 2, '104,371,122,384', 'rect'),
(229, 2, NULL, 'line', 2, '120,31,138,45', 'rect'),
(230, 1, 65, 'station', NULL, '236,361,6.5', 'circle'),
(231, 2, 65, 'station', NULL, '128,254,5', 'circle'),
(232, 2, NULL, 'line', 3, '11,185,30,198', 'rect'),
(233, 2, NULL, 'line', 3, '228,353,246,366', 'rect'),
(234, 2, NULL, 'line', 4, '99,217,117,229', 'rect'),
(235, 2, NULL, 'line', 4, '291,309,309,321', 'rect'),
(236, 2, NULL, 'line', 5, '56,79,75,92', 'rect'),
(237, 2, NULL, 'line', 5, '181,329,200,342', 'rect'),
(238, 1, 67, 'station', NULL, '423,309,6.5', 'circle'),
(239, 2, 67, 'station', NULL, '221,223,5', 'circle');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `metros_images_elements`
--
ALTER TABLE `metros_images_elements`
  ADD CONSTRAINT `metros_images_elements_ibfk_1` FOREIGN KEY (`metro_id`) REFERENCES `metros` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `metros_images_elements_ibfk_2` FOREIGN KEY (`metro_image_id`) REFERENCES `metros_images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
