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
	
	/* Select all */
	
	$('#select_all').click(function()
	{
		if($(this).attr('checked') == 'checked' || $(this).attr('checked') == true)
		{
			$('tbody').find('input[type=checkbox]').attr('checked', 'checked');
		}
		else
		{
			$('tbody').find('input[type=checkbox]').removeAttr('checked');
		}
	});	
	
	/* Form validation */
	
	$('form input:submit').click(function(){
		if($("tbody input:checked").length == 0)
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
		<h1>วัตถุดิบที่แนะนำให้ซื้อเพิ่ม</h1>
		<hr />
			<form method="post" action="material_order_create.php">
				<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
					<thead>
						<tr>
							<th scope="col"><input type="checkbox" id="select_all" /></th>
							<th scope="col">รหัส</th>
							<th scope="col">ชื่อวัตถุดิบ</th>
							<th scope="col">จำนวนคงเหลือ</th>
							<th scope="col">จำนวนที่แนะนำให้ซื้อเพิ่ม</th>
							<th scope="col">วันที่ทำรายการล่าสุด</th>
							<th scope="col">การดำเนินการ</th>
						</tr>
					</thead>
					<tbody>
						<?php 					
						$sql = 'SELECT * FROM material WHERE total < stock_min LIMIT '.$limit_start.', '.$rows_per_page;  
						$query = mysql_query($sql);
						$query_row = mysql_num_rows($query);
						
						if($query_row > 0)
						{
							while($data = mysql_fetch_array($query))
							{
								echo '	<tr>
											<td class="center"><input type="checkbox" name="id[]" value="'.$data['id'].'" /></td>
											<td class="center">'.zero_fill(4, $data['id']).'</td>
											<td>'.$data['name'].'</td>
											<td class="right">'.add_comma($data['total']).' ('.$data['unit'].')</td>
											<td class="right">'.add_comma(abs($data['total'] - $data['stock_min'])).' ('.$data['unit'].')</td>
											<td class="center">'.change_date_time_format($data['date_update_transaction']).'</td>
											<td class="center">
												<a class="button" href="material_view.php?id='.$data['id'].'">ดู</a>
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
				<input type="submit" name="create" value="ออกใบสั่งซื้อ" />
			</form>
		<hr style="margin-top:25px" />
		<div class="pagination">
			<?php echo get_pagination($total_rows, $target, $_GET['page'], $rows_per_page); ?>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>
