<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{	
	$message = '';
	
	// --------------------------------------------------------------------------------
	// Start transaction
	// --------------------------------------------------------------------------------
	
	mysql_query('BEGIN');

	// --------------------------------------------------------------------------------
	// Insert order head
	// --------------------------------------------------------------------------------
	
	$sql = 'INSERT INTO product_order
			SET		
				orderer			= "'.$_POST['orderer'].'",
				tel				= "'.$_POST['tel'].'",
				date_receive	= "'.$_POST['date_receive'].'",
				date_create		= NOW()';
					
	$query = mysql_query($sql) or die(mysql_error());
	
	// --------------------------------------------------------------------------------
	// Get id
	// --------------------------------------------------------------------------------
	
	$sql = 'SELECT MAX(id) as id FROM product_order';
	$query = mysql_query($sql) or die(mysql_error());
	$data = mysql_fetch_assoc($query);
	$id_order = $data['id'];
	
	// --------------------------------------------------------------------------------
	// Insert order item
	// --------------------------------------------------------------------------------
	
	foreach($_POST['quantity'] as $key => $val)
	{
		if($val != '' && $val != 0)
		{
			$sql = 'INSERT INTO product_order_item
					SET
						id_order	= "'.$id_order.'",
						id_product	= "'.$key.'",
						quantity	= "'.$val.'"';
						
			if(! mysql_query($sql))
			{
				$message .= '<li class="red">เกิดข้อผิดพลาด: บันทึกข้อมูลรายการสินค้าล้มเหลว</li>';
				$message .= '<li>'.mysql_error().'</li>';
				
				/* Rollback transaction */
				mysql_query('ROLLBACK');
				
				// Report
				$css = '../css/style.css';
				$url_target = 'product_order.php';
				$title = 'สถานะการทำงาน';
				
				require_once("../iic_tools/views/iic_report.php");
				exit();
			}
		}
	}
				
	// --------------------------------------------------------------------------------
	// Commit transaction
	// --------------------------------------------------------------------------------
	
	mysql_query('COMMIT');
		
	// --------------------------------------------------------------------------------
	// Report
	// --------------------------------------------------------------------------------
	
	$css = '../css/style.css';
	$url_target = 'product_order.php';
	$title = 'สถานะการทำงาน';
	$message = '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	
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
<title>ออกใบสั่งซื้อสินค้า</title>
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
	$('#date_receive').datepicker({ dateFormat: 'yy-mm-dd' });
	$("form").validate();
});
</script>
<?php include("inc.css.php"); ?>
<style type="text/css">
input[type=text].right { min-width: 50px; }
form hr { margin: 20px 0; }
form td i.error { margin: 0px; float: left;}
</style>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>ออกใบสั่งซื้อสินค้า</h1>
		<hr>
		<div class="float_r">วันที่ <?php echo date('d / m / Y'); ?></div>
		<form method="post" enctype="application/x-www-form-urlencoded">
			<label for="orderer">ชื่อ<i>*</i></label>
			<input id="orderer" name="orderer" type="text" class="required" />
			<label for="tel">โทรศัพท์<i>*</i></label>
			<input id="tel" name="tel" type="text" class="required integer" maxlength="10" />
			<label for="date_receive">วันที่นัดรับสินค้า<i>*</i></label>
			<select id="date_receive" name="date_receive">
				<?php for ($loop = 7; $loop <= 28; $loop++): ?>
				<?php $receive_date = date('Y-m-d', strtotime('+'.$loop.' days')) ?>
				<option value="<?php echo $receive_date ?>"><?php echo change_date_format($receive_date) ?></option>
				<?php endfor ?>
			</select>
			<hr />
			<table>
				<tr>
					<th width="25">ลำดับ</th>
					<th>สินค้า</th>
					<th>จำนวน</th>
				</tr>
				<?php 
				$sql = 'SELECT * FROM product';
				$query = mysql_query($sql);
				$loop = 1;
				while($data = mysql_fetch_assoc($query)): 
				?>
				<tr>
					<td align="center"><?php echo $loop; ?></td>
					<td><?php echo $data['name']; ?> (สั่งขั้นต่ำ <?php echo $data['order_min']; ?> <?php echo $data['unit']; ?>)</td>
					<td width="200"><span></span><input type="text" name="quantity[<?php echo $data['id']; ?>]" class="right"  value="" min="<?php echo $data['order_min']; ?>" /> <?php echo $data['unit']; ?></td>
				</tr>
				<?php 
				$loop++;
				endwhile; 
				?>
			</table>
			<p class="center">
				<input id="submit" name="submit" type="submit" value="บันทึก" />
			</p>
		</form>
		<hr style="margin-top:25px" />
		<a href="product_order.php">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>