<?php 
require_once("../include/session.php"); 
include_once('../include/connect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบจัดการผลิตและจำหน่ายสละลอยแก้ว</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
h2 { margin-top: 15px; }

form { width: 100%; }
</style>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<script>
$(function() {
	$("#tabs").tabs();
	
	$('#tabs-2 input:submit').click(function(){
		if($("#tabs-2 input:checked").length == 0)
		{
			alert('กรุณาเลือกวิตถุดิบอย่างน้อย 1 ชนิด');
			
			return false;
		}
	});
});
</script>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
	<h1>รายงานกำไร-ขาดทุน</h1>
    <hr/>
    <hr/>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>
