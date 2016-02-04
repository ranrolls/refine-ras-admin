<?


$old_password=$_REQUEST["old_password"];
$new_password=$_REQUEST["new_password"];
$confirm_password=$_REQUEST["confirm_password"];
$auth_token=$_REQUEST["auth_token"];
$Jsoncallback=$_REQUEST['jsoncallback'];     


$url="http://api.mydeals247.com/users/update_password/change_password.json?old_password=".urlencode($old_password)."&new_password=".urlencode($new_password)."&confirm_password=".urlencode($confirm_password)."&auth_token=".urlencode($auth_token);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	echo $Jsoncallback . '(' . $data . ');';

?>