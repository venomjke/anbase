-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 19 2012 г., 04:42
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('cd1f12c23c417c79c65c6f82021945a9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332127146, '');

-- --------------------------------------------------------

--
-- Структура таблицы `invites_users`
--

CREATE TABLE IF NOT EXISTS `invites_users` (
  `key_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `org_id` int(10) unsigned NOT NULL DEFAULT '0',
  `role` enum('Админ','Менеджер','Агент') NOT NULL DEFAULT 'Агент',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `manager_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`key_id`,`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1);

-- --------------------------------------------------------

--
-- Структура таблицы `organizations`
--

CREATE TABLE IF NOT EXISTS `organizations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `ceo` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ceo_id` (`ceo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `ceo`) VALUES
(1, 'org_1', 1),
(3, 'org_2', 2);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `activated`, `name`, `middle_name`, `last_name`, `phone`, `role`, `created`, `last_login`, `last_ip`, `modifed`, `password`) VALUES
(1, 'user_1', 'user_1@mail.ru', 1, 'user_1', 'user_1', 'user_1', 'user', 'Админ', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '2012-03-19 03:23:37', '12345678'),
(2, 'user_2', 'user_2@mail.ru', 1, 'user_2', 'user_2', 'user_2', 'user_2', 'Админ', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-19 03:26:46', '12345678'),
(3, 'user_3', 'user_3@mail.ru', 1, 'user_3', 'user_3', 'user_3', 'user_3', 'Менеджер', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-19 03:29:14', '12345678'),
(4, 'user_4', 'user_4@mail.ru', 1, 'user_4', 'user_4', 'user_4', 'user_4', 'Менеджер', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-19 03:29:31', '12345678'),
(5, 'user_5', 'user_5@mail.ru', 1, 'user_5', 'user_5', 'user_5', 'user_5', 'Агент', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-19 03:31:22', '12345678'),
(6, 'user_6', 'user_6@mail.ru', 1, 'user_6', 'user_6', 'user_6', 'user_6', 'Агент', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-19 03:31:22', '12345678'),
(7, 'user_7', 'user_7@mail.ru', 1, 'user_7', 'user_7', 'user_7', 'user_7', 'Агент', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-19 03:32:55', '12345678'),
(8, 'user_8', 'user_8@mail.ru', 1, 'user_8', 'user_8', 'user_8', 'user_8', 'Агент', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-19 03:32:55', '12345678');

-- --------------------------------------------------------

--
-- Структура таблицы `users_organizations`
--

CREATE TABLE IF NOT EXISTS `users_organizations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_org_id` (`user_id`,`org_id`),
  KEY `org_id` (`org_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `users_organizations`
--

INSERT INTO `users_organizations` (`id`, `user_id`, `org_id`) VALUES
(1, 1, 1),
(2, 2, 3),
(4, 3, 1),
(5, 4, 1),
(6, 5, 1),
(7, 6, 3),
(8, 7, 3),
(9, 8, 3);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_ibfk_1` FOREIGN KEY (`ceo`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users_organizations`
--
ALTER TABLE `users_organizations`
  ADD CONSTRAINT `users_organizations_ibfk_2` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_organizations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
