<?php 
require("../include/session.php");
require('../include/connect.php');
require('../include/product_helper.php');

// --------------------------------------------------------------------------------
// Calculate income
// --------------------------------------------------------------------------------

$sql = 'SELECT *
		FROM product_order 
		WHERE 
			date_create BETWEEN "'.$_POST['start_date'].'" AND "'.$_POST['end_date'].'"';
		
$query_order = mysql_query($sql) or die(mysql_error());

$grand_total = 0;

while($order = mysql_fetch_assoc($query_order))
{				
	
	$sql = 'SELECT *
			FROM product_order_item 
	
			LEFT JOIN product
			ON product_order_item.id_product = product.id
			
			WHERE product_order_item.id_order = '.$order['id'];
			
	$query_item = mysql_query($sql) or die(mysql_error());
	
	while($data = mysql_fetch_assoc($query_item))
	{
		$price = ($data['quantity'] >= $data['order_min']) ? $data['price_wholesale'] : $data['price_retail'];
		$total = $price * $data['quantity'];
		$grand_total += $total;
	}
}

// --------------------------------------------------------------------------------
// Calculate cost
// --------------------------------------------------------------------------------

$total_cost = 0;

$sql = 'SELECT
			*, 
			ABS(SUM(quantity)) AS total
		FROM product_transaction
		WHERE 
			date_create BETWEEN "'.$_POST['start_date'].'" AND "'.$_POST['end_date'].'"
			AND (type = 2 OR type = 3)
		GROUP BY id_product, stock_code';
		
//echo $sql;
		
$query_production_log = mysql_query($sql) or die(mysql_error());

while($data = mysql_fetch_assoc($query_production_log))
{
	$product_cost = get_product_cost($data['stock_code']);
	$total_cost += $product_cost[$data['id_product']] * $data['total'];
}

//echo $total_cost;

// --------------------------------------------------------------------------------
// Calculate profit
// --------------------------------------------------------------------------------

$profit = $grand_total - $total_cost;
$profit = ($profit > 0) ? $profit : 0;
	
$sql = 'SELECT COUNT(*) AS total_work_qty

		FROM production_member AS t1
		
		JOIN production_log
		ON production_log.id = t1.id_log
		
		WHERE 
			production_log.date_create 
				BETWEEN "'.$_POST['start_date'].'" 
				AND "'.$_POST['end_date'].'"';
				
$result = mysql_query($sql) or die(mysql_error());
$data = mysql_fetch_assoc($result);
$total_work_qty = $data['total_work_qty'];

$DPU = round($profit / $total_work_qty, 3);

// --------------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ใบรายการ</title>
<?php include("inc.css.php"); ?>
<style type="text/css" media="print">
#paper
{
	width: 16cm;
	min-height: 24.7cm;
	padding: 2.5cm;
	position: relative;
}
</style>

<style type="text/css" media="screen">
#paper
{
	background: #FFF;
	border: 1px solid #666;
	margin: 20px auto;
	width: 21cm;
	min-height: 27cm;
	padding: 50px;
	position: relative;
	
	/* CSS3 */
	
	box-shadow: 0px 0px 5px #000;
	-moz-box-shadow: 0px 0px 5px #000;
	-webkit-box-shadow: 0px 0px 5px #000;
}
</style>

<style type="text/css" >
#paper h3 { margin-bottom: 0px; }

#paper li
{
	list-style: decimal;
	margin: 5px 0px 5px 30px;
}

#paper textarea
{
	margin-bottom:25px;
	width: 50%;
}

#paper table, #paper th, #paper td { border: none; }

#paper table.border, #paper table.border th, #paper table.border td { border: 1px solid #666; }

#paper th
{
	background: none;
	color: #000
}

#paper hr { border-style: solid; }
</style>
</head>

<body>
<div id="paper">
	<table width="100%">
		<!--<tr>
			<td width="80" align="right">วันที่ : /*<?php echo change_date_format(date('Y-m-d')) ?>*/</td>
		</tr>-->
		<tr>
			<td><h1 align="center">รายงานเงินปันผลของสมาชิก</h1></td>
		</tr>
		<tr>
			<td align="center">
				<h5>
				ช่วงวันที่&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo change_date_format($_POST['start_date']) ?>&nbsp;&nbsp;&nbsp;&nbsp;
				ถึงวันที่&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo change_date_format($_POST['end_date']) ?>
				</h5>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>
	<table width="100%" class="border">
		<thead>
			<tr>
				<th width="80">รหัส</th>
				<th>ชื่อ - นามสกุล</th>
                <th width="100">ปันผล / 1 ครั้ง</th>
                <th width="100">เข้าทำงาน</th>
                <th width="100">เงินปันผล</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$sql = 'SELECT 
						*,
						(
							SELECT COUNT(id_worked_member)
							
							FROM production_member
							
							JOIN production_log
							ON production_log.id = production_member.id_log

							WHERE
								id_worked_member = t1.id_worked_member
								AND production_log.date_create 
										BETWEEN "'.$_POST['start_date'].'" 
										AND "'.$_POST['end_date'].'"
						) AS work_qty
			
					FROM production_member AS t1
					
					JOIN member
					ON member.id = t1.id_worked_member
					
					JOIN production_log
					ON production_log.id = t1.id_log
					
					WHERE 
						production_log.date_create 
							BETWEEN "'.$_POST['start_date'].'" 
							AND "'.$_POST['end_date'].'"
						
					GROUP BY
						t1.id_worked_member';
						
			//echo $sql;
			
			$result = mysql_query($sql) or die(mysql_error());			
			
			while($data = mysql_fetch_assoc($result)):
			?>
			<tr>
				<td align="center"><?php echo zero_fill(4, $data['id_worked_member']) ?></td>
				<td align=""><?php echo $data['name'] ?></td>
                <td align="right"><?php echo $DPU ?></td>
                <td align="right"><?php echo $data['work_qty'] ?></td>
                <td align="right"><?php echo $DPU * $data['work_qty'] ?></td>
			</tr>
			<?php endwhile ?>		
		</tbody>
        <tfoot>
			<tr>
				<td colspan="3" class="bold right">รวม</td>
				<td align="right"><?php echo $total_work_qty ?></td>
				<td align="right"><?php echo $DPU * $total_work_qty ?></td>

			</tr>
		</tfoot>
	</table>
</div>
</body>
</html>