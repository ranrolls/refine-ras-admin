<?php
	echo " check for mysqli enabled or not below <br> " ;
	var_dump(function_exists('mysqli_connect'));
	
	echo '<br>';
	
	echo "lets cehck if mysqlnd is the default driver or stil pdo is the default driver if we get disabled we need to enable <br>" ;
	
	$mysqlnd = function_exists('mysqli_fetch_all');

	if ($mysqlnd) {
    		echo 'mysqlnd enabled!';
	}
	
	else {
		echo '<p style="color:red;">mysqlnd is disabled</p>';
	}
	
	
	
	phpinfo();
?>