-- MySQL dump 10.11
--
-- Host: localhost    Database: eelagoas
-- ------------------------------------------------------
-- Server version	5.0.51a-24+lenny1

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
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL auto_increment,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY  (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `coleta`
--

DROP TABLE IF EXISTS `coleta`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `coleta` (
  `id_coleta` int(11) NOT NULL auto_increment,
  `id_lagoa` int(11) NOT NULL,
  `id_ponto_amostral` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY  (`id_coleta`),
  KEY `id_lagoa` (`id_lagoa`),
  KEY `id_ponto_amostral` (`id_ponto_amostral`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `coleta_ibfk_1` FOREIGN KEY (`id_lagoa`) REFERENCES `lagoa` (`id_lagoa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_ibfk_2` FOREIGN KEY (`id_ponto_amostral`) REFERENCES `ponto_amostral` (`id_ponto_amostral`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_ibfk_3` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `coleta_parametro`
--

DROP TABLE IF EXISTS `coleta_parametro`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `coleta_parametro` (
  `id_coleta_parametro` int(11) NOT NULL auto_increment,
  `id_coleta` int(11) NOT NULL,
  `id_parametro` int(11) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `nivel` float default NULL,
  PRIMARY KEY  (`id_coleta_parametro`),
  KEY `id_coleta` (`id_coleta`),
  KEY `id_parametro` (`id_parametro`),
  CONSTRAINT `coleta_parametro_ibfk_1` FOREIGN KEY (`id_coleta`) REFERENCES `coleta` (`id_coleta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_parametro_ibfk_2` FOREIGN KEY (`id_parametro`) REFERENCES `parametro` (`id_parametro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `lagoa`
--

DROP TABLE IF EXISTS `lagoa`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `lagoa` (
  `id_lagoa` int(11) NOT NULL auto_increment,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY  (`id_lagoa`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `parametro`
--

DROP TABLE IF EXISTS `parametro`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `parametro` (
  `id_parametro` int(11) NOT NULL auto_increment,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY  (`id_parametro`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ponto_amostral`
--

DROP TABLE IF EXISTS `ponto_amostral`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ponto_amostral` (
  `id_ponto_amostral` int(11) NOT NULL auto_increment,
  `id_lagoa` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY  (`id_ponto_amostral`),
  KEY `id_lagoa` (`id_lagoa`),
  CONSTRAINT `ponto_amostral_ibfk_1` FOREIGN KEY (`id_lagoa`) REFERENCES `lagoa` (`id_lagoa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `usuario` (
  `id_usuario` int(11) unsigned NOT NULL auto_increment,
  `login` varchar(8) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) default NULL,
  `lastlogin` datetime default NULL,
  `lastupdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (4,'admin',md5('admin'),'Administador',NULL,now(),now());
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

-- Dump completed on 2009-07-16  6:05:16
