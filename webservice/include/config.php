<?php 
@session_start();

//ini_set("error_reporting", E_ALL); 
//error_reporting(1);

ini_set('date.timezone', 'America/Los_Angeles');


ini_set('magic_quotes_gpc', 1);

 //realpath (dirname(__FILE__));

 
//header('Access-Control-Allow-Origin: *');

 
 
global $conf;
$conf = array();
// CONF VARIABLES
$conf["PREFIX"]="ras_";
 

$conf["SESSION_PREFIX"]			=	"webservice";// Session Prefix.
$conf["SUBDOMAIN_STATUS"]               =        FALSE;
$conf["SITE_URL"]			=       'http://rasmentorshipforum.com/';
$conf["SITE_ROOT_DIR"]			=        '/';
$conf["BASE_ROOT_DIR"]			=        $_SERVER['DOCUMENT_ROOT'].$conf["SITE_ROOT_DIR"];
$conf["ROOT_URL"] 			=	$conf["SITE_URL"].$conf["SITE_ROOT_DIR"];
$conf["WEBSERVICE_DIR"]			= 	"webservice/";
$conf["INCLUDE_DIR"] 			=	$conf["BASE_ROOT_DIR"].$conf["WEBSERVICE_DIR"]."include/";
$conf["MODULE_DIR"] 			=	$conf["BASE_ROOT_DIR"].$conf["WEBSERVICE_DIR"]."module/";
$conf["COMMON_DIR"] 			= 	$conf["BASE_ROOT_DIR"].$conf["WEBSERVICE_DIR"]."common/";

function __autoload($name){	
global $conf;

//include(getcwd()."/common/".$name.".class.php");	

	if(file_exists("common/".$name.".class.php")){
		include_once("common/".$name.".class.php");
					//echo " Common: ".file_exists($conf['COMMON_DIR'].$name.".class.php") ;
					include_once("common/".$name.".class.php");
				}
				//else{
					//echo "File Not Found" ;
				//}
				
		if(file_exists("module/".$name.".class.php")){
			//echo " Module:".file_exists($conf['MODULE_DIR'].$name.".class.php");
			include_once("module/".$name.".class.php");
			//include_once($conf['MODULE_DIR'].$name.".class.php");
		}
				//else{
					//echo "File Not Found";
				//}
//		echo "DBNAME:".$name."CD:".$conf['COMMON_DIR']."<br>".getcwd();
		
	
}

//$filename = "./". $name .".php";
   // include_once($filename);


global $dbObj,$common;
$dbHost 	= "localhost";
$dbName 	= "rasmento_ras";
$dbUser 	= "rasmento_rasras";
$dbPassword     = 'zzU5k]T!]R@w';
$dbObj 		 = DB::singleton($dbHost, $dbName, $dbUser, $dbPassword);

$common = Common::singleton();
function ssss($date,$granularity=2) {
    $date = strtotime($date);
    $difference = time() - $date;
    $periods = array('decade' => 315360000,
        'year' => 31536000,
        'month' => 2628000,
        'week' => 604800, 
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1);

    foreach ($periods as $key => $value) {
        if ($difference >= $value) {
            $time = floor($difference/$value);
            $difference %= $value;
            $retval .= ($retval ? ' ' : '').$time.' ';
            $retval .= (($time > 1) ? $key.'s' : $key);
            $granularity--;
        }
        if ($granularity == '0') { break; }
    }
    return $retval.' ago';      
}


//function removeStripsSlashes($string){
	//return stripslashes(stripslashes(htmlspecialchars_decode($string)));
//}


function truncate_string($string, $word_length, $endstring = "...") {
    $retval = $string;
    // if a string is given instead of integer return total string.
    $word_length = (intval($word_length) == 0 ) ? strlen($retval) : intval($word_length);
    $array = explode(" ", $string);
    if (count($array) <= $word_length) {
        $retval = $string;
    } else {
        array_splice($array, $word_length);
        $retval = implode(" ", $array);
        $retval .= $endstring;
    }
    return $retval;
}


 function truncateWords($input, $numwords, $padding="")
  {
    $output = strtok($input, " \n");
    while(--$numwords > 0) $output .= " " . strtok(" \n");
    if($output != $input) $output .= $padding;
    return $output;
  }



?>
