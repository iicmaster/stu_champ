<?php
/*--------------------------------------------------------------------------------*/
/* Connection */
/*--------------------------------------------------------------------------------*/

// Set connection variable
$Host	= '192.168.2.102';
$User	= 'root';
$Pass	= '';
$DB		= 'champ';

// Open connection
$link = mysql_connect($Host, $User, $Pass);

// Select data base
mysql_select_db($DB); 

// Set mysql chracter set
mysql_query('SET NAMES UTF8');

/*--------------------------------------------------------------------------------*/
/* Date time setting */
/*--------------------------------------------------------------------------------*/

date_default_timezone_set('Asia/Bangkok');

/*--------------------------------------------------------------------------------*/
/* Function */
/*--------------------------------------------------------------------------------*/

include('../iic_tools/helpers/iic_utilities_helper.php');
include('../iic_tools/helpers/iic_crud_helper.php');

/*--------------------------------------------------------------------------------*/
/* End */
/*--------------------------------------------------------------------------------*/
?>