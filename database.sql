/*
MySQL Backup
Source Server Version: 5.5.5
Source Database: laundry
Date: 10 Oct 2017 10:24:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `attributes`
-- ----------------------------
DROP TABLE IF EXISTS `attributes`;
CREATE TABLE `attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(250) DEFAULT NULL,
  `type` varchar(150) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `attribute_values`
-- ----------------------------
DROP TABLE IF EXISTS `attribute_values`;
CREATE TABLE `attribute_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attrib_id` int(11) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `value` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ci_sessions`
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` longtext NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `customers`
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(150) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `contact_person` varchar(250) DEFAULT NULL,
  `contact_no` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `images`
-- ----------------------------
DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_file_name` longtext,
  `img_path` longtext,
  `img_ref_id` int(11) DEFAULT NULL,
  `img_tbl` varchar(50) DEFAULT NULL,
  `img_blob` longblob,
  `datetime` datetime DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `inventory_moves`
-- ----------------------------
DROP TABLE IF EXISTS `inventory_moves`;
CREATE TABLE `inventory_moves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_type` int(11) DEFAULT NULL,
  `trans_ref` varchar(150) DEFAULT NULL,
  `loc_id` int(11) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `mat_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `curr_qty` double DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `items`
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `item_categories`
-- ----------------------------
DROP TABLE IF EXISTS `item_categories`;
CREATE TABLE `item_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `locations`
-- ----------------------------
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `contact_no` varchar(150) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `materials`
-- ----------------------------
DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `cost` double DEFAULT '0',
  `cat_id` int(11) DEFAULT NULL,
  `type` varchar(150) DEFAULT NULL,
  `tax_type_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `reg_date` datetime DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `material_categories`
-- ----------------------------
DROP TABLE IF EXISTS `material_categories`;
CREATE TABLE `material_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `tax_type_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `purchase_orders`
-- ----------------------------
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(150) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `rcv_loc_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `total_qty` double DEFAULT NULL,
  `rcv_qty` double DEFAULT '0',
  `memo` varchar(255) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `purchase_order_details`
-- ----------------------------
DROP TABLE IF EXISTS `purchase_order_details`;
CREATE TABLE `purchase_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `mat_id` int(11) DEFAULT NULL,
  `order_qty` double DEFAULT NULL,
  `cost` double DEFAULT NULL,
  `total_cost` double DEFAULT NULL,
  `rcv_qty` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `receive_orders`
-- ----------------------------
DROP TABLE IF EXISTS `receive_orders`;
CREATE TABLE `receive_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(150) DEFAULT NULL,
  `order_ref` varchar(150) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `loc_id` int(11) DEFAULT NULL,
  `total_rcv` double DEFAULT '0',
  `receive_date` date DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  `memo` varchar(250) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `receive_order_details`
-- ----------------------------
DROP TABLE IF EXISTS `receive_order_details`;
CREATE TABLE `receive_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rcv_id` int(11) DEFAULT NULL,
  `mat_id` int(11) DEFAULT NULL,
  `order_qty` double DEFAULT NULL,
  `remain_qty` double DEFAULT NULL,
  `rcv_qty` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `references`
-- ----------------------------
DROP TABLE IF EXISTS `references`;
CREATE TABLE `references` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `next_ref` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `code` varchar(150) NOT NULL,
  `category` varchar(150) DEFAULT NULL,
  `value` longtext,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `suppliers`
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(150) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `tin` varchar(200) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `contact_no` varchar(100) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `tax_types`
-- ----------------------------
DROP TABLE IF EXISTS `tax_types`;
CREATE TABLE `tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `percent` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_refs`
-- ----------------------------
DROP TABLE IF EXISTS `trans_refs`;
CREATE TABLE `trans_refs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `trans_ref` varchar(55) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `trans_types`
-- ----------------------------
DROP TABLE IF EXISTS `trans_types`;
CREATE TABLE `trans_types` (
  `type_id` int(11) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `next_ref` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `uom`
-- ----------------------------
DROP TABLE IF EXISTS `uom`;
CREATE TABLE `uom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abbrev` varchar(25) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(55) DEFAULT NULL,
  `mname` varchar(55) DEFAULT NULL,
  `lname` varchar(55) DEFAULT NULL,
  `suffix` varchar(55) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `inactive` int(11) DEFAULT '0',
  PRIMARY KEY (`id`,`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `user_roles`
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `access` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `voids`
-- ----------------------------
DROP TABLE IF EXISTS `voids`;
CREATE TABLE `voids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_ref` varchar(255) DEFAULT NULL,
  `src_type` int(11) DEFAULT NULL,
  `src_id` int(11) DEFAULT NULL,
  `reason` varchar(250) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_orders`
-- ----------------------------
DROP TABLE IF EXISTS `work_orders`;
CREATE TABLE `work_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(150) DEFAULT NULL,
  `batch_no` varchar(150) DEFAULT NULL,
  `lot_no` varchar(150) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `wo_date` date DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `uom` varchar(20) DEFAULT '0',
  `memo` varchar(255) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  `curr_stage_id` int(11) DEFAULT NULL,
  `finished` tinyint(1) DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_orders_produced`
-- ----------------------------
DROP TABLE IF EXISTS `work_orders_produced`;
CREATE TABLE `work_orders_produced` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wo_id` int(11) DEFAULT NULL,
  `wo_stg_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `dispatched` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_orders_staging`
-- ----------------------------
DROP TABLE IF EXISTS `work_orders_staging`;
CREATE TABLE `work_orders_staging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wo_id` int(11) DEFAULT NULL,
  `order` double DEFAULT NULL,
  `stage_id` int(11) DEFAULT NULL,
  `weight` double DEFAULT '0',
  `damage` double DEFAULT '0',
  `uom` varchar(20) DEFAULT NULL,
  `stage_date` date DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_orders_staging_materials`
-- ----------------------------
DROP TABLE IF EXISTS `work_orders_staging_materials`;
CREATE TABLE `work_orders_staging_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wo_stg_id` int(11) DEFAULT NULL,
  `mat_id` int(11) DEFAULT NULL,
  `min_qty` double DEFAULT '0',
  `wo_qty` double DEFAULT NULL,
  `cost` double DEFAULT '0',
  `total_cost` double DEFAULT '0',
  `used_qty` double DEFAULT '0',
  `additional` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_dispatch`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_dispatch`;
CREATE TABLE `work_order_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(150) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `dispatch_date` date DEFAULT NULL,
  `total_qty` double DEFAULT '0',
  `memo` varchar(255) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_dispatch_items`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_dispatch_items`;
CREATE TABLE `work_order_dispatch_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dispatch_id` int(11) DEFAULT NULL,
  `wo_stg_id` int(11) DEFAULT NULL,
  `wo_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_items`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_items`;
CREATE TABLE `work_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wo_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `rcv_id` int(11) DEFAULT NULL,
  `rcv_qty` double DEFAULT NULL,
  `wo_qty` double DEFAULT NULL,
  `remain_qty` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_materials`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_materials`;
CREATE TABLE `work_order_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wo_id` int(11) DEFAULT NULL,
  `mat_id` int(11) DEFAULT NULL,
  `min_qty` double DEFAULT '0',
  `wo_qty` double DEFAULT NULL,
  `cost` double DEFAULT '0',
  `total_cost` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_receives`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_receives`;
CREATE TABLE `work_order_receives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(150) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `rcv_date` date DEFAULT NULL,
  `rcv_qty` double DEFAULT '0',
  `memo` varchar(255) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `reg_user` int(11) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_receive_items`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_receive_items`;
CREATE TABLE `work_order_receive_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rcv_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `rcv_qty` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_stages`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_stages`;
CREATE TABLE `work_order_stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_types`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_types`;
CREATE TABLE `work_order_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_type_items`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_type_items`;
CREATE TABLE `work_order_type_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_type_materials`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_type_materials`;
CREATE TABLE `work_order_type_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `mat_id` int(11) DEFAULT NULL,
  `order_qty` double DEFAULT '0',
  `cost` double DEFAULT '0',
  `total_cost` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `work_order_type_stages`
-- ----------------------------
DROP TABLE IF EXISTS `work_order_type_stages`;
CREATE TABLE `work_order_type_stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT '0',
  `stage_id` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `attributes` VALUES ('1','materials','text','brand');
INSERT INTO `attribute_values` VALUES ('1','1','1','Tide');
INSERT INTO `ci_sessions` VALUES ('0f2c780c32f4507f07140455894ab1c1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','1506577709','a:5:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:3:\"Rey\";s:5:\"lname\";s:8:\"Reynalds\";s:5:\"mname\";s:0:\"\";s:6:\"suffix\";s:0:\"\";s:9:\"full_name\";s:14:\"Rey  Reynalds \";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:44:\"http://localhost/laundry/uploads/users/1.png\";}s:7:\"company\";a:6:{s:12:\"comp_address\";s:50:\"1013 Emerald Bldg. Barangay San Antonio Pasig City\";s:15:\"comp_contact_no\";s:13:\"(02) 887 9643\";s:10:\"comp_email\";s:15:\"email@email.com\";s:9:\"comp_logo\";s:24:\"uploads/company/logo.png\";s:9:\"comp_name\";s:8:\"PointOne\";s:8:\"comp_tin\";s:15:\"000-888-888-888\";}s:9:\"type-mats\";a:2:{i:0;a:5:{s:4:\"cost\";s:3:\"100\";s:14:\"cost_total_hid\";s:5:\"50.00\";s:7:\"ord_qty\";s:3:\"0.5\";s:6:\"mat_id\";s:1:\"1\";s:8:\"mat_name\";s:18:\"Fabric Conditioner\";}i:1;a:5:{s:4:\"cost\";s:2:\"50\";s:14:\"cost_total_hid\";s:5:\"40.00\";s:7:\"ord_qty\";s:3:\"0.8\";s:6:\"mat_id\";s:1:\"2\";s:8:\"mat_name\";s:6:\"Bleach\";}}s:10:\"stage-mats\";a:2:{i:0;a:8:{s:5:\"wo_id\";s:1:\"5\";s:8:\"mat_name\";s:18:\"Fabric Conditioner\";s:3:\"uom\";s:2:\"kg\";s:6:\"mat_id\";s:1:\"1\";s:7:\"min_qty\";s:3:\"0.5\";s:6:\"wo_qty\";s:3:\"100\";s:4:\"cost\";s:3:\"100\";s:10:\"total_cost\";s:5:\"10000\";}i:1;a:8:{s:5:\"wo_id\";s:1:\"5\";s:8:\"mat_name\";s:6:\"Bleach\";s:3:\"uom\";s:2:\"kl\";s:6:\"mat_id\";s:1:\"2\";s:7:\"min_qty\";s:3:\"0.8\";s:6:\"wo_qty\";s:3:\"160\";s:4:\"cost\";s:2:\"50\";s:10:\"total_cost\";s:4:\"8000\";}}}'), ('30c689d22acdb4d1bb9d312e50e1e310','::1','Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36','1469361841','a:3:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:3:\"Rey\";s:5:\"lname\";s:8:\"Reynalds\";s:5:\"mname\";s:0:\"\";s:6:\"suffix\";s:0:\"\";s:9:\"full_name\";s:14:\"Rey  Reynalds \";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:43:\"http://localhost/sch007/uploads/users/1.png\";}s:7:\"company\";a:6:{s:12:\"comp_address\";s:50:\"1013 Emerald Bldg. Barangay San Antonio Pasig City\";s:15:\"comp_contact_no\";s:13:\"(02) 887 9643\";s:10:\"comp_email\";s:15:\"email@email.com\";s:9:\"comp_logo\";s:24:\"uploads/company/logo.png\";s:9:\"comp_name\";s:18:\"Kidscoco Preschool\";s:8:\"comp_tin\";s:15:\"000-888-888-888\";}}'), ('3e16cbb57f7ea9bb0cbb207bd6e77421','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','1507002321',''), ('5748e1a831ae582071a0f663211a6a83','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36','1506489125','a:5:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:3:\"Rey\";s:5:\"lname\";s:8:\"Reynalds\";s:5:\"mname\";s:0:\"\";s:6:\"suffix\";s:0:\"\";s:9:\"full_name\";s:14:\"Rey  Reynalds \";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:44:\"http://localhost/laundry/uploads/users/1.png\";}s:7:\"company\";a:6:{s:12:\"comp_address\";s:50:\"1013 Emerald Bldg. Barangay San Antonio Pasig City\";s:15:\"comp_contact_no\";s:13:\"(02) 887 9643\";s:10:\"comp_email\";s:15:\"email@email.com\";s:9:\"comp_logo\";s:24:\"uploads/company/logo.png\";s:9:\"comp_name\";s:8:\"PointOne\";s:8:\"comp_tin\";s:15:\"000-888-888-888\";}s:9:\"type-mats\";a:2:{i:0;a:5:{s:4:\"cost\";s:3:\"100\";s:14:\"cost_total_hid\";s:5:\"50.00\";s:7:\"ord_qty\";s:3:\"0.5\";s:6:\"mat_id\";s:1:\"1\";s:8:\"mat_name\";s:18:\"Fabric Conditioner\";}i:1;a:5:{s:4:\"cost\";s:2:\"50\";s:14:\"cost_total_hid\";s:5:\"40.00\";s:7:\"ord_qty\";s:3:\"0.8\";s:6:\"mat_id\";s:1:\"2\";s:8:\"mat_name\";s:6:\"Bleach\";}}s:7:\"wo-mats\";a:2:{i:0;a:5:{s:4:\"cost\";s:3:\"100\";s:14:\"cost_total_hid\";s:5:\"50.00\";s:7:\"ord_qty\";s:3:\"0.5\";s:6:\"mat_id\";s:1:\"1\";s:8:\"mat_name\";s:18:\"Fabric Conditioner\";}i:1;a:5:{s:4:\"cost\";s:2:\"50\";s:14:\"cost_total_hid\";s:5:\"40.00\";s:7:\"ord_qty\";s:3:\"0.8\";s:6:\"mat_id\";s:1:\"2\";s:8:\"mat_name\";s:6:\"Bleach\";}}}'), ('92c599660941f13e1b68082ab51689d2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36','1506417319','a:3:{s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:3:\"Rey\";s:5:\"lname\";s:8:\"Reynalds\";s:5:\"mname\";s:0:\"\";s:6:\"suffix\";s:0:\"\";s:9:\"full_name\";s:14:\"Rey  Reynalds \";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:44:\"http://localhost/laundry/uploads/users/1.png\";}s:7:\"company\";a:6:{s:12:\"comp_address\";s:50:\"1013 Emerald Bldg. Barangay San Antonio Pasig City\";s:15:\"comp_contact_no\";s:13:\"(02) 887 9643\";s:10:\"comp_email\";s:15:\"email@email.com\";s:9:\"comp_logo\";s:24:\"uploads/company/logo.png\";s:9:\"comp_name\";s:8:\"PointOne\";s:8:\"comp_tin\";s:15:\"000-888-888-888\";}s:9:\"type-mats\";a:3:{i:0;a:5:{s:4:\"cost\";s:3:\"100\";s:14:\"cost_total_hid\";s:8:\"20000.00\";s:7:\"ord_qty\";s:3:\"200\";s:6:\"mat_id\";s:1:\"1\";s:8:\"mat_name\";s:18:\"Fabric Conditioner\";}i:1;a:5:{s:4:\"cost\";s:2:\"50\";s:14:\"cost_total_hid\";s:8:\"10000.00\";s:7:\"ord_qty\";s:3:\"200\";s:6:\"mat_id\";s:1:\"2\";s:8:\"mat_name\";s:6:\"Bleach\";}i:2;a:5:{s:4:\"cost\";s:2:\"20\";s:14:\"cost_total_hid\";s:7:\"4000.00\";s:7:\"ord_qty\";s:3:\"200\";s:6:\"mat_id\";s:1:\"3\";s:8:\"mat_name\";s:11:\"Liquid soap\";}}}'), ('c0c5c7fa47d5c3c3f0108233a10a7ee2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36','1506302393',''), ('c2e29e06d0a1c8927855c24a9418112d','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36','1506401286','a:3:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:3:\"Rey\";s:5:\"lname\";s:8:\"Reynalds\";s:5:\"mname\";s:0:\"\";s:6:\"suffix\";s:0:\"\";s:9:\"full_name\";s:14:\"Rey  Reynalds \";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:44:\"http://localhost/laundry/uploads/users/1.png\";}s:7:\"company\";a:6:{s:12:\"comp_address\";s:50:\"1013 Emerald Bldg. Barangay San Antonio Pasig City\";s:15:\"comp_contact_no\";s:13:\"(02) 887 9643\";s:10:\"comp_email\";s:15:\"email@email.com\";s:9:\"comp_logo\";s:24:\"uploads/company/logo.png\";s:9:\"comp_name\";s:8:\"PointOne\";s:8:\"comp_tin\";s:15:\"000-888-888-888\";}}'), ('d5de0e8f3a1b223c50630fa03e158081','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','1507602022','a:4:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:3:\"Rey\";s:5:\"lname\";s:8:\"Reynalds\";s:5:\"mname\";s:0:\"\";s:6:\"suffix\";s:0:\"\";s:9:\"full_name\";s:14:\"Rey  Reynalds \";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:44:\"http://localhost/laundry/uploads/users/1.png\";}s:7:\"company\";a:6:{s:12:\"comp_address\";s:50:\"1013 Emerald Bldg. Barangay San Antonio Pasig City\";s:15:\"comp_contact_no\";s:13:\"(02) 887 9643\";s:10:\"comp_email\";s:15:\"email@email.com\";s:9:\"comp_logo\";s:24:\"uploads/company/logo.png\";s:9:\"comp_name\";s:8:\"PointOne\";s:8:\"comp_tin\";s:15:\"000-888-888-888\";}s:10:\"stage-mats\";a:2:{i:0;a:8:{s:5:\"wo_id\";s:1:\"4\";s:8:\"mat_name\";s:18:\"Fabric Conditioner\";s:3:\"uom\";s:2:\"kg\";s:6:\"mat_id\";s:1:\"1\";s:7:\"min_qty\";s:3:\"0.5\";s:6:\"wo_qty\";s:2:\"50\";s:4:\"cost\";s:3:\"100\";s:10:\"total_cost\";s:4:\"5000\";}i:1;a:8:{s:5:\"wo_id\";s:1:\"4\";s:8:\"mat_name\";s:6:\"Bleach\";s:3:\"uom\";s:2:\"kl\";s:6:\"mat_id\";s:1:\"2\";s:7:\"min_qty\";s:3:\"0.8\";s:6:\"wo_qty\";s:2:\"80\";s:4:\"cost\";s:2:\"50\";s:10:\"total_cost\";s:4:\"4000\";}}}'), ('e29cd833df0ecbdffbf7d7c0bdc66458','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36','1506275882','a:3:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"fname\";s:3:\"Rey\";s:5:\"lname\";s:8:\"Reynalds\";s:5:\"mname\";s:0:\"\";s:6:\"suffix\";s:0:\"\";s:9:\"full_name\";s:14:\"Rey  Reynalds \";s:7:\"role_id\";s:1:\"1\";s:4:\"role\";s:14:\"Administrator \";s:6:\"access\";s:3:\"all\";s:3:\"img\";s:44:\"http://localhost/laundry/uploads/users/1.png\";}s:7:\"company\";a:6:{s:12:\"comp_address\";s:50:\"1013 Emerald Bldg. Barangay San Antonio Pasig City\";s:15:\"comp_contact_no\";s:13:\"(02) 887 9643\";s:10:\"comp_email\";s:15:\"email@email.com\";s:9:\"comp_logo\";s:24:\"uploads/company/logo.png\";s:9:\"comp_name\";s:8:\"PointOne\";s:8:\"comp_tin\";s:15:\"000-888-888-888\";}}'), ('ef4aa7220928c31d718e670a745d1bef','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36','1506309314','');
INSERT INTO `customers` VALUES ('1','CUS000001','Customer 1','Customer 1 Person','02 987 3131','email@mail.com','Pasig City','2017-09-25','1','0'), ('2','CUS000002','Customer 2','Customer 2 Person','02 873 8131','email2@mail.com','Pasig','2017-09-25','1','0'), ('3','CUS000003','Customer 3','Customer 3 Person','02 783 6457','customer3@mail.com','Makati','2017-09-26','1','0');
INSERT INTO `images` VALUES ('1','2.png','uploads/users/2.png','2','users',NULL,'2016-02-12 08:23:10','0'), ('5','1.png','uploads/users/1.png','1','users',NULL,'2016-02-12 08:30:45','0'), ('25','1.png','uploads/students/1.png','1','students',NULL,'2016-07-24 19:54:00','0'), ('26','2.png','uploads/students/2.png','2','students',NULL,'2016-07-24 19:55:17','0'), ('27','3.png','uploads/students/3.png','3','students',NULL,'2016-07-24 19:56:31','0'), ('28','4.png','uploads/students/4.png','4','students',NULL,'2016-07-24 19:58:27','0'), ('29','2.png','uploads/customers/2.png','2','customers',NULL,'2017-09-25 10:20:28','0');
INSERT INTO `items` VALUES ('1','ITM000001','White Blankets','White Blankets','kg','1','0','2017-09-25 11:12:57','1'), ('2','ITM000002','White Bed Sheets','White Bed Sheets','kg','3','0','2017-09-25 11:13:49','1'), ('3','ITM000003','Lab Gowns','Lab Gowns','pc','2','0','2017-09-25 11:14:22','1');
INSERT INTO `item_categories` VALUES ('1','Blankets','kg','0'), ('2','Gowns','kg','0'), ('3','Sheets','kg','0');
INSERT INTO `locations` VALUES ('1','Stock Room 1','01 Pasig City','02 783 6541','Person 1','0'), ('2','Stock Room 2','02 Pasig City','02 833 1241','Person 2','0');
INSERT INTO `materials` VALUES ('1','MAT000001','Fabric Conditioner','Fabric Conditioner','kg','100','1','inventory','1','0','2017-09-23 15:37:39','1'), ('2','MAT000002','Bleach','Bleach detergent soap','kl','50','1','inventory','1','0','2017-09-24 10:52:16','1'), ('3','MAT000003','Liquid soap','Liquid soap','kl','20','4','inventory','1','0','2017-09-24 10:53:21','1');
INSERT INTO `material_categories` VALUES ('1','Detergents','kg','inventory','1','0'), ('2','Service','unit','service','1','0'), ('3','Conditioners','ltr','inventory','1','0'), ('4','Liquid','ltr','inventory','1','0');
INSERT INTO `settings` VALUES ('comp_address','company','1013 Emerald Bldg. Barangay San Antonio Pasig City'), ('comp_contact_no','company','(02) 887 9643'), ('comp_email','company','email@email.com'), ('comp_logo','company','uploads/company/logo.png'), ('comp_name','company','PointOne'), ('comp_tin','company','000-888-888-888');
INSERT INTO `suppliers` VALUES ('1','SUP000001','Supplier 1','37-293710-29','Pasig City','02 987 6541','2017-09-24','1','0'), ('2','SUP000002','Supplier 2','82-9903891-12','Marikina City','02 873 7131','2017-09-24','1','0');
INSERT INTO `tax_types` VALUES ('1','VAT','12'), ('2','NON-VAT','0');
INSERT INTO `trans_types` VALUES ('1','Materials Code','MAT000004'), ('2','Suppliers Code','SUP000003'), ('3','Customers Code','CUS000004'), ('4','Items Code','ITM000004'), ('99','Void Transaction','VOD000001'), ('110','Purchase Order','PUR000001'), ('111','Receive Order','REC000001'), ('120','Work Order','WOI000001'), ('121','Work Order Receive Items','WOD000001'), ('122','Work Order Batch','WOL000001'), ('123','Work Order Lot No.','WOB000001'), ('124','Work Order Dispatch','WOD000001');
INSERT INTO `uom` VALUES ('1','pc','Piece','0'), ('2','unit','Unit','0'), ('3','ltr','Liters','0'), ('4','kg','kilograms','0'), ('5','kl','kiloliters','0'), ('6','kls','kilos','0');
INSERT INTO `users` VALUES ('1','admin','5f4dcc3b5aa765d61d8327deb882cf99','Rey','','Reynalds','','1','rey.tejada01@gmail.com','0917-555-06-82','2014-06-16 14:41:31','0');
INSERT INTO `user_roles` VALUES ('1','Administrator ','System Administrator','all');
INSERT INTO `voids` VALUES ('1','VOD0001','10','2','Yes','2016-07-08 13:55:22','1'), ('2','VOD0001','10','1','test','2016-07-08 20:22:06','1');
INSERT INTO `work_order_stages` VALUES ('1','Sorting','Sorting out items','0'), ('2','Washing','washing items','0'), ('3','Drying','drying items','0');
INSERT INTO `work_order_types` VALUES ('1','Bed Sheets and Blankets Cleaning','Bed Sheets and Blankets Cleaning','kls','0'), ('2','Gown Washing','gown washing','kls','0');
INSERT INTO `work_order_type_items` VALUES ('3','2','3'), ('6','1','1'), ('7','1','2');
INSERT INTO `work_order_type_materials` VALUES ('33','2','1','200','100','20000'), ('34','2','2','200','50','10000'), ('35','2','3','200','20','4000'), ('41','1','1','0.5','100','50'), ('42','1','2','0.8','50','40');
INSERT INTO `work_order_type_stages` VALUES ('33','2','0','1'), ('34','2','1','2'), ('35','2','2','3'), ('42','1','0','1'), ('43','1','1','2'), ('44','1','2','3');
