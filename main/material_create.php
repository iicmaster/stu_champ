<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{	
	// --------------------------------------------------------------------------------
	// Start transaction
	// --------------------------------------------------------------------------------
	
	mysql_query("BEGIN");	
	
	// --------------------------------------------------------------------------------
	// Insert material data
	// --------------------------------------------------------------------------------
	
	$sql = 'INSERT INTO material
			SET		
				name		= "'.$_POST['name'].'",
				description	= "'.$_POST['description'].'",
				stock_min	= "'.$_POST['stock_min'].'",
				stock_max	= "'.$_POST['stock_max'].'",
				unit 		= "'.$_POST['unit'].'",
				date_create	= NOW()';
					
	// RollBack transaction and show error message when query error						
	if(! $query = mysql_query($sql))
	{
		echo 'Insert material data';
		echo '<hr />';
		echo mysql_error();
		echo '<hr />';
		echo $sql;
		mysql_query("ROLLBACK");
		exit();
	}
		
	// --------------------------------------------------------------------------------
	// Get last id
	// --------------------------------------------------------------------------------
	
	$sql = 'SELECT MAX(id) AS id FROM material';
	
	// RollBack transaction and show error message when query error						
	if(! $query = mysql_query($sql))
	{
		echo 'Get last id';
		echo '<hr />';
		echo mysql_error();
		echo '<hr />';
		echo $sql;
		mysql_query("ROLLBACK");
		exit();
	}
	
	$data = mysql_fetch_array($query);
	$id_material = $data['id'];
	
	// --------------------------------------------------------------------------------
	// Insert material's supplier
	// --------------------------------------------------------------------------------
	
	if(count($_POST["id_supplier"]) > 0)
	{
		foreach ($_POST["id_supplier"] as $id_supplier) 
		{
			$query = 'INSERT INTO material_supplier
					  SET
					      id_material	= "'.$id_material.'",
					      id_supplier	= "'.$id_supplier.'"';
						  
			// RollBack transaction and show error message when query error						
			if(! $result = mysql_query($query))
			{
				echo "Insert material's supplier";
				echo '<hr />';
				echo mysql_error();
				echo '<hr />';
				echo $query;
				mysql_query("ROLLBACK");
				exit();
			}
		}
	}
	
	// --------------------------------------------------------------------------------
	// Commit transaction
	// --------------------------------------------------------------------------------
	
	mysql_query("COMMIT");
	
	// --------------------------------------------------------------------------------
	// Report
	// --------------------------------------------------------------------------------
	
	$css 		= '../css/style.css';
	$url_target = 'material.php';
	$title		= 'สถานะการทำงาน';
	$message	= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	
	require_once("../iic_tools/views/iic_report.php");
	exit();
	
	// --------------------------------------------------------------------------------
	// End
	// --------------------------------------------------------------------------------
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>เพื่มชนิดวัตถุดิบ</title>
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
<!-- jQuery - Select List -->
<link rel="stylesheet" type="text/css" href="../iic_tools/css/jquery.selectlist.css" />
<script type="text/javascript" src="../iic_tools/js/jquery.selectlist.js"></script>
<script type="text/javascript">
$(function(){
	$("form").validate();
	$("#id_supplier").selectList();
});
</script>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>เพื่มชนิดวัตถุดิบ</h1>
		<hr>
		<form method="post" enctype="multipart/form-data">
			<label for="name">ผู้จัดจำหน่าย <i>*</i></label>
			<select id="id_supplier" name="id_supplier[]" class="required" multiple="" title="โปรดระบุ">
				<?php
				$query = "SELECT * FROM supplier";
				$result = mysql_query($query) or die(mysql_error());
				while($data = mysql_fetch_assoc($result)):
				?>
				<option value="<?php echo $data["id"] ?>"><?php echo $data["name"] ?></option>
				<?php endwhile; ?>
			</select>
			<label for="name">วัตถุดิบ <i>*</i></label>
			<input id="name" name="name" type="text" class="required" />
			<label for="description">คำอธิบาย</label>
			<textarea id="description" name="description"></textarea>
			<label for="stock_min">จำนวนวัตถุดิบคงคลังขั้นต่ำ <i>*</i></label>
			<input id="stock_min" name="stock_min" type="text" value="" class="required integer" />
			<label for="stock_max">จำนวนวัตถุดิบคงคลังสูงสุด <i>*</i></label>
			<input id="stock_max" name="stock_max" type="text" value="" class="required integer" />
			<label for="unit">หน่วย <i>*</i></label>
			<input id="unit" name="unit" type="text" class="required" />
			<label>
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