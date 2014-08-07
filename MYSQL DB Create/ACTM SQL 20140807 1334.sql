-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.27-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema actm
--

CREATE DATABASE IF NOT EXISTS actm;
USE actm;

--
-- Temporary table structure for view `additional_allocation`
--
DROP TABLE IF EXISTS `additional_allocation`;
DROP VIEW IF EXISTS `additional_allocation`;
CREATE TABLE `additional_allocation` (
  `sjt` double,
  `sjd` double,
  `svt` double,
  `svd` double,
  `sjt_loose` double,
  `sjd_loose` double,
  `svt_loose` double,
  `svd_loose` double,
  `control_id` varchar(45)
);

--
-- Temporary table structure for view `control_remittance`
--
DROP TABLE IF EXISTS `control_remittance`;
DROP VIEW IF EXISTS `control_remittance`;
CREATE TABLE `control_remittance` (
  `remit_id` int(10) unsigned,
  `remit_ticket_seller` varchar(45),
  `remit_log` varchar(45),
  `control_id` varchar(45),
  `id` int(10) unsigned,
  `ticket_seller` varchar(45),
  `log_id` varchar(45),
  `unit` varchar(45),
  `reference_id` varchar(45),
  `station` varchar(45),
  `status` varchar(45)
);

--
-- Definition of table `allocation`
--

DROP TABLE IF EXISTS `allocation`;
CREATE TABLE `allocation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `initial` varchar(45) NOT NULL,
  `additional` varchar(45) DEFAULT NULL,
  `transaction_id` varchar(45) NOT NULL,
  `initial_loose` varchar(45) DEFAULT NULL,
  `additional_loose` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45876 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `allocation`
--

/*!40000 ALTER TABLE `allocation` DISABLE KEYS */;
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
  PRIMARY KEY (`id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1967 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `beginning_balance_cash`
--

/*!40000 ALTER TABLE `beginning_balance_cash` DISABLE KEYS */;
/*!40000 ALTER TABLE `beginning_balance_cash` ENABLE KEYS */;


--
-- Definition of table `beginning_balance_sjt`
--

DROP TABLE IF EXISTS `beginning_balance_sjt`;
CREATE TABLE `beginning_balance_sjt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `sjt_loose` varchar(45) DEFAULT NULL,
  `sjd_loose` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1934 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `beginning_balance_sjt`
--

/*!40000 ALTER TABLE `beginning_balance_sjt` DISABLE KEYS */;
/*!40000 ALTER TABLE `beginning_balance_sjt` ENABLE KEYS */;


--
-- Definition of table `beginning_balance_svt`
--

DROP TABLE IF EXISTS `beginning_balance_svt`;
CREATE TABLE `beginning_balance_svt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `svt` varchar(45) NOT NULL,
  `svd` varchar(45) NOT NULL,
  `svt_loose` varchar(45) NOT NULL,
  `svd_loose` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1923 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `beginning_balance_svt`
--

/*!40000 ALTER TABLE `beginning_balance_svt` DISABLE KEYS */;
/*!40000 ALTER TABLE `beginning_balance_svt` ENABLE KEYS */;


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `beginning_balance_tickets`
--

/*!40000 ALTER TABLE `beginning_balance_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `beginning_balance_tickets` ENABLE KEYS */;


--
-- Definition of table `cash_remittance`
--

DROP TABLE IF EXISTS `cash_remittance`;
CREATE TABLE `cash_remittance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(45) DEFAULT NULL,
  `log_id` varchar(45) DEFAULT NULL,
  `ticket_seller` varchar(45) NOT NULL,
  `control_remittance` double DEFAULT NULL,
  `cash_remittance` double DEFAULT NULL,
  `control_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29452 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cash_remittance`
--

/*!40000 ALTER TABLE `cash_remittance` DISABLE KEYS */;
/*!40000 ALTER TABLE `cash_remittance` ENABLE KEYS */;


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
  `reference_id` varchar(45) NOT NULL,
  `unit` varchar(45) DEFAULT NULL,
  `destination_ca` varchar(45) DEFAULT NULL,
  `control_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `ticket_seller` (`ticket_seller`)
) ENGINE=InnoDB AUTO_INCREMENT=28713 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cash_transfer`
--

/*!40000 ALTER TABLE `cash_transfer` DISABLE KEYS */;
/*!40000 ALTER TABLE `cash_transfer` ENABLE KEYS */;


--
-- Definition of table `control_cash`
--

DROP TABLE IF EXISTS `control_cash`;
CREATE TABLE `control_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `unpaid_shortage` varchar(450) DEFAULT NULL,
  `overage` varchar(45) DEFAULT NULL,
  `cash_advance` varchar(45) DEFAULT NULL,
  `ot` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10711 DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10834 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_sales_amount`
--

/*!40000 ALTER TABLE `control_sales_amount` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_sales_amount` ENABLE KEYS */;


--
-- Definition of table `control_slip`
--

DROP TABLE IF EXISTS `control_slip`;
CREATE TABLE `control_slip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_seller` varchar(45) NOT NULL,
  `log_id` varchar(45) NOT NULL,
  `unit` varchar(45) NOT NULL,
  `reference_id` varchar(45) DEFAULT NULL,
  `station` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_id` (`log_id`),
  KEY `unit` (`unit`)
) ENGINE=InnoDB AUTO_INCREMENT=11649 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_slip`
--

/*!40000 ALTER TABLE `control_slip` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_slip` ENABLE KEYS */;


--
-- Definition of table `control_sold`
--

DROP TABLE IF EXISTS `control_sold`;
CREATE TABLE `control_sold` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `svt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `svd` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11514 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_sold`
--

/*!40000 ALTER TABLE `control_sold` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_sold` ENABLE KEYS */;


--
-- Definition of table `control_tracking`
--

DROP TABLE IF EXISTS `control_tracking`;
CREATE TABLE `control_tracking` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `log_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22574 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_tracking`
--

/*!40000 ALTER TABLE `control_tracking` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_tracking` ENABLE KEYS */;


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
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30065 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_unsold`
--

/*!40000 ALTER TABLE `control_unsold` DISABLE KEYS */;
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
  PRIMARY KEY (`id`),
  KEY `cash_transfer_id` (`cash_transfer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=107069 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `denomination`
--

/*!40000 ALTER TABLE `denomination` DISABLE KEYS */;
/*!40000 ALTER TABLE `denomination` ENABLE KEYS */;


--
-- Definition of table `discount`
--

DROP TABLE IF EXISTS `discount`;
CREATE TABLE `discount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sj` varchar(45) DEFAULT NULL,
  `sv` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10928 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discount`
--

/*!40000 ALTER TABLE `discount` DISABLE KEYS */;
/*!40000 ALTER TABLE `discount` ENABLE KEYS */;


--
-- Definition of table `discrepancy`
--

DROP TABLE IF EXISTS `discrepancy`;
CREATE TABLE `discrepancy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `amount` varchar(45) NOT NULL,
  `classification` varchar(45) NOT NULL,
  `reported` varchar(45) DEFAULT NULL,
  `reference_id` varchar(45) DEFAULT NULL,
  `log_id` varchar(45) DEFAULT NULL,
  `ticket_seller` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `ticket_seller` (`ticket_seller`)
) ENGINE=InnoDB AUTO_INCREMENT=7789 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discrepancy`
--

/*!40000 ALTER TABLE `discrepancy` DISABLE KEYS */;
/*!40000 ALTER TABLE `discrepancy` ENABLE KEYS */;


--
-- Definition of table `discrepancy_ticket`
--

DROP TABLE IF EXISTS `discrepancy_ticket`;
CREATE TABLE `discrepancy_ticket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `classification` varchar(45) DEFAULT NULL,
  `ticket_type` varchar(45) DEFAULT NULL,
  `reported` varchar(45) DEFAULT NULL,
  `reference_id` varchar(45) DEFAULT NULL,
  `log_id` varchar(45) DEFAULT NULL,
  `ticket_seller` varchar(45) DEFAULT NULL,
  `price` varchar(45) DEFAULT NULL,
  `control_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=279 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discrepancy_ticket`
--

/*!40000 ALTER TABLE `discrepancy_ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `discrepancy_ticket` ENABLE KEYS */;


--
-- Definition of table `ending_balance_cash`
--

DROP TABLE IF EXISTS `ending_balance_cash`;
CREATE TABLE `ending_balance_cash` (
  `log_id` int(10) unsigned NOT NULL,
  `revolving_fund` varchar(45) DEFAULT NULL,
  `for_deposit` varchar(45) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1907 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ending_balance_cash`
--

/*!40000 ALTER TABLE `ending_balance_cash` DISABLE KEYS */;
/*!40000 ALTER TABLE `ending_balance_cash` ENABLE KEYS */;


--
-- Definition of table `ending_balance_sjt`
--

DROP TABLE IF EXISTS `ending_balance_sjt`;
CREATE TABLE `ending_balance_sjt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `sjt_loose` varchar(45) DEFAULT NULL,
  `sjd_loose` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1861 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ending_balance_sjt`
--

/*!40000 ALTER TABLE `ending_balance_sjt` DISABLE KEYS */;
/*!40000 ALTER TABLE `ending_balance_sjt` ENABLE KEYS */;


--
-- Definition of table `ending_balance_svt`
--

DROP TABLE IF EXISTS `ending_balance_svt`;
CREATE TABLE `ending_balance_svt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `svt` varchar(45) NOT NULL,
  `svd` varchar(45) NOT NULL,
  `svt_loose` varchar(45) NOT NULL,
  `svd_loose` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1846 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ending_balance_svt`
--

/*!40000 ALTER TABLE `ending_balance_svt` DISABLE KEYS */;
/*!40000 ALTER TABLE `ending_balance_svt` ENABLE KEYS */;


--
-- Definition of table `extension`
--

DROP TABLE IF EXISTS `extension`;
CREATE TABLE `extension` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station` varchar(45) NOT NULL,
  `extension` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `extension`
--

/*!40000 ALTER TABLE `extension` DISABLE KEYS */;
INSERT INTO `extension` (`id`,`station`,`extension`) VALUES 
 (2,'2','3'),
 (3,'4','5'),
 (4,'6','8'),
 (5,'9','10'),
 (6,'11','12');
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
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2367 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fare_adjustment`
--

/*!40000 ALTER TABLE `fare_adjustment` DISABLE KEYS */;
/*!40000 ALTER TABLE `fare_adjustment` ENABLE KEYS */;


--
-- Definition of table `fare_adjustment_tickets`
--

DROP TABLE IF EXISTS `fare_adjustment_tickets`;
CREATE TABLE `fare_adjustment_tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `svt` varchar(45) DEFAULT NULL,
  `svd` varchar(45) DEFAULT NULL,
  `c` varchar(45) DEFAULT NULL,
  `ot` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2367 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fare_adjustment_tickets`
--

/*!40000 ALTER TABLE `fare_adjustment_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `fare_adjustment_tickets` ENABLE KEYS */;


--
-- Definition of table `log_history`
--

DROP TABLE IF EXISTS `log_history`;
CREATE TABLE `log_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `log_id` varchar(45) NOT NULL,
  `date` datetime NOT NULL,
  `login` datetime DEFAULT NULL,
  `logout` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2646 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_history`
--

/*!40000 ALTER TABLE `log_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_history` ENABLE KEYS */;


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
-- Definition of table `log_users`
--

DROP TABLE IF EXISTS `log_users`;
CREATE TABLE `log_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `log_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_users`
--

/*!40000 ALTER TABLE `log_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_users` ENABLE KEYS */;


--
-- Definition of table `logbook`
--

DROP TABLE IF EXISTS `logbook`;
CREATE TABLE `logbook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station` varchar(45) DEFAULT NULL,
  `cash_assistant` text,
  `date` datetime NOT NULL,
  `shift` varchar(45) DEFAULT NULL,
  `initial_cash` varchar(45) DEFAULT NULL,
  `revenue` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=1971 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logbook`
--

/*!40000 ALTER TABLE `logbook` DISABLE KEYS */;
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
  `shift` varchar(45) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `id` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`username`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

/*!40000 ALTER TABLE `login` DISABLE KEYS */;
INSERT INTO `login` (`username`,`password`,`firstName`,`lastName`,`midInitial`,`station`,`role`,`shift`,`position`,`id`,`status`) VALUES 
 ('','123456','MARLON','BUNITE','C',NULL,'administrator',NULL,'ADMINISTRATOR','999','active'),
 ('123','123','123','123','123',NULL,'administrator',NULL,'123','123','active'),
 ('1999-0065','111872','MIKAELA','ABALOYAN','MIRADOR',NULL,'cash assistant',NULL,'CASHIER II','4','active'),
 ('1999-0066','wingabmie','EMELITA','GALOLO','LOBRAMONTE',NULL,'cash assistant',NULL,'CASHIER II','278','active'),
 ('1999-0067','1209','MARY JOY','LANA','EUGENIO',NULL,'cash assistant',NULL,'CASHIER II','335','active'),
 ('1999-0068','030508','MARISSA','PASCUA','BAUTISTA',NULL,'cash assistant',NULL,'CASHIER II','482','active'),
 ('1999-0069','092008','JOAN','PORNASDORO','BOLANO',NULL,'cash assistant',NULL,'CASHIER II','504','active'),
 ('1999-0070','42227','LUCITA','VILLARUEL','ESPECTACION',NULL,'cash assistant',NULL,'CASHIER II','649','active'),
 ('1999-0104','miggs','ALINE','CALIBOSO','MENOR',NULL,'cash assistant',NULL,'CASHIER II','131','active'),
 ('1999-0147','superV','EMMANUEL','STA  ANA','GAMBOL',NULL,'administrator',NULL,'CASHIER IV','611','active'),
 ('1999-0148','Bisors','HERNANDO JR ','CATINDIG','BURGOS',NULL,'administrator',NULL,'CASHIER III','152','active'),
 ('1999-0149','1418','NOELs','ABALOYAN','2222',NULL,'cash assistant',NULL,'CASHIER II','5','active'),
 ('1999-0150','848484','JEANILYN','CABRALES','PASCUAL ',NULL,'cash assistant',NULL,'CASHIER II','127','active'),
 ('1999-0152','ARVILC','ALENA','CORTADO','SESCON',NULL,'cash assistant',NULL,'CASHIER II','165','active'),
 ('1999-0153','FACES','LILIA','FABRO','VALDEZ',NULL,'cash assistant',NULL,'CASHIER II','261','active'),
 ('1999-0154','0638','ESTELA','GALBIZO','GALANIDO',NULL,'cash assistant',NULL,'CASHIER II','275','active'),
 ('1999-0155','Leng5977','RODELYNN','GARCIA','LLORA',NULL,'cash assistant',NULL,'CASHIER II','283','active'),
 ('1999-0156','akoito','RONALDO','GRAVADOR','ORIGEN',NULL,'cash assistant',NULL,'CASHIER II','299','active'),
 ('1999-0176','Bisors','ARNEL','ASTETE','BALTAZAR',NULL,'administrator',NULL,'CASHIER III','61','active'),
 ('1999-0177','Bisors','RUSTICO','CARREON','RAMOS',NULL,'administrator',NULL,'CASHIER III','145','active'),
 ('1999-0178','Bisors','MARIA LOURDES','COLINARES','CAMPOSANO',NULL,'administrator',NULL,'CASHIER III','158','active'),
 ('1999-0179','Bisors','ALICIA','DELA CRUZ','VERGEL',NULL,'administrator',NULL,'CASHIER III','203','active'),
 ('1999-0180','Bisors','DANILO','DIZON','JARIEL',NULL,'administrator',NULL,'CASHIER III','234','active'),
 ('1999-0181','Bisors','MARILYN','LACSINTO','DIMALANTA',NULL,'administrator',NULL,'CASHIER III','331','active'),
 ('1999-0182','Bisors','RICK JAYSON','MERCADO','ILAO',NULL,'administrator',NULL,'CASHIER III','408','active'),
 ('1999-0183','Bisors','JENNY','OCAMPO','BELEN',NULL,'administrator',NULL,'CASHIER III','446','active'),
 ('1999-0185','Bisors','DANILO','TUMANENG','DE CASTRO',NULL,'administrator',NULL,'CASHIER III','634','active'),
 ('1999-0186','gabbea_48','SARAH','ALGARA','CAPUS',NULL,'cash assistant',NULL,'CASHIER II','35','active'),
 ('1999-0187','481100','REWEL','BALATBAT','PANGILINAN',NULL,'cash assistant',NULL,'CASHIER II','76','active'),
 ('1999-0188','daleat24','LOLITA','BARRAMEDA','UMANG',NULL,'cash assistant',NULL,'CASHIER II','89','active'),
 ('1999-0189','2714','SHARON','BONZA','EMING',NULL,'cash assistant',NULL,'CASHIER II','115','active'),
 ('1999-0190','KAEANN','ROMMEL','CAPAROS','BERGAMO',NULL,'cash assistant',NULL,'CASHIER II','134','active'),
 ('1999-0191','otep06','JOSEPH ','CUERDO','DIONISIO',NULL,'cash assistant',NULL,'CASHIER II','172','active'),
 ('1999-0192','92172','MILAGROS','DE LEON','PAGLINAWAN',NULL,'cash assistant',NULL,'CASHIER II','192','active'),
 ('1999-0193','AM727','JASON','DELA CRUZ','GUTIERREZ',NULL,'cash assistant',NULL,'CASHIER II','207','active'),
 ('1999-0195','malko','REGINA','LIMBO','PINGUL',NULL,'cash assistant',NULL,'CASHIER II','347','active'),
 ('1999-0196','090105','MAILA','LOGRONIO','ROXAS',NULL,'cash assistant',NULL,'CASHIER II','352','active'),
 ('1999-0197','bebiecarol29','CAROLINA','MAGADAN','PIAO',NULL,'cash assistant',NULL,'CASHIER II','369','active'),
 ('1999-0198','mcp1112','MICHAEL','PAGSANJAN','CUNANAN',NULL,'cash assistant',NULL,'CASHIER II','465','active'),
 ('1999-0199','598447','EMMANUEL','PAGUIO','SINSON',NULL,'cash assistant',NULL,'CASHIER II','468','active'),
 ('1999-0201','weejj','WELMER','PORNASDORO','CATALLA',NULL,'cash assistant',NULL,'CASHIER II','505','active'),
 ('1999-0203','chin','JENNIFER','DITA','RAEL',NULL,'cash assistant',NULL,'CASHIER II','232','active'),
 ('1999-0204','111609','MARLON','RAQUION','SANTOS',NULL,'cash assistant',NULL,'CASHIER II','524','active'),
 ('1999-0205','122426','MARITESS','RONQUILLO','JACINTO',NULL,'cash assistant',NULL,'CASHIER II','553','active'),
 ('1999-0206','mabs29','MARELYN ','SERRANO','BALLADA',NULL,'cash assistant',NULL,'CASHIER II','599','active'),
 ('1999-0207','022175','VIENA-RISSA','SOTELO','MAGNAYE',NULL,'cash assistant',NULL,'CASHIER II','610','active'),
 ('1999-0208','TRAX77','FELIX ANGELO','TAMAYO','RAMIZO',NULL,'cash assistant',NULL,'CASHIER II','616','active'),
 ('1999-0209','alemrac27','MA  CARMELA','TOCA','LUCELO',NULL,'cash assistant',NULL,'CASHIER II','623','active'),
 ('1999-0213','081673','JANE','OSIO','CERVANTES',NULL,'cash assistant',NULL,'CASHIER II','459','active'),
 ('2000-0239','2794','LOURDES','ROSALIN','SENOLOS',NULL,'cash assistant',NULL,'CASHIER II','557','active'),
 ('2000-0250','Bisors','OFELIA','BENEDICTO','DAQUIOAG',NULL,'administrator',NULL,'CASHIER III','104','active'),
 ('2000-0251','Bisors','FORTUNATO','IDEA','REYNOSO',NULL,'administrator',NULL,'CASHIER III','317','active'),
 ('2000-0252','RICASTRO','RICARDO','ASTROLOGO','REYES',NULL,'cash assistant',NULL,'CASHIER II','63','active'),
 ('2000-0255','454882','NELSON','FERNANDO','CANO',NULL,'cash assistant',NULL,'CASHIER II','266','active'),
 ('2000-0256','828545','RAMIL','HERNANDEZ','REYES',NULL,'cash assistant',NULL,'CASHIER II','312','active'),
 ('2000-0257','1014','JANE','MENDOZA','ORCA',NULL,'cash assistant',NULL,'CASHIER II','405','active'),
 ('2000-0258','120309','ELIZABETH','METIAM','PABLO',NULL,'cash assistant',NULL,'CASHIER II','412','active'),
 ('2000-0259','1021','MA  LUISA','PASTORIN','AURE',NULL,'cash assistant',NULL,'CASHIER II','485','active'),
 ('2000-0261','628810','MARIBEL','UNABIA','REDUCINDO',NULL,'cash assistant',NULL,'CASHIER II','635','active'),
 ('2000-0308','10269','ARMIDA','ARAMBULO','LERUM',NULL,'cash assistant',NULL,'CASHIER II','54','active'),
 ('2000-0309','0313','CHERYL ANN','CARINGAL','CARANDANG',NULL,'cash assistant',NULL,'CASHIER II','141','active'),
 ('2000-0310','kambal','CERES','DIAZ','SATURAY',NULL,'cash assistant',NULL,'CASHIER II','221','active'),
 ('2000-0311','natnat','BRIAN','LUCIANO','FABIAN',NULL,'cash assistant',NULL,'CASHIER II','359','active'),
 ('2000-0312','NAZI','ERWIN','MALABANAN','S',NULL,'cash assistant',NULL,'CASHIER II','381','active'),
 ('2000-0313','m2623','MARIA AURORA','BARRETE','LUMBANG',NULL,'cash assistant',NULL,'CASHIER II','90','active'),
 ('2005-0488','04281961','EDGARDO','SATOQUIA','TOLENTINO',NULL,'cash assistant',NULL,'CASHIER II','593','active'),
 ('2008-0624','dylantweety','MA TERESITA','QUILLO ','CAMPOMANES',NULL,'cash assistant',NULL,'CASHIER II','512','active');
/*!40000 ALTER TABLE `login` ENABLE KEYS */;


--
-- Definition of table `physically_defective`
--

DROP TABLE IF EXISTS `physically_defective`;
CREATE TABLE `physically_defective` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `svt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `svd` varchar(45) DEFAULT NULL,
  `date` datetime NOT NULL,
  `ticket_seller` varchar(45) DEFAULT NULL,
  `station` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=423 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `physically_defective`
--

/*!40000 ALTER TABLE `physically_defective` DISABLE KEYS */;
/*!40000 ALTER TABLE `physically_defective` ENABLE KEYS */;


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
  `reference_id` varchar(45) DEFAULT NULL,
  `bank_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1042 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pnb_deposit`
--

/*!40000 ALTER TABLE `pnb_deposit` DISABLE KEYS */;
/*!40000 ALTER TABLE `pnb_deposit` ENABLE KEYS */;


--
-- Definition of table `refund`
--

DROP TABLE IF EXISTS `refund`;
CREATE TABLE `refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sj` varchar(45) DEFAULT NULL,
  `sv` varchar(45) DEFAULT NULL,
  `sj_amount` double DEFAULT NULL,
  `sv_amount` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10701 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `refund`
--

/*!40000 ALTER TABLE `refund` DISABLE KEYS */;
/*!40000 ALTER TABLE `refund` ENABLE KEYS */;


--
-- Definition of table `remittance`
--

DROP TABLE IF EXISTS `remittance`;
CREATE TABLE `remittance` (
  `id` int(10) unsigned NOT NULL,
  `control_id` varchar(45) DEFAULT NULL,
  `ticket_seller` varchar(45) DEFAULT NULL,
  `log_id` varchar(45) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`),
  KEY `ticket_seller` (`ticket_seller`),
  KEY `log_id` (`log_id`)
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
 ('S1','5:00 AM - 1:00 PM'),
 ('S2','1:00 PM - 9:00 PM'),
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
  `classification` varchar(45) NOT NULL,
  `sjt_loose` varchar(45) DEFAULT NULL,
  `sjd_loose` varchar(45) DEFAULT NULL,
  `svt_loose` varchar(45) DEFAULT NULL,
  `svd_loose` varchar(45) DEFAULT NULL,
  `unit` varchar(45) DEFAULT NULL,
  `reference_id` varchar(45) DEFAULT NULL,
  `station` varchar(45) DEFAULT NULL,
  `control_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3679 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket_order`
--

/*!40000 ALTER TABLE `ticket_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket_order` ENABLE KEYS */;


--
-- Definition of table `ticket_seller`
--

DROP TABLE IF EXISTS `ticket_seller`;
CREATE TABLE `ticket_seller` (
  `id` varchar(45) NOT NULL DEFAULT '0',
  `ticket_seller_name` varchar(450) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL,
  `employee_number` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket_seller`
--

/*!40000 ALTER TABLE `ticket_seller` DISABLE KEYS */;
INSERT INTO `ticket_seller` (`id`,`ticket_seller_name`,`first_name`,`last_name`,`middle_name`,`position`,`employee_number`,`status`) VALUES 
 ('0490',NULL,'ELIZABETH','FAJARDO','TAMPIL','TICKET SELLER II','2003-0436','active'),
 ('0491',NULL,'MARIA AMALIA','CACHUELA','PEREÑA','TICKET SELLER III','2003-0446','active'),
 ('0494',NULL,'ANSTRELL','TOBIAS','DE LEON','TICKET SELLER II','2003-0449','active'),
 ('0577',NULL,'JESPER','MAGAT','MIRANDA','TICKET SELLER II','2003-0437','active'),
 ('0589',NULL,'CHRISTOPHER SAMUEL','PINEDA','PINGUL','TICKET SELLER II','2003-0444','active'),
 ('0615',NULL,'HELEN','LANZAR','FLORES','TICKET SELLER II','2003-0447','active'),
 ('0617',NULL,'DONALD','ZOLETA','BEBIDA','TICKET SELLER II','2003-0441','active'),
 ('0630',NULL,'MARICEL','RIVERA','RAVINO','TICKET SELLER III','2003-0439','active'),
 ('0633',NULL,'JENNIFER','GRATUITO','EMBOG','TICKET SELLER II','2005-0508','active'),
 ('0658',NULL,'OFELIA','MUTYA','EDNACO','TICKET SELLER II','2003-0438','active'),
 ('0672',NULL,'MARIA RENITA','ROSALES','MATURAN','TICKET SELLER II','2003-0440','active'),
 ('0757',NULL,'WILFREDO','LACATAN','SALAZAR','TICKET SELLER II','2005-0501','active'),
 ('0759',NULL,'JASMIN','ROSARIO','AVILA','TICKET SELLER II','2005-0509','active'),
 ('0760',NULL,'ERALYN','ATIBAGOS','AFABLE','TICKET SELLER II','2005-0506','active'),
 ('0761',NULL,'CLYDE','BELARMINO','ANDRADE','TICKET SELLER II','2005-0507','active'),
 ('0763',NULL,'FRANKLIN JR ','RAMBAYON','PAGDILAO','TICKET SELLER III','2006-0522','active'),
 ('0764',NULL,'RODOLFO JR ','RAMIREZ','VILORIA','TICKET SELLER II','2006-0523','active'),
 ('0765',NULL,'MARGARET','PALACIO','NER','TICKET SELLER II','2006-0521','active'),
 ('0766',NULL,'HENRICH','LOPENA','ALCANTARA','TICKET SELLER II','2006-0520','active'),
 ('0767',NULL,'JOGEN','CHAVEZ','ALOJADO','TICKET SELLER II','2006-0524','active'),
 ('0770',NULL,'VIVIAN','NOBLEZADA','GUMABON','TICKET SELLER III','2006-0536','active'),
 ('0771',NULL,'HAROLD','CUNADA','VILLA','TICKET SELLER II','2006-0530','active'),
 ('0772',NULL,'HAYDEE','ABELA','HERNANDEZ','TICKET SELLER II','2006-0532','active'),
 ('0773',NULL,'WINNIEFER','MENCIAS','PANGANIBAN','TICKET SELLER II','2006-0535','active'),
 ('0774',NULL,'ALVIN','MATIC','GUANSING','TICKET SELLER II','2006-0534','active'),
 ('0776',NULL,'NOEL','ELEMOS','MORALES','TICKET SELLER II','2006-0531','active'),
 ('0778',NULL,'MARY ROSE','MARTIN','CRUZ','TICKET SELLER II','2006-0533','active'),
 ('0779',NULL,'RICHARD','NIEVES','PIZARRO','TICKET SELLER II','2006-0540','active'),
 ('0780',NULL,'LEONID','DEZA','ANDRADE','TICKET SELLER II','2006-0538','active'),
 ('0784',NULL,'LEA','MEDINA','FEDERICO','TICKET SELLER II','2006-0539','active'),
 ('0785',NULL,'JAY','QUIAMCO','PANANGIT','TICKET SELLER II','2006-0545','active'),
 ('0786',NULL,'RACQUEL','GERONIMO','SUIZA','TICKET SELLER II','2007-0549','active'),
 ('0787',NULL,'ELINORE','ADOBAS','VERTUDAZO','TICKET SELLER II','2007-0551','active'),
 ('0789',NULL,'JOSEPHINE','JULIO','CAPALAD','TICKET SELLER II','2007-0550','active'),
 ('0790',NULL,'NOEMI','CORPORAL','OCASLA','TICKET SELLER II','2007-0556','active'),
 ('0791',NULL,'JOESEPH CARMELO','DEL ROSARIO','GAVINO','TICKET SELLER II','2007-0561','active'),
 ('0793',NULL,'BUENAFE','GARCES','RAMOS','TICKET SELLER II','2007-0564','active'),
 ('0794',NULL,'CONSOLACION','LAPECIROS','JAMINAL','TICKET SELLER III','2006-0510','active'),
 ('0796',NULL,'NORILYN','SANTIDAD','GUILLERGAN','TICKET SELLER III','2002-0389','active'),
 ('0798',NULL,'LEAH JESSICA','ROSALES','TIMKANG','TICKET SELLER II','2008-0580','active'),
 ('0799',NULL,'CANDELARIA','ROMULO','SALIVIO','TICKET SELLER II','2008-0579','active'),
 ('0800',NULL,'EDUARDO JR ','DALUNOS','LACHICA','TICKET SELLER II','2008-0575','active'),
 ('0801',NULL,'JUVIE','MUNOZ','ROJAS','TICKET SELLER II','2008-0578','active'),
 ('0802',NULL,'JEAN','APACIBLE','GURA','TICKET SELLER II','2008-0577','active'),
 ('0803',NULL,'EVELYN','ASEO','BAGUISI','TICKET SELLER II','2008-0573','active'),
 ('0804',NULL,'MICHAEL','BAUTISTA','NULUD','TICKET SELLER II','2008-0574','active'),
 ('0805',NULL,'KRIS IAN','GUERRERO','BARRIOS','TICKET SELLER II','2008-0576','active'),
 ('0806',NULL,'LIEZEL','BENITEZ','EVANGELISTA','TICKET SELLER II','2008-0582','active'),
 ('0807',NULL,'RESTY','SOTAYCO','DELA CRUZ','TICKET SELLER II','2008-0600','active'),
 ('0808',NULL,'ROYAN','MABBORANG','BURCE','TICKET SELLER II','2008-0594','active'),
 ('0809',NULL,'JAKIE LOU','APARECE','ALVAREZ','TICKET SELLER II','2008-0584','active'),
 ('0810',NULL,'RAYMOND','NIVAL','PAPA','TICKET SELLER II','2008-0595','active'),
 ('0811',NULL,'JENNIFER  ','PERONILLA','TULIAO','TICKET SELLER II','2008-0602','active'),
 ('0812',NULL,'MARIA FATIMA','PAREDES','PEÑAFIEL','TICKET SELLER II','2008-0596','active'),
 ('0813',NULL,'RYAN','QUINDOZA','MONTEROSO','TICKET SELLER II','2008-0599','active'),
 ('0814',NULL,'MARVIN','EROSA','CAMBARI','TICKET SELLER II','2008-0589','active'),
 ('0815',NULL,'RACHEL ANN','PAGSANJAN','PEREZ','TICKET SELLER II','2008-0597','active'),
 ('0816',NULL,'PATTY ROSE','PLATON','BELCHEZ','TICKET SELLER II','2008-0598','active'),
 ('0817',NULL,'JOY','GALLEGOS','ABO','TICKET SELLER II','2008-0590','active'),
 ('0818',NULL,'ARNEL','GRAVADOR','EVANGELISTA','TICKET SELLER II','2008-0591','active'),
 ('0819',NULL,'VIRGINIA','LAPULAPU','SALIBO','TICKET SELLER II','2008-0592','active'),
 ('0820',NULL,'IRVIN','BERNAL','POÑEGAL','TICKET SELLER II','2008-0585','active'),
 ('0821',NULL,'RAQUEL','DACILLO','LOS BAÑOS','TICKET SELLER II','2008-0588','active'),
 ('0822',NULL,'RONA','CONSEJERO','QUIMINALES','TICKET SELLER II','2008-0587','active'),
 ('0823',NULL,'RODOLFO JR ','LIM','VILLENA','TICKET SELLER II','2008-0593','active'),
 ('0824',NULL,'ELOISA','BUNITE','AZUPARDO','TICKET SELLER II','2008-0586','active'),
 ('0825',NULL,'MARCIAL JR ','TORALLO','RICARDO','TICKET SELLER II','2008-0601','active'),
 ('0826',NULL,'CECILE','SORIAGA','PANTALEON','TICKET SELLER II','2008-0583','active'),
 ('0827',NULL,'JENNIFER AUBREY','CAPIO','LIGTAS','TICKET SELLER II','2008-0617','active'),
 ('0828',NULL,'JOCELYN','VELASCO','ORUGA','TICKET SELLER II','2008-0627','active'),
 ('0829',NULL,'JHONALYN','EVANGELISTA','JACOB','TICKET SELLER II','2008-0619','active'),
 ('0831',NULL,' JOY-ANN','PANGANIBAN','RAMOS','TICKET SELLER II','2008-0623','active'),
 ('0832',NULL,'MANILYN','RAMOS','VIDALLON','TICKET SELLER II','2008-0625','active'),
 ('0834',NULL,'BEVERLY HAZEL ','PACIS','OLIVEROS','TICKET SELLER II','2008-0622','active'),
 ('0835',NULL,'ALEC MARTIN','ALSISTO','MIRANDO','TICKET SELLER II','2008-0615','active'),
 ('0837',NULL,'CHRISTINE JOY','OBISPO','DALANGIN','TICKET SELLER II','2008-0621','active'),
 ('0838',NULL,'PRECY','DOMINGO','BARCENA','TICKET SELLER II','2008-0618','active'),
 ('0839',NULL,'MAE','AGUSTIN','DUKA','TICKET SELLER II','2008-0614','active'),
 ('0840',NULL,'JEANETTE','MAGLINES','PACALDO','TICKET SELLER II','2009-0634','active'),
 ('0841',NULL,'SHERWIN','BOLTRON','DE REAL','TICKET SELLER II','2009-0630','active'),
 ('0842',NULL,'ROUDOLFH','SISON','FIDEL','TICKET SELLER II','2009-0635','active'),
 ('0843',NULL,'MARY JUNE','DAVIN','MACATIGBAC','TICKET SELLER II','2009-0633','active'),
 ('0844',NULL,'RAQUEL','CRUZ','YLAYA','TICKET SELLER II','2009-0631','active'),
 ('0845',NULL,'ARLINE','GLIOCAM','NACARIO','TICKET SELLER II','2009-0632','active'),
 ('0846',NULL,'RONELYN','BARBACENA','BENAVENTE','TICKET SELLER II','2009-0628','active'),
 ('0847',NULL,'MARIA ROSARIO','BERNARDO','VENTANILLA','TICKET SELLER II','2009-0629','active'),
 ('0849',NULL,'ERICKSON','BAUTISTA','DELA CRUZ','TICKET SELLER II','2009-0650','active'),
 ('0850',NULL,'SHERRY ANN','AGUILOR','RAMOS','TICKET SELLER II','2009-0657','active'),
 ('0851',NULL,'SHERRY MARIE','RANGEL','GARACHICO','TICKET SELLER II','2009-0661','active'),
 ('0852',NULL,'DONNA LEIL','CELIZ','ADAJAR','TICKET SELLER II','2009-0656','active'),
 ('0853',NULL,'CRISTINA','DORIA','RUESTRA','TICKET SELLER II','2009-0660','active'),
 ('0854',NULL,'CHERYLL','DAMIAN','PADRIQUE','TICKET SELLER II','2009-0659','active'),
 ('0855',NULL,'MA  NIMFA','MAGBANUA','RAMOS','TICKET SELLER II','2009-0662','active'),
 ('0856',NULL,'CIRILO JR ','BATERNA','SAGRAGAO','TICKET SELLER II','2009-0669','active'),
 ('0857',NULL,'JENNIFER','RONDINA','GARING','TICKET SELLER II','2009-0665','active'),
 ('0858',NULL,'JANET','LEBIG','NEDAMO','TICKET SELLER II','2009-0664','active'),
 ('0859',NULL,'RESIE','LAGOC','YALONG','TICKET SELLER II','2009-0667','active'),
 ('0861',NULL,'MARY JANE','RUBIA','SALGADO','TICKET SELLER II','2009-0666','active'),
 ('0862',NULL,'JHOANA','CABAL','DAVID','TICKET SELLER II','2009-0658','active'),
 ('0863',NULL,'BRYAN','FACTUAR','','TICKET SELLER II','2009-0668','active'),
 ('0866',NULL,'RYAN','CARANDANG','DE RAMOS','TICKET SELLER II','2009-0680','active'),
 ('0867',NULL,'MAYDILYN','ENRIQUE','ABANTO','TICKET SELLER II','2009-0676','active'),
 ('0868',NULL,'MICHELLE','RODRIGUEZ','ESCALERA','TICKET SELLER II','2009-0681','active'),
 ('0869',NULL,'SHARMAINE MICHELL','HILARIE','SANCHEZ','TICKET SELLER II','2009-0682','active'),
 ('0870',NULL,'MARIA TITA','MADRIGAL','JANDUSAY','TICKET SELLER II','2009-0683','active'),
 ('0871',NULL,'ROSEMARIE','DIZON','GARCIA','TICKET SELLER II','2009-0677','active'),
 ('0872',NULL,'JANREI','PALACIO','MANALO','TICKET SELLER II','2010-0692','active'),
 ('0873',NULL,'MA  JENNILYN','DIWA','SAN JOSE','TICKET SELLER II','2010-0691','active'),
 ('0874',NULL,'JODEN','ASTURIAS','FUENTES','TICKET SELLER II','2010-0690','active'),
 ('0875',NULL,'MARY ANN','CALOMPONG','DOTE','TICKET SELLER II','2010-0694','active'),
 ('0876',NULL,'DAISY LYNN','HOLAMPONG','DOMINGO','TICKET SELLER II','2010-0701','active'),
 ('0877',NULL,'JOANNE','VALENCIA','WATA','TICKET SELLER II','2010-0702','active'),
 ('0878',NULL,'JANE MAURICE','RAMOS','PANGANIBAN','TICKET SELLER II','2011-0708','active'),
 ('0879',NULL,'JANET','PABILAN','BILARMINO','TICKET SELLER II','2011-0709','active'),
 ('0881',NULL,'CRISTOPHER','DE LARA','ANG','TICKET SELLER II','2011-0712','active'),
 ('0882',NULL,'ALLAN','BAS','VIADER','TICKET SELLER II','2011-0713','active'),
 ('0883',NULL,'JOSEPHINE','MESINA','PEREZ','TICKET SELLER II','2011-0720','active'),
 ('0884',NULL,'MABEL','BALLICUD','PEÑARANDA','TICKET SELLER II','2011-0721','active'),
 ('0885',NULL,'CATHERINE','HERNANDEZ','FEDERICO','TICKET SELLER II','2011-0731','active'),
 ('0886',NULL,'SHERLYN','PAGUIA','DELA CRUZ','TICKET SELLER II','2011-0733','active'),
 ('0887',NULL,'GIRLIE','GUMIA','SEBELLENO','TICKET SELLER II','2011-0728','active'),
 ('0888',NULL,'MARIE LYNN','MILAN','CANLAS','TICKET SELLER II','2011-0732','active'),
 ('0889',NULL,'RAYMOND','BALTONADO','ADORACION','TICKET SELLER II','2011-0734','active'),
 ('0890',NULL,'CHRISTIAN ERROL','ALMIRA','GARON','TICKET SELLER II','2012-0742','active'),
 ('0891',NULL,'SHAYNE','MAGNO','FETIL','TICKET SELLER II','2012-0743','active'),
 ('0892',NULL,'JESIL','LOBOS','ENQUILLO','TICKET SELLER II','2012-0744','active'),
 ('0893',NULL,'MOHAMIRA','GANDAMRA','PIMPING','TICKET SELLER II','2009-0647','active'),
 ('0894',NULL,'ARVIN','DOCTORE','ESTEBAN','TICKET SELLER II','2012-0745','active'),
 ('0895',NULL,'LEA','ABIERA','COMALA','TICKET SELLER II','2012-0751','active'),
 ('0896',NULL,'PRINCESS','LAUREL','SILVA','TICKET SELLER II','2012-0760','active'),
 ('0897',NULL,'MADONNA','PUDA','DAVID','TICKET SELLER II','2012-0752','active'),
 ('0898',NULL,'MADELAINE','ORIAS','UMALI','TICKET SELLER II','2012-0754','active'),
 ('0899',NULL,'KATHYRENE','PEREZ','ALTRE','TICKET SELLER II','2012-0759','active'),
 ('0900',NULL,'JEAN','CASANA','CURIAS','TICKET SELLER II','2012-0753','active'),
 ('0901',NULL,'CRISELDA','SALUDAR','DAPITILLO','TICKET SELLER II','2012-0755','active'),
 ('0902',NULL,'MERLY','ASTRONOMO','ANACLETO','TICKET SELLER II','2012-0756','active'),
 ('0903',NULL,'JOBELLE','DOMINGO ','SALAO','TICKET SELLER II','2012-0757','active'),
 ('0904',NULL,'ALEXA','OBICO','QUIJANO','TICKET SELLER II','2012-0750','active'),
 ('0905',NULL,'KARLA REGINA ','DESCALLAR','CASTILLO','TICKET SELLER II','2012-0758','active'),
 ('0906',NULL,'MA. LOURDES','TAURO','CASIANO','TICKET SELLER II','2012-0763','active'),
 ('0907',NULL,'MARK GREGORY','TENORIO','ABIERA','TICKET SELLER II','2012-0775','active'),
 ('0908',NULL,'RONETH','DELA ROSA','TAGUINES','TICKET SELLER II','2012-0774','active'),
 ('0909',NULL,'MARIANNE','LABAROSA','BOCALBOS','TICKET SELLER II','2012-0777','active'),
 ('0910',NULL,'MARIE FRANCE','SANTOS','CIPCON','TICKET SELLER II','2012-0776','active'),
 ('0911',NULL,'MA. GRACIA','ZUNIGA','S','TICKET SELLER II',NULL,'active'),
 ('0912',NULL,'JUN RAFAEL','CASTRO','B','TICKET SELLER II',NULL,'active'),
 ('0913',NULL,'ROGELYN','GURADILLO','L','TICKET SELLER II',NULL,'active'),
 ('0914',NULL,'EMAFEL','NOLLORA','S','TICKET SELLER II',NULL,'active'),
 ('0915',NULL,'ROMESEE','RAMOS','MENDIOLA','TICKET SELLER II','2013-0788','active'),
 ('0916',NULL,'ZANDRA','DELOS REYES','PENTASON','TICKET SELLER  II','2013-0794','active'),
 ('0917',NULL,'KEMVERLY ANN','RIVERO','SEÑAR','TICKET SELLER II','2013-0793','active'),
 ('0918',NULL,'ROWELL','CORPUZ','','TICKET SELLER II','','active'),
 ('0919',NULL,'NELLY','CABOG','','TICKET SELLER  II','','active'),
 ('0920',NULL,'ROWENA','CASIDSID','','TICKET SELLER','','active'),
 ('0921',NULL,'CHRISTINE','AMPER','','TICKET SELLER ','','active'),
 ('0922',NULL,'ISAAC','CABALTICA','','TICKET SELLER','','active'),
 ('0923',NULL,'JONA','PAGRAD','','TICKET SELLER','','active'),
 ('0924',NULL,'KRISTINE DIANNE','AGNAS','','TICKET SELLER','','active'),
 ('0925',NULL,'GALI LEE','ANTONIO','','TICKET SELLER','','active'),
 ('0926',NULL,'Juan Paulo','Mariano','','TICKET SELLER II','','active'),
 ('0927',NULL,'Mark Gil','Garon','','TICKET SELLER II','','active'),
 ('0928',NULL,'ROY','Dimapawi','','TICKET SELLER II','','active'),
 ('0929',NULL,'Rona Christine','Araiz','','TICKET SELLER II','','active'),
 ('0930',NULL,'Babylyn','Ardalez','','TICKET SELLER II','','active'),
 ('0931',NULL,'Mark Greg','Yabut','','TICKET SELLER II','','active'),
 ('1131',NULL,'RODOLFO','ORANTE','ESTABILLO','TICKET SELLER II','2004-0462','active'),
 ('2501',NULL,'JANE','ABIERA','AGPALO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0016','active'),
 ('2505',NULL,'MAITA','ALEJANDRO','GARCIA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0017','active'),
 ('2508',NULL,'JOHN JAY','AOYANG','UY','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0019','active'),
 ('2509',NULL,'NESTOR','BAER','VILLANUEVA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0020','active'),
 ('2511',NULL,'MARIFE','BALASBAS','SALAZAR','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0010','active'),
 ('2515',NULL,'MARIO JR ','CARINGAL','MARISTELA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0021','active'),
 ('2521',NULL,'CARMELITO JOSE','DE LAS ALAS','MALIGAYA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0022','active'),
 ('2522',NULL,'ARTURO JR ','DU','QUIROZ','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0011','active'),
 ('2524',NULL,'RHONA','ALEJANDRO','FERNANDEZ','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0018','active'),
 ('2530',NULL,'FRANCISCO JR ','LEGAZPI','CORDERO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0023','active'),
 ('2536',NULL,'CLARISSA','MANCENIDO','MENDOZA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0024','active'),
 ('2538',NULL,'ARTEMIO','MERCENE','EMPERADO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0012','active'),
 ('2540',NULL,'RAFAEL','ORTIZ-LUIS','LALO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0013','active'),
 ('2543',NULL,'PERPETUA','PIELAGO','TOLEDANO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0009','active'),
 ('2544',NULL,'FLORANTE','PUNZALAN','JOSE','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0025','active'),
 ('2545',NULL,'REGINO','RAGUIRAG','CARAANG','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0014','active'),
 ('2550',NULL,'IRENEO','SANGIL','JUAN','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0015','active'),
 ('2552',NULL,'JOHN PHILLIP','SANTOS','JUNIO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0026','active'),
 ('2558',NULL,'ROLANDO','CARIAGA','JAVIER','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0280','active'),
 ('2559',NULL,'RENALIE','CRUZ','DE VILLA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0073','active'),
 ('2560',NULL,'RAFAEL','CURA','RIVERA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0237','active'),
 ('2562',NULL,'RICHARD','GATCHALIAN','GARCIA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0077','active'),
 ('2564',NULL,'ALBERTO','MAGLINES','PADECIO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0281','active'),
 ('2565',NULL,'MANUEL RAYMUNDO','MOLINA','DE JESUS','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0282','active'),
 ('2567',NULL,'MELVYN','NINO','VERGARA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0086','active'),
 ('2569',NULL,'JAMES','PERALTA','NIEVA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0284','active'),
 ('2570',NULL,'NESTOR','REAL','CALVAN','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0285','active'),
 ('2572',NULL,'ALFREDO JR ','SANTIAGO','ZALAMEA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0286','active'),
 ('2578',NULL,'JUPITER','FUENTES','CABAGBAG','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0075','active'),
 ('2580',NULL,'ROSALYN','FUENTES','ROJAS','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2001-0382','active'),
 ('2581',NULL,'EMMANUEL','SALINAS','LOPEZ','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2001-0383','active'),
 ('2582',NULL,'INES','PATRICIO','DE CASTRO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0087','active'),
 ('2583',NULL,'FERDINAND','GUEVARA','ANDES','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0078','active'),
 ('2585',NULL,'GUENEVER','MANAOG','GENITA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0083','active'),
 ('2586',NULL,'RAFAEL','ROBLES','BORROMEO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0088','active'),
 ('2587',NULL,'ALEXIS','TALAN','FAUSTINO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0090','active'),
 ('2588',NULL,'JOHAN','CARONONGAN','LOMUNTAD','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0072','active'),
 ('2590',NULL,'AUREA','SANTIDAD','SUMANG','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0089','active'),
 ('2591',NULL,'FE','VICENCIO','CANTORIA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0092','active'),
 ('2594',NULL,'EMMA','MORALES','ESPINOSA','SUPERVISING TRANSPORTATION DEVELOPMENT OFFICE','1999-0006','active'),
 ('2600',NULL,'ROWENA','BERNARDINO','ADRIANO','TICKET SELLER III','1999-0103','active'),
 ('2604',NULL,'LAILANIE','AGANON','A','TICKET SELLER III',NULL,'active'),
 ('2612',NULL,'ROGELIO JR ','BASCUG','MAGADAN','TICKET SELLER III','1999-0100','active'),
 ('2613',NULL,'ALLAN','BAUTISTA','LUMAYAG','TICKET SELLER III','1999-0101','active'),
 ('2616',NULL,'DANILO','BENITEZ','BAS','TICKET SELLER III','1999-0102','active'),
 ('2618',NULL,'JULIET','MIGUEL','CAGAMPANG','TICKET SELLER III','1999-0119','active'),
 ('2619',NULL,'EDWIN','CARANDANG','TENORIO','TICKET SELLER III','1999-0105','active'),
 ('2621',NULL,'MARY ANN','MAGTAAN','CASALLA','TICKET SELLER III','1999-0117','active'),
 ('2622',NULL,'RANDY','CASCAYAN','FERNANDEZ','TICKET SELLER III','1999-0106','active'),
 ('2631',NULL,'MARIA BYLIN','DE RAMOS','DE LEON','TICKET SELLER III','1999-0107','active'),
 ('2633',NULL,'MARIBEL','DELA CRUZ','DE LEON','TICKET SELLER III','1999-0108','active'),
 ('2634',NULL,'CLARISSA','DELOS REYES','ALBA ','TICKET SELLER III','1999-0109','active'),
 ('2639',NULL,'ROLITA','BALASA','ENRIQUEZ','TICKET SELLER III','1999-0097','active'),
 ('2643',NULL,'ROMMEL','GADAZA','TABLICO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0076','active'),
 ('2644',NULL,'RACHEL','MACAS','GALANIDO','TICKET SELLER III','1999-0116','active'),
 ('2648',NULL,'RENATO','GARCIA','SEDILLA','TICKET SELLER III','1999-0112','active'),
 ('2650',NULL,'RHOMELEX','GONZALES','LAROZA','TICKET SELLER III','1999-0139','active'),
 ('2651',NULL,'PAULIN','HERNANDEZ','MIQUIABAS','TICKET SELLER III','1999-0140','active'),
 ('2653',NULL,'JHOAN','OBOG','HONRUBIA','TICKET SELLER III','1999-0124','active'),
 ('2655',NULL,'MARIA LINDA JENNIFFER','JUAN','SANTIAGO','TICKET SELLER III','1999-0114','active'),
 ('2657',NULL,'MERIAM','ANDRES','LAJADA','TICKET SELLER III','1999-0096','active'),
 ('2658',NULL,'JOHN-JOHN','LIM','DOLLETE','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0080','active'),
 ('2659',NULL,'ROWEL LITO','LUMBRES','MEDINA','TICKET SELLER III','1999-0115','active'),
 ('2662',NULL,'GILNORE','MANABO','MAGLINTE','TICKET SELLER III','1999-0118','active'),
 ('2669',NULL,'ROBERT JR ','MURPHY','MAMARIL','TICKET SELLER III','1999-0120','active'),
 ('2671',NULL,'ARIEL','NAPOLES','MENDOZA','TICKET SELLER III','1999-0121','active'),
 ('2673',NULL,'JOHN FREDERIC','NAVARRO','ARANAS','TICKET SELLER III','1999-0122','active'),
 ('2674',NULL,'CARMEL','NOVEDA','BUZZARD','TICKET SELLER III','1999-0123','active'),
 ('2675',NULL,'LIEUNESSA','REDULLA','OBOG','TICKET SELLER III','1999-0129','active'),
 ('2679',NULL,'NORBERTA','PANGANIBAN','CUBELO','TICKET SELLER III','1999-0125','active'),
 ('2684',NULL,'MABELLE','PELOBELLO','PALAD','TICKET SELLER III','1999-0127','active'),
 ('2686',NULL,'ARIEL','PERONILLA','DELFIN','TICKET SELLER III','1999-0141','active'),
 ('2687',NULL,'VIRGINIA','ALAGON','PILI','TICKET SELLER III','1999-0094','active'),
 ('2688',NULL,'FRANCISCO JOEL','PUHAWAN','REYES','TICKET SELLER III','1999-0128','active'),
 ('2695',NULL,'JOHNEL','ROSALES','LUMACAD','TICKET SELLER III','1999-0131','active'),
 ('2696',NULL,'EMELITA','SAMPAYO','GARCIA','TICKET SELLER III','1999-0132','active'),
 ('2698',NULL,'JOCELYN','SANCHA','BEDIA','TICKET SELLER III','1999-0133','active'),
 ('2700',NULL,'JUDITH','SANTOS','CO','TICKET SELLER III','1999-0134','active'),
 ('2704',NULL,'ARISTOTLE','SUMARIA','SENFELICES','TICKET SELLER III','1999-0136','active'),
 ('2708',NULL,'CHARITO ','TATOY','SALA','TICKET SELLER II','1999-0142','active'),
 ('2709',NULL,'IRENE','LUNA','TENERIFE','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0081','active'),
 ('2715',NULL,'RENATO','ALEJANDRO','PAGSANJAN','TICKET SELLER III','1999-0095','active'),
 ('2720',NULL,'CHARITO','NINO','BONSAY','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0085','active'),
 ('2733',NULL,'DORIELYN','LAZARAGA','GALANEDO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0079','active'),
 ('2735',NULL,'ELVIN','ALAMPAY','GODALLE','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0071','active'),
 ('2737',NULL,'ALBERTO','HATOSA','ALIVIO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0145','active'),
 ('2744',NULL,'LYLAINE','MAJANO','DEL ROSARIO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0082','active'),
 ('2746',NULL,'MARJORIE','MIJARES','GOMEZ','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0084','active'),
 ('2751',NULL,'MERLE','GERONIMO','PEREZ','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0113','active'),
 ('2754',NULL,'CHRISTOPHER','RODILLAS','DELA CRUZ','TICKET SELLER III','1999-0130','active'),
 ('2757',NULL,'MARY JANE','BARICANOSA','SAMAN','TICKET SELLER III','1999-0099','active'),
 ('2761',NULL,'JOHNA','SUDARIO','RESURRECCION','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0135','active'),
 ('2763',NULL,'MELINDA','TOLEDO','CRUZ','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0091','active'),
 ('2768',NULL,'JOCELYN','SANCHEZ','ALIÑO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0146','active'),
 ('2774',NULL,'DELFINA','BAUTISTA','CABATBAT','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0240','active'),
 ('2780',NULL,'NOEL','FLORES','RITONA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','1999-0074','active'),
 ('2781',NULL,'NYDA','FLOREN','DIORES','TICKET SELLER III','2000-0242','active'),
 ('2784',NULL,'ELENA','IGNAO','MARTINEZ','TICKET SELLER III','2000-0243','active'),
 ('2790',NULL,'VIVIAN','PAGUIO','ALEJANDRO ','TICKET SELLER III','2000-0244','active'),
 ('2792',NULL,'ALVIN','REQUILLO','DUEÑAS','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0245','active'),
 ('2800',NULL,'ELENA','CONDINO','CUEBILLAS','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0262','active'),
 ('2805',NULL,'JESSICA','PEN','BARASONA','TICKET SELLER III','2000-0292','active'),
 ('2806',NULL,'ROBIN','BARTE','MANEJA','TICKET SELLER II','2000-0295','active'),
 ('2808',NULL,'ARVIN','CONCEPCION','GAELA','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0289','active'),
 ('2811',NULL,'ROBERTO','CRUZ','SANTOS','TICKET SELLER II','2000-0296','active'),
 ('2815',NULL,'RICO','HERMOGENES','DEL ROSARIO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0290','active'),
 ('2819',NULL,'LORNA','ANTONIO','LOPEZ','TICKET SELLER III','2000-0288','active'),
 ('2821',NULL,'MA  MICHELE','MANALO','LAGMAN','TICKET SELLER III','2000-0291','active'),
 ('2829',NULL,'MA  GRACIA','SISON','ALLAPITAN','TICKET SELLER III','2000-0293','active'),
 ('2834',NULL,'RUEL','ALOG','MATEO','TICKET SELLER III','2000-0327','active'),
 ('2835',NULL,'RICARDO','ANGELES','CAMUA','TICKET SELLER III','2000-0315','active'),
 ('2838',NULL,'RICARDO JR ','DATA','KING','TICKET SELLER II','2000-0331','active'),
 ('2839',NULL,'JONILO','DE JUAN','LEONOR','TICKET SELLER III','2000-0332','active'),
 ('2841',NULL,'KING','LACONICO','REYNALDO','TICKET SELLER III','2000-0320','active'),
 ('2846',NULL,'LAWRENCE','PANES','SALMORIN','TICKET SELLER II','2000-0340','active'),
 ('2847',NULL,'PETER JOHN','SABSALON','OLOR','TICKET SELLER III','2000-0323','active'),
 ('2848',NULL,'SHERWIN','SANTOS','COSTILLAS','TICKET SELLER III','2000-0324','active'),
 ('2849',NULL,'CHRISMAR','UNABIA','BADIAG','TICKET SELLER III','2000-0326','active'),
 ('2852',NULL,'LUCILYN','AGUADO','VELITARIO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0314','active'),
 ('2853',NULL,'MARINELA','DERIE','ALMADIN','TICKET SELLER III','2000-0333','active'),
 ('2854',NULL,'MARITES','AMBROCIO','BASINANG','TICKET SELLER II','2000-0328','active'),
 ('2856',NULL,'FLORENCE','BARCENA','CASTOR','TICKET SELLER II','2000-0329','active'),
 ('2857',NULL,'ROSEMARIE','BERNAL','PIÑERA','TICKET SELLER II','2000-0330','active'),
 ('2862',NULL,'MILETTE','DETERA','DELA CRUZ','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0316','active'),
 ('2863',NULL,'ALICIA','DIMAANO','CUNANAN','TICKET SELLER II','2000-0334','active'),
 ('2865',NULL,'MA  ANTOINETTE','GONZALEZ','TECSON','TICKET SELLER II','2000-0336','active'),
 ('2867',NULL,'JOSEPHINE','JUNTURA','PAJO','TICKET SELLER II','2000-0337','active'),
 ('2868',NULL,'DAISY','SABANATE','LORA','TICKET SELLER III','2000-0322','active'),
 ('2869',NULL,'GERARDINE','MANGALILI','DE JESUS','TICKET SELLER II','2000-0338','active'),
 ('2870',NULL,'NATIVIDAD','DIMAYA','ORTEGA','TICKET SELLER III','2000-0317','active'),
 ('2872',NULL,'PERCIVAL JR ','FANO','FETALINO','TICKET SELLER II','2000-0335','active'),
 ('2873',NULL,'ANN CHRISTINE','TORALLO','PEDARSE','TICKET SELLER III','2000-0321','active'),
 ('2874',NULL,'ROWENA','PEDRERA','LIBUTAN','TICKET SELLER III','2000-0341','active'),
 ('2875',NULL,'JOCEBEL','CINCO','PLUCENA','TICKET SELLER III','2000-0342','active'),
 ('2876',NULL,'SHEINA','TORRES','SAGRAGAO','TICKET SELLER III','2000-0325','active'),
 ('2878',NULL,'ELLA','JATULAN','TIBUS','TICKET SELLER III','2000-0319','active'),
 ('2880',NULL,'RAMIL','MONTE DE RAMOS','ROJAS','TICKET SELLER II','2000-0339','active'),
 ('2888',NULL,'MICHAEL','MARASIGAN','PALO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2000-0302','active'),
 ('2890',NULL,'CHRISTOPHER','MOLINA','GONZALES','TICKET SELLER III','2000-0303','active'),
 ('2894',NULL,'JAIME','PORTILLANO','RIVERA ','TICKET SELLER II','2000-0304','active'),
 ('2895',NULL,'FERDINAND','RAYMUNDO','BARLITA','TICKET SELLER II','2000-0305','active'),
 ('2896',NULL,'MICHAEL ','ROBLES','GONZALES','TICKET SELLER II','2000-0306','active'),
 ('2897',NULL,'JESUS ARIEL','STA  ANA','SAN JOSE','TICKET SELLER II','2000-0307','active'),
 ('2911',NULL,'ALFONSO JR ','BOQUIREN','BOLASOC','TICKET SELLER III','2001-0375','active'),
 ('2913',NULL,'MARIE ANTONIETTE','DE JESUS','PAGAL','TICKET SELLER II','2001-0378','active'),
 ('2917',NULL,'GLORIA','GARVEZ','DELARMENTE','TICKET SELLER III','2001-0376','active'),
 ('2921',NULL,'CRISANTA','SAAVEDRA','RAMOS','TICKET SELLER II','2001-0381','active'),
 ('2922',NULL,'ALFONSA','SALIGUE','MAGADAN','TICKET SELLER III','2001-0377','active'),
 ('2924',NULL,'LIMUEL','TORDECILLA','CATAQUIZ','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2001-0374','active'),
 ('2927',NULL,'CHERAQUEL2','ABANTO','PANOPIO','TICKET SELLER II','2002-0398','active'),
 ('2929',NULL,'JAMES JOHN','ACOSTA','NAZARENO','TICKET SELLER II','2002-0399','active'),
 ('2934',NULL,'JAYSON','BOLASOC','MAPA','TICKET SELLER II','2002-0401','active'),
 ('2935',NULL,'MARION','CASTRO','RAMOS','TICKET SELLER II','2002-0402','active'),
 ('2936',NULL,'LEO','CONCON','GAVINO','SENIOR TRANSPORTATION DEVELOPMENT OFFICER','2002-0412','active'),
 ('2937',NULL,'AMALIA','DELA CRUZ','BILOLO','TICKET SELLER II','2002-0403','active'),
 ('2938',NULL,'ARLENE','DIMARUCUT','PINGUL','TICKET SELLER II','2002-0404','active'),
 ('2941',NULL,'REYNITA','DOREGO','FRANCISCO','TICKET SELLER II','2002-0405','active'),
 ('2942',NULL,'BARBARA','GOTENGCO','OGCILA','TICKET SELLER II','2002-0406','active'),
 ('2943',NULL,'GINA','HAGONOY','ESPLANA','TICKET SELLER III','2002-0407','active'),
 ('2948',NULL,'EDGARDO','MAGBOO','CAGUETE','TICKET SELLER III','2002-0394','active'),
 ('2952',NULL,'JULIETA','MERCENE','MAGBOO','TICKET SELLER III','2002-0395','active'),
 ('2955',NULL,'CYRIL','PASTOR','ACEVEDO','TICKET SELLER III','2002-0396','active'),
 ('2956',NULL,'ERNESTO JR ','PINEDA','ONG','TICKET SELLER II','2002-0410','active'),
 ('2957',NULL,'RENNIE','REDULLA','DOLERA','TICKET SELLER III','2002-0397','active'),
 ('2960',NULL,'DANTE','SENORA','BALDOMARO','TICKET SELLER II','2002-0411','active'),
 ('2966',NULL,'BERNADETTE','ALFONSO','EVANGELISTA','TICKET SELLER II','2002-0423','active'),
 ('2968',NULL,'LEAH','BAYONA','SOSING','TICKET SELLER III','2002-0424','active'),
 ('2970',NULL,'SANTIAGO','DELA CRUZ','DE LEON','TICKET SELLER III','2002-0421','active'),
 ('2972',NULL,'GRACE','DIOMAMPO','EVANGELIO','TICKET SELLER II','2002-0425','active'),
 ('2977',NULL,'ROWENA','MEDINA','VILLALOBOS','TICKET SELLER III','2002-0426','active'),
 ('2978',NULL,'REHGANNE','PADILLA','SORIANO','TICKET SELLER III','2002-0422','active'),
 ('2979',NULL,'FE ','BANDONG','SALA','TICKET SELLER III','2002-0430','active'),
 ('2982',NULL,'PAUL ANTHONY','SELPA','CAPICENIO','TICKET SELLER III','2002-0429','active'),
 ('2984',NULL,'SHEILA ROSE','PEREZ','SISON','TICKET SELLER II','2002-0427','active'),
 ('2987',NULL,'ROWENA','DEVELA','GAMIT','TICKET SELLER III','2003-0435','active'),
 ('2988',NULL,'ELIBETH','LOPENA','ELAURIA','TICKET SELLER II','2005-0477','active'),
 ('2990',NULL,'CRISTITA','REYES','CODERA','TICKET SELLER II','2005-0486','active'),
 ('2994',NULL,'PORTIA JOY','CUNANAN','QUERIDO','TICKET SELLER II','2005-0476','active'),
 ('2995',NULL,'ROSALINA','MANUEL','BRILLANTE','TICKET SELLER II','2005-0478','active'),
 ('2997',NULL,'RECHELDA','ABABA','ENDRACA','TICKET SELLER II','2005-0475','active'),
 ('2998',NULL,'ROSSANA','SAMAN','SAMSON','TICKET SELLER II','2005-0481','active'),
 ('3000',NULL,'CYNTHIA','LUCIDOS','BRITON','TICKET SELLER II','2004-0470','active'),
 ('3001',NULL,'CARLOS ANGELITO','LINAO','GATMAITAN','TICKET SELLER II','2005-0495','active'),
 ('3005',NULL,'MARIO JR ','RIVERA','PAYNADO','TICKET SELLER II','2005-0479','active'),
 ('3006',NULL,'JANINE','DELA CRUZ','VIGO','TICKET SELLER II','2005-0499','active'),
 ('3007',NULL,'CARLOS','REYES','DELOS SANTOS','TICKET SELLER II','2004-0472','active'),
 ('3008',NULL,'EFREN JR ','DELA CRUZ','ADRIANO','TICKET SELLER II','2005-0485','active'),
 ('3011',NULL,'JACKIELYN','ACUZAR','RODRIGUEZ','TICKET SELLER II','2006-0516','active'),
 ('3013',NULL,'RHIA','REVANO','ORTEGA','TICKET SELLER II','2006-0515','active'),
 ('3014',NULL,'RICA','SAN BUENAVENTURA','BERICO','TICKET SELLER III','2006-0517','active'),
 ('3015',NULL,'JOCELYN','PEDARIA','LASALA','TICKET SELLER III','1999-0126','active'),
 ('3016',NULL,'MARY VI','BANGALAN','PACANNUAYAN','TICKET SELLER III','1999-0098','active');
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
  `reference_id` varchar(45) DEFAULT NULL,
  `ticket_seller` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55574 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;


--
-- Definition of table `unreg_sale`
--

DROP TABLE IF EXISTS `unreg_sale`;
CREATE TABLE `unreg_sale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `svt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `svd` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10700 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unreg_sale`
--

/*!40000 ALTER TABLE `unreg_sale` DISABLE KEYS */;
/*!40000 ALTER TABLE `unreg_sale` ENABLE KEYS */;


--
-- Definition of view `additional_allocation`
--

DROP TABLE IF EXISTS `additional_allocation`;
DROP VIEW IF EXISTS `additional_allocation`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `additional_allocation` AS select sum(`ticket_order`.`sjt`) AS `sjt`,sum(`ticket_order`.`sjd`) AS `sjd`,sum(`ticket_order`.`svt`) AS `svt`,sum(`ticket_order`.`svd`) AS `svd`,sum(`ticket_order`.`sjt_loose`) AS `sjt_loose`,sum(`ticket_order`.`sjd_loose`) AS `sjd_loose`,sum(`ticket_order`.`svt_loose`) AS `svt_loose`,sum(`ticket_order`.`svd_loose`) AS `svd_loose`,`ticket_order`.`control_id` AS `control_id` from `ticket_order` where ((`ticket_order`.`type` = 'allocation') and (`ticket_order`.`classification` = 'ticket_seller')) group by `ticket_order`.`control_id`;

--
-- Definition of view `control_remittance`
--

DROP TABLE IF EXISTS `control_remittance`;
DROP VIEW IF EXISTS `control_remittance`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `control_remittance` AS select `cash_remittance`.`id` AS `remit_id`,`cash_remittance`.`ticket_seller` AS `remit_ticket_seller`,`cash_remittance`.`log_id` AS `remit_log`,`cash_remittance`.`control_id` AS `control_id`,`control_slip`.`id` AS `id`,`control_slip`.`ticket_seller` AS `ticket_seller`,`control_slip`.`log_id` AS `log_id`,`control_slip`.`unit` AS `unit`,`control_slip`.`reference_id` AS `reference_id`,`control_slip`.`station` AS `station`,`control_slip`.`status` AS `status` from (`cash_remittance` join `control_slip` on((`control_slip`.`id` = `cash_remittance`.`control_id`)));



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
