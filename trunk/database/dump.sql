-- MySQL dump 10.13  Distrib 5.1.37, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: eelagoas
-- ------------------------------------------------------
-- Server version	5.1.37-1ubuntu5-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria_extra` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_categoria`),
  KEY `id_categoria_extra` (`id_categoria_extra`),
  CONSTRAINT `categoria_ibfk_1` FOREIGN KEY (`id_categoria_extra`) REFERENCES `categoria_extra` (`id_categoria_extra`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,2,'Perfil'),(2,1,'Fundo'),(3,2,'Perfil 2');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria_extra`
--

DROP TABLE IF EXISTS `categoria_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria_extra` (
  `id_categoria_extra` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `tem_valor` tinyint(1) DEFAULT '0',
  `tem_relacao` tinyint(1) DEFAULT '0',
  `tabela` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_categoria_extra`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_extra`
--

LOCK TABLES `categoria_extra` WRITE;
/*!40000 ALTER TABLE `categoria_extra` DISABLE KEYS */;
INSERT INTO `categoria_extra` VALUES (1,'nenhum','Nenhum',0,0,NULL),(2,'profundidade','Profundidade',1,0,NULL);
/*!40000 ALTER TABLE `categoria_extra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coleta`
--

DROP TABLE IF EXISTS `coleta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coleta` (
  `id_coleta` int(11) NOT NULL AUTO_INCREMENT,
  `id_lagoa` int(11) NOT NULL,
  `id_ponto_amostral` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `tipo_periodo` enum('mensal','diario') NOT NULL DEFAULT 'mensal',
  PRIMARY KEY (`id_coleta`),
  KEY `id_lagoa` (`id_lagoa`),
  KEY `id_ponto_amostral` (`id_ponto_amostral`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `coleta_ibfk_1` FOREIGN KEY (`id_lagoa`) REFERENCES `lagoa` (`id_lagoa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_ibfk_2` FOREIGN KEY (`id_ponto_amostral`) REFERENCES `ponto_amostral` (`id_ponto_amostral`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_ibfk_3` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coleta`
--

LOCK TABLES `coleta` WRITE;
/*!40000 ALTER TABLE `coleta` DISABLE KEYS */;
INSERT INTO `coleta` VALUES (22,23,22,1,'1984-10-07 13:00:00','diario'),(66,27,26,2,'1992-07-07 17:00:00','diario'),(67,28,27,1,'2008-02-09 07:00:00','diario'),(68,29,28,3,'1999-05-01 05:00:00','mensal'),(69,27,26,2,'1987-12-01 05:00:00','mensal'),(70,29,29,1,'1980-08-01 13:00:00','mensal');
/*!40000 ALTER TABLE `coleta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coleta_parametro`
--

DROP TABLE IF EXISTS `coleta_parametro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coleta_parametro` (
  `id_coleta_parametro` int(11) NOT NULL AUTO_INCREMENT,
  `id_coleta` int(11) NOT NULL,
  `id_parametro` int(11) NOT NULL,
  `valor` float DEFAULT NULL,
  `valor_extra` varchar(200) DEFAULT NULL,
  `valor_categoria_extra` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_coleta_parametro`),
  KEY `id_coleta` (`id_coleta`),
  KEY `id_parametro` (`id_parametro`),
  CONSTRAINT `coleta_parametro_ibfk_1` FOREIGN KEY (`id_coleta`) REFERENCES `coleta` (`id_coleta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_parametro_ibfk_2` FOREIGN KEY (`id_parametro`) REFERENCES `parametro` (`id_parametro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coleta_parametro`
--

LOCK TABLES `coleta_parametro` WRITE;
/*!40000 ALTER TABLE `coleta_parametro` DISABLE KEYS */;
INSERT INTO `coleta_parametro` VALUES (30,22,1,34,'especie','12'),(31,22,3,56,NULL,'12'),(147,66,1,1,'especie',NULL),(148,66,3,2,NULL,NULL),(149,66,92,3,'especie',NULL),(153,68,93,34,'especie','12'),(154,68,1,56,'especie','12'),(155,68,4,78,NULL,'12'),(156,68,94,910,'especie','12'),(157,69,4,1,NULL,NULL),(158,70,4,2,NULL,'1'),(159,67,93,24,'especie','12'),(160,67,92,34,'especie','12'),(161,67,4,56,NULL,'12');
/*!40000 ALTER TABLE `coleta_parametro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coleta_parametro_especie`
--

DROP TABLE IF EXISTS `coleta_parametro_especie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coleta_parametro_especie` (
  `id_coleta_parametro_especie` int(11) NOT NULL AUTO_INCREMENT,
  `id_coleta_parametro` int(11) NOT NULL,
  `id_especie` int(11) NOT NULL,
  PRIMARY KEY (`id_coleta_parametro_especie`),
  KEY `id_coleta_parametro` (`id_coleta_parametro`),
  KEY `id_especie` (`id_especie`),
  CONSTRAINT `coleta_parametro_especie_ibfk_1` FOREIGN KEY (`id_especie`) REFERENCES `especie` (`id_especie`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_parametro_especie_ibfk_2` FOREIGN KEY (`id_coleta_parametro`) REFERENCES `coleta_parametro` (`id_coleta_parametro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coleta_parametro_especie`
--

LOCK TABLES `coleta_parametro_especie` WRITE;
/*!40000 ALTER TABLE `coleta_parametro_especie` DISABLE KEYS */;
INSERT INTO `coleta_parametro_especie` VALUES (11,30,1),(95,147,1),(96,149,75),(97,149,76),(103,153,78),(104,153,79),(105,154,1),(106,156,81),(107,156,82),(108,156,83),(109,159,77),(110,159,78),(111,159,79),(112,160,75),(113,160,76);
/*!40000 ALTER TABLE `coleta_parametro_especie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especie`
--

DROP TABLE IF EXISTS `especie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `especie` (
  `id_especie` int(11) NOT NULL AUTO_INCREMENT,
  `id_parametro` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  PRIMARY KEY (`id_especie`),
  KEY `id_parametro` (`id_parametro`),
  CONSTRAINT `especie_ibfk_1` FOREIGN KEY (`id_parametro`) REFERENCES `parametro` (`id_parametro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especie`
--

LOCK TABLES `especie` WRITE;
/*!40000 ALTER TABLE `especie` DISABLE KEYS */;
INSERT INTO `especie` VALUES (1,1,'Baiacú'),(75,92,'H gtX'),(76,92,'Y gtX'),(77,93,'Bact D4rf'),(78,93,'Bact drof'),(79,93,'Bact oroel'),(80,93,'Bact XLR5'),(81,94,'girino'),(82,94,'salamandra'),(83,94,'lagarto pré-histórico');
/*!40000 ALTER TABLE `especie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lagoa`
--

DROP TABLE IF EXISTS `lagoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lagoa` (
  `id_lagoa` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_lagoa`),
  KEY `id_projeto` (`id_projeto`),
  CONSTRAINT `lagoa_ibfk_1` FOREIGN KEY (`id_projeto`) REFERENCES `projeto` (`id_projeto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lagoa`
--

LOCK TABLES `lagoa` WRITE;
/*!40000 ALTER TABLE `lagoa` DISABLE KEYS */;
INSERT INTO `lagoa` VALUES (23,23,'Lagoa a do Projeto B'),(27,27,'Lagoa a do projeto A'),(28,23,'Lagoa b do Projeto B'),(29,28,'Lagoa a do Projeto C'),(30,28,'A lagoa '),(31,29,'Lagoa de teste 1');
/*!40000 ALTER TABLE `lagoa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parametro`
--

DROP TABLE IF EXISTS `parametro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parametro` (
  `id_parametro` int(11) NOT NULL AUTO_INCREMENT,
  `id_parametro_extra` int(11) NOT NULL DEFAULT '1',
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_parametro`),
  KEY `id_parametro_extra` (`id_parametro_extra`),
  CONSTRAINT `parametro_ibfk_1` FOREIGN KEY (`id_parametro_extra`) REFERENCES `parametro_extra` (`id_parametro_extra`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametro`
--

LOCK TABLES `parametro` WRITE;
/*!40000 ALTER TABLE `parametro` DISABLE KEYS */;
INSERT INTO `parametro` VALUES (1,2,'Composição de Peixes'),(3,1,'Salinidade'),(4,1,'pH'),(92,2,'Composição de micróbios'),(93,2,'Composição de Bactérias'),(94,2,'Composição de larvas');
/*!40000 ALTER TABLE `parametro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parametro_extra`
--

DROP TABLE IF EXISTS `parametro_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parametro_extra` (
  `id_parametro_extra` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `tem_valor` tinyint(1) DEFAULT '0',
  `tem_relacao` tinyint(1) DEFAULT '0',
  `tabela` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_parametro_extra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametro_extra`
--

LOCK TABLES `parametro_extra` WRITE;
/*!40000 ALTER TABLE `parametro_extra` DISABLE KEYS */;
INSERT INTO `parametro_extra` VALUES (1,'nenhum','Nenhum',0,0,NULL),(2,'especie','Espécie',1,1,'especie');
/*!40000 ALTER TABLE `parametro_extra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ponto_amostral`
--

DROP TABLE IF EXISTS `ponto_amostral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ponto_amostral` (
  `id_ponto_amostral` int(11) NOT NULL AUTO_INCREMENT,
  `id_lagoa` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_ponto_amostral`),
  KEY `id_lagoa` (`id_lagoa`),
  CONSTRAINT `ponto_amostral_ibfk_1` FOREIGN KEY (`id_lagoa`) REFERENCES `lagoa` (`id_lagoa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ponto_amostral`
--

LOCK TABLES `ponto_amostral` WRITE;
/*!40000 ALTER TABLE `ponto_amostral` DISABLE KEYS */;
INSERT INTO `ponto_amostral` VALUES (22,23,'ponto 1 da Lagoa a'),(26,27,'Ponto 1 da Lagoa a'),(27,28,'Ponto 1 da Lagoa b'),(28,29,'Ponto 1 da Lagoa a'),(29,29,'A novo ponto');
/*!40000 ALTER TABLE `ponto_amostral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projeto`
--

DROP TABLE IF EXISTS `projeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projeto` (
  `id_projeto` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  PRIMARY KEY (`id_projeto`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projeto`
--

LOCK TABLES `projeto` WRITE;
/*!40000 ALTER TABLE `projeto` DISABLE KEYS */;
INSERT INTO `projeto` VALUES (23,'Projeto B'),(27,'Projeto A'),(28,'Projeto C'),(29,'Projeto 1a');
/*!40000 ALTER TABLE `projeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(8) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3','admin@localhost',NULL,'2009-12-04 23:10:47','2009-12-05 01:10:47');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-12-05  0:43:45
