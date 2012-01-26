<?php 
require("../include/session.php");
require('../include/connect.php');

if($_POST['submit'])
{ 
	// Check action
	if($_POST['action'] == 0)
	{
		$quantity = 0 + $_POST['quantity'];
		$amount = $_POST['amount'];
	}
	else
	{
		$quantity = 0 - $_POST['quantity'];
		$amount = 0;
	}
	
	// Calculate cost per unit
	$cost_per_unit = round($_POST['amount'] / $_POST['quantity']);
	
	/* Start transaction */
	mysql_query('BEGIN');

	// Step 1 - Add transaction
	$sql = 	'INSERT INTO material_transaction
			 SET 	id_material		= "'.$_POST['id'].'",
					id_supplier		= '.$_POST['id_supplier'].',
					amount			= '.$amount.',
					quantity		= "'.$quantity.'",
					description		= "'.$_POST['description'].'",
					date_create		= NOW()';
	$query = mysql_query($sql);
	
	if($query)
	{
		$message .= '<li class="green">เพิ่มข้อมูลเสร็จสมบูรณ์</li>';
		
		// Calculate average_cost_per_unit
		$sql =	'SELECT SUM(amount)/SUM(quantity) as average_cost_per_unit
				 FROM material_transaction
				 WHERE	id_material = '.$_POST['id'].'
				 		AND quantity > 0';
		$query = mysql_query($sql);
		$data  = mysql_fetch_array($query);
	
		// Step 2 - Update amount, average_cost_per_unit, date_last_update_transaction
		$sql =	'UPDATE material
				 SET	total							= total + '.$quantity.',
				 		average_cost_per_unit			= '.$data['average_cost_per_unit'].',
						date_last_update_transaction	= now()				 
				 WHERE	id_material						= '.$_POST['id'];
		$query = mysql_query($sql);
		
		if($query)
		{
			/* Commit transaction */
			mysql_query('COMMIT');
			
			$message .= '<li class="green">ปรับปรุงยอดวัตถุดิบเสร็จสมบูรณ์</li>';
		}
		else
		{
			/* Rollback transaction */
			mysql_query('ROLLBACK');
			
			$message .= '<li class="red">เกิดข้อผิดพลาด: เปรับปรุงยอดวัตถุดิบล้มเหลว</li>';
		}
	}
	else
	{
		$message .= '<li class="red">เกิดข้อผิดพลาด: เพิ่มข้อมูลล้มเหลว</li>';
	}
	
	// Report
	$url_target = 'material.php';
	$title = 'สถานะการทำงาน';
	
	require_once("../iic_tools/views/iic_report.php");
	exit();
}

$sql = 'SELECT * FROM material WHERE id_material = '.$_GET['id'];
$result = mysql_query($sql);
$data = mysql_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ลดจำนวนวัตถุดิบ</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
#amount, #quantity
{
	min-width: 100px;
	margin-right: 10px;
	text-align:right;
}
</style>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>ลดจำนวนวัตถุดิบ</h1>
		<hr>
		<form method="post">
			<label>วัตถุดิบ: <span class="normal"><?php echo $data['name'] ?></span></label>
			<label for="id_supplier">ผู้จัดจำหน่าย <span class="normal">| <a href="supplier_create.php">เพิ่มผู้จัดจำหน่าย</a></span></label>
			<select id="id_supplier" name="id_supplier">
				<option value="NULL">-</option>
				<?php 
				$sql_supplier = 'SELECT * FROM supplier';
				$query_supplier = mysql_query($sql_supplier);
				while($data_supplier = mysql_fetch_array($query_supplier))
				{
					echo '<option value="'.$data_supplier['id_supplier'].'" '.$selected.'>'.$data_supplier['name'].'</option>';	
				}
				?>
			</select>
			<input name="action" type="hidden" value="1">
			<label for="amount">ราคาซื้อ</label>
			<input id="amount" name="amount" type="text" value="" /> บาท
			<label for="quantity">จำนวนวัตถุดิบ</label>
			<input id="quantity" name="quantity" type="text" value="" /> <?php echo $data['unit'] ?>
			<label for="description">คำอธิบาย</label>
			<textarea id="description" name="description"></textarea>
			<input name="id" type="hidden" value="<?php echo $_GET['id'] ?>">
			<label class="center">
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