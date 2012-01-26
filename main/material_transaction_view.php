<?php 
require("../include/session.php");
require('../include/connect.php');

$sql = 'SELECT *
		FROM material 
		WHERE id = '.$_GET['id'];
		
$query	= mysql_query($sql) or die(mysql_error()); 
$data	= mysql_fetch_array($query);
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
		<h1>รายการความเคลื่อนไหวของวัตถุดิบ</h1>
		<hr />
		<table width="100%">
			<tr>
				<th rowspan="2" width="110">วันที่-เวลา</th>
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
						name, 
						description, 
						amount, 
						quantity
						 
					FROM material_transaction 
					
					LEFT JOIN supplier
					ON material_transaction.id_supplier = supplier.id
					
					WHERE 
						id_material = '.$_GET['id'].'
					
					ORDER BY 
						material_transaction.date_create DESC';
			
			$query 		= mysql_query($sql) or die(mysql_error()); 
			$query_rows = mysql_num_rows($query);
			
			if($query_rows > 0)
			{
				while($data = mysql_fetch_array($query))
				{
					$cost_per_unit = add_comma(round($data['amount']/abs($data['quantity'])));
					
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
							  <td class="center">'.change_date_time_format($data['date_create']).'</td>
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
				echo '<tr><td colspan="4" class="center">ไม่มีข้อมูล</td></tr>';
			}
			?>
		</table>
		<hr />
		<a href="material_view.php?id=<?php echo $_GET['id'] ?>">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>