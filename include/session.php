<?php 
session_start();
		
if( ! isset($_SESSION["login"]))
{	
	// Report
	$css 		= '../css/style.css';
	$url_target	= 'login.php';
	$title		= 'ระบบรักษาความปลอดภัย';
	$message	= '<li class="red">กรูณา Login เพื่อเข้าถึงข้อมูล</li>';
	
	require("../iic_tools/views/iic_report.php");
	exit();
}
?>