<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{	
	$sql = 'INSERT INTO material
			SET		
				name		= "'.$_POST['name'].'",
				description	= "'.$_POST['description'].'",
				stock_min	= "'.$_POST['stock_min'].'",
				stock_max	= "'.$_POST['stock_max'].'",
				unit 		= "'.$_POST['unit'].'",
				date_create	= NOW()';
					
	$query = mysql_query($sql) or die(mysql_error());

	// Report
	$css 		= '../css/style.css';
	$url_target = 'material.php';
	$title		= 'สถานะการทำงาน';
	$message	= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	
	require_once("../iic_tools/views/iic_report.php");
	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>เพื่มชนิดวัตถุดิบ</title>
<?php include("inc.css.php"); ?>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>เพื่มชนิดวัตถุดิบ</h1>
		<hr>
		<form method="post" enctype="multipart/form-data">
			<label for="name">วัตถุดิบ</label>
			<input id="name" name="name" type="text" />
			<label for="description">คำอธิบาย</label>
			<textarea id="description" name="description"></textarea>
			<label for="stock_min">จำนวนวัตถุดิบคงคลังขั้นต่ำ</label>
			<input id="stock_min" name="stock_min" type="text" value="" />
			<label for="stock_max">จำนวนวัตถุดิบคงคลังสูงสุด</label>
			<input id="stock_max" name="stock_max" type="text" value="" />
			<label for="unit">หน่วย</label>
			<input id="unit" name="unit" type="text" />
			<label class="center">
				<input id="submit" name="submit" type="submit" value="บันทึก" />
			</label>
		</form>
		<hr style="margin-top:25px" />
		<a href="material.php">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>