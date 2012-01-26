<?php 
/* -------------------------------------------------------------------------------- */
/* Include */
/* -------------------------------------------------------------------------------- */

require_once("../include/session.php"); 
require_once('../include/connect.php'); 

// Get total member
$sql	= 'SELECT COUNT(*) as total_member FROM member';
$query	= mysql_query($sql);
$data	= mysql_fetch_array($query);

$total_member = $data['total_member'];

// Get search result
$sql = 'SELECT * 
		FROM member 
		WHERE '.$_POST['category'].' LIKE "%'.$_POST['keyword'].'%"';  
				
$query		= mysql_query($sql) or die(mysql_error()); 
$query_rows = mysql_num_rows($query);

/* -------------------------------------------------------------------------------- */
/* End */
/* -------------------------------------------------------------------------------- */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ค้นหาสมาชิก</title>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript">
$(function(){
	$("#category").val('<?php echo $_POST['category'] ?>');
});
</script>
<?php include("inc.css.php"); ?>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content"> <a href="member_create.php" class="float_r">สมัครสมาชิก</a>
		<h1>ค้นหาสมาชิก</h1>
		<hr />
		<div id="search_section">
			<p>ค้นพบข้อมูลที่เกี่ยวข้องจำนวน <b class="green"><?php echo $query_rows ?></b> รายการ จากข้อมูลทั้งหมด <b class="green"><?php echo $total_member ?></b> รายการ</p>
			<form method="post" action="member_search.php">
				<label for="category" class="inline">ค้นหาจาก: </label>
				<select id="category" name="category">
					<option value="id">รหัส</option>
					<option value="nickname">ชื่อเล่น</option>
					<option value="name">ชื่อ-นามสกุล</option>
					<option value="tel">เบอร์โทรศัพท์</option>
				</select>
				<label for="keyword" class="inline">คำค้น: </label>
				<input type="text" id="keyword" name="keyword" value="<?php echo $_POST['keyword'] ?>" />
				<input type="submit" name="submit" value="ค้นหา" />
			</form>
		</div>
		<hr />
		<table border="1" align="center" cellpadding="5" cellspacing="0">
			<thead>
				<tr>
					<th width="30">รหัส</th>
					<th class="nowarp">ชื่อเล่น</th>
					<th>ชื่อ-นามสกุล</th>
					<th>เบอร์โทรศัพท์</th>
					<th width="80">การดำเนินการ</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				
				
				if($query_rows > 0)
				{
					while($data = mysql_fetch_array($query))
					{
						echo 	'<tr>
									<td width="30" class="right">'.zero_fill(4, $data['id']).'</td>
									<td>'.$data['nickname'].'</td>
									<td>'.$data['name'].'</td>
									<td>'.$data['tel'].'</td>
									<td class="center nowarp">
										<a class="button" href="member_read.php?id='.$data['id'].'">ดู</a>
										<a class="button" href="member_update.php?id='.$data['id'].'">แก้ไข</a>
										<a class="button" href="member_delete.php?id='.$data['id'].'">ลบ</a> 
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
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>