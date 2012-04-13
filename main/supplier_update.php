<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{	
	$sql = 'UPDATE supplier
			SET		
				name 		= "'.$_POST['name'].'",
				address		= "'.$_POST['address'].'",
				tel 		= "'.$_POST['tel'].'",
				fax 		= "'.$_POST['fax'].'",
				contact 	= "'.$_POST['contact'].'",
				contact_tel	= "'.$_POST['contact_tel'].'"
				
			WHERE	
				id= '.$_POST['id'];
			
	$query = mysql_query($sql) or die(mysql_error());

	// Report
	$css 		= '../css/style.css';
	$url_target	= 'supplier.php';
	$title		= 'สถานะการทำงาน';
	$message	= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	
	require_once("../iic_tools/views/iic_report.php");
	exit();
}

$sql = 'SELECT * FROM supplier WHERE id = '.$_GET['id'];
$query = mysql_query($sql);
$data = mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แก้ไขข้อมูลผู้จัดจำหน่าย</title>
<?php include("inc.css.php"); ?>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>แก้ไขข้อมูลผู้จัดจำหน่าย</h1>
		<hr>
		<form method="post" enctype="multipart/form-data">
			<label for="name">ชื่อร้าน</label>
			<input id="name" name="name" type="text" value="<?php echo $data['name'] ?>" />
			<label for="address">ที่อยู่</label>
			<textarea name="address" rows="5" id="address"><?php echo $data['address'] ?></textarea>
			<label for="tel">โทรศัพท์</label>
			<input id="tel" name="tel" type="text" value="<?php echo $data['tel'] ?>" />
			<label for="fax">แฟกซ์</label>
			<input id="fax" name="fax" type="text" value="<?php echo $data['fax'] ?>" />
			<label for="contact">ผู้ประสานงาน</label>
			<input id="contact" name="contact" type="text" value="<?php echo $data['contact'] ?>" />
			<label for="contact_tel">เบอร์โทรศัพท์ ผู้ประสานงาน</label>
			<input id="contact_tel" name="contact_tel" type="text" value="<?php echo $data['contact_tel'] ?>" />
			<input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" />
			<label>
				<input id="submit" name="submit" type="submit" value="บันทึก" />
			</label>
		</form>
		<hr style="margin-top:25px" />
		<a href="supplier.php">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>