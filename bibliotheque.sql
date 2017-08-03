-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: bibliotheque
-- ------------------------------------------------------
-- Server version	5.7.14

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
-- Table structure for table `abonne`
--

DROP TABLE IF EXISTS `abonne`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abonne` (
  `id_abonne` int(3) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(25) NOT NULL,
  PRIMARY KEY (`id_abonne`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abonne`
--

LOCK TABLES `abonne` WRITE;
/*!40000 ALTER TABLE `abonne` DISABLE KEYS */;
INSERT INTO `abonne` VALUES (2,'Benoit'),(3,'Chloe'),(4,'Laura'),(28,'Guillaume'),(29,'Alain'),(30,'Beatrice'),(31,'Marc'),(32,'Mike');
/*!40000 ALTER TABLE `abonne` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emprunt`
--

DROP TABLE IF EXISTS `emprunt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emprunt` (
  `id_emprunt` int(3) NOT NULL AUTO_INCREMENT,
  `id_livre` int(3) DEFAULT NULL,
  `id_abonne` int(3) DEFAULT NULL,
  `date_sortie` date NOT NULL,
  `date_rendu` date DEFAULT NULL,
  PRIMARY KEY (`id_emprunt`),
  KEY `id_livre` (`id_livre`),
  KEY `id_abonne` (`id_abonne`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emprunt`
--

LOCK TABLES `emprunt` WRITE;
/*!40000 ALTER TABLE `emprunt` DISABLE KEYS */;
INSERT INTO `emprunt` VALUES (1,100,1,'2014-12-17','2014-12-18'),(2,101,2,'2014-12-18','2014-12-20'),(3,100,3,'2014-12-19','2014-12-22'),(4,100,3,'2014-12-19','2014-12-22'),(5,104,1,'2014-12-19','2014-12-28'),(6,105,2,'2015-03-20','2015-03-26'),(7,105,3,'2015-06-13',NULL),(8,100,2,'2015-06-15',NULL),(9,100,1,'2017-06-11','1970-01-01'),(10,101,2,'2017-06-11','2017-08-13'),(11,102,4,'1970-01-01',NULL),(12,105,10,'2017-10-06',NULL),(13,103,4,'1970-01-01',NULL),(14,103,28,'2017-06-11',NULL);
/*!40000 ALTER TABLE `emprunt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livre`
--

DROP TABLE IF EXISTS `livre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `livre` (
  `id_livre` int(3) NOT NULL AUTO_INCREMENT,
  `auteur` varchar(25) NOT NULL,
  `titre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_livre`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livre`
--

LOCK TABLES `livre` WRITE;
/*!40000 ALTER TABLE `livre` DISABLE KEYS */;
INSERT INTO `livre` VALUES (100,'GUY DE MAUPASSANT','Une vie'),(101,'GUY DE MAUPASSANT','Bel-Ami'),(102,'HONORE DE BALZAC','Le pere Goriot'),(103,'ALPHONSE DAUDET','Le petit chose'),(104,'ALEXANDRE DUMAS','La Reine Margot'),(105,'ALEXANDRE DUMAS','Les Trois Mousquetaires');
/*!40000 ALTER TABLE `livre` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-03 20:54:01
