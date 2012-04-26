<?php 
/* -------------------------------------------------------------------------------- */
/* Include */
/* -------------------------------------------------------------------------------- */

require_once("../include/session.php"); 
require_once("../include/connect.php");

/* -------------------------------------------------------------------------------- */
/* Setup pagination */
/* -------------------------------------------------------------------------------- */

// Check page
$_GET['page'] = (isset($_GET['page'])) ? $_GET['page'] : 1;

// Get total rows
$sql		= 'SELECT * FROM product_order';
$query		= mysql_query($sql) or die(mysql_error()); 
$total_rows = mysql_num_rows($query);

// Set date to display per page
$rows_per_page = 10;

// Set start query from					
$limit_start = ($_GET['page'] - 1) * $rows_per_page;

// Set pagination link target
$target = 'product_order.php?page=';

/* -------------------------------------------------------------------------------- */
/* End */
/* -------------------------------------------------------------------------------- */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>การสั่งซื้อจากลูกค้า</title>
<?php include("inc.css.php"); ?>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<a href="product_order_create.php" class="float_r">ออกใบสั่งซื้อ</a>
		<h1>การสั่งซื้อจากลูกค้า</h1>
		<hr />
			<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
				<thead>
					<tr>
						<th width="30">รหัส</th>
						<th width="80">วันที่ทำรายการ</th>
						<th>ผู้สั่ง</th>
						<th width="80">สถานะ</th>
						<th width="100">การดำเนินการ</th>
					</tr>
				</thead>
				<tbody>
					<?php 					
					$sql = 'SELECT *
							FROM product_order 
							ORDER BY product_order.id DESC
							LIMIT '.$limit_start.', '.$rows_per_page;  
							
					$query = mysql_query($sql) or die(mysql_error());
					$query_rows	= mysql_num_rows($query);
					
					if($query_rows > 0)
					{
						while($data = mysql_fetch_array($query))
						{
							$status = ($data['is_receive'] == 0) ? '<span class="red">ยังไม่ได้รับสินค้า</span>' : '<span class="green">รับสินค้าแล้ว</span>';
							
							echo '	<tr>
										<td class="center">'.zero_fill(4, $data['id']).'</td>
										<td class="center">'.change_date_format($data['date_create']).'</td>
										<td>'.$data['orderer'].'</td>
										<td class="center">'.$status.'</td>
										<td class="center nowarp">
											<a class="button" href="product_order_read.php?id='.$data['id'].'">ดู</a>
											<a class="button" href="product_order_confirm_receive.php?id='.$data['id'].'">ยืนยันการรับสินค้า</a> 
											<a class="button" href="product_order_delete.php?id='.$data['id'].'">ยกเลิก</a>
										</td>
									</tr>';
						}
					}
					else
					{
						echo '<tr><td colspan="7" class="center">ไม่มีข้อมูล</td></tr>';
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
