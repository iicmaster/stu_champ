<?php 
require_once("../include/session.php");
require_once('../include/connect.php');

function get_product_cost($stock_code)
{
	// Get id_production_log
	$sql = 'SELECT * FROM product_transaction WHERE stock_code = "'.$stock_code.'"';
	$query = mysql_query($sql) or die(mysql_error());
	$data = mysql_fetch_array($query);
	$id_production_log = $data['id_production_log'];
	
	// Get material cost for this production
	$sql = 'SELECT * FROM material';
	$query = mysql_query($sql) or die(mysql_error());
	
	while($material = mysql_fetch_array($query))
	{
	    $sql = 'SELECT 
					ABS(quantity) AS quantity,
					(
						SELECT SUM(amount) 
						FROM material_transaction 
						WHERE stock_code = t1.stock_code 
						AND id_material = t1.id_material
					) AS total_amount,
					(
						SELECT SUM(quantity) 
						FROM material_transaction 
						WHERE stock_code = t1.stock_code 
						AND id_material = t1.id_material 
						AND quantity > 0
					) AS total_quantity
				
				FROM material_transaction AS t1
				
				WHERE 
					id_material = '.$material['id'].'
					AND id_production_log = "'.$id_production_log.'"';
					
	    $result_cost = mysql_query($sql) or die(mysql_error);
	    $result_cost_row = mysql_num_rows($result_cost);
		
		$material_cost[$material['id']] = 0;
		
		if($result_cost_row > 0)
		{
			while($cost = mysql_fetch_assoc($result_cost))
			{
				//$material_cost[$material['id']] = round($cost['total_amount'] / $cost['total_quantity'], 2);{
				$material_cost[$material['id']] += $cost['quantity'] * round($cost['total_amount'] / $cost['total_quantity'], 2);
				@$total_used_material[$material['id']] += $cost['quantity'];
			}
			
			$material_cost[$material['id']] = round($material_cost[$material['id']] / @$total_used_material[$material['id']], 2);
		}
		    
		// Get required material per product
	    $sql = 'SELECT id FROM product';
	    $result = mysql_query($sql) or die(mysql_error());
	   	$required_qty = array();
		
	    while($product = mysql_fetch_assoc($result))
	    {
	        $sql = 'SELECT *
	                FROM product_material
	                WHERE
	                    id_product = '.$product['id'].'
	                    AND id_material = '.$material['id'];
						
	        $result_pm = mysql_query($sql) or die(mysql_error);
	        $data = mysql_fetch_assoc($result_pm);
	        
	        $material_require[$product['id']][$material['id']] = $data['quantity'];
	    }
	}
		
	//print_array($material_cost);
	//print_array($material_require);
	
	// Get product cost/unit
	$product_material_cost = array();
	$product_cost = array();
	
	$sql = 'SELECT *
			FROM product_transaction
			WHERE 
				id_production_log = "'.$id_production_log.'"
			GROUP BY id_product';
		
	$query_production_transaction = mysql_query($sql) or die(mysql_error());
	
	while($production_transaction = mysql_fetch_assoc($query_production_transaction))
	{
		$id_product = $production_transaction['id_product'];
		
		foreach ($material_require[$id_product] as $id_material => $value) 
		{
			$product_material_cost[$id_product][$id_material] = $material_require[$id_product][$id_material] * $material_cost[$id_material];
			@$product_cost[$id_product] += $material_require[$id_product][$id_material] * $material_cost[$id_material];
		}
	}
	
	//print_array($product_material_cost);
	//print_array($product_cost);
	//exit();
	
	return $product_cost;
}	
?>