<?php


	if (isset($_GET["regId"]) && isset($_GET["message"])) {

		$regId = $_GET["regId"];
		$message = $_GET["message"];
		$mode = $_REQUEST['mode'];

		include_once './GCM.php';

		$gcm = new GCM();

		$registatoin_ids = array($regId);  

		$message = array($mode => $message);
 
		$result = $gcm->send_notification($registatoin_ids, $message);
		$jsonresArr = json_decode($result,true);
		print($jsonresArr);
	}


?>
