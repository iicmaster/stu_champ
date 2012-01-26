<?php 
require_once('../include/connect.php');

// Delete data
$sql = 'DELETE FROM material WHERE id = '.$_GET['id'];

$query = mysql_query($sql) or die(mysql_error()); 

// Report
$css 		= '../css/style.css';
$url_target = 'material.php';
$title		= 'สถานะการทำงาน';
$message	= '<li class="green">ลบข้อมูลเสร็จสมบูรณ์</li>';

require_once("../iic_tools/views/iic_report.php");
exit();
?>