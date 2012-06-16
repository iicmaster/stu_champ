<?php
require_once("../include/session.php");
require('../include/connect.php');

//print_array($_POST);
//exit();

$total_ordered = array();

if(isset($_POST['id_order']))
{
	foreach($_POST['id_order'] as $id_order) 
	{
		foreach($_POST['product_ordered_list'][$id_order] as $id_product => $ordered_qty)
		{
			$total_ordered[$id_product] = (isset($total_ordered[$id_product])) ? $total_ordered[$id_product] : 0;
			$total_ordered[$id_product] += $ordered_qty;
			$product_ordered[$id_order][$id_product] = $ordered_qty;
		}
	}
}

$total_produced = array();

foreach($_POST['product_restock_list'] as $id_product => $qty) 
{
	$total_ordered[$id_product] = (isset($total_ordered[$id_product])) ? $total_ordered[$id_product] : 0;
	$total_produced[$id_product] = $_POST['product_restock_list'][$id_product] + $total_ordered[$id_product];
}


//$total_produced_weight = $_POST['total_produced_weight'];
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
                            
                            $required_qty += $total_produced[$product['id']] * $data['qty'];
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
	                $total_worker = round(array_sum($_POST['total_produced_weight']) / 15000);
	                
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
			    <?php foreach($product_ordered as $id_order => $order): ?>
			    	<?php foreach($order as $id_product => $qty): ?>
                    <input type="hidden" name="product_ordered[<?php echo $id_order ?>][<?php echo $id_product ?>]" value="<?php echo $qty ?>" />
                	<?php endforeach ?>
                <?php endforeach ?>
                
                
			    <?php foreach ($_POST['product_restock_list'] as $key => $value): ?>
                    <input type="hidden" name="product_restock[<?php echo $key ?>]" value="<?php echo $value ?>" />
                <?php endforeach ?>
				<input type="submit" name="submit" value="บันทึกการผลิต" />
			</p>
		</form>
	</div>
	<?php include ("inc.footer.php") ?>
</div>
</body>
</html>