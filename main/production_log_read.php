<?php 
require("../include/session.php");
require('../include/connect.php');

$sql = 'SELECT * FROM production_log WHERE id = "'.$_GET['id'].'"';
$query = mysql_query($sql) or die(mysql_error());
$production = mysql_fetch_array($query);

$is_approved = ($production['is_approved'] == 1) ? '<span class="block center green">ตรวจรับแล้ว</span>' : '<span class="block center red">ยังไม่ได้ตรวจรับ</span>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ใบผลิต</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
input[type=text], textarea
{
	width: 50%;
}
h3 { margin: 30px 0px 15px 0px}
</style>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<a href="production_log_print.php?id=<?php echo $_GET['id'] ?>" class="float_r">พิมพ์ใบผลิต</a>
		<h1>ใบผลิต</h1>
		<hr>
		<h3>รายละเอียด</h3>
		<table>
	        <thead>
				<tr>
					<th scope="col">เลขที่</th>
					<th scope="col">วันที่ออกใบผลิต</th>
					<th scope="col">วันที่ผลิต</th>
					<th scope="col">วันหมดอายุ</th>
	                <th scope="col">สถานะการตรวจรับ</th>
				</tr>
	        </thead>
				<tr>
					<td class="center"><?php echo zero_fill(10, $production['id']) ?></td>
					<td class="center"><?php echo change_date_format($production['date_create']) ?></td>
					<td class="center"><?php echo change_date_format($production['date_work']) ?></td>
					<td class="center"><?php echo change_date_format($production['date_exp']) ?></td>
					<td class="center"><?php echo $is_approved ?></td>
				</tr>
			<tbody>
			</tbody>
		</table>
		<h3>จำนวนสินค้าที่ผลิตเพิ่มเพื่อนำเข้าคลัง</h3>
		<table>
	        <thead>
				<tr>
					<th scope="col">สินค้า</th>
					<th scope="col">จำนวนที่ผลิตได้จริง</th>
	                <th scope="col">หน่วย</th>
				</tr>
	        </thead>
			<tbody>
			<?php
			$sql = 'SELECT	
						production_product.*,
						product.name AS name,
						product.unit AS unit
							
					FROM production_product 
					
					LEFT JOIN product
					ON production_product.id_product = product.id
					
					WHERE 
						production_product.type = 0
						AND id_log = '.$_GET['id'];
	
			$result = mysql_query($sql) or die(mysql_error());
			$result_row = mysql_num_rows($result);
	
			while($data = mysql_fetch_array($result))
			{
				$total_restock[$data['id_product']] = $data['quantity_receive'];
				$total_produced[$data['id_product']] = $data['quantity_receive'];
	
				echo '<tr>
						<td>'.$data['name'].'</td>
						<td class="right">'.add_comma($data['quantity_receive']).'</td>
						<td>'.$data['unit'].'</td>
					  </tr>';
			}
			?>
			</tbody>
		</table>
		<h3>จำนวนสินค้าที่มีการสั่งจากลูกค้า</h3>
		<table>
	        <thead>
				<tr>
					<th scope="col">สินค้า</th>
					<th scope="col">จำนวนที่ผลิตได้จริง</th>
	                <th scope="col">หน่วย</th>
				</tr>
	        </thead>
			<tbody>
			<?php
			$sql = 'SELECT	
						production_product.*,
						SUM(quantity_receive) AS total_receive,
						product.name AS name,
						product.unit AS unit
							
					FROM production_product
					
					LEFT JOIN product
					ON production_product.id_product = product.id
					
					WHERE 
						production_product.type = 1
						AND id_log = '.$_GET['id'].'
						
					GROUP BY production_product.id_product';
	
			$result = mysql_query($sql) or die(mysql_error());
			$result_row = mysql_num_rows($result);
			
			$id_order = NULL;
	
			while($data = mysql_fetch_array($result))
			{
				$total_ordered[$data['id_product']] = $data['total_receive'];
				$total_produced[$data['id_product']] += $data['total_receive'];
				
				echo '<tr>
						<td>'.$data['name'].'</td>
						<td class="right">'.add_comma($data['total_receive']).'</td>
						<td>'.$data['unit'].'</td>
					  </tr>';
			}
			?>
			</tbody>
		</table>
		<h3>วัตถุดิบที่ต้องใช้</h3>
		<table>
			<thead>
				<tr>
					<th>วัตถุดิบ</th>
					<th>จำนวนที่ใช้</th>
					<th>หน่วย</th>
					<th>ต้นทุนรวม(บาท)</th>
				</tr>
			</thead>
			<tbody>
		    <!-- @formatter:off -->
            <?php
            //print_array($total_produced);
            
            $sql = 'SELECT * FROM material';
            $query = mysql_query($sql);
            
            while($material = mysql_fetch_array($query)): ?>
                
                <?php
				$sql = 'SELECT 
							ABS(quantity) AS quantity,
							(SELECT SUM(amount) FROM material_transaction WHERE stock_code = t1.stock_code AND id_material = t1.id_material) AS total_amount,
							(SELECT SUM(quantity) FROM material_transaction WHERE stock_code = t1.stock_code AND id_material = t1.id_material AND quantity > 0) AS total_quantity
						
						FROM material_transaction AS t1
						WHERE 
							id_material = '.$material['id'].'
							AND id_production_log = '.$production['id'];
							
                $result_cost = mysql_query($sql) or die(mysql_error);
				
				$material_cost[$material['id']] = 0;
				
				while($cost = mysql_fetch_assoc($result_cost))
				{
					$material_cost[$material['id']] += $cost['quantity'] * round($cost['total_amount'] / $cost['total_quantity'], 2);
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
                    
                    @$required_qty[$product['id']] += $total_produced[$product['id']] * $data['quantity'];
                }
				
				//print_array($required_qty);
				//echo array_sum($required_qty);
				?>
            	
				<tr>
					<td><?php echo $material['name'] ?></td>
					<td align="right"><?php echo add_comma(array_sum($required_qty)) ?></td>
					<td><?php echo $material['unit'] ?></td>
					<td align="right"><?php echo add_comma($material_cost[$material['id']]) ?></td>
				</tr>
				
			<?php endwhile ?>
            <!--@formatter:on-->
            <tr>
            	<td colspan="3" class="center">รวมเป็นเงิน</td>
            	<td class="right"><?php echo add_comma(array_sum($material_cost)) ?></td>
            </tr>
			</tbody>
		</table>
		<h3>ต้นทุนการผลิต</h3>
		<table>
	        <thead>
				<tr>
					<th scope="col">สินค้า</th>
					<th scope="col">จำนวนที่ผลิตได้จริง</th>
					<th scope="col">ราคาต้นทุน / หน่วย</th>
				</tr>
	        </thead>
			<tbody>
			<?php
			$sql = 'SELECT	
						production_product.*,
						SUM(quantity_receive) AS total_receive,
						product.name AS name,
						product.unit AS unit
							
					FROM production_product 
					
					LEFT JOIN product
					ON production_product.id_product = product.id
					
					WHERE id_log = '.$_GET['id'].'
                    
                    GROUP BY id_product';
	
			$result = mysql_query($sql) or die(mysql_error());
	
			while($product = mysql_fetch_array($result))
			{
				// Get total material used for this product
				$sql = 'SELECT *
                        FROM product_material
                        WHERE id_product = '.$product['id_product'];
								
                $result_pm = mysql_query($sql) or die(mysql_error);
				
				$total_cost = array();
				$total_material_used = array();
				$total_material_cost = array();
                
                while($data_pm = mysql_fetch_assoc($result_pm))
                {
					$material_used = $data_pm['quantity'] * $product['total_receive'];
					$total_material_used[$data_pm['id_material']] = $material_used;
					
					// Get material cost
					$sql = 'SELECT 
								ABS(quantity) AS quantity,
								(SELECT SUM(amount) FROM material_transaction WHERE stock_code = t1.stock_code AND id_material = t1.id_material) AS total_amount,
								(SELECT SUM(quantity) FROM material_transaction WHERE stock_code = t1.stock_code AND id_material = t1.id_material AND quantity > 0) AS total_quantity
							
							FROM material_transaction AS t1
							WHERE 
								id_material = '.$data_pm['id_material'].'
								AND id_production_log = '.$production['id'];
								
	                $result_cost = mysql_query($sql) or die(mysql_error);
					$result_cost_row = mysql_num_rows($result_cost);
					
					$material_cost[$material['id']] = 0;
					
					while($cost = mysql_fetch_assoc($result_cost))
					{
						$material_cost[$material['id']] += ($cost['total_amount'] / $cost['total_quantity']);
					}

					$total_material_cost[$data_pm['id_material']] = $material_cost[$material['id']] / $result_cost_row;
					
					$total_cost[$data_pm['id_material']] = $material_used * ($material_cost[$material['id']]/$result_cost_row);
                }
				
				//print_array($total_material_cost);
				//print_array($total_material_used);
				//print_array($total_cost);
				
				$average_cost_per_unit = round(array_sum($total_cost) / $product['total_receive'], 10);

				echo '<tr>
						<td>'.$product['name'].'</td>
						<td class="right">'.add_comma($product['total_receive']).'</td>
						<td class="right">'.add_comma($average_cost_per_unit).'</td>
					  </tr>';
			}
			?>
			</tbody>
		</table>
	    <h3>รายชื่อผู้ทำการผลิต</h3>
	    <table>
	        <thead>
	            <tr>
	                <th width="20">ลำดับ</th>
	                <th>ชื่อ-นามสกุล</th>
	                <th>เบอร์โทรศัพท์</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php            
	            $sql = 'SELECT *
	                    
	                    FROM production_member
	                    
	                    LEFT JOIN member
	                    ON production_member.id_worked_member = member.id
	                    
	                    WHERE 
	                    	id_log = '.$_GET['id'];
						
	            $result = mysql_query($sql) or die(mysql_error());
	            $loop = 1;
				
	            while($member = mysql_fetch_assoc($result)):
	            ?>
	                <tr>
	                    <td class="right"><?php echo $loop ?></td>
	                    <td><?php echo $member['name'] ?></td>
	                    <td><?php echo $member['tel'] ?></td>
	                </tr>
	            <?php
				$loop++;
				endwhile
	            ?>
	        </tbody>
	    </table>
	    <h3>หมายเหตุ</h3>
	    <textarea name="description" style="width:100%" readonly="readonly"><?php echo $production['description'] ?></textarea>
		<hr style="margin-top:25px" />
		<a href="material_order.php">กลับ</a> 
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>