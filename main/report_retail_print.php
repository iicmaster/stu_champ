<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ใบรายการ</title>
<?php include("inc.css.php"); ?>
<style type="text/css" media="print">
#paper
{
	width: 16cm;
	min-height: 24.7cm;
	padding: 2.5cm;
	position: relative;
}
</style>

<style type="text/css" media="screen">
#paper
{
	background: #FFF;
	border: 1px solid #666;
	margin: 20px auto;
	width: 21cm;
	min-height: 27cm;
	padding: 50px;
	position: relative;
	
	/* CSS3 */
	
	box-shadow: 0px 0px 5px #000;
	-moz-box-shadow: 0px 0px 5px #000;
	-webkit-box-shadow: 0px 0px 5px #000;
}
</style>

<style type="text/css" >
#paper h3 { margin-bottom: 0px; }

#paper li
{
	list-style: decimal;
	margin: 5px 0px 5px 30px;
}

#paper textarea
{
	margin-bottom:25px;
	width: 50%;
}

#paper table, #paper th, #paper td { border: none; }

#paper table.border, #paper table.border th, #paper table.border td { border: 1px solid #666; }

#paper th
{
	background: none;
	color: #000
}

#paper hr { border-style: solid; }
</style>
</head>

<body>
<div id="paper">
	<table width="100%">
		<tr>
			<td width="80" align="right">วันที่ : </td>
		</tr>
		<tr>
			<td><h1 align="center">รายงานการขาย</h1></td>
		</tr>
		<tr>
			<td align="center"><h5>ช่วงวันที่  ถึงวันที่ </h5></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>
	<table width="100%" class="border">
		<thead>
			<tr>
				<th width="80">รหัส</th>
				<th width="100">วันที่ทำรายการ</th>
                <th>ชื่อลูกค้า</th>
                <th width="100">สละลอยแก้ว ขนาดเล็ก</th>
                <th width="100">สละลอยแก้ว ขนาดกลาง</th>
                <th width="100">สละลอยแก้ว ขนาดใหญ่</th>
                <th width="100">จำนวนเงิน</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="center">001</td>
				<td align="center"></td>
                <td></td>
                <td align="right"></td>
                <td align="right"></td>
                <td align="right"></td>
                <td align="right"></td>
			</tr>		
		</tbody>
        <tfoot>
			<tr>
				<td colspan="3" align="right">รวม</td>
				<td align="right"></td>
				<td align="right"></td>
				<td align="right"></td>
				<td align="right"></td>
			</tr>
		</tfoot>
	</table>
</div>
</body>
</html>