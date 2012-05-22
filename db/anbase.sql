-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 22 2012 г., 07:07
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `managers_users`
--

CREATE TABLE IF NOT EXISTS `managers_users` (
  `manager_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  KEY `idx_manager_id` (`manager_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `metros_images`
--

CREATE TABLE IF NOT EXISTS `metros_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `create_date` date NOT NULL DEFAULT '0000-00-00',
  `deal_type` enum('Куплю','Сниму','Сдам','Продам') NOT NULL DEFAULT 'Сдам',
  `price` decimal(12,0) unsigned NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `orders_metros`
--

CREATE TABLE IF NOT EXISTS `orders_metros` (
  `order_id` bigint(20) unsigned NOT NULL,
  `metro_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`order_id`,`metro_id`),
  KEY `metro_id` (`metro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `orders_regions`
--

CREATE TABLE IF NOT EXISTS `orders_regions` (
  `order_id` bigint(20) unsigned NOT NULL,
  `region_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`order_id`,`region_id`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `orders_users`
--

CREATE TABLE IF NOT EXISTS `orders_users` (
  `user_id` int(10) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  KEY `idx_user_id` (`user_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `regions_images`
--

CREATE TABLE IF NOT EXISTS `regions_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `forget_password_key` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_idx` (`login`),
  UNIQUE KEY `email_idx` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users_organizations`
--

CREATE TABLE IF NOT EXISTS `users_organizations` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_id` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `user_id_org_id` (`user_id`,`org_id`),
  KEY `org_id` (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  ADD CONSTRAINT `orders_metros_ibfk_2` FOREIGN KEY (`metro_id`) REFERENCES `metros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_metros_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders_regions`
--
ALTER TABLE `orders_regions`
  ADD CONSTRAINT `orders_regions_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_regions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `regions_images_elements_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `regions_images_elements_ibfk_2` FOREIGN KEY (`region_image_id`) REFERENCES `regions_images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
