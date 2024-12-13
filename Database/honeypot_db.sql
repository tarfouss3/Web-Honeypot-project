-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: honeypot_db
-- ------------------------------------------------------
-- Server version	8.0.40

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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` (`id`, `product_id`, `username`, `content`, `created_at`) VALUES (46,2,'asd','good phone','2024-11-08 00:17:35');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(255) NOT NULL,
  `payment_method` enum('Pay in Cash','Pay Later') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` (`id`, `user_id`, `status`, `created_at`, `address`, `payment_method`) VALUES (12,1,'pending','2024-11-19 16:12:31','asdsadsad','Pay in Cash'),(13,1,'pending','2024-11-19 16:14:05','asd','Pay in Cash'),(14,1,'pending','2024-11-19 16:14:32','asdsad','Pay in Cash');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`) VALUES (1,'iPhone 16 Pro Max','The latest iPhone with A18 Bionic chip.',1099.00,'iphone16promax.jpg'),(2,'iPhone 15','The iPhone with A17 Bionic chip.',999.00,'iphone15.jpg'),(3,'iPhone 14','The iPhone with A16 Bionic chip.',899.00,'iphone14.jpg'),(4,'iPhone 13','The iPhone with A15 Bionic chip.',799.00,'iphone13.jpg'),(5,'iPhone 12','The iPhone with A14 Bionic chip.',699.00,'iphone12.jpg'),(6,'iPhone 11','The iPhone with A13 Bionic chip.',599.00,'iphone11.jpg'),(7,'iphone XR','cheap iphone with so much stuff',100.00,'iphonexr.jpg');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `user_role` enum('admin','user','SuperUser') DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `avatar`, `is_active`, `user_role`, `status`) VALUES (1,'User12','$2y$10$IstoF302ILtNKeGoofyPQeWJJd7je8bjwbYK36Eh3vk/3pIO.Wc3G','673cd0136be83-a.jpg',1,'user',0),(2,'bilal.tarfouss','$2y$10$0T7QVvbXLjuk9CMRgtTQieGmRlYwdVbC0BnawHNEHd3XXYQ4cAzkK','WhatsApp Image 2024-03-20 at 06.16.31_968dc8f5.jpg',1,'user',0),(3,'user1','$2y$10$22z4CLeqmXPRKGVqKdAWou6UfjU7V2kDy/y4IElZvmzMdK9rko1Ae','WhatsApp Image 2024-03-20 at 06.16.31_2ca29e18.jpg',1,'user',0),(4,'admin','$2y$10$KudCHHpdRvmJjCb.V1dKGOVZAxXAIWJSvsTxFxFKGlUV3HXkmPSna','broken.jpg',1,'SuperUser',0),(5,'TechGuy01','$2y$10$0DDXgJiJ9aa1D8LfJQ41.OYpO8QJob9whJQ.z86T5bvPK2UUw08BS','profile1.jpg',1,'user',0),(6,'tarfouss3','$2y$10$rKwrCD9xDB7v/iVs8ceCUu20KeMvST8Xse5xzKmr8lmj72BuMHEu.','output..jpg',1,'user',0),(7,'N_Secure','$2y$10$LVly6MHpRE4flDiQRyeVUee1z/Y0oZrhD2zzo.wVWzyMNWMgnAyti','images.jpg',1,'user',0),(8,'User001','$2y$10$lTgHtyf7TPBJrT9ltkiZMOHZoAYGq0ocxJDXqB/x.YJLaV.WqN34.','images (1).jpg',1,'user',0),(9,'Sarah_','$2y$10$SVvEi7IgyaIrU5Bv.tSMWuCyjEoSqxrOh65PCUPvOOQtQ0BmKiOtC','images (2).jpg',1,'user',0),(10,'ManalH','$2y$10$C/N9.Yt9stK5gcjMws7mYuv5QwhmgmyZui5wfVv6f3AWX0ZID9DNK','GettyImages-1242093545-1024x702.jpg',1,'user',0),(11,'IphoneForever','$2y$10$eYofNJGYo7l8Ed5cdJjOU.KlWM5vO76udiv72YtqwGkQVvXiEkvmW','images (3).jpg',1,'user',0),(12,'Koenk','$2y$10$94ec74Ozgp.PyxrhubrngOjH/k6AUByVvZwjIdV7isk9xFX3RoNx2','superman.png',1,'user',0),(13,'HoneyPhone','$2y$10$JqcoeSzjHNDlgpnO2bMAO.wBbcSrNEFFoX8Jj6cL2hQOczs3H7g6S','Honey-Pot.png',1,'user',0),(14,'tarfouss','$2y$10$LyM3l4mIWzv.E1Y/aiBEeOXn4UoZK5mZ9CB.X3Pyyhzi8QxlrThpS','WhatsApp Image 2024-03-20 at 06.16.31_968dc8f5.jpg',1,'admin',1);
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

-- Dump completed on 2024-11-20 12:50:25
