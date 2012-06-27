<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_POST['submit']))
{
	//print_array($_POST);
	//exit();
		
	$message = '';
	
	// --------------------------------------------------------------------------------
	// Start transaction
	// --------------------------------------------------------------------------------
	
	mysql_query('BEGIN');

	// --------------------------------------------------------------------------------
	// Insert order head
	// --------------------------------------------------------------------------------
	
	$sql = 'INSERT INTO product_order
			SET		
				orderer			= "'.$_POST['orderer'].'",
				type			= 1,
				date_create		= NOW()';
					
	$query = mysql_query($sql) or die(mysql_error());
	
	// --------------------------------------------------------------------------------
	// Get id
	// --------------------------------------------------------------------------------
	
	$sql = 'SELECT MAX(id) as id FROM product_order';
	$query = mysql_query($sql) or die(mysql_error());
	$data = mysql_fetch_assoc($query);
	$id_order = $data['id'];
	
	// --------------------------------------------------------------------------------
	// Insert order item
	// --------------------------------------------------------------------------------
	
	foreach($_POST['quantity'] as $key => $val)
	{
		if($val != '' && $val != 0)
		{
			$sql = 'INSERT INTO product_order_item
					SET
						id_order	= "'.$id_order.'",
						id_product	= "'.$key.'",
						quantity	= "'.$val.'"';
						
			if(! mysql_query($sql))
			{
				$message .= '<li class="red">เกิดข้อผิดพลาด: บันทึกข้อมูลรายการสินค้าล้มเหลว</li>';
				$message .= '<li>'.mysql_error().'</li>';
				
				/* Rollback transaction */
				mysql_query('ROLLBACK');
				
				// Report
				$css = '../css/style.css';
				$url_target = 'product_order.php';
				$title = 'สถานะการทำงาน';
				
				require_once("../iic_tools/views/iic_report.php");
				exit();
			}
		}
	}

	// --------------------------------------------------------------------------------
	// Withdraw product from stock
	// --------------------------------------------------------------------------------
	
    foreach($_POST['quantity'] as $id_product => $qty)
    {
	   $sql = 'INSERT INTO product_transaction
			   SET
				  id_product  		= "'.$id_product.'",
				  type				= 2,
				  description		= "ขายปลีกให้ลูกค้า รหัสอ้างอิงใบเสร็จเลขที่ '.zero_fill(10, $id_order).'",
				  quantity	    	= -'.$qty.',
				  stock_code	    = CURDATE()';
	                
		// RollBack transaction and show error message when query error						
		if(! $query = mysql_query($sql))
		{
			echo 'Withdraw product from stock';
			echo '<hr />';
			echo mysql_error();
			echo '<hr />';
			echo $sql;
			mysql_query("ROLLBACK");
			exit();
		}
	}
				
	// --------------------------------------------------------------------------------
	// Commit transaction
	// --------------------------------------------------------------------------------
	
	mysql_query('COMMIT');
	
	// --------------------------------------------------------------------------------
	// Open print
	// --------------------------------------------------------------------------------
	
	$message .= '<script type="text/javascript">
					 window.open("product_retail_print.php?id='.$id_product.'");
				 </script>'; 
		
	// --------------------------------------------------------------------------------
	// Report
	// --------------------------------------------------------------------------------
	
	$css = '../css/style.css';
	$url_target = 'product_retail.php';
	$title = 'สถานะการทำงาน';
	$message = '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	
	require_once("../iic_tools/views/iic_report.php");
	exit();
	
	// --------------------------------------------------------------------------------
	// End
	// --------------------------------------------------------------------------------
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>สร้างรายการขายสินค้า</title>
<!-- jQuery -->
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<!-- jQuery - UI -->
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<!-- jQuery - Form validate -->
<link rel="stylesheet" type="text/css" href="../iic_tools/css/jquery.validate.css" />
<script type="text/javascript" src="../iic_tools/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../iic_tools/js/jquery.validate.additional-methods.js"></script>
<script type="text/javascript" src="../iic_tools/js/jquery.validate.messages_th.js"></script>
<script type="text/javascript" src="../iic_tools/js/jquery.validate.config.js"></script>
<script type="text/javascript">
$(function()
{
	$('#date_receive').datepicker({ dateFormat: 'yy-mm-dd' });
	$("form").validate();
	
	$('input:text').change(function()
	{
		calculate_price();
	});
});

function calculate_price()
{
	var grand_total = 0;
	
	$('tbody > tr').each(function(index) 
	{
	  var id_product = parseInt($(this).find('td.id').attr('rel'));
	  var stock_remain = parseInt($(this).find('td.stock_remain').text());
	  var order_min = parseInt($(this).find('td.order_min').text());
	  var price_wholesale = parseInt($(this).find('td.price_wholesale').text());
	  var price_retail = parseInt($(this).find('td.price_retail').text());
	  var quantity = parseInt($(this).find('td.quantity > input').val());
	  
	  var price = (quantity >= order_min) ? price_wholesale : price_retail;
	  var total = quantity * price;
	  
	  // Update total
	  $(this).find('td.total').text(add_comma(total));
	  
	  // Update grand_total
	  grand_total += total;

	});
	
	$('#grand_total').text(add_comma(grand_total));
}

function remove_comma( val, dec )
{    
    var newVal = 0, 
    	defaultVal = 0,		
    	dec = (dec || 2);

    if ( !val )
    	return defaultVal;

    val = val.toString().replace(/,/g, '');

    if ( !val )
    	return defaultVal;

    newVal = parseFloat(val);

    if ( isNaN(newVal) )
    	return defaultVal;

    newVal = +( newVal.toFixed(dec) );

    return ( newVal || defaultVal );
}

function add_comma(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	
	while (rgx.test(x1)) 
	{
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	
	return x1 + x2;
}
</script>
<?php include("inc.css.php"); ?>
<style type="text/css">
input[type=text].right { min-width: 50px; width: 50px; }
form hr { margin: 20px 0; }
form td i.error { margin: 0px; float: left;}
</style>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<h1>สร้างรายการขายสินค้า</h1>
		<hr>
		<div class="float_r">วันที่ <?php echo date('d / m / Y'); ?></div>
		<form method="post" enctype="application/x-www-form-urlencoded">
			<label for="orderer">ชื่อลูกค้า <i>*</i></label>
			<input id="orderer" name="orderer" type="text" class="required" />
			<hr />
			<table>
				<thead>
				<tr>
					<th width="25">ลำดับ</th>
					<th>สินค้า</th>
					<th>คงเหลือในคลัง</th>
					<th>ขายส่งขั้นต่ำ</th>
					<th>ราคาขายส่ง</th>
					<th>ราคาขายปลีก</th>
					<th>จำนวน</th>
					<th>รวม</th>
				</tr></thead>
				<tbody>
				<?php 
				$sql = 'SELECT 
							*,
							(SELECT SUM(quantity) FROM product_transaction WHERE id_product = t1.id AND type != 1 ) AS "stock_remain"
						FROM product AS t1';
				$query = mysql_query($sql);
				$loop = 1;
				while($data = mysql_fetch_assoc($query)): 
				?>
				<tr>
					<td align="center"><?php echo $loop; ?></td>
					<td class="id" rel="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></td>
					
					<td align="right" class="stock_remain"><?php echo add_comma($data['stock_remain']); ?></td>
					<td align="right" class="order_min"><?php echo $data['order_min']; ?></td>
					<td align="right" class="price_wholesale"><?php echo $data['price_wholesale']; ?></td>
					<td align="right" class="price_retail"><?php echo $data['price_retail']; ?></td>
					<td width="50" class="quantity"><span></span><input type="text" name="quantity[<?php echo $data['id']; ?>]" class="right" value="0" max="<?php echo add_comma($data['stock_remain']); ?>" /></td>
					<td align="right" width="50" class="total">0</td>
				</tr>
				<?php 
				$loop++;
				endwhile; 
				?></tbody>
				<tfoot>
					<tr>
						<td colspan="7" class="center">รวม</td>
						<td class="right" id="grand_total">0</td>
					</tr>
				</tfoot>
			</table>
			<p class="center">
				<input id="submit" name="submit" type="submit" value="บันทึก" />
			</p>
		</form>
		<hr style="margin-top:25px" />
		<a href="product_order.php">กลับ</a>
	</div>
	<?php include("inc.footer.php"); ?>
</div>
</body>
</html>