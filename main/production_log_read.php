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
	                <th scope="col">สถานะการตรวจรับ</th>
				</tr>
	        </thead>
				<tr>
					<td class="center"><?php echo zero_fill(10, $production['id']) ?></td>
					<td class="center"><?php echo change_date_format($production['date_create']) ?></td>
					<td class="center"><?php echo change_date_format($production['date_work']) ?></td>
					<td class="center"><?php echo $is_approved ?></td>
				</tr>
			<tbody>
			</tbody>
		</table>
		<h3>จำนวนสินค้าที่ควรผลิตเพิ่ม</h3>
		<table>
	        <thead>
				<tr>
					<th scope="col">สินค้า</th>
					<th scope="col">จำนวนที่ควรผลิตเพิ่ม</th>
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
				$total_restock[$data['id_product']] = $data['quantity_order'];
				$total_produced[$data['id_product']] = $data['quantity_order'];
	
				echo '<tr>
						<td>'.$data['name'].'</td>
						<td class="right">'.add_comma($data['quantity_order']).'</td>
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
					<th scope="col">เลขที่ใบสั่งซื้อ</th>
					<th scope="col">วันที่นัดรับ</th>
					<?php 
					$query = 'SELECT name, unit FROM product';
					$result = mysql_query($query) or die(mysql_error());
					$total_product_type = mysql_num_rows($result);
					while($product = mysql_fetch_assoc($result)):
					?>
					<th scope="col"><?php echo $product['name'] ?> (<?php echo $product['unit'] ?>)</th>
					<?php endwhile ?>
					<th scope="col">รวม</th>
	                <th scope="col">หน่วย</th>
				</tr>
	        </thead>
			<tbody>
			<?php
			$sql = 'SELECT * 
					FROM product_order 
					WHERE id IN (SELECT id_order FROM production_product WHERE id_log = '.$_GET['id'].')
					ORDER BY id DESC';
	
			$result_order = mysql_query($sql) or die(mysql_error());
			?>
			<?php while($order = mysql_fetch_array($result_order)): ?>
				<tr>
					<td class="center"><?php echo zero_fill(10, $order['id']) ?></td>
					<td class="center"><?php echo change_date_format($order['date_receive']) ?></td>
					
					<?php
					$query = 'SELECT id, name, unit FROM product';
					$result = mysql_query($query) or die(mysql_error());
					?>
					
					<?php while($data = mysql_fetch_assoc($result)): ?>
						
						<?php
						$sql = 'SELECT	
									production_product.*,
									product.name AS name,
									product.unit AS unit,
									product.weight AS weight
										
								FROM product 
								
								LEFT JOIN production_product
								ON product.id = production_product.id_product
								
								WHERE 
									production_product.type = 1
									AND id_product = '.$data['id'].'
									AND id_order = '.$order['id'].'
									AND id_log = '.$_GET['id'];
		
						$result_product = mysql_query($sql) or die(mysql_error());
						$product = mysql_fetch_assoc($result_product);
						
						$ordered_list[$order['id']][$product['id_product']] = $product['quantity_order'];
						@$total_ordered[$product['id_product']] += $product['quantity_order'];
						@$total_produced[$product['id_product']] += $product['quantity_order'];
						?>	
						
			            <td class="right"> <?php echo add_comma($product['quantity_order']) ?> </td>
			            
		            <?php endwhile ?>
	                    
					<td class="right"><?php echo add_comma(array_sum($ordered_list[$order['id']])) ?></td>
					<td>หน่วย</td>
				</tr>
	        <?php endwhile ?>
			</tbody>
		</table>
		<h3>วัตถุดิบที่ต้องใช้</h3>
		<table>
			<thead>
				<tr>
					<th>วัตถุดิบ</th>
					<th>จำนวนที่ต้องใช้</th>
	                <th>จำนวนที่ต้องซื้อเพื่ม</th>
					<th>หน่วย</th>
				</tr>
			</thead>
			<tbody>
			    <!-- @formatter:off -->
	            <?php					
	            
				// --------------------------------------------------
				// Required material
				// --------------------------------------------------
				   
				// Get material                  
	            $sql = 'SELECT 
	                        id_material as id,
	                        name,
	                        total,
	                        unit
	                    
	                    FROM product_material
	                    
	                    LEFT JOIN material
	                    ON product_material.id_material = material.id
	                    
	                    GROUP BY id_material';
	                    
	            $query = mysql_query($sql);
	            
	            while($material = mysql_fetch_array($query)):
	                
	                $required_qty = 0;
	                $buy_qty = 0;
	                
					// Get required material per product
	                $sql = 'SELECT id FROM product';
	                $result = mysql_query($sql) or die(mysql_error());
	                
	                while($product = mysql_fetch_assoc($result))
	                {
	                    $sql = 'SELECT quantity as qty 
	                            FROM product_material
	                            WHERE
	                                id_product = '.$product['id'].'
	                                AND id_material = '.$material['id'];
									
	                    $result_pm = mysql_query($sql) or die(mysql_error);
	                    $data = mysql_fetch_assoc($result_pm);
	                    
	                    $required_qty += $total_produced[$product['id']] * $data['qty'];
	                }
	                
	                $buy_qty = $required_qty - $material['total'];
	                $buy_qty = ($buy_qty > 0) ? $buy_qty : 0;
	            ?>
				<tr>
					<td><?php echo $material['name'] ?></td>
					<td align="right"><?php echo add_comma($required_qty) ?></td>
	                <td align="right"><?php echo add_comma($buy_qty) ?></td>
					<td><?php echo $material['unit'] ?></td>
				</tr>
				<?php endwhile ?>
	            <!--@formatter:on-->
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
	                    ON production_member.id_assigned_member = member.id
	                    
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