<?php

include("include/config.php");
 
$uploadReceipt = new ReceiptuploadClass();

$uploadReceipt->action = $common->replaceEmpty('action','');



switch($uploadReceipt->action){
  
	case 'uploadreceipt':$uploadReceipt->UploadReceipt();
	  
	break;
	 
	case 'displayReceipt':$uploadReceipt->DisplayReceipt();
	  
	break;
	
	
	case 'deletereceipt':$uploadReceipt->DeleteReceipt();
	  
	break;
	 
default:
		echo json_encode('error');die;
break;


}
 

   
?>
