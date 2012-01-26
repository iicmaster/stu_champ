<?php
require_once('../include/connect.php');

$message = '';

// Get file path
$sql_file	= 'SELECT * FROM member WHERE id = '.$_GET['id'];
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
$sql	= 'DELETE FROM member WHERE id = '.$_GET['id'];
$query	= mysql_query($sql) or die(mysql_error());

if($query)
{
	$message .= '<li class="green">ลบข้อมูลเสร็จสมบูรณ์</li>';
}
else if(mysql_errno() == 1415);
{
	$message .= '<li class="red">ข้อสมาชิกคนนี้ยังถูกอ้างอิงใช้ในระบบอยู่ ไม่สามารถลบได้</li>';
	$message .= '<li class="red">การสั่งลบถูกยกเลิก</li>';
}

// Report
$css 		= '../css/style.css';
$url_target	= 'member.php';
$title		= 'สถานะการทำงาน';
$message	.= '<li class="green">ลบข้อมูลเสร็จสมบูรณ์</li>';

require_once("../iic_tools/views/iic_report.php");
exit();
?>