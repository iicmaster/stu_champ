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
<title>คลังสินค้า</title>
<?php include("inc.css.php"); ?>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content"> 
		<h1>คลังสินค้า</h1>
		<hr />
		<table border="1" align="center" cellpadding="5" cellspacing="0">
			<thead>
				<tr>
					<th>รหัสสต็อค</th>
					<th>วันหมดอายุ</th>
					<?php 
					$query = 'SELECT name, unit FROM product';
					$result = mysql_query($query) or die(mysql_error());
					$total_product_type = mysql_num_rows($result);
					while($product = mysql_fetch_assoc($result)):
					?>
					<th><?php echo $product['name'] ?> (<?php echo $product['unit'] ?>)</th>
					<?php endwhile  ?>
					<th width="80">แก้ไข</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$sql = 'SELECT 
						*, 
						(
							SELECT COUNT(*) 
							FROM product_transaction 
							WHERE 
								type = 3 
								AND stock_code = t1.stock_code
						) AS is_delete
					FROM product_transaction AS t1
					GROUP BY stock_code
					ORDER BY stock_code
					LIMIT '.$limit_start.', '.$rows_per_page;  
					
			$query = mysql_query($sql) or die(mysql_error());  
			$query_rows = mysql_num_rows($query);
			?>
			<?php if($query_rows > 0): ?>
				<?php while($data = mysql_fetch_array($query)): ?>
				<?php
				$date_exp = change_date_format($data['date_exp']);
				$date_exp = (get_timestamp($data['date_exp']) < get_timestamp(date('Y-m-d')))
								? '<span class="bold red">'.change_date_format($data['date_exp']).'</span>'
								: $date_exp;
				?>
				<tr>
					<td class="center"><?php echo $data['stock_code'] ?></td>
					<td class="center"><?php echo $date_exp ?></td>
					<?php 
					$sql = 'SELECT 
								*, 
								(
									SELECT SUM(quantity) 
									FROM product_transaction 
									WHERE 
										id_product = t1.id_product
										AND stock_code = t1.stock_code
								) AS remain
								
							FROM product_transaction as t1
							
							WHERE
								stock_code = "'.$data['stock_code'].'"
								
							GROUP BY id_product';
								
					$result = mysql_query($sql) or die(mysql_error());
					$result_row = mysql_num_rows($result);
					$col_diff = $total_product_type - $result_row;
					while($product = mysql_fetch_assoc($result)):
					?>
					<td class="right"><?php echo add_comma($product['remain']) ?></td>
					<?php endwhile  ?>
					<?php if ($col_diff > 0): ?>
						<?php for ($i = 1; $i <= $col_diff; $i++): ?>
							<td class="right">0</td>
						<?php endfor ?>
					<?php endif ?>
					
					<td class="center nowarp">
						<a class="button" href="product_stock_view.php?stock_code=<?php echo $data['stock_code'] ?>">ดู</a>
						<?php if ($data['is_delete'] == 0): ?>
						<a class="button" href="product_stock_delete.php?stock_code=<?php echo $data['stock_code'] ?>">กำจัด</a> 
						<?php endif ?>
					</td>
				</tr>
				<?php endwhile ?>
			<?php else: ?>
				<tr><td colspan="6" class="center">ไม่มีข้อมูล</td></tr>
			<?php endif ?>
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