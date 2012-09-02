<?php
require_once ("../include/session.php");
include_once ('../include/connect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบจัดการผลิตและจำหน่ายสละลอยแก้ว - รายงานกำไร-ขาดทุน</title>
<?php
include ("inc.css.php");
?>
<style type="text/css">
	h2
	{
		margin-top: 15px;
	}

	form
	{
		width: 100%;
	}

	form table tr td
	{
		border: none;
	}
</style>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<script>
	$(function()
	{
		$('table input:text').datepicker(
		{
			dateFormat : 'yy-mm-dd'
		});
	}); 
</script>
</head>
<body>
<div id="container">
	<?php
	include ("inc.header.php");
	?>
	<div id="content">
		<h1>รายงานเงินปันผลของสมาชิก</h1>
		<hr/>
		<form method="post" action="report_dividend_print.php">
			<table style="border:none">
				<tr>
					<td>ตั้งแต่วันที่</td>
					<td>
					<input type="text" name="start_date" />
					</td>
					<td>ถึงวันที่</td>
					<td>
					<input type="text" name="end_date" />
					</td>
				</tr>
			</table>
			<p class="center">
				<input type="submit" name="submit" value="ออกรายงาน" id="submit"/>
			</p>
		</form>
		<hr/>
	</div>
	<?php
	include ("inc.footer.php");
	?>
</div>
</body>
</html>
