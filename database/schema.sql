/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: db_sewelas
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `role` enum('admin','owner','karyawan') NOT NULL,
  `aksi` varchar(50) NOT NULL COMMENT 'Tipe aksi',
  `deskripsi` text NOT NULL COMMENT 'Detail perubahan',
  `no_spk` varchar(50) DEFAULT NULL COMMENT 'Referensi SPK',
  `data_lama` text DEFAULT NULL COMMENT 'JSON data lama',
  `data_baru` text DEFAULT NULL COMMENT 'JSON data baru',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_log`),
  KEY `idx_user` (`id_user`),
  KEY `idx_aksi` (`aksi`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3216 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id_cust` int(11) NOT NULL AUTO_INCREMENT,
  `nama_c` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `kota` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_cust`)
) ENGINE=InnoDB AUTO_INCREMENT=1206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `id_inv` int(11) NOT NULL AUTO_INCREMENT,
  `ukuran` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `satuan` varchar(20) DEFAULT NULL,
  `tipe` enum('kusen_m','kusen_pivot') DEFAULT 'kusen_m',
  PRIMARY KEY (`id_inv`),
  KEY `jenis_kaca` (`ukuran`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `master_hpp`
--

DROP TABLE IF EXISTS `master_hpp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_hpp` (
  `id_hpp` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_kaca` varchar(50) DEFAULT NULL,
  `warna_kusen` varchar(50) DEFAULT NULL,
  `ukuran` varchar(11) DEFAULT NULL,
  `kode_produk` varchar(10) NOT NULL,
  `total_biaya_modal` decimal(12,2) DEFAULT NULL,
  `biaya_kaca` decimal(12,2) DEFAULT NULL,
  `biaya_kusen_lengkung` int(11) DEFAULT NULL,
  `biaya_kusen_lurus` int(11) DEFAULT 0,
  `biaya_b_6cm` int(11) DEFAULT 0,
  `biaya_ornamen` decimal(12,2) DEFAULT NULL,
  `biaya_packing` decimal(12,2) DEFAULT NULL,
  `biaya_tenaga` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id_hpp`),
  KEY `jenis_kaca` (`jenis_kaca`,`warna_kusen`,`ukuran`)
) ENGINE=InnoDB AUTO_INCREMENT=1563 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monitoring`
--

DROP TABLE IF EXISTS `monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `monitoring` (
  `id_mon` int(11) NOT NULL AUTO_INCREMENT,
  `no_spk` varchar(20) DEFAULT NULL,
  `status_pengerjaan` enum('Antri','Produksi','Packing','Dikirim','Diterima','Cancel') NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_mon`),
  KEY `no_spk` (`no_spk`),
  CONSTRAINT `monitoring_ibfk_1` FOREIGN KEY (`no_spk`) REFERENCES `orders` (`no_spk`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1197 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `no_spk` varchar(20) NOT NULL,
  `tanggal_order` date DEFAULT NULL,
  `tgl_input` timestamp NULL DEFAULT NULL,
  `id_cust` int(11) DEFAULT NULL,
  `id_hpp` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `harga_jual_satuan` decimal(12,2) DEFAULT NULL,
  `hpp_saat_ini` decimal(12,2) DEFAULT NULL,
  `total_harga_jual` decimal(12,2) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `total_harga` decimal(12,2) DEFAULT NULL,
  `ekspedisi` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`no_spk`),
  KEY `id_cust` (`id_cust`),
  KEY `id_hpp` (`id_hpp`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_cust`) REFERENCES `customers` (`id_cust`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`id_hpp`) REFERENCES `master_hpp` (`id_hpp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin','owner','karyawan') NOT NULL,
  `firebase_uid` varchar(128) DEFAULT NULL,
  `google_email` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `firebase_uid` (`firebase_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-27  0:36:54
