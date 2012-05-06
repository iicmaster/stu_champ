<?php
require_once ("../include/session.php");
require ('../include/connect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบจัดการผลิตและจำหน่ายสละลอยแก้ว</title>
<style type="text/css">
#product_stock h3 { margin-bottom: 10px; }
</style>
<?php include ("inc.css.php") ?>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript">
$(function()
{

});
</script>
</head>
<body>
<div id="container">
	<?php include ("inc.header.php") ?>
	<div id="content">
		<h1>คำนวนการผลิต</h1>
		<hr />
		<div id="product_stock">
			<h3>จำนวนสินค้าที่ต้องผลิต</h3>
			<table>
                <thead>
    				<tr>
    					<th scope="col">สินค้า</th>
    					<th scope="col">จำนวนคงเหลือ</th>
    					<th scope="col">จำนวนที่ควรผลิตเพิ่ม</th>
    					<th scope="col">ยอดการสั่งซื้อสินค้า</th>
    					<th scope="col">รวมสินค้าที่ต้องผลิตทั้งหมด</th>
                        <th scope="col">หน่วย</th>
    				</tr>
                </thead>
                <tbody>
    				<!-- @formatter:off -->
    				<?php 
    				$total_manufactured = array();
                    
    				$sql = 'SELECT * FROM product';
    				$query = mysql_query($sql);
    				while($data = mysql_fetch_array($query)):
                        
                        $sql_order = 'SELECT SUM(quantity) AS order_qty 
                                      FROM product_order_item 
                                      WHERE id_product = '.$data['id'];
                                      
                        $query_order = mysql_query($sql_order);
                        
                        $order_qty = mysql_fetch_assoc($query_order);
                        $order_qty = ($order_qty['order_qty'] > 0) ? $order_qty['order_qty'] : 0;
                        $total_manufactured[$data['id']] = ($data['stock_max'] - $data['total']) + $order_qty;
    				?>
    				<tr>
    					<td><?php echo $data['name'] ?></td>
    					<td class="right"><?php echo $data['total'] ?></td>
    					<td class="right"><?php echo ($data['stock_max'] - $data['total']) ?></td>
                        <td class="right"><?php echo $order_qty ?></td>
                        <td class="right"><?php echo add_comma($total_manufactured[$data['id']]) ?></td>
    					<td><?php echo $data['unit'] ?></td>
    				</tr>
    				<?php endwhile ?>
    				<!--@formatter:on-->
				</tbody>
			</table>
			<h3>วัตถุดิบที่ต้องใช้</h3>
			<table>
				<thead>
					<tr>
						<th>วัตถุดิบ</th>
						<th>จำนวนที่ต้องใช้</th>
                        <th>จำนวนคงเหลือ</th>
                        <th>จำนวนที่ต้องซื้อเพื่ม</th>
						<th>หน่วย</th>
					</tr>
				</thead>
				<tbody>
				    <!-- @formatter:off -->
                    <?php                    
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
                        
                        $sql = 'SELECT id FROM product';
                        $result = mysql_query($sql);
                        
                        while($product = mysql_fetch_assoc($result))
                        {
                            $sql = 'SELECT quantity as qty 
                                    FROM product_material
                                    WHERE
                                        id_product = '.$product['id'].'
                                        AND id_material = '.$material['id'];
                            $result_pm = mysql_query($sql) or die(mysql_error);
                            $data = mysql_fetch_assoc($result_pm);
                            
                            $required_qty += $total_manufactured[$product['id']] * $data['qty'];
                        }
                        
                        $buy_qty = $required_qty - $material['total'];
                        $buy_qty = ($buy_qty > 0) ? $buy_qty : 0;
                        
                    ?>
					<tr>
						<td><?php echo $material['name'] ?></td>
						<td align="right"><?php echo add_comma($required_qty) ?></td>
                        <td align="right"><?php echo add_comma($material['total']) ?></td>
                        <td align="right"><?php echo add_comma($buy_qty) ?></td>
						<td><?php echo $material['unit'] ?></td>
					</tr>
    				<?php endwhile ?>
                    <!--@formatter:on-->
				</tbody>
			</table>
		</div>
		<form method="get" action="production_queue_add.php">
			<p class="center">
				<input id="submit" name="submit" type="submit" value="จัดคิวทำงาน" />
			</p>
		</form>
	</div>
	<?php include ("inc.footer.php") ?>
</div>
</body>
</html>