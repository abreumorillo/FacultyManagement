-- MySQL dump 10.13  Distrib 5.6.27, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: researchdb
-- ------------------------------------------------------
-- Server version	5.6.27-0ubuntu0.14.04.1

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
-- Current Database: `researchdb`
--

/*!40000 DROP DATABASE IF EXISTS `researchdb`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `researchdb` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `researchdb`;

--
-- Table structure for table `authorship`
--

DROP TABLE IF EXISTS `authorship`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authorship` (
  `facultyId` int(11) NOT NULL,
  `paperId` int(11) NOT NULL,
  PRIMARY KEY (`facultyId`,`paperId`),
  KEY `fk_paper_idx` (`paperId`),
  KEY `fk_people_idx` (`facultyId`),
  CONSTRAINT `fk_paper` FOREIGN KEY (`paperId`) REFERENCES `papers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_people` FOREIGN KEY (`facultyId`) REFERENCES `people` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authorship`
--

LOCK TABLES `authorship` WRITE;
/*!40000 ALTER TABLE `authorship` DISABLE KEYS */;
INSERT INTO `authorship` VALUES (25,6),(25,7),(26,12),(38,14),(37,15);
/*!40000 ALTER TABLE `authorship` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keywords`
--

LOCK TABLES `keywords` WRITE;
/*!40000 ALTER TABLE `keywords` DISABLE KEYS */;
INSERT INTO `keywords` VALUES (20,'ASP.NET MVC'),(1,'C#'),(22,'CSS'),(3,'Data Mining'),(17,'Database'),(24,'GIS'),(16,'Glassfish'),(23,'HTML'),(7,'Human memory'),(13,'Informatics'),(2,'Java'),(18,'Performance'),(9,'PHP'),(26,'Quality of Service'),(10,'RESTful'),(8,'SOAP'),(14,'Tomcat'),(12,'Web 2.0'),(6,'Web API'),(25,'Web services'),(4,'XML');
/*!40000 ALTER TABLE `keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `papers`
--

DROP TABLE IF EXISTS `papers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `papers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `abstract` mediumtext,
  `citation` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `papers`
--

LOCK TABLES `papers` WRITE;
/*!40000 ALTER TABLE `papers` DISABLE KEYS */;
INSERT INTO `papers` VALUES (6,'CoWS: An Internet-Enriched and Quality-Aware Web Services Search Engine','With more and more Web services available on the Internet, many approaches have been proposed to help users discover and select desired services. However, existing approaches heavily rely on the infor','Zisman, A.; Spanoudakis, G.; Dooley, J.; Siveroni'),(7,'RESTful Cloud Service','Paper by Abreu Rumer','Gates, Jobs'),(12,'The future of web services (API)','The future abastract','Test 1, Test 2, Test 3'),(13,'Lineal regression model','The lineal regression model','Santana, Duclerc, Hesdric'),(14,'A Serious Game for Measuring Disaster Response Spatial Thinking (Edit)','A Serious Game for Measuring Disaster Response Spatial Thinking abstract','citation 1, citation 2, citation 3'),(15,'Browser Web Storage Vulnerability Investigation HTML5 localStorage Object','Browser Web Storage Vulnerability Investigation HTML5 localStorage Object abstract','citation 1, citation 2, citation 3');
/*!40000 ALTER TABLE `papers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `papers_keywords`
--

DROP TABLE IF EXISTS `papers_keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `papers_keywords` (
  `paperId` int(11) NOT NULL,
  `keywordId` int(11) NOT NULL,
  PRIMARY KEY (`paperId`,`keywordId`),
  KEY `fk_papers__keywords1_idx` (`keywordId`),
  KEY `fk_paper_papers1_idx` (`paperId`),
  CONSTRAINT `fk_papers_has_keywords_keywords1` FOREIGN KEY (`keywordId`) REFERENCES `keywords` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_papers_has_keywords_papers1` FOREIGN KEY (`paperId`) REFERENCES `papers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `papers_keywords`
--

LOCK TABLES `papers_keywords` WRITE;
/*!40000 ALTER TABLE `papers_keywords` DISABLE KEYS */;
INSERT INTO `papers_keywords` VALUES (7,1),(6,3),(7,3),(12,3),(13,3),(15,4),(13,7),(14,12),(15,12),(12,17),(14,17),(15,17),(15,23),(14,24);
/*!40000 ALTER TABLE `papers_keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `username` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `fk_role_idx` (`roleId`),
  CONSTRAINT `fk_role` FOREIGN KEY (`roleId`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `people`
--

LOCK TABLES `people` WRITE;
/*!40000 ALTER TABLE `people` DISABLE KEYS */;
INSERT INTO `people` VALUES (24,1,'Sandy','Abreu','2b65d4c139c448203cc305d0f685fd46','abreumorillo@gmail.com','sabreu'),(25,2,'Rumer','Abreu','5f0c2682065376c531b4d4aa6d582ec4','rumerabreu@gmail.com','rabreu'),(26,2,'Amauris','Abreu','5f0c2682065376c531b4d4aa6d582ec4','aabreu@gmail.com','aabreu'),(27,3,'Randy','Perez','5f0c2682065376c531b4d4aa6d582ec4','rperez@gmail.com','rperez'),(34,3,'Jesus Javier','Santana','5f0c2682065376c531b4d4aa6d582ec4','jsantana@rit.edu','jsantana'),(35,2,'Alicia','Vazquez','5f0c2682065376c531b4d4aa6d582ec4','avazquez@jcp.com','avazquez'),(37,2,'Daniel','Bogaard','5f0c2682065376c531b4d4aa6d582ec4','dbogaard@rit.edu','dbogaard'),(38,2,'Michael','Floeser','5f0c2682065376c531b4d4aa6d582ec4','mfloeser@rit.edu','mfloeser');
/*!40000 ALTER TABLE `people` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin'),(2,'Faculty'),(3,'Student');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-15 18:19:52
