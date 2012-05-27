<?php
require_once("../include/session.php");
require('../include/connect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>คำนวนการผลิตสินค้า - ระบบจัดการผลิตและจำหน่ายสละลอยแก้ว</title>
<style type="text/css">
#product_stock h3 { margin-bottom: 10px; font-size: medium; }
</style>
<?php include ("inc.css.php") ?>
<!-- jQuery -->
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<!-- jQuery - UI -->
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<script type="text/javascript">
$(function()
{
    $("#production_date").datepicker({
        dateFormat : 'yy-mm-dd'
    });
}); 
</script>
</script>
</head>
<body>
<div id="container">
	<?php include ("inc.header.php") ?>
	<div id="content">
        <form method="post" action="production_log_create.php">
        <p class="float_r" style="margin-bottom: 0">ประจำวันที่ : <input type="text" id="production_date" name="production_date" value="<?php echo date('Y-m-d') ?>" size="8" class="center" style="max-width:195px;min-width: 195px;width:195px;" /></p>
		<h1>คำนวนการผลิตสินค้า</h1>
		<hr />
		<div id="product_stock">
			<h3>จำนวนสินค้าที่ควรผลิตเพิ่ม</h3>
			<table>
                <thead>
    				<tr>
    					<th scope="col">สินค้า</th>
    					<th scope="col">จำนวนคงเหลือ</th>
    					<th scope="col">จำนวนที่ควรผลิตเพิ่ม</th>
                        <th scope="col">หน่วย</th>
    				</tr>
                </thead>
                <tbody>
    				<!-- @formatter:off -->
    				<?php 
    				$total_produced_qty = array();
    				$total_produced_weight = array();
					
    				// --------------------------------------------------
    				// Product restock
    				// --------------------------------------------------
    				
    				$product_restock_list = array();
					$total_restock = 0;
                    
    				$sql = 'SELECT * FROM product';
    				$query = mysql_query($sql) or die(mysql_error());
					
    				while($data = mysql_fetch_array($query)):
						
						$sql = 'SELECT SUM(quantity) AS total 
								FROM product_stock 
								WHERE id_product = '.$data['id'];
								
						$result = mysql_query($sql) or die(mysql_error());
						
						$product_stock_data = mysql_fetch_assoc($result);
						$product_stock_qty = $product_stock_data['total'];
						
                        $restock_qty = $data['stock_max'] - $data['total'];
						$total_restock += $restock_qty;
                        $product_restock_list[$data['id']] = $restock_qty;
						
						$total_produced_qty[$data['id']] = $restock_qty;
						$total_produced_weight[$data['id']] = $restock_qty * $data['weight'];
    				?>
    				<tr>
    					<td><?php echo $data['name'] ?></td>
    					<td class="right"><?php echo add_comma($product_stock_qty) ?></td>
    					<td class="right"><?php echo add_comma($restock_qty) ?></td>
    					<td><?php echo $data['unit'] ?></td>
    				</tr>
    				<?php endwhile ?>
    				<!--@formatter:on-->
				</tbody>
                <tfoot>
                    <tr>
                        <td class="center" colspan="2">รวมทั้งหมด</td>
                        <td class="right"><?php echo add_comma($total_restock) ?></td>
                        <td>หน่วย</td>
                    </tr>
                </tfoot>
			</table>
			<h3>จำนวนสินค้าที่มีการสั่งจากลูกค้า</h3>
			<table>
                <thead>
    				<tr>
    					<th scope="col"></th>
    					<th scope="col">เลขที่ใบสั่งซื้อ</th>
    					<th scope="col">วันที่นัดรับ</th>
    					<?php 
    					$query = 'SELECT name, unit FROM product';
    					$result = mysql_query($query) or die(mysql_error());
						while($product = mysql_fetch_assoc($result)):
						?>
    					<th scope="col"><?php echo $product['name'] ?> (<?php echo $product['unit'] ?>)</th>
    					<?php endwhile  ?>
    					<th scope="col">รวม</th>
                        <th scope="col">หน่วย</th>
    				</tr>
                </thead>
                <tbody>
				<!-- @formatter:off -->
				<?php 
				// --------------------------------------------------
				// Product order
				// --------------------------------------------------
				
                $product_order = array();
				$total_ordered_product = 0;
                
				// Get product order
				$sql = 'SELECT * FROM product_order ORDER BY id DESC';
				$result_order = mysql_query($sql) or die(mysql_error());
				
				while($order = mysql_fetch_assoc($result_order)):
                    
					// Get product order item
                    $sql_order_item = 'SELECT *
                                  	   FROM product_order_item
                                  	   WHERE id_order = '.$order['id'];
                                  
                    $query_order_item = mysql_query($sql_order_item) or die(mysql_error());
					
					// Gen order item array
                    while($data_order_item = mysql_fetch_assoc($query_order_item))
					{
						$order_item[$data_order_item['id_product']] = $data_order_item['quantity'];
					}
					
					$total_ordered_product += array_sum($order_item);
                ?>
    				<tr>
    					<td align="center"><input type="checkbox" name="id_order[]" checked="checked" value="<?php echo $order['id'] ?>" /></td>
    					<td align="center"><a target="_blank" href="product_order_read.php?id=<?php echo $order['id'] ?>"><?php echo zero_fill(10, $order['id']) ?></a></td>
    					<td align="center" nowrap="nowrap"><?php echo change_date_format($order['date_receive']) ?></td>
    					
    					<?php 
    					$query = 'SELECT id, name, weight FROM product';
    					$result = mysql_query($query) or die(mysql_error());
						?>
    					<?php while($product = mysql_fetch_assoc($result)): ?>
							<?php 
							if(isset($order_item[$product['id']]))
							{
								$total_produced_weight[$data['id']] = $order_item[$product['id']] * $product['weight']; 
								$total_produced_qty[$product['id']] += $order_item[$product['id']];
							}
							?>
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
                        
                        <td class="right"><?php echo add_comma(array_sum($order_item)) ?></td>
    					<td>หน่วย</td>
    				</tr>
				<?php endwhile ?>
				<!--@formatter:on-->
				</tbody>
                <tfoot>
                    <tr>
                        <td class="center" colspan="6">รวมทั้งหมด</td>
                        <td class="right"><?php echo add_comma($total_ordered_product) ?></td>
                        <td>หน่วย</td>
                    </tr>
                </tfoot>
			</table>
			<a href="material_stock.php" class="float_r">ออกใบสั่งซื้อวัตถุดิบ</a>
			<h3>วัตถุดิบที่ต้องใช้</h3>
			<table>
				<thead>
					<tr>
						<th>วัตถุดิบ</th>
                        <th>จำนวนคงเหลือ</th>
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
                            
                            $required_qty += $total_produced_qty[$product['id']] * $data['qty'];
                        }
                        
                        $buy_qty = $required_qty - $material['total'];
                        $buy_qty = ($buy_qty > 0) ? $buy_qty : 0;
                    ?>
					<tr>
						<td><?php echo $material['name'] ?></td>
                        <td align="right"><?php echo add_comma($material['total']) ?></td>
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
                        <th>ชื่อ</th>
                        <th>เบอร์โทรศัพท์</th>
                    </tr>
                </thead>
                <tbody>
                	<?php
    				// --------------------------------------------------
    				// Worker
    				// --------------------------------------------------
	                $total_worker = round(array_sum($total_produced_weight) / 15000);
	                
	                $query = 'SELECT * FROM member';
	                $result_member = mysql_query($query);
	                
	                // Create member list
	                $query = 'SELECT * FROM member LIMIT '.$total_worker;
	                $result = mysql_query($query);
	                
	                $loop = 1;
	                while($member = mysql_fetch_assoc($result)):
	                ?>
	                    <tr>
	                        <td class="right"><?php echo $loop ?></td>
	                        <td>
	                            <select id="id_member_<?php echo $member['id'] ?>" name="id_member[]">
	                            <?php 
	                            $selected = '';
	                            while($option = mysql_fetch_assoc($result_member))
	                            {
	                                $selected = ($option['id'] == $member['id']) ? 'selected="selected"' : '';
	                                echo '<option value="'.$option['id'].'" '.$selected.'>'.$option['name'].'</option>';
	                            }
	                            ?>
	                            </select>
	                        </td>
	                        <td><?php echo $member['tel'] ?></td>
	                    </tr>
	                <?php 
	                mysql_data_seek($result_member, 0);
	                $loop++;
	                endwhile 
	                ?>
                </tbody>
            </table>
            <h3>หมายเหตุ</h3>
            <textarea name="description" style="width:750px"></textarea>
		</div>
			<p class="center">
			    <?php foreach ($product_order as $key => $value): ?>
                    <input type="hidden" name="product_ordered[<?php echo $key ?>]" value="<?php echo $value ?>" />
                    <input type="hidden" name="product_restock[<?php echo $key ?>]" value="<?php echo $product_restock_list[$key] ?>" />
                <?php endforeach ?>
				<input type="submit" name="submit" value="บันทึกการผลิต" />
			</p>
		</form>
	</div>
	<?php include ("inc.footer.php") ?>
</div>
</body>
</html>