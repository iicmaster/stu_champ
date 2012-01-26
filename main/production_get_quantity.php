<?php 
require("../include/session.php");
require('../include/connect.php');

if(isset($_GET['id_product']))
{
	$sql = 'SELECT 
				manufacture_min, 
				manufacture_max
				
			FROM product
			
			WHERE 
				id = '.$_GET['id_product'];
				
	$query	= mysql_query($sql);
	$data	= mysql_fetch_array($query);
	
	$quantity = $data['manufacture_min'];
	$increase = round($data['manufacture_min'] / 2);
	
	if($increase < 1)
	{
		$increase = 1;
	}
	
	// Genarate option value
	while($quantity <= $data['manufacture_max'])
	{
		echo '<option value="'.$quantity.'">'.$quantity.'</option>';	
		$quantity += $increase;
	}
}

?>