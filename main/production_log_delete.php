<?php 
require_once('../include/connect.php');

$message = '';

// Delete data
$sql = 'DELETE FROM production_log WHERE id = '.$_GET['id'];
$query = mysql_query($sql);

if($query)
{
    $message = '<li class="green">ลบข้อมูลเสร็จสมบูรณ์</li>';
}
else if(mysql_errno() == 1451)
{
    $message .= '<li class="red">ข้อมูลของวัตถุดิบนี้ยังถูกใช้อ้างอิงในระบบอยู่ ไม่สามารถลบได้</li>';
    $message .= '<li class="red">การสั่งลบถูกยกเลิก</li>';
}

// Report
$css = '../css/style.css';
$url_target = 'production_log.php';
$title = 'สถานะการทำงาน';

require_once("../iic_tools/views/iic_report.php");
exit();
?>