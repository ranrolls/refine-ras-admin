<?php

 
  define( '_JEXEC', 1 );
define( 'JPATH_BASE', str_replace('/webservice','',dirname(__FILE__)) ); 	# This is when we are in the root
define( 'DS', DIRECTORY_SEPARATOR );
require_once( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'framework.php' );
$mainframe = & JFactory::getApplication('site');
$mainframe->initialise();

jimport('joomla.user.helper');



/**
 * Class Name 		:	Login 
 * Description		:	This class authenticate email and password and login into the system.
 * Author			:	Vishal
 * Created on		:	29-06-2014
 * 
 * */
Class Login{
	
 
public function __construct(){
		 
}


		
/*Function to authenticate user 
* @param $credentials as user name and password
 * @return xml response 
* */

public function Authecticate()
{
	

 global $dbObj,$common;
			
$username      = $common->replaceEmpty('username','');
$userpassword  = $common->replaceEmpty('password','');
			
$result= array();
 			 
 if($action='login'){
				 
 $sql_username ="SELECT * from ras_users where username = '".$username."' and block = '0' ";  
 $rs_username  = $dbObj->runQuery($sql_username);
 
  	if($rows_username = mysql_fetch_assoc($rs_username)){ 
		 $dbpassword = $rows_username['password']; 
				  
		if(JUserHelper::verifyPassword($userpassword, $rows_username['password'], $rows_username['id'])){
			
		$datelogged = date('Y-m-d H:i:s');
		$sqlLog = "INSERT INTO ras_user_visit_log SET userID='".$rows_username['id']."', useFrom = 'Android', dateLogged='".$datelogged."'";
		$dbObj->runQuery($sqlLog);
		
		 $result[]=$rows_username; 
                echo json_encode(array('status'=>'1',$result));
		 }
		  
		 else{
				$result[] =  "0";
				echo json_encode($result); 
				}
				
}
 else{
				$result[] =  "No Record";
				echo json_encode($result); 
				}

} // action close

} //closing of Function
	############## User List by Email id ###########
	
	   		
public function UserListDetails()
{
global $dbObj,$common;
			
$auth_token  = $common->replaceEmpty('auth_token','');
			
$result= array();
			 
 if($action='userlist'){
				 
  $sql= $dbObj->runQuery("select * from user where auth_token='".$auth_token."' ");
		
$num_row = mysql_num_rows($sql); 
if($num_row>0){
while($logindetails = mysql_fetch_assoc($sql)) {
$result = $logindetails;
 } 
 
header('Content-Type: application/json');
			
echo json_encode($result);	 				
 
}


else {
$results[] =  "No User List";
echo json_encode($results);
			 
}
			
}

} //closing of Function
	
	
	
	############## End Here ###########################
	
			 
} //closing of Class
		

?>
