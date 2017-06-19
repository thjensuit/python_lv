-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: 192.168.0.61    Database: offshore_vessel
-- ------------------------------------------------------
-- Server version	5.1.73

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
-- Table structure for table `m_admin_user`
--

DROP TABLE IF EXISTS `m_admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_admin_user` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` varchar(100) NOT NULL,
  `admin_pass` varchar(100) NOT NULL,
  `admin_name` varchar(100) DEFAULT NULL,
  `login_time` datetime NOT NULL,
  `sid` varchar(100) NOT NULL,
  `reg_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `up_datetime` datetime NOT NULL,
  `login_hash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_admin_user`
--

LOCK TABLES `m_admin_user` WRITE;
/*!40000 ALTER TABLE `m_admin_user` DISABLE KEYS */;
INSERT INTO `m_admin_user` VALUES (1,'root1234','i7cvGlkMNsMX9IYm045zNB4y7OjJYwQ6O/jjsQSc1KQ=','root root','2017-04-03 07:00:19','0','2017-01-01 02:04:01','2017-02-17 17:42:56','691a6c7f38a860370eb9a1dbc43f34a0a62a73d5'),(2,'trinhtrinh','nkpBUVlTNgMYkWNiY72XYYMGZ0yDaxtJQXGHPLdZ+Q4=','trinh trinh','2017-03-24 13:48:31','0','2017-01-26 03:53:19','1970-01-01 08:00:00','7bfca37319b03e9b650194191ef8ab187c7ee68a'),(6,'diadora001','DxS2PTLMqWTBp1fARx0AGAmQYWJVIAH00l7oPObEYNQ=','diadora test','1970-01-01 08:00:00','0','2017-01-26 04:22:55','1970-01-01 08:00:00',''),(8,'khanhkid','i7cvGlkMNsMX9IYm045zNB4y7OjJYwQ6O/jjsQSc1KQ=','root root','2017-02-22 04:22:21','0','2017-01-01 02:04:01','2017-02-06 11:18:36','28add8e48793f2c5e247965bb70d9735714b4906'),(10,'nakanishi','qBPmknBwJ/Y2YdxmM7lKdF0ShQngMjZVVOALVeOnyUs=','wws中西','2017-02-20 12:09:05','0','2017-02-07 04:45:39','1970-01-01 09:00:00','850cf69f5b4eb6335dc406810fdd567dc63e97dc'),(11,'sensen','E+eQNfw7WKSS1cpoYc0m5gdAJuobjQUTu672wLpi0Wc=','sen sen','2017-03-31 15:24:31','0','2017-02-23 09:52:42','1970-01-01 08:00:00','8844ae207eebaca5ffe2ea79c6704674ebd0fd49'),(12,'newnew','O9m02//bs+eMHDT0RKCTIfZENnk3B4ZXUtbQyjvctd0=','new user','1970-01-01 08:00:00','0','2017-03-08 01:42:42','1970-01-01 08:00:00',''),(13,'diadora','+R7FGDNLCM7Yld6LguiCG+HvlRxjSmF53U8/IS4W8ao=','test','2017-03-18 14:51:19','0','2017-03-13 11:15:28','2017-03-13 20:15:28','ae36cf4a9fd06e00da059ee242c35e69bdaa3cd7');
/*!40000 ALTER TABLE `m_admin_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-03 14:05:47
