<?php
$coupon_name=$_REQUEST["coupon[name]"];
$coupon_category_id=$_REQUEST["coupon[category_id]"];
$coupon_ad_quize_info=$_REQUEST["cupon[ad_quiz_info]"];
$employment_status=$_REQUEST["employment_status[]"];
$employment_status=$_REQUEST["employment_status[]"];
$employment_status=$_REQUEST["employment_status[]"];
$employment_status=$_REQUEST["employment_status[]"];
$coupon_video_url=$_REQUEST["coupon[video_url]"];
$coupon_target_url=$_REQUEST["coupon[target_url]"];
$coupon_coupon_img=$_REQUEST["coupon[coupon_img]"];
$coupon_city=$_REQUEST["coupon[city]"];
$coupon_state=$_REQUEST["coupon[state]"];
$coupon_country=$_REQUEST["coupon[country]"];
$coupon_zipcode=$_REQUEST["coupon[zipcode]"];
$seller_valid=$_REQUEST["seller_valid"];
$auth_token=$_REQUEST['auth_token']; 




$url="http://api.mydeals247.com/coupons/update/<Ad-ID>/update.json?=coupon[name]".urlencode($coupon_name)."&coupon[category_id]=".urlencode($coupon_category_id)."&coupon[ad_quize_info]=".urlencode($coupon_ad_quize_info)."&employment_status[]=".urlencode($employment_status)."&employment_status[]=".urlencode($employment_status)."&employment_status[]=".urlencode($employment_status)."&employment_status[]=".urlencode($employment_status)."&coupon[video_url]=".urlencode($coupon_video_url)."&coupon[target_url]=".urlencode($coupon_target_url)."&coupon[coupon_img]=".urlencode($coupon_coupon_img)."&coupon[city]=".urlencode($coupon_city)."&coupon[state]=".urlencode($coupon_state)."&coupon[country]=".urlencode($coupon_country)."&coupon[zipcode]=".urlencode($coupon_zipcode)."&seller_valid =".urlencode($seller_valid)."&auth_token=".urlencode($auth_token);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);



    echo $Jsoncallback . '(' . $data . ');';



?>