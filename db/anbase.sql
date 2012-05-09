-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 09 2012 г., 22:12
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
-- Структура таблицы `attempts_login_users`
--

CREATE TABLE IF NOT EXISTS `attempts_login_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `login` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login_idx` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Структура таблицы `autologin_users`
--

CREATE TABLE IF NOT EXISTS `autologin_users` (
  `key_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('10f420477d472b2476b2a1bcffbeae4c', '192.168.137.205', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19', 1336593860, 'a:12:{s:7:"user_id";s:2:"53";s:5:"login";s:13:"smakmybitchup";s:5:"email";s:26:"victor.kuvshinov@gmail.com";s:6:"status";i:1;s:4:"role";s:10:"Админ";s:4:"name";s:12:"Виктор";s:9:"last_name";s:16:"Кувшинов";s:11:"middle_name";s:18:"Андреевич";s:5:"phone";s:12:"+79119210360";s:6:"org_id";s:2:"16";s:3:"ceo";s:2:"53";s:10:"last_login";s:17:"09.05.12 22:05:31";}'),
('35fcfa9b3e794cccb4fd45d67cd15698', '192.168.137.205', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19', 1336594050, ''),
('6034f51a6c677c365d5317b1eab73d27', '192.168.137.121', 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19', 1336588389, 'a:13:{s:9:"user_data";s:0:"";s:7:"user_id";s:2:"38";s:5:"login";s:5:"pavel";s:5:"email";s:22:"pavel.flyweb@gmail.com";s:6:"status";i:1;s:4:"role";s:10:"Админ";s:4:"name";s:10:"Павел";s:9:"last_name";s:24:"Веретенников";s:11:"middle_name";s:20:"Алексеевич";s:5:"phone";s:11:"89602452411";s:6:"org_id";s:2:"13";s:3:"ceo";s:2:"38";s:10:"last_login";s:17:"09.05.12 15:16:27";}'),
('72c1d96731563ba09dd05cea7bad806f', '192.168.137.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19', 1336593557, 'a:13:{s:9:"user_data";s:0:"";s:7:"user_id";s:1:"1";s:5:"login";s:6:"user_1";s:5:"email";s:14:"user_1@mail.ru";s:6:"status";i:1;s:4:"role";s:10:"Админ";s:4:"name";s:18:"Александр";s:9:"last_name";s:14:"Стригин";s:11:"middle_name";s:16:"Петрович";s:5:"phone";s:12:"+79219845212";s:6:"org_id";s:1:"1";s:3:"ceo";s:1:"1";s:10:"last_login";s:17:"09.05.12 17:43:35";}');

-- --------------------------------------------------------

--
-- Структура таблицы `invites_users`
--

CREATE TABLE IF NOT EXISTS `invites_users` (
  `key_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `org_id` int(10) unsigned NOT NULL DEFAULT '0',
  `role` enum('Админ','Менеджер','Агент') NOT NULL DEFAULT 'Агент',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `manager_id` int(10) unsigned DEFAULT '0',
  `email` varchar(100) NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `idx_org_id` (`org_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Дамп данных таблицы `invites_users`
--

INSERT INTO `invites_users` (`key_id`, `org_id`, `role`, `created`, `manager_id`, `email`, `id`) VALUES
('7481c6285d03cbd87e52c03f', 16, 'Агент', '2012-05-09 20:06:36', NULL, 'pizdec@gmail.com', 29);

-- --------------------------------------------------------

--
-- Структура таблицы `managers_users`
--

CREATE TABLE IF NOT EXISTS `managers_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `manager_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_manager_id` (`manager_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Дамп данных таблицы `managers_users`
--

INSERT INTO `managers_users` (`id`, `manager_id`, `user_id`) VALUES
(6, 48, 49),
(46, 3, 46),
(47, 3, 50);

-- --------------------------------------------------------

--
-- Структура таблицы `metros`
--

CREATE TABLE IF NOT EXISTS `metros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `line` varchar(3) NOT NULL DEFAULT '1',
  `transshipment` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- Дамп данных таблицы `metros`
--

INSERT INTO `metros` (`id`, `name`, `line`, `transshipment`) VALUES
(1, 'Купчино', '2', NULL),
(2, 'Звездная', '2', NULL),
(3, 'Проспект Ветеранов', '1', NULL),
(4, 'Ленинский Проспект', '1', NULL),
(5, 'Автово', '1', NULL),
(6, 'Кировский Завод', '1', NULL),
(7, 'Нарвская', '1', NULL),
(8, 'Балтийская', '1', NULL),
(9, 'Пушкинская', '1', 6),
(10, 'Владимирская', '1', 4),
(11, 'Площадь Восстания', '1', 1),
(12, 'Чернышевская', '1', NULL),
(13, 'Площадь Ленина', '1', NULL),
(14, 'Выборгская', '1', NULL),
(15, 'Лесная', '1', NULL),
(16, 'Площадь Мужества', '1', NULL),
(17, 'Политехническая', '1', NULL),
(18, 'Академическая', '1', NULL),
(19, 'Гражданский Проспект', '1', NULL),
(20, 'Девяткино', '1', NULL),
(21, 'Московская', '2', NULL),
(22, 'Парк Победы', '2', NULL),
(23, 'Электросила', '2', NULL),
(24, 'Московские Ворота', '2', NULL),
(25, 'Фрунзенская', '2', NULL),
(26, 'Технологический Институт I', '2', 8),
(27, 'Сенная Площадь', '2', 3),
(28, 'Невский Проспект', '2', 2),
(29, 'Горьковская', '2', NULL),
(30, 'Петроградская', '2', NULL),
(31, 'Черная речка', '2', NULL),
(32, 'Пионерская', '2', NULL),
(33, 'Удельная', '2', NULL),
(34, 'Озерки', '2', NULL),
(35, 'Проспект Просвещения', '2', NULL),
(36, 'Парнас', '2', NULL),
(37, 'Рыбацкое', '3', NULL),
(38, 'Обухово', '3', NULL),
(39, 'Пролетарская', '3', NULL),
(40, 'Ломоносовская', '3', NULL),
(41, 'Елизаровская', '3', NULL),
(42, 'Площадь Александра Невского I', '3', 7),
(43, 'Маяковская', '3', 1),
(44, 'Гостинный Двор', '3', 2),
(45, 'Василеостровская', '3', NULL),
(46, 'Приморская', '3', NULL),
(47, 'Улица Дыбенко', '4', NULL),
(48, 'Проспект Большевиков', '4', NULL),
(49, 'Ладожская', '4', NULL),
(50, 'Новочеркасская', '4', NULL),
(52, 'Лиговский Проспект', '4', NULL),
(53, 'Спасская', '4', 3),
(54, 'Волковская', '5', NULL),
(55, 'Обводный Канал', '5', NULL),
(56, 'Звенигородская', '5', 6),
(57, 'Садовая', '5', 3),
(58, 'Спортивная', '5', NULL),
(59, 'Чкаловская', '5', NULL),
(60, 'Крестовский Остров', '5', NULL),
(61, 'Старая Деревня', '5', NULL),
(62, 'Комендантский Проспект', '5', NULL),
(63, 'Адмиралтейская', '5', NULL),
(64, 'Достоевская', '4', 4),
(65, 'Технологический Институт II', '1', 8),
(67, 'Площадь Александра Невского II', '4', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `metros_images`
--

CREATE TABLE IF NOT EXISTS `metros_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `metros_images`
--

INSERT INTO `metros_images` (`id`, `name`, `image`) VALUES
(1, 'metro-normal', 'metro-normal.png'),
(2, 'metro-list', 'metro-list.png');

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

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(19);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `create_date` date NOT NULL DEFAULT '0000-00-00',
  `deal_type` enum('Куплю','Сниму','Сдам','Продам') NOT NULL DEFAULT 'Сдам',
  `price` float unsigned NOT NULL DEFAULT '0',
  `description` mediumtext NOT NULL,
  `delegate_date` date NOT NULL DEFAULT '0000-00-00',
  `finish_date` date NOT NULL DEFAULT '0000-00-00',
  `phone` varchar(20) NOT NULL,
  `state` enum('on','off') NOT NULL DEFAULT 'on',
  `org_id` int(10) unsigned NOT NULL DEFAULT '0',
  `category` enum('Жилая','Коммерческая','Загородная') NOT NULL DEFAULT 'Жилая',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `delegated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `finished` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `any_metro` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `any_region` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_number` (`number`),
  KEY `idx_org_id` (`org_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12141 ;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `number`, `create_date`, `deal_type`, `price`, `description`, `delegate_date`, `finish_date`, `phone`, `state`, `org_id`, `category`, `created`, `delegated`, `finished`, `any_metro`, `any_region`) VALUES
(31, 23123, '2012-04-19', 'Сниму', 10002300, 'asdasdasdasd', '2012-05-09', '0000-00-00', '89219844040', 'on', 1, 'Жилая', '0000-00-00 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(34, 5000, '2012-04-04', 'Куплю', 40000, '1 к.кв С/п+реб Длит.срок, евро рем. Есть собака(такса)', '2012-05-09', '2012-04-30', '79219844040', 'on', 1, 'Загородная', '0000-00-00 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(40, 231, '2012-05-03', 'Сниму', 1000, 'vhgvh', '2012-05-09', '0000-00-00', '89219844040', 'on', 1, 'Загородная', '0000-00-00 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 1),
(41, 1553722, '0000-00-00', 'Продам', 10000, '124124', '2012-05-09', '0000-00-00', '9633178599', 'on', 12, 'Жилая', '0000-00-00 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(5993, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(5995, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6002, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6009, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6010, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6011, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6012, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6013, 1001, '2012-04-22', 'Сниму', 10000, 'Клиент мудак денег не дал, не посоны я реально не левачу', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6014, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6015, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6016, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Загородная', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(6017, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 1, 1),
(6018, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 1),
(6019, 1001, '2012-04-22', 'Сниму', 10000, 'Текст текст текст текст объявления Текст текст текст текст объявления \nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления\nТекст текст текст текст объявления', '2012-05-09', '2012-04-22', '89216542311', 'on', 1, 'Жилая', '2012-04-22 00:00:00', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(7634, 1, '2012-05-06', 'Сниму', 12000, 'комната, 2 м/ч 24 г. прописка ЛО. Анатолий', '2012-05-09', '0000-00-00', '89046081448', 'off', 13, 'Жилая', '2012-05-06 14:45:26', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(7635, 2, '2012-05-06', 'Сниму', 12000, '1 к.кв. 1 м/ч. Александр', '2012-05-09', '0000-00-00', '89219177711', 'on', 13, 'Загородная', '2012-05-06 14:48:03', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0),
(7636, 3, '2012-05-06', 'Сниму', 23000, '1к.кв. с/п+реб. СРОЧНО! Алексей', '2012-05-09', '0000-00-00', '89052700055', 'off', 13, 'Жилая', '2012-05-06 15:03:26', '2012-05-09 17:58:06', '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `orders_metros`
--

CREATE TABLE IF NOT EXISTS `orders_metros` (
  `order_id` int(10) unsigned NOT NULL,
  `metro_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`order_id`,`metro_id`),
  KEY `metro_id` (`metro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders_metros`
--

INSERT INTO `orders_metros` (`order_id`, `metro_id`) VALUES
(31, 1),
(31, 2),
(31, 3),
(31, 4),
(31, 5),
(31, 6),
(31, 7),
(31, 8),
(40, 9),
(6012, 9),
(6012, 10),
(6012, 11),
(7635, 18),
(31, 22),
(40, 23),
(40, 24),
(40, 25),
(6012, 26),
(6019, 26),
(6011, 27),
(6012, 27),
(6017, 27),
(6012, 28),
(6012, 43),
(6012, 44),
(6011, 53),
(6012, 53),
(6017, 53),
(6012, 56),
(6011, 57),
(6012, 57),
(6017, 57),
(6012, 64),
(6012, 65),
(6019, 65);

-- --------------------------------------------------------

--
-- Структура таблицы `orders_regions`
--

CREATE TABLE IF NOT EXISTS `orders_regions` (
  `order_id` int(10) unsigned NOT NULL,
  `region_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`order_id`,`region_id`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders_regions`
--

INSERT INTO `orders_regions` (`order_id`, `region_id`) VALUES
(31, 1),
(40, 1),
(6011, 2),
(6012, 2),
(6019, 2),
(7636, 2),
(40, 3),
(6011, 3),
(6012, 3),
(6019, 3),
(7636, 3),
(7634, 4),
(7635, 4),
(6013, 12),
(6011, 14),
(6012, 14),
(6019, 14),
(7636, 14),
(6012, 15),
(7634, 16),
(7635, 16),
(6011, 18),
(6012, 18),
(6019, 18),
(7636, 18);

-- --------------------------------------------------------

--
-- Структура таблицы `orders_users`
--

CREATE TABLE IF NOT EXISTS `orders_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

--
-- Дамп данных таблицы `orders_users`
--

INSERT INTO `orders_users` (`id`, `user_id`, `order_id`) VALUES
(30, 36, 41),
(32, 3, 40),
(61, 3, 6018),
(66, 3, 5993),
(70, 3, 6002),
(85, 49, 7635),
(86, 46, 6016),
(87, 5, 6017),
(88, 5, 6015),
(89, 50, 6013);

-- --------------------------------------------------------

--
-- Структура таблицы `organizations`
--

CREATE TABLE IF NOT EXISTS `organizations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `ceo` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ceo_id` (`ceo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `ceo`, `email`, `phone`) VALUES
(1, 'ООО "Сокрафт"', 1, 'sokraft@mail.ru', '+79219542312'),
(3, 'Организация "ОРГ134"', 2, 'ORG2@jopa.ru', '79214512155'),
(8, 'ООО "Агентство Недвижимостии"', 18, 'org@mail.ru', '79425212312'),
(11, 'Corp', 33, 'd@ddcom', '22222222'),
(12, 'Verpa', 34, 'support@verpa.com', '8912221122'),
(13, 'АН "Flyweb"', 38, 'pavel@fliweb.su', '5556622'),
(15, 'ООО "Агентство Недвижимости"', 52, 'apstrigin@gmail.com', '+79219844040'),
(16, 'Смакмайбитчап', 53, 'smb_06@mail.ru', '+79119210360');

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `regions`
--

INSERT INTO `regions` (`id`, `name`) VALUES
(1, 'Фрунзенский'),
(2, 'Адмиралтейский'),
(3, 'Василеостровский'),
(4, 'Выборгский'),
(5, 'Калининский'),
(6, 'Кировский'),
(7, 'Колпинский'),
(8, 'Красногвардейский'),
(9, 'Красносельский'),
(10, 'Кронштадский'),
(11, 'Курортный'),
(12, 'Московский'),
(13, 'Невский'),
(14, 'Петроградский'),
(15, 'Петродворцовый'),
(16, 'Приморский'),
(17, 'Пушкинский'),
(18, 'Центральный');

-- --------------------------------------------------------

--
-- Структура таблицы `regions_images`
--

CREATE TABLE IF NOT EXISTS `regions_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `regions_images`
--

INSERT INTO `regions_images` (`id`, `name`, `image`) VALUES
(1, 'regions-normal', 'regions-normal.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `regions_images_elements`
--

CREATE TABLE IF NOT EXISTS `regions_images_elements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_image_id` int(10) unsigned NOT NULL,
  `region_id` int(10) unsigned DEFAULT NULL,
  `coords` varchar(512) NOT NULL DEFAULT '',
  `shape` enum('circle','default','poly','rect') NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  KEY `idx_region_image_id` (`region_image_id`),
  KEY `idx_region_id` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `regions_images_elements`
--

INSERT INTO `regions_images_elements` (`id`, `region_image_id`, `region_id`, `coords`, `shape`) VALUES
(1, 1, 2, '315,265, 309,265, 307,261, 295,262, 295,251, 304,239, 313,235, 318,243, 324,252, 318,257', 'poly'),
(2, 1, 3, '289,249, 279,239, 275,228, 279,219, 288,218, 303,225, 310,228, 314,234, 305,239, 298,246', 'poly'),
(3, 1, 4, '319,207, 325,212, 332,206, 333,196, 335,182, 337,168, 344,152, 339,134, 329,127, 311,119, 306,110, 304,102, 297,102, 285,106, 273,105, 261,106, 249,109, 238,113, 233,121, 247,135, 256,147, 256,151, 279,145, 282,145, 285,147, 287,152, 301,144, 311,152, 317,164, 320,183, 322,195', 'poly'),
(4, 1, 5, '347,225, 351,220, 357,207, 361,195, 363,186, 363,165, 359,162, 354,158, 344,153, 338,164, 334,183, 332,197, 332,206, 328,208, 329,219, 327,225, 335,226, 340,223', 'poly'),
(5, 1, 6, '262,270, 270,262, 278,252, 295,249, 295,262, 306,260, 309,272, 305,295, 297,309, 287,314, 278,315, 269,316, 268,311, 276,301, 282,287, 270,284, 263,276', 'poly'),
(6, 1, 7, '461,355, 440,352, 422,342, 412,331, 406,322, 401,321, 396,326, 392,323, 387,323, 382,327, 371,314, 360,313, 356,317, 368,340, 384,349, 382,366, 387,375, 398,374, 404,390, 425,384, 430,393, 444,392, 445,384, 449,378, 468,377', 'poly'),
(7, 1, 8, '345,251, 353,251, 365,239, 384,240, 389,214, 400,210, 390,204, 384,188, 380,187, 380,178, 366,165, 362,165, 363,178, 359,204, 352,218, 347,226', 'poly'),
(8, 1, 9, '212,391, 221,392, 241,410, 245,402, 236,390, 235,377, 240,372, 240,361, 251,357, 253,337, 262,337, 275,333, 286,322, 288,313, 280,315, 269,316, 272,306, 282,288, 269,283, 263,276, 251,286, 242,286, 234,286, 235,299, 226,305, 225,319, 241,323, 236,333, 233,342, 228,344, 218,355, 221,364, 220,373, 212,383', 'poly'),
(9, 1, 10, '89,163, 92,176, 94,183, 109,182, 112,187, 120,197, 124,203, 125,201, 134,190, 128,189, 114,175, 110,165', 'poly'),
(10, 1, 11, '9,37, 39,11, 60,20, 71,13, 81,10, 92,6, 100,10, 111,15, 111,9, 132,14, 138,22, 142,28, 146,35, 157,41, 169,49, 179,55, 191,62, 217,76, 261,93, 271,97, 271,97, 276,99, 276,105, 271,106, 261,107, 249,107, 242,112, 235,117, 235,123, 244,131, 254,144, 256,152, 256,160, 251,164, 245,168, 238,168, 235,174, 233,182, 224,186, 217,188, 198,183, 193,176, 191,163, 193,152, 191,136, 180,123, 191,102, 178,77, 151,61, 118,50, 89,40, 48,39, 38,46, 26,48, 17,44', 'poly'),
(11, 1, 12, '278,336, 287,337, 301,338, 295,352, 295,363, 300,368, 306,372, 313,368, 317,362, 315,334, 325,326, 335,321, 335,313, 330,284, 324,260, 319,257, 315,265, 309,266, 306,285, 296,310, 288,313, 280,325', 'poly'),
(12, 1, 13, '383,328, 386,324, 392,324, 396,326, 403,320, 399,312, 389,313, 379,295, 386,291, 381,270, 383,247, 384,240, 365,239, 353,251, 334,253, 336,260, 353,287, 361,303, 366,306, 367,313', 'poly'),
(13, 1, 14, '289,205, 279,205, 284,217, 297,222, 314,231, 323,227, 330,219, 326,211, 319,207, 303,204', 'poly'),
(14, 1, 15, '143,255, 154,258, 188,277, 212,288, 235,286, 234,299, 226,305, 225,319, 215,318, 211,304, 196,303, 179,307, 178,304, 179,296, 160,292, 160,296, 156,297, 156,290, 144,280, 139,280, 133,279, 131,283, 126,283, 125,279, 133,265', 'poly'),
(15, 1, 16, '279,204, 268,200, 258,196, 243,196, 224,187, 233,182, 233,175, 238,169, 255,163, 256,151, 281,146, 286,152, 300,144, 315,158, 321,188, 319,207, 309,204, 296,204', 'poly'),
(16, 1, 17, '285,453, 300,440, 304,435, 312,438, 314,428, 344,424, 350,418, 354,400, 363,400, 374,415, 384,415, 389,405, 400,405, 403,401, 403,389, 397,374, 387,374, 383,365, 384,349, 366,337, 356,317, 343,319, 327,325, 314,335, 316,363, 305,372, 298,385, 280,407, 276,419, 274,427, 280,428', 'poly'),
(17, 1, 1, '356,317, 367,313, 367,307, 362,302, 344,273, 335,254, 323,252, 321,256, 327,279, 333,297, 334,309, 336,321, 347,319', 'poly'),
(18, 1, 18, '324,252, 330,253, 335,253, 340,251, 345,251, 347,225, 334,226, 324,225, 313,235, 318,243', 'poly');

-- --------------------------------------------------------

--
-- Структура таблицы `settings_org`
--

CREATE TABLE IF NOT EXISTS `settings_org` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_id` int(10) unsigned NOT NULL,
  `default_category` enum('Жилая','Коммерческая','Загородная') NOT NULL DEFAULT 'Жилая',
  `default_dealtype` enum('Куплю','Сниму','Сдам','Продам') NOT NULL DEFAULT 'Сниму',
  `price_col` tinyint(4) NOT NULL DEFAULT '1',
  `regions_col` tinyint(4) NOT NULL DEFAULT '1',
  `metros_col` tinyint(4) NOT NULL DEFAULT '1',
  `phone_col` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_org_id` (`org_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `settings_org`
--

INSERT INTO `settings_org` (`id`, `org_id`, `default_category`, `default_dealtype`, `price_col`, `regions_col`, `metros_col`, `phone_col`) VALUES
(2, 15, 'Жилая', 'Сниму', 1, 1, 1, 1),
(3, 1, 'Жилая', 'Сниму', 0, 1, 1, 1),
(4, 3, 'Жилая', 'Сниму', 1, 1, 1, 1),
(5, 8, 'Жилая', 'Сниму', 1, 1, 1, 1),
(6, 11, 'Жилая', 'Сниму', 1, 1, 1, 1),
(7, 12, 'Жилая', 'Сниму', 1, 1, 1, 1),
(8, 13, 'Жилая', 'Сниму', 1, 1, 1, 1),
(9, 16, 'Жилая', 'Сниму', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `activated` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `name` varchar(15) NOT NULL,
  `middle_name` varchar(15) NOT NULL,
  `last_name` varchar(15) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('Админ','Менеджер','Агент') NOT NULL DEFAULT 'Агент',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `modifed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_idx` (`login`),
  UNIQUE KEY `email_idx` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `activated`, `name`, `middle_name`, `last_name`, `phone`, `role`, `created`, `last_login`, `last_ip`, `modifed`, `password`) VALUES
(1, 'user_1', 'user_1@mail.ru', 1, 'Александр', 'Петрович', 'Стригин', '+79219845212', 'Админ', '0000-00-00 00:00:00', '2012-05-09 20:45:40', '192.168.137.1', '2012-05-09 18:45:40', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(2, 'user_2', 'user_2@mail.ru', 1, 'user_2', 'user_2', 'user_2', 'user_2', 'Админ', '0000-00-00 00:00:00', '2012-05-05 22:49:42', '192.168.137.98', '2012-05-05 20:49:42', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(3, 'user_3', 'user_3@mail.ru', 1, 'Юзер3', 'Юзер3', 'Юзер3', '79219844040', 'Менеджер', '0000-00-00 00:00:00', '2012-05-09 17:28:13', '192.168.137.1', '2012-05-09 15:28:13', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(5, 'user_5', 'user_5@mail.ru', 1, 'Юзер 5', 'Юзер 5', 'Юзер 5', '89219842112', 'Админ', '0000-00-00 00:00:00', '2012-05-06 14:34:07', '192.168.137.1', '2012-05-07 06:41:38', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(6, 'user_6', 'user_6@mail.ru', 1, 'user_6', 'user_6', 'user_6', 'user_6', 'Агент', '0000-00-00 00:00:00', '2012-03-25 14:20:44', '192.168.137.1', '2012-03-25 12:20:44', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(7, 'user_7', 'user_7@mail.ru', 1, 'user_7', 'user_7', 'user_7', 'user_7', 'Агент', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-20 02:56:28', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(9, 'user_9', 'user_9@mail.ru', 1, 'user_9', 'user_9', 'user_9', 'user_9', 'Агент', '2012-03-19 16:23:34', '2012-03-19 16:23:34', '', '2012-03-20 02:56:28', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(18, 'alex', 'alex@gmail.com', 1, 'Александр', 'Петрович', 'Стригин', '79219844040', 'Админ', '2012-03-22 19:25:43', '2012-04-16 12:07:19', '192.168.137.1', '2012-04-20 16:36:40', '$P$BRJbHz9mY11vxXw3OHXf9ptrXLBs.d0'),
(33, 'vpash-90', 'vpash-90@mail.ru', 1, 'Павел', 'Алексеевич2', 'Veretennikov', '89602452410', 'Админ', '2012-04-07 20:03:23', '2012-04-07 20:03:31', '192.168.137.127', '2012-04-20 16:36:50', '$P$BqCiGuo0Rg2ND6/S0W2Yx2nHyPs0660'),
(34, 'verpa', '2234562@mail.ru', 1, 'Павел я', 'Алексеевич', 'Веретенников', '89602452410', 'Админ', '2012-04-10 16:26:55', '2012-04-19 17:13:57', '192.168.137.30', '2012-04-20 16:37:06', '$P$Bkuhc/yMzEaFc2gITc8.SInfKYYfJN1'),
(35, 'verpa2', 'dfsklmd@dsklfms.re', 1, 'Сергей', 'Олегович', 'Кравцов', '89602452410', 'Менеджер', '2012-04-10 16:54:09', '2012-04-10 17:02:04', '192.168.137.26', '2012-04-10 15:02:53', '$P$BZ5T1XQ78.sUgFbOc22NFvaA8hP2hU.'),
(36, 'verpa3', '87373@kg.witj', 1, '112', '221', '331', '89602452410', 'Менеджер', '2012-04-10 17:39:02', '2012-04-10 17:40:59', '192.168.137.26', '2012-04-20 16:37:19', '$P$BG7lu7yE1ZioqRKT.XWD0bj80qZZIF0'),
(37, 'verpa4', '87373@kg.witjq', 1, 'Имя', 'Отчество', 'Фамилия', '89602442410', 'Агент', '2012-04-10 17:45:34', '2012-04-17 13:35:22', '192.168.137.53', '2012-04-20 16:37:35', '$P$BRvbymi5t1hsBn3NmQ4HpRkSfXM5mL0'),
(38, 'pavel', 'pavel.flyweb@gmail.com', 1, 'Павел', 'Алексеевич', 'Веретенников', '89602452411', 'Админ', '2012-05-03 00:06:10', '2012-05-09 18:31:02', '192.168.137.121', '2012-05-09 16:31:02', '$P$B/fbEwCmlnQl5oyEaBIT6qE1qwm4LR.'),
(46, 'user_4', 'apstrigin@gmail.com', 1, 'Александр', 'Петрович', 'Стригин', '+79219844040', 'Агент', '2012-05-06 13:17:50', '2012-05-09 17:41:47', '192.168.137.1', '2012-05-09 15:41:47', '$P$BYKJH2LAd9.YOdUhMVaUYeTuUKd/0n/'),
(47, 'pavel2', 'olia@gmail.com', 1, 'Ольга', 'Олеговна', 'Маркес', '89602452411', 'Админ', '2012-05-06 14:17:32', '2012-05-06 14:17:53', '192.168.137.191', '2012-05-06 12:17:53', '$P$BCph3uCizp8vkE7jz4HY9HEnjZBzRd0'),
(48, 'pavel3', 'men@mail.ru', 1, 'Оксана', 'Викторовна', 'Исаева', '89602452411', 'Менеджер', '2012-05-06 14:23:52', '2012-05-06 14:23:52', '192.168.137.191', '2012-05-06 12:23:52', '$P$BhB2P92QnySYWovkTK8kQdZnYjtm6e1'),
(49, 'pavel_ag', 'vitek@milo.ru', 1, 'Виктор', 'Отчество', 'Кувшинов', '83334534535', 'Агент', '2012-05-06 14:42:03', '2012-05-06 14:42:13', '192.168.137.191', '2012-05-06 12:42:13', '$P$B5AnKZAuP50oaUerWrtAUV.s1kPDm21'),
(50, 'SoMeBody', 'smb_06@mail.ru', 1, 'Виктор', 'Семенович', 'Усанова', '+79119210360', 'Агент', '2012-05-07 19:59:09', '2012-05-07 19:59:18', '192.168.137.29', '2012-05-07 17:59:18', '$P$BQnuJg7.r0BWeWD1kz85sD3rrlh7zH.'),
(52, 'test', 'test@mail.ru', 1, 'Александр', 'Петрович', 'Стригин', '+79219844040', 'Админ', '2012-05-08 22:27:52', '2012-05-08 22:27:52', '192.168.137.1', '2012-05-08 20:27:52', '$P$Bq.VWcCbdboAiUE15kpZZ95arhkbt8.'),
(53, 'smakmybitchup', 'victor.kuvshinov@gmail.com', 1, 'Виктор', 'Андреевич', 'Кувшинов', '+79119210360', 'Админ', '2012-05-09 22:05:31', '2012-05-09 22:05:42', '192.168.137.205', '2012-05-09 20:05:42', '$P$BKE0iChnF8mB7GEoav9EukyqPkkVnx.');

-- --------------------------------------------------------

--
-- Структура таблицы `users_organizations`
--

CREATE TABLE IF NOT EXISTS `users_organizations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `user_id_org_id` (`user_id`,`org_id`),
  KEY `org_id` (`org_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Дамп данных таблицы `users_organizations`
--

INSERT INTO `users_organizations` (`id`, `user_id`, `org_id`) VALUES
(1, 1, 1),
(2, 2, 3),
(4, 3, 1),
(6, 5, 1),
(7, 6, 3),
(8, 7, 3),
(10, 18, 8),
(22, 33, 11),
(23, 34, 12),
(24, 35, 12),
(25, 36, 12),
(26, 37, 12),
(27, 38, 13),
(35, 46, 1),
(36, 47, 13),
(37, 48, 13),
(38, 49, 13),
(39, 50, 1),
(41, 52, 15),
(42, 53, 16);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `autologin_users`
--
ALTER TABLE `autologin_users`
  ADD CONSTRAINT `autologin_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `invites_users`
--
ALTER TABLE `invites_users`
  ADD CONSTRAINT `invites_users_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `managers_users`
--
ALTER TABLE `managers_users`
  ADD CONSTRAINT `managers_users_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `managers_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `metros_images_elements`
--
ALTER TABLE `metros_images_elements`
  ADD CONSTRAINT `metros_images_elements_ibfk_1` FOREIGN KEY (`metro_id`) REFERENCES `metros` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `metros_images_elements_ibfk_2` FOREIGN KEY (`metro_image_id`) REFERENCES `metros_images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders_metros`
--
ALTER TABLE `orders_metros`
  ADD CONSTRAINT `orders_metros_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_metros_ibfk_2` FOREIGN KEY (`metro_id`) REFERENCES `metros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders_regions`
--
ALTER TABLE `orders_regions`
  ADD CONSTRAINT `orders_regions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_regions_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders_users`
--
ALTER TABLE `orders_users`
  ADD CONSTRAINT `orders_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_users_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_ibfk_1` FOREIGN KEY (`ceo`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `regions_images_elements`
--
ALTER TABLE `regions_images_elements`
  ADD CONSTRAINT `regions_images_elements_ibfk_2` FOREIGN KEY (`region_image_id`) REFERENCES `regions_images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `regions_images_elements_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `settings_org`
--
ALTER TABLE `settings_org`
  ADD CONSTRAINT `settings_org_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users_organizations`
--
ALTER TABLE `users_organizations`
  ADD CONSTRAINT `users_organizations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_organizations_ibfk_2` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
