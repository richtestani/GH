# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Database: RA073
# Generation Time: 2016-06-03 21:13:40 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `categorytype` varchar(100) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;

INSERT INTO `categories` (`id`, `category`, `parent`, `lft`, `rgt`, `created_on`, `modified_on`, `created_by`, `modified_by`, `categorytype`, `slug`, `description`)
VALUES
	(1,'pages',0,0,1,'2016-01-20 00:00:00','2016-01-20 13:47:46',1,1,'root','page','');

/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table imagelinks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `imagelinks`;

CREATE TABLE `imagelinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `image_id` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `imagelinks` WRITE;
/*!40000 ALTER TABLE `imagelinks` DISABLE KEYS */;

INSERT INTO `imagelinks` (`id`, `item_id`, `image_id`, `created_on`, `type`)
VALUES
	(4,5,'574c4def5709d','2016-06-03 09:24:41','product');

/*!40000 ALTER TABLE `imagelinks` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `images`;

CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `caption` text NOT NULL,
  `ext` varchar(5) NOT NULL,
  `mime` varchar(25) NOT NULL,
  `size` int(11) NOT NULL,
  `public_path` text NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `dimensions` varchar(20) NOT NULL,
  `unit` varchar(5) NOT NULL,
  `uid` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `category` int(11) NOT NULL,
  `default_image` varchar(255) NOT NULL,
  `published_on` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `template` varchar(100) NOT NULL,
  `pagetype` varchar(50) NOT NULL,
  `published` int(11) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `title`, `body`, `category`, `default_image`, `published_on`, `status`, `created_on`, `modified_on`, `created_by`, `modified_by`, `slug`, `template`, `pagetype`, `published`, `subtitle`, `views`)
VALUES
	(1,'Welcome to RichAPP CMS','A sample page to edit or delete.',0,'0','2016-05-28 10:32:03',1,'2016-05-28 10:32:03','2016-05-28 10:32:03',0,1,'welcome-to-richapp-cms','','page',1,'A Powerful CMS for developers and users',0);

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `inputtype` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  `inputvalues` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;

INSERT INTO `settings` (`id`, `name`, `value`, `created_on`, `modified_on`, `created_by`, `modified_by`, `inputtype`, `class`, `inputvalues`, `description`)
VALUES
	(1,'x-small','100','2016-01-20 00:00:00','2016-01-20 12:48:31',1,1,'text','image','',''),
	(2,'small','150','2016-01-20 00:00:00','2016-01-20 12:48:31',1,1,'text','image','',''),
	(3,'medium','350','2016-01-20 00:00:00','2016-01-20 13:30:35',1,1,'text','image','',''),
	(4,'large','600','2016-01-20 00:00:00','2016-01-20 13:30:35',1,1,'text','image','',''),
	(5,'x-large','900','2016-01-20 00:00:00','2016-01-20 13:32:34',1,1,'text','image','',''),
	(6,'current_theme','marketplace','2016-01-20 00:00:00','2016-05-29 13:24:42',1,1,'select','theme','\\RichApp\\Core\\Settings::menuCallback(/public/themes)','Choose a Theme'),
	(7,'admin_theme','default','2016-01-20 00:00:00','2016-05-29 13:24:42',1,1,'select','theme','\\RichApp\\Core\\Settings::pathsMenu(/RichApp/Admin/Themes)','Choose an Admin Theme'),
	(8,'theme_path','themes/','2016-01-20 00:00:00','2016-05-29 13:24:42',1,1,'text','theme','',''),
	(9,'num_posts_per_page','15','2016-01-20 00:00:00','2016-01-20 13:46:33',1,1,'text','pages','',''),
	(10,'package','Marketplace','2016-01-20 00:00:00','2016-05-29 13:25:02',1,1,'text','app','',''),
	(11,'thumbnail','55','2016-01-20 00:00:00','2016-05-28 10:41:40',1,1,'text','image','',''),
	(12,'version','0.8.1','2016-01-20 00:00:00','2016-05-28 10:41:40',1,1,'text','app','','');

/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table taglinks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `taglinks`;

CREATE TABLE `taglinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `taglinks` WRITE;
/*!40000 ALTER TABLE `taglinks` DISABLE KEYS */;

INSERT INTO `taglinks` (`id`, `tag_id`, `item_id`)
VALUES
	(1,1,1);

/*!40000 ALTER TABLE `taglinks` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;

INSERT INTO `tags` (`id`, `tag`, `slug`, `created_on`, `modified_on`, `created_by`, `modified_by`)
VALUES
	(1,'demo','demo','2016-05-28 10:32:03','2016-05-28 10:32:03',1,0);

/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first` varchar(255) NOT NULL,
  `last` varchar(255) NOT NULL,
  `active` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `last_login` datetime NOT NULL,
  `activation` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
