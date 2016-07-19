/*
SQLyog Enterprise - MySQL GUI v7.02 
MySQL - 5.6.21 : Database - madura_data
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`madura_data` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `madura_data`;

/*Table structure for table `panen` */

DROP TABLE IF EXISTS `panen`;

CREATE TABLE `panen` (
  `id_panen` int(11) NOT NULL AUTO_INCREMENT,
  `id_kabupaten` int(11) DEFAULT NULL,
  `id_kecamatan` int(11) DEFAULT NULL,
  `luas_panen` int(11) DEFAULT NULL,
  `produktivitas` int(11) DEFAULT NULL,
  `produksi` int(11) DEFAULT NULL,
  `jenis_tanaman` varchar(255) DEFAULT NULL,
  `tahun_data` year(4) DEFAULT NULL,
  PRIMARY KEY (`id_panen`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `panen` */

insert  into `panen`(`id_panen`,`id_kabupaten`,`id_kecamatan`,`luas_panen`,`produktivitas`,`produksi`,`jenis_tanaman`,`tahun_data`) values (2,3,3,888,11,89,'Jagung',2010),(3,3,3,2837,14,4061,'Jagung',2016),(4,3,4,2837,14,4061,'Jagung',2016),(5,3,3,2837,14,4061,'Jagung',2016);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
