<?php
require ("../include/session.php");
require ('../include/connect.php');

//$ID_SUPPLIER = (isset($_GET['id_supplier'])) ? 'AND
// material_order_item.id_supplier = '.$_GET['id_supplier'] : '';

$sql = 'SELECT * FROM production_log WHERE id = "'.$_GET['id'].'"';
$query = mysql_query($sql) or die(mysql_error());
$production = mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Production log print</title>
<?php
include ("inc.css.php");
 ?>
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
		margin-bottom: 25px;
		width: 50%;
	}

	#paper th
	{
		color: #333;
		background: none;
	}

	#paper hr
	{
		border-style: solid;
	}

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

	h3
	{
		margin: 30px 0px 15px 0px;
	}
</style>
</head>

<body>
<div id="paper">
	<h1 class="center">ใบสั่งผลิต</h1>
	<h6 class="center"> 
		กลุ่มแม่บ้านบางกะจะ
		<br />
		หมู่ 4 ตำบลบางกะจะ อำเภอเมือง
		<br />จังหวัดจันทบุรี  22000
	</h6>
	<p class="float_r" >ใบสั่งผลิตเลขที่: <?php echo zero_fill(4, $_GET['id']); ?> 
	</br>วันที่ทำการผลิต: <?php echo change_date_format($production['date_work']) ?>
	</p><br/>
	<!-- <h3>จำนวนสินค้าที่ควรผลิตเพิ่ม</h3>
	<table>
        <thead>
			<tr>
				<th scope="col">สินค้า</th>
				<th scope="col">จำนวนที่ควรผลิตเพิ่ม</th>
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
		echo $sql;

		$result = mysql_query($sql) or die(mysql_error());
		$result_row = mysql_num_rows($result);

		while($data = mysql_fetch_array($result))
		{
			@$total_restock[$data['id_product']] += $data['quantity_order'];
			$total_produced[$data['id_product']] = $data['quantity_order'];

			echo '<tr>
					<td>'.$data['name'].'</td>
					<td class="right">'.add_comma($data['quantity_order']).'</td>
					<td>'.$data['unit'].'</td>
				  </tr>';
		}
		?>
		</tbody>
	</table> -->
	<h3>จำนวนสินค้า</h3>
	<table>
        <thead>
			<tr>
				<th scope="col" colspan="2">สินค้า</th>
				<?php 
				$query = 'SELECT name, unit FROM product';
				$result = mysql_query($query) or die(mysql_error());
				$total_product_type = mysql_num_rows($result);
				while($product = mysql_fetch_assoc($result)):
				?>
				<th scope="col"><?php echo $product['name'] ?> (<?php echo $product['unit'] ?>)</th>
				<?php endwhile ?>
			</tr>
        </thead>
		<tbody>
        	<tr>
        		<td	colspan="2">จำนวนที่ต้องผลิต</td>
				<?php for ($loop = 1; $loop <= $total_product_type; $loop++): ?>
				<td class="right product_<?php echo $loop ?>_qty"><?php echo @$total_restock[$loop] ?></td>	
				<?php endfor ?>
        	</tr>
        	<tr>
        		<td>เลขที่ใบสั่งซื้อ</td>
        		<td>วันที่นัดรับ</td>
        		<td	colspan="<?php echo $total_product_type ?>"></td>
        	</tr>
		<?php
		$sql = 'SELECT * 
				FROM product_order 
				WHERE id IN (SELECT id_order FROM production_product WHERE id_log = '.$_GET['id'].')
				ORDER BY id DESC';

		$result_order = mysql_query($sql) or die(mysql_error());
		$result_order_row = mysql_num_rows($result_order);
		?>

		<?php if ($result_order_row > 0): ?>
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
				</tr>
	        <?php endwhile ?>
		<?php else: ?>
			<tr><td colspan="<?php echo (4 + $total_product_type) ?>" align="center">ไม่พบข้อมูล</td></tr>
		<?php endif ?>
		</tbody>
		<?php if ($result_order_row > 0): ?>
        <tfoot>
            <tr>
                <td class="center" colspan="2">รวมทั้งหมด</td>
				<?php for ($loop = 1; $loop <= $total_product_type; $loop++): ?>
				<td class="right" id="product_<?php echo $loop ?>_total"><?php echo @$total_restock[$loop] + @$total_ordered[$loop] ?></td>	
				<?php endfor ?>
            </tr>
        </tfoot>
		<?php endif ?>
	</table>
	<h3>วัตถุดิบที่ต้องใช้</h3>
	<table>
		<thead>
			<tr>
				<th>วัตถุดิบ</th>
				<th>จำนวนที่ต้องใช้</th>
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
                    
                    $required_qty += @$total_produced[$product['id']] * $data['qty'];
                }
                
                $buy_qty = $required_qty - $material['total'];
                $buy_qty = ($buy_qty > 0) ? $buy_qty : 0;
            ?>
			<tr>
				<td><?php echo $material['name'] ?></td>
				<td align="right"><?php echo add_comma($required_qty) ?></td>
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
    <textarea name="description" style="width:100%; margin-bottom: 150px;" readonly="readonly"><?php echo $production['description'] ?></textarea>
	<div id="signature">
		..............................
		<br />
		(ผู้ดำเนินการ)
	</div>
</div>
</body>
</html>