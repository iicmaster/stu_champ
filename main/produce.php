<?php 
	require("../include/session.php");  //เรียกใช้งาน session
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- Template Design by TheWebhub.com | http://www.thewebhub.com | Released for free under a Creative Commons Attribution-Share Alike 3.0 Philippines -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PAGE PRODUCE</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="../css/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
  <?php include("inc.header.php"); ?>
  <div id="content">
    <div id="right">
      <div class="post">
        <h2>บุคคลที่เข้าทำการผลิต</h2>
        <form id="form1" method="post" action="">
          <p>
            <label>
              <input type="checkbox" name="checkbox" value="checkbox" />
              คุณ</label>
            <br />
            <label>
              <input type="checkbox" name="checkbox2" value="checkbox" />
              คุณ</label>
            <br />
            <label>
              <input type="checkbox" name="checkbox3" value="checkbox" />
              คุณ</label>
            <br />
            <label>
              <input type="checkbox" name="checkbox4" value="checkbox" />
              คุณ</label>
            <br />
            <label>
              <input type="checkbox" name="checkbox5" value="checkbox" />
              คุณ</label>
            <br />
            <label>
              <input type="checkbox" name="checkbox6" value="checkbox" />
              คุณ</label>
            <br />
            <label></label>
          </p>
        </form>
        <p><a href="#"></a></p>
      </div><!--end post-->
      <div class="post">
        <h2>จำนวนสินค้าที่ต้องการผลิต</h2>
        <p>ขนาดเล็ก ......<br />
          ขยาดกลาง.....<br />
          ขนาดใหญ่.....</p>
        <p>&nbsp;</p>
        <form id="form2" method="post" action="">
          <p>
            <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="submit" name="Submit" value="เริ่มใหม่" />
            </label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label>
              <input type="submit" name="Submit2" value="ตกลง" />
            </label>
            &nbsp;&nbsp;</p>
        </form>
        <p>&nbsp;</p>
      </div><!--end post-->
    </div><!--end right-->
    <div id="left">
      <h2>รายละเอียดการผลิต</h2>
      <p>วันที่ ......</p>
      <p>จำนวนสินค้าคงเหลือ</p>
      <p>ขนาดเล็ก ......<br />
        ขยาดกลาง.....<br />
        ขนาดใหญ่.....</p>
      <p>จำนวนสินค้าที่ควรผลิต</p>
      <p>ขนาดเล็ก ......<br />
        ขยาดกลาง.....<br />
        ขนาดใหญ่.....</p>
      <p>สินค้าสั่งซื้อเพิ่มพิเศษ</p>
      <p>ขนาดเล็ก ......<br />
        ขยาดกลาง.....<br />
        ขนาดใหญ่.....</p>
      <p>&nbsp;</p>
    </div><!--end left-->
  </div><!--end content-->
  <div id="footer">
    <p class="copyright"><a href="http://famfamfam.com/">mashimaro_za9@hotmail.com</a></p>
    <p class="links">....</p>
  </div><!--end footer-->
</div><!--end wrapper-->
</body>
</html>
