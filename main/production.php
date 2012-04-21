<?php 
require_once("../include/session.php"); 
require('../include/connect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบจัดการผลิตและจำหน่ายสละลอยแก้ว</title>
<style type="text/css">
#product_stock { float: right; }
</style>
<?php include("inc.css.php"); ?>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript">
$(function(){
	$("#id_product").change(function(){
		
		var id_product = $(this).val();
		
		$.get("production_get_quantity.php", { "id_product": id_product }, function(data){
		   $("#quantity").html(data);
		 });
	});
});
</script>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>คำนวนการผลิต</h1>
		<hr />
		<div id="product_stock">
			<h3>จำนวนสินค้าที่ต้องผลิต</h3>
			<table>
				<tr>
					<th scope="col">สินค้า</th>
					<th scope="col">จำนวนคงเหลือ</th>
					<th scope="col">จำนวนที่ควรผลิตเพิ่ม</th>
					<th scope="col">ยอดการสั่งซื้อสินค้า</th>
					<th scope="col">รวมสินค้าที่ต้องผลิตทั้งหมด</th>
				</tr>
				<?php 
				$sql = 'SELECT * FROM product';
				$query = mysql_query($sql);
				while($data = mysql_fetch_array($query))
				{
					echo '<tr>
								<td>'.$data['name'].'</td>
								<td class="right">'.$data['total'].'</td>
								<td class="right">'.($data['stock_max'] - $data['total']).'</td>
								<td></td>
								<td></td>
							</tr>';	
				}
				?>
			</table>
		<h3>วัตถุดิบที่ต้องใช้</h3>
		<table>
			<thead>
				<tr>
					<th>วัตถุดิบ</th>
					<th>จำนวน</th>
					<th>หน่วย</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
		</div>
		<form method="get" action="production_queue_add.php">
			<p class="center">
				<input id="submit" name="submit" type="submit" value="จัดคิวทำงาน" />
			</p>
			<?php echo $data['unit'] ?>
		</form>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>
