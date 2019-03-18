/*
SQLyog Ultimate v12.5.0 (64 bit)
MySQL - 5.7.16 : Database - bytrip
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `memberby` */

CREATE TABLE `memberby` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL,
  `nickname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `realname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hx_password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hx_username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `sex` int(11) unsigned DEFAULT NULL,
  `address_city` int(11) unsigned DEFAULT NULL,
  `address_province` int(11) unsigned DEFAULT NULL,
  `height` int(11) unsigned DEFAULT NULL,
  `weight` int(11) unsigned DEFAULT NULL,
  `bwh` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marriage` int(11) unsigned DEFAULT NULL,
  `education` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` int(11) unsigned DEFAULT NULL,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mood` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temperament` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speciality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isapprove` int(11) unsigned DEFAULT NULL,
  `type` int(11) unsigned DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `account` decimal(10,2) DEFAULT NULL,
  `score` int(11) unsigned DEFAULT NULL,
  `giftbox` int(11) unsigned DEFAULT NULL,
  `flower` int(11) unsigned DEFAULT NULL,
  `vip` int(11) unsigned DEFAULT NULL,
  `overdue_time` double DEFAULT NULL,
  `login` int(11) unsigned DEFAULT NULL,
  `reg_ip` tinyint(1) unsigned DEFAULT NULL,
  `reg_time` int(11) unsigned DEFAULT NULL,
  `last_login_ip` int(11) unsigned DEFAULT NULL,
  `last_login_time` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `travel_status` tinyint(1) unsigned DEFAULT NULL,
  `updatetime` tinyint(1) unsigned DEFAULT NULL,
  `video` tinyint(1) unsigned DEFAULT NULL,
  `approve_type` tinyint(1) unsigned DEFAULT NULL,
  `isfull` tinyint(1) unsigned DEFAULT NULL,
  `price` int(11) unsigned DEFAULT NULL,
  `login_type` int(11) unsigned DEFAULT NULL,
  `hot` int(11) unsigned DEFAULT NULL,
  `is_tan` int(11) unsigned DEFAULT NULL,
  `zskf` int(11) unsigned DEFAULT NULL,
  `htkt_time` tinyint(1) unsigned DEFAULT NULL,
  `is_read` tinyint(1) unsigned DEFAULT NULL,
  `is_recommend` tinyint(1) unsigned DEFAULT NULL,
  `face_img` int(11) unsigned DEFAULT NULL,
  `link_time` int(11) unsigned DEFAULT NULL,
  `index_sort` tinyint(1) unsigned DEFAULT NULL,
  `is_apply` tinyint(1) unsigned DEFAULT NULL,
  `gid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_top` int(11) unsigned DEFAULT NULL,
  `journey_num` tinyint(1) unsigned DEFAULT NULL,
  `online_lover_num` tinyint(1) unsigned DEFAULT NULL,
  `video_num` tinyint(1) unsigned DEFAULT NULL,
  `tourist_price` decimal(10,2) DEFAULT NULL,
  `cus_num` tinyint(1) unsigned DEFAULT NULL,
  `video_banyou_num` tinyint(1) unsigned DEFAULT NULL,
  `video_mm_num` tinyint(1) unsigned DEFAULT NULL,
  `free_date` tinyint(1) unsigned DEFAULT NULL,
  `rtime` int(11) unsigned DEFAULT NULL,
  `sort` int(11) unsigned DEFAULT NULL,
  `pid` tinyint(1) unsigned DEFAULT NULL,
  `setting` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `_face` int(11) unsigned DEFAULT NULL,
  `user_type` int(11) unsigned DEFAULT NULL,
  `age` int(11) unsigned DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_face` int(11) unsigned DEFAULT NULL,
  `is_bjby` int(11) unsigned DEFAULT NULL,
  `num` int(11) unsigned DEFAULT NULL,
  `main_pic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `membercontacts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) unsigned DEFAULT NULL,
  `create_time` int(11) unsigned DEFAULT NULL,
  `uid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type_number` (`type`,`uid`,`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*Table structure for table `membercontactsnologin` */

CREATE TABLE `membercontactsnologin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) unsigned DEFAULT NULL,
  `create_time` int(11) unsigned DEFAULT NULL,
  `uid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type_number` (`type`,`uid`,`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `memberlevel` */

CREATE TABLE `memberlevel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `overdue_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
