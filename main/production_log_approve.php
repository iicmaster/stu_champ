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
form table tr td input[type=text]
{
	width: 180px;
	min-width: 180px;
	font-size: 12px;
}
input, table tr td select, textarea { font-size: 12px;}
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
		<form method="post" action="production_log_approve2.php">
			<h3>รายละเอียด</h3>
			<table>
		        <thead>
					<tr>
						<th scope="col">เลขที่</th>
						<th scope="col">วันที่ออกใบผลิต</th>
						<th scope="col">วันที่ผลิต</th>
		          
					</tr>
		        </thead>
					<tr>
						<td class="center"><?php echo zero_fill(10, $production['id']) ?></td>
						<td clas s="center"><?php echo change_date_format($production['date_create']) ?></td>
						<td class="center"><?php echo change_date_format($production['date_work']) ?></td>
			
					</tr>
				<tbody>
				</tbody>
			</table>
			<h3>จำนวนสินค้าที่ควรผลิตเพิ่ม</h3>
			<table>
		        <thead>
					<tr>
						<th scope="col">สินค้า</th>
						<th scope="col" width="25%">จำนวนที่ควรผลิตเพิ่ม</th>
						<th scope="col" width="25%">จำนวนที่ผลิตได้จริง</th>
		                <th scope="col" width="50">หน่วย</th>
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
							<td class="right"><input type="text" class="right" name="product_restock_approved['.$data['id_product'].']" value="'.$data['quantity_order'].'"/></td>
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
						<th scope="col">รหัสใบสั่งซื้อ</th>
						<th scope="col">สินค้า</th>
						<th scope="col" width="25%">จำนวนที่ควรผลิตเพิ่ม</th>
						<th scope="col" width="25%">จำนวนที่ผลิตได้จริง</th>
		                <th scope="col">หน่วย</th>
					</tr>
		        </thead>
				<tbody>
				<?php
				$sql = 'SELECT	
							t1.*,
							(SELECT COUNT(id_order) FROM production_product WHERE id_order = t1.id_order ) AS "total_product_type",
							product.name AS name,
							product.unit AS unit
								
						FROM production_product AS t1
						
						LEFT JOIN product
						ON t1.id_product = product.id
						
						WHERE 
							t1.type = 1
							AND id_log = '.$_GET['id'];
		
				$result = mysql_query($sql) or die(mysql_error());
				$result_row = mysql_num_rows($result);
				
				$id_order = NULL;
		
			if($result_row > 0)
			{
				while($data = mysql_fetch_array($result))
				{
					$total_restock[$data['id_product']] = $data['quantity_order'];
					$total_produced[$data['id_product']] += $data['quantity_order'];
					
					if($id_order == $data['id_order'])
					{
						$column_id_order = '';
					}
					else 
					{
						$id_order = $data['id_order'];
						$column_id_order = '<td rowspan="'.$data['total_product_type'].'" class="center">'.zero_fill(10, $data['id_order']).'</td>';
					}
		
					echo '<tr>
							'.$column_id_order.'
							<td>'.$data['name'].'</td>
							<td class="right">'.add_comma($data['quantity_order']).'</td>
							<td class="right"><input type="text" class="right" name="product_ordered_approved['.$data['id_order'].']['.$data['id_product'].']" value="'.$data['quantity_order'].'"/></td>
							<td>'.$data['unit'].'</td>
						  </tr>';
				}
				
			}
			else 
			{
				echo '<tr><td colspan="5" class="center">ไม่มีข้อมูล</td></tr>';
			}
				?>
				</tbody>
			</table>
		    <h3>รายชื่อผู้ทำการผลิต</h3>
		    <table>
		        <thead>
		            <tr>
		                <th width="20">ลำดับ</th>
		                <th>ผู้ที่ได้รับมอบหมาย</th>
		                <th>เบอร์โทรศัพท์</th>
		                <th>ผู้ที่มาทำงานจริง</th>
		                <th>เบอร์โทรศัพท์</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php            
		            $sql = 'SELECT *
		                    
		                    FROM production_member
		                    
		                    LEFT JOIN member
		                    ON production_member.id_assigned_member = member.id
		                    
		                    WHERE id_log = '.$_GET['id'];
							
		            $result = mysql_query($sql) or die(mysql_error());
		            $loop = 1;
					
		            while($member = mysql_fetch_assoc($result)):
		            ?>
		                <tr>
		                    <td class="right"><?php echo $loop ?></td>
		                    <td><?php echo $member['name'] ?></td>
		                    <td><?php echo $member['tel'] ?></td>
	                        <td>
	                            <select id="id_member_<?php echo $member['id'] ?>" name="id_member_approved[<?php echo $member['id'] ?>]">
		                            <?php 
					                $query = 'SELECT * FROM member';
					                $result_member = mysql_query($query);
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
					$loop++;
					endwhile
		            ?>
		        </tbody>
		    </table>
		    <h3>หมายเหตุ</h3>
		    <textarea style="width:100%" readonly="readonly"><?php echo $production['description'] ?></textarea>
		    <p class="center">                
		    	<input type="hidden" name="id_production_log" value="<?php echo $production['id'] ?>" id="id_production_log"/>
		    	<input type="submit" name="submit" value="ตรวจรับสินค้า" />
		    </p>
	    </form>
		<hr style="margin-top:25px" />
		<a href="material_order.php">กลับ</a> 
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>