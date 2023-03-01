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

/*Table structure for table `pr_signatories` */

DROP TABLE IF EXISTS `pr_signatories`;

CREATE TABLE `pr_signatories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `campus` int(11) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `title` varchar(55) DEFAULT NULL,
  `amount` int(255) DEFAULT NULL,
  `signatory_type` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `purchase_request` */

DROP TABLE IF EXISTS `purchase_request`;

CREATE TABLE `purchase_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campus` int(11) DEFAULT NULL,
  `project_code` int(255) DEFAULT NULL,
  `fund_source_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `pr_no` varchar(55) DEFAULT NULL,
  `responsibility_center_code` varchar(55) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `printed_name` int(55) DEFAULT NULL,
  `approving_officer` int(11) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `remark` varchar(255) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `disapproved_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `purchase_request_items` */

DROP TABLE IF EXISTS `purchase_request_items`;

CREATE TABLE `purchase_request_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campus` int(11) DEFAULT NULL,
  `pr_no` varchar(55) DEFAULT '0',
  `project_code` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `specification` text DEFAULT NULL,
  `file_name` varchar(55) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `routing_slip` */

DROP TABLE IF EXISTS `routing_slip`;

CREATE TABLE `routing_slip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_no` varchar(55) DEFAULT NULL,
  `employee_id` int(55) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `campus` int(11) DEFAULT NULL,
  `activity` int(11) DEFAULT NULL,
  `date_received` datetime DEFAULT NULL,
  `date_released` datetime DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `signed_purchase_request` */

DROP TABLE IF EXISTS `signed_purchase_request`;

CREATE TABLE `signed_purchase_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_no` varchar(55) DEFAULT NULL,
  `campus` int(55) DEFAULT NULL,
  `department_id` int(55) DEFAULT NULL,
  `employee_id` int(55) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
