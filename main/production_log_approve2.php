<?php
require_once("../include/session.php");
require_once('../include/connect.php');

print_array($_POST);
exit();

if(isset($_POST['submit']))
{   
    $message = '';
    
    // --------------------------------------------------------------------------------
    // Start transaction
    // --------------------------------------------------------------------------------
    
    mysql_query('BEGIN');
    
    // --------------------------------------------------------------------------------
    // Update approved member
    // --------------------------------------------------------------------------------
    
    foreach($_POST['id_member_approved'] as $key => $val)
    {
        if($val != '' && $val != 0)
        {
            $sql = 'UPDATE production_member
                    SET id_member = "'.$val.'"
                    WHERE id_log = '.$_POST['id_production_log'];
                        
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
    
    foreach($_POST['product_ordered_approved'] as $key => $val)
    {
        if($val != '' && $val != 0)
        {
            $sql = 'UPDATE production_product
                    SET quantity_receive = "'.$val.'"
                    WHERE 
                    	id_log = '.$_POST['id_production_log'].'
                    	AND type = 1
                    	AND id_product = '.$key;
                        
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
	
	// --------------------------------------------------------------------------------
	// Update material
	// --------------------------------------------------------------------------------
	
	$total_produced = array();
	
	foreach($_POST['product_restock_approved'] as $key => $value)
	{
       $total_produced[$key] += $value;
	}
	
	foreach($_POST['product_ordered_approved'] as $key => $value)
	{
       $total_produced[$key] += $value;
	}
	
	$sql = 'SELECT 
	            id_material as id,
	            name,
	            total,
	            unit
	        
	        FROM product_material
	        
	        LEFT JOIN material
	        ON product_material.id_material = material.id
	        
	        GROUP BY id_material';
	        
	$query = mysql_query($sql);
	
	while($material = mysql_fetch_array($query))
	{
	    $required_qty = 0;
	    $buy_qty = 0;
	    
		// Get required material per product
	    $sql = 'SELECT id FROM product';
	    $result = mysql_query($sql) or die(mysql_error());
	    
	    while($product = mysql_fetch_assoc($result))
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
	    
		
				
	}
	
	// --------------------------------------------------------------------------------
	// Update stock
	// --------------------------------------------------------------------------------
	
	foreach($_POST['product_restock'] as $key => $val)
    {
        if($val != '' && $val != 0)
        {
            $sql = 'INSERT INTO product_stock
                    SET
                        id_production_log	= "'.$id_log.'",
                        id_product  		= "'.$key.'",
                        type				= 0,
                        total	    		= "'.$val.'",
                		date_create			= NOW()';
                        
			// RollBack transaction and show error message when query error						
			if(! $query = mysql_query($sql))
			{
				echo 'Insert product_stock';
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
    // Commit transaction
    // --------------------------------------------------------------------------------
    
    mysql_query('COMMIT');
        
    // --------------------------------------------------------------------------------
    // Report
    // --------------------------------------------------------------------------------
    
    $css = '../css/style.css';
    $url_target = 'production_log.php';
    $title = 'สถานะการทำงาน';
    $message = '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
    
    require_once("../iic_tools/views/iic_report.php");
    exit();
    
    // --------------------------------------------------------------------------------
    // End
    // --------------------------------------------------------------------------------
}
?>