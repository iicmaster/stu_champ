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
    // Insert production log
    // --------------------------------------------------------------------------------
    
    $sql = 'INSERT INTO production_log
            SET     
                date_work	= "'.$_POST['production_date'].'",
                date_exp	= DATE_ADD(NOW(), INTERVAL 30 DAY),
                description	= "'.$_POST['description'].'",
                date_create	= NOW()';
	
	// RollBack transaction and show error message when query error						
	if(! $query = mysql_query($sql))
	{
		echo 'Insert production log';
		echo '<hr />';
		echo mysql_error();
		echo '<hr />';
		echo $sql;
		mysql_query("ROLLBACK");
		exit();
	}
    
    // --------------------------------------------------------------------------------
    // Get production log id
    // --------------------------------------------------------------------------------
    
    $sql = 'SELECT MAX(id) as id FROM production_log';
	
	// RollBack transaction and show error message when query error						
	if(! $query = mysql_query($sql))
	{
		echo 'Get production log id';
		echo '<hr />';
		echo mysql_error();
		echo '<hr />';
		echo $sql;
		mysql_query("ROLLBACK");
		exit();
	}

    $data = mysql_fetch_assoc($query);
    $id_log = $data['id'];
    
    // --------------------------------------------------------------------------------
    // Insert production member
    // --------------------------------------------------------------------------------
    
    foreach($_POST['id_member'] as $key => $val)
    {
        if($val != '' && $val != 0)
        {
            $sql = 'INSERT INTO production_member
                    SET
                        id_log				= "'.$id_log.'",
                        id_assigned_member	= "'.$val.'"';
                        
			// RollBack transaction and show error message when query error						
			if(! $query = mysql_query($sql))
			{
				echo 'Insert production member';
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
    // Insert production product (restock)
    // --------------------------------------------------------------------------------
    
    foreach($_POST['product_restock'] as $key => $val)
    {
        if($val != '' && $val != 0)
        {
            $sql = 'INSERT INTO production_product
                    SET
                        id_log			= "'.$id_log.'",
                        type			= 0,
                        id_product		= "'.$key.'",
                        quantity_order	= "'.$val.'"';
                        
			// RollBack transaction and show error message when query error						
			if(! $query = mysql_query($sql))
			{
				echo 'Insert production product (restock)';
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
    // Insert production product (ordered)
    // --------------------------------------------------------------------------------
    
    if(isset($_POST['product_ordered']))
	{
	    foreach($_POST['product_ordered'] as $id_order => $order)
	    {
	    	foreach($order as $id_product => $qty)
			{
		        if($qty != '' && $qty != 0)
		        {
		            $sql = 'INSERT INTO production_product
		                    SET
		                        id_log			= "'.$id_log.'",
		                        type			= 1,
		                        id_order	  	= "'.$id_order.'",
		                        id_product  	= "'.$id_product.'",
		                        quantity_order	= "'.$qty.'"';
		                        
					// RollBack transaction and show error message when query error						
					if(! $query = mysql_query($sql))
					{
						echo 'Insert production product (ordered)';
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
	}
                
    // --------------------------------------------------------------------------------
    // Commit transaction
    // --------------------------------------------------------------------------------
    
    mysql_query('COMMIT');
	
	// --------------------------------------------------------------------------------
	// Open print
	// --------------------------------------------------------------------------------
	
	$message .= '<script type="text/javascript">
					 window.open("production_log_print.php?id='.$id_log.'");
				 </script>'; 
        
    // --------------------------------------------------------------------------------
    // Report
    // --------------------------------------------------------------------------------
    
    $css = '../css/style.css';
    $url_target = 'production_log.php';
    $title = 'สถานะการทำงาน';
    $message .= '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
    
    require_once("../iic_tools/views/iic_report.php");
    exit();
    
    // --------------------------------------------------------------------------------
    // End
    // --------------------------------------------------------------------------------
}
?>