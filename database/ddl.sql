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
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id_usuario`),
  KEY `usuario_ibfk_1` (`id_perfil`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `e_perfil` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `profundidade` float(9,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_coleta`),
  UNIQUE KEY `id_lagoa` (`id_lagoa`,`id_ponto_amostral`,`id_categoria`,`data`,`tipo_periodo`,`profundidade`),
  KEY `coleta_ibfk_2` (`id_ponto_amostral`),
  KEY `coleta_ibfk_3` (`id_categoria`),
  CONSTRAINT `coleta_ibfk_1` FOREIGN KEY (`id_lagoa`) REFERENCES `lagoa` (`id_lagoa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_ibfk_2` FOREIGN KEY (`id_ponto_amostral`) REFERENCES `ponto_amostral` (`id_ponto_amostral`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_ibfk_3` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parametro`
--

DROP TABLE IF EXISTS `parametro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parametro` (
  `id_parametro` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `composicao` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_parametro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id_coleta_parametro`),
  UNIQUE KEY `id_coleta_2` (`id_coleta`,`id_parametro`),
  KEY `id_coleta` (`id_coleta`),
  KEY `id_parametro` (`id_parametro`),
  CONSTRAINT `coleta_parametro_ibfk_1` FOREIGN KEY (`id_coleta`) REFERENCES `coleta` (`id_coleta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_parametro_ibfk_2` FOREIGN KEY (`id_parametro`) REFERENCES `parametro` (`id_parametro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `quantidade` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_coleta_parametro_especie`),
  UNIQUE KEY `id_coleta_parametro_2` (`id_coleta_parametro`,`id_especie`),
  KEY `id_coleta_parametro` (`id_coleta_parametro`),
  KEY `id_especie` (`id_especie`),
  CONSTRAINT `coleta_parametro_especie_ibfk_1` FOREIGN KEY (`id_coleta_parametro`) REFERENCES `coleta_parametro` (`id_coleta_parametro`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `coleta_parametro_especie_ibfk_2` FOREIGN KEY (`id_especie`) REFERENCES `especie` (`id_especie`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
