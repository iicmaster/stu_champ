<?php 
require("../include/session.php");
require('../include/connect.php');

$sql = 'SELECT *
 		FROM product_order 
		WHERE id = "'.$_GET['id'].'"';
				
$query = mysql_query($sql) or die(mysql_error());
$data = mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ใบสั่งซื้อสินค้า</title>
<?php include("inc.css.php"); ?>
<style type="text/css">


#paper h5, #paper th { margin-bottom: 10px; text-shadow: none; }

#paper
{
	background: #FFF;
	border: 1px solid #666;
	margin: 20px;
	min-height: 25cm;
	padding: 50px 20px;
	position: relative;

	/* CSS3 */
	box-shadow: 0px 0px 5px #000;
	-moz-box-shadow: 0px 0px 5px #000;
	-webkit-box-shadow: 0px 0px 5px #000;
}

#paper textarea
{
	margin-bottom:25px;
	width: 50%;
}

#paper th
{
	background: none;
	color: #000
}

#paper hr { border-style: solid; }

#paper textarea { width: 100%}

#signature
{
	bottom: 0;
	margin: 50px;
	padding: 50px;
	position: absolute;
	right: 0;
	text-align: center;
}
</style>
</head>

<body>
<div id="paper">
	<h1 class="center">ใบเสร็จรับเงิน</h1>
	<h6 class="center"> 
		กลุ่มแม่บ้านบางกะจะ
		<br />
		หมู่ 4 ตำบลบางกะจะ อำเภอเมือง
		<br />จังหวัดจันทบุรี  22000
	</h6>
	<p class="float_r" >ใบเสร็จเลขที่: <?php echo zero_fill(4, $_GET['id']); ?> </br> วันที่: <?php echo change_date_format($data['date_create']); ?> </p>
	<h6>ชื่อลูกค้า: <span class="normal"><?php echo $data['orderer']; ?></span></h6>
	<h6>เบอร์โทรศัพท์: <span class="normal"><?php echo $data['tel']; ?></span></h6>
	<h6>วันที่มารับสินค้า: <span class="normal"><?php echo change_date_format($data['date_receive']); ?></span></h6>
	<h5>รายการ</h5>
	<hr />
	<table width="100%">
		<thead>
			<tr>
				<th scope="col" width="30">ลำดับ</th>
				<th scope="col">สินค้า</th>
				<th scope="col" width="120">ราคาต่อหน่วย (บาท)</th>
				<th scope="col" width="90">จำนวนที่สั่งซื้อ</th>
				<th scope="col" width="50">หน่วย</th>
				<th scope="col" width="120">รวม (บาท)</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$sql = 'SELECT	
						product_order_item.quantity AS quantity, 
						product.name AS product,
						product.price_wholesale AS price,
						product.unit AS unit
							
					FROM product_order_item 
					
					LEFT JOIN product
					ON product_order_item.id_product = product.id
					
					WHERE product_order_item.id_order = "'.$_GET['id'].'"';
					
			$query = mysql_query($sql) or die(mysql_error());
			$loop = 1;
			$grand_total = 0;
				
			while($data = mysql_fetch_array($query))
			{
				echo '<tr>
						<td class="center">'.$loop++.'</td>
						<td>'.$data['product'].'</td>
						<td class="right">'.add_comma($data['price']).'</td>
						<td class="right">'.$data['quantity'].'</td>
						<td>'.$data['unit'].'</td>
						<td class="right">'.add_comma($data['price'] * $data['quantity']).'.00</td>
					</tr>';
					
				$grand_total += $data['price'] * $data['quantity'];
			}
			?>
		</tbody>
		<tfoot>
			<tr><td colspan="5" class="center">รวมเป็นเงิน (บาท)</td>
				<td width="250" class="right"><?php echo add_comma($grand_total); ?>.00</td>
			</tr>
		</tfoot>
	</table>
		<div class="float_r">
		..............................
		<br />
		(ผู้ดำเนินการ)
		</div>
</div>
</body>
</html>