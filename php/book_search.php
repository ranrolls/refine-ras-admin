<?php
$q=$_REQUEST["q"];
$cat=$_REQUEST["cat"];
$Jsoncallback=$_REQUEST['jsoncallback'];

//echo $q;
//echo $cat;

   $url="http://www.mydeals247.com/my_deals/search_books/search/get.json?field=".$cat."&query=".$q;
//echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);

    echo $Jsoncallback . '(' . $data . ');';
?>