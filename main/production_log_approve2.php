<?php
require_once("../include/session.php");
require_once('../include/connect.php');

//print_array($_POST);
//exit();

if(isset($_POST['submit']))
{   
    $message = '';
    
    // --------------------------------------------------------------------------------
    // Start transaction
    // --------------------------------------------------------------------------------
    
    mysql_query('BEGIN');
    
    // --------------------------------------------------------------------------------
    // Update approve status
    // --------------------------------------------------------------------------------
    
	$sql = 'UPDATE production_log
            SET is_approved = 1
            WHERE id = '.$_POST['id_production_log'];
                
	// RollBack transaction and show error message when query error						
	if(! $query = mysql_query($sql))
	{
		echo 'Update approve status';
		echo '<hr />';
		echo mysql_error();
		echo '<hr />';
		echo $sql;
		mysql_query("ROLLBACK");
		exit();
	}

    // --------------------------------------------------------------------------------
    // Update approved member
    // --------------------------------------------------------------------------------
    
    foreach($_POST['id_member_approved'] as $key => $val)
    {
        if($val != '' && $val != 0)
        {
            $sql = 'UPDATE production_member
                    SET id_worked_member = "'.$val.'"
                    WHERE 
                    	id_log = '.$_POST['id_production_log'].'
						AND id_assigned_member = '.$key;
                        
			// RollBack transaction and show error message when query error						
			if(! $query = mysql_query($sql))
			{
				echo 'Update approved member';
				echo '<hr />';
				echo mysql_error();
				echo '<hr />';
				echo $sql;
				mysql_query("ROLLBACK");
				exit();
			}
        }
    }
    
    // --------------------------------------------------------------------------------
    // Update approved product (restock)
    // --------------------------------------------------------------------------------
    
    foreach($_POST['product_restock_approved'] as $key => $val)
    {
        if($val != '' && $val != 0)
        {
            $sql = 'UPDATE production_product
                    SET quantity_receive = "'.$val.'"
                    WHERE 
                    	id_log = '.$_POST['id_production_log'].'
                    	AND type = 0
                    	AND id_product = '.$key;
                        
			// RollBack transaction and show error message when query error						
			if(! $query = mysql_query($sql))
			{
				echo 'Update approved product (restock)';
				echo '<hr />';
				echo mysql_error();
				echo '<hr />';
				echo $sql;
				mysql_query("ROLLBACK");
				exit();
			}
        }
    }
    
    // --------------------------------------------------------------------------------
    // Update approved product (ordered)
    // --------------------------------------------------------------------------------
    
    foreach($_POST['product_ordered_approved'] as $id_order => $product)
    {
    	foreach($product as $id_product => $qty)
		{
	        if($qty != '' && $qty != 0)
	        {
	            $sql = 'UPDATE production_product
	                    SET quantity_receive = "'.$qty.'"
	                    WHERE 
	                    	id_log = '.$_POST['id_production_log'].'
	                    	AND id_order = '.$id_order.'
	                    	AND type = 1
	                    	AND id_product = '.$id_product;
							
				//echo '<p>'.$id_order.'-'.$id_product.'-'.$qty.'</p>';
	                        
				// RollBack transaction and show error message when query error						
				if(! $query = mysql_query($sql))
				{
					echo 'Update approved product (ordered)';
					echo '<hr />';
					echo mysql_error();
					echo '<hr />';
					echo $sql;
					mysql_query("ROLLBACK");
					exit();
				}
	        }
		}
    }
	
	//exit();
	
	// --------------------------------------------------------------------------------
	// Update material
	// --------------------------------------------------------------------------------
	
	// Sum total product to produce
	$total_produced = array();
	
	foreach($_POST['product_restock_approved'] as $key => $value)
	{
       @$total_produced[$key] += $value;
	}
	
    foreach($_POST['product_ordered_approved'] as $id_order => $product)
    {
    	foreach($product as $id_product => $qty)
		{
       		@$total_produced[$id_product] += $qty;
		}
	}
	
	//echo '$total_produced';
	//print_array($total_produced);
	//exit();
	
	// Loop by material
	$sql = 'SELECT 
	            id_material as id,
	            name,
	            total,
	            unit
	        
	        FROM product_material
	        
	        LEFT JOIN material
	        ON product_material.id_material = material.id
	        
	        GROUP BY id_material';
	        
	$query_material = mysql_query($sql);
	
	while($material = mysql_fetch_array($query_material))
	{
	    $required_qty = 0;
	    $buy_qty = 0;
	    
		// Get required material per product
	    $sql = 'SELECT id FROM product';
	    $query_product = mysql_query($sql) or die(mysql_error());
	    
	    while($product = mysql_fetch_assoc($query_product))
	    {
	        $sql = 'SELECT quantity as qty 
	                FROM product_material
	                WHERE
	                    id_product = '.$product['id'].'
	                    AND id_material = '.$material['id'];
						
	        $result_pm = mysql_query($sql) or die(mysql_error);
	        $data = mysql_fetch_assoc($result_pm);
	        
	        $required_qty += $total_produced[$product['id']] * $data['qty'];
		}
		
		$sql = 'SELECT 
					*,
					SUM(quantity) AS material_remain
					
				FROM material_transaction
				
				WHERE id_material = '.$material['id'].'
					
				GROUP BY stock_code
				ORDER BY stock_code';
				
		$query_stock = mysql_query($sql) or die(mysql_error());
		
		//echo '<ol>';
		
		// Loop by stock remain order by stock_code
		while($required_qty > 0)
		{
			$stock = mysql_fetch_array($query_stock);
			
			// Withdraw materail from oldest stock
			if($stock['material_remain'] > 0)
			{
				$RQ = $required_qty;
								
				if($required_qty >= $stock['material_remain'])
				{
					$withdraw_qty = $stock['material_remain'];
					$required_qty -= $stock['material_remain'];
				}
				else 
				{
					$withdraw_qty = $required_qty;
					$required_qty = 0;
				}
				
				$sql = 'INSERT INTO material_transaction
						SET
							id_material 		= "'.$stock['id_material'].'",
							id_supplier 		= "'.$stock['id_supplier'].'",
							id_production_log	= "'.$_POST['id_production_log'].'",
							stock_code			= "'.$stock['stock_code'].'",
							description 		= "นำไปผลิต",
							quantity			= -'.$withdraw_qty;
							
				/*echo '<li>
						['.$RQ.']-'.$stock['material_remain'].'-['.$stock['stock_code'].']-'.$withdraw_qty.'
					  </li>';*/
							
				// RollBack transaction and show error message when query error						
				if(! $query = mysql_query($sql))
				{
					echo 'Withdraw materail from oldest stock';
					echo '<hr />';
					echo mysql_error();
					echo '<hr />';
					echo $sql;
					mysql_query("ROLLBACK");
					exit();
				}
				
				// Update total
				$sql =	'UPDATE material
				 		 SET total = total - '.$withdraw_qty.'
				 		 WHERE id = '.$stock['id_material'];
						 
				$query_material_total = mysql_query($sql) or die(mysql_error());
			}
	    }
		
		//echo '</ol>';
	}
	
	//exit();
	
	// --------------------------------------------------------------------------------
	// Insert stock (restock)
	// --------------------------------------------------------------------------------
	
	foreach($_POST['product_restock_approved'] as $key => $val)
    {
        if($val != '' && $val != 0)
        {
            $sql = 'INSERT INTO product_transaction
                    SET
                        id_production_log	= "'.$_POST['id_production_log'].'",
                        id_product  		= "'.$key.'",
                        type				= 0,
                        quantity	    	= "'.$val.'",
                        stock_code	    	= CURDATE(),
                		date_create			= NOW(),
                		date_exp			= DATE_ADD(NOW(), INTERVAL 30 DAY)';
                        
			// RollBack transaction and show error message when query error						
			if(! $query = mysql_query($sql))
			{
				echo 'Insert stock (restock)';
				echo '<hr />';
				echo mysql_error();
				echo '<hr />';
				echo $sql;
				mysql_query("ROLLBACK");
				exit();
			}
        }
    }
	
	// --------------------------------------------------------------------------------
	// Insert stock (ordered)
	// --------------------------------------------------------------------------------
	
    foreach($_POST['product_ordered_approved'] as $id_order => $product)
    {
    	foreach($product as $id_product => $qty)
		{
	        if($val != '' && $val != 0)
	        {
	            $sql = 'INSERT INTO product_transaction
	                    SET
	                        id_production_log	= "'.$_POST['id_production_log'].'",
	                        id_product  		= "'.$id_product.'",
	                        type				= 1,
	                        quantity	    	= "'.$qty.'",
	                        stock_code	    	= CURDATE(),
	                		date_create			= NOW(),
	                		date_exp			= DATE_ADD(NOW(), INTERVAL 30 DAY)';
	                        
				// RollBack transaction and show error message when query error						
				if(! $query = mysql_query($sql))
				{
					echo 'Insert stock (ordered)';
					echo '<hr />';
					echo mysql_error();
					echo '<hr />';
					echo $sql;
					mysql_query("ROLLBACK");
					exit();
				}
	        }
		}
    }
                
    // --------------------------------------------------------------------------------
    // Commit transaction
    // --------------------------------------------------------------------------------
    
    mysql_query('COMMIT');
        
    // --------------------------------------------------------------------------------
    // Report
    // --------------------------------------------------------------------------------
    
    $css = '../css/style.css';
    $url_target = 'production_log_read.php?id='.$_POST['id_production_log'];
    $title = 'สถานะการทำงาน';
    $message .= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
    
    require_once("../iic_tools/views/iic_report.php");
    exit();
    
    // --------------------------------------------------------------------------------
    // End
    // --------------------------------------------------------------------------------
}
?>