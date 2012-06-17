<?php 
require("../include/session.php");
require('../include/connect.php');

$sql = 'SELECT *
		FROM material 
		WHERE id = '.$_GET['id'];
$query = mysql_query($sql) or die(mysql_error());
$data = mysql_fetch_array($query);

$total_stock_remain = 0;
$total_stock_value = 0;

$sql = 'SELECT
			stock_code,
			SUM(quantity) AS "material_remain",
			ROUND(SUM(amount) / (SELECT SUM(quantity) 
									FROM material_transaction 
									WHERE 
										quantity > 0 
										AND stock_code = t1.stock_code
										AND id_material = t1.id_material
								), 2) AS "average_price_per_unit"			
		FROM material_transaction AS t1
		WHERE id_material = '.$_GET['id'].'
		GROUP BY stock_code';
		
$query = mysql_query($sql) or die(mysql_error());

while($data_cost = mysql_fetch_array($query))
{
	$total_stock_remain += $data_cost['material_remain'];
	$total_stock_value += $data_cost['material_remain'] * $data_cost['average_price_per_unit'];
}

$average_cost_per_unit = round($total_stock_value/$total_stock_remain, 2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แก้ไขข้อมูลวัตถุดิบ</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
#material_description th
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
		<h1>ข้อมูลวัตถุดิบ</h1>
		<hr>
		<table id="material_description" class="text_12">
			<tr>
				<th>วัตถุดิบ</th>
				<td><?php echo $data['name'] ?></td>
			</tr>
			<tr>
				<th>คำอธิบาย</th>
				<td><?php echo $data['description'] ?></td>
			</tr>
			<tr>
				<th>จำนวนคงเหลือ</th>
				<td><?php echo add_comma($data['total']) ?> <?php echo $data['unit'] ?></td>
			</tr>
			<tr>
				<th>ราคาเฉลี่ยต่อหน่วย</th>
				<td><?php echo add_comma($average_cost_per_unit) ?> บาท/<?php echo $data['unit'] ?></td>
			</tr>
		</table>
		<a href="material_transaction_view.php?id=<?php echo $_GET['id'] ?>" class="float_r">ดูทั้งหมด</a>
		<h2>รายการความเคลื่อนไหวของวัตถุดิบ</h2>
		<hr />
		<table width="100%">
			<tr>
				<th rowspan="2" width="100">วันที่-เวลา</th>
				<th rowspan="2" width="80">รหัสสต็อค</th>
				<th rowspan="2" width="150">ผู้จัดจำหน่าย</th>
				<th rowspan="2">คำอธิบาย</th>
				<th rowspan="2" width="50">ราคาซื้อ</th>
				<th rowspan="2" width="50">ราคาต่อหน่วย</th>
				<th colspan="2">จำนวนวัตถุดิบ</th>
			</tr>
			<tr>
				<th width="50">เพิ่ม</th>
				<th width="50">ลด</th>
			</tr>
			<?php 
			$sql = 'SELECT 
						material_transaction.date_create, 
						material_transaction.stock_code,
						name, 
						description, 
						amount, 
						quantity 
						
					FROM material_transaction 
					
					LEFT JOIN supplier
					ON material_transaction.id_supplier = supplier.id
					
					WHERE 
						material_transaction.id_material = '.$_GET['id'].'
						
					ORDER BY 
						material_transaction.date_create DESC
						
					LIMIT 10';
					
			$query = mysql_query($sql) or die(mysql_error());
			$query_rows = mysql_num_rows($query);
			
			if($query_rows > 0)
			{
				while($data = mysql_fetch_array($query))
				{
					$cost_per_unit = add_comma(round($data['amount']/abs($data['quantity']), 2));
					
					if($data['quantity'] > 0)
					{
						$deposit   = add_comma($data['quantity']);
						$withdraw  = '';
						$data['amount'] = add_comma($data['amount']);
					}
					else
					{
						$deposit   = '';
						$withdraw  = add_comma(abs($data['quantity']));
						
						$data['name'] = '-';
						$data['amount'] = '-';
						$cost_per_unit = '-';
					}

					echo '<tr>
							  <td class="center nowarp">'.change_date_time_format($data['date_create']).'</td>
							  <td class="center nowarp">'.$data['stock_code'].'</td>
							  <td>'.$data['name'].'</td>
							  <td>'.$data['description'].'</td>
							  <td class="right">'.$data['amount'].'</td>
							  <td class="right">'.$cost_per_unit.'</td>
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
		<a href="material.php">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>