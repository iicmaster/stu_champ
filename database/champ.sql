-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.1.57-community - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL version:             7.0.0.4140
-- Date/time:                    2012-05-22 16:36:25
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for champ
DROP DATABASE IF EXISTS `champ`;
CREATE DATABASE IF NOT EXISTS `champ` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `champ`;


-- Dumping structure for table champ.account
DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `balance` int(10) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_transaction_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.account: ~0 rows (approximately)
DELETE FROM `account`;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
/*!40000 ALTER TABLE `account` ENABLE KEYS */;


-- Dumping structure for table champ.account_transaction
DROP TABLE IF EXISTS `account_transaction`;
CREATE TABLE IF NOT EXISTS `account_transaction` (
  `id_account` int(10) unsigned NOT NULL,
  `payer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payee` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(10) NOT NULL,
  `date_create` datetime NOT NULL,
  KEY `FK_account_transaction_account` (`id_account`),
  CONSTRAINT `FK_account_transaction_account` FOREIGN KEY (`id_account`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.account_transaction: ~0 rows (approximately)
DELETE FROM `account_transaction`;
/*!40000 ALTER TABLE `account_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_transaction` ENABLE KEYS */;


-- Dumping structure for table champ.material
DROP TABLE IF EXISTS `material`;
CREATE TABLE IF NOT EXISTS `material` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `stock_min` int(10) unsigned NOT NULL DEFAULT '0',
  `stock_max` int(10) unsigned NOT NULL DEFAULT '0',
  `unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `average_cost_per_unit` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `date_create` datetime NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update_transaction` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.material: ~8 rows (approximately)
DELETE FROM `material`;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
INSERT INTO `material` (`id`, `name`, `description`, `total`, `stock_min`, `stock_max`, `unit`, `average_cost_per_unit`, `date_create`, `date_update`, `date_update_transaction`) VALUES
	(30, 'สละ', 'สละพันธุ์ดีมาก', 80, 100, 0, 'กิโลกรัม', 85.00, '2011-04-10 00:43:09', '2011-04-10 00:43:09', '2012-03-01 01:48:38'),
	(31, 'น้ำตาลทรายขาว', 'อย่างดี', 110, 100, 0, 'กิโลกรัม', 11.64, '2011-04-10 01:09:29', '2011-04-10 01:09:29', '2012-03-01 01:48:38'),
	(32, 'เกลือ', 'คุณภาพสูง', 20000, 20000, 0, 'กรัม', 0.01, '2011-04-10 01:14:18', '2011-04-10 01:14:18', '2011-09-12 02:48:34'),
	(33, 'ถ้วยพลาสติก เล็ก', '', 200, 500, 0, 'ถ้วย', 0.50, '2011-04-10 05:43:03', '2011-04-10 05:43:03', '2011-09-12 02:48:34'),
	(34, 'ถ้วยพลาสติก กลาง', '', 300, 500, 0, 'ถ้วย', 1.83, '2011-04-10 05:43:20', '2011-04-10 05:43:20', '2012-03-01 01:48:38'),
	(35, 'ถ้วยพลาสติก ใหญ่', '', 610, 500, 0, 'ถ้วย', 2.03, '2011-04-10 05:43:44', '2011-04-10 05:43:44', '2011-09-12 02:47:51'),
	(36, 'หนังยาง', 'หนังยางรัดของ', 2700, 2000, 0, 'เส้น', 0.12, '2011-04-11 03:45:44', '2011-04-11 03:45:44', '2011-09-12 02:47:51'),
	(38, 'สละพันธ์สุมาลีคัดพิเศษ', '', 0, 50, 100, 'กิโลกรัม', 0.00, '2012-04-19 04:16:40', '2012-04-19 04:16:40', NULL);
/*!40000 ALTER TABLE `material` ENABLE KEYS */;


-- Dumping structure for table champ.material_order
DROP TABLE IF EXISTS `material_order`;
CREATE TABLE IF NOT EXISTS `material_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8_unicode_ci,
  `date_create` date NOT NULL,
  `is_approve` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = ยังไม่ได้ตรวจรับ, 1 = ตรวจรับแล้ว',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.material_order: ~13 rows (approximately)
DELETE FROM `material_order`;
/*!40000 ALTER TABLE `material_order` DISABLE KEYS */;
INSERT INTO `material_order` (`id`, `description`, `date_create`, `is_approve`) VALUES
	(53, '', '2011-09-12', 1),
	(54, '', '2011-09-12', 1),
	(56, '', '2011-09-12', 1),
	(57, '', '2011-09-12', 1),
	(58, '', '2011-09-12', 1),
	(59, '', '2011-09-12', 1),
	(68, '', '2011-09-12', 0),
	(69, '', '2011-09-12', 0),
	(70, '', '2011-09-12', 0),
	(71, '', '2011-09-12', 0),
	(72, '', '2012-02-29', 0),
	(73, '', '2012-02-29', 1),
	(74, NULL, '2012-04-17', 0);
/*!40000 ALTER TABLE `material_order` ENABLE KEYS */;


-- Dumping structure for table champ.material_order_item
DROP TABLE IF EXISTS `material_order_item`;
CREATE TABLE IF NOT EXISTS `material_order_item` (
  `id_material_order` int(10) unsigned NOT NULL,
  `id_material` int(10) unsigned NOT NULL,
  `id_supplier` int(10) unsigned NOT NULL,
  `quantity_order` int(10) unsigned NOT NULL,
  `quantity_receive` int(10) DEFAULT NULL,
  `total_price` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_material_order`,`id_material`),
  KEY `FK_material_order_item_supplier` (`id_supplier`),
  KEY `FK_material_order_item_material` (`id_material`),
  CONSTRAINT `FK_material_order_item_material` FOREIGN KEY (`id_material`) REFERENCES `material` (`id`),
  CONSTRAINT `FK_material_order_item_material_order` FOREIGN KEY (`id_material_order`) REFERENCES `material_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_material_order_item_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.material_order_item: ~31 rows (approximately)
DELETE FROM `material_order_item`;
/*!40000 ALTER TABLE `material_order_item` DISABLE KEYS */;
INSERT INTO `material_order_item` (`id_material_order`, `id_material`, `id_supplier`, `quantity_order`, `quantity_receive`, `total_price`) VALUES
	(53, 33, 4, 500, 100, NULL),
	(54, 34, 2, 500, 300, NULL),
	(56, 35, 4, 500, 600, NULL),
	(57, 36, 2, 500, 1000, NULL),
	(58, 34, 2, 500, 100, NULL),
	(58, 35, 2, 500, 10, NULL),
	(58, 36, 2, 500, 200, NULL),
	(59, 31, 2, 90, 80, NULL),
	(59, 32, 4, 8000, 8000, NULL),
	(59, 33, 2, 500, 400, NULL),
	(68, 30, 1, 60, NULL, NULL),
	(68, 31, 2, 10, NULL, NULL),
	(68, 34, 2, 100, NULL, NULL),
	(69, 30, 1, 60, NULL, NULL),
	(69, 31, 2, 10, NULL, NULL),
	(69, 34, 2, 100, NULL, NULL),
	(70, 30, 1, 60, NULL, NULL),
	(70, 31, 2, 10, NULL, NULL),
	(70, 34, 2, 100, NULL, NULL),
	(71, 30, 1, 60, NULL, NULL),
	(71, 31, 2, 10, NULL, NULL),
	(71, 34, 2, 100, NULL, NULL),
	(72, 30, 1, 60, NULL, NULL),
	(72, 31, 2, 10, NULL, NULL),
	(72, 34, 4, 100, NULL, NULL),
	(73, 30, 2, 60, 60, NULL),
	(73, 31, 1, 10, 10, NULL),
	(73, 34, 4, 100, 100, NULL),
	(74, 30, 1, 20, NULL, NULL),
	(74, 33, 2, 300, NULL, NULL),
	(74, 34, 4, 200, NULL, NULL);
/*!40000 ALTER TABLE `material_order_item` ENABLE KEYS */;


-- Dumping structure for table champ.material_supplier
DROP TABLE IF EXISTS `material_supplier`;
CREATE TABLE IF NOT EXISTS `material_supplier` (
  `id_material` int(10) unsigned NOT NULL,
  `id_supplier` int(10) unsigned NOT NULL,
  KEY `FK_material_supplier_material` (`id_material`),
  KEY `FK_material_supplier_supplier` (`id_supplier`),
  CONSTRAINT `FK_material_supplier_material` FOREIGN KEY (`id_material`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_material_supplier_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.material_supplier: ~12 rows (approximately)
DELETE FROM `material_supplier`;
/*!40000 ALTER TABLE `material_supplier` DISABLE KEYS */;
INSERT INTO `material_supplier` (`id_material`, `id_supplier`) VALUES
	(38, 5),
	(36, 2),
	(36, 4),
	(35, 2),
	(34, 2),
	(33, 2),
	(32, 4),
	(32, 2),
	(31, 2),
	(31, 4),
	(30, 1),
	(30, 5);
/*!40000 ALTER TABLE `material_supplier` ENABLE KEYS */;


-- Dumping structure for table champ.material_transaction
DROP TABLE IF EXISTS `material_transaction`;
CREATE TABLE IF NOT EXISTS `material_transaction` (
  `id_material` int(10) unsigned NOT NULL,
  `id_supplier` int(10) unsigned DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `quantity` int(10) NOT NULL DEFAULT '0',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id_material` (`id_material`),
  KEY `FK_material_transaction_supplier` (`id_supplier`),
  CONSTRAINT `FK_material_transaction_material` FOREIGN KEY (`id_material`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_material_transaction_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.material_transaction: ~31 rows (approximately)
DELETE FROM `material_transaction`;
/*!40000 ALTER TABLE `material_transaction` DISABLE KEYS */;
INSERT INTO `material_transaction` (`id_material`, `id_supplier`, `description`, `amount`, `quantity`, `date_create`) VALUES
	(30, 1, '', 1000, 10, '2011-04-10 06:48:15'),
	(30, 1, '', 2000, 10, '2011-04-10 07:05:02'),
	(30, 1, '', 1000, 10, '2011-04-10 07:06:35'),
	(30, 1, '', 1500, 10, '2011-04-10 07:12:57'),
	(30, 1, '', 3600, 20, '2011-04-10 07:21:00'),
	(30, NULL, 'นำไปผลิตสละลอยแก้ว', 0, -50, '2011-04-10 07:24:01'),
	(30, 1, '', 7500, 50, '2011-04-10 07:34:55'),
	(30, 1, '', 6000, 50, '2011-04-10 07:42:43'),
	(30, NULL, 'นำไปผลิตสละลอยแก้ว', 0, -50, '2011-04-10 08:18:18'),
	(30, NULL, 'นำไปผลิตสละลอยแก้ว', 0, -20, '2011-04-10 08:22:47'),
	(31, 2, '', 180, 10, '2011-04-10 08:25:08'),
	(36, 4, '', 100, 500, '2011-04-11 03:46:32'),
	(36, 2, '', 50, 500, '2011-04-11 03:47:55'),
	(36, 4, '', 120, 500, '2011-04-11 04:02:18'),
	(32, 2, '', 100, 12000, '2011-04-17 01:58:49'),
	(33, 4, '', 100, 100, '2011-09-12 02:43:29'),
	(34, 2, '', 600, 300, '2011-09-12 02:45:57'),
	(35, 4, '', 1200, 600, '2011-09-12 02:47:05'),
	(36, 2, '', 50, 1000, '2011-09-12 02:47:15'),
	(34, 2, '', 300, 100, '2011-09-12 02:47:51'),
	(35, 2, '', 40, 10, '2011-09-12 02:47:51'),
	(36, 2, '', 10, 200, '2011-09-12 02:47:51'),
	(31, 2, '', 100, 80, '2011-09-12 02:48:34'),
	(32, 4, '', 150, 8000, '2011-09-12 02:48:34'),
	(33, 2, '', 150, 400, '2011-09-12 02:48:34'),
	(30, 2, '', 600, 60, '2012-03-01 01:46:34'),
	(31, 1, '', 500, 10, '2012-03-01 01:46:34'),
	(34, 4, '', 100, 100, '2012-03-01 01:46:34'),
	(30, 2, NULL, 600, 60, '2012-03-01 01:48:38'),
	(31, 1, NULL, 500, 10, '2012-03-01 01:48:38'),
	(34, 4, NULL, 100, 100, '2012-03-01 01:48:38');
/*!40000 ALTER TABLE `material_transaction` ENABLE KEYS */;


-- Dumping structure for table champ.member
DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `tel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.member: ~18 rows (approximately)
DELETE FROM `member`;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` (`id`, `name`, `nickname`, `address`, `tel`, `image`, `date_create`, `date_update`) VALUES
	(1, 'นายแชมป์', '', '268  ซอยดอนกุศล  ถนนเจริญกรุง 57  เขตสาทร กรุงเทพมหานคร 10120', '022113800', '', NULL, '2012-05-20 14:08:27'),
	(3, 'นายดำรงค์  ปคุณวานิช', '', '166/3  หมู่ 7  ซอยมหามิตร  ถนนทหาร  ตำบลหมากแข้ง อำเภอเมือง อุดรธานี 41000', '0423000312', '', NULL, '2012-05-20 14:08:15'),
	(5, 'นายสมหวัง  จตุรงค์ล้ำเลิศ', '', '64/515 หมู่1  ซอยนครทอง  ถนนบางนา-ตราด  ตำบลราชาเทวะ อำเภอบางพลี สมุทรปราการ 10540', '091044170', '', NULL, '2012-05-20 14:08:01'),
	(6, 'นางสุมาลี  ดลสุขวงศาวัฒน์', '', '605  หมู่10   ถนนนวมินทร์  แขวงคลองกุ่ม เขตบึงกุ่ม กรุงเทพมหานคร 10230', '0-1901-9465', NULL, NULL, '2011-04-16 19:20:24'),
	(7, 'นายทรงพล  อาริยวัฒน์', '', '112/31  หมู่6  ซอยชินเขต1/5   ถนนงามวงศ์วาน  แขวงทุ่งสองห้อง   เขตหลักสี่ กรุงเทพมหานคร 10210', '025892269', '', NULL, '2012-05-20 14:07:45'),
	(8, 'นางสาวอรมนัส  อยู่บุญสอ', '', '124/247  หมู่4  ซอยเรวดี 18  ถนนเรวดี  ตำบลตลาดขวัญ อำเภอเมือง นนทบุรี 11000', '0-2951-4593 กด 0', NULL, NULL, '2011-04-16 19:20:24'),
	(9, 'นางสาวสมใจ  หาญเรืองเกียรติ', '', '        ', '-', NULL, NULL, '2011-04-16 19:20:24'),
	(10, 'นายธันยกร  หลีสันติพงศ์', '', '385/462   เตาปูนแมนชั่น C  ถนนเตชะวณิช  แขวงบางซื่อ เขตบางซื่อ กรุงเทพมหานคร 10800', '0-1825-1432', NULL, NULL, '2011-04-16 19:20:24'),
	(11, 'นางสาวประภาศรี  ทองกิ่งแก้ว', '', '4236/35  ซอยประดู่ 1  ถนนเจริญกรุง  แขวงบางโคล่ เขตบางคอแหลม กรุงเทพมหานคร 10120', '0-1640-2563', NULL, NULL, '2011-04-16 19:20:24'),
	(12, 'นางสาววันเพ็ญ  แซ่เอีย', '', '6/40  ถนนนิพัทธ์สงเคราะห์1  ตำบลหาดใหญ่ อำเภอหาดใหญ่ สงขลา 90110', '0-7424-5459', NULL, NULL, '2011-04-16 19:20:24'),
	(13, 'นายสมพงษ์  สายธารพรม', '', '194/15  หมู่บ้านช. รุ่งเรือง 6  ซอย5   ถนนบางกรวย-ไทรน้อย   ตำบลบางรักพัฒนา  อำเภอบางบัวทอง นนทบุรี 11130', '0-2924-3621 กด 1', NULL, NULL, '2011-04-16 19:20:24'),
	(14, 'นางสาวสุดจิตร  ลาภเลิศสุข', '', '59/22 หมู่3  ซอยเสนานิคม 1 ถนนพหลโยธิน แขวงลาดพร้าว เขตลาดพร้าว กรุงเทพมหานคร 10230', '0-1830-5616', NULL, NULL, '2011-04-16 19:20:24'),
	(15, 'นางวลัยพร  ติ้วเจริญสกุล', '', '89/64  หมู่บ้านกรีนเลค  ถนนบางนา-ตราด  ตำบลราชาเทวะ อำเภอบางพลี สมุทรปราการ 10540', '0-2750-1943', NULL, NULL, '2011-04-16 19:20:24'),
	(16, 'นางพรศรี  สุตเธียรกุล', '', '342  ซอยจรัญสนิทวงศ์ 69  ถนนจรัญสนิทวงศ์  แขวงบางพลัด เขตบางพลัด กรุงเทพมหานคร 10700', '0-2433-1223', NULL, NULL, '2011-04-16 19:20:24'),
	(17, 'นางสาวสุดารัตน์  เกื้อทวีกุล', '', '31  หมู่4  ตำบลคีรีราษฎร์ อำเภอพบพระ ตาก 63160', '0-1727-1385', NULL, NULL, '2011-04-16 19:20:24'),
	(18, 'นางสาวสุกานดา  พลอยส่องแสง', '', '684   ถนนพระราม 3  แขวงบางโพงพาง เขตยานนาวา กรุงเทพมหานคร 10120', '0-2295-0586', NULL, NULL, '2011-04-16 19:20:24'),
	(19, 'นางวารุณี  ลภิธนานุวัฒน์', '', '111/378  หมู่11  หมู่บ้านดิเอมเมอรัล  ซอยเรวดี 50  ถนนติวานนท์  ตำบลตลาดขวัญ อำเภอเมือง นนทบุรี 11000', '0-1643-6975', NULL, NULL, '2011-04-16 19:20:24'),
	(20, 'นายวรพจน์  จงจิตต์', '', '303/1 หมู่2 หมู่บ้านการเคหะทุ่งสองห้อง  ถนนวิภาวดีรังสิต  แขวงทุ่งสองห้อง เขตหลักสี่ กรุงเทพมหานคร 10210', '029819151', '', NULL, '2012-04-15 06:00:07');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;


-- Dumping structure for table champ.product
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `weight` int(10) NOT NULL,
  `unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacture_min` int(10) unsigned NOT NULL,
  `manufacture_max` int(10) unsigned NOT NULL,
  `stock_max` int(10) unsigned NOT NULL,
  `labour_min` int(10) unsigned NOT NULL,
  `order_min` int(10) unsigned NOT NULL,
  `unit_per_labour` int(10) unsigned NOT NULL,
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `price_retail` int(10) unsigned NOT NULL,
  `price_wholesale` int(10) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product: ~3 rows (approximately)
DELETE FROM `product`;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`id`, `name`, `description`, `weight`, `unit`, `image`, `manufacture_min`, `manufacture_max`, `stock_max`, `labour_min`, `order_min`, `unit_per_labour`, `total`, `price_retail`, `price_wholesale`, `date_create`, `date_update`) VALUES
	(1, 'สละลอยแก้ว ขนาดเล็ก', 'หวาน...กรอบ', 150, 'ถ้วย', '', 1, 800, 400, 0, 100, 100, 0, 15, 12, '2012-05-15 08:51:49', '2012-05-15 08:51:49'),
	(2, 'สละลอยแก้ว ขนาดกลาง', 'อร่อยมาก', 300, 'ถ้วย', '', 1, 400, 200, 0, 50, 50, 0, 30, 25, '2012-05-15 08:51:59', '2012-05-15 08:51:59'),
	(3, 'สละลอยแก้ว ขนาดใหญ่', '', 350, 'ถ้วย', '', 1, 200, 100, 0, 25, 25, 0, 60, 50, '2012-05-15 08:52:11', '2012-05-15 08:52:11');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;


-- Dumping structure for table champ.production_log
DROP TABLE IF EXISTS `production_log`;
CREATE TABLE IF NOT EXISTS `production_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8_unicode_ci,
  `date_create` date NOT NULL,
  `date_exp` date NOT NULL,
  `date_work` date NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.production_log: ~1 rows (approximately)
DELETE FROM `production_log`;
/*!40000 ALTER TABLE `production_log` DISABLE KEYS */;
INSERT INTO `production_log` (`id`, `description`, `date_create`, `date_exp`, `date_work`, `date_update`) VALUES
	(2, '', '2012-05-21', '0000-00-00', '2012-05-21', '2012-05-21 09:21:01');
/*!40000 ALTER TABLE `production_log` ENABLE KEYS */;


-- Dumping structure for table champ.production_member
DROP TABLE IF EXISTS `production_member`;
CREATE TABLE IF NOT EXISTS `production_member` (
  `id_log` int(10) unsigned NOT NULL,
  `id_member` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_log`,`id_member`),
  KEY `FK_queue_member_member` (`id_member`),
  CONSTRAINT `FK_queue_member_order` FOREIGN KEY (`id_member`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_queue_member_queue` FOREIGN KEY (`id_log`) REFERENCES `production_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.production_member: ~16 rows (approximately)
DELETE FROM `production_member`;
/*!40000 ALTER TABLE `production_member` DISABLE KEYS */;
INSERT INTO `production_member` (`id_log`, `id_member`) VALUES
	(2, 1),
	(2, 3),
	(2, 5),
	(2, 6),
	(2, 7),
	(2, 8),
	(2, 9),
	(2, 10),
	(2, 11),
	(2, 12),
	(2, 13),
	(2, 14),
	(2, 15),
	(2, 16),
	(2, 17),
	(2, 18);
/*!40000 ALTER TABLE `production_member` ENABLE KEYS */;


-- Dumping structure for table champ.production_product
DROP TABLE IF EXISTS `production_product`;
CREATE TABLE IF NOT EXISTS `production_product` (
  `id_log` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `id_order` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = สต็อกปกติ, 1 = สั่งผลิตจากลูกค้า',
  `quantity` int(10) unsigned NOT NULL,
  KEY `FK_production_product_production_log` (`id_log`),
  KEY `FK_production_product_product` (`id_product`),
  CONSTRAINT `FK_production_product_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_production_product_production_log` FOREIGN KEY (`id_log`) REFERENCES `production_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.production_product: ~6 rows (approximately)
DELETE FROM `production_product`;
/*!40000 ALTER TABLE `production_product` DISABLE KEYS */;
INSERT INTO `production_product` (`id_log`, `id_product`, `id_order`, `type`, `quantity`) VALUES
	(2, 1, 0, 0, 400),
	(2, 2, 0, 0, 200),
	(2, 3, 0, 0, 100),
	(2, 1, 0, 1, 200),
	(2, 2, 0, 1, 100),
	(2, 3, 0, 1, 50);
/*!40000 ALTER TABLE `production_product` ENABLE KEYS */;


-- Dumping structure for table champ.product_material
DROP TABLE IF EXISTS `product_material`;
CREATE TABLE IF NOT EXISTS `product_material` (
  `id_product` int(10) unsigned NOT NULL,
  `id_material` int(10) unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_product`,`id_material`),
  KEY `id_material` (`id_material`),
  CONSTRAINT `FK_product_material_material_2` FOREIGN KEY (`id_material`) REFERENCES `material` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_product_material_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_material: ~12 rows (approximately)
DELETE FROM `product_material`;
/*!40000 ALTER TABLE `product_material` DISABLE KEYS */;
INSERT INTO `product_material` (`id_product`, `id_material`, `quantity`) VALUES
	(1, 30, 0.25),
	(1, 31, 0.03),
	(1, 32, 0.60),
	(1, 33, 1.00),
	(2, 30, 0.50),
	(2, 31, 0.06),
	(2, 32, 1.20),
	(2, 34, 1.00),
	(3, 30, 1.00),
	(3, 31, 0.12),
	(3, 32, 1.80),
	(3, 35, 1.00);
/*!40000 ALTER TABLE `product_material` ENABLE KEYS */;


-- Dumping structure for table champ.product_order
DROP TABLE IF EXISTS `product_order`;
CREATE TABLE IF NOT EXISTS `product_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_receive` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = ยังไม่ได้รับ, 1 = รับแล้ว',
  `date_receive` date NOT NULL,
  `date_create` date NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_order: ~1 rows (approximately)
DELETE FROM `product_order`;
/*!40000 ALTER TABLE `product_order` DISABLE KEYS */;
INSERT INTO `product_order` (`id`, `orderer`, `tel`, `description`, `is_receive`, `date_receive`, `date_create`, `date_update`) VALUES
	(7, 'ตรงกระแส กระแสสั้น', '123456789', '', 0, '2012-05-16', '2012-05-09', '2012-05-09 15:28:16');
/*!40000 ALTER TABLE `product_order` ENABLE KEYS */;


-- Dumping structure for table champ.product_order_item
DROP TABLE IF EXISTS `product_order_item`;
CREATE TABLE IF NOT EXISTS `product_order_item` (
  `id_order` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_order`,`id_product`),
  KEY `FK_product_order_item_product` (`id_product`),
  CONSTRAINT `FK_product_order_item_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_product_order_item_product_order` FOREIGN KEY (`id_order`) REFERENCES `product_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_order_item: ~3 rows (approximately)
DELETE FROM `product_order_item`;
/*!40000 ALTER TABLE `product_order_item` DISABLE KEYS */;
INSERT INTO `product_order_item` (`id_order`, `id_product`, `quantity`) VALUES
	(7, 1, 200),
	(7, 2, 100),
	(7, 3, 50);
/*!40000 ALTER TABLE `product_order_item` ENABLE KEYS */;


-- Dumping structure for table champ.product_stock
DROP TABLE IF EXISTS `product_stock`;
CREATE TABLE IF NOT EXISTS `product_stock` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_production_log` int(10) unsigned NOT NULL DEFAULT '0',
  `id_product` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '0 = สต็อกปกติ, 1 = สั่งผลิตจากลูกค้า',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  `date_expire` datetime NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_transaction_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_stock_product` (`id_product`),
  CONSTRAINT `FK_product_stock_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_stock: ~0 rows (approximately)
DELETE FROM `product_stock`;
/*!40000 ALTER TABLE `product_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_stock` ENABLE KEYS */;


-- Dumping structure for table champ.product_transaction
DROP TABLE IF EXISTS `product_transaction`;
CREATE TABLE IF NOT EXISTS `product_transaction` (
  `id_stock` int(10) unsigned NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `date_create` date NOT NULL,
  KEY `FK_product_transaction_product_stock` (`id_stock`),
  CONSTRAINT `FK_product_transaction_product_stock` FOREIGN KEY (`id_stock`) REFERENCES `product_stock` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_transaction: ~0 rows (approximately)
DELETE FROM `product_transaction`;
/*!40000 ALTER TABLE `product_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_transaction` ENABLE KEYS */;


-- Dumping structure for table champ.supplier
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.supplier: ~4 rows (approximately)
DELETE FROM `supplier`;
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;
INSERT INTO `supplier` (`id`, `name`, `address`, `tel`, `fax`, `contact`, `contact_tel`, `date_create`, `date_update`) VALUES
	(1, 'สวนสละลุงดำ', 'บางกะจะ', '0391234567', '', 'ลุงดัก', '0812345678', '2011-04-09 23:48:00', '2012-04-16 02:01:58'),
	(2, 'บางกะจะ ซุปเปอร์สโตร์', 'บางกะจะ', '039-987654-5', '039-987656', 'คุณพรเพ็ญ ณ บางกะจะ', '', '2011-04-09 23:50:28', '2011-04-09 23:58:43'),
	(4, 'บางกะจะโชห่วย', 'บางกะจะ', '039-441569', '', 'เจ๊พร', '', '2011-04-11 03:45:01', '2011-05-04 09:52:17'),
	(5, 'สวนสละลุงแดง', 'ท่าใหม่', '0812345678', '', 'น้าเขียว', '0845678901', '2012-04-19 03:57:27', '2012-04-19 03:57:27');
/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
