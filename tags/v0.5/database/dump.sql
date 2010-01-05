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
-- Table structure for table `acao`
--

DROP TABLE IF EXISTS `acao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acao` (
  `id_acao` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `modulo` varchar(200) NOT NULL,
  `acesso` enum('N','S') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id_acao`),
  KEY `acao_ibfk_1` (`id_perfil`),
  KEY `acao_ibfk_3` (`nome`),
  CONSTRAINT `acao_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1211 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acao`
--

LOCK TABLES `acao` WRITE;
/*!40000 ALTER TABLE `acao` DISABLE KEYS */;
INSERT INTO `acao` VALUES (93,1,'buscar','GerenciarCategoria','S'),(94,1,'editar','GerenciarCategoria','S'),(95,1,'excluir','GerenciarCategoria','S'),(96,1,'listar','GerenciarCategoria','S'),(97,1,'salvar','GerenciarCategoria','S'),(98,1,'buscar','GerenciarColeta','S'),(99,1,'editar','GerenciarColeta','S'),(100,1,'excluir','GerenciarColeta','S'),(101,1,'listar','GerenciarColeta','S'),(102,1,'salvar','GerenciarColeta','S'),(103,1,'buscar','GerenciarEspecie','S'),(104,1,'editar','GerenciarEspecie','S'),(105,1,'excluir','GerenciarEspecie','S'),(106,1,'listar','GerenciarEspecie','S'),(107,1,'salvar','GerenciarEspecie','S'),(108,1,'buscar','GerenciarLagoa','S'),(109,1,'editar','GerenciarLagoa','S'),(110,1,'excluir','GerenciarLagoa','S'),(111,1,'listar','GerenciarLagoa','S'),(112,1,'salvar','GerenciarLagoa','S'),(113,1,'buscar','GerenciarParametro','S'),(114,1,'editar','GerenciarParametro','S'),(115,1,'excluir','GerenciarParametro','S'),(116,1,'listar','GerenciarParametro','S'),(117,1,'salvar','GerenciarParametro','S'),(118,1,'buscar','GerenciarPerfil','S'),(119,1,'editar','GerenciarPerfil','S'),(120,1,'excluir','GerenciarPerfil','S'),(121,1,'listar','GerenciarPerfil','S'),(122,1,'salvar','GerenciarPerfil','S'),(123,1,'buscar','GerenciarPontoAmostral','S'),(124,1,'editar','GerenciarPontoAmostral','S'),(125,1,'excluir','GerenciarPontoAmostral','S'),(126,1,'listar','GerenciarPontoAmostral','S'),(127,1,'salvar','GerenciarPontoAmostral','S'),(128,1,'buscar','GerenciarProjeto','S'),(129,1,'editar','GerenciarProjeto','S'),(130,1,'excluir','GerenciarProjeto','S'),(131,1,'listar','GerenciarProjeto','S'),(132,1,'salvar','GerenciarProjeto','S'),(133,1,'buscar','GerenciarUsuario','S'),(134,1,'editar','GerenciarUsuario','S'),(135,1,'excluir','GerenciarUsuario','S'),(136,1,'listar','GerenciarUsuario','S'),(137,1,'salvar','GerenciarUsuario','S'),(138,1,'execute','Relatorio','S'),(139,1,'search','Relatorio','S'),(140,2,'buscar','GerenciarCategoria','N'),(141,2,'editar','GerenciarCategoria','N'),(142,2,'excluir','GerenciarCategoria','N'),(143,2,'listar','GerenciarCategoria','N'),(144,2,'salvar','GerenciarCategoria','N'),(145,2,'buscar','GerenciarColeta','N'),(146,2,'editar','GerenciarColeta','N'),(147,2,'excluir','GerenciarColeta','N'),(148,2,'listar','GerenciarColeta','N'),(149,2,'salvar','GerenciarColeta','N'),(150,2,'buscar','GerenciarEspecie','N'),(151,2,'editar','GerenciarEspecie','N'),(152,2,'excluir','GerenciarEspecie','N'),(153,2,'listar','GerenciarEspecie','N'),(154,2,'salvar','GerenciarEspecie','N'),(155,2,'buscar','GerenciarLagoa','N'),(156,2,'editar','GerenciarLagoa','N'),(157,2,'excluir','GerenciarLagoa','N'),(158,2,'listar','GerenciarLagoa','N'),(159,2,'salvar','GerenciarLagoa','N'),(160,2,'buscar','GerenciarParametro','N'),(161,2,'editar','GerenciarParametro','N'),(162,2,'excluir','GerenciarParametro','N'),(163,2,'listar','GerenciarParametro','N'),(164,2,'salvar','GerenciarParametro','N'),(165,2,'buscar','GerenciarPerfil','N'),(166,2,'editar','GerenciarPerfil','N'),(167,2,'excluir','GerenciarPerfil','N'),(168,2,'listar','GerenciarPerfil','N'),(169,2,'salvar','GerenciarPerfil','N'),(170,2,'buscar','GerenciarPontoAmostral','N'),(171,2,'editar','GerenciarPontoAmostral','N'),(172,2,'excluir','GerenciarPontoAmostral','N'),(173,2,'listar','GerenciarPontoAmostral','N'),(174,2,'salvar','GerenciarPontoAmostral','N'),(175,2,'buscar','GerenciarProjeto','N'),(176,2,'editar','GerenciarProjeto','N'),(177,2,'excluir','GerenciarProjeto','N'),(178,2,'listar','GerenciarProjeto','N'),(179,2,'salvar','GerenciarProjeto','N'),(180,2,'buscar','GerenciarUsuario','N'),(181,2,'editar','GerenciarUsuario','N'),(182,2,'excluir','GerenciarUsuario','N'),(183,2,'listar','GerenciarUsuario','N'),(184,2,'salvar','GerenciarUsuario','N'),(185,2,'execute','Relatorio','S'),(186,2,'search','Relatorio','S'),(187,3,'buscar','GerenciarCategoria','S'),(188,3,'editar','GerenciarCategoria','N'),(189,3,'excluir','GerenciarCategoria','N'),(190,3,'listar','GerenciarCategoria','S'),(191,3,'salvar','GerenciarCategoria','N'),(192,3,'buscar','GerenciarColeta','S'),(193,3,'editar','GerenciarColeta','S'),(194,3,'excluir','GerenciarColeta','N'),(195,3,'listar','GerenciarColeta','S'),(196,3,'salvar','GerenciarColeta','N'),(197,3,'buscar','GerenciarEspecie','S'),(198,3,'editar','GerenciarEspecie','S'),(199,3,'excluir','GerenciarEspecie','N'),(200,3,'listar','GerenciarEspecie','S'),(201,3,'salvar','GerenciarEspecie','N'),(202,3,'buscar','GerenciarLagoa','S'),(203,3,'editar','GerenciarLagoa','N'),(204,3,'excluir','GerenciarLagoa','N'),(205,3,'listar','GerenciarLagoa','S'),(206,3,'salvar','GerenciarLagoa','N'),(207,3,'buscar','GerenciarParametro','S'),(208,3,'editar','GerenciarParametro','N'),(209,3,'excluir','GerenciarParametro','N'),(210,3,'listar','GerenciarParametro','S'),(211,3,'salvar','GerenciarParametro','N'),(212,3,'buscar','GerenciarPerfil','N'),(213,3,'editar','GerenciarPerfil','N'),(214,3,'excluir','GerenciarPerfil','N'),(215,3,'listar','GerenciarPerfil','N'),(216,3,'salvar','GerenciarPerfil','N'),(217,3,'buscar','GerenciarPontoAmostral','S'),(218,3,'editar','GerenciarPontoAmostral','N'),(219,3,'excluir','GerenciarPontoAmostral','N'),(220,3,'listar','GerenciarPontoAmostral','S'),(221,3,'salvar','GerenciarPontoAmostral','N'),(222,3,'buscar','GerenciarProjeto','S'),(223,3,'editar','GerenciarProjeto','N'),(224,3,'excluir','GerenciarProjeto','N'),(225,3,'listar','GerenciarProjeto','S'),(226,3,'salvar','GerenciarProjeto','N'),(227,3,'buscar','GerenciarUsuario','N'),(228,3,'editar','GerenciarUsuario','N'),(229,3,'excluir','GerenciarUsuario','N'),(230,3,'listar','GerenciarUsuario','N'),(231,3,'salvar','GerenciarUsuario','N'),(232,3,'execute','Relatorio','S'),(233,3,'search','Relatorio','S'),(1210,3,'','perfil','');
/*!40000 ALTER TABLE `acao` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (9,2,'Perfil'),(10,1,'Superficie'),(11,1,'Integrada');
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
  `descricao` varchar(100) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coleta`
--

LOCK TABLES `coleta` WRITE;
/*!40000 ALTER TABLE `coleta` DISABLE KEYS */;
INSERT INTO `coleta` VALUES (79,48,39,9,'2004-09-23 10:00:00','diario'),(80,48,39,9,'2004-09-23 10:00:00','diario'),(81,49,40,10,'2008-09-01 09:00:00','mensal'),(82,49,40,11,'2008-02-01 08:00:00','mensal'),(84,48,39,11,'1993-12-01 07:00:00','mensal'),(85,48,39,11,'2008-09-01 09:00:00','mensal'),(87,48,39,11,'2008-02-01 08:00:00','mensal'),(88,51,42,11,'2008-09-01 09:00:00','mensal'),(89,51,42,11,'2008-09-01 09:00:00','mensal'),(90,48,39,11,'2008-12-01 02:00:00','mensal');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coleta_parametro`
--

LOCK TABLES `coleta_parametro` WRITE;
/*!40000 ALTER TABLE `coleta_parametro` DISABLE KEYS */;
INSERT INTO `coleta_parametro` VALUES (1,79,112,27.8,NULL,'0.0'),(2,80,112,26.4,NULL,'0.2'),(3,81,113,0.5,NULL,NULL),(4,82,114,567,'especie',NULL),(6,84,113,1,NULL,NULL),(7,85,113,1,NULL,NULL),(9,87,113,1,NULL,NULL),(10,88,113,1,NULL,NULL),(11,89,113,1,NULL,NULL),(13,90,113,1,NULL,NULL),(14,90,112,1,NULL,NULL);
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
  CONSTRAINT `coleta_parametro_especie_ibfk_1` FOREIGN KEY (`id_coleta_parametro`) REFERENCES `coleta_parametro` (`id_coleta_parametro`) ON UPDATE CASCADE,
  CONSTRAINT `coleta_parametro_especie_ibfk_2` FOREIGN KEY (`id_especie`) REFERENCES `especie` (`id_especie`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coleta_parametro_especie`
--

LOCK TABLES `coleta_parametro_especie` WRITE;
/*!40000 ALTER TABLE `coleta_parametro_especie` DISABLE KEYS */;
INSERT INTO `coleta_parametro_especie` VALUES (115,4,88);
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
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especie`
--

LOCK TABLES `especie` WRITE;
/*!40000 ALTER TABLE `especie` DISABLE KEYS */;
INSERT INTO `especie` VALUES (88,114,'Moina Minuta'),(89,115,'Baiacú'),(90,115,'Tilapía');
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lagoa`
--

LOCK TABLES `lagoa` WRITE;
/*!40000 ALTER TABLE `lagoa` DISABLE KEYS */;
INSERT INTO `lagoa` VALUES (48,36,'Cabiúnas'),(49,36,'Comprida'),(51,36,'saúde');
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
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametro`
--

LOCK TABLES `parametro` WRITE;
/*!40000 ALTER TABLE `parametro` DISABLE KEYS */;
INSERT INTO `parametro` VALUES (112,1,'Temperatura  '),(113,1,'Secchi  '),(114,2,'Composição zoo'),(115,2,'Composição Peixe');
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
  `descricao` varchar(200) NOT NULL,
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
INSERT INTO `parametro_extra` VALUES (1,'nenhum','Nenhum',0,0,NULL),(2,'especie','Especie',1,1,'especie');
/*!40000 ALTER TABLE `parametro_extra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'Administrador'),(2,'Aluno'),(3,'Pesquisador');
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ponto_amostral`
--

LOCK TABLES `ponto_amostral` WRITE;
/*!40000 ALTER TABLE `ponto_amostral` DISABLE KEYS */;
INSERT INTO `ponto_amostral` VALUES (39,48,'Ponto 01'),(40,49,'Ponto 01'),(42,51,'púnção');
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projeto`
--

LOCK TABLES `projeto` WRITE;
/*!40000 ALTER TABLE `projeto` DISABLE KEYS */;
INSERT INTO `projeto` VALUES (36,'Ecolagoas'),(37,'adfasdf'),(38,'eterte'),(39,'fghjfghj'),(40,'ryrtuy'),(41,'tyuityiu'),(42,'etyerty');
/*!40000 ALTER TABLE `projeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `login` varchar(8) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (2,1,'admin','21232f297a57a5a743894a0e4a801fc3','Administrador','','2009-12-31 04:03:09','2009-12-31 06:03:09'),(7,2,'bia','14d777febb71c53630e9e843bedbd4d8','bia1','bia@bia','2009-12-26 21:32:16','2009-12-26 23:32:16'),(9,3,'paloma','14d777febb71c53630e9e843bedbd4d8','Paloma','paloma@paloma','2009-12-26 23:53:18','2009-12-27 01:53:18');
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

-- Dump completed on 2009-12-31  6:18:15
