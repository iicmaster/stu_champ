<?php 
require("../include/session.php");
require('../include/connect.php');

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

function get_product_cost($stock_code)
{
	// Get id_production_log
	$sql = 'SELECT * FROM product_transaction WHERE stock_code = "'.$stock_code.'"';
	$query = mysql_query($sql) or die(mysql_error());
	$data = mysql_fetch_array($query);
	$id_production_log = $data['id_production_log'];
	
	// Get material cost for this production
	$sql = 'SELECT * FROM material';
	$query = mysql_query($sql) or die(mysql_error());
	
	while($material = mysql_fetch_array($query))
	{
	    $sql = 'SELECT 
					ABS(quantity) AS quantity,
					(
						SELECT SUM(amount) 
						FROM material_transaction 
						WHERE stock_code = t1.stock_code 
						AND id_material = t1.id_material
					) AS total_amount,
					(
						SELECT SUM(quantity) 
						FROM material_transaction 
						WHERE stock_code = t1.stock_code 
						AND id_material = t1.id_material 
						AND quantity > 0
					) AS total_quantity
				
				FROM material_transaction AS t1
				
				WHERE 
					id_material = '.$material['id'].'
					AND id_production_log = "'.$id_production_log.'"';
					
	    $result_cost = mysql_query($sql) or die(mysql_error);
	    $result_cost_row = mysql_num_rows($result_cost);
		
		$material_cost[$material['id']] = 0;
		
		if($result_cost_row > 0)
		{
			while($cost = mysql_fetch_assoc($result_cost))
			{
				//$material_cost[$material['id']] = round($cost['total_amount'] / $cost['total_quantity'], 2);{
				$material_cost[$material['id']] += $cost['quantity'] * round($cost['total_amount'] / $cost['total_quantity'], 2);
				@$total_used_material[$material['id']] += $cost['quantity'];
			}
			
			$material_cost[$material['id']] = round($material_cost[$material['id']] / @$total_used_material[$material['id']], 2);
		}
		    
		// Get required material per product
	    $sql = 'SELECT id FROM product';
	    $result = mysql_query($sql) or die(mysql_error());
	   	$required_qty = array();
		
	    while($product = mysql_fetch_assoc($result))
	    {
	        $sql = 'SELECT *
	                FROM product_material
	                WHERE
	                    id_product = '.$product['id'].'
	                    AND id_material = '.$material['id'];
						
	        $result_pm = mysql_query($sql) or die(mysql_error);
	        $data = mysql_fetch_assoc($result_pm);
	        
	        $material_require[$product['id']][$material['id']] = $data['quantity'];
	    }
	}
		
	//print_array($material_cost);
	//print_array($material_require);
	
	// Get product cost/unit
	$product_material_cost = array();
	$product_cost = array();
	
	$sql = 'SELECT *
			FROM product_transaction
			WHERE 
				id_production_log = "'.$id_production_log.'"
			GROUP BY id_product';
		
	$query_production_transaction = mysql_query($sql) or die(mysql_error());
	
	while($production_transaction = mysql_fetch_assoc($query_production_transaction))
	{
		$id_product = $production_transaction['id_product'];
		
		foreach ($material_require[$id_product] as $id_material => $value) 
		{
			$product_material_cost[$id_product][$id_material] = $material_require[$id_product][$id_material] * $material_cost[$id_material];
			@$product_cost[$id_product] += $material_require[$id_product][$id_material] * $material_cost[$id_material];
		}
	}
	
	//print_array($product_material_cost);
	//print_array($product_cost);
	//exit();
	
	return $product_cost;
}	

//print_array(get_product_cost('2012-09-02')); 
//get_product_cost(31); 
//exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานกำไร-ขาดทุน</title>
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
		<tr>
			<td><h1 align="center">รายงานกำไร - ขาดทุน</h1></td>
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
	<table width="100%" bordercolor="#FFFFFF">
		<tbody>
			<tr>
				<th colspan="3" class="float_l">รายได้</th>
			</tr>
			<tr>
				<td align="center">รายได้จากการขายสินค้า</td>
				<td align="center"><?php echo add_comma($grand_total) ?></td>
                <td width="80" align="center">บาท</td>
			</tr>
			<tr>
				<th align="right">ยอดรวม</th>
				<td align="right"><?php echo add_comma($grand_total) ?></td>
                <td width="80" align="center">บาท</td>
			</tr>
			<tr>
				<th colspan="3" class="float_l">หัก</th>
			</tr>
			<tr>
				<td align="center">ต้นทุน</td>
				<td align="center"><?php echo add_comma($total_cost) ?></td>
                <td width="80" align="center">บาท</td>
			</tr>
			<tr>
				<th>ยอดรวม</th>
				<td align="right"><?php echo add_comma($total_cost) ?></td>
                <td width="80" align="center">บาท</td>
			</tr>
			<tr>
				<th class="float_r">กำไรขั้นต้น</th>
				<td align="right"><?php echo add_comma($grand_total - $total_cost) ?></td>
                <td width="80" align="center">บาท</td>
			</tr>
		</tbody>
	</table>
</div>
</body>
</html>