-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.1.63-community - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL version:             7.0.0.4174
-- Date/time:                    2012-09-02 05:19:21
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for champ
DROP DATABASE IF EXISTS `champ`;
CREATE DATABASE IF NOT EXISTS `champ` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `champ`;


-- Dumping structure for table champ.material
DROP TABLE IF EXISTS `material`;
CREATE TABLE IF NOT EXISTS `material` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total` int(10) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `stock_min` int(10) unsigned NOT NULL DEFAULT '0',
  `stock_max` int(10) unsigned NOT NULL DEFAULT '0',
  `unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update_transaction` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.material: ~6 rows (approximately)
DELETE FROM `material`;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
INSERT INTO `material` (`id`, `name`, `total`, `description`, `stock_min`, `stock_max`, `unit`, `date_create`, `date_update`, `date_update_transaction`) VALUES
	(30, 'สละ', 200, 'สละพันธุ์ดีมาก', 700, 0, 'กิโลกรัม', '2011-04-10 00:43:09', '2011-04-10 00:43:09', '2012-09-01 05:45:47'),
	(31, 'น้ำตาลทรายขาว', 40, 'อย่างดี', 100, 0, 'กิโลกรัม', '2011-04-10 01:09:29', '2011-04-10 01:09:29', '2012-09-01 05:45:47'),
	(32, 'เกลือ', 28890, 'คุณภาพสูง', 30000, 0, 'กรัม', '2011-04-10 01:14:18', '2011-04-10 01:14:18', '2012-09-01 05:45:47'),
	(33, 'ถ้วยพลาสติก เล็ก', 200, '', 1000, 0, 'ถ้วย', '2011-04-10 05:43:03', '2011-04-10 05:43:03', '2012-09-01 05:45:47'),
	(34, 'ถ้วยพลาสติก กลาง', 700, '', 1000, 0, 'ถ้วย', '2011-04-10 05:43:20', '2011-04-10 05:43:20', '2012-09-01 05:45:47'),
	(35, 'ถ้วยพลาสติก ใหญ่', 850, '', 1000, 0, 'ถ้วย', '2011-04-10 05:43:44', '2011-04-10 05:43:44', '2012-09-01 05:45:47');
/*!40000 ALTER TABLE `material` ENABLE KEYS */;


-- Dumping structure for table champ.material_order
DROP TABLE IF EXISTS `material_order`;
CREATE TABLE IF NOT EXISTS `material_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8_unicode_ci,
  `date_create` date NOT NULL,
  `is_approve` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = ยังไม่ได้ตรวจรับ, 1 = ตรวจรับแล้ว',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.material_order: ~1 rows (approximately)
DELETE FROM `material_order`;
/*!40000 ALTER TABLE `material_order` DISABLE KEYS */;
INSERT INTO `material_order` (`id`, `description`, `date_create`, `is_approve`) VALUES
	(94, NULL, '2012-09-01', 1);
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

-- Dumping data for table champ.material_order_item: ~6 rows (approximately)
DELETE FROM `material_order_item`;
/*!40000 ALTER TABLE `material_order_item` DISABLE KEYS */;
INSERT INTO `material_order_item` (`id_material_order`, `id_material`, `id_supplier`, `quantity_order`, `quantity_receive`, `total_price`) VALUES
	(94, 30, 1, 700, 700, NULL),
	(94, 31, 2, 100, 100, NULL),
	(94, 32, 2, 30000, 30000, NULL),
	(94, 33, 2, 1000, 1000, NULL),
	(94, 34, 2, 1000, 1000, NULL),
	(94, 35, 2, 1000, 1000, NULL);
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

-- Dumping data for table champ.material_supplier: ~9 rows (approximately)
DELETE FROM `material_supplier`;
/*!40000 ALTER TABLE `material_supplier` DISABLE KEYS */;
INSERT INTO `material_supplier` (`id_material`, `id_supplier`) VALUES
	(31, 2),
	(31, 4),
	(35, 2),
	(34, 2),
	(33, 2),
	(32, 2),
	(32, 4),
	(30, 1),
	(30, 5);
/*!40000 ALTER TABLE `material_supplier` ENABLE KEYS */;


-- Dumping structure for table champ.material_transaction
DROP TABLE IF EXISTS `material_transaction`;
CREATE TABLE IF NOT EXISTS `material_transaction` (
  `id_material` int(10) unsigned NOT NULL,
  `id_supplier` int(10) unsigned DEFAULT NULL,
  `id_production_log` int(10) unsigned DEFAULT NULL,
  `stock_code` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id_material` (`id_material`),
  KEY `FK_material_transaction_supplier` (`id_supplier`),
  CONSTRAINT `FK_material_transaction_material` FOREIGN KEY (`id_material`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_material_transaction_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.material_transaction: ~18 rows (approximately)
DELETE FROM `material_transaction`;
/*!40000 ALTER TABLE `material_transaction` DISABLE KEYS */;
INSERT INTO `material_transaction` (`id_material`, `id_supplier`, `id_production_log`, `stock_code`, `description`, `amount`, `quantity`, `date_create`) VALUES
	(30, 1, NULL, '2012-09-01', NULL, 21000.00, 700.00, '2012-09-01 05:45:47'),
	(31, 2, NULL, '2012-09-01', NULL, 2000.00, 100.00, '2012-09-01 05:45:47'),
	(32, 2, NULL, '2012-09-01', NULL, 300.00, 30000.00, '2012-09-01 05:45:47'),
	(33, 2, NULL, '2012-09-01', NULL, 1000.00, 1000.00, '2012-09-01 05:45:47'),
	(34, 2, NULL, '2012-09-01', NULL, 2000.00, 1000.00, '2012-09-01 05:45:47'),
	(35, 2, NULL, '2012-09-01', NULL, 3000.00, 1000.00, '2012-09-01 05:45:47'),
	(30, 1, 23, '2012-09-01', 'นำไปผลิต', 0.00, -325.00, '2012-09-01 05:48:39'),
	(31, 2, 23, '2012-09-01', 'นำไปผลิต', 0.00, -39.00, '2012-09-01 05:48:39'),
	(32, 2, 23, '2012-09-01', 'นำไปผลิต', 0.00, -720.00, '2012-09-01 05:48:39'),
	(33, 2, 23, '2012-09-01', 'นำไปผลิต', 0.00, -500.00, '2012-09-01 05:48:39'),
	(34, 2, 23, '2012-09-01', 'นำไปผลิต', 0.00, -200.00, '2012-09-01 05:48:39'),
	(35, 2, 23, '2012-09-01', 'นำไปผลิต', 0.00, -100.00, '2012-09-01 05:48:39'),
	(30, 1, 24, '2012-09-01', 'นำไปผลิต', 0.00, -175.00, '2012-09-01 06:05:53'),
	(31, 2, 24, '2012-09-01', 'นำไปผลิต', 0.00, -21.00, '2012-09-01 06:05:53'),
	(32, 2, 24, '2012-09-01', 'นำไปผลิต', 0.00, -390.00, '2012-09-01 06:05:53'),
	(33, 2, 24, '2012-09-01', 'นำไปผลิต', 0.00, -300.00, '2012-09-01 06:05:53'),
	(34, 2, 24, '2012-09-01', 'นำไปผลิต', 0.00, -100.00, '2012-09-01 06:05:53'),
	(35, 2, 24, '2012-09-01', 'นำไปผลิต', 0.00, -50.00, '2012-09-01 06:05:53');
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
	(3, 'สละลอยแก้ว ขนาดใหญ่', '', 450, 'ถ้วย', '', 1, 200, 100, 0, 25, 25, 0, 60, 50, '2012-05-15 08:52:11', '2012-06-19 04:43:16');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;


-- Dumping structure for table champ.production_log
DROP TABLE IF EXISTS `production_log`;
CREATE TABLE IF NOT EXISTS `production_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_approved` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  `date_create` date NOT NULL,
  `date_exp` date NOT NULL,
  `date_work` date NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.production_log: ~5 rows (approximately)
DELETE FROM `production_log`;
/*!40000 ALTER TABLE `production_log` DISABLE KEYS */;
INSERT INTO `production_log` (`id`, `is_approved`, `description`, `date_create`, `date_exp`, `date_work`, `date_update`) VALUES
	(23, 1, '', '2012-09-01', '2012-10-01', '2012-09-01', '2012-09-01 05:47:50'),
	(24, 1, '', '2012-09-01', '2012-10-01', '2012-09-01', '2012-09-01 06:05:42'),
	(25, 0, '', '2012-09-01', '2012-10-01', '2012-09-01', '2012-09-01 23:46:20'),
	(26, 0, '', '2012-09-01', '2012-10-01', '2012-09-01', '2012-09-01 23:52:19'),
	(27, 0, '', '2012-09-01', '2012-10-01', '2012-09-01', '2012-09-01 23:53:36'),
	(28, 0, '', '2012-09-02', '2012-10-02', '2012-09-02', '2012-09-02 04:31:19');
/*!40000 ALTER TABLE `production_log` ENABLE KEYS */;


-- Dumping structure for table champ.production_member
DROP TABLE IF EXISTS `production_member`;
CREATE TABLE IF NOT EXISTS `production_member` (
  `id_log` int(10) unsigned NOT NULL,
  `id_assigned_member` int(10) unsigned NOT NULL,
  `id_worked_member` int(10) unsigned DEFAULT NULL,
  KEY `FK_production_member_production_log` (`id_log`),
  KEY `id_assigned_member` (`id_assigned_member`),
  CONSTRAINT `FK_production_member_production_log` FOREIGN KEY (`id_log`) REFERENCES `production_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `production_member_ibfk_1` FOREIGN KEY (`id_assigned_member`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.production_member: ~49 rows (approximately)
DELETE FROM `production_member`;
/*!40000 ALTER TABLE `production_member` DISABLE KEYS */;
INSERT INTO `production_member` (`id_log`, `id_assigned_member`, `id_worked_member`) VALUES
	(23, 1, 1),
	(23, 3, 3),
	(23, 5, 5),
	(23, 6, 6),
	(23, 7, 7),
	(23, 8, 8),
	(23, 9, 9),
	(23, 10, 10),
	(23, 11, 11),
	(23, 12, 12),
	(23, 13, 13),
	(23, 14, 14),
	(24, 1, 1),
	(24, 3, 3),
	(24, 5, 5),
	(24, 6, 6),
	(24, 7, 7),
	(24, 8, 8),
	(24, 9, 9),
	(25, 15, NULL),
	(25, 1, NULL),
	(25, 17, NULL),
	(25, 18, NULL),
	(25, 19, NULL),
	(25, 20, NULL),
	(25, 10, NULL),
	(25, 11, NULL),
	(25, 12, NULL),
	(25, 13, NULL),
	(26, 15, NULL),
	(26, 1, NULL),
	(26, 17, NULL),
	(26, 18, NULL),
	(26, 19, NULL),
	(26, 20, NULL),
	(26, 10, NULL),
	(26, 11, NULL),
	(26, 12, NULL),
	(26, 13, NULL),
	(27, 15, NULL),
	(27, 16, NULL),
	(27, 17, NULL),
	(27, 18, NULL),
	(27, 19, NULL),
	(27, 20, NULL),
	(27, 10, NULL),
	(27, 11, NULL),
	(27, 12, NULL),
	(27, 13, NULL),
	(28, 15, NULL),
	(28, 16, NULL),
	(28, 17, NULL),
	(28, 18, NULL),
	(28, 19, NULL),
	(28, 20, NULL),
	(28, 10, NULL),
	(28, 11, NULL),
	(28, 12, NULL),
	(28, 13, NULL),
	(28, 14, NULL);
/*!40000 ALTER TABLE `production_member` ENABLE KEYS */;


-- Dumping structure for table champ.production_product
DROP TABLE IF EXISTS `production_product`;
CREATE TABLE IF NOT EXISTS `production_product` (
  `id_log` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = สต็อกปกติ, 1 = สั่งผลิตจากลูกค้า',
  `id_order` int(10) unsigned DEFAULT NULL,
  `quantity_order` int(10) unsigned NOT NULL,
  `quantity_receive` int(10) DEFAULT NULL,
  KEY `id_log` (`id_log`),
  KEY `id_product` (`id_product`),
  CONSTRAINT `production_product_ibfk_1` FOREIGN KEY (`id_log`) REFERENCES `production_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `production_product_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.production_product: ~16 rows (approximately)
DELETE FROM `production_product`;
/*!40000 ALTER TABLE `production_product` DISABLE KEYS */;
INSERT INTO `production_product` (`id_log`, `id_product`, `type`, `id_order`, `quantity_order`, `quantity_receive`) VALUES
	(23, 1, 0, NULL, 400, 400),
	(23, 2, 0, NULL, 200, 200),
	(23, 3, 0, NULL, 100, 100),
	(23, 1, 1, 11, 100, 100),
	(24, 1, 0, NULL, 300, 300),
	(24, 2, 0, NULL, 100, 100),
	(24, 3, 0, NULL, 50, 50),
	(25, 1, 0, NULL, 300, NULL),
	(25, 2, 0, NULL, 200, NULL),
	(25, 3, 0, NULL, 100, NULL),
	(26, 1, 0, NULL, 300, NULL),
	(26, 2, 0, NULL, 200, NULL),
	(26, 3, 0, NULL, 100, NULL),
	(27, 1, 0, NULL, 300, NULL),
	(27, 2, 0, NULL, 200, NULL),
	(27, 3, 0, NULL, 100, NULL),
	(28, 1, 0, NULL, 400, NULL),
	(28, 2, 0, NULL, 200, NULL),
	(28, 3, 0, NULL, 100, NULL);
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
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = สั่งซื้อ, 1 = ขายปลีก',
  `orderer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_receive` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = ยังไม่ได้รับ, 1 = รับแล้ว',
  `is_produced` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = ยังไม่ได้ผลิต, 1 = ผลิตแล้ว',
  `date_receive` date DEFAULT NULL,
  `date_create` date NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_order: ~4 rows (approximately)
DELETE FROM `product_order`;
/*!40000 ALTER TABLE `product_order` DISABLE KEYS */;
INSERT INTO `product_order` (`id`, `type`, `orderer`, `tel`, `description`, `is_receive`, `is_produced`, `date_receive`, `date_create`, `date_update`) VALUES
	(11, 0, 'champ', '0000000000', '', 1, 0, '2012-09-28', '2012-09-01', '2012-09-01 05:50:39'),
	(12, 1, 'pue', NULL, NULL, 0, 0, NULL, '2012-09-01', '2012-09-01 05:51:30'),
	(13, 1, 'champ', NULL, NULL, 0, 0, NULL, '2012-09-01', '2012-09-01 05:54:57'),
	(14, 1, 'gee', NULL, NULL, 0, 0, NULL, '2012-09-01', '2012-09-01 06:06:56');
/*!40000 ALTER TABLE `product_order` ENABLE KEYS */;


-- Dumping structure for table champ.product_order_item
DROP TABLE IF EXISTS `product_order_item`;
CREATE TABLE IF NOT EXISTS `product_order_item` (
  `id_order` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `quantity_received` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_order`,`id_product`),
  KEY `FK_product_order_item_product` (`id_product`),
  CONSTRAINT `FK_product_order_item_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_product_order_item_product_order` FOREIGN KEY (`id_order`) REFERENCES `product_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_order_item: ~8 rows (approximately)
DELETE FROM `product_order_item`;
/*!40000 ALTER TABLE `product_order_item` DISABLE KEYS */;
INSERT INTO `product_order_item` (`id_order`, `id_product`, `quantity`, `quantity_received`) VALUES
	(11, 1, 100, 100),
	(12, 1, 200, 0),
	(12, 2, 100, 0),
	(12, 3, 50, 0),
	(13, 1, 100, 0),
	(14, 1, 300, 0),
	(14, 2, 200, 0),
	(14, 3, 100, 0);
/*!40000 ALTER TABLE `product_order_item` ENABLE KEYS */;


-- Dumping structure for table champ.product_transaction
DROP TABLE IF EXISTS `product_transaction`;
CREATE TABLE IF NOT EXISTS `product_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_production_log` int(10) unsigned DEFAULT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `stock_code` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '0 = สต็อกปกติ, 1 = สั่งผลิตจากลูกค้า, 2 = ขายปลืก, 3 = กำจัด',
  `description` text COLLATE utf8_unicode_ci,
  `quantity` int(10) NOT NULL,
  `date_exp` date DEFAULT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_product_stock_product` (`id_product`),
  CONSTRAINT `FK_product_stock_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_transaction: ~15 rows (approximately)
DELETE FROM `product_transaction`;
/*!40000 ALTER TABLE `product_transaction` DISABLE KEYS */;
INSERT INTO `product_transaction` (`id`, `id_production_log`, `id_product`, `stock_code`, `type`, `description`, `quantity`, `date_exp`, `date_create`) VALUES
	(38, 23, 1, '2012-09-01', 0, NULL, 400, '2012-10-01', '2012-09-02 03:02:40'),
	(39, 23, 2, '2012-09-01', 0, NULL, 200, '2012-10-01', '2012-09-02 03:02:40'),
	(40, 23, 3, '2012-09-01', 0, NULL, 100, '2012-10-01', '2012-09-02 03:02:40'),
	(41, 23, 1, '2012-09-01', 1, NULL, 100, '2012-10-01', '2012-09-02 03:02:40'),
	(42, NULL, 1, '2012-09-01', 1, 'ส่งมอบให้ลูกค้า รหัสอ้างอิงใบสั่งซื้อที่ 0000000011', -100, '2012-10-01', '2012-09-02 03:02:40'),
	(43, NULL, 1, '2012-09-01', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000012', -200, '2012-10-01', '2012-09-02 03:02:40'),
	(44, NULL, 2, '2012-09-01', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000012', -100, '2012-10-01', '2012-09-02 03:02:40'),
	(45, NULL, 3, '2012-09-01', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000012', -50, '2012-10-01', '2012-09-02 03:02:40'),
	(46, NULL, 1, '2012-09-01', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000013', -100, '2012-10-01', '2012-09-02 03:02:40'),
	(47, 24, 1, '2012-09-01', 0, NULL, 300, '2012-10-01', '2012-09-02 03:02:40'),
	(48, 24, 2, '2012-09-01', 0, NULL, 100, '2012-10-01', '2012-09-02 03:02:40'),
	(49, 24, 3, '2012-09-01', 0, NULL, 50, '2012-10-01', '2012-09-02 03:02:40'),
	(50, NULL, 1, '2012-09-01', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000014', -300, '2012-10-01', '2012-09-02 03:02:40'),
	(51, NULL, 2, '2012-09-01', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000014', -200, '2012-10-01', '2012-09-02 03:02:40'),
	(52, NULL, 3, '2012-09-01', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000014', -100, '2012-10-01', '2012-09-02 03:02:40'),
	(65, NULL, 1, '2012-09-01', 3, 'กำจัดทิ้ง', -100, NULL, '2012-09-02 04:30:30');
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
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
