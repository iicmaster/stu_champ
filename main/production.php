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
	sum_product_qty();

    $("#production_date").datepicker({
        dateFormat : 'yy-mm-dd'
    });

    $(':checkbox').click(function()
    {
    	sum_product_qty();
    })
}); 

function sum_product_qty () 
{
	var id_list = [1, 2, 3];
	var total = {
					1: 0,
					2: 0,
					3: 0
				};

	// Sum selected item
	$(':checked').each(function()
	{
		var root = $(this).parent().parent();

		$.each(id_list, function(key, product_id)
		{
			order_product_qty = parseInt(root.find('.order_product_'+product_id+'_qty').text());
			total[product_id] += order_product_qty;

			//console.log('order_product_'+product_id+'_qty', order_product_qty)	
			//console.log('total['+product_id+']', total[product_id])		
		});
	});	

	// Sum total item
	$.each(id_list, function(key, product_id)
	{
		product_qty = parseInt($('.product_'+product_id+'_qty').text());	
		total[product_id] += product_qty;

		// Display total
		$('#product_'+product_id+'_total').html(total[product_id]);

		//console.log('order_product_'+product_id+'_qty', order_product_qty)	
		//console.log('total['+product_id+']', total[product_id])		
	});

	//console.log(total)
}
</script>
</script>
</head>
<body>
<div id="container">
	<?php include ("inc.header.php") ?>
	<div id="content">
	
		<?php
		$sql = 'SELECT * 
				FROM production_log 
				ORDER BY id DESC 
				LIMIT 1' ;
    	$query = mysql_query($sql) or die(mysql_error());
    	$data = mysql_fetch_array($query);

		if($data['is_approved'] == 0)
		{
			echo '<h1>กรุณาตรวจรับการผลิตครั้งที่แล้วก่อน</h1> >>> <a href="production_log.php" class="button">ตรวจรับ</a> <<< ';
			exit();
		}
		?>
		
		
        <form method="post" action="production2.php">
        <p class="float_r" style="margin-bottom: 0">ประจำวันที่ : 
        	<input 
        		type="text" 
        		name="production_date" 
        		value="<?php echo date('Y-m-d') ?>" 
        		size="8" class="center" 
        		style="max-width:195px;min-width: 195px;width:195px;" 
        		readonly="readonly"
        	/>
        </p>
		<h1>คำนวนการผลิตสินค้า</h1>
		<hr />
		<div id="product_stock">
			<!--<h3>จำนวนสินค้าที่ควรผลิตเพิ่ม</h3>
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
								FROM product_transaction 
							
								WHERE 
									id_product = '.$data['id'].'
								 	AND type != 1';
								
						$result = mysql_query($sql) or die(mysql_error());
						$product_stock_data = mysql_fetch_assoc($result);
						
						$product_stock_qty = $product_stock_data['total'];
						
                        $restock_qty = $data['stock_max'] - $product_stock_data['total'];
						$restock_qty = ($restock_qty > 0) ? $restock_qty : 0;
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
				</tbody>
                <tfoot>
                    <tr>
                        <td class="center" colspan="2">รวมทั้งหมด</td>
                        <td class="right"><?php echo add_comma($total_restock) ?></td>
                        <td>ถ้วย</td>
                    </tr>
                </tfoot>
			</table>-->
		
			<table>
                <thead>
    				<tr>
    					<th scope="col" colspan="3">สินค้า</th>
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
                		<td	colspan="3">จำนวนที่ต้องผลิต</td>
    					<?php foreach ($product_restock_list as $key => $value): ?>
						<td class="right product_<?php echo $key ?>_qty"><?php echo $value ?></td>	
						<?php endforeach ?>
                	</tr>
                	<tr>
                		<td	colspan="2">เลขที่ใบสั่งซื้อ</td>
                		<td>วันที่นัดรับ</td>
                		<td	colspan="3"></td>
                	</tr>

				<?php 
				// --------------------------------------------------
				// Product order
				// --------------------------------------------------
				
				$product_ordered_list = array();
				$product_ordered_list2 = array();
				$total_ordered_weight = array();
				$total_ordered_product = 0;
                
				// Get product order
				$sql = 'SELECT * 
						FROM product_order 
						WHERE 
							id NOT IN (SELECT id_order FROM production_product WHERE type = 1)
							AND type = 0
						ORDER BY id';
						
				$result_order = mysql_query($sql) or die(mysql_error());
				$result_order_row = mysql_num_rows($result_order);
				?>

				<?php if ($result_order_row > 0): ?>
					<?php while($order = mysql_fetch_assoc($result_order)): ?>
	                    <?php
						// Get product order item
	                    $sql_order_item = 'SELECT *
	                                  	   FROM product_order_item
	                                  	   WHERE id_order = '.$order['id'];
	                                  
	                    $query_order_item = mysql_query($sql_order_item) or die(mysql_error());
						
						// Get order item array
	                    while($data_order_item = mysql_fetch_assoc($query_order_item))
						{
							$product_ordered_list[$order['id']][$data_order_item['id_product']] = $data_order_item['quantity'];
							@$product_ordered_list2[$data_order_item['id_product']] += $data_order_item['quantity'];
							$order_item[$order['id']][$data_order_item['id_product']] = $data_order_item['quantity'];
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
							if(isset($order_item[$order['id']][$product['id']]))
							{
								@$total_ordered_weight[$order['id']][$product['id']] += $order_item[$order['id']][$product['id']] * $product['weight']; 
								$total_produced_qty[$product['id']] += $order_item[$order['id']][$product['id']];
							}
							else 
							{
								$total_ordered_weight[$order['id']][$product['id']] = 0;
							}
							?>
                        <td class="right order_product_<?php echo $product['id'] ?>_qty">
                        	<?php 
                        	$item_quantity = (isset($order_item[$order['id']][$product['id']])) ? $order_item[$order['id']][$product['id']] : 0;
                        	echo add_comma($item_quantity);
                        	?>
                        </td>
                        <?php endwhile ?>
    				</tr>
				<?php endwhile ?>
				</tbody>
                <tfoot>
                    <tr>
                        <td class="center" colspan="3">รวมทั้งหมด</td>
    					<?php foreach ($product_restock_list as $key => $value): ?>
						<td class="right" id="product_<?php echo $key ?>_total"><?php echo $value + @$product_ordered_list2[$key] ?></td>	
						<?php endforeach ?>
                    </tr>
                </tfoot>
				<?php else: ?>
					<tr><td colspan="<?php echo (5 + $total_product_type) ?>" align="center">ไม่พบข้อมูล</td></tr>
				<?php endif ?>
			</table>
		</div>
		<p class="center">
		    <?php foreach($total_produced_qty as $id_product => $value): ?>
				<input type="hidden" name="total_restock_weight[<?php echo $id_product ?>]" value="<?php echo $total_produced_weight[$id_product] ?>" />
                <input type="hidden" name="product_restock_list[<?php echo $id_product ?>]" value="<?php echo $product_restock_list[$id_product] ?>" />
            <?php endforeach ?>
            
		    <?php foreach($product_ordered_list as $id_order => $order): ?>
		    	<?php foreach($order as $id_product => $product): ?>
				<input type="hidden" name="total_ordered_weight[<?php echo $id_order ?>][<?php echo $id_product ?>]" value="<?php echo $total_ordered_weight[$id_order][$id_product] ?>" />
                <input type="hidden" name="product_ordered_list[<?php echo $id_order ?>][<?php echo $id_product ?>]" value="<?php echo $product ?>" />
            	<?php endforeach ?>
            <?php endforeach ?>
			<input type="submit" name="submit" value="คำนวณการผลิต" />
		</p>
		</form>
	</div>
	<?php include ("inc.footer.php") ?>
</div>
</body>
</html>