<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{	
	$message = '';

	// Check is upload file
	if($_FILES['img']['name'] != "" ) 
	{
		// Get lasted id
		$sql_id		= 'SHOW TABLE STATUS LIKE "product"';
		$query_id	= mysql_query($sql_id);
		$data_id	= mysql_fetch_array($query_id);
		$id_lated	= $data_id['Auto_increment'];
		
		// Get file name andm file type
		list($file_name, $file_type) = explode('.', $_FILES['img']['name']);
		
		// Set target folder
		$file_uri = '../upload/product/product_'.$id_lasted.'.'.$file_type;
		
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
	
	/* Start transaction */
	mysql_query('BEGIN');
	
	$sql = 'INSERT INTO product
			SET		
				name 			= "'.$_POST['name'].'",
				description		= "'.$_POST['description'].'",
				unit			= "'.$_POST['unit'].'",
				weight			= "'.$_POST['weight'].'",
				image			= "'.$file_uri.'",
				stock_max 		= "'.$_POST['stock_max'].'",
				manufacture_min	= "'.$_POST['manufacture_min'].'",
				manufacture_max	= "'.$_POST['manufacture_max'].'",
				labour_min		= "'.$_POST['labour_min'].'",
				order_min		= "'.$_POST['order_min'].'",
				unit_per_labour	= "'.$_POST['unit_per_labour'].'",
				price_retail 	= "'.$_POST['price_retail'].'",
				price_wholesale	= "'.$_POST['price_wholesale'].'",
				date_create		= NOW()';
				
	$query = mysql_query($sql) or die(mysql_error()); 
	
	// Get product id
	$sql_id		= 'SELECT MAX(id_product) as id FROM product';
	$query_id	= mysql_query($sql_id);
	$data_id	= mysql_fetch_array($query_id);
	$id_product = $data_id['id'];
	
	foreach($_POST['id'] as $id)
	{
		$sql = 'INSERT INTO product_material
				SET		
					id_product		= '.$id_product.',
					id_material		= '.$id_material.',
					quantity		= '.$_POST['quantity_'.$id_material];
		
		/* Rollback transaction if query error */
		if( ! $query = mysql_query($sql))
		{
			echo 'Error: insert product material';
			echo '<hr />';
			echo mysql_error();
			echo '<hr />';
			echo $sql;
			mysql_query('ROLLBACK');
		}
	}
	
	/* Commit transaction */
	mysql_query('COMMIT');
	
	// Report
	$css 		= '../css/style.css';
	$url_target = 'product.php';
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
<title>เพิ่มสินค้า</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
#tabs-2 input[type=text] { min-width: 50px; margin: 3px 0px; }
#tabs-2 label { margin: 0px; }
</style>
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
	
	// Generate tabs
	$("#tabs").tabs();

});
</script>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>เพิ่มสินค้า</h1>
		<hr>
		<form method="post" enctype="multipart/form-data">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">รายละเอียด</a></li>
					<li><a href="#tabs-2">วัตถุดิบ</a></li>
				</ul>
				<div id="tabs-1">
					<label for="name">ชื่อ <i>*</i></label>
					<input id="name" name="name" type="text" class="required" />
					<label for="description">คำอธิบาย</label>
					<textarea id="description" name="description"></textarea>
					<label for="unit">หน่วย <i>*</i></label>
					<input id="unit" name="unit" type="text" class="required" />
					<label for="weight">หน่วย (กรัม) <i>*</i></label>
					<input id="weight" name="weight" type="text" class="required" />
					<label for="stock_max">สต็อคสูงสุด <i>*</i></label>
					<input id="stock_max" name="stock_max" type="text" class="required integer" />
					<label for="manufacture_min">จำนวนขั้นต่ำในการผลิต <i>*</i></label>
					<input id="manufacture_min" name="manufacture_min" type="text" class="required integer"/>
					<label for="manufacture_max">จำนวนสูงสุดในการผลิต <i>*</i></label>
					<input id="manufacture_max" name="manufacture_max" type="text" class="required integer" />
					<label for="order_min">จำนวนขึ้นต่ำในการสั่งซื้อ <i>*</i></label>
					<input id="order_min" name="order_min" type="text" class="required integer" />
					<label for="labour_min">จำนวนแรงงานขั้นต่ำในการผลิต <i>*</i></label>
					<input id="labour_min" name="labour_min" type="text" class="required integer" />
					<label for="unit_per_labour">จำนวนสินค้าต่อการเพิ่มแรงงาน 1 คน <i>*</i></label>
					<input id="unit_per_labour" name="unit_per_labour" type="text" class="required integer" />
					<label for="price_retail">ราคาขายปลีก <i>*</i></label>
					<input id="price_retail" name="price_retail" type="text" class="required integer" />
					<label for="price_wholesale">ราคาขายส่ง <i>*</i></label>
					<input id="price_wholesale" name="price_wholesale" type="text" class="required integer" />
					<label for="img">รูปภาพ</label>
					<input id="img" name="img" type="file" size="20" />
				</div>
				<div id="tabs-2"> 
					<a href="material_create.php" class="float_r">เพิ่มชนิดวัตถุดิบ</a>
					<p>จำนวนวัตถุดิบที่ต้องใช้ในการผลิตสินค้า 1 หน่วย</p>
					<hr />
					<table>
						<tr>
							<th width="20">&nbsp;</th>
							<th>วัตถุดิบ</th>
							<th>จำนวน</th>
							<th>หน่วย</th>
						</tr>
						<?php 
						$sql = 'SELECT * FROM material';  
						$query = mysql_query($sql);
						
						while($data = mysql_fetch_array($query))
						{
							echo '<tr>
									  <td><input id="material_'.$data['id'].'" name="id[]" type="checkbox" value="'.$data['id'].'" /></td>
									  <td><label for="material_'.$data['id'].'" class="normal">'.$data['name'].'</label></td>
									  <td><input id="quantity_'.$data['id'].'" name="quantity_'.$data['id'].'" type="text" size="4" class="right" /></td>
									  <td>'.$data['unit'].'</td>
								  </tr>';
						}
						?>
					</table>
				</div>
			</div>
			<label class="center">
				<input id="submit" name="submit" type="submit" value="บันทึก" />
			</label>
		</form>
		<hr style="margin-top:25px" />
		<a href="product.php">กลับ</a> </div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>