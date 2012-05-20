<?php
require_once ("../include/session.php");
require ('../include/connect.php');

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
    // Insert order head
    // --------------------------------------------------------------------------------
    
    $sql = 'INSERT INTO production_log
            SET     
                orderer         = "'.$_POST['orderer'].'",
                tel             = "'.$_POST['tel'].'",
                date_receive    = "'.$_POST['date_receive'].'",
                description     = "'.$_POST['description'].'",
                date_create     = NOW()';
                    
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
                        id_order    = "'.$id_order.'",
                        id_product  = "'.$key.'",
                        quantity    = "'.$val.'"';
                        
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
    // Commit transaction
    // --------------------------------------------------------------------------------
    
    mysql_query('COMMIT');
        
    // --------------------------------------------------------------------------------
    // Report
    // --------------------------------------------------------------------------------
    
    $css = '../css/style.css';
    $url_target = 'product_order.php';
    $title = 'สถานะการทำงาน';
    $message = '<li class="green">บันทึกข้อมูลเสร็จสมบูรณ์</li>';
    
    require_once("../iic_tools/views/iic_report.php");
    exit();
    
    // --------------------------------------------------------------------------------
    // End
    // --------------------------------------------------------------------------------
}
?>