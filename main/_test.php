<?php
require_once('../include/connect.php');

	/*$sql_id = 'SHOW TABLE STATUS LIKE "member"';
	$query_id = mysql_query($sql_id);
	$data_id = mysql_fetch_array($query_id);
	
	print_array($data_id);*/
	
	function abc()
	{
		return array('a' => 'A', 'b' => 'B', 'c' => 'C');	
		return TRUE;
	}
	
	$abc = abc();
	
	//echo $abc;
	//print_array($abc);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include("inc.css.php"); ?>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<script type="text/javascript">
$(function(){
	// Set menu to active this page
	$("#menu a:eq(3)").addClass('active');
	
	// Generate tab
	$("#tabs").tabs();
	
	// Generate queue date caledar
	$("#queue_date").datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
</head>

<body>
<div id="container">
	<?php include('inc.header.php'); ?>
	<div id="content">
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">เครื่องซักผ้า</a></li>
				<li><a href="#tabs-2">เครื่องอบผ้า</a></li>
				<li><a href="#tabs-3">เตารีด</a></li>
			</ul>
			<div id="tabs-1"></div>
			<div id="tabs-2"></div>
			<div id="tabs-3"></div>
		</div>
	</div>
</div>
</body>
</html>
