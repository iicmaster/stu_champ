<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{ 
	//print_array($_POST);
	//exit();

	$message = '';

	// --------------------------------------------------------------------------------
	// Start transaction
	// --------------------------------------------------------------------------------
	
		mysql_query("BEGIN");	
		
	// --------------------------------------------------------------------------------
	// Create material order
	// --------------------------------------------------------------------------------
	
		$sql = 	'INSERT INTO material_order
				 SET 	
<<<<<<< HEAD
					description	= "'.$_POST['description'].'",
=======
>>>>>>> 736daa5b8a0bdca7c8d1c03000a54c5f6e3d5959
					date_create	= "'.date('Y-m-d').'"';
		
		// RollBack transaction and show error message when query error						
		if( ! $query = mysql_query($sql))
		{
			echo 'Create material_order';
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
	
		$sql	= 'SELECT MAX(id) AS id FROM material_order';
		$query	= mysql_query($sql);
		
		// RollBack transaction and show error message when query error						
		if( ! $query = mysql_query($sql))
		{
			echo 'Get last id';
			echo '<hr />';
			echo mysql_error();
			echo '<hr />';
			echo $sql;
			mysql_query("ROLLBACK");
			exit();
		}
		
		$data				= mysql_fetch_array($query);
		$id_material_order	= $data['id'];
		
	// --------------------------------------------------------------------------------
	// Create material order item		
	// --------------------------------------------------------------------------------

		for($loop = 0; $loop < count($_POST['id_material']); $loop++)
		{
			// Remove comma
			$quantity = remove_comma($_POST['quantity'][$loop]);
			
			$sql = 	'INSERT INTO material_order_item
					 SET 	
					 		id_material_order	= "'.$id_material_order.'",
					 		id_material 		= "'.$_POST['id_material'][$loop].'",
					 		quantity_order		= "'.$quantity.'",
							id_supplier			= "'.$_POST['id_supplier'][$loop].'"';
							
			// RollBack transaction and show error message when query error						
			if( ! $query = mysql_query($sql))
			{
				echo 'Create material order item	';
				echo '<hr />';
				echo mysql_error();
				echo '<hr />';
				echo $sql;
				mysql_query("ROLLBACK");
				exit();
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
		$url_target	= 'material_order.php';
		$title		= 'สถานะการทำงาน';
		$message 	.= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
		$message 	.= '<p class="center" style="margin-top:25px;"><a href="material_order_print.php?id='.$id_material_order.'" target="_blank" class="iic_button">พิมพ์ใบสั่งซื้อวัตถุดิบ</a></p>';
		
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
<title>ใบสั่งซื้อวัตถดิบ</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
#amount, #quantity
{
	min-width: 100px;
	margin-right: 10px;
	text-align:right;
}

form select { min-width: 250px; }

form input[type=text] 
{ 
	min-width: 80px;
	width: 80px; 
	text-align: right;
}

form textarea
{
	width: 50%;
}
</style>
<!-- jQuery -->
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>

<!-- jQuery - Internal -->
<script type="text/javascript">
$(function()
{
	// Validate form
	$("form").submit(function()
	{
		var validate = true;
		
		$('form select').each(function(index)
		{
			if($(this).val() == '')
			{
				alert('โปรดเลือกผู้จัดจำหน่าย');
				
				$(this).focus();
				
				validate = false;
				
				return false;	
			}
		});
		
		if(! validate)
		{
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
		<h1>ใบสั่งซื้อวัตถุดิบ</h1>
		<hr>
		<form method="post">
			<p class="float_r">วันที่: <?php echo date('d / m / Y '); ?></p>
<<<<<<< HEAD
			<label for="description">คำอธิบาย</label>
			<textarea id="description" name="description" rows="3" ></textarea>
=======
>>>>>>> 736daa5b8a0bdca7c8d1c03000a54c5f6e3d5959
			<label>รายการ</label>
			<hr />

			<table width="100%">
				<thead>
					<tr>
						<th scope="col" width="30">ลำดับ</th>
						<th scope="col">วัตถุดิบ</th>
						<th scope="col" width="90">จำนวน</th>
						<th scope="col" width="50">หน่วย</th>
						<th scope="col" width="250">ผู้จัดจำหน่าย</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$id_material = array();
					
					if(isset($_POST['id']))
					{
						$id_material = $_POST['id'];
					}
					else
					{
						array_push($id_material, $_GET['id']);
					}
						
					// Create supplier option
					$sql_supplier	= 'SELECT * FROM supplier';
					$query_supplier	= mysql_query($sql_supplier) or die(mysql_error());
					
					$supplier_option = '';
					
					while($data_supplier = mysql_fetch_array($query_supplier))
					{
						$supplier_option .= '<option value="'.$data_supplier['id'].'">'.$data_supplier['name'].'</option>';	
					}
					
					for($loop = 0; $loop < count($id_material); $loop++)
					{
						$sql	= 'SELECT * FROM material WHERE id = '.$id_material[$loop];
						$result = mysql_query($sql);
						$data	= mysql_fetch_array($result);
						
						echo '<tr>
								<td class="center">'.($loop + 1).'</td>
								<td>'.$data['name'].'<input type="hidden" name="id_material[]" value="'.$data['id'].'" /></td>
								<td class="right"><input type="text" name="quantity[]" value="'.add_comma(abs($data['total'] - $data['stock_min'])).'" /></td>
								<td>'.$data['unit'].'</td>
								<td class="center">
									<select id="id_supplier_'.($loop + 1).'" name="id_supplier[]" class="required">
										<option value="">-</option>
										'.$supplier_option.'
									</select>
								</td>
							</tr>';
					}
					?>
				</tbody>
			</table>
			<p class="center">
				<input id="submit" name="submit" type="submit" value="บันทึก" />
			</p>
		</form>
		<hr style="margin-top:25px" />
		<a href="material.php">กลับ</a> </div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>