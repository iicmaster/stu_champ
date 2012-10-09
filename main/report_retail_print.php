<?php 
require("../include/session.php");
require('../include/connect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานการขายปลีก</title>
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
			<td><h1 align="center">รายงานการขายปลีก</h1></td>
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
				<th width="20">รหัส</th>
				<th width="100">วันที่ทำรายการ</th>
      
                
				<?php 
				$query = 'SELECT name, unit FROM product';
				$result = mysql_query($query) or die(mysql_error());
				$total_product_type = mysql_num_rows($result);
				while($product = mysql_fetch_assoc($result)):
				?>
				<th><?php echo $product['name'] ?></th>
				<?php endwhile  ?>
				
			</tr>
		</thead>
		<tbody>
		<?php
		$sql = 'SELECT *
		 		FROM product_order
		 		WHERE
		 			type = 1
		 			AND date_create BETWEEN "'.$_POST['start_date'].'" AND "'.$_POST['end_date'].'"';
						
		$query = mysql_query($sql) or die(mysql_error());
		$total_ordered = array();
		?>
		
		<?php while($order = mysql_fetch_array($query)): ?>
			
			<?php 
			// Get product order item
            $sql_order_item = 'SELECT *
                          	   FROM product_order_item
                          	   WHERE id_order = '.$order['id'];
                          
            $query_order_item = mysql_query($sql_order_item) or die(mysql_error());
			$order_item = array();
			
			// Get order item array
            while($data_order_item = mysql_fetch_assoc($query_order_item))
			{
				$product_ordered_list[$order['id']][$data_order_item['id_product']] = $data_order_item['quantity'];
				$order_item[$data_order_item['id_product']] = $data_order_item['quantity'];
				@$total_ordered[$data_order_item['id_product']] += $data_order_item['quantity'];
			}
			?>
			<tr>
				<td align="center"><?php echo zero_fill(4, $order['id']) ?></td>
				<td align="center"><?php echo change_date_format($order['date_create']) ?></td>
      
                <?php 
				$query_product = 'SELECT * FROM product';
				$result = mysql_query($query_product) or die(mysql_error());
				?>
				
				<?php while($product = mysql_fetch_assoc($result)): ?>
                <td class="right">
                	<?php 
                	if(isset($order_item[$product['id']]))
                	{
                		echo add_comma($order_item[$product['id']]);
					} 
                	else 
                	{
                		echo 0;
                	}
                	?>
                </td>
                <?php endwhile ?>
			</tr>	
		<?php endwhile ?>	
		</tbody>
        <tfoot>
			<tr>
				<td colspan="2" align="center">รวม</td>
				<?php foreach($total_ordered as $val): ?>
				<td align="right"><?php echo add_comma($val) ?></td>
				<?php endforeach ?>
			</tr>
		</tfoot>
	</table>
</div>
</body>
</html>