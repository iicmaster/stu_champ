<?php
require_once ("../include/session.php");
require ('../include/connect.php');
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
    				$product_restock = array();
                    $product_ordered = array();
    				$total_produced_qty = array();
    				$total_produced_weight = array();
                    
    				$sql = 'SELECT * FROM product';
    				$query = mysql_query($sql);
    				while($data = mysql_fetch_array($query)):
                        
                        $sql_order = 'SELECT SUM(quantity) AS order_qty 
                                      FROM product_order_item 
                                      WHERE id_product = '.$data['id'];
                                      
                        $query_order = mysql_query($sql_order);
                        
                        $order_qty = mysql_fetch_assoc($query_order);
                        $order_qty = ($order_qty['order_qty'] > 0) ? $order_qty['order_qty'] : 0;
                        $restock_qty = $data['stock_max'] - $data['total'];
                        
                        $product_restock[$data['id']] = $restock_qty;
                        $product_ordered[$data['id']] = $order_qty;
                        $total_produced_qty[$data['id']] = ($data['stock_max'] - $data['total']) + $order_qty;
                        $total_produced_weight[$data['id']] = $total_produced_qty[$data['id']] * $data['weight'];
    				?>
    				<tr>
    					<td><?php echo $data['name'] ?></td>
    					<td class="right"><?php echo $data['total'] ?></td>
    					<td class="right"><?php echo add_comma($restock_qty) ?></td>
                        <td class="right"><?php echo add_comma($order_qty) ?></td>
                        <td class="right"><?php echo add_comma($total_produced_qty[$data['id']]) ?></td>
    					<td><?php echo $data['unit'] ?></td>
    				</tr>
    				<?php endwhile ?>
    				<!--@formatter:on-->
				</tbody>
                <tfoot>
                    <tr>
                        <td class="center" colspan="4">รวมทั้งหมด</td>
                        <td class="right"><?php echo add_comma(array_sum($total_produced_qty)) ?></td>
                        <td>หน่วย</td>
                    </tr>
                </tfoot>
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
                            
                            $required_qty += $total_produced_qty[$product['id']] * $data['qty'];
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
            <textarea name="description" style="width:760px"></textarea>
		</div>
			<p class="center">
			    <?php foreach ($product_ordered as $key => $value): ?>
                    <input type="hidden" name="product_ordered[<?php echo $key ?>]" value="<?php echo $value ?>" />
                    <input type="hidden" name="product_restock[<?php echo $key ?>]" value="<?php echo $product_restock[$key] ?>" />
                <?php endforeach ?>
				<input type="submit" value="บันทึกการผลิต" />
			</p>
		</form>
	</div>
	<?php include ("inc.footer.php") ?>
</div>
</body>
</html>