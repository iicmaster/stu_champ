<?php 
require("../include/session.php");
require('../include/connect.php');

$sql = 'SELECT *
		FROM product_transaction 
		WHERE stock_code = "'.$_GET['stock_code'].'"';
$query = mysql_query($sql) or die(mysql_error());
$data = mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ข้อมูลคลังสินค้า</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
#product_description th
{
	padding: 8px;
	text-align: right;
	width: 100px;
	white-space: nowrap;
}
</style>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>ข้อมูลคลังสินค้า</h1>
		<hr>
		<table id="product_description" class="text_12">
			<tr>
				<th>รหัสสต็อค</th>
				<td><?php echo $data['stock_code'] ?></td>
			</tr>
			<?php 
			$sql = 'SELECT 
						*, 
						(
							SELECT SUM(quantity) 
							FROM product_transaction 
							WHERE id_product = t1.id_product
						) AS remain
						
					FROM product_transaction as t1
					
					JOIN product
					ON t1.id_product = product.id
					
					WHERE
						stock_code = "'.$data['stock_code'].'"
						
					GROUP BY id_product';
					
			$result = mysql_query($sql) or die(mysql_error());
			$total_product_type = mysql_num_rows($result);
			while($data = mysql_fetch_assoc($result)):
			?>
			<tr>
				<th><?php echo $data['name'] ?></th>
				<td><?php echo $data['remain'] ?> <?php echo $data['unit'] ?></td>
			</tr>
			<?php endwhile  ?>
		</table>
		<h2>รายการความเคลื่อนไหวของสินค้า</h2>
		<hr />
		<table width="100%">
			<tr>
				<th rowspan="2" width="100">วันที่-เวลา</th>
				<th rowspan="2">สินค้า</th>
				<th rowspan="2">คำอธิบาย</th>
				<th colspan="2">จำนวน</th>
			</tr>
			<tr>
				<th width="50">เพิ่ม</th>
				<th width="50">ลด</th>
			</tr>
			<?php 
			$sql = 'SELECT 
						product_transaction.date_create, 
						product_transaction.stock_code,
						name, 
						product_transaction.description, 
						quantity 
						
					FROM product_transaction 
					
					LEFT JOIN product
					ON product_transaction.id_product = product.id
					
					WHERE 
						product_transaction.stock_code = "'.$_GET['stock_code'].'"
						
					ORDER BY 
						product_transaction.date_create DESC';
					
			$query = mysql_query($sql) or die(mysql_error());
			$query_rows = mysql_num_rows($query);
			
			if($query_rows > 0)
			{
				while($data = mysql_fetch_array($query))
				{					
					if($data['quantity'] > 0)
					{
						$deposit   = add_comma($data['quantity']);
						$withdraw  = '';
					}
					else
					{
						$deposit   = '';
						$withdraw  = add_comma(abs($data['quantity']));
					}

					echo '<tr>
							  <td class="center nowarp">'.change_date_time_format($data['date_create']).'</td>
							  <td>'.$data['name'].'</td>
							  <td>'.$data['description'].'</td>
							  <td class="right">'.$deposit.'</td>
							  <td class="right">'.$withdraw.'</td>
						  </tr>';
				}
			}
			else
			{
				echo '<tr><td colspan="7" class="center">ไม่มีข้อมูล</td></tr>';
			}
			?>
		</table>
		<hr />
		<a href="product.php">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>