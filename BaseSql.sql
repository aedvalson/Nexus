-- MySQL dump 10.11
--
-- Host: localhost    Database: NexusDev
-- ------------------------------------------------------
-- Server version	5.0.51a-24+lenny3

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
-- Table structure for table `TaxRates`
--

DROP TABLE IF EXISTS `TaxRates`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `TaxRates` (
  `id` int(11) NOT NULL auto_increment,
  `county` varchar(100) collate utf8_unicode_ci NOT NULL,
  `state` varchar(16) collate utf8_unicode_ci NOT NULL,
  `rate` decimal(6,3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `TaxRates`
--

LOCK TABLES `TaxRates` WRITE;
/*!40000 ALTER TABLE `TaxRates` DISABLE KEYS */;
INSERT INTO `TaxRates` VALUES (1,'Alachua','FL','6.500'),(4,'Baker','FL','6.125'),(3,'Marion','FL','7.000'),(6,'Pinellas','FL','7.000');
/*!40000 ALTER TABLE `TaxRates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_history`
--

DROP TABLE IF EXISTS `admin_history`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `admin_history` (
  `id` int(8) NOT NULL auto_increment,
  `datetime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `user_id` int(8) NOT NULL,
  `table_name` varchar(128) collate utf8_unicode_ci NOT NULL,
  `action_name` varchar(128) collate utf8_unicode_ci NOT NULL,
  `value` varchar(128) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `admin_history`
--

LOCK TABLES `admin_history` WRITE;
/*!40000 ALTER TABLE `admin_history` DISABLE KEYS */;
INSERT INTO `admin_history` VALUES (1,'2011-05-30 19:38:13',11,'teams','insert',''),(2,'2011-05-30 19:38:32',11,'users','update','admin'),(3,'2011-05-30 19:39:13',11,'products','insert',''),(4,'2011-05-30 19:39:31',11,'storagelocations','insert',''),(5,'2011-05-30 19:40:30',11,'users','insert','');
/*!40000 ALTER TABLE `admin_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commission_templates`
--

DROP TABLE IF EXISTS `commission_templates`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `commission_templates` (
  `id` int(4) NOT NULL auto_increment,
  `template_name` varchar(100) character set utf8 NOT NULL,
  `dealers` varchar(2000) character set utf8 NOT NULL,
  `min_price` float NOT NULL,
  `max_price` float NOT NULL,
  `payee_type` varchar(200) collate utf8_unicode_ci NOT NULL,
  `payment_type` varchar(200) collate utf8_unicode_ci NOT NULL,
  `quantity` varchar(100) collate utf8_unicode_ci NOT NULL,
  `amount` decimal(8,3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `commission_templates`
--

LOCK TABLES `commission_templates` WRITE;
/*!40000 ALTER TABLE `commission_templates` DISABLE KEYS */;
INSERT INTO `commission_templates` VALUES (2,'1000 Split - Dealer Remaining','{\"dealers\":[{\"role\":\"Dealer\"}]}',1000,999999,'employee','remaining','','0.000'),(3,'1000 Split - Corporate 1000','{\"dealers\":[]}',1000,999999,'corporate','flat','','1000.000');
/*!40000 ALTER TABLE `commission_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_types`
--

DROP TABLE IF EXISTS `contact_types`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `contact_types` (
  `contact_type_id` int(11) NOT NULL auto_increment,
  `contact_type_name` varchar(50) collate ascii_bin NOT NULL,
  PRIMARY KEY  (`contact_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=ascii COLLATE=ascii_bin;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `contact_types`
--

LOCK TABLES `contact_types` WRITE;
/*!40000 ALTER TABLE `contact_types` DISABLE KEYS */;
INSERT INTO `contact_types` VALUES (2,'Customer'),(3,'Lead');
/*!40000 ALTER TABLE `contact_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `contacts` (
  `contact_id` int(8) NOT NULL auto_increment,
  `contact_firstname` varchar(40) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_lastname` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_DisplayName` varchar(200) collate ascii_bin NOT NULL,
  `contact_email` varchar(60) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_phone` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_phonedetails` varchar(30) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_address` varchar(200) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_address2` varchar(4096) collate ascii_bin NOT NULL,
  `contact_city` varchar(200) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_state` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_zipcode` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_country` varchar(40) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_alternate_address1` varchar(255) collate ascii_bin NOT NULL,
  `contact_alternate_address2` varchar(255) collate ascii_bin NOT NULL,
  `contact_alternate_city` varchar(255) collate ascii_bin NOT NULL,
  `contact_alternate_state` varchar(255) collate ascii_bin NOT NULL,
  `contact_alternate_zipcode` varchar(32) collate ascii_bin NOT NULL,
  `contact_alternate_country` varchar(255) collate ascii_bin NOT NULL,
  `contact_alternate_phone` varchar(64) collate ascii_bin NOT NULL,
  `contact_notes` varchar(500) character set utf8 collate utf8_unicode_ci NOT NULL,
  `contact_type_id` int(11) NOT NULL,
  `county` varchar(100) collate ascii_bin NOT NULL,
  `contact_home_type` varchar(32) collate ascii_bin NOT NULL,
  `contact_home_status` varchar(32) collate ascii_bin NOT NULL,
  `contact_social` int(8) NOT NULL,
  `contact_license` varchar(200) collate ascii_bin NOT NULL,
  `contact_license_state` varchar(32) collate ascii_bin NOT NULL,
  PRIMARY KEY  (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dealer_roles`
--

DROP TABLE IF EXISTS `dealer_roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dealer_roles` (
  `id` int(8) NOT NULL auto_increment,
  `role` varchar(120) character set utf8 NOT NULL,
  `abbreviation` varchar(10) character set utf8 default NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dealer_roles`
--

LOCK TABLES `dealer_roles` WRITE;
/*!40000 ALTER TABLE `dealer_roles` DISABLE KEYS */;
INSERT INTO `dealer_roles` VALUES (1,'Factory Distributor','FD'),(2,'Distributor Trainee','DT'),(3,'Team Leader','TL'),(4,'Canvasser','CV'),(5,'Field Counselor','FC'),(6,'Dealer Power Specialist','DPS'),(7,'Senior Dealer','SD'),(8,'Dealer','DL'),(9,'Junior Dealer','JD'),(10,'Dealer Trainee','TR');
/*!40000 ALTER TABLE `dealer_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dtoffices`
--

DROP TABLE IF EXISTS `dtoffices`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dtoffices` (
  `dtoffice` varchar(120) NOT NULL,
  `master` int(4) NOT NULL,
  PRIMARY KEY  (`dtoffice`),
  KEY `master` (`master`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dtoffices`
--

LOCK TABLES `dtoffices` WRITE;
/*!40000 ALTER TABLE `dtoffices` DISABLE KEYS */;
/*!40000 ALTER TABLE `dtoffices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finance_options`
--

DROP TABLE IF EXISTS `finance_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `finance_options` (
  `id` int(8) NOT NULL auto_increment,
  `CompanyName` varchar(200) collate utf8_unicode_ci NOT NULL,
  `Address` varchar(200) collate utf8_unicode_ci NOT NULL,
  `City` varchar(100) collate utf8_unicode_ci NOT NULL,
  `State` varchar(8) collate utf8_unicode_ci NOT NULL,
  `ZipCode` varchar(10) collate utf8_unicode_ci NOT NULL,
  `ContactName` varchar(200) collate utf8_unicode_ci NOT NULL,
  `Phone` varchar(50) collate utf8_unicode_ci NOT NULL,
  `Extension` varchar(20) collate utf8_unicode_ci NOT NULL,
  `Email` varchar(100) collate utf8_unicode_ci NOT NULL,
  `LoanOptions` varchar(4096) character set utf8 NOT NULL,
  `Reserve` decimal(6,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `finance_options`
--

LOCK TABLES `finance_options` WRITE;
/*!40000 ALTER TABLE `finance_options` DISABLE KEYS */;
INSERT INTO `finance_options` VALUES (1,'Default Finance Option','123 Asd Street.','Gainesville','FL','32641','John Smith','123-123-1234','123','jon@smith.org','{\"loanOptions\":[{\"Index\":0,\"optionName\":\"Default Rate\",\"displayOrder\":\"1\",\"reserve\":\"10\"}]}','0.000');
/*!40000 ALTER TABLE `finance_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `inventory` (
  `inventory_id` int(4) NOT NULL auto_increment,
  `product_id` int(4) NOT NULL,
  `storagelocation_id` int(4) NOT NULL,
  `dtoffice` varchar(64) collate ascii_bin NOT NULL,
  `invoice` varchar(20) collate ascii_bin NOT NULL,
  `serial` varchar(50) collate ascii_bin NOT NULL,
  `status` int(4) NOT NULL default '1',
  `status_data` int(16) NOT NULL,
  `status_data_text` varchar(200) collate ascii_bin default NULL,
  `status_date` timestamp NULL default NULL,
  `AddedBy` int(11) NOT NULL,
  `DateAdded` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `DateReceived` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`inventory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=ascii COLLATE=ascii_bin;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,8,10,'','1','1',1,10,'Default Location','2011-05-30 04:00:00',11,'2011-05-30 19:46:36','2011-05-01 04:00:00'),(2,8,10,'','1','2',1,10,'Default Location','2011-05-30 04:00:00',11,'2011-05-30 19:46:36','2011-05-01 04:00:00'),(3,8,10,'','1','3',1,10,'Default Location','2011-05-30 04:00:00',11,'2011-05-30 19:46:36','2011-05-01 04:00:00');
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_status`
--

DROP TABLE IF EXISTS `inventory_status`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `inventory_status` (
  `status_id` int(4) NOT NULL auto_increment,
  `status_name` varchar(50) collate ascii_bin NOT NULL,
  `status_data` int(16) NOT NULL,
  `status_date` timestamp NULL default NULL,
  `preposition` varchar(20) collate ascii_bin NOT NULL,
  PRIMARY KEY  (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=ascii COLLATE=ascii_bin;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `inventory_status`
--

LOCK TABLES `inventory_status` WRITE;
/*!40000 ALTER TABLE `inventory_status` DISABLE KEYS */;
INSERT INTO `inventory_status` VALUES (1,'Checked In',0,NULL,'at'),(2,'Checked Out',0,NULL,'to'),(3,'Transferred',0,NULL,'to'),(4,'Sale Pending',0,NULL,'for'),(5,'Sold',0,NULL,'to'),(6,'Reported Lost',0,NULL,'on'),(7,'Damaged',0,NULL,'on');
/*!40000 ALTER TABLE `inventory_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_commissions`
--

DROP TABLE IF EXISTS `order_commissions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `order_commissions` (
  `id` int(8) NOT NULL auto_increment,
  `order_id` int(8) NOT NULL,
  `payee_type` varchar(50) collate utf8_unicode_ci NOT NULL,
  `payment_type` varchar(50) collate utf8_unicode_ci NOT NULL,
  `payee` int(8) NOT NULL,
  `amount` double(8,2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `order_commissions`
--

LOCK TABLES `order_commissions` WRITE;
/*!40000 ALTER TABLE `order_commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(4) NOT NULL default '1',
  PRIMARY KEY  (`order_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_payments`
--

DROP TABLE IF EXISTS `order_payments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `order_payments` (
  `id` int(16) NOT NULL auto_increment,
  `order_id` int(16) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `order_payments`
--

LOCK TABLES `order_payments` WRITE;
/*!40000 ALTER TABLE `order_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status`
--

DROP TABLE IF EXISTS `order_status`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `order_status` (
  `order_status_id` int(11) NOT NULL auto_increment,
  `order_status_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`order_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `order_status`
--

LOCK TABLES `order_status` WRITE;
/*!40000 ALTER TABLE `order_status` DISABLE KEYS */;
INSERT INTO `order_status` VALUES (1,'New Contact'),(2,'Contacted'),(3,'Presentation Complete'),(4,'Waiting on Customer'),(5,'Completed'),(6,'Delivered - Pending Financing'),(7,'Undelivered - Pending     Financing'),(8,'Cancelled');
/*!40000 ALTER TABLE `order_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders` (
  `order_id` int(8) NOT NULL auto_increment,
  `order_status_id` int(4) NOT NULL,
  `amount` double(8,2) NOT NULL default '0.00',
  `tax` float(12,3) NOT NULL default '0.000',
  `contact_id` int(11) NOT NULL,
  `cobuyer_contact_id` int(32) NOT NULL,
  `dealerArray` varchar(4096) NOT NULL,
  `CommStructure` varchar(2000) default NULL,
  `ProductsArray` varchar(4000) default NULL,
  `AccessoriesArray` varchar(4000) default NULL,
  `PaymentArray` varchar(4000) default NULL,
  `AddedBy` int(11) NOT NULL,
  `DateAdded` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `DateCompleted` timestamp NULL default NULL,
  PRIMARY KEY  (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_roles`
--

DROP TABLE IF EXISTS `permission_roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `permission_roles` (
  `id` int(4) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `permission` int(8) NOT NULL,
  `roleid` varchar(8) character set ucs2 NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `permission_roles`
--

LOCK TABLES `permission_roles` WRITE;
/*!40000 ALTER TABLE `permission_roles` DISABLE KEYS */;
INSERT INTO `permission_roles` VALUES (1,'Read Only',1,'RO'),(2,'User',10,'U'),(3,'Super User',100,'SU'),(4,'Admin',1000,'A');
/*!40000 ALTER TABLE `permission_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products` (
  `product_id` int(4) NOT NULL auto_increment,
  `product_type` varchar(50) collate ascii_bin NOT NULL,
  `product_name` varchar(100) collate ascii_bin NOT NULL,
  `product_model` varchar(50) collate ascii_bin default NULL,
  `product_description` varchar(1000) collate ascii_bin default NULL,
  `status` varchar(50) collate ascii_bin NOT NULL default 'Active',
  PRIMARY KEY  (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=ascii COLLATE=ascii_bin;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (8,'Product','Default Product','DP_001','Default Product.','Active');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relproducts_accessories`
--

DROP TABLE IF EXISTS `relproducts_accessories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `relproducts_accessories` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=ascii COLLATE=ascii_bin;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `relproducts_accessories`
--

LOCK TABLES `relproducts_accessories` WRITE;
/*!40000 ALTER TABLE `relproducts_accessories` DISABLE KEYS */;
/*!40000 ALTER TABLE `relproducts_accessories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `reports` (
  `id` int(4) NOT NULL auto_increment,
  `data` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=590 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storagelocations`
--

DROP TABLE IF EXISTS `storagelocations`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `storagelocations` (
  `storagelocation_id` int(11) NOT NULL auto_increment,
  `storagelocation_name` varchar(200) collate ascii_bin NOT NULL,
  `description` varchar(250) character set utf8 NOT NULL,
  PRIMARY KEY  (`storagelocation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=ascii COLLATE=ascii_bin;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `storagelocations`
--

LOCK TABLES `storagelocations` WRITE;
/*!40000 ALTER TABLE `storagelocations` DISABLE KEYS */;
INSERT INTO `storagelocations` VALUES (10,'Default Location','Default');
/*!40000 ALTER TABLE `storagelocations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL auto_increment,
  `team_name` varchar(100) collate ascii_bin NOT NULL,
  `status` varchar(50) collate ascii_bin NOT NULL default 'Active',
  `team_leader` int(16) NOT NULL,
  `date_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=ascii COLLATE=ascii_bin;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (23,'Default Team','Active',11,'2011-05-30 19:38:13');
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_statuses`
--

DROP TABLE IF EXISTS `user_statuses`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user_statuses` (
  `Status` varchar(20) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user_statuses`
--

LOCK TABLES `user_statuses` WRITE;
/*!40000 ALTER TABLE `user_statuses` DISABLE KEYS */;
INSERT INTO `user_statuses` VALUES ('Active'),('Inactive'),('Terminated');
/*!40000 ALTER TABLE `user_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL auto_increment,
  `Username` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `team_id` int(11) default NULL,
  `FirstName` varchar(250) NOT NULL,
  `LastName` varchar(250) NOT NULL,
  `Status` varchar(30) NOT NULL default 'Active',
  `permission_role` int(4) NOT NULL default '1',
  `License` varchar(32) NOT NULL,
  `Social` varchar(32) NOT NULL,
  `BirthDate` date NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Address2` varchar(255) NOT NULL,
  `HomeType` varchar(32) NOT NULL,
  `City` varchar(64) NOT NULL,
  `State` varchar(8) NOT NULL,
  `ZipCode` varchar(32) NOT NULL,
  `Phone` varchar(64) NOT NULL,
  `Cell` varchar(64) NOT NULL,
  `ContactFirstName` varchar(64) NOT NULL,
  `ContactLastName` varchar(64) NOT NULL,
  `ContactAddress` varchar(255) NOT NULL,
  `ContactAddress2` varchar(255) NOT NULL,
  `ContactCity` varchar(64) NOT NULL,
  `ContactState` varchar(8) NOT NULL,
  `ContactZipCode` varchar(32) NOT NULL,
  `ContactPhone` varchar(64) NOT NULL,
  `ContactCell` varchar(64) NOT NULL,
  `dtoffice` varchar(120) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (11,'admin','4c835f5b332712d2aa2a8f1fc6110dd2',23,'Admin','SuperAdmin','Active',4,'','','0000-00-00','','','','','','','','','','','','','','','','','','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-05-30 19:55:27
