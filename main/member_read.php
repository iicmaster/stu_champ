<?php
require('../include/connect.php');	

$sql	= 'SELECT * FROM member WHERE id = '.$_GET['id'];
$query	= mysql_query($sql) or die(mysql_error());
$data	= mysql_fetch_assoc($query);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ข้อมูลสมาชิก</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
#profile_image { float: right; }
</style>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<a class="button float_r" href="member_update.php?id=<?php echo $_GET['id'] ?>">แก้ไข</a>
		<h1>แก้ไขข้อมูลสมาชิก</h1>
		<hr>
		<div id="profile_image">
			<?php 
				if($data['image'] != "") 
				{
					echo get_image_preview($data['image']);
				}
			?>
		</div>
		<form method="post" enctype="multipart/form-data">
			<label for="name">ชื่อ-นามสกุล</label>
			<input name="name" type="text" id="name" value="<?php echo $data['name'] ?>" readonly="readonly" />
			<label for="nickname">ชื่อเล่น</label>
			<input name="nickname" type="text" id="nickname" value="<?php echo $data['nickname'] ?>" readonly="readonly" />
			<label for="address">ที่อยู่</label>
			<textarea name="address" rows="5" readonly="readonly" id="address"><?php echo $data['address'] ?></textarea>
			<label for="tel">เบอร์โทรศัพท์</label>
			<input name="tel" type="text" id="tel" value="<?php echo $data['tel'] ?>" readonly="readonly" />
			<label>  </label>
		</form>
		<hr style="margin-top:25px" />
		<a href="member.php">กลับ</a> </div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>