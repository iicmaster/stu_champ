<?php 
/* -------------------------------------------------------------------------------- */
/* Include */
/* -------------------------------------------------------------------------------- */

require_once("../include/session.php"); 
require_once('../include/connect.php'); 	

/* -------------------------------------------------------------------------------- */
/* Setup pagination */
/* -------------------------------------------------------------------------------- */

// Check page
$_GET['page'] = (isset($_GET['page'])) ? $_GET['page'] : 1;

// Get total rows
$sql		= 'SELECT * FROM product';
$query		= mysql_query($sql) or die(mysql_error());  
$total_rows = mysql_num_rows($query);

// Set date to display per page
$rows_per_page = 10;

// Set start query from					
$limit_start = ($_GET['page'] - 1) * $rows_per_page;

// Set pagination link target
$target = 'product.php?page=';

/* -------------------------------------------------------------------------------- */
/* End */
/* -------------------------------------------------------------------------------- */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>สินค้า</title>
<?php include("inc.css.php"); ?>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content"> 
		<a href="product_create.php" class="float_r">เพิ่มสินค้า</a>
		<h1>สินค้า</h1>
		<hr />
		<table border="1" align="center" cellpadding="5" cellspacing="0">
			<thead>
				<tr>
					<th width="30">รหัส</th>
					<th>ชื่อ</th>
					<th>สินค้าคลัง</th>
					<th>สินค้าส่งมอบ</th>
					<th>หน่วย</th>
					<th>ปรับปรุงล่าสุด</th>
					<th width="80">แก้ไข</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$sql = 'SELECT 
							*,
							(SELECT SUM(quantity) FROM product_transaction WHERE id_product = t1.id AND type != 1 ) AS "stock_remain"
							,(SELECT SUM(quantity) FROM product_transaction WHERE id_product = t1.id AND type = 1 ) AS "stock_re"
						FROM product AS t1
						LIMIT '.$limit_start.', '.$rows_per_page;  
				$query = mysql_query($sql) or die(mysql_error());  
				$query_rows = mysql_num_rows($query);
				
				if($query_rows > 0)
				{
					while($data = mysql_fetch_array($query))
					{
						$data['stock_remain'] = ($data['stock_remain'] == '') ? 0 : $data['stock_remain'];
						
						echo 	'<tr>
									<td width="30" class="right">'.zero_fill(4, $data['id']).'</td>
									<td>'.$data['name'].'</td>
									<td class="right">'.$data['stock_remain'].'</td>
									<td class="right">'.$data['stock_re'].'</td>
									<td>'.$data['unit'].'</td>
									<td class="center">'.change_date_time_format($data['date_update']).'</td>
									<td class="center nowarp">
										<a class="button" href="product_update.php?id='.$data['id'].'">แก้ไข</a>
										<a class="button" href="product_delete.php?id='.$data['id'].'">ลบ</a> 
									</td>
								</tr>';
					}
				}
				else
				{
					echo '<tr><td colspan="4" class="center">ไม่มีข้อมูล</td></tr>';
				}
				?>
			</tbody>
		</table>
		<hr />
		<div class="pagination">
			<?php echo get_pagination($total_rows, $target, $_GET['page'], $rows_per_page); ?>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>