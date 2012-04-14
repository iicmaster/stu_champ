<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{	
	$file_uri	= '';
	$message	= '';

	// Check is upload file
	if($_FILES['img']['name'] != '') 
	{
		// Get lasted id
		$sql_id		= 'SHOW TABLE STATUS LIKE "member"';
		$query_id	= mysql_query($sql_id);
		$data_id	= mysql_fetch_array($query_id);
		$id_lasted	= $data_id['Auto_increment'];
		
		// Get file name andm file type
		list($file_name, $file_type) = explode('.', $_FILES['img']['name']);
		
		// Set target folder
		$file_uri = '../upload/member/member_'.$id_lasted.'.'.$file_type;
		
		// Check is upload complete
		if(is_uploaded_file($_FILES['img']['tmp_name'])) 
		{ 
			// Check is move file complete
			if(move_uploaded_file($_FILES['img']['tmp_name'], $file_uri)) 
			{
				$message .= '<li class="green">อัพโหลดไฟล์สำเร็จ</li>';
			}	
			else 
			{
				$message .= '<li class="red">อัพโหลดไฟล์ล้มเหลว</li>';	
			}
		}
	}
	
	$sql = 'INSERT INTO member
			SET		name 		= "'.$_POST['name'].'",
					nickname	= "'.$_POST['nickname'].'",
					address		= "'.$_POST['address'].'",
					tel 		= "'.$_POST['tel'].'",
					image		= "'.$file_uri.'",
					date_create	= NOW()';
					
	$query = mysql_query($sql);
	
	// Set report message
	if($query)
	{	
	}
	else
	{
		$message .= '<li class="red">เกิดข้อผิดพลาด: บันทึกข้อมูลล้มเหลว</li>';
	}

	// Report
	$css 		= '../css/style.css';
	$url_target = 'member.php';
	$title		= 'สถานะการทำงาน';
	$message 	.= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	
	require_once("../iic_tools/views/iic_report.php");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>สมัครสมาชิก</title>
<?php include("inc.css.php"); ?>
<!-- jQuery -->
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
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
		<h1>สมัครสมาชิก</h1>
		<hr>
		<form method="post" enctype="multipart/form-data">
			<label for="name">ชื่อ-นามสกุล<i>*</i></label>
			<input id="name" name="name" type="text" class="required" />
			<label for="nickname">ชื่อเล่น</label>
			<input id="nickname" name="nickname" type="text" />
			<label for="address">ที่อยู่<i>*</i></label>
			<textarea name="address" rows="5" id="address" class="required"></textarea>
			<label for="tel">เบอร์โทรศัพท์<i>*</i></label>
			<input id="tel" name="tel" type="text" class="required integer" maxlength="10" />
			<label for="img">รูปภาพ</label>
			<input id="img" name="img" type="file" size="20" />
			<label>
				<input id="submit" name="submit" type="submit" value="บันทึก" />
			</label>
		</form>
		<hr style="margin-top:25px" />
		<a href="member.php">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>