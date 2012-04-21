<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{	
	$sql = 'INSERT INTO supplier
			SET		name 		= "'.$_POST['name'].'",
					address		= "'.$_POST['address'].'",
					tel 		= "'.$_POST['tel'].'",
					fax 		= "'.$_POST['fax'].'",
					contact 	= "'.$_POST['contact'].'",
					contact_tel	= "'.$_POST['contact_tel'].'",
					date_create	= NOW()';
					
	$query = mysql_query($sql) or die(mysql_error());

	// Report
	$css 		= '../css/style.css';
	$url_target = 'supplier.php';
	$title		= 'สถานะการทำงาน';
	$message 	= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	
	require_once("../iic_tools/views/iic_report.php");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>เพิ่มผู้จัดจำหน่าย</title>
<?php include("inc.css.php"); ?>
<!-- jQuery -->
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<!-- jQuery - UI -->
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<!-- jQuery - Form validate -->
<link rel="stylesheet" type="text/css" href="../iic_tools/css/jquery.validate.css" />
<script type="text/javascript" src="../iic_tools/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../iic_tools/js/jquery.validate.additional-methods.js"></script>
<script type="text/javascript" src="../iic_tools/js/jquery.validate.messages_th.js"></script>
<script type="text/javascript" src="../iic_tools/js/jquery.validate.config.js"></script>
<script type="text/javascript">
$(function(){
	$("form").validate();
});
</script>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>เพิ่มผู้จัดจำหน่าย</h1>
		<hr>
		<form method="post" enctype="multipart/form-data">
			<label for="name">ชื่อร้าน<i>*</i></label>
			<input id="name" name="name" type="text" class="required" />
			<label for="address">ที่อยู่<i>*</i></label>
			<textarea name="address" rows="5" id="address" class="required"></textarea>
			<label for="tel">โทรศัพท์<i>*</i></label>
			<input id="tel" name="tel" type="text" class="required integer" maxlength="10" minlength="10" />
			<label for="fax">แฟกซ์</label>
			<input id="fax" name="fax" type="text" />
			<label for="contact">ผู้ประสานงาน<i>*</i></label>
			<input id="contact" name="contact" type="text" class="required" />
			<label for="contact_tel">เบอร์โทรศัพท์ ผู้ประสานงาน<i>*</i></label>
			<input id="contact_tel" name="contact_tel" type="text" class="required integer" maxlength="10" minlength="10" />
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