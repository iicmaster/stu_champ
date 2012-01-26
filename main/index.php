<?php 
require_once("../include/session.php"); 
include_once('../include/connect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบจัดการผลิตและจำหน่ายสละลอยแก้ว</title>
<?php include("inc.css.php"); ?>
<style type="text/css">
h2 { margin-top: 15px; }

form { width: 100%; }
</style>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<script>
$(function() {
	$("#tabs").tabs();
	
	$('#tabs-2 input:submit').click(function(){
		if($("#tabs-2 input:checked").length == 0)
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
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">สินค้าคงเหลือ</a></li>
				<li><a href="#tabs-2">วัตถุดิบคงเหลือ</a></li>
				<li><a href="#tabs-3">รายการสั่งซื้อสินค้า</a></li>
				<li><a href="#tabs-4">คิวงาน</a></li>
			</ul>
			<div id="tabs-1">
				<h2>สินค้าคงเหลือ</h2>
				<hr />
				<table width="100%" class="table">
					<thead>
						<tr>
							<th scope="col">รหัส</th>
							<th scope="col">ชื่อสินค้า</th>
							<th scope="col">จำนวนคงเหลือ</th>
							<th scope="col">จำนวนที่แนะนำให้ผลิตเพิ่ม</th>
							<th scope="col">หน่วย</th>
							<th scope="col">การดำเนินการ</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$sql = 'SELECT * FROM product';  
						$query = mysql_query($sql); 
						$query_rows = mysql_num_rows($query);
						
						if($query_rows > 0)
						{
							while($data = mysql_fetch_array($query))
							{
								echo 	'<tr>
											<td width="30" class="right">'.zero_fill(4, $data['id']).'</td>
											<td>'.$data['name'].'</td>
											<td class="right">'.$data['total'].'</td>
											<td class="right">'.($data['stock_max'] - $data['total']).'</td>
											<td>'.$data['unit'].'</td>
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
			</div>
			<div id="tabs-2">
				<h2>วัตถุดิบคงเหลือ</h2>
				<hr />
				<form method="post" action="material_order_create.php">
					<table width="100%" class="table">
						<thead>
							<tr>
								<th scope="col"><input type="checkbox" id="select_all" /></th>
								<th scope="col">รหัส</th>
								<th scope="col">ชื่อวัตถุดิบ</th>
								<th scope="col">จำนวนคงเหลือ</th>
								<th scope="col">จำนวนที่แนะนำให้ซื้อเพิ่ม</th>
								<th scope="col">การดำเนินการ</th>
							</tr>
						</thead>
						<tbody>
							<?php 					
							$sql = 'SELECT * FROM material';
							if(! $query = mysql_query($sql))
							{
								echo mysql_error();
							}
							$query_row = mysql_num_rows($query);
							
							if($query_row > 0)
							{
								while($data = mysql_fetch_array($query))
								{
									$total = ($data['total'] < $data['stock_min']) ? add_comma($data['stock_min'] - $data['total']) : 0;
									
									echo '	<tr>
												<td class="center"><input type="checkbox" id="id_'.$data['id'].'" name="id[]" value="'.$data['id'].'" /></td>
												<td class="center">'.zero_fill(4, $data['id']).'</td>
												<td>'.$data['name'].'</td>
												<td class="right">'.add_comma($data['total']).' ('.$data['unit'].')</td>
												<td class="right">'.$total.' ('.$data['unit'].')</td>
												<td class="center">
													<a class="button" href="material_view.php?id='.$data['id'].'">ดู</a>
												</td>
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
					<input type="submit" name="create" value="ออกใบสั่งซื้อ" />
				</form>
			</div>
			<div id="tabs-3">
				<h2>รายการสั่งซื้อสินค้า</h2>
				<hr />
				<table width="100%" class="table">
					<thead>
						<tr>
							<th scope="col">รหัส</th>
							<th scope="col">ชื่อสินค้า</th>
							<th scope="col">จำนวน</th>
							<th scope="col">รวมเป็นเงิน</th>
							<th scope="col">ชื่อลูกค้า</th>
							<th scope="col">ชื่อผู้รับเรื่อง</th>
							<th scope="col">การดำเนินการ</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="tabs-4">
				<h2>คิวงาน</h2>
				<hr />
				<h3>ประจำวันที่: dd / mm / yyyy </h3>
				<table width="100%" class="table">
					<thead>
						<tr>
							<th scope="col">รหัส</th>
							<th scope="col">ขื่อสมาชิก</th>
							<th scope="col">เบอร์โทรศัพท์</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
					</tbody>
				</table>
				<h3>ประจำวันที่: dd / mm / yyyy </h3>
				<table width="100%" class="table">
					<thead>
						<tr>
							<th scope="col">รหัส</th>
							<th scope="col">ขื่อสมาชิก</th>
							<th scope="col">เบอร์โทรศัพท์</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
					</tbody>
				</table>
				<h3>ประจำวันที่: dd / mm / yyyy </h3>
				<table width="100%" class="table">
					<thead>
						<tr>
							<th scope="col">รหัส</th>
							<th scope="col">ขื่อสมาชิก</th>
							<th scope="col">เบอร์โทรศัพท์</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
					</tbody>
				</table>
				<h3>ประจำวันที่: dd / mm / yyyy </h3>
				<table width="100%" class="table">
					<thead>
						<tr>
							<th scope="col">รหัส</th>
							<th scope="col">ขื่อสมาชิก</th>
							<th scope="col">เบอร์โทรศัพท์</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>
