<?php
require ("../include/session.php");
require ('../include/connect.php');

if(isset($_POST['submit']))
{
	//print_array($_POST);
	//exit();

	$message = '';

	// ----------------------------------------------------------------------------
	// Start transaction
	// ----------------------------------------------------------------------------

	mysql_query("BEGIN");

	// ----------------------------------------------------------------------------
	// Update matirial order
	// ----------------------------------------------------------------------------

	$sql = 'UPDATE material_order 
				SET		
					is_approve	= "1"
					
				WHERE	
					id = '.$_POST['id'];

	// RollBack transaction and show error message when query error
	if(!$query = mysql_query($sql))
	{
		echo 'Update matirial order';
		echo '<hr />';
		echo mysql_error();
		echo '<hr />';
		echo $sql;
		mysql_query("ROLLBACK");
		exit();
	}

	// ----------------------------------------------------------------------------
	// Update material order item
	// ----------------------------------------------------------------------------

	foreach($_POST['quantity_receive'] as $key => $value)
	{
		//echo $key.' => '.$value;
		//exit();

		// Update material order item
		$sql = 'UPDATE material_order_item 
					SET		
						quantity_receive = "'.$value.'"
						
					WHERE	
						id_material_order = '.$_POST['id'].'
						AND
						id_material = '.$_POST['id_material'][$key];

		// RollBack transaction and show error message when query error
		if(!$query = mysql_query($sql))
		{
			echo 'Update material order item';
			echo '<hr />';
			echo mysql_error();
			echo '<hr />';
			echo $sql;
			mysql_query("ROLLBACK");
			exit();
		}

		// Update material stock
		$sql = 'INSERT INTO material_transaction
					 SET 	
					 	id_material		= "'.$_POST['id_material'][$key].'",
						id_supplier		= "'.$_POST['id_supplier'][$key].'",
						amount			= "'.$_POST['total_price'][$key].'",
						quantity		= "'.$value.'",
						date_create		= "'.date('Y-m-d H:i:s').'"';

		// RollBack transaction and show error message when query error
		if(!$query = mysql_query($sql))
		{
			echo 'Update material stock';
			echo '<hr />';
			echo mysql_error();
			echo '<hr />';
			echo $sql;
			mysql_query("ROLLBACK");
			exit();
		}

		$message .= '<li class="green">เพิ่มข้อมูลเสร็จสมบูรณ์</li>';

		// Calculate average_cost_per_unit
		$sql = 'SELECT ROUND(SUM(amount) / SUM(quantity), 2) AS average_cost_per_unit
					 FROM material_transaction
					 WHERE	
					 	id_material = '.$_POST['id_material'][$key].'
						AND quantity > 0';

		// RollBack transaction and show error message when query error
		if(!$query = mysql_query($sql))
		{
			echo 'Calculate average_cost_per_unit';
			echo '<hr />';
			echo mysql_error();
			echo '<hr />';
			echo $sql;
			mysql_query("ROLLBACK");
			exit();
		}

		$data = mysql_fetch_array($query);

		// Update amount, average_cost_per_unit, date_last_update_transaction
		$sql = 'UPDATE material
					 SET	
					 	total					= total + '.$value.',
						average_cost_per_unit	= '.$data['average_cost_per_unit'].',
						date_update_transaction	= "'.date('Y-m-d H:i:s').'"		
					 WHERE	
					 	id = '.$_POST['id_material'][$key];

		// RollBack transaction and show error message when query error
		if(!$query = mysql_query($sql))
		{
			echo 'Update amount, average_cost_per_unit, date_last_update_transaction';
			echo '<hr />';
			echo mysql_error();
			echo '<hr />';
			echo $sql;
			mysql_query("ROLLBACK");
			exit();
		}
	}

	// ----------------------------------------------------------------------------
	// Commit transaction
	// ----------------------------------------------------------------------------

	mysql_query("COMMIT");

	// ----------------------------------------------------------------------------
	// Report
	// ----------------------------------------------------------------------------

	$css = '../css/style.css';
	$url_target = 'material_order.php';
	$title = 'สถานะการทำงาน';
	$message .= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';

	require_once ("../iic_tools/views/iic_report.php");
	exit();

	// ----------------------------------------------------------------------------
	// End
	// ----------------------------------------------------------------------------
}

$sql = 'SELECT * FROM material_order WHERE material_order.id = "'.$_GET['id'].'"';
$query = mysql_query($sql) or die(mysql_error());
$data = mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ใบสั่งซื้อวัตถุดิบ</title>
<?php include ("inc.css.php"); ?>
<style type="text/css">
input[type=text], textarea
{
	width: 50%;
}
form input[type=text]
{
	min-width: 80px;
}
</style>
</head>
<body>
<div id="container">
	<?php
	include ("inc.header.php");
	?>
	<div id="content">
		<a href="material_order_print.php?id=<?php echo $_GET['id'] ?>" class="float_r">พิมพ์ใบสั่งซื้อวัตถุดิบ</a>
		<h1>ตรวจรับใบสั่งซื้อวัตถุดิบ</h1>
		<hr>
		<p class="float_r">
			วันที่: <?php echo change_date_format($data['date_create']);?>
		</p>
		<form method="post" action="material_order_recive.php">
			<label>รายการ</label>
			<hr />
			<table width="100%">
				<thead>
					<tr>
						<th scope="col" width="30">ลำดับ</th>
						<th scope="col">วัตถุดิบ</th>
						<th scope="col" width="50">สั่งซื้อ</th>
						<th scope="col" width="50">หน่วย</th>
						<th scope="col" width="50">ตรวจรับ</th>
						<th scope="col" width="50">หน่วย</th>
						<th scope="col" width="80">ราคารวม</th>
						<th scope="col" width="80">ราคาต่อหน่วย</th>
						<th scope="col">ผู้จัดจำหน่าย</th>
					</tr>
				</thead>
				<tbody>
					<!-- @formatter:off -->
					<?php
					$sql = 'SELECT	
								material_order_item.quantity_order AS quantity, 
								material.id AS id_material,
								material.name AS material,
								material.unit AS unit, 
								supplier.id AS id_supplier,
								supplier.name AS supplier
								
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
						echo '<tr>
							<td class="center">
								'.$loop.'
								<input name="id_material[]" type="hidden" value="'.$data['id_material'].'" />
								<input name="id_supplier[]" type="hidden" value="'.$data['id_supplier'].'" />
							</td>
							<td>
								'.$data['material'].'
							</td>
							<td class="right">
								'.add_comma($data['quantity']).'
							</td>
							<td>'.$data['unit'].'</td>
							<td class="right">
								<input type="text" name="quantity_receive[]" id="quantity_receive_'.$loop.'" value="'.$data['quantity'].'" class="right" />
							</td>
							<td>'.$data['unit'].'</td>
							<td class="right">
								<input type="text" name="total_price[]" id="total_price_'.$loop.'" rel="'.$loop.'" value="0" class="right" />
							</td>
							<td class="right">
								<input type="text" name="price_per_unit[]" id="price_per_unit_'.$loop.'" value="0" readonly="readonly" class="right" />
							</td>
							<td>
								'.$data['supplier'].'
							</td>
						</tr>';

						$loop++;
					}
					?>
					<!-- @formatter:on -->
				</tbody>
			</table>
			<input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" />
			<input type="submit" name="submit" value="บันทึก" />
		</form>
		<hr style="margin-top:25px" />
		<a href="material_order.php">กลับ</a>
	</div>
	<?php include ("inc.footer.php"); ?>
</div>
</body>
</html>