<?php 
require("../include/session.php");
require('../include/connect.php');

$sql	= 'SELECT * FROM supplier WHERE id = '.$_GET['id'];
$query	= mysql_query($sql) or die(mysql_error());
$data	= mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ผู้จัดจำหน่าย</title>
<?php include("inc.css.php"); ?>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<a href="supplier_update.php?id=<?php echo $_GET['id'] ?>" class="button float_r">แก้ไข</a>
		<h1>ข้อมูลผู้จัดจำหน่าย</h1>
		<hr>
		<form method="post" enctype="multipart/form-data">
			<label for="name">ชื่อ</label>
			<input name="name" type="text" id="name" value="<?php echo $data['name'] ?>" readonly="readonly" />
			<label for="address">ที่อยู่</label>
			<textarea name="address" rows="5" readonly="readonly" id="address"><?php echo $data['address'] ?></textarea>
			<label for="tel">โทรศัพท์</label>
			<input name="tel" type="text" id="tel" value="<?php echo $data['tel'] ?>" readonly="readonly" />
			<label for="fax">แฟกซ์</label>
			<input id="fax" name="fax" type="text" value="<?php echo $data['fax'] ?>" />
			<label for="contact">ผู้ประสานงาน</label>
			<input name="contact" type="text" id="contact" value="<?php echo $data['contact'] ?>" readonly="readonly" />
			<label for="contact_tel">เบอร์โทรศัพท์ ผู้ประสานงาน</label>
			<input name="contact_tel" type="text" id="contact_tel" value="<?php echo $data['contact_tel'] ?>" readonly="readonly" />
		</form>
		<hr style="margin-top:25px" />
		<a href="supplier.php">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>