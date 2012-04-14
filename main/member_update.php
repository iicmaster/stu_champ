<?php
require('../include/connect.php');	

if(isset($_POST['submit']))
{
	$message = '';

	// Check is upload file
	if($_FILES['image']['name'] != "" ) 
	{		
		// Get file name andm file type
		list($file_name, $file_type) = explode('.', $_FILES['image']['name']);
		
		// Set target folder
		$file_uri = '../upload/member/member_'.$_GET['id'].'.'.$file_type;
		
		// Check is upload complete
		if(is_uploaded_file($_FILES['image']['tmp_name'])) 
		{ 
			// Check is move file complete
			if(move_uploaded_file($_FILES['image']['tmp_name'], $file_uri)) 
			{
				$message .= '<li class="green">อัพโหลดไฟล์สำเร็จ</li>';
			}	
			else 
			{
				$message .= '<li class="red">อัพโหลดไฟล์ล้มเหลว</li>';	
			}
		}
	}
	else if(isset($_POST['delete_image'])) 
	{
		@unlink($_POST['old_uri_image']);
		$file_uri = '';
		$message .= '<li class="green">ลบไฟล์สำเร็จ</li>';
	} 
	else 
	{
		$file_uri = $_POST['old_uri_image'];
	}
	
	$sql = 'UPDATE	member 
			SET		
				name		= "'.$_POST['name'].'",
				nickname	= "'.$_POST['nickname'].'",
				address		= "'.$_POST['address'].'",
				tel			= "'.$_POST['tel'].'",
				image		= "'.$file_uri.'"
				
			WHERE	
				id			= '.$_POST['id'];
	
	$query = mysql_query($sql);
	
	// Set report message
	if($query)
	{	
		$message .= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	}
	else
	{
		$message .= '<li class="red">เกิดข้อผิดพลาด: บันทึกข้อมูลล้มเหลว</li>';
	}

	// Report
	$css 		= '../css/style.css';
	$url_target	= 'member.php';
	$title		= 'สถานะการทำงาน';
	
	require_once("../iic_tools/views/iic_report.php");
	exit();
}

$sql	= 'SELECT * FROM member WHERE id = '.$_GET['id'];
$query	= mysql_query($sql);
$data	= mysql_fetch_array($query);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แก้ไขข้อมูลสมาชิก</title>
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
		<h1>แก้ไขข้อมูลสมาชิก</h1>
		<hr>
		<form method="post" enctype="multipart/form-data">
			<label for="name">ชื่อ-นามสกุล<i>*</i></label>
			<input id="name" name="name" type="text" value="<?php echo $data['name'] ?>" class="required" />
			<label for="nickname">ชื่อเล่น</label>
			<input id="nickname" name="nickname" type="text" value="<?php echo $data['nickname'] ?>" />
			<label for="address">ที่อยู่<i>*</i></label>
			<textarea name="address" rows="5" id="address" class="required"><?php echo $data['address'] ?></textarea>
			<label for="tel">เบอร์โทรศัพท์<i>*</i></label>
			<input id="tel" name="tel" type="text" value="<?php echo $data['tel'] ?>" class="required integer" maxlength="10" />
			<label for="image">รูปภาพ</label>
			<input id="image" name="image" type="file" />
			<?php 
				if($data['image'] != "") 
				{
					echo get_crud_image_preview($data['image'], 'image');
				}
			?>
			<input name="old_uri_image" type="hidden" value="<?php echo $data['image'] ?>" />
			<input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" />
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