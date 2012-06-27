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
	
	mysql_query("BEGIN");	
		
	// --------------------------------------------------------------------------------
	// Update product order status
	// --------------------------------------------------------------------------------
	
	$sql = "UPDATE product_order
			SET is_receive = 1
			WHERE id = ".$_POST['id_order'];
			
	// RollBack transaction and show error message when query error						
	if(! $query = mysql_query($sql))
	{
		echo 'Update product order status';
		echo '<hr />';
		echo mysql_error();
		echo '<hr />';
		echo $sql;
		mysql_query("ROLLBACK");
		exit();
	}
		
	// --------------------------------------------------------------------------------
	// Update received quaintity
	// --------------------------------------------------------------------------------
	
    foreach($_POST['quantity_received'] as $id_product => $qty)
    {
        $sql = 'UPDATE product_order_item
                SET quantity_received = "'.$qty.'"
                WHERE 
                	id_order = '.$_POST['id_order'].'
                	AND id_product = '.$id_product;
                    
		// RollBack transaction and show error message when query error						
		if(! $query = mysql_query($sql))
		{
			echo 'Update received quaintity';
			echo '<hr />';
			echo mysql_error();
			echo '<hr />';
			echo $sql;
			mysql_query("ROLLBACK");
			exit();
		}
    }
	
	// --------------------------------------------------------------------------------
	// Withdraw product from stock (ordered)
	// --------------------------------------------------------------------------------
	
    foreach($_POST['quantity_received'] as $id_product => $qty)
    {
	   $sql = 'INSERT INTO product_transaction
			   SET
				  id_product  		= "'.$id_product.'",
				  type				= 1,
				  description		= "ส่งมอบให้ลูกค้า รหัสอ้างอิงใบสั่งซื้อที่ '.zero_fill(10, $_POST['id_order']).'",
				  quantity	    	= -'.$qty.',
				  stock_code	    = CURDATE()';
	                
		// RollBack transaction and show error message when query error						
		if(! $query = mysql_query($sql))
		{
			echo 'Withdraw product from stock (ordered)';
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
	
	mysql_query("COMMIT");
	
	// --------------------------------------------------------------------------------
	// Open print
	// --------------------------------------------------------------------------------
	
	$message .= '<script type="text/javascript">
					 window.open("product_order_print.php?id='.$_POST['id_order'].'", "_blank");
				 </script>';

	// --------------------------------------------------------------------------------
	// Report
	// --------------------------------------------------------------------------------
	
	$css = '../css/style.css';
	$url_target	= 'product_order.php';
	$title = 'สถานะการทำงาน';
	$message .= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
	require_once("../iic_tools/views/iic_report.php");
	exit();
		
	// --------------------------------------------------------------------------------
	// End
	// --------------------------------------------------------------------------------
}

$sql = 'SELECT * FROM product_order WHERE id = "'.$_GET['id'].'"';
$query = mysql_query($sql) or die(mysql_error());
$data = mysql_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ส่งมอบสินค้า</title>
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.11.min.js"></script>
<script type="text/javascript">
$(function(){
	$('#date_receive').datepicker({ dateFormat: 'yy-mm-dd' });
	
	$('table td input[type=text]').change(function(){
		var root = $(this).parent().parent();
		var price = root.find('span.price').text();
		var qty = $(this).val();
		var total = price * qty;
		
		root.find('span.total').text(add_comma(total));
		sum_grand_total();
	});
});

function sum_grand_total()
{
	var grand_total = 0;
	
	$('span.total').each(function()
	{
		grand_total +=  parseFloat(remove_comma($(this).text()));
		//alert($(this).text());
	})
	
	$('span.grand_total').text(add_comma(grand_total));
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
input[type=text].right { min-width: 50px; }
form hr { margin-top: 20px; }
</style>
</head>
<body>
<div id="container">
	<?php include("inc.header.php"); ?>
	<div id="content">
		<a href="product_order_print.php?id=<?php echo $_GET['id']; ?>" class="float_r">พิมพ์ใบสั่งซื้อสินค้า</a>
		<h1>ส่งมอบสินค้า</h1>
		<hr>
		<div class="float_r">วันที่ <?php echo date('d / m / Y'); ?></div>
		<form method="post" enctype="multipart/form-data">
			<label for="orderer">ชื่อ</label>
			<input id="orderer" name="orderer" type="text" value="<?php echo $data['orderer']; ?>" />
			<label for="tel">โทรศัพท์</label>
			<input id="tel" name="tel" type="text" value="<?php echo $data['tel']; ?>" />
			<label for="date_receive">วันที่มารับสินค้า</label>
			<input id="date_receive" name="date_receive" class="datepicker" type="text" value="<?php echo $data['date_receive']; ?>" />
			<label for="description">รายละเอียด</label>
			<textarea name="description"><?php echo $data['description']; ?></textarea>
			<hr />
			<table>
				<tr>
					<th width="25">ลำดับ</th>
					<th>สินค้า</th>
					<th>จำนวนที่สั่งซื้อ</th>
					<th>จำนวนส่งมอบ</th>
					<th>ราคาต่อหน่วย</th>
					<th>รวม</th>
				</tr>
				<?php 
				$sql = 'SELECT *
						FROM product_order_item 
				
						LEFT JOIN product
						ON product_order_item.id_product = product.id
						
						WHERE product_order_item.id_order = '.$_GET['id'];
						
				$query = mysql_query($sql) or die(mysql_error());
				$loop = 1;
				
				while($data = mysql_fetch_assoc($query)): 
					$total[$data['id_product']] = $data['price_wholesale'] * $data['quantity']
				?>
				<tr>
					<td align="center"><?php echo $loop; ?></td>
					<td><?php echo $data['name']; ?></td>
					<td class="right"><?php echo $data['quantity'] ?></td>
					<td class="right"><input type="text" name="quantity_received[<?php echo $data['id']; ?>]" class="right" value="<?php echo $data['quantity'] ?>" /></td>
					<td class="right"><span class="price"><?php echo $data['price_wholesale'] ?></span></td>
					<td class="right"><span class="total"><?php echo add_comma($total[$data['id_product']]) ?></span></td>
				</tr>
				
				<?php 
				$loop++;
				endwhile; 
				?>
				<tr>
					<td colspan="5" class="center">รวม</td>
					<td class="right"><span class="grand_total"><?php echo add_comma(array_sum($total)) ?></span></td>
				</tr>
			</table>
			<p class="center">
				<input type="hidden" name="id_order" value="<?php echo $_GET['id']; ?>" id="id_order"/>
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