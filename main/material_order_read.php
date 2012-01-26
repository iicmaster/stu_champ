<?php 
require("../include/session.php");
require('../include/connect.php');

$sql = 'SELECT	
			material_order.*
				
		FROM material_order 
		
		WHERE material_order.id = "'.$_GET['id'].'"';

$query	= mysql_query($sql) or die(mysql_error());
$data	= mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ใบสั่งซื้อวัตถุดิบ</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
input[type=text], textarea
{
	width: 50%;
}
</style>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<a href="material_order_print.php?id=<?php echo $_GET['id'] ?>" class="float_r">พิมพ์ใบสั่งซื้อวัตถุดิบ</a>
		<h1>ใบสั่งซื้อวัตถุดิบ</h1>
		<hr>
		<p class="float_r">วันที่: <?php echo change_date_format($data['date_create']); ?></p>
<<<<<<< HEAD
		<label for="description">คำอธิบาย</label>
		<textarea id="description" name="description" rows="3" readonly="readonly"><?php echo $data['description'] ?></textarea>
=======
>>>>>>> 736daa5b8a0bdca7c8d1c03000a54c5f6e3d5959
		<label>รายการ</label>
		<hr />
		<table width="100%">
		
			<thead>
				<tr>
					<th scope="col" width="30">ลำดับ</th>
					<th scope="col">วัตถุดิบ</th>
					<th scope="col" width="90">จำนวนที่สั่งซื้อ</th>
					<th scope="col" width="50">หน่วย</th>
					<th scope="col" width="90">จำนวนที่ตรวจรับ</th>
					<th scope="col" width="50">หน่วย</th>
					<th scope="col" width="250">ผู้จัดจำหน่าย</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					
					$sql = 'SELECT	
								material_order_item.quantity_order as quantity, 
								material_order_item.quantity_receive as quantity_receive, 
								material.name as material,
								material.unit as unit, 
								supplier.name as supplier
									
							FROM material_order_item 
							
							LEFT JOIN material
							ON material_order_item.id_material = material.id
							
							LEFT JOIN supplier
							ON material_order_item.id_supplier = supplier.id
							
							WHERE 
								material_order_item.id_material_order = "'.$data['id'].'"';
							
					$query = mysql_query($sql) or die(mysql_error());
					$loop = 1;
						
					while($data = mysql_fetch_array($query))
					{
						$quantity_receive = ($data['quantity_receive'] != '') ? add_comma($data['quantity_receive']) : '<span class="block center red">ยังไม่ได้ตรวจรับ</span>';
						echo '<tr>
								<td class="center">'.$loop++.'</td>
								<td>'.$data['material'].'</td>
								<td class="right">'.add_comma($data['quantity']).'</td>
								<td>'.$data['unit'].'</td>
								<td class="right">'.$quantity_receive.'</td>
								<td>'.$data['unit'].'</td>
								<td>'.$data['supplier'].'</td>
							</tr>';
					}
					?>
			</tbody>
		</table>
		<hr style="margin-top:25px" />
		<a href="material_order.php">กลับ</a> </div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>