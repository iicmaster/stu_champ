<?php
require_once('../include/connect.php');

$message = '';

// Get file path
$sql_file	= 'SELECT * FROM product WHERE id = '.$_GET['id'];
$query_file = mysql_query($sql_file);
$data_file	= mysql_fetch_array($query_file);
	
// Delete file
if($data_file['image'] != '')
{
	if(@unlink($data_file['image']))
	{
		$message .= '<li class="green">ลบไฟล์เสร็จสมบูรณ์</li>';
	}
	else
	{
		$message .= '<li class="red">เกิดข้อผิดพลาด: ลบไฟล์ล้มเหลว</li>';
	}
}

// Delete data
$sql	= 'DELETE FROM product WHERE id = '.$_GET['id'];
$query	= mysql_query($sql) or die(mysql_error()); 

// Report
$css 		= '../css/style.css';
$url_target = 'product.php';
$title 		= 'สถานะการทำงาน';
$message	.= '<li class="green">ลบข้อมูลเสร็จสมบูรณ์</li>';

require_once("../iic_tools/views/iic_report.php");
exit();
?>