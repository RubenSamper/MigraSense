-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: aquaSense
-- ------------------------------------------------------
-- Server version	8.0.46

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
-- Table structure for table `historial_riesgo`
--

DROP TABLE IF EXISTS `historial_riesgo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_riesgo` (
  `id` char(36) NOT NULL,
  `usuario_id` char(36) NOT NULL,
  `fecha` date NOT NULL,
  `nivel` enum('verde','amarillo','rojo') NOT NULL,
  `variacion_hpa` decimal(5,1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unico_usuario_fecha` (`usuario_id`,`fecha`),
  KEY `fk_historial_usuario` (`usuario_id`),
  CONSTRAINT `fk_historial_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_riesgo`
--

LOCK TABLES `historial_riesgo` WRITE;
/*!40000 ALTER TABLE `historial_riesgo` DISABLE KEYS */;
INSERT INTO `historial_riesgo` VALUES ('8d655a2a-dc9a-428b-9257-9a97f5a28847','9439ddc0-0d99-4f5f-804f-d932d51a0c86','2026-09-25','verde',2.9,'2026-09-25 18:18:56','2026-09-25 18:18:56'),('97285d80-dadc-44e5-a401-bcd4393e65bd','a44be90e-55f9-4588-b852-b90e3e14c524','2026-09-25','verde',1.8,'2026-09-25 15:16:27','2026-09-25 15:18:53'),('c3c46cca-70da-4f92-b0c0-05b1fe27eefc','2fa0c728-ce2a-48fd-bbfc-995878b438a0','2026-09-25','verde',2.9,'2026-09-25 15:40:45','2026-09-25 16:49:33');
/*!40000 ALTER TABLE `historial_riesgo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migranas`
--

DROP TABLE IF EXISTS `migranas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migranas` (
  `id` char(36) NOT NULL,
  `usuario_id` char(36) NOT NULL,
  `tipo_migrana` enum('con_aura','sin_aura','menstrual','vestibular','cronica') NOT NULL,
  `hora_inicio` timestamp NOT NULL,
  `hora_termina` timestamp NULL DEFAULT NULL,
  `intensidad` enum('leve','moderada','fuerte') NOT NULL,
  `sintomas` json DEFAULT NULL,
  `datos_extra` json DEFAULT NULL,
  `notas` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_migranas_usuario` (`usuario_id`),
  CONSTRAINT `fk_migranas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migranas`
--

LOCK TABLES `migranas` WRITE;
/*!40000 ALTER TABLE `migranas` DISABLE KEYS */;
INSERT INTO `migranas` VALUES ('01dcc9d2-d2dd-4a4b-ad6b-18c200b95fbc','2fa0c728-ce2a-48fd-bbfc-995878b438a0','sin_aura','2026-09-25 16:30:00','2026-09-27 18:30:00','fuerte',NULL,NULL,NULL,'2026-09-25 16:30:25','2026-09-25 16:30:25'),('148299c7-7da1-45a6-9efe-cdf415c2b2fb','2fa0c728-ce2a-48fd-bbfc-995878b438a0','vestibular','2026-09-25 16:49:00','2026-10-03 18:49:00','fuerte',NULL,NULL,NULL,'2026-09-25 16:49:44','2026-09-25 16:49:44'),('28a98233-4abf-44a0-8a7c-0e2287ba88f5','2fa0c728-ce2a-48fd-bbfc-995878b438a0','menstrual','2026-09-25 16:35:00','2026-10-02 18:35:00','fuerte',NULL,NULL,'adlkñasnkasdn','2026-09-25 16:36:02','2026-09-25 16:36:02'),('3dc35b4a-cb3a-4172-ba06-91081b0d4578','a44be90e-55f9-4588-b852-b90e3e14c524','menstrual','2026-09-25 15:18:00','2026-09-27 17:18:00','fuerte',NULL,NULL,NULL,'2026-09-25 15:18:22','2026-09-25 15:18:22'),('5969d539-a131-4261-aa70-79041fd30630','a44be90e-55f9-4588-b852-b90e3e14c524','con_aura','2026-09-26 15:17:00','2026-09-29 17:17:00','fuerte',NULL,NULL,NULL,'2026-09-25 15:17:50','2026-09-25 15:17:50'),('a1b4e496-fc6c-449f-a251-988c365e8252','2fa0c728-ce2a-48fd-bbfc-995878b438a0','vestibular','2026-09-25 16:35:00','2026-10-01 18:35:00','moderada',NULL,NULL,'asdlasjdkllk','2026-09-25 16:35:37','2026-09-25 16:35:37'),('c9bfcfee-beb6-48f6-9c72-f9ca6796f36b','2fa0c728-ce2a-48fd-bbfc-995878b438a0','vestibular','2026-09-25 16:20:00','2026-09-26 18:20:00','moderada',NULL,NULL,NULL,'2026-09-25 16:20:46','2026-09-25 16:20:46'),('d23690a9-5557-4c34-9fdd-320d03467397','2fa0c728-ce2a-48fd-bbfc-995878b438a0','sin_aura','2026-09-25 16:25:00','2026-09-27 18:25:00','fuerte',NULL,NULL,NULL,'2026-09-25 16:25:49','2026-09-25 16:25:49'),('dc92babb-af17-485c-a513-7ec1ef453a34','2fa0c728-ce2a-48fd-bbfc-995878b438a0','vestibular','2026-09-25 16:34:00','2026-10-04 18:34:00','fuerte',NULL,NULL,NULL,'2026-09-25 16:34:07','2026-09-25 16:34:07'),('dce950c1-8383-40c3-9dcc-428154c90f56','2fa0c728-ce2a-48fd-bbfc-995878b438a0','vestibular','2026-09-25 16:34:00','2026-10-02 18:34:00','moderada',NULL,NULL,NULL,'2026-09-25 16:34:41','2026-09-25 16:34:41'),('dd99bc9b-5ae3-49dc-ba24-54a6445464f7','2fa0c728-ce2a-48fd-bbfc-995878b438a0','sin_aura','2026-09-25 16:33:00','2026-09-27 18:33:00','fuerte',NULL,NULL,'pruebasassss','2026-09-25 16:33:51','2026-09-25 16:33:51'),('e132eec6-4035-42ae-b2dc-b574748f27e3','2fa0c728-ce2a-48fd-bbfc-995878b438a0','cronica','2026-09-25 16:49:00','2026-10-02 18:49:00','leve',NULL,NULL,NULL,'2026-09-25 16:49:33','2026-09-25 16:49:33'),('f2b042fb-05f3-48a0-9d52-d5eeda37825f','2fa0c728-ce2a-48fd-bbfc-995878b438a0','sin_aura','2026-09-25 16:22:00','2026-10-02 18:22:00','leve',NULL,NULL,NULL,'2026-09-25 16:23:01','2026-09-25 16:23:01');
/*!40000 ALTER TABLE `migranas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador'),(2,'Usuario');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` char(36) NOT NULL,
  `google_id` varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int NOT NULL,
  `sensibilidad_presion` enum('poco_sensible','sensible','muy_sensible') NOT NULL DEFAULT 'sensible',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES ('2fa0c728-ce2a-48fd-bbfc-995878b438a0',NULL,36.8340000,-2.4637000,'Alejandro','Girona Cañas','admin123@gmail.com','$2y$12$Pm0ZuRsj95IlTE4nuKx0oOII9Mb6JStIBMHA.4WfWTlAPJsucImdi',1,'sensible','2026-05-17 21:00:16','2026-09-08 20:21:41'),('9439ddc0-0d99-4f5f-804f-d932d51a0c86','112054751448122860839',36.8463440,-2.3569310,'Rubén','Samper Cruz','rubensamper21@gmail.com','$2y$12$jqB4yX36j83SpUBnOSkY2eehTTO3OWq7BjTCCayFIECk2r7DO.ZTm',2,'sensible','2026-09-25 18:17:33','2026-09-25 18:18:55'),('a44be90e-55f9-4588-b852-b90e3e14c524','103522572849641792200',39.4850150,-0.4746770,'Nicol','V','scracli10@gmail.com','$2y$12$.qkLfecTnVFKc.ixEtvZKug.X4Vh8B6MGfQP75HPaSK5RIP/5nnkO',2,'sensible','2026-09-24 20:28:10','2026-09-25 15:16:48');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-01 12:43:29
