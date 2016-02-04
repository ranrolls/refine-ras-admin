<?php

include_once("config.php");
 
$action = $_REQUEST['action'];
if($action=='trackdevicestatus'){
 
$devicetok    = $_REQUEST['devicetoken'];
//$devicetok  = $_REQUEST['deviceid'];
$read         = $_REQUEST['read'];


//echo "UPDATE ".$prefix."mobile_device_tokens set readstatus= '$read'  where token = '$devicetok' ";

 $dd= mysql_query("UPDATE ".$prefix."mobile_device_tokens set readstatus= '$read' where token= '$devicetok' ") ;

$datd4 = array('result'=>'1');
echo json_encode($datd4);
   
}//action close

?>