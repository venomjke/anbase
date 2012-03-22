-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 22 2012 г., 21:38
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

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

--
-- Дамп данных таблицы `autologin_users`
--

INSERT INTO `autologin_users` (`key_id`, `user_id`, `user_agent`, `last_ip`, `last_login`) VALUES
('eac4839bee79898636a4166e04d1d61e', 18, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', '127.0.0.1', '2012-03-22 18:26:07');

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
  PRIMARY KEY (`key_id`,`org_id`),
  KEY `idx_org_id` (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `metros`
--

CREATE TABLE IF NOT EXISTS `metros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `metros`
--

INSERT INTO `metros` (`id`, `name`) VALUES
(1, 'Купчино'),
(2, 'Звездная');

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
(3);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` enum('Жилая недвижимость','Коммерческая недвижимость','Загородная недвижимость') NOT NULL DEFAULT 'Жилая недвижимость',
  `deal_type` enum('Куплю','Сниму','Сдам','Продам') NOT NULL DEFAULT 'Сдам',
  `region_id` int(10) unsigned NOT NULL,
  `metro_id` int(10) unsigned NOT NULL,
  `price` float unsigned NOT NULL DEFAULT '0',
  `description` mediumtext NOT NULL,
  `delegate_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `finish_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `phone` varchar(20) NOT NULL,
  `state` enum('on','off') NOT NULL DEFAULT 'on',
  `org_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_number` (`number`),
  KEY `idx_metro_id` (`metro_id`),
  KEY `idx_region_id` (`region_id`),
  KEY `idx_org_id` (`org_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `number`, `create_date`, `category`, `deal_type`, `region_id`, `metro_id`, `price`, `description`, `delegate_date`, `finish_date`, `phone`, `state`, `org_id`) VALUES
(1, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\r\nБла бла бла бла бла бла <br/>\r\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(2, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(3, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(4, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(5, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(6, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(7, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(8, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(9, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(10, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 1, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 8),
(11, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1),
(12, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1),
(13, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1),
(14, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1),
(15, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1),
(16, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1),
(17, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1),
(18, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1),
(19, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1),
(20, 1000, '0000-00-00 00:00:00', 'Жилая недвижимость', 'Сдам', 2, 1, 10000, 'бла бла бла бла бла бла бла ла <br/>\nБла бла бла бла бла бла <br/>\nблабл ла ла бла <br/>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '+79219844040', 'on', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `orders_users`
--

INSERT INTO `orders_users` (`id`, `user_id`, `order_id`) VALUES
(1, 5, 11),
(2, 5, 12),
(3, 5, 13),
(4, 5, 14),
(5, 6, 15),
(6, 6, 16);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `ceo`) VALUES
(1, 'org_1', 1),
(3, 'org_2', 2),
(8, 'ООО "Агентство Недвижимости"', 18);

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `regions`
--

INSERT INTO `regions` (`id`, `name`) VALUES
(1, 'Фрунзенский'),
(2, 'Адмиралтейский');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `activated`, `name`, `middle_name`, `last_name`, `phone`, `role`, `created`, `last_login`, `last_ip`, `modifed`, `password`) VALUES
(1, 'user_1', 'user_1@mail.ru', 1, 'user_1', 'user_1', 'user_1', 'user', 'Админ', '0000-00-00 00:00:00', '2012-03-22 17:41:48', '127.0.0.1', '2012-03-22 16:41:48', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(2, 'user_2', 'user_2@mail.ru', 1, 'user_2', 'user_2', 'user_2', 'user_2', 'Админ', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-20 02:56:28', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(3, 'user_3', 'user_3@mail.ru', 1, 'user_3', 'user_3', 'user_3', 'user_3', 'Менеджер', '0000-00-00 00:00:00', '2012-03-20 06:35:20', '127.0.0.1', '2012-03-20 05:35:20', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(4, 'user_4', 'user_4@mail.ru', 1, 'user_4', 'user_4', 'user_4', 'user_4', 'Менеджер', '0000-00-00 00:00:00', '2012-03-21 09:02:11', '127.0.0.1', '2012-03-21 08:02:11', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(5, 'user_5', 'user_5@mail.ru', 1, 'user_5', 'user_5', 'user_5', 'user_5', 'Агент', '0000-00-00 00:00:00', '2012-03-21 08:15:28', '127.0.0.1', '2012-03-21 07:15:28', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(6, 'user_6', 'user_6@mail.ru', 1, 'user_6', 'user_6', 'user_6', 'user_6', 'Агент', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-20 02:56:28', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(7, 'user_7', 'user_7@mail.ru', 1, 'user_7', 'user_7', 'user_7', 'user_7', 'Агент', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '192.168.0.12', '2012-03-20 02:56:28', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(9, 'user_9', 'user_9@mail.ru', 1, 'user_9', 'user_9', 'user_9', 'user_9', 'Агент', '2012-03-19 16:23:34', '2012-03-19 16:23:34', '', '2012-03-20 02:56:28', '$P$Bz75TcMDQZ3Lm13WO7NKIelvOA6E3i/'),
(18, 'alex', 'alex@gmail.com', 1, 'Александр', 'Петрович', 'Стригин', '+79219844040', 'Админ', '2012-03-22 19:25:43', '2012-03-22 19:26:07', '127.0.0.1', '2012-03-22 18:26:07', '$P$BRJbHz9mY11vxXw3OHXf9ptrXLBs.d0');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

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
(10, 18, 8);

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
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`),
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`metro_id`) REFERENCES `metros` (`id`);

--
-- Ограничения внешнего ключа таблицы `orders_users`
--
ALTER TABLE `orders_users`
  ADD CONSTRAINT `orders_users_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
