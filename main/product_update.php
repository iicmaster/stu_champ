<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{	
	// Check is upload file
	if($_FILES['img']['name'] != "" ) 
	{
		// Get lasted id
		$sql_id = 'SHOW TABLE STATUS LIKE "product"';
		$query_id = mysql_query($sql_id);
		$data_id = mysql_fetch_array($query_id);
		$id_lasted = $data_id['Auto_increment'];
		
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
	else if($_POST['delete_image'] == 1) 
	{
		unlink($_POST['old_uri_image']);
		$file_uri = '';
		$message .= '<li class="green">ลบไฟล์สำเร็จ</li>';
	} 
	else 
	{
		$file_uri = $_POST['old_uri_image'];
	}
	
	/* Start transaction */
	mysql_query('BEGIN');
	
	$sql = 'UPDATE product
			SET		name 			= "'.$_POST['name'].'",
					description		= "'.$_POST['description'].'",
					unit			= "'.$_POST['unit'].'",
					image			= "'.$file_uri.'",
					stock_max 		= "'.$_POST['stock_max'].'",
					manufacture_min	= "'.$_POST['manufacture_min'].'",
					manufacture_max	= "'.$_POST['manufacture_max'].'",
					labour_min		= "'.$_POST['labour_min'].'",
					unit_per_labour	= "'.$_POST['unit_per_labour'].'",
					price_retail 	= "'.$_POST['price_retail'].'",
					price_wholesale	= "'.$_POST['price_wholesale'].'",
					date_create		= NOW()
			WHERE id_product = '.$_POST['id'];
	$query = mysql_query($sql);
	
	// Set report message
	if($query)
	{	
		// Delete old data
		$sql_delete = 'DELETE FROM product_material WHERE id_product = '.$_POST['id'];
		$query_delete = mysql_query($sql_delete);
		
		foreach($_POST['id_material'] as $id_material)
		{
			$sql = 'INSERT INTO product_material
					SET		id_product		= '.$_POST['id'].',
							id_material		= '.$id_material.',
							quantity		= '.$_POST['quantity_'.$id_material];
			$query = mysql_query($sql);
			
			if(!$query)
			{
				/* Rollback transaction */
				mysql_query('ROLLBACK');
				
				$message .= '<li class="red">เกิดข้อผิดพลาด: เปรับปรุงยอดวัตถุดิบล้มเหลว</li>';
			}
		}
		
		/* Commit transaction */
		mysql_query('COMMIT');
		
		$message .= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	}
	else
	{
		$message .= '<li class="red">เกิดข้อผิดพลาด: บันทึกข้อมูลล้มเหลว</li>';
	}

	// Report
	$url_target = 'product.php';
	$title = 'สถานะการทำงาน';
	
	require_once("../iic_tools/views/iic_report.php");
	exit();
}

$sql = 'SELECT * FROM product WHERE id_product = '.$_GET['id'];
$query = mysql_query($sql);
$data = mysql_fetch_array($query);
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
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script  type="text/javascript"src="../js/jquery-ui-1.8.11.min.js"></script>
<script type="text/javascript">
$(function(){
	
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
					<label for="name">ชื่อ</label>
					<input id="name" name="name" type="text" value="<?php echo $data['name'] ?>" />
					<label for="description">คำอธิบาย</label>
					<textarea id="description" name="description"><?php echo $data['description'] ?></textarea>
					<label for="unit">หน่วย</label>
					<input id="unit" name="unit" type="text" value="<?php echo $data['unit'] ?>" />
					<label for="stock_max">สต็อคสูงสุด</label>
					<input id="stock_max" name="stock_max" type="text" value="<?php echo $data['stock_max'] ?>" />
					<label for="manufacture_min">จำนวนขั้นต่ำในการผลิต</label>
					<input id="manufacture_min" name="manufacture_min" type="text" value="<?php echo $data['manufacture_min'] ?>" />
					<label for="manufacture_max">จำนวนสูงสุดในการผลิต</label>
					<input id="manufacture_max" name="manufacture_max" type="text" value="<?php echo $data['manufacture_max'] ?>" />
					<label for="labour_min">จำนวนแรงงานขั้นต่ำในการผลิต</label>
					<input id="labour_min" name="labour_min" type="text" value="<?php echo $data['labour_min'] ?>" />
					<label for="unit_per_labour">จำนวนสินค้าต่อการเพิ่มแรงงาน 1 คน</label>
					<input id="unit_per_labour" name="unit_per_labour" type="text" value="<?php echo $data['unit_per_labour'] ?>" />
					<label for="price_retail">ราคาขายปลีก</label>
					<input id="price_retail" name="price_retail" type="text" value="<?php echo $data['price_retail'] ?>" />
					<label for="price_wholesale">ราคาขายส่ง</label>
					<input id="price_wholesale" name="price_wholesale" type="text" value="<?php echo $data['price_wholesale'] ?>" />
					<label for="img">รูปภาพ</label>
					<input id="img" name="img" type="file" size="20" />
					<?php 
						if($data['image'] != "") 
						{
							echo get_crud_image_preview($data['image'], 'image');
						}
					?>
					<input name="old_uri_image" type="hidden" value="<?php echo $data['image'] ?>" />
				</div>
				<div id="tabs-2"> 
					<a href="material_create.php" class="float_r">เพิ่มชนิดวัตถุดิบ</a>
					<p>จำนวนวัตถุดิบที่ต้องใช้ในการผลิตสินค้า 1 หน่วย</p>
					<hr />
					<table>
						<tr>
							<th>&nbsp;</th>
							<th>วัตถุดิบ</th>
							<th>จำนวน</th>
							<th>หน่วย</th>
						</tr>
						<?php 
						
						$sql = 'SELECT * FROM material';  
						$query = mysql_query($sql);
						
						while($data = mysql_fetch_array($query))
						{
							$sql_product_material = 'SELECT * 
													 FROM product_material 
													 WHERE	id_product = '.$_GET['id'].'
													 AND	id_material = '.$data['id_material'].'';
							$query_product_material = mysql_query($sql_product_material);
							$query_product_material_rows = mysql_num_rows($query_product_material);
							
							if($query_product_material_rows > 0)
							{
								$data_product_material = mysql_fetch_array($query_product_material);
								$checked = 'checked="checked" ';
							}
							else
							{
								$checked = '';
								$data_product_material = '';
							}
							
							echo '<tr>
									  <td><input id="material_'.$data['id_material'].'" name="id_material[]" type="checkbox" value="'.$data['id_material'].'" '.$checked.'/></td>
									  <td><label for="material_'.$data['id_material'].'" class="normal">'.$data['name'].'</label></td>
									  <td><input id="quantity_'.$data['id_material'].'" name="quantity_'.$data['id_material'].'" type="text" size="4" value="'.$data_product_material['quantity'].'" class="right" /></td>
									  <td>'.$data['unit'].'</td>
								  </tr>';
						}
						?>
					</table>
				</div>
			</div>
			<input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" />
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