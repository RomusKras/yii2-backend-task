-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: yii2basic
-- ------------------------------------------------------
-- Server version	8.4.5

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
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1749466473),('m250606_095003_create_users_table',1749466475),('m250606_095139_create_products_table',1749466475),('m250606_095215_create_orders_table',1749466475),('m250606_095314_create_order_items_table',1749466475);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `count` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-order_items-order_id` (`order_id`),
  KEY `idx-order_items-product_id` (`product_id`),
  CONSTRAINT `fk-order_items-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk-order_items-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,2,19.99,2,'2025-06-09 11:06:53','2025-06-09 11:06:53'),(2,1,3,14.99,3,'2025-06-09 11:06:53','2025-06-09 11:06:53');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
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
  `name` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-orders-user_id` (`user_id`),
  KEY `idx-orders-status` (`status`),
  KEY `idx-orders-date` (`date`),
  CONSTRAINT `fk-orders-user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,2,'Заказ 1','2025-06-18 12:00:00','pending',0.00,'2025-06-09 11:06:53','2025-06-09 11:06:53');
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
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-products-name` (`name`),
  KEY `idx-products-price` (`price`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Essence Mascara Lash Princess',9.99,'The Essence Mascara Lash Princess is a popular mascara known for its volumizing and lengthening effects. Achieve dramatic lashes with this long-lasting and cruelty-free formula.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(2,'Eyeshadow Palette with Mirror',19.99,'The Eyeshadow Palette with Mirror offers a versatile range of eyeshadow shades for creating stunning eye looks. With a built-in mirror, it\'s convenient for on-the-go makeup application.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(3,'Powder Canister',14.99,'The Powder Canister is a finely milled setting powder designed to set makeup and control shine. With a lightweight and translucent formula, it provides a smooth and matte finish.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(4,'Red Lipstick',12.99,'The Red Lipstick is a classic and bold choice for adding a pop of color to your lips. With a creamy and pigmented formula, it provides a vibrant and long-lasting finish.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(5,'Red Nail Polish',8.99,'The Red Nail Polish offers a rich and glossy red hue for vibrant and polished nails. With a quick-drying formula, it provides a salon-quality finish at home.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(6,'Calvin Klein CK One',49.99,'CK One by Calvin Klein is a classic unisex fragrance, known for its fresh and clean scent. It\'s a versatile fragrance suitable for everyday wear.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(7,'Chanel Coco Noir Eau De',129.99,'Coco Noir by Chanel is an elegant and mysterious fragrance, featuring notes of grapefruit, rose, and sandalwood. Perfect for evening occasions.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(8,'Dior J\'adore',89.99,'J\'adore by Dior is a luxurious and floral fragrance, known for its blend of ylang-ylang, rose, and jasmine. It embodies femininity and sophistication.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(9,'Dolce Shine Eau de',69.99,'Dolce Shine by Dolce & Gabbana is a vibrant and fruity fragrance, featuring notes of mango, jasmine, and blonde woods. It\'s a joyful and youthful scent.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(10,'Gucci Bloom Eau de',79.99,'Gucci Bloom by Gucci is a floral and captivating fragrance, with notes of tuberose, jasmine, and Rangoon creeper. It\'s a modern and romantic scent.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(11,'Annibale Colombo Bed',1899.99,'The Annibale Colombo Bed is a luxurious and elegant bed frame, crafted with high-quality materials for a comfortable and stylish bedroom.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(12,'Annibale Colombo Sofa',2499.99,'The Annibale Colombo Sofa is a sophisticated and comfortable seating option, featuring exquisite design and premium upholstery for your living room.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(13,'Bedside Table African Cherry',299.99,'The Bedside Table in African Cherry is a stylish and functional addition to your bedroom, providing convenient storage space and a touch of elegance.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(14,'Knoll Saarinen Executive Conference Chair',499.99,'The Knoll Saarinen Executive Conference Chair is a modern and ergonomic chair, perfect for your office or conference room with its timeless design.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(15,'Wooden Bathroom Sink With Mirror',799.99,'The Wooden Bathroom Sink with Mirror is a unique and stylish addition to your bathroom, featuring a wooden sink countertop and a matching mirror.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(16,'Apple',1.99,'Fresh and crisp apples, perfect for snacking or incorporating into various recipes.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(17,'Beef Steak',12.99,'High-quality beef steak, great for grilling or cooking to your preferred level of doneness.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(18,'Cat Food',8.99,'Nutritious cat food formulated to meet the dietary needs of your feline friend.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(19,'Chicken Meat',9.99,'Fresh and tender chicken meat, suitable for various culinary preparations.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(20,'Cooking Oil',4.99,'Versatile cooking oil suitable for frying, sautéing, and various culinary applications.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(21,'Cucumber',1.49,'Crisp and hydrating cucumbers, ideal for salads, snacks, or as a refreshing side.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(22,'Dog Food',10.99,'Specially formulated dog food designed to provide essential nutrients for your canine companion.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(23,'Eggs',2.99,'Fresh eggs, a versatile ingredient for baking, cooking, or breakfast.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(24,'Fish Steak',14.99,'Quality fish steak, suitable for grilling, baking, or pan-searing.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(25,'Green Bell Pepper',1.29,'Fresh and vibrant green bell pepper, perfect for adding color and flavor to your dishes.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(26,'Green Chili Pepper',0.99,'Spicy green chili pepper, ideal for adding heat to your favorite recipes.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(27,'Honey Jar',6.99,'Pure and natural honey in a convenient jar, perfect for sweetening beverages or drizzling over food.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(28,'Ice Cream',5.49,'Creamy and delicious ice cream, available in various flavors for a delightful treat.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(29,'Juice',3.99,'Refreshing fruit juice, packed with vitamins and great for staying hydrated.','2025-06-09 10:54:52','2025-06-09 10:54:52'),(30,'Kiwi',2.49,'Nutrient-rich kiwi, perfect for snacking or adding a tropical twist to your dishes.','2025-06-09 10:54:52','2025-06-09 10:54:52');
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
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx-users-username` (`username`),
  KEY `idx-users-token` (`token`),
  KEY `idx-users-role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'romus2','$2y$13$XKJ4U0qwu0vctYjhpj2aNO4VGh.ncas5XhZCEuT1fZIzLfKCykEw2',NULL,'admin','2025-06-09 10:56:43','2025-06-09 10:58:20'),(2,'customer','$2y$13$0cjiTZ0ngrBvGQ5ci2jo9eFiAofusxXXAFRk61BDkb0YVVeNmFzdi','','customer','2025-06-09 10:58:59','2025-06-09 10:58:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'yii2basic'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-09 16:09:41
