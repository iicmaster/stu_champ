<?php 
require_once("../include/session.php"); 
require('../include/connect.php');

if(isset($_GET['id_product']) && isset($_GET['quantity']))
{
	$sql = 'SELECT *
			FROM product
			WHERE id_product = '.$_GET['id_product'];
	$query = mysql_query($sql);
	$data = mysql_fetch_array($query);
	
	if($_GET['quantity'] > $data['manufacture_min'])
	{
		$total_labour = $data['labour_min'];
		$labour_increase = round(($_GET['quantity']-$data['manufacture_min']) / $data['unit_per_labour']);
		
		if($labour_increase > 0)
		{
			for($loop = 1; $loop <= $labour_increase; $loop++)
			{
				$total_labour++;
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบจัดการผลิตและจำหน่ายสละลอยแก้ว</title>
<?php include("inc.css.php"); ?>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>ผลิตสินค้า</h1>
		<hr />
			<table border="1" align="center" cellpadding="5" cellspacing="0">
				<thead>
					<tr>
						<th width="20">&nbsp;</th>
						<th>รายชื่อสมาชิกที่เข้าทำงาน</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				
					
					function get_member_selectbox($selected)
					{
						$sql = 'SELECT * FROM member';
						$query = mysql_query($sql);
						
						$_selectbox = '<select name="id_member[]">';
						while($data = mysql_fetch_array($query))
						{
							
							$_selected = ($data['id_member'] == $selected) ? 'selected="selected"' : '';
							
							$_selectbox .= '<option value="'.$data['id_member'].'" '.$_selected.'>'.$data['name'].'</option>';
						}
						$_selectbox .= '</select>';
						
						return $_selectbox;
					}
					
					$sql = 'SELECT *
							FROM member
							LIMIT '.$total_labour;
					$query = mysql_query($sql);
					$loop = 1;
					
					while($data = mysql_fetch_array($query))
					{
						echo '<tr>
								<td class="right">'.$loop.'</td>
								<td>'.get_member_selectbox($data['id_member']).'</td>
							  </tr>';
						$loop++;
					}
				?>
					</tbody>
			</table>
			<p class="center">
				<input id="submit" name="submit" type="submit" value="ตกลง" />
			</p>
			<?php echo $data['unit'] ?>
		</form>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>
