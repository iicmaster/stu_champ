<?php 
require("../include/session.php");
require('../include/connect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานการใช้วัตถุดิบ</title>
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
			<td><h1 align="center">รายงานการใช้วัตถุดิบ</h1></td>
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
				<th>รายการ</th>
				<th width="80">จำนวน</th>
                <th width="80">หน่วย</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$sql = 'SELECT *
		 		FROM material';
						
		$query = mysql_query($sql) or die(mysql_error());
		?>
		
		<?php while($material = mysql_fetch_array($query)): ?>
			<?php			
			$sql = 'SELECT 
						id_product,
						SUM(quantity) AS total
					
					FROM product_transaction
					
					WHERE
						quantity > 0
						AND date_create BETWEEN "'.$_POST['start_date'].'" AND "'.$_POST['end_date'].'"
					
					GROUP BY id_product';
            $result = mysql_query($sql) or die(mysql_error());
           	$total_used_material = array();
			
            while($product = mysql_fetch_assoc($result))
            {
                $sql = 'SELECT *
                        FROM product_material
                        WHERE
                            id_product = '.$product['id_product'].'
                            AND id_material = '.$material['id'];
							
                $result_pm = mysql_query($sql) or die(mysql_error);
                $data = mysql_fetch_assoc($result_pm);
				
                $total_used_material[$product['id_product']] = $product['total'] * $data['quantity'];
            }
			
			?>
			<tr>
				<td><?php echo $material['name'] ?></td>
				<td align="right"><?php echo add_comma(array_sum($total_used_material)) ?></td>
                <td><?php echo $material['unit'] ?></td>
			</tr>
		<?php endwhile ?>
		</tbody>
	</table>
</div>
</body>
</html>