-- --------------------------------------------------------
-- Hostitel:                     127.0.0.1
-- Verze serveru:                5.7.33 - MySQL Community Server (GPL)
-- OS serveru:                   Win64
-- HeidiSQL Verze:               11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Exportování struktury databáze pro
CREATE DATABASE IF NOT EXISTS `test-db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci */;
USE `test-db`;

-- Exportování struktury pro tabulka test-db.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `name_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `task_id` (`task_id`),
  KEY `name_id` (`name_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`name_id`) REFERENCES `login` (`id_users`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Exportování dat pro tabulku test-db.comments: ~7 rows (přibližně)
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` (`id`, `task_id`, `name_id`, `content`, `created_at`) VALUES
	(1, 2, 1, 'Great idea', '2022-03-15 23:28:40'),
	(2, 3, 1, 'Yea', '2022-03-15 23:32:27'),
	(3, 4, 1, 'Yea?', '2022-03-15 23:35:57'),
	(4, 5, 1, 'Okay', '2022-03-15 23:38:57'),
	(5, 14, 1, 'Im trying', '2022-03-15 23:40:08'),
	(6, 15, 1, 'Nah', '2022-03-15 23:41:11'),
	(7, 2, 1, 'Will try', '2022-03-15 23:57:33');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

-- Exportování struktury pro tabulka test-db.login
CREATE TABLE IF NOT EXISTS `login` (
  `id_users` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `email` varchar(319) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_users`) USING BTREE,
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci AVG_ROW_LENGTH=300;

-- Exportování dat pro tabulku test-db.login: ~3 rows (přibližně)
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
INSERT INTO `login` (`id_users`, `name`, `password`, `email`, `role`) VALUES
	(1, 'Veronika', '$2y$10$/jayKafN4pxIfb5PiL6bH.Ko33lvdBGx1fUkJvNMEuvNB8hW3nVPm', 'veronicationhurt@gmail.com', 'admin'),
	(2, 'Veronication', '$2y$10$/jayKafN4pxIfb5PiL6bH.Ko33lvdBGx1fUkJvNMEuvNB8hW3nVPm', 'veronikahurtov@seznam.cz', 'admin'),
	(3, 'Admin', '$2y$10$/jayKafN4pxIfb5PiL6bH.Ko33lvdBGx1fUkJvNMEuvNB8hW3nVPm', 'veronticka9@gmail.com', 'admin');
/*!40000 ALTER TABLE `login` ENABLE KEYS */;

-- Exportování struktury pro tabulka test-db.taskcategory
CREATE TABLE IF NOT EXISTS `taskcategory` (
  `id_taskcategory` int(11) NOT NULL AUTO_INCREMENT,
  `nameOfTasksCategory` varchar(255) NOT NULL,
  PRIMARY KEY (`id_taskcategory`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Exportování dat pro tabulku test-db.taskcategory: ~3 rows (přibližně)
/*!40000 ALTER TABLE `taskcategory` DISABLE KEYS */;
INSERT INTO `taskcategory` (`id_taskcategory`, `nameOfTasksCategory`) VALUES
	(1, 'Updates'),
	(2, 'Random'),
	(3, 'Testing');
/*!40000 ALTER TABLE `taskcategory` ENABLE KEYS */;

-- Exportování struktury pro tabulka test-db.tasks
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientId` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deadline` date NOT NULL,
  `idtaskcategory` int(11) NOT NULL,
  `idusers` int(11) NOT NULL,
  `idtaskstatus` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK3` (`idtaskstatus`),
  KEY `FK4` (`idtaskcategory`),
  KEY `FK5` (`idusers`),
  CONSTRAINT `FK3` FOREIGN KEY (`idtaskstatus`) REFERENCES `taskstatus` (`id_status`),
  CONSTRAINT `FK4` FOREIGN KEY (`idtaskcategory`) REFERENCES `taskcategory` (`id_taskcategory`),
  CONSTRAINT `FK5` FOREIGN KEY (`idusers`) REFERENCES `login` (`id_users`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Exportování dat pro tabulku test-db.tasks: ~14 rows (přibližně)
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` (`id`, `clientId`, `content`, `created_at`, `deadline`, `idtaskcategory`, `idusers`, `idtaskstatus`, `title`) VALUES
	(2, 1, 'Find some template and create simple CRM', '2022-03-01 12:44:52', '2022-03-25', 2, 2, 1, 'Update the website for Diana'),
	(3, 1, 'For his bday', '2022-03-01 13:12:49', '2022-03-18', 3, 1, 2, 'Visit brother'),
	(4, 1, 'AdsasD', '2022-03-01 13:15:16', '2022-03-01', 3, 3, 3, 'Testing'),
	(5, 1, 'Set limit and after click show them all', '2022-03-01 13:17:08', '2022-03-30', 3, 1, 3, 'Display only few tasks'),
	(6, 1, 'Send email after new task is created...', '2022-03-01 13:17:28', '2022-04-03', 1, 3, 1, 'Sending e-mails'),
	(7, 1, 'Display only mine', '2022-03-01 13:17:35', '2022-04-06', 3, 3, 2, 'Select only mine tasks'),
	(8, 1, 'Try microsite', '2022-03-01 19:53:11', '2022-03-01', 3, 3, 1, 'Create microsite'),
	(9, 3, 'ASd', '2022-03-02 21:53:27', '2022-03-01', 3, 1, 2, 'Lorem ipsum'),
	(10, 3, 'Probably write to superman', '2022-03-02 23:19:27', '2022-03-01', 3, 1, 2, 'Find new music'),
	(11, 1, 'Create comment section with new db', '2022-03-03 00:16:47', '2022-03-16', 2, 1, 2, 'Create comment section'),
	(12, 1, 'Do something with roles', '2022-03-03 00:17:14', '2022-03-26', 2, 3, 1, 'Set roles'),
	(13, 1, 'Create campains and earn money', '2022-03-03 00:17:39', '2022-03-18', 2, 1, 2, 'Automatic newsletters'),
	(14, 1, 'to your gitproject', '2022-03-03 00:18:29', '2022-04-07', 2, 3, 3, 'Make more contributions'),
	(15, 1, 'Scraper for instagram followers', '2022-03-06 22:45:20', '2022-03-25', 1, 2, 1, 'Create script for insta followers');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;

-- Exportování struktury pro tabulka test-db.taskstatus
CREATE TABLE IF NOT EXISTS `taskstatus` (
  `id_status` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id_status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Exportování dat pro tabulku test-db.taskstatus: ~3 rows (přibližně)
/*!40000 ALTER TABLE `taskstatus` DISABLE KEYS */;
INSERT INTO `taskstatus` (`id_status`, `status`) VALUES
	(1, 'New'),
	(2, 'In proccess'),
	(3, 'Done');
/*!40000 ALTER TABLE `taskstatus` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
