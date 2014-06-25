-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.41


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema finance
--

CREATE DATABASE IF NOT EXISTS finance;
USE finance;

--
-- Definition of table `allocation`
--

DROP TABLE IF EXISTS `allocation`;
CREATE TABLE `allocation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `initial` varchar(45) NOT NULL,
  `additional` varchar(45) NOT NULL,
  `transaction_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `allocation`
--

/*!40000 ALTER TABLE `allocation` DISABLE KEYS */;
INSERT INTO `allocation` (`id`,`control_id`,`type`,`initial`,`additional`,`transaction_id`) VALUES 
 (1,'1','sjt','2','',''),
 (2,'1','sjd','1','',''),
 (3,'1','svd','2','',''),
 (4,'1','svt','5','',''),
 (5,'19','sjt','1479','',''),
 (6,'19','svt','650','',''),
 (24,'20','sjt','200','','20120627_31'),
 (25,'20','svt','355','','20120627_31');
/*!40000 ALTER TABLE `allocation` ENABLE KEYS */;


--
-- Definition of table `beginning_balance_cash`
--

DROP TABLE IF EXISTS `beginning_balance_cash`;
CREATE TABLE `beginning_balance_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `revolving_fund` varchar(100) NOT NULL,
  `for_deposit` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `beginning_balance_cash`
--

/*!40000 ALTER TABLE `beginning_balance_cash` DISABLE KEYS */;
INSERT INTO `beginning_balance_cash` (`id`,`log_id`,`revolving_fund`,`for_deposit`) VALUES 
 (1,'1','48900','600000'),
 (2,'16','40001.35','602000'),
 (3,'17','36830.95','602000'),
 (4,'18','31850.95','602000'),
 (5,'19','31850.95','602000'),
 (6,'32','31850.95','602000'),
 (7,'33','31850.95','602000'),
 (8,'34','31850.95','602000'),
 (9,'35','31850.95','602000'),
 (10,'48','199500','-180000'),
 (11,'49','179500','-200000'),
 (12,'47','200000','120000'),
 (13,'59','179500','-200000'),
 (14,'64','179500','-200000');
/*!40000 ALTER TABLE `beginning_balance_cash` ENABLE KEYS */;


--
-- Definition of table `beginning_balance_tickets`
--

DROP TABLE IF EXISTS `beginning_balance_tickets`;
CREATE TABLE `beginning_balance_tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `svt` varchar(45) DEFAULT NULL,
  `svd` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `beginning_balance_tickets`
--

/*!40000 ALTER TABLE `beginning_balance_tickets` DISABLE KEYS */;
INSERT INTO `beginning_balance_tickets` (`id`,`log_id`,`sjt`,`sjd`,`svt`,`svd`) VALUES 
 (1,'1','3014','3017','4028','5003'),
 (2,'16','3014','2217','4828','6116'),
 (3,'17','3014','2217','4828','6116'),
 (4,'18','3014','2217','4828','6116'),
 (5,'19','3014','2217','4828','6116'),
 (6,'32','3014','2217','4828','6116'),
 (7,'59','22','1','2','3'),
 (8,'64','8','3',NULL,NULL);
/*!40000 ALTER TABLE `beginning_balance_tickets` ENABLE KEYS */;


--
-- Definition of table `cash_transfer`
--

DROP TABLE IF EXISTS `cash_transfer`;
CREATE TABLE `cash_transfer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `ticket_seller` varchar(100) NOT NULL,
  `cash_assistant` varchar(100) NOT NULL,
  `type` varchar(45) NOT NULL,
  `transaction_id` varchar(45) NOT NULL,
  `total_in_words` text NOT NULL,
  `total` varchar(450) NOT NULL,
  `net_revenue` varchar(450) DEFAULT NULL,
  `station` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cash_transfer`
--

/*!40000 ALTER TABLE `cash_transfer` DISABLE KEYS */;
INSERT INTO `cash_transfer` (`id`,`log_id`,`time`,`ticket_seller`,`cash_assistant`,`type`,`transaction_id`,`total_in_words`,`total`,`net_revenue`,`station`) VALUES 
 (1,'1','2012-04-23 16:47:00','1','s','allocation','2012423_4','dfsdf','2341.25','2333',''),
 (2,'1','2012-04-24 09:11:00','1','s','allocation','2012424_5','dd','1016','299',''),
 (3,'1','2012-04-24 09:19:00','1','s','remittance','2012424_6','s','1073','2777',''),
 (4,'1','2012-04-24 14:49:00','1','s','allocation','2012424_8','','531','2999',''),
 (5,'1','2012-04-25 11:37:00','1','s','allocation','2012425_9','','4045','9000',''),
 (6,'1','2012-04-27 16:19:00','1','s','allocation','2012427_12','','1015.4','2888',''),
 (7,'1','2012-04-27 16:22:00','2','s','allocation','2012427_13','','1023','',''),
 (8,'16','2012-05-14 16:07:00','1','s','allocation','2012514_14','three thousand one hundred seventy and forty centavos','3170.4','2000',''),
 (9,'17','2012-05-15 09:32:00','1','s','allocation','2012515_15','','4980','2000',''),
 (10,'47','2012-06-26 14:30:00','1','s','allocation','2012626_16','two thousand five hundred','2500','3000',''),
 (11,'48','2012-06-26 14:31:00','1','s','allocation','2012626_17','twenty thousand','20000','2000',''),
 (12,'47','2012-06-26 14:36:00','1','s','remittance','2012626_19','twenty five thousand','25000','3000',''),
 (13,'47','2012-06-26 15:14:00','1','s','allocation','2012626_21','twenty three thousand','23000','20000','');
/*!40000 ALTER TABLE `cash_transfer` ENABLE KEYS */;


--
-- Definition of table `control_cash`
--

DROP TABLE IF EXISTS `control_cash`;
CREATE TABLE `control_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `refund` varchar(450) NOT NULL,
  `unpaid_shortage` varchar(450) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_cash`
--

/*!40000 ALTER TABLE `control_cash` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_cash` ENABLE KEYS */;


--
-- Definition of table `control_sales_amount`
--

DROP TABLE IF EXISTS `control_sales_amount`;
CREATE TABLE `control_sales_amount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sjt` varchar(45) NOT NULL,
  `sjd` varchar(45) NOT NULL,
  `svt` varchar(45) NOT NULL,
  `svd` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_sales_amount`
--

/*!40000 ALTER TABLE `control_sales_amount` DISABLE KEYS */;
INSERT INTO `control_sales_amount` (`id`,`control_id`,`sjt`,`sjd`,`svt`,`svd`) VALUES 
 (1,'1','2100','300','1000','1500'),
 (2,'19','11459','','57000',''),
 (3,'17','1509','880','58800','2900'),
 (4,'21','','','',''),
 (5,'20','','','','');
/*!40000 ALTER TABLE `control_sales_amount` ENABLE KEYS */;


--
-- Definition of table `control_slip`
--

DROP TABLE IF EXISTS `control_slip`;
CREATE TABLE `control_slip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_seller` varchar(45) NOT NULL,
  `log_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_slip`
--

/*!40000 ALTER TABLE `control_slip` DISABLE KEYS */;
INSERT INTO `control_slip` (`id`,`ticket_seller`,`log_id`) VALUES 
 (1,'1','1'),
 (2,'2','1'),
 (3,'3','1'),
 (4,'4','1'),
 (5,'1','11'),
 (6,'2','11'),
 (7,'3','11'),
 (8,'4','11'),
 (9,'2','13'),
 (10,'3','13'),
 (11,'4','13'),
 (12,'1','44'),
 (13,'3','44'),
 (14,'2','44'),
 (15,'4','44'),
 (16,'2','47'),
 (17,'4','47'),
 (18,'3','47'),
 (19,'1','47'),
 (20,'1','49'),
 (21,'4','49');
/*!40000 ALTER TABLE `control_slip` ENABLE KEYS */;


--
-- Definition of table `control_unsold`
--

DROP TABLE IF EXISTS `control_unsold`;
CREATE TABLE `control_unsold` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sealed` varchar(45) DEFAULT NULL,
  `loose_good` varchar(45) DEFAULT NULL,
  `loose_defective` varchar(45) DEFAULT NULL,
  `type` varchar(45) NOT NULL,
  `transaction_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_unsold`
--

/*!40000 ALTER TABLE `control_unsold` DISABLE KEYS */;
INSERT INTO `control_unsold` (`id`,`control_id`,`sealed`,`loose_good`,`loose_defective`,`type`,`transaction_id`) VALUES 
 (1,'1','2','0','2','svt',''),
 (2,'1','1','','','sjd',''),
 (3,'19','600','','','sjt',''),
 (4,'19','','79','1','svt',''),
 (5,'17','100','86','','sjt',''),
 (6,'17','30','4','','sjd',''),
 (7,'17','400','11','1','svt',''),
 (8,'17','','11','','svd',''),
 (9,'21','2','1','2','sjt',''),
 (13,'20','','3','2','sjt','20120627_31'),
 (14,'20','','4','2','svt','20120627_31');
/*!40000 ALTER TABLE `control_unsold` ENABLE KEYS */;


--
-- Definition of table `denomination`
--

DROP TABLE IF EXISTS `denomination`;
CREATE TABLE `denomination` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cash_transfer_id` varchar(45) NOT NULL,
  `denomination` varchar(45) NOT NULL,
  `quantity` varchar(105) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `denomination`
--

/*!40000 ALTER TABLE `denomination` DISABLE KEYS */;
INSERT INTO `denomination` (`id`,`cash_transfer_id`,`denomination`,`quantity`) VALUES 
 (1,'4','200','2'),
 (2,'4','50','2'),
 (3,'4','10','3'),
 (4,'4','.25','4'),
 (5,'5','500','8'),
 (6,'5','5','9'),
 (7,'6','500','2'),
 (8,'6','5','3'),
 (9,'6','.10','4'),
 (10,'7','500','2'),
 (11,'7','10','2'),
 (12,'7','1','3'),
 (13,'8','1000','3'),
 (14,'8','50','3'),
 (15,'8','10','2'),
 (16,'8','.10','4'),
 (17,'9','500','2'),
 (18,'9','20','199'),
 (19,'10','1000','2'),
 (20,'10','500','1'),
 (21,'11','1000','20'),
 (22,'12','1000','25'),
 (23,'13','1000','23');
/*!40000 ALTER TABLE `denomination` ENABLE KEYS */;


--
-- Definition of table `extension`
--

DROP TABLE IF EXISTS `extension`;
CREATE TABLE `extension` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_seller` varchar(45) NOT NULL,
  `station` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `extension`
--

/*!40000 ALTER TABLE `extension` DISABLE KEYS */;
/*!40000 ALTER TABLE `extension` ENABLE KEYS */;


--
-- Definition of table `fare_adjustment`
--

DROP TABLE IF EXISTS `fare_adjustment`;
CREATE TABLE `fare_adjustment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `svt` varchar(45) DEFAULT NULL,
  `svd` varchar(45) DEFAULT NULL,
  `c` varchar(45) DEFAULT NULL,
  `ot` varchar(45) DEFAULT NULL,
  `fare_adjustment` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fare_adjustment`
--

/*!40000 ALTER TABLE `fare_adjustment` DISABLE KEYS */;
INSERT INTO `fare_adjustment` (`id`,`control_id`,`sjt`,`sjd`,`svt`,`svd`,`c`,`ot`,`fare_adjustment`) VALUES 
 (1,'1','20','','10','','','',''),
 (2,'17','154','2','','','','','');
/*!40000 ALTER TABLE `fare_adjustment` ENABLE KEYS */;


--
-- Definition of table `log_ticket_seller`
--

DROP TABLE IF EXISTS `log_ticket_seller`;
CREATE TABLE `log_ticket_seller` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_seller` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `ts_id` varchar(45) NOT NULL,
  `logbook_id` varchar(45) NOT NULL,
  `revolving` varchar(450) NOT NULL,
  `deposit` varchar(450) NOT NULL,
  `balance` varchar(450) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_ticket_seller`
--

/*!40000 ALTER TABLE `log_ticket_seller` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_ticket_seller` ENABLE KEYS */;


--
-- Definition of table `logbook`
--

DROP TABLE IF EXISTS `logbook`;
CREATE TABLE `logbook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station` varchar(45) NOT NULL,
  `cash_assistant` text,
  `date` datetime NOT NULL,
  `shift` varchar(45) NOT NULL,
  `initial_cash` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logbook`
--

/*!40000 ALTER TABLE `logbook` DISABLE KEYS */;
INSERT INTO `logbook` (`id`,`station`,`cash_assistant`,`date`,`shift`,`initial_cash`) VALUES 
 (1,'2','Merceditas Gutierrez','2012-04-19 00:00:00','1',NULL),
 (2,'1',NULL,'2012-05-08 00:00:00','1',NULL),
 (3,'4',NULL,'2012-05-08 00:00:00','1',NULL),
 (4,'1',NULL,'2012-05-08 00:00:00','3',NULL),
 (5,'1',NULL,'2012-05-10 00:00:00','1',NULL),
 (6,'1',NULL,'2012-04-19 00:00:00','1',NULL),
 (7,'3',NULL,'2012-04-19 00:00:00','1',NULL),
 (8,'1',NULL,'2012-05-10 00:00:00','2',NULL),
 (9,'6',NULL,'2012-05-11 00:00:00','1',NULL),
 (10,'1',NULL,'2012-05-10 00:00:00','3',NULL),
 (11,'1',NULL,'2012-05-14 00:00:00','1',NULL),
 (12,'1',NULL,'2012-05-14 00:00:00','2',NULL),
 (13,'1',NULL,'2012-05-14 00:00:00','3',NULL),
 (14,'1',NULL,'2012-05-05 00:00:00','1',NULL),
 (15,'2',NULL,'2012-04-09 00:00:00','1',NULL),
 (16,'2',NULL,'2012-04-19 00:00:00','2',NULL),
 (17,'2',NULL,'2012-04-19 00:00:00','3',NULL),
 (18,'2',NULL,'2012-04-20 00:00:00','1',NULL),
 (19,'2',NULL,'2012-04-20 00:00:00','2',NULL),
 (20,'1',NULL,'2012-04-19 00:00:00','2',NULL),
 (21,'1',NULL,'2012-05-15 00:00:00','2',NULL),
 (22,'1',NULL,'2012-05-15 00:00:00','3',NULL),
 (23,'1',NULL,'2012-05-16 00:00:00','1',NULL),
 (24,'1',NULL,'2012-05-15 00:00:00','1',NULL),
 (25,'1',NULL,'2012-04-20 00:00:00','3',NULL),
 (26,'1',NULL,'2012-04-21 00:00:00','1',NULL),
 (27,'1',NULL,'2012-05-16 00:00:00','2',NULL),
 (28,'1',NULL,'2012-05-16 00:00:00','3',NULL),
 (29,'1',NULL,'2012-05-17 00:00:00','1',NULL),
 (30,'1',NULL,'2012-05-19 00:00:00','2',NULL),
 (31,'1',NULL,'2012-05-19 00:00:00','3',NULL),
 (32,'2',NULL,'2012-04-20 00:00:00','3',NULL),
 (33,'2',NULL,'2012-04-21 00:00:00','1',NULL),
 (34,'2',NULL,'2012-04-21 00:00:00','2',NULL),
 (35,'2',NULL,'2012-04-21 00:00:00','3',NULL),
 (36,'1',NULL,'2012-05-21 00:00:00','2',NULL),
 (37,'1',NULL,'2012-05-21 00:00:00','3',NULL),
 (38,'1',NULL,'2012-05-22 00:00:00','1',NULL),
 (39,'1',NULL,'2012-05-22 00:00:00','2',NULL),
 (40,'1',NULL,'2012-05-28 00:00:00','1',NULL),
 (41,'1',NULL,'2012-05-28 00:00:00','2',NULL),
 (42,'1',NULL,'2012-05-30 00:00:00','1',NULL),
 (43,'1',NULL,'2012-05-30 00:00:00','2',NULL),
 (44,'1',NULL,'2012-06-04 00:00:00','1',NULL),
 (45,'1',NULL,'2012-06-04 00:00:00','2',NULL),
 (46,'1',NULL,'2012-06-04 00:00:00','3',NULL),
 (47,'1',NULL,'2012-06-26 00:00:00','2',NULL),
 (48,'1',NULL,'2012-06-26 00:00:00','3',NULL),
 (49,'1',NULL,'2012-06-27 00:00:00','1',NULL),
 (50,'9',NULL,'2012-06-26 00:00:00','2',NULL),
 (51,'9',NULL,'2012-06-26 00:00:00','3',NULL),
 (52,'1',NULL,'2012-06-25 00:00:00','2',NULL),
 (53,'1',NULL,'2012-06-25 00:00:00','3',NULL),
 (54,'1',NULL,'2012-06-23 00:00:00','2',NULL),
 (55,'1',NULL,'2012-06-23 00:00:00','3',NULL),
 (56,'1',NULL,'2012-06-15 00:00:00','2',NULL),
 (57,'1',NULL,'2012-06-15 00:00:00','3',NULL),
 (58,'1',NULL,'2012-06-26 00:00:00','1',NULL),
 (59,'1',NULL,'2012-06-27 00:00:00','2',NULL),
 (60,'3',NULL,'2012-06-26 00:00:00','2',NULL),
 (61,'3',NULL,'2012-06-26 00:00:00','3',NULL),
 (62,'2',NULL,'2012-06-26 00:00:00','2',NULL),
 (63,'2',NULL,'2012-06-26 00:00:00','3',NULL),
 (64,'1',NULL,'2012-06-27 00:00:00','3',NULL);
/*!40000 ALTER TABLE `logbook` ENABLE KEYS */;


--
-- Definition of table `login`
--

DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `username` varchar(45) NOT NULL,
  `password` text NOT NULL,
  `firstName` text,
  `lastName` text,
  `midInitial` text,
  `station` varchar(45) DEFAULT NULL,
  `role` varchar(45) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

/*!40000 ALTER TABLE `login` DISABLE KEYS */;
INSERT INTO `login` (`username`,`password`,`firstName`,`lastName`,`midInitial`,`station`,`role`) VALUES 
 ('admin','123456','Charito','Santos','W',NULL,'cash assistant'),
 ('auditor','123456','Conchita','Carpio-Morales',NULL,NULL,'auditor'),
 ('gibson','123456','William','Gibson',NULL,NULL,'cash assistant');
/*!40000 ALTER TABLE `login` ENABLE KEYS */;


--
-- Definition of table `pnb_deposit`
--

DROP TABLE IF EXISTS `pnb_deposit`;
CREATE TABLE `pnb_deposit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` int(10) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `cash_assistant` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `transaction_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pnb_deposit`
--

/*!40000 ALTER TABLE `pnb_deposit` DISABLE KEYS */;
INSERT INTO `pnb_deposit` (`id`,`log_id`,`time`,`cash_assistant`,`type`,`amount`,`transaction_id`) VALUES 
 (1,1,'2012-04-24 05:25:00','s','previous','2000','2012424_7'),
 (2,48,'2012-06-26 14:31:00','s','previous','20000','2012626_18'),
 (3,47,'2012-06-26 15:13:00','s','previous','300000','2012626_20');
/*!40000 ALTER TABLE `pnb_deposit` ENABLE KEYS */;


--
-- Definition of table `remittance`
--

DROP TABLE IF EXISTS `remittance`;
CREATE TABLE `remittance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `quality` varchar(45) NOT NULL,
  `good` varchar(45) NOT NULL,
  `defective` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `remittance`
--

/*!40000 ALTER TABLE `remittance` DISABLE KEYS */;
/*!40000 ALTER TABLE `remittance` ENABLE KEYS */;


--
-- Definition of table `shift`
--

DROP TABLE IF EXISTS `shift`;
CREATE TABLE `shift` (
  `shift_id` varchar(45) NOT NULL,
  `shift_name` text NOT NULL,
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shift`
--

/*!40000 ALTER TABLE `shift` DISABLE KEYS */;
INSERT INTO `shift` (`shift_id`,`shift_name`) VALUES 
 ('S1','5:00 AM - 2:00 PM'),
 ('S2','2:00 PM - 9:00 PM'),
 ('S3','9:00 PM - 5:00 AM');
/*!40000 ALTER TABLE `shift` ENABLE KEYS */;


--
-- Definition of table `station`
--

DROP TABLE IF EXISTS `station`;
CREATE TABLE `station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `station`
--

/*!40000 ALTER TABLE `station` DISABLE KEYS */;
INSERT INTO `station` (`id`,`station_name`) VALUES 
 (1,'North Avenue'),
 (2,'Quezon Avenue'),
 (3,'GMA-Kamuning'),
 (4,'Araneta-Cubao'),
 (5,'Santolan'),
 (6,'Ortigas'),
 (7,'Shaw Boulevard'),
 (8,'Boni'),
 (9,'Guadalupe'),
 (10,'Buendia'),
 (11,'Ayala'),
 (12,'Magallanes'),
 (13,'Taft');
/*!40000 ALTER TABLE `station` ENABLE KEYS */;


--
-- Definition of table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
CREATE TABLE `ticket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket`
--

/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
INSERT INTO `ticket` (`id`,`ticket_name`) VALUES 
 (1,'SJT'),
 (2,'SJD'),
 (3,'SVT'),
 (4,'SVD');
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;


--
-- Definition of table `ticket_order`
--

DROP TABLE IF EXISTS `ticket_order`;
CREATE TABLE `ticket_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sjt` varchar(45) NOT NULL,
  `sjd` varchar(45) NOT NULL,
  `svt` varchar(45) NOT NULL,
  `svd` varchar(45) NOT NULL,
  `log_id` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `ticket_seller` varchar(100) NOT NULL,
  `cash_assistant` varchar(100) NOT NULL,
  `transaction_id` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket_order`
--

/*!40000 ALTER TABLE `ticket_order` DISABLE KEYS */;
INSERT INTO `ticket_order` (`id`,`sjt`,`sjd`,`svt`,`svd`,`log_id`,`time`,`ticket_seller`,`cash_assistant`,`transaction_id`,`type`) VALUES 
 (1,'2000','1000','200','10','1','2012-04-27 15:39:00','1','s','2012427_10','allocation'),
 (2,'2000','200','1000','1123','1','2012-04-27 16:02:00','1','s','2012427_11','remittance'),
 (3,'23','45','456','33','49','2012-06-27 11:02:00','1','s','2012627_30','allocation');
/*!40000 ALTER TABLE `ticket_order` ENABLE KEYS */;


--
-- Definition of table `ticket_seller`
--

DROP TABLE IF EXISTS `ticket_seller`;
CREATE TABLE `ticket_seller` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_seller_name` varchar(450) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket_seller`
--

/*!40000 ALTER TABLE `ticket_seller` DISABLE KEYS */;
INSERT INTO `ticket_seller` (`id`,`ticket_seller_name`) VALUES 
 (1,'merceditas gutierrez'),
 (2,'ssss'),
 (3,'ssssdd'),
 (4,'weedads'),
 (5,'ma. lourdes sereno');
/*!40000 ALTER TABLE `ticket_seller` ENABLE KEYS */;


--
-- Definition of table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `log_id` varchar(45) NOT NULL,
  `log_type` varchar(45) NOT NULL,
  `transaction_type` varchar(45) NOT NULL,
  `transaction_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
INSERT INTO `transaction` (`id`,`date`,`log_id`,`log_type`,`transaction_type`,`transaction_id`) VALUES 
 (4,'2012-04-23 16:47:00','1','cash','allocation','2012423_4'),
 (5,'2012-04-24 09:11:00','1','cash','allocation','2012424_5'),
 (6,'2012-04-24 09:19:00','1','cash','remittance','2012424_6'),
 (7,'2012-04-24 05:25:00','1','cash','deposit','2012424_7'),
 (8,'2012-04-24 14:49:00','1','cash','allocation','2012424_8'),
 (9,'2012-04-25 11:37:00','1','cash','allocation','2012425_9'),
 (10,'2012-04-27 15:39:00','1','ticket','allocation','2012427_10'),
 (11,'2012-04-27 16:02:00','1','ticket','remittance','2012427_11'),
 (12,'2012-04-27 16:19:00','1','cash','allocation','2012427_12'),
 (13,'2012-04-27 16:22:00','1','cash','allocation','2012427_13'),
 (14,'2012-05-14 16:07:00','16','cash','allocation','2012514_14'),
 (15,'2012-05-15 09:32:00','17','cash','allocation','2012515_15'),
 (16,'2012-06-26 14:30:00','47','cash','allocation','2012626_16'),
 (17,'2012-06-26 14:31:00','48','cash','allocation','2012626_17'),
 (18,'2012-06-26 14:31:00','48','cash','deposit','2012626_18'),
 (19,'2012-06-26 14:36:00','47','cash','remittance','2012626_19'),
 (20,'2012-06-26 15:13:00','47','cash','deposit','2012626_20'),
 (21,'2012-06-26 15:14:00','47','cash','allocation','2012626_21'),
 (31,'2012-06-27 14:04:00','49','initial','allocation','20120627_31');
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
