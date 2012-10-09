-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.5.25a - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-10-10 00:07:43
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

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
	(30, 'สละ', 450, 'สละพันธุ์ดีมาก', 700, 0, 'กิโลกรัม', '2011-04-10 00:43:09', '2011-04-10 00:43:09', '2012-10-09 20:10:52'),
	(31, 'น้ำตาลทรายขาว', 22, 'อย่างดี', 100, 0, 'กิโลกรัม', '2011-04-10 01:09:29', '2011-04-10 01:09:29', '2012-10-09 20:07:38'),
	(32, 'เกลือ', 28560, 'คุณภาพสูง', 30000, 0, 'กรัม', '2011-04-10 01:14:18', '2011-04-10 01:14:18', '2012-10-09 20:04:32'),
	(33, 'ถ้วยพลาสติก เล็ก', 0, '', 1000, 0, 'ถ้วย', '2011-04-10 05:43:03', '2011-04-10 05:43:03', '2012-10-09 20:04:32'),
	(34, 'ถ้วยพลาสติก กลาง', 600, '', 1000, 0, 'ถ้วย', '2011-04-10 05:43:20', '2011-04-10 05:43:20', '2012-10-09 20:04:32'),
	(35, 'ถ้วยพลาสติก ใหญ่', 800, '', 1000, 0, 'ถ้วย', '2011-04-10 05:43:44', '2011-04-10 05:43:44', '2012-10-09 20:04:32');
/*!40000 ALTER TABLE `material` ENABLE KEYS */;


-- Dumping structure for table champ.material_order
DROP TABLE IF EXISTS `material_order`;
CREATE TABLE IF NOT EXISTS `material_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8_unicode_ci,
  `date_create` date NOT NULL,
  `is_approve` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = ยังไม่ได้ตรวจรับ, 1 = ตรวจรับแล้ว',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.material_order: ~4 rows (approximately)
DELETE FROM `material_order`;
/*!40000 ALTER TABLE `material_order` DISABLE KEYS */;
INSERT INTO `material_order` (`id`, `description`, `date_create`, `is_approve`) VALUES
	(100, NULL, '2012-10-09', 1),
	(101, NULL, '2012-10-09', 1),
	(102, NULL, '2012-10-09', 1),
	(103, NULL, '2012-10-09', 1);
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

-- Dumping data for table champ.material_order_item: ~9 rows (approximately)
DELETE FROM `material_order_item`;
/*!40000 ALTER TABLE `material_order_item` DISABLE KEYS */;
INSERT INTO `material_order_item` (`id_material_order`, `id_material`, `id_supplier`, `quantity_order`, `quantity_receive`, `total_price`) VALUES
	(100, 30, 1, 700, 700, NULL),
	(100, 31, 2, 20, 20, NULL),
	(100, 32, 2, 30000, 30000, NULL),
	(100, 33, 2, 1000, 1000, NULL),
	(100, 34, 2, 1000, 1000, NULL),
	(100, 35, 2, 1000, 1000, NULL),
	(101, 31, 2, 20, 20, NULL),
	(102, 31, 4, 60, 60, NULL),
	(103, 30, 5, 400, 400, NULL);
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

-- Dumping data for table champ.material_transaction: ~27 rows (approximately)
DELETE FROM `material_transaction`;
/*!40000 ALTER TABLE `material_transaction` DISABLE KEYS */;
INSERT INTO `material_transaction` (`id_material`, `id_supplier`, `id_production_log`, `stock_code`, `description`, `amount`, `quantity`, `date_create`) VALUES
	(30, 1, NULL, '2012-10-09', NULL, 21000.00, 700.00, '2012-10-09 20:04:32'),
	(31, 2, NULL, '2012-10-09', NULL, 400.00, 20.00, '2012-10-09 20:04:32'),
	(32, 2, NULL, '2012-10-09', NULL, 300.00, 30000.00, '2012-10-09 20:04:32'),
	(33, 2, NULL, '2012-10-09', NULL, 1000.00, 1000.00, '2012-10-09 20:04:32'),
	(34, 2, NULL, '2012-10-09', NULL, 2000.00, 1000.00, '2012-10-09 20:04:32'),
	(35, 2, NULL, '2012-10-09', NULL, 3000.00, 1000.00, '2012-10-09 20:04:32'),
	(31, 2, NULL, '2012-10-09', NULL, 600.00, 20.00, '2012-10-09 20:05:14'),
	(31, 4, NULL, '2012-10-09', NULL, 1800.00, 60.00, '2012-10-09 20:07:38'),
	(30, 1, 35, '2012-10-09', 'นำไปผลิต', 0.00, -300.00, '2012-10-09 20:10:08'),
	(31, 2, 35, '2012-10-09', 'นำไปผลิต', 0.00, -36.00, '2012-10-09 20:10:08'),
	(32, 2, 35, '2012-10-09', 'นำไปผลิต', 0.00, -660.00, '2012-10-09 20:10:08'),
	(33, 2, 35, '2012-10-09', 'นำไปผลิต', 0.00, -400.00, '2012-10-09 20:10:08'),
	(34, 2, 35, '2012-10-09', 'นำไปผลิต', 0.00, -200.00, '2012-10-09 20:10:08'),
	(35, 2, 35, '2012-10-09', 'นำไปผลิต', 0.00, -100.00, '2012-10-09 20:10:08'),
	(30, 5, NULL, '2012-10-09', NULL, 16000.00, 400.00, '2012-10-09 20:10:52'),
	(30, 1, 36, '2012-10-09', 'นำไปผลิต', 0.00, -175.00, '2012-10-10 20:21:33'),
	(31, 2, 36, '2012-10-09', 'นำไปผลิต', 0.00, -21.00, '2012-10-10 20:21:33'),
	(32, 2, 36, '2012-10-09', 'นำไปผลิต', 0.00, -390.00, '2012-10-10 20:21:33'),
	(33, 2, 36, '2012-10-09', 'นำไปผลิต', 0.00, -300.00, '2012-10-10 20:21:33'),
	(34, 2, 36, '2012-10-09', 'นำไปผลิต', 0.00, -100.00, '2012-10-10 20:21:33'),
	(35, 2, 36, '2012-10-09', 'นำไปผลิต', 0.00, -50.00, '2012-10-10 20:21:33'),
	(30, 1, 37, '2012-10-09', 'นำไปผลิต', 0.00, -175.00, '2012-10-11 20:25:59'),
	(31, 2, 37, '2012-10-09', 'นำไปผลิต', 0.00, -21.00, '2012-10-11 20:25:59'),
	(32, 2, 37, '2012-10-09', 'นำไปผลิต', 0.00, -390.00, '2012-10-11 20:25:59'),
	(33, 2, 37, '2012-10-09', 'นำไปผลิต', 0.00, -300.00, '2012-10-11 20:25:59'),
	(34, 2, 37, '2012-10-09', 'นำไปผลิต', 0.00, -100.00, '2012-10-11 20:25:59'),
	(35, 2, 37, '2012-10-09', 'นำไปผลิต', 0.00, -50.00, '2012-10-11 20:25:59');
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
  `stock_max` int(10) unsigned NOT NULL,
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
INSERT INTO `product` (`id`, `name`, `description`, `weight`, `unit`, `image`, `stock_max`, `order_min`, `unit_per_labour`, `total`, `price_retail`, `price_wholesale`, `date_create`, `date_update`) VALUES
	(1, 'สละลอยแก้ว ขนาดเล็ก', 'หวาน...กรอบ', 150, 'ถ้วย', '', 400, 100, 100, 0, 15, 12, '2012-05-15 08:51:49', '2012-05-15 08:51:49'),
	(2, 'สละลอยแก้ว ขนาดกลาง', 'อร่อยมาก', 300, 'ถ้วย', '', 200, 50, 50, 0, 30, 25, '2012-05-15 08:51:59', '2012-05-15 08:51:59'),
	(3, 'สละลอยแก้ว ขนาดใหญ่', '', 450, 'ถ้วย', '', 100, 25, 25, 0, 60, 50, '2012-05-15 08:52:11', '2012-06-19 04:43:16');
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.production_log: ~3 rows (approximately)
DELETE FROM `production_log`;
/*!40000 ALTER TABLE `production_log` DISABLE KEYS */;
INSERT INTO `production_log` (`id`, `is_approved`, `description`, `date_create`, `date_exp`, `date_work`, `date_update`) VALUES
	(35, 1, '', '2012-10-09', '2012-11-08', '2012-10-09', '2012-10-09 20:09:54'),
	(36, 1, '', '2012-10-10', '2012-11-09', '2012-10-10', '2012-10-10 20:20:52'),
	(37, 1, '', '2012-10-11', '2012-11-10', '2012-10-11', '2012-10-11 20:25:45');
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

-- Dumping data for table champ.production_member: ~25 rows (approximately)
DELETE FROM `production_member`;
/*!40000 ALTER TABLE `production_member` DISABLE KEYS */;
INSERT INTO `production_member` (`id_log`, `id_assigned_member`, `id_worked_member`) VALUES
	(35, 1, 1),
	(35, 3, 3),
	(35, 5, 5),
	(35, 6, 6),
	(35, 7, 7),
	(35, 8, 8),
	(35, 9, 9),
	(35, 10, 10),
	(35, 11, 11),
	(35, 12, 12),
	(35, 13, 13),
	(36, 14, 14),
	(36, 15, 15),
	(36, 16, 16),
	(36, 17, 17),
	(36, 18, 18),
	(36, 19, 19),
	(36, 20, 20),
	(37, 1, 1),
	(37, 3, 3),
	(37, 5, 5),
	(37, 6, 6),
	(37, 7, 7),
	(37, 8, 8),
	(37, 9, 9);
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

-- Dumping data for table champ.production_product: ~9 rows (approximately)
DELETE FROM `production_product`;
/*!40000 ALTER TABLE `production_product` DISABLE KEYS */;
INSERT INTO `production_product` (`id_log`, `id_product`, `type`, `id_order`, `quantity_order`, `quantity_receive`) VALUES
	(35, 1, 0, NULL, 400, 400),
	(35, 2, 0, NULL, 200, 200),
	(35, 3, 0, NULL, 100, 100),
	(36, 1, 0, NULL, 300, 300),
	(36, 2, 0, NULL, 100, 100),
	(36, 3, 0, NULL, 50, 50),
	(37, 1, 0, NULL, 300, 300),
	(37, 2, 0, NULL, 100, 100),
	(37, 3, 0, NULL, 50, 50);
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_order: ~2 rows (approximately)
DELETE FROM `product_order`;
/*!40000 ALTER TABLE `product_order` DISABLE KEYS */;
INSERT INTO `product_order` (`id`, `type`, `orderer`, `tel`, `description`, `is_receive`, `is_produced`, `date_receive`, `date_create`, `date_update`) VALUES
	(26, 1, 'บุคคลทั่วไป', NULL, NULL, 0, 0, NULL, '2012-10-09', '2012-10-09 20:20:18'),
	(27, 1, 'บุคคลทั่วไป', NULL, NULL, 0, 0, NULL, '2012-10-10', '2012-10-10 20:23:32'),
	(28, 0, 'ตรงกระแส', '0840149016', NULL, 0, 0, '2012-10-31', '2012-10-10', '2012-10-10 00:03:24'),
	(29, 0, 'เป็นต่อมาก', '0840149016', NULL, 0, 0, '2012-10-26', '2012-10-10', '2012-10-10 00:03:49');
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

-- Dumping data for table champ.product_order_item: ~6 rows (approximately)
DELETE FROM `product_order_item`;
/*!40000 ALTER TABLE `product_order_item` DISABLE KEYS */;
INSERT INTO `product_order_item` (`id_order`, `id_product`, `quantity`, `quantity_received`) VALUES
	(26, 1, 300, 0),
	(26, 2, 100, 0),
	(26, 3, 50, 0),
	(27, 1, 300, 0),
	(27, 2, 100, 0),
	(27, 3, 50, 0),
	(28, 2, 60, 0),
	(29, 3, 30, 0);
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
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table champ.product_transaction: ~16 rows (approximately)
DELETE FROM `product_transaction`;
/*!40000 ALTER TABLE `product_transaction` DISABLE KEYS */;
INSERT INTO `product_transaction` (`id`, `id_production_log`, `id_product`, `stock_code`, `type`, `description`, `quantity`, `date_exp`, `date_create`) VALUES
	(111, 35, 1, '2012-10-09', 0, NULL, 400, '2012-11-08', '2012-10-09 20:10:08'),
	(112, 35, 2, '2012-10-09', 0, NULL, 200, '2012-11-08', '2012-10-09 20:10:08'),
	(113, 35, 3, '2012-10-09', 0, NULL, 100, '2012-11-08', '2012-10-09 20:10:08'),
	(114, NULL, 1, '2012-10-09', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000026', -300, NULL, '2012-10-09 20:20:18'),
	(115, NULL, 2, '2012-10-09', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000026', -100, NULL, '2012-10-09 20:20:18'),
	(116, NULL, 3, '2012-10-09', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000026', -50, NULL, '2012-10-09 20:20:18'),
	(117, 36, 1, '2012-10-10', 0, NULL, 300, '2012-11-09', '2012-10-10 20:21:33'),
	(118, 36, 2, '2012-10-10', 0, NULL, 100, '2012-11-09', '2012-10-10 20:21:33'),
	(119, 36, 3, '2012-10-10', 0, NULL, 50, '2012-11-09', '2012-10-10 20:21:33'),
	(120, NULL, 1, '2012-10-09', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000027', -100, NULL, '2012-10-10 20:23:32'),
	(121, NULL, 1, '2012-10-10', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000027', -200, NULL, '2012-10-10 20:23:32'),
	(122, NULL, 2, '2012-10-09', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000027', -100, NULL, '2012-10-10 20:23:32'),
	(123, NULL, 3, '2012-10-09', 2, 'ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ 0000000027', -50, NULL, '2012-10-10 20:23:32'),
	(124, 37, 1, '2012-10-11', 0, NULL, 300, '2012-11-10', '2012-10-11 20:25:59'),
	(125, 37, 2, '2012-10-11', 0, NULL, 100, '2012-11-10', '2012-10-11 20:25:59'),
	(126, 37, 3, '2012-10-11', 0, NULL, 50, '2012-11-10', '2012-10-11 20:25:59');
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
