-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: bd_incidencia
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `tbl_accion`
--

DROP TABLE IF EXISTS `tbl_accion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_accion` (
  `id_acc` int NOT NULL AUTO_INCREMENT,
  `nombre_acc` varchar(255) NOT NULL,
  `slug_acc` varchar(255) DEFAULT NULL,
  `activo_acc` tinyint(1) DEFAULT '1',
  `id_per` int DEFAULT NULL,
  PRIMARY KEY (`id_acc`),
  KEY `id_per` (`id_per`),
  CONSTRAINT `tbl_accion_ibfk_1` FOREIGN KEY (`id_per`) REFERENCES `tbl_permiso` (`id_per`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_accion`
--

LOCK TABLES `tbl_accion` WRITE;
/*!40000 ALTER TABLE `tbl_accion` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_accion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_activo_informatico`
--

DROP TABLE IF EXISTS `tbl_activo_informatico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_activo_informatico` (
  `id_ain` int NOT NULL AUTO_INCREMENT,
  `nombre_ain` varchar(255) NOT NULL,
  `activo_ain` tinyint(1) DEFAULT '1',
  `id_tac` int DEFAULT NULL,
  PRIMARY KEY (`id_ain`),
  KEY `id_tac` (`id_tac`),
  CONSTRAINT `tbl_activo_informatico_ibfk_1` FOREIGN KEY (`id_tac`) REFERENCES `tbl_tipo_activo` (`id_tac`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_activo_informatico`
--

LOCK TABLES `tbl_activo_informatico` WRITE;
/*!40000 ALTER TABLE `tbl_activo_informatico` DISABLE KEYS */;
INSERT INTO `tbl_activo_informatico` VALUES (1,'Pc',1,1),(2,'Laptop',1,1),(3,'Impresora',1,1),(4,'Switch',1,1),(5,'Sistema Web',1,2);
/*!40000 ALTER TABLE `tbl_activo_informatico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_adjunto`
--

DROP TABLE IF EXISTS `tbl_adjunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_adjunto` (
  `id_adj` int NOT NULL AUTO_INCREMENT,
  `ruta_adj` varchar(255) NOT NULL,
  `nombre_adj` varchar(255) DEFAULT NULL,
  `activo_adj` tinyint(1) DEFAULT '1',
  `id_inc` int DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado_por` int DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_adj`),
  KEY `id_inc` (`id_inc`),
  CONSTRAINT `tbl_adjunto_ibfk_1` FOREIGN KEY (`id_inc`) REFERENCES `tbl_incidencia` (`id_inc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_adjunto`
--

LOCK TABLES `tbl_adjunto` WRITE;
/*!40000 ALTER TABLE `tbl_adjunto` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_adjunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_cargo`
--

DROP TABLE IF EXISTS `tbl_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_cargo` (
  `id_car` int NOT NULL AUTO_INCREMENT,
  `nombre_car` varchar(255) NOT NULL,
  `activo_car` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_car`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_cargo`
--

LOCK TABLES `tbl_cargo` WRITE;
/*!40000 ALTER TABLE `tbl_cargo` DISABLE KEYS */;
INSERT INTO `tbl_cargo` VALUES (1,'Jefatura',1),(2,'Secretario(a)',1),(3,'Apoyo',1);
/*!40000 ALTER TABLE `tbl_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_complejidad`
--

DROP TABLE IF EXISTS `tbl_complejidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_complejidad` (
  `id_com` int NOT NULL AUTO_INCREMENT,
  `nombre_com` varchar(255) NOT NULL,
  `orden_com` int DEFAULT NULL,
  `activo_com` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_com`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_complejidad`
--

LOCK TABLES `tbl_complejidad` WRITE;
/*!40000 ALTER TABLE `tbl_complejidad` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_complejidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_incidencia`
--

DROP TABLE IF EXISTS `tbl_incidencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_incidencia` (
  `id_inc` int NOT NULL AUTO_INCREMENT,
  `incidencia_inc` text NOT NULL,
  `fecha_incidencia_inc` timestamp NOT NULL,
  `solucion_inc` text,
  `fecha_solucion_inc` date DEFAULT NULL,
  `observacion_inc` text,
  `activo_inc` tinyint(1) DEFAULT '1',
  `estado_inc` int DEFAULT '1',
  `id_usu` int DEFAULT NULL,
  `id_tat` int DEFAULT NULL,
  `id_com` int DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado_por` int DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_inc`),
  KEY `id_usu` (`id_usu`),
  KEY `id_tat` (`id_tat`),
  KEY `id_com` (`id_com`),
  CONSTRAINT `tbl_incidencia_ibfk_1` FOREIGN KEY (`id_usu`) REFERENCES `tbl_usuario` (`id_usu`),
  CONSTRAINT `tbl_incidencia_ibfk_2` FOREIGN KEY (`id_tat`) REFERENCES `tbl_trabajador_activo` (`id_tat`),
  CONSTRAINT `tbl_incidencia_ibfk_3` FOREIGN KEY (`id_com`) REFERENCES `tbl_complejidad` (`id_com`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_incidencia`
--

LOCK TABLES `tbl_incidencia` WRITE;
/*!40000 ALTER TABLE `tbl_incidencia` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_incidencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_oficina`
--

DROP TABLE IF EXISTS `tbl_oficina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_oficina` (
  `id_ofi` int NOT NULL AUTO_INCREMENT,
  `nombre_ofi` varchar(255) NOT NULL,
  `activo_ofi` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_ofi`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_oficina`
--

LOCK TABLES `tbl_oficina` WRITE;
/*!40000 ALTER TABLE `tbl_oficina` DISABLE KEYS */;
INSERT INTO `tbl_oficina` VALUES (1,'Oficina de Tecnología de Información',1);
/*!40000 ALTER TABLE `tbl_oficina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_oficina_cargo`
--

DROP TABLE IF EXISTS `tbl_oficina_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_oficina_cargo` (
  `id_oca` int NOT NULL AUTO_INCREMENT,
  `id_ofi` int DEFAULT NULL,
  `id_car` int DEFAULT NULL,
  PRIMARY KEY (`id_oca`),
  KEY `id_ofi` (`id_ofi`),
  KEY `id_car` (`id_car`),
  CONSTRAINT `tbl_oficina_cargo_ibfk_1` FOREIGN KEY (`id_ofi`) REFERENCES `tbl_oficina` (`id_ofi`),
  CONSTRAINT `tbl_oficina_cargo_ibfk_2` FOREIGN KEY (`id_car`) REFERENCES `tbl_cargo` (`id_car`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_oficina_cargo`
--

LOCK TABLES `tbl_oficina_cargo` WRITE;
/*!40000 ALTER TABLE `tbl_oficina_cargo` DISABLE KEYS */;
INSERT INTO `tbl_oficina_cargo` VALUES (1,1,1),(2,1,2),(3,1,3);
/*!40000 ALTER TABLE `tbl_oficina_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_permiso`
--

DROP TABLE IF EXISTS `tbl_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_permiso` (
  `id_per` int NOT NULL AUTO_INCREMENT,
  `nombre_per` varchar(255) NOT NULL,
  `slug_per` varchar(255) DEFAULT NULL,
  `activo_per` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_per`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_permiso`
--

LOCK TABLES `tbl_permiso` WRITE;
/*!40000 ALTER TABLE `tbl_permiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_rol`
--

DROP TABLE IF EXISTS `tbl_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_rol` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(255) NOT NULL,
  `descripcion_rol` text,
  `slug_rol` varchar(255) DEFAULT NULL,
  `activo_rol` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_rol`
--

LOCK TABLES `tbl_rol` WRITE;
/*!40000 ALTER TABLE `tbl_rol` DISABLE KEYS */;
INSERT INTO `tbl_rol` VALUES (1,'Administrador','Rol con acceso completo al sistema','administrador',1),(2,'Gestor de Incidencias','Rol con permisos para gestionar incidencias','gestor-de-incidencias',1);
/*!40000 ALTER TABLE `tbl_rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_rol_permiso`
--

DROP TABLE IF EXISTS `tbl_rol_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_rol_permiso` (
  `id_rpe` int NOT NULL AUTO_INCREMENT,
  `id_acc` int DEFAULT NULL,
  `id_rol` int DEFAULT NULL,
  PRIMARY KEY (`id_rpe`),
  KEY `id_acc` (`id_acc`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `tbl_rol_permiso_ibfk_1` FOREIGN KEY (`id_acc`) REFERENCES `tbl_accion` (`id_acc`),
  CONSTRAINT `tbl_rol_permiso_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `tbl_rol` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_rol_permiso`
--

LOCK TABLES `tbl_rol_permiso` WRITE;
/*!40000 ALTER TABLE `tbl_rol_permiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_rol_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_tipo_activo`
--

DROP TABLE IF EXISTS `tbl_tipo_activo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_tipo_activo` (
  `id_tac` int NOT NULL AUTO_INCREMENT,
  `nombre_tac` varchar(255) NOT NULL,
  `activo_tac` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_tac`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_tipo_activo`
--

LOCK TABLES `tbl_tipo_activo` WRITE;
/*!40000 ALTER TABLE `tbl_tipo_activo` DISABLE KEYS */;
INSERT INTO `tbl_tipo_activo` VALUES (1,'Bien',1),(2,'Servicio',1);
/*!40000 ALTER TABLE `tbl_tipo_activo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_trabajador`
--

DROP TABLE IF EXISTS `tbl_trabajador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_trabajador` (
  `id_tra` int NOT NULL AUTO_INCREMENT,
  `apellido_paterno_tra` varchar(255) NOT NULL,
  `apellido_materno_tra` varchar(255) NOT NULL,
  `nombres_tra` varchar(255) NOT NULL,
  `activo_tra` tinyint(1) DEFAULT '1',
  `id_oca` int DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado_por` int DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_tra`),
  KEY `id_oca` (`id_oca`),
  CONSTRAINT `tbl_trabajador_ibfk_1` FOREIGN KEY (`id_oca`) REFERENCES `tbl_oficina_cargo` (`id_oca`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_trabajador`
--

LOCK TABLES `tbl_trabajador` WRITE;
/*!40000 ALTER TABLE `tbl_trabajador` DISABLE KEYS */;
INSERT INTO `tbl_trabajador` VALUES (1,'Mendoza','Flores','Jamt Americo',1,1,'2024-08-21 14:13:15','2024-08-21 14:13:15',1,1);
/*!40000 ALTER TABLE `tbl_trabajador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_trabajador_activo`
--

DROP TABLE IF EXISTS `tbl_trabajador_activo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_trabajador_activo` (
  `id_tat` int NOT NULL AUTO_INCREMENT,
  `modelo_tat` varchar(255) DEFAULT NULL,
  `detalle_tat` text,
  `ip_asignado_tat` varchar(45) DEFAULT NULL,
  `id_tra` int DEFAULT NULL,
  `id_ain` int DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado_por` int DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_tat`),
  KEY `id_tra` (`id_tra`),
  KEY `id_ain` (`id_ain`),
  CONSTRAINT `tbl_trabajador_activo_ibfk_1` FOREIGN KEY (`id_tra`) REFERENCES `tbl_trabajador` (`id_tra`),
  CONSTRAINT `tbl_trabajador_activo_ibfk_2` FOREIGN KEY (`id_ain`) REFERENCES `tbl_activo_informatico` (`id_ain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_trabajador_activo`
--

LOCK TABLES `tbl_trabajador_activo` WRITE;
/*!40000 ALTER TABLE `tbl_trabajador_activo` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_trabajador_activo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_usuario`
--

DROP TABLE IF EXISTS `tbl_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_usuario` (
  `id_usu` int NOT NULL AUTO_INCREMENT,
  `correo_usu` varchar(255) NOT NULL,
  `contrasena_usu` varchar(255) NOT NULL,
  `foto_usu` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `activo_usu` tinyint(1) DEFAULT '1',
  `id_rol` int DEFAULT NULL,
  `id_tra` int DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creado_por` int DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_usu`),
  KEY `id_rol` (`id_rol`),
  KEY `id_tra` (`id_tra`),
  CONSTRAINT `tbl_usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `tbl_rol` (`id_rol`),
  CONSTRAINT `tbl_usuario_ibfk_2` FOREIGN KEY (`id_tra`) REFERENCES `tbl_trabajador` (`id_tra`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_usuario`
--

LOCK TABLES `tbl_usuario` WRITE;
/*!40000 ALTER TABLE `tbl_usuario` DISABLE KEYS */;
INSERT INTO `tbl_usuario` VALUES (1,'desarrollo_oti@unia.edu.pe','$2y$10$Piv4DqIDULAMRlPFumbJqeIk9vreVIVZ4PJJdzHhEkeAD57YuBVJu',NULL,NULL,1,1,1,'2024-08-21 14:13:20','2024-08-21 14:13:20',1,1);
/*!40000 ALTER TABLE `tbl_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'bd_incidencia'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-22 10:32:21
