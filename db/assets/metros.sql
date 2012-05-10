-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 07 2012 г., 08:24
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;