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
$sql = 'SELECT * FROM material';
$query = mysql_query($sql); 
$total_rows = mysql_num_rows($query);

// Set date to display per page
$rows_per_page = 10;

// Set start query from					
$limit_start = ($_GET['page'] - 1) * $rows_per_page;

// Set pagination link target
$target = 'material.php?page=';

/* -------------------------------------------------------------------------------- */
/* End */
/* -------------------------------------------------------------------------------- */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>วัตถุดิบ</title>
<?php include("inc.css.php"); ?>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<script>
$(function() {
	$('form input:submit').click(function(){
		if($("input:checked").length == 0)
		{
			alert('กรุณาเลือกวิตถุดิบอย่างน้อย 1 ชนิด');
			
			return false;
		}
	});
});
</script>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<a href="material_create.php" class="float_r">เพื่มชนิดวัตถุดิบ</a>
		<h1>วัตถุดิบ</h1>
		<hr />
			<form method="post" action="material_order_create.php">
				<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
					<thead>
						<tr>
							<th scope="col">รหัส</th>
							<th scope="col">ชื่อวัตถุดิบ</th>
							<th scope="col">จำนวนคงเหลือ</th>
							<th scope="col">วันที่ทำรายการล่าสุด</th>
							<th scope="col">การดำเนินการ</th>
						</tr>
					</thead>
					<tbody>
						<?php 					
						$sql = 'SELECT * 
								FROM material 
								ORDER BY id DESC
								LIMIT '.$limit_start.', '.$rows_per_page;  
								
						$query = mysql_query($sql);
						$query_row = mysql_num_rows($query);
						
						if($query_row > 0)
						{
							while($data = mysql_fetch_array($query))
							{
								$date_update_transaction = (is_null($data['date_update_transaction'])) ? '-' : change_date_time_format($data['date_update_transaction']);
								
								echo '	<tr>
											<td class="center">'.zero_fill(4, $data['id']).'</td>
											<td>'.$data['name'].'</td>
											<td class="right">'.add_comma($data['total']).' '.$data['unit'].'</td>
											<td class="center">'.$date_update_transaction.'</td>
											<td class="center">
												<a class="button" href="material_view.php?id='.$data['id'].'">ดู</a>
												<a class="button" href="material_update.php?id='.$data['id'].'">แก้ไข</a>
												<a class="button" href="material_delete.php?id='.$data['id'].'">ลบ</a> 
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
			</form>
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
