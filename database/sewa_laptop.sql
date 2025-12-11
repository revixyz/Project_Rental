-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: sewa_laptop
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_laptop`
--

DROP TABLE IF EXISTS `tb_laptop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_laptop` (
  `id_laptop` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `spesifikasi` text NOT NULL,
  `harga_per_hari` int DEFAULT NULL,
  `stok` int NOT NULL,
  `status` enum('Tersedia','Disewa') NOT NULL,
  `harga` int NOT NULL,
  PRIMARY KEY (`id_laptop`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_laptop`
--

LOCK TABLES `tb_laptop` WRITE;
/*!40000 ALTER TABLE `tb_laptop` DISABLE KEYS */;
INSERT INTO `tb_laptop` VALUES (3,'Lenovo ThinkPad E14','1765381520_Lenovo ThinkPad E14.jpeg','Prosesor: Intel Core i5-1235U\r\nRAM: 8GB DDR4\r\nStorage: 512GB SSD\r\nGPU: Intel Iris Xe\r\nLayar: 14\" Full HD\r\nKelebihan: Keyboard terbaik, cocok kerja profesional',NULL,0,'Disewa',75000),(4,'ASUS VivoBook 15','1765386259_ASUS VivoBook 15.jpeg','Prosesor: Intel Core i5-1135G7\r\nRAM: 8GB DDR4\r\nStorage: 512GB SSD\r\nGPU: Intel Iris Xe\r\nLayar: 15.6\" Full HD\r\nKelebihan: Ringan, cocok untuk kuliah & kerja harian',NULL,10,'Disewa',40000),(5,'HP Victus 16','1765386740_HP Victus 16.jpeg','Prosesor: AMD Ryzen 5 5600H\r\nRAM: 8GB DDR4\r\nStorage: 512GB SSD\r\nGPU: NVIDIA GTX 1650 / RTX 3050 (opsi)\r\nLayar: 16.1\" 144Hz\r\nKelebihan: Laptop gaming harga terjangkau',NULL,11,'Tersedia',67000),(6,'Acer Aspire 5','1765386824_Acer Aspire 5.jpeg','Prosesor: Intel Core i5-1240P\r\nRAM: 8GB DDR4\r\nStorage: 512GB SSD\r\nGPU: Intel Iris Xe\r\nLayar: 15.6\" Full HD\r\nKelebihan: Kuat untuk tugas kuliah & editing ringan',NULL,5,'Tersedia',88000),(7,'Apple MacBook Air M1','1765386885_Apple MacBook Air M1.jpeg','Prosesor: Apple M1\r\nRAM: 8GB\r\nStorage: 256GB SSD\r\nGPU: Integrated M1\r\nLayar: 13.3\" Retina\r\nKelebihan: Baterai super awet, performa halus',NULL,4,'Tersedia',120000),(8,'Axioo MyBook 14F','1765386942_Axioo MyBook 14F.jpeg','Prosesor: Intel Celeron N4020\r\nRAM: 8GB DDR4\r\nStorage: 256GB SSD\r\nGPU: Intel UHD Graphics\r\nLayar: 14\" Full HD\r\nKelebihan: Harga terjangkau, cocok untuk sekolah & tugas ringan',NULL,15,'Tersedia',56000),(9,'Dell Inspiron 15 3000','1765387006_Dell Inspiron 15 3000.jpeg','Prosesor: Intel Core i5-1135G7\r\nRAM: 8GB DDR4\r\nStorage: 256GB SSD\r\nGPU: Intel Iris Xe\r\nLayar: 15.6\" Full HD\r\nKelebihan: Build quality bagus, awet',NULL,6,'Tersedia',112000),(10,'Samsung Galaxy Book2','1765387057_Samsung Galaxy Book2.jpeg','Prosesor: Intel Core i5-1235U\r\nRAM: 8GB\r\nStorage: 512GB SSD\r\nGPU: Intel Iris Xe\r\nLayar: 15.6\" Full HD\r\nKelebihan: Ringan, integrasi bagus dengan HP Samsung',NULL,5,'Tersedia',70000),(11,'ASUS ROG Strix G15','1765387110_ASUS ROG Strix G15.jpeg','Prosesor: AMD Ryzen 7 6800H\r\nRAM: 16GB DDR5\r\nStorage: 512GB SSD\r\nGPU: NVIDIA RTX 3050\r\nLayar: 15.6\" 144Hz\r\nKelebihan: Gaming & editing berat lancar',NULL,2,'Tersedia',90000),(12,'Advan Workmate','1765387176_Advan Workmate.jpeg','Prosesor: (varian bisa Intel i3-1215U atau AMD Ryzen 5 3500U) \r\nRAM: 8 GB DDR4 \r\nStorage: 256 GB SSD \r\nLayar: 14″ IPS, resolusi 1920×1200 (rasio 16:10) \r\nKamera: 720p HD \r\nKelebihan: Ringan, cukup powerful untuk kerja/kuliah, mobilitas tinggi',NULL,4,'Tersedia',65000),(13,'Advan Soulmate 14\"','1765387241_Advan Soulmate 14.jpeg','Prosesor: Intel Gemini Lake N4020 \r\nRAM: 4 GB DDR4 (upgradable) \r\nStorage: 128 GB (upgradable) \r\nGPU: Intel Integrated Graphics \r\nLayar: 14″ HD (1366×768) \r\nKelebihan: Harga sangat terjangkau, cocok untuk tugas ringan (seperti mengetik, browsing, tugas sekolah)',NULL,9,'Tersedia',45000),(14,'Infinix Inbook X2 Gen 11','1765387324_WhatsApp Image 2025-12-10 at 13.49.36.jpeg','Prosesor: Intel Core i3-1115G4 \r\nRAM: 8 GB\r\nStorage: 256 GB SSD (varian ini) \r\nGPU: Intel UHD Graphics (integrated) \r\nLayar: 14″ Full HD IPS \r\nKelebihan: Bobot ringan (sekitar 1,24 kg) dan portabel — cocok untuk kuliah, kerja ringan, dan mobilitas tinggi.',NULL,8,'Tersedia',63000);
/*!40000 ALTER TABLE `tb_laptop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_pengembalian`
--

DROP TABLE IF EXISTS `tb_pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_pengembalian` (
  `id_pengembalian` int NOT NULL AUTO_INCREMENT,
  `id_pesanan` int NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `denda` int DEFAULT '0',
  `kondisi_laptop` varchar(100) DEFAULT 'Baik',
  `catatan_admin` text,
  `status` enum('Pending','Disetujui','Ditolak') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id_pengembalian`),
  KEY `id_pesanan` (`id_pesanan`),
  CONSTRAINT `tb_pengembalian_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `tb_pesanan` (`id_pesanan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_pengembalian`
--

LOCK TABLES `tb_pengembalian` WRITE;
/*!40000 ALTER TABLE `tb_pengembalian` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_pengembalian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_pesanan`
--

DROP TABLE IF EXISTS `tb_pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_pesanan` (
  `id_pesanan` int NOT NULL AUTO_INCREMENT,
  `id_laptop` int NOT NULL,
  `id_user` int NOT NULL,
  `tanggal_sewa` date NOT NULL,
  `durasi` int NOT NULL,
  `total_harga` int NOT NULL,
  `status` enum('Menunggu','Menunggu Pembayaran','Menunggu Konfirmasi','Disetujui','Ditolak','Masa Sewa Berakhir','Menunggu Pengembalian','Selesai') NOT NULL,
  `bukti` varchar(255) NOT NULL,
  `metode_bayar` varchar(20) DEFAULT NULL,
  `rekening_tujuan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_pesanan`),
  KEY `id_laptop` (`id_laptop`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `tb_pesanan_ibfk_1` FOREIGN KEY (`id_laptop`) REFERENCES `tb_laptop` (`id_laptop`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_pesanan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_pesanan`
--

LOCK TABLES `tb_pesanan` WRITE;
/*!40000 ALTER TABLE `tb_pesanan` DISABLE KEYS */;
INSERT INTO `tb_pesanan` VALUES (11,4,1,'2025-12-11',2,80000,'Disetujui','top_tracks_short_term.png','BCA','1234567890 (a.n Rental Laptop)'),(12,4,1,'2025-12-09',1,40000,'Masa Sewa Berakhir','top_tracks_short_term.png','BCA','1234567890 (a.n Rental Laptop)');
/*!40000 ALTER TABLE `tb_pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_user`
--

DROP TABLE IF EXISTS `tb_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_user`
--

LOCK TABLES `tb_user` WRITE;
/*!40000 ALTER TABLE `tb_user` DISABLE KEYS */;
INSERT INTO `tb_user` VALUES (1,'revina','revina@gmail.com','revina','user'),(2,'Indah','indah@gmail.com','indah','admin'),(5,'Admin','admin@gmail.com','admin','admin'),(6,'User','user@gmail.com','user','user');
/*!40000 ALTER TABLE `tb_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-12  3:49:01
