/*
SQLyog Enterprise - MySQL GUI v7.02 
MySQL - 5.6.21 : Database - banyuwangi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`banyuwangi` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `banyuwangi`;

/*Table structure for table `bahan_makanan` */

DROP TABLE IF EXISTS `bahan_makanan`;

CREATE TABLE `bahan_makanan` (
  `id_bahan_makanan` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_tanaman` varchar(255) DEFAULT NULL,
  `luas_panen` double DEFAULT NULL,
  `produktivitas` double DEFAULT NULL,
  `produksi` double DEFAULT NULL,
  `waktu` date DEFAULT NULL,
  PRIMARY KEY (`id_bahan_makanan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `bahan_makanan` */

/*Table structure for table `buah_buahan` */

DROP TABLE IF EXISTS `buah_buahan`;

CREATE TABLE `buah_buahan` (
  `id_bahan_makanan` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_tanaman` varchar(255) DEFAULT NULL,
  `luas_panen` double DEFAULT NULL,
  `produktivitas` double DEFAULT NULL,
  `produksi` double DEFAULT NULL,
  `waktu` date DEFAULT NULL,
  PRIMARY KEY (`id_bahan_makanan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `buah_buahan` */

/*Table structure for table `map_role_pengguna` */

DROP TABLE IF EXISTS `map_role_pengguna`;

CREATE TABLE `map_role_pengguna` (
  `PENGGUNA_ID` int(11) NOT NULL,
  `ROLE_ID` int(11) NOT NULL,
  PRIMARY KEY (`PENGGUNA_ID`,`ROLE_ID`),
  KEY `FK_map_role_pengguna_role` (`ROLE_ID`),
  CONSTRAINT `FK_map_role_pengguna_pengguna` FOREIGN KEY (`PENGGUNA_ID`) REFERENCES `pengguna` (`PENGGUNA_ID`),
  CONSTRAINT `FK_map_role_pengguna_role` FOREIGN KEY (`ROLE_ID`) REFERENCES `role` (`ROLE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `map_role_pengguna` */

insert  into `map_role_pengguna`(`PENGGUNA_ID`,`ROLE_ID`) values (1,1),(4,1),(7,1),(8,1),(1,2),(4,2),(7,2),(1,3),(7,3),(8,3),(1,4),(7,4),(8,4),(1,5),(4,5),(7,5),(1,6),(7,6);

/*Table structure for table `pengguna` */

DROP TABLE IF EXISTS `pengguna`;

CREATE TABLE `pengguna` (
  `PENGGUNA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PENGGUNA_NAMA` varchar(50) DEFAULT NULL,
  `PENGGUNA_USERNAME` varchar(50) DEFAULT NULL,
  `PENGGUNA_PASSWORD` varchar(50) DEFAULT '25d55ad283aa400af464c76d713c07ad',
  PRIMARY KEY (`PENGGUNA_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `pengguna` */

insert  into `pengguna`(`PENGGUNA_ID`,`PENGGUNA_NAMA`,`PENGGUNA_USERNAME`,`PENGGUNA_PASSWORD`) values (1,'ADMIN','admin','25d55ad283aa400af464c76d713c07ad'),(4,'satu','satu','27946274a201346f0322e3861909b5ff'),(5,'dua','dua','a319360336c8cac32102f4dffbee4260'),(6,'tiga','tiga','ca0e90458e0fa1ec4b91cd5ce243f25f'),(7,'test','test','9533edc0bac7aa4b947576a01f6f4fab'),(8,'test2','test2','e9287a53b94620249766921107fe70a3');

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `ROLE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ROLE_NAME` varchar(50) DEFAULT NULL,
  `ROLE_CODE` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ROLE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `role` */

insert  into `role`(`ROLE_ID`,`ROLE_NAME`,`ROLE_CODE`) values (1,'Menambahkan Pengguna','add_user'),(2,'Menghapus Pengguna','delete_user'),(3,'Mengubah Pengguna','edit_user'),(4,'Melihat Pengguna','view_user'),(5,'Menambah data penduduk','add_penduduk'),(6,'Mengubah data penduduk','edit_penduduk');

/*Table structure for table `sayur_sayuran` */

DROP TABLE IF EXISTS `sayur_sayuran`;

CREATE TABLE `sayur_sayuran` (
  `id_bahan_makanan` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_tanaman` varchar(255) DEFAULT NULL,
  `luas_panen` double DEFAULT NULL,
  `produktivitas` double DEFAULT NULL,
  `produksi` double DEFAULT NULL,
  `waktu` date DEFAULT NULL,
  PRIMARY KEY (`id_bahan_makanan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sayur_sayuran` */

/*Table structure for table `tanaman_perkebunan` */

DROP TABLE IF EXISTS `tanaman_perkebunan`;

CREATE TABLE `tanaman_perkebunan` (
  `id_bahan_makanan` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_tanaman` varchar(255) DEFAULT NULL,
  `luas_panen` double DEFAULT NULL,
  `produktivitas` double DEFAULT NULL,
  `produksi` double DEFAULT NULL,
  `waktu` date DEFAULT NULL,
  PRIMARY KEY (`id_bahan_makanan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tanaman_perkebunan` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
