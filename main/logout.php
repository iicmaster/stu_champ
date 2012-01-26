<?php
// Destroy all session
session_start();
session_destroy();

// Report
$css 		= '../css/style.css';
$url_target	= 'login.php';
$title		= 'สถานะการทำงาน';
$message	= '<li class="green">ลงชื่อออกจากระบบเสร็จสมบูรณ์</li>';

require_once("../iic_tools/views/iic_report.php");
exit();
?>