<?php 
require("../include/session.php");
require('../include/connect.php');

$sql = 'SELECT * FROM product_order WHERE id = "'.$_GET['id'].'"';
$query = mysql_query($sql) or die(mysql_error());
$data = mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ดูข้อมูลออกใบสั่งซื้อสินค้า</title>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<script type="text/javascript">
$(function(){
	$('#date_receive').datepicker({ dateFormat: 'yy-mm-dd' });
});
</script>
<?php include("inc.css.php"); ?>
<style type="text/css">
input[type=text].right { min-width: 50px; }
form hr { margin-top: 20px; }
</style>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<a href="product_order_print.php?id=<?php echo $_GET['id']; ?>" class="float_r">พิมพ์ใบสั่งซื้อสินค้า</a>
		<h1>ดูข้อมูลออกใบสั่งซื้อสินค้า</h1>
		<hr>
		<div class="float_r">วันที่ <?php echo date('d / m / Y'); ?></div>
		<form method="post" enctype="multipart/form-data">
			<label for="orderer">ชื่อ</label>
			<input id="orderer" name="orderer" type="text" value="<?php echo $data['orderer']; ?>" />
			<label for="tel">โทรศัพท์</label>
			<input id="tel" name="tel" type="text" value="<?php echo $data['tel']; ?>" />
			<label for="date_receive">วันที่มารับสินค้า</label>
			<input id="date_receive" name="date_receive" class="datepicker" type="text" value="<?php echo $data['date_receive']; ?>" />
			<label for="description">รายละเอียด</label>
			<textarea name="description"><?php echo $data['description']; ?></textarea>
			<hr />
			<table>
				<tr>
					<th width="25">ลำดับ</th>
					<th>สินค้า</th>
					<th>จำนวน</th>
				</tr>
				<?php 
				$sql = 'SELECT *
						FROM product_order_item 
				
						LEFT JOIN product
						ON product_order_item.id_product = product.id
						
						WHERE product_order_item.id_order = '.$_GET['id'];
						
				$query = mysql_query($sql) or die(mysql_error());
				$loop = 1;
				
				while($data = mysql_fetch_assoc($query)): 
				?>
				<tr>
					<td align="center"><?php echo $loop; ?></td>
					<td><?php echo $data['name']; ?></td>
					<td width="200"><input type="text" name="quantity[<?php echo $data['id']; ?>]" class="right" value="<?php echo $data['quantity'] ?>" readonly="readonly" /> <?php echo $data['unit']; ?></td>
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