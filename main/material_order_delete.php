<?php 
require_once('../include/connect.php');

$message = '';

// Delete data
$sql = 'DELETE FROM material_order WHERE id = '.$_GET['id'];
$query = mysql_query($sql) or die(mysql_error());

$sql = 'DELETE FROM material_order WHERE id = '.$_GET['id'];
$query = mysql_query($sql);

if($query)
{
	$message .= '<li class="green">ลบใบสั่งซื้อเสร็จสมบูรณ์</li>';
}
else
{
	$message .= '<li class="red">เกิดข้อผิดพลาด: ยกเลิกใบสั่งซื้อล้มเหลว</li>';
}	

// Report
$css 		= '../css/style.css';
$url_target = 'material_order.php';
$title		= 'สถานะการทำงาน';
$message 	= '<li class="green">ยกเลิกใบสั่งซื้อเสร็จสมบูรณ์</li>';

require_once("../iic_tools/views/iic_report.php");
exit();
?>