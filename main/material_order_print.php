<?php 
require("../include/session.php");
require('../include/connect.php');

$ID_SUPPLIER = (isset($_GET['id_supplier'])) ? 'AND material_order_item.id_supplier = '.$_GET['id_supplier'] : '';

$sql 	= 'SELECT material_order.*
		   FROM material_order 
		   WHERE material_order.id = "'.$_GET['id'].'"';
				
$query	= mysql_query($sql) or die(mysql_error());
$data	= mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ใบสั่งซื้อวัตถุดิบ</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
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
	color: #333;
	background: none;
}

#paper hr { border-style: solid; }

#signature
{
	bottom: 0;
	margin: 50px;
	padding: 50px;
	position: absolute;
	right: 0;
	text-align: center;
}

h1, h2, h3, h4, h5, h6, th, b, strong
{
	color: #333;
	text-shadow: none;
}
</style>
</head>

<body>
<div id="paper">
	<h1 class="center">ใบสั่งซื้อวัตถุดิบ</h1>
	<h6 class="center"> 
		กลุ่มแม่บ้านบางกะจะ
		<br />
		หมู่ 4 ตำบลบางกะจะ อำเภอเมือง
		<br />จังหวัดจันทบุรี  22000
	</h6>
	<p class="float_r" >ใบสั่งซื้อเลขที่: <?php echo zero_fill(4, $_GET['id']); ?> <br/> วันที่: <?php echo change_date_format($data['date_create']); ?> </p>
	<br/>
	<h5>รายการ</h5>
	<hr />
	<table width="100%">
		<thead>
			<tr>
				<th scope="col" width="30">ลำดับ</th>
				<th scope="col">วัตถุดิบ</th>
				<th scope="col" width="90">จำนวนที่สั่งซื้อ</th>
				<th scope="col" width="50">หน่วย</th>
				<th scope="col" width="250">ผู้จัดจำหน่าย</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$sql = 'SELECT	
						material_order_item.quantity_order as quantity, 
						material_order_item.quantity_receive as quantity_receive, 
						material.name as material,
						material.unit as unit, 
						supplier.name as supplier
							
					FROM material_order_item 
					
					LEFT JOIN material
					ON material_order_item.id_material = material.id
					
					LEFT JOIN supplier
					ON material_order_item.id_supplier = supplier.id
					
					WHERE 
						material_order_item.id_material_order = "'.$data['id'].'"'.
						$ID_SUPPLIER;
					
			$result = mysql_query($sql) or die(mysql_error());
			$result_row = mysql_num_rows($result);
			$loop = 1;
			
			if($result_row > 0)
			{
				while($data = mysql_fetch_array($result))
				{
					echo '<tr>
							<td class="center">'.$loop++.'</td>
							<td>'.$data['material'].'</td>
							<td class="right">'.add_comma($data['quantity']).'</td>
							<td>'.$data['unit'].'</td>
							<td>'.$data['supplier'].'</td>
						</tr>';
				}
			}
			else 
			{
				echo '<tr><td colspan="5" align="center">ไม่พบข้อมูล</td></tr>';
			}
			?>
		</tbody>
	</table>
	<div class="float_r">
		..............................
		<br />
		(ผู้ดำเนินการ)
	</div>
</div>
</body>
</html>