-- MariaDB dump 10.19  Distrib 10.6.5-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: efa
-- ------------------------------------------------------
-- Server version	10.6.5-MariaDB

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
-- Table structure for table `branch`
--

DROP TABLE IF EXISTS `branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `manager` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adress` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BB861B1F19EB6921` (`client_id`),
  KEY `IDX_BB861B1FFED90CCA` (`permission_id`),
  CONSTRAINT `FK_BB861B1F19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  CONSTRAINT `FK_BB861B1FFED90CCA` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch`
--

LOCK TABLES `branch` WRITE;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` VALUES (10,16,27,0,NULL,'2022-09-19 21:11:40','havikor@gmail.com',NULL,'40 Rue Chapon 75400 Paris',NULL),(22,16,40,1,'05-6333611bb7748447068557.jpg','2022-09-26 13:28:15','mechbalradouan88@gmail.com',NULL,'45 Boulevard Raspail 74900 Marseille','2022-09-27 22:46:19'),(25,16,43,1,'08-6333610ab798f459205635.jpg','2022-09-26 20:31:40','mechbalradouanee88@gmail.com',NULL,'45 Boulevard Raspail','2022-09-27 22:46:02'),(27,16,45,1,'bissat-b-633360fa7eb51573433971.jpg','2022-09-26 20:33:02','mechbalradouadddn88@gmail.com',NULL,'3 Avenue de la Porte d\'Auteuil 74500 Paris','2022-09-27 22:45:46'),(28,16,48,1,'4-633362e41d297842883012.jpg','2022-09-27 21:59:24','mechbalradoedzedzuan88@gmail.com',NULL,'najib flatamakedzùe;dzedzed','2022-09-27 22:53:56');
/*!40000 ALTER TABLE `branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `short_desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_desc` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dpo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `technical_contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commercial_contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (8,'zedzed',1,NULL,NULL,NULL,NULL,NULL,NULL,'klazdaz@fr.fr','kakasssss@gx.fr','2022-09-17 22:30:01'),(9,'azdaz',1,NULL,NULL,NULL,NULL,NULL,NULL,'marie.flataddd.2016@gmail.com','kakasssss@gx.fr','2022-09-17 22:30:13'),(10,'zedzedzed',0,NULL,NULL,'4-633362d628f9d617990267.jpg','2022-09-27 22:53:42',NULL,NULL,'marie.flatsssa.2016@gmail.com','klazdaz@fr.fr','2022-09-17 22:30:30'),(11,'zedzed',0,NULL,NULL,'2-633362cb59481898198986.jpg','2022-09-27 22:53:31',NULL,NULL,'marie.flazedzedzeta.2016@gmail.com','najib@hde.fr','2022-09-17 22:30:46'),(16,'najib flata',1,NULL,NULL,'3-633362c1b80a2620338383.jpg','2022-09-27 22:53:21',NULL,NULL,'marie.flata.2016@gmail.com','klazdaz@fr.fr','2022-09-18 01:13:11'),(18,'zedzedzed',1,'hello world','éedé',NULL,NULL,NULL,'azdazd','najib@azdazd.fr','kakassseess@gx.fr','2022-09-27 22:34:22'),(19,'zedzedzed',1,'hello world','zedzed','1-633362b93699d720248933.jpg','2022-09-27 22:53:13','http://najib.com',NULL,'najeeeib@azdazd.fr','kakasssss@gx.fr','2022-09-27 22:34:53');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20220917105755','2022-09-17 12:57:58',44),('DoctrineMigrations\\Version20220926095730','2022-09-26 11:57:38',72),('DoctrineMigrations\\Version20220926101042','2022-09-26 12:10:50',46),('DoctrineMigrations\\Version20220926103740','2022-09-26 12:37:46',49),('DoctrineMigrations\\Version20220926104913','2022-09-26 12:49:22',47),('DoctrineMigrations\\Version20220926184051','2022-09-26 20:41:00',524),('DoctrineMigrations\\Version20220926184209','2022-09-26 20:42:14',56),('DoctrineMigrations\\Version20220926204245','2022-09-26 22:42:51',585),('DoctrineMigrations\\Version20220927202653','2022-09-27 22:27:00',182);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `read_resa` tinyint(1) NOT NULL,
  `edit_resa` tinyint(1) NOT NULL,
  `remove_resa` tinyint(1) NOT NULL,
  `read_payment` tinyint(1) NOT NULL,
  `edit_payment` tinyint(1) NOT NULL,
  `manage_drink` tinyint(1) NOT NULL,
  `add_sub` tinyint(1) NOT NULL,
  `edit_sub` tinyint(1) NOT NULL,
  `remove_sub` tinyint(1) NOT NULL,
  `manage_schedules` tinyint(1) NOT NULL,
  `branch` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (1,1,0,1,1,0,1,0,0,0,0,0),(13,0,0,0,0,0,0,0,0,0,0,0),(14,0,0,0,0,0,0,0,0,0,0,0),(15,1,0,0,0,0,0,0,1,0,0,0),(16,0,0,0,0,0,0,0,0,0,0,0),(24,0,1,0,0,0,1,0,1,1,0,0),(25,0,0,0,0,0,0,0,0,0,0,1),(26,0,0,0,0,0,0,0,0,0,0,1),(27,0,0,0,0,0,0,0,0,0,0,1),(28,1,0,0,0,0,1,0,0,0,0,1),(29,0,1,0,0,0,0,0,0,0,0,1),(32,0,1,0,0,0,1,0,1,1,0,1),(33,0,1,0,0,0,1,0,1,1,0,1),(34,0,1,0,0,0,1,0,1,1,0,1),(35,0,1,0,0,0,1,0,1,1,0,1),(36,0,1,0,0,0,1,0,1,1,0,1),(37,0,1,0,0,0,1,0,1,1,0,1),(38,0,1,0,0,0,1,0,1,1,0,1),(39,0,1,0,0,0,1,0,1,1,0,1),(40,0,1,0,0,0,1,0,1,1,0,1),(41,0,1,0,0,0,1,0,1,1,0,1),(42,0,1,0,0,0,1,0,1,1,0,1),(43,0,1,0,0,0,1,0,1,1,0,1),(44,0,1,0,0,0,1,0,1,1,0,1),(45,1,0,0,0,0,1,0,1,1,0,1),(48,0,1,0,0,0,1,0,1,1,0,1),(49,1,0,1,1,0,1,0,0,0,0,0),(50,1,0,1,1,0,1,0,0,0,0,0);
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_client`
--

DROP TABLE IF EXISTS `permission_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_client` (
  `permission_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  PRIMARY KEY (`permission_id`,`client_id`),
  KEY `IDX_E9EA3BEFED90CCA` (`permission_id`),
  KEY `IDX_E9EA3BE19EB6921` (`client_id`),
  CONSTRAINT `FK_E9EA3BE19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_E9EA3BEFED90CCA` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_client`
--

LOCK TABLES `permission_client` WRITE;
/*!40000 ALTER TABLE `permission_client` DISABLE KEYS */;
INSERT INTO `permission_client` VALUES (13,8),(14,9),(15,10),(16,11),(24,16),(25,16),(26,16),(27,16),(28,16),(29,16),(32,16),(33,16),(34,16),(35,16),(36,16),(37,16),(38,16),(39,16),(40,16),(41,16),(42,16),(43,16),(44,16),(45,16),(48,16),(49,18),(50,19);
/*!40000 ALTER TABLE `permission_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `confirm_pwd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_at` datetime NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D64919EB6921` (`client_id`),
  UNIQUE KEY `UNIQ_8D93D649DCD6CC49` (`branch_id`),
  CONSTRAINT `FK_8D93D64919EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  CONSTRAINT `FK_8D93D649DCD6CC49` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'havikoro2004@gmail.com','[\"ROLE_ADMIN\"]','$2y$13$i7F3hgpw2ynGevzMNUu5MOxsUSY1dtYCGdYOSvgIw6BnWs/CNonUG',NULL,NULL,'$2y$13$i7F3hgpw2ynGevzMNUu5MOxsUSY1dtYCGdYOSvgIw6BnWs/CNonUG','0000-00-00 00:00:00',NULL),(33,'klazdaz@fr.fr','[\"ROLE_READER\"]','$2y$13$LokS8IDyUw28Yl3uSWqFIuWNHIfwBuYKaGt7B1qPWCUFVAnFSzWk6',8,NULL,'$2y$13$LokS8IDyUw28Yl3uSWqFIuWNHIfwBuYKaGt7B1qPWCUFVAnFSzWk6','2022-09-17 22:30:02',''),(34,'marie.flataddd.2016@gmail.com','[\"ROLE_READER\"]','$2y$13$Kr6QKmfEz7MI4scZIXD5Wehx1LC3Yt/K2mJL7TEvENvrmI5EMkzhC',9,NULL,'$2y$13$Kr6QKmfEz7MI4scZIXD5Wehx1LC3Yt/K2mJL7TEvENvrmI5EMkzhC','2022-09-17 22:30:14','9ad7c1a7b2ab699ad3895418cc4d77e1'),(35,'marie.flatsssa.2016@gmail.com','[\"ROLE_READER\"]','$2y$13$AtiXIo6xShGVvC0hro5zneW.nN1DkXCeI.mjs1fyfKDB59Zei89/q',10,NULL,'$2y$13$AtiXIo6xShGVvC0hro5zneW.nN1DkXCeI.mjs1fyfKDB59Zei89/q','2022-09-17 22:30:30',''),(36,'marie.flazedzedzeta.2016@gmail.com','[\"ROLE_READER\"]','$2y$13$VYiviX1LhPe.kqNNN8YbBOzF1wadClWv0PW/mMvekyk2K0QmF677q',11,NULL,'$2y$13$VYiviX1LhPe.kqNNN8YbBOzF1wadClWv0PW/mMvekyk2K0QmF677q','2022-09-17 22:30:47',''),(44,'marie.flata.2016@gmail.com','[\"ROLE_READER\"]','$2y$13$Ry.FB.ZPqwfnzbLYLLkB0uJ5vPQRaR5FQWedo/EGuSUjD7WpMwzqK',16,NULL,'$2y$13$Ry.FB.ZPqwfnzbLYLLkB0uJ5vPQRaR5FQWedo/EGuSUjD7WpMwzqK','2022-09-18 01:13:12',NULL),(47,'havikor@gmail.com','[\"ROLE_USER\"]','$2y$13$9eFzxbRTIAhBxBfHl/PTBequ5EnBTTjDVh7zaF9el3w9C2oOsC3tK',NULL,10,'$2y$13$9eFzxbRTIAhBxBfHl/PTBequ5EnBTTjDVh7zaF9el3w9C2oOsC3tK','2022-09-19 21:11:40','74ad9665e95a61cab2ee4fbef45ded72'),(52,'mechbalradouan88@gmail.com','[\"ROLE_USER\"]','$2y$13$tBYafVxKlrPCst4X.tUvRu2dg.NLHotPUZiX9KU1WyNl.g7q4PPDO',NULL,22,'$2y$13$tBYafVxKlrPCst4X.tUvRu2dg.NLHotPUZiX9KU1WyNl.g7q4PPDO','2022-09-26 13:28:26',NULL),(53,'mechbalradouanee88@gmail.com','[\"ROLE_USER\"]','$2y$13$73aP34XvdDORiUJuRmreDOeK8olFetmp0oJdzcB39pm5JH2os3k3e',NULL,25,'$2y$13$73aP34XvdDORiUJuRmreDOeK8olFetmp0oJdzcB39pm5JH2os3k3e','2022-09-26 20:31:48','7f912784e909abd6bf395ad5388a59c5'),(54,'mechbalradouadddn88@gmail.com','[\"ROLE_USER\"]','$2y$13$uBCj/auwd122EFOtODgp5e/kyEMAREYTMBV46CjvfYUKYYbJOinje',NULL,27,'$2y$13$uBCj/auwd122EFOtODgp5e/kyEMAREYTMBV46CjvfYUKYYbJOinje','2022-09-26 20:33:21','be3e74e3502f5776feab7c87f688cc31'),(55,'mechbalradoedzedzuan88@gmail.com','[\"ROLE_USER\"]','$2y$13$SImGBYomQVZjGAnsY95zzOemkGS/WyVqQ0aqSCvmOsW/NeGJNCuDq',NULL,28,'$2y$13$SImGBYomQVZjGAnsY95zzOemkGS/WyVqQ0aqSCvmOsW/NeGJNCuDq','2022-09-27 22:00:27','372a9055407dc0152ee380aafd01afa1'),(56,'najib@azdazd.fr','[\"ROLE_READER\"]','$2y$13$YmMxz8OOmUU1JDteie4pguhDUESpiaN1rHwW7lM1zN/JweBdYaTma',18,NULL,'$2y$13$YmMxz8OOmUU1JDteie4pguhDUESpiaN1rHwW7lM1zN/JweBdYaTma','2022-09-27 22:34:22','fc2c37eed3572d7ae4fc262453ca1575'),(57,'najeeeib@azdazd.fr','[\"ROLE_READER\"]','$2y$13$CCpukFqFrXGR3Tn8x/04TOdUb0ztIrh/MHxWG0E/4bL.UTlQEs66u',19,NULL,'$2y$13$CCpukFqFrXGR3Tn8x/04TOdUb0ztIrh/MHxWG0E/4bL.UTlQEs66u','2022-09-27 22:34:53','bf012bbda18d158b76d20fcc110fde93');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-29  9:16:40
