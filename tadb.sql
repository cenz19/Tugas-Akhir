-- MySQL dump 10.13  Distrib 8.0.40, for macos14 (arm64)
--
-- Host: 127.0.0.1    Database: tadb
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','pakar') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Us Ser','user@example.com','$2y$10$pE42HMLzBQZspacaqfI3X.Uzi49cQmhseoBlls.KfLW9TiFrlyrJG','user'),(2,'Pak Akar','pakar@example.com','$2y$10$FI3k1sPAmsEcKKsIuo4emueuKC7QKuezU4yZaxutFWjad/uPoHyca','pakar'),(3,'vincent','vincent@gmail.com','$2y$10$Wi4I1kGm9kfVZj4R42o5yuVy/5vOuPLRLvzOvlHGagZ/yZFimRIbe','user'),(4,'fanny','fanny@gmail.com','$2y$10$ej9WVh5vkIzXfS4.DWlXSOauFvr8bfgK2xtolYuMmm7pJ6E5JgQce','pakar'),(5,'charger','charger@gmail.com','$2y$10$ZCNbwoQnxtEcAGXCzoeiDOB8BvyBfkDZtTWU/MV88qxVn6tuOh6wu','pakar'),(6,'vincentkh','vkh@gmail.com','$2y$10$.fzScAulbpSp2xzp0zr4X.MpfYct1OvhlragSZDtREPyXm8T9aS..','pakar'),(7,'vkh','vkh1@gmail.com','$2y$10$4ntk/uki78mxjpxnLBwzzOl/zIITZSOeL9zMMbljeYMZ0y4XSZmZ.','pakar'),(8,'pakar','pakar@gmail.com','$2y$10$GOEE5diz4sGzVlXWzXrTPOaEk5K4bJfZCkz2OldFvgbtLxoO.WUce','pakar');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-04 13:43:45
