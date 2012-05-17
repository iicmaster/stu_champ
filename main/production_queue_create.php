<?php
require_once ("../include/session.php");
require ('../include/connect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบจัดการผลิตและจำหน่ายสละลอยแก้ว</title>
<?php include ("inc.css.php") ?>
<style type="text/css">
#product_stock h3
{
	margin-bottom: 10px;
}
</style>
<!-- jQuery -->
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<!-- jQuery - UI -->
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>

<script type="text/javascript">
$(function()
{
    $("#production_date").datepicker({
    	dateFormat : 'yy-mm-dd'
    });
});
</script>
</head>
<body>
<div id="container">
	<?php include ("inc.header.php") ?>
	<div id="content">
		<h1>จัดคิวการผลิต</h1>
		<hr />
		<p>ประจำวันที่
			<input type="text" id="production_date" value="<?php echo date('Y-m-d') ?>" size="8" class="center" />
		</p>
		<table>
			<thead>
				<tr>
					<th width="20">ลำดับ</th>
                    <th>ชื่อ</th>
				</tr>
			</thead>
		    <?php 
              
              // Create option list
		      $option_list = '';
              
		      $query = 'SELECT * FROM member';
              $result = mysql_query($query);
              
              while($member = mysql_fetch_assoc($result)) 
              {
                 $option_list .= '<option value="'.$member['id'].'">'.$member['name'].'</option>';
              } 
              
              // Create member list
              $query = 'SELECT * FROM member';
              $result = mysql_query($query);
              
              $loop = 1;
              while($member = mysql_fetch_assoc($result)):
		    ?>
			<tbody>
				<tr>
					<td class="right"><?php echo $loop ?></td>
                    <td>
                        <select id="id_member_<?php echo $member['id'] ?>" name="id_member[]">
                           <?php echo $option_list ?>
                        </select>
                    </td>
				</tr>
			</tbody>
			<?php $loop++; endwhile ?>
		</table>
        
        <form method="get" action="production_queue_create.php">
            <p class="center">
                <input id="submit" name="submit" type="submit" value="บันทึก" />
            </p>
        </form>
	</div>
	<?php include ("inc.footer.php") ?>
</div>
</body>
</html>