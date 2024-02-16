-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 16, 2024 at 07:09 PM
-- Server version: 5.7.35-38
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foxdevuz_transla`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

CREATE TABLE IF NOT EXISTS `active_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` varchar(35) NOT NULL,
  `menu` varchar(20) NOT NULL DEFAULT '',
  `step` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT 'admin',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `fromid`, `menu`, `step`, `status`, `created_at`) VALUES
(1, '2025653134', '', '', 'supperadmin', '2024-02-16 19:07:39');

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE IF NOT EXISTS `channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '1-Kanal',
  `target` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `join_request_channels`
--

CREATE TABLE IF NOT EXISTS `join_request_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sendAd`
--

CREATE TABLE IF NOT EXISTS `sendAd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `reply_markup` json NOT NULL,
  `toRus` tinyint(1) NOT NULL DEFAULT '1',
  `toUs` tinyint(1) NOT NULL DEFAULT '1',
  `toUz` tinyint(1) NOT NULL DEFAULT '1',
  `toNotSelectedLang` tinyint(1) NOT NULL DEFAULT '1',
  `toGroup` tinyint(1) NOT NULL DEFAULT '1',
  `sended_count` int(11) NOT NULL DEFAULT '0',
  `sended_user_count` varchar(255) NOT NULL DEFAULT '0',
  `send_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `sending_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(100) NOT NULL DEFAULT '',
  `chat_type` varchar(255) NOT NULL DEFAULT 'private',
  `lang` varchar(20) NOT NULL,
  `del` varchar(5) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data` text,
  `step` varchar(10) NOT NULL DEFAULT '',
  `full_name` varchar(255) NOT NULL DEFAULT '',
  `phone_1` varchar(255) NOT NULL DEFAULT '',
  `phone_2` varchar(255) NOT NULL,
  `direction` varchar(255) NOT NULL DEFAULT '',
  `course_at` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
