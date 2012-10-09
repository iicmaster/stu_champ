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
<title>ดูข้อมูลการขายปลีกสินค้า</title>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<script type="text/javascript">
$(function(){
	//$('#date_receive').datepicker({ dateFormat: 'yy-mm-dd' });
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
		<a href="product_retail_print.php?id=<?php echo $_GET['id']; ?>" class="float_r">พิมพ์ใบเสร็จรับเงิน</a>
		<h1>ดูข้อมูลการขายปลีกสินค้า</h1>
		<hr>
		<div class="float_r">วันที่ <?php echo change_date_format($data['date_create']); ?></div>
		<form method="post" enctype="multipart/form-data">
			<label for="orderer">ชื่อลูกค้า</label>
			<input id="orderer" name="orderer" type="text" value="<?php echo $data['orderer']; ?>" readonly="readonly" />
			<hr />
			<table>
				<thead>
				<tr>
					<th width="25">ลำดับ</th>
					<th>สินค้า</th>
					<th>จำนวน</th>
					<th>ราคาต่อหน่วย</th>
					<th>รวม</th>
				</tr>
				</thead>
				<tbody>
				<?php 
				$sql = 'SELECT *
						FROM product_order_item 
				
						LEFT JOIN product
						ON product_order_item.id_product = product.id
						
						WHERE product_order_item.id_order = '.$_GET['id'];
						
				$query = mysql_query($sql) or die(mysql_error());
				$loop = 1;
				
				$grand_total = 0;
				
				while($data = mysql_fetch_assoc($query)): 
					
					$price = ($data['quantity'] >= $data['order_min']) ? $data['price_wholesale'] : $data['price_retail'];
					$total = $price * $data['quantity'];
					$grand_total += $total;
				?>
				<tr>
					<td align="center"><?php echo $loop; ?></td>
					<td><?php echo $data['name']; ?></td>
					<td align="right"><?php echo $data['quantity'] ?></td>
					<td align="right"><?php echo add_comma($price) ?></td>
					<td align="right"><?php echo add_comma($total) ?></td>
				</tr>
				
				<?php 
				$loop++;
				endwhile; 
				?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" class="center">รวม</td>
						<td align="right"><?php echo add_comma($grand_total) ?></td>
					</tr>
				</tfoot>
			</table>
		</form>
		<hr style="margin-top:25px" />
		<a href="product_retail.php">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>