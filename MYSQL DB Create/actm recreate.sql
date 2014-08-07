USE actm;

CREATE TABLE  `allocation` (
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

CREATE TABLE  `beginning_balance_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `revolving_fund` varchar(100) NOT NULL,
  `for_deposit` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1967 DEFAULT CHARSET=latin1;

CREATE TABLE  `beginning_balance_sjt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `sjt_loose` varchar(45) DEFAULT NULL,
  `sjd_loose` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1934 DEFAULT CHARSET=latin1;

CREATE TABLE  `beginning_balance_svt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `svt` varchar(45) NOT NULL,
  `svd` varchar(45) NOT NULL,
  `svt_loose` varchar(45) NOT NULL,
  `svd_loose` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1923 DEFAULT CHARSET=latin1;

CREATE TABLE `beginning_balance_tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `svt` varchar(45) DEFAULT NULL,
  `svd` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE  `cash_remittance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(45) DEFAULT NULL,
  `log_id` varchar(45) DEFAULT NULL,
  `ticket_seller` varchar(45) NOT NULL,
  `control_remittance` double DEFAULT NULL,
  `cash_remittance` double DEFAULT NULL,
  `control_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29452 DEFAULT CHARSET=latin1;

CREATE TABLE  `cash_transfer` (
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

CREATE TABLE  `control_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `unpaid_shortage` varchar(450) DEFAULT NULL,
  `overage` varchar(45) DEFAULT NULL,
  `cash_advance` varchar(45) DEFAULT NULL,
  `ot` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10711 DEFAULT CHARSET=latin1;

CREATE TABLE  `control_sales_amount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sjt` varchar(45) NOT NULL,
  `sjd` varchar(45) NOT NULL,
  `svt` varchar(45) NOT NULL,
  `svd` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10834 DEFAULT CHARSET=latin1;

CREATE TABLE  `control_slip` (
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

CREATE TABLE  `control_sold` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `svt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `svd` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11514 DEFAULT CHARSET=latin1;

CREATE TABLE  `control_tracking` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `log_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22574 DEFAULT CHARSET=latin1;

CREATE TABLE  `control_unsold` (
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

CREATE TABLE  `denomination` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cash_transfer_id` varchar(45) NOT NULL,
  `denomination` varchar(45) NOT NULL,
  `quantity` varchar(105) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cash_transfer_id` (`cash_transfer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=107069 DEFAULT CHARSET=latin1;

CREATE TABLE  `discount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sj` varchar(45) DEFAULT NULL,
  `sv` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10928 DEFAULT CHARSET=latin1;

CREATE TABLE  `discrepancy` (
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

CREATE TABLE  `discrepancy_ticket` (
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

CREATE TABLE  `ending_balance_cash` (
  `log_id` int(10) unsigned NOT NULL,
  `revolving_fund` varchar(45) DEFAULT NULL,
  `for_deposit` varchar(45) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1907 DEFAULT CHARSET=latin1;

CREATE TABLE  `ending_balance_sjt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `sjt_loose` varchar(45) DEFAULT NULL,
  `sjd_loose` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1861 DEFAULT CHARSET=latin1;

CREATE TABLE  `ending_balance_svt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `svt` varchar(45) NOT NULL,
  `svd` varchar(45) NOT NULL,
  `svt_loose` varchar(45) NOT NULL,
  `svd_loose` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1846 DEFAULT CHARSET=latin1;

CREATE TABLE  `extension` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station` varchar(45) NOT NULL,
  `extension` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

CREATE TABLE  `fare_adjustment` (
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

CREATE TABLE  `fare_adjustment_tickets` (
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

CREATE TABLE  `log_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `log_id` varchar(45) NOT NULL,
  `date` datetime NOT NULL,
  `login` datetime DEFAULT NULL,
  `logout` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2646 DEFAULT CHARSET=latin1;

CREATE TABLE  `log_ticket_seller` (
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

CREATE TABLE  `log_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `log_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE  `logbook` (
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

CREATE TABLE  `login` (
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

CREATE TABLE  `physically_defective` (
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

CREATE TABLE  `pnb_deposit` (
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

CREATE TABLE  `refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sj` varchar(45) DEFAULT NULL,
  `sv` varchar(45) DEFAULT NULL,
  `sj_amount` double DEFAULT NULL,
  `sv_amount` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10701 DEFAULT CHARSET=latin1;

CREATE TABLE  `remittance` (
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

CREATE TABLE  `shift` (
  `shift_id` varchar(45) NOT NULL,
  `shift_name` text NOT NULL,
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE  `station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

CREATE TABLE  `ticket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE TABLE  `ticket_order` (
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

CREATE TABLE  `ticket_seller` (
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

CREATE TABLE  `transaction` (
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

CREATE TABLE  `unreg_sale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` varchar(45) NOT NULL,
  `sjt` varchar(45) DEFAULT NULL,
  `svt` varchar(45) DEFAULT NULL,
  `sjd` varchar(45) DEFAULT NULL,
  `svd` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_id` (`control_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10700 DEFAULT CHARSET=latin1;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW  `additional_allocation` AS select sum(`ticket_order`.`sjt`) AS `sjt`,sum(`ticket_order`.`sjd`) AS `sjd`,sum(`ticket_order`.`svt`) AS `svt`,sum(`ticket_order`.`svd`) AS `svd`,sum(`ticket_order`.`sjt_loose`) AS `sjt_loose`,sum(`ticket_order`.`sjd_loose`) AS `sjd_loose`,sum(`ticket_order`.`svt_loose`) AS `svt_loose`,sum(`ticket_order`.`svd_loose`) AS `svd_loose`,`ticket_order`.`control_id` AS `control_id` from `ticket_order` where ((`ticket_order`.`type` = 'allocation') and (`ticket_order`.`classification` = 'ticket_seller')) group by `ticket_order`.`control_id`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW  `control_remittance` AS select `cash_remittance`.`id` AS `remit_id`,`cash_remittance`.`ticket_seller` AS `remit_ticket_seller`,`cash_remittance`.`log_id` AS `remit_log`,`cash_remittance`.`control_id` AS `control_id`,`control_slip`.`id` AS `id`,`control_slip`.`ticket_seller` AS `ticket_seller`,`control_slip`.`log_id` AS `log_id`,`control_slip`.`unit` AS `unit`,`control_slip`.`reference_id` AS `reference_id`,`control_slip`.`station` AS `station`,`control_slip`.`status` AS `status` from (`cash_remittance` join `control_slip` on((`control_slip`.`id` = `cash_remittance`.`control_id`)));