<?php 
/*
Class Name : DB
Uses of Class : It contains the database methods and database connectivity related method.

*/
class DB{	
	private static $instance;
	var $host;
	var $database;
	var $user;
	var $pass;
	var $name;
	var $cnn;
	var $dbase;
	/*

		It is used prevent multiple instance of db class.

	

	*/

	public static function singleton($dbHost='', $dbName='', $dbUser='', $dbPassword='') {
		if (!isset(self::$instance)) {
        		$c = __CLASS__;
        		self::$instance = new $c($dbHost, $dbName, $dbUser, $dbPassword);
		}
		return self::$instance;

    }

	
	/*
		It is used for database connectivity.
	
	*/
	
	public function __construct($dbHost='', $dbName='', $dbUser='', $dbPassword=''){	
			 $this->host = $dbHost;
			 $this->database = $dbName;
			 $this->user = $dbUser;
			 $this->pass = $dbPassword;
			 $this->cnn = mysql_connect($this->host, $this->user, $this->pass) or die(mysql_error());
			 $this->dbase = @mysql_select_db($this->database, $this->cnn) or die(mysql_error());

				
	}
	
	/*
		It is used for number formate.
	
	*/
	function numberFormat($number, $decimals = '2', $decimalPoint = '.', $thousandsSep = '' ){
		
		return number_format($number, $decimals, $decimalPoint, $thousandsSep); 
	}
	/*
		It is used to get the Max of a given field in a table.
	
	*/
	public function latestPosition($tableName, $fieldName){
		global $dbObj;	
		
		$sql = "select MAX($fieldName) AS position from ".$dbObj->addPrefix($tableName)." ";	
		$rs = $dbObj->runQuery($sql);
		$rw = $dbObj->getArray($rs);
		
		return $rw['position'];
	}
	/*
		It is used to add the prefix in table.
	
	*/
	function addPrefix($tblname){
		global $conf;
		return $conf["PREFIX"].$tblname;
	}
	/*
		It is used to run query.
	
	*/
	public function runQuery($SQL){
	//echo $SQL;
	//echo '<br><br>';
	$rs = @mysql_query($SQL) or die(mysql_error());
	return $rs;
	}
	/*
		It is used get Array.
	*/
	public function getArray($rs){
		
		if(mysql_num_rows($rs)>0){
		$rw = @mysql_fetch_array($rs) or die(mysql_error());
		return $rw;
		}else{
			return false;
		}
		
	}
	/*
		It is used Assoc Array.	
	*/
	function getAssoc($rs){
	//$rw = @mysql_fetch_assoc($rs) or die(mysql_error());
	if(mysql_num_rows($rs)>0){
		$rw = @mysql_fetch_assoc($rs) or die(mysql_error());
		return $rw;
		}else{
			return false;
		}
		return $rw;
	}
	/*
		It is used get row according to given fields and condition.	
	*/
	function getRecords($table, $fields, $condn = ''){
		global $dbObj, $conf;
		$row = array();
		if(!empty($condn)) $condn = " where ".$condn;
		$query = "Select ".$fields." from ".$table." ".$condn;
		$dbObj -> runQuery($query); #echo "<br /><br />".$query;die;
		if($dbObj -> numRow() > 0){ while($rs = $dbObj ->  getAssoc()) $row[] = $rs; }
		$objDB -> freeResult();
		return $row;
	}
	/*
		It is used getID mean autoincrement value.	
	*/
	function getID($tn,$fn){
		$SQL = "select max($fn) from $tn";
		$rs = @mysql_query($SQL) or die(mysql_error());
		if (($rw = mysql_fetch_array($rs))){
			return $rw[0]+1;
		}
		else{
			return 1;
		}
	}
	/*
		It is used get the vlaue of a single row on single condition.	
	*/
	public function getValue($tn, $sf, $gf, $val, $nf = ""){
		if (is_null($val)){
			return "";
		}

		$val = "'" . $val . "'";
		
		$SQL = "select $gf from $tn where $sf = $val";
		$rs = @mysql_query($SQL) or die(mysql_error());
		if (($rw = mysql_fetch_array($rs))){
			return $rw[0];
		}
		else{
			return $nf;
		}
	}
	/*
		It is used get the vlaue of a single row on multiple condition.	
	*/
	function isValueExist($tn, $sf, $gf, $val, $exp = -1, $cond = "0=0"){
		$val = "'" . $val . "'";
		$exp = "'" . $exp . "'";
		
		$SQL = "select $gf from $tn where ($sf = $val) and ($cond) and ($gf != $exp)";
		
		$rs = @mysql_query($SQL) or die(mysql_error());
		if (($rw = mysql_fetch_array($rs))){
			return $rw[0];
		}
		else{
			return false;
		}
	}
	/*
		It is used get the vlaue of a single row on multiple condition.	
	*/
	function isValueExists($tn, $sf, $gf, $val, $exp = -1, $cond = "0=0"){
		$val = "'" . $val . "'";
		$exp = "'" . $exp . "'";

		 $SQL = "select $gf from $tn where ($sf = $val) and ($cond) and ($gf = $exp)";
		$rs = @mysql_query($SQL) or die(mysql_error());
		if (($rw = mysql_fetch_array($rs))){			
			return $rw[0];
		}
		else{			
			return false;			
		}		
	}
	
	/*
		It is used get the vlaue of a single row on multiple condition.	
	*/
	function getSingleRow($query){
		
		$rs = @mysql_query($query) or die(mysql_error());
		if (($rw = mysql_fetch_array($rs))){			
			return $rw;
		}
		else{			
			return false;			
		}		
	}
	
	
	/*
		It is used get the number of records on given query.	
	*/
	function getNoOfRec($SQL){
		$rsCount = @mysql_query($SQL) or die(mysql_error());
		$NOR = mysql_num_rows($rsCount);
		return $NOR;
	}
	/*
		It is used get the number of records on given resultset.	
	*/
	function getNoOfRow($rs){
		$NOR = mysql_num_rows($rs);
		return $NOR;
	}
	/*
		It is used close the mysql connection.	
	*/
	public function close(){
		@mysql_close();
	}
	/*
		It is used get the last inserted autoincremented ID.	
	*/
	function insertID()
	{
		return mysql_insert_id($this -> cnn);
	}
	
	
	public function __destruct() {
       @mysql_close();
	}
	
}
?>

