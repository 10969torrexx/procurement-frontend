/*
SQLyog Ultimate v12.09 (32 bit)
MySQL - 10.4.22-MariaDB : Database - procurement
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`procurement` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `procurement`;

/*Table structure for table `app_cse` */

DROP TABLE IF EXISTS `app_cse`;

CREATE TABLE `app_cse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `campus` int(11) DEFAULT NULL,
  `year_created` varchar(255) DEFAULT NULL,
  `project_category` int(11) DEFAULT NULL,
  `file_name` text DEFAULT NULL,
  `file_directory` text DEFAULT NULL,
  `app_cse` text DEFAULT NULL,
  `endorse` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `project_titles` */

DROP TABLE IF EXISTS `project_titles`;

CREATE TABLE `project_titles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `campus` int(11) DEFAULT NULL,
  `signatories_id` int(11) DEFAULT 1,
  `project_title` text DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `project_year` varchar(255) DEFAULT NULL,
  `fund_source` varchar(25) DEFAULT NULL,
  `allocated_budget` int(11) DEFAULT NULL,
  `immediate_supervisor` varchar(255) DEFAULT NULL,
  `project_category` int(11) DEFAULT NULL,
  `project_type` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `univ_wide_status` varchar(11) DEFAULT '--',
  `per_campus_status` varchar(11) DEFAULT '--',
  `remark` text DEFAULT NULL,
  `year_created` varchar(255) DEFAULT NULL,
  `endorse` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `signatories_app_non_cse` */

DROP TABLE IF EXISTS `signatories_app_non_cse`;

CREATE TABLE `signatories_app_non_cse` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  `Profession` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL DEFAULT '0',
  `Role` int(11) DEFAULT NULL,
  `project_category` varchar(11) DEFAULT '--',
  `Position` int(11) DEFAULT NULL,
  `status` varchar(11) DEFAULT '--',
  `univ_wide_status` varchar(11) DEFAULT '--',
  `campus` int(11) NOT NULL,
  `Year` int(11) NOT NULL,
  `pres_created_at` timestamp NULL DEFAULT NULL,
  `pres_updated_at` timestamp NULL DEFAULT NULL,
  `bac_committee_created_at` timestamp NULL DEFAULT NULL,
  `bac_committee_updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `signed_app` */

DROP TABLE IF EXISTS `signed_app`;

CREATE TABLE `signed_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `campus` int(11) DEFAULT NULL,
  `year_created` varchar(255) DEFAULT NULL,
  `project_category` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `file_name` text DEFAULT NULL,
  `file_directory` text DEFAULT NULL,
  `signed_app` text DEFAULT NULL,
  `endorse` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
