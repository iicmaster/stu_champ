<?php 
require_once('../include/connect.php');

$sql = 'SELECT 
			*, 
			(
				SELECT SUM(quantity) 
				FROM product_transaction 
				WHERE 
					id_product = t1.id_product
					AND stock_code = t1.stock_code
			) AS remain
			
		FROM product_transaction as t1
		
		JOIN product
		ON t1.id_product = product.id
		
		WHERE
			stock_code = "'.$_GET['stock_code'].'"
			
		GROUP BY id_product';
		
$result = mysql_query($sql) or die(mysql_error());

mysql_query('BEGIN');

while($data = mysql_fetch_assoc($result))
{
	if($data['remain'] > 0)
	{
		$sql = 'INSERT INTO product_transaction
				SET
		  			id_product  = "'.$data['id_product'].'",
		  			type		= 3,
		  			description	= "กำจัดทิ้ง",
					stock_code	= "'.$_GET['stock_code'].'",
					quantity	= -'.$data['remain'];
					
		// RollBack transaction and show error message when query error						
		if(! $query = mysql_query($sql))
		{
			echo 'Destroy product from stock';
			echo '<hr />';
			echo mysql_error();
			echo '<hr />';
			echo $sql;
			mysql_query("ROLLBACK");
			exit();
		}
	}
}

mysql_query('COMMIT');

// Report
$css = '../css/style.css';
$url_target = 'product_stock.php';
$title = 'สถานะการทำงาน';
$message = '<li class="green">กำจัดสินค้าเสร็จสมบูรณ์</li>';

require_once("../iic_tools/views/iic_report.php");
exit();
?>