<div class="col-sm-7"> 
  <!--<h3>Calling all movies</h3>
  <h1 class="red">FANATICS!</h1>
  <h4>Win a pair of Movie Tickets Every Week from 4 January to 14 February 2016</h4>-->
  <div class="contest-margin-top"></div>
  <img src="/images/fantactics-image.png" alt="" /> </div>
<div class="col-sm-5"> 
  <?php 
/**
 * File name: $HeadURL: svn://tools.janguo.de/jacc/trunk/admin/templates/modules/tmpl/default.php $
 * Revision: $Revision: 147 $
 * Last modified: $Date: 2013-10-06 10:58:34 +0200 (So, 06. Okt 2013) $
 * Last modified by: $Author: michel $
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license 
 */
defined('_JEXEC') or die('Restricted access'); 
 
$user = JFactory::getUser();

define( '_JEXEC', 1 );
define( 'JPATH_BASE', str_replace('/','',dirname(__FILE__)) ); 	# This is when we are in the root
define( 'DS', DIRECTORY_SEPARATOR );
require_once( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'framework.php' );
//$mainframe = & JFactory::getApplication('site');
//$mainframe->initialise(); 

jimport('joomla.user.helper');

include_once("./webservice/config.php");
  
########## For login #############

if(isset($_POST['loginbutton']))
    { 
     $username      = $_POST['username'];
     $userpassword  = $_POST['password1'];
      
     $sql_username = "SELECT * from ".$prefix."users where username = '".$username."'  ";  
     $rs_username  = mysql_query($sql_username);
    

    if($rows_username = mysql_fetch_assoc($rs_username)){ 
        $dbuserid= $rows_username['id'];	 
   if(JUserHelper::verifyPassword($userpassword, $rows_username['password'], $rows_username['id'])){
    
       $loggeduser= $rows_username['username'];
        
    }
       else{ 
             echo "Username & password not Matched.";
	 }

  }
        else{ 
           echo "User Not Logged In";
	 }


}



############## FOr Registration ######################

if(isset($_POST['save']))
    {

 $source=$_POST['source'];

 //die;

$data=array();
  
$uri = JUri::getInstance();
$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));


$data['activation'] = JApplicationHelper::getHash(JUserHelper::genRandomPassword());

$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token=' . $data['activation'], false); 

$data['name']=$_POST['name'];
$data['username']=$_POST['username'];

$data['password']=$_POST['password1'];

$data['email']=$_POST['email1'];
$data['block']='0';
$data['registerDate']= date('Y-m-d H:i:s');

$region=$_POST['profile_region'];

$data['country']=$_POST['profile_country'];

$data['company']=$_POST['profile_favoritebook'];
  
#######################################################
  
$db = JFactory::getDbo(); 
               
$query_user = $db->getQuery(true);

 $query_user->select('*') 
  ->from($db->quoteName('#__users')) 
  ->where($db->quoteName('username')." = ".$db->quote($data['username']),'OR') 
   ->where($db->quoteName('email')." = ".$db->quote($data['email']));

$db->setQuery($query_user); 

 $userDetail =  $db->loadAssocList();

 foreach($userDetail as $cValue){ 
        $dbEmail    =$cValue['email'];
        $dbUsername =$cValue['username'];
     
 }

################################################
if($dbUsername==$data['username'] && $dbEmail==$data['email']){ 
   
 echo '<div id="re-box">';
     echo ' <div class="re-center-box">User already exits.';  
     echo '<br /><br/>'; 
     echo '<div class="new-red-button" style="width: 70%; text-align: center; margin: 0px auto;"><a style="color:#fff" href="http://www.rasmentorshipforum.com/discussion-forum/contest/">Join the Discussion</a></div>';
     echo '<br />';
     echo '</div>';
    echo '</div>'; 


} //username and email already exits condition
######################################################
 
 else if($dbUsername == $_POST['username']){  
     
 echo '<div id="re-box">';
     echo ' <div class="re-center-box">Username already exits. Choose another username.';  
     echo '<br /><br/>'; 
     echo '<div class="new-red-button" style="width: 70%; text-align: center; margin: 0px auto;"><a style="color:#fff" href="http://www.rasmentorshipforum.com/discussion-forum/contest/">Join the Discussion</a></div>';
     echo '<br />';
     echo '</div>';
    echo '</div>'; 


   }  

###############################################
 else if($dbEmail == $_POST['email1']){  
     

 
 echo '<div id="re-box">';
     echo ' <div class="re-center-box">Email already exits. Choose another email.';  
     echo '<br /><br/>'; 
     echo '<div class="new-red-button" style="width: 70%; text-align: center; margin: 0px auto;"><a style="color:#fff" href="http://www.rasmentorshipforum.com/discussion-forum/contest/">Join the Discussion</a></div>';
     echo '<br />';
     echo '</div>';
    echo '</div>'; 


   }  

###############################################

else{
 
$serverurl =  $_SERVER['HTTP_HOST'];


$serverurl1 =  "http://www.rasmentorshipforum.com";
 
$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.2.min.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="http://w2ui.com/src/w2ui-1.4.2.min.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width; maximum-scale=1.0;">
<title>RAS</title>

<style type="text/css">
 body{ margin:0px; padding:0px;}
@media only screen and (max-width:598px){
table[class="mainWd"]{ width:100% !important; }
.img{ width:100% !important; }
}
@media only screen and (max-width:599px){
table{ float:none !important; }
table[class="mainWd"]{ width:100% !important; }
table[class="table-width"]{ float:left !important}
.img{ width:100% !important; }
@media only screen and (max-width:480px){
td[class="wd660"]{ width:100% !important; float:left !important; text-align:center !important; }
.img1{ display:none !important}
td[class="wd360"]{ width:100% !important; float:left !important; text-align:center; margin-bottom:20px; }	
table[class="full_480"]{ width:220px !important;  text-align:center !important;  float:none !important;  }	
td[class="mob_hide"]{ display:none !important; }
}
 
.img {width:100% !important; }
.img {width:100% !important; }
</style>
</head>

<body style="background:#000;-moz-text-size-adjust:none; -webkit-text-size-adjust:none; -ms-text-size-adjust:none;  ">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
<tr><td align="center">
	<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="mainWd" >
    
<tr><td height="25" align="center" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#ffffff; background:#2a4c75">Can’t see this email? View it in your browser. </td></tr> 
    

  
  
  <tr>
    <td align="left" valign="top" class="bg" bgcolor="#ffffff">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    
<tr>    <td height="20" align="left" valign="top"> <img src="http://'.$serverurl.'/images/ras-email-banner123.jpg" alt=" " class="img" border="0" align="left" style="display:block;width:100%"></td>    </tr>
 
    
     
     <tr><td height="20" align="center" valign="top"> </td></tr>
     
     <tr><td   align="center" valign="top">
       <table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
         <td align="left" valign="top"><p><span style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#343434; font-weight:normal;">Dear <span style="color:#f77635;text-transform:capitalize;">'.$data['name'].',</span><br /><br />Welcome to the RAS Mentorship Forum. Your registration process was a success and you’re one step closer to winning two free movie tickets.<br />
         </span><br />
              

             <span style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#343434; font-weight:normal;">Here are your login details:</span></p>

       </td></tr> 
 <tr><td height="5" align="center" valign="top"> </td></tr>
 
<tr>
  <td align="left" valign="top" style="border:1px dashed #1e7fc0; padding:10px;">
     
     
      <p style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:25px; color:#fd742f; font-weight:normal; "><span style="color:#000000;">Login: </span><a href="'.$serverurl1.'/discussion-forum/contest/">'.$serverurl1.'/discussion-forum/contest</a><br />
      <span style="color:#000000;">Username:</span> '.$data['username'].'<br /> 
      <span style="color:#000000;">Password:</span> '.$data['password'].'</p>


  </td></tr>
  <tr><td height="20" align="center" valign="top"> </td></tr>
<tr>
  <td align="left" valign="top"><span style="color:#000000;">.</span><br />
<br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:26px; line-height:20px; color:#454545; font-weight:bold;">Get started now!<br /><br />  </span>

<span style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#454545; font-weight:normal;">Best regards,<br /> 
Restaurant Association of Singapore</span>
 
 </td></tr>

 <tr>
   <td height="20" align="center" valign="top"></td></tr>
</table>
      </td>    
        
 
       <tr> <td align="center" valign="middle" height="37 " bgcolor="#2a4c75" > <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px  ; color:#ffffff;-webkit-text-size-adjust: none;">Copyright © 2016. RAS All rights reserved </span></td>  
         </tr> 
     

  
  
  </table>
  </td>
  </tr>
<tr>
  <td align="center">&nbsp;</td>
</tr>
</table>

</body>
</html>'; 

//echo $body;


$mailer  = JFactory::getMailer();
$config  = JFactory::getConfig();
 $subject = 'Welcome to RAS Mentorship Forum ';
  $from    = $config->get('mailfrom');
  $fromname = $config->get('fromname' ); 

$to = $data['email'];

$sender = array( 
    $from,
    $fromname
);




$mailer->isHTML(true);
$mailer->setSender($sender); 

$mailer->addRecipient($to);
$mailer->Encoding = 'base64';
$mailer->setSubject($subject);

$mailer->setBody($body);

$send= $mailer->Send();

 if($send){
 
$db = JFactory::getDbo(); 
  $query = $db->getQuery(true);

 $columns = array('name','username', 'email','password','block','registerDate','activation');      

	 $values = array($db->quote($data['name']),$db->quote($data['username']),$db->quote($data['email']),
	           $db->quote(md5($data['password'])),$db->quote($data['block']),
		   $db->quote($data['registerDate']),$db->quote($data['activation']));
 
         $query
		->insert($db->quoteName('ras_users'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
               $db->setQuery($query); 
		$result = $db->execute();
                $user_id = $db->insertid();
 
#######Contest Registered User Details########################
  
  $db = JFactory::getDbo(); 
  $query_contest = $db->getQuery(true);

   $columns = array('userid','name','username','email','source');      

   $values = array($db->quote($user_id),$db->quote($_POST['name']),$db->quote($_POST['username']),
                  $db->quote($_POST['email1']),$db->quote($source));
 
  $query_contest
		->insert($db->quoteName('contest_users_details'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
                $db->setQuery($query_contest); 
		$result6 = $db->execute();
               
###############################	
		$country = $db->getQuery(true);

		$columns = array('user_id','profile_key', 'profile_value');      
		$values = array($db->quote($user_id),$db->quote('profile.country'),$db->quote($data['country']));

  $country
			->insert($db->quoteName('ras_user_profiles'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		 $db->setQuery($country); 
		 $result1 = $db->execute(); 
      ################################################   

		$company = $db->getQuery(true);

		$columns = array('user_id','profile_key', 'profile_value');      
		$values = array($db->quote($user_id),$db->quote('profile.favoritebook'),$db->quote($data['company']));

  $company 
			->insert($db->quoteName('ras_user_profiles'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		 $db->setQuery($company); 
		 $result2 = $db->execute();
       
     ######################################################### 
		$region = $db->getQuery(true);

		$columns = array('user_id','profile_key', 'profile_value');      
		$values = array($db->quote($user_id),$db->quote('profile.region'),$db->quote($region));

		  $region 
			->insert($db->quoteName('ras_user_profiles'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		 $db->setQuery($region); 
		 $result3 = $db->execute();
      ###############################################################

		 $user_registration_usergroup = $db->getQuery(true);

		$columns = array('user_id','group_id');      
		$values = array($db->quote($user_id),$db->quote('2'));

		$user_registration_usergroup 
			->insert($db->quoteName('ras_user_usergroup_map'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		 $db->setQuery($user_registration_usergroup); 
		 $result4 = $db->execute();

     #########################################
	$karma_time1	                = date("Y-m-d h:i:sa"); //date("Ymdis");  
	$karma_time   			= strtotime($karma_time1); 

	$user_registration_forum = $db->getQuery(true);

	$columns = array('userid','karma_time');      
	$values = array($db->quote($user_id),$db->quote($karma_time));

	 $user_registration_forum 
			->insert($db->quoteName('ras_kunena_users'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
	 $db->setQuery($user_registration_forum); 
	 $result5 = $db->execute();

          $userlastid = $db->insertid();

         $urlMainSite = "http://".$_SERVER['HTTP_HOST'];
         $urlRed = $urlMainSite."/discussion-forum/contest/";



        echo '<div id="re-box">';
        echo ' <div class="re-center-box">Username and password have been sent to your registered email address. Please store them safely as you will need the information to login to the discussion forum.';  
     echo '<br /><br/>'; 
    echo '<div class="new-red-button" style="width: 70%; text-align: center; margin: 0px auto;"><a style="color:#fff" href="http://www.rasmentorshipforum.com/discussion-forum/contest/">Join the Discussion</a></div>';
    echo '<br />';
    echo '</div>';
    echo '</div>';
	       
      
}
 

   }//else condition if new user



  }//save button close


?>
  <div class="promo_contest contest-margin-top<?php echo $params->get( 'moduleclass_sfx' ) ?>">
    <div class="title red_text" style="font-size:36px;">Question</div>
    <div class="blue_text yellow-heading" >What do you think is Singapore's most iconic hawker dish and why?</div>
    <div class="promo_contest_bor_bottom"><!--//--></div>
    <br />
    <!--<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>--> 
    
    <script>
function myFunction(){
//alert('f1');
document.getElementById('in').style.display  = 'block';
document.getElementById('out').style.display  = 'none';
}
function myFunction2(){
//alert('f2');
document.getElementById('in').style.display  = 'none';
document.getElementById('out').style.display  = 'block';
}
</script>
    <div onclick="myFunc()">
      <label onclick="myFunction()" id="">
        <input type="radio" checked=checked name="group" value="new">
        <strong>New User</strong></label>
      &nbsp;&nbsp;&nbsp;&nbsp;      
      <label onclick="myFunction2()" id="">
        <input type="radio" name="group" value="existing">
        <strong>Existing User</strong></label>
    </div>
    <?php 
$user = JFactory::getUser(); 
$loggedIn = !$user->get('guest');  
$userToken = JSession::getFormToken();
$username =$user->username;

if($username!=""){ 

echo '<div id="out" class="" style="display: none; ">';
echo '<label style="padding-top:35px; padding-bottom:35px;"><strong>You are already logged in!</strong></label>';
echo '<div class="new-red-button" style="width: 70%;"><a style="color:#fff" href="http://www.rasmentorshipforum.com/discussion-forum/contest/">Join the Discussion</a></div>';
echo '</div>';
 
}

else{
$urld= '<a href="http://www.rasmentorshipforum.com/discussion-forum/contest/">Sign in </a>';
echo '<div id="out" class="" style="display: none;">';

echo '<label style=" padding-top:20px;"><strong>Already registered? Simply '.$urld.' to join the discussion.</strong></label>';

//echo '<div class="new-red-button" style="width: 70%; text-align: center; margin: 0px auto;"><a style="color:#fff" href="http://www.rasmentorshipforum.com/discussion-forum/contest/">Join the Discussion</a></div>';

echo '</div>';
}
  
 
?>
    <div id="in" class="">
      <div>
      <span style="color:green;" id="captcha_info"></span> <br />
      <div class="title red_text" style="font-size:17px;">Register to Participate</div>
      <form action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data" method="post" id="reg" name="reg" class="form-validate">
        <div class="componentheading"> </div>
        <div id="k2Container" class="k2AccountPage">
          <table class="admintable" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td><input required="required" aria-required="true" name="name" id="name" size="40" value="" class="inputbox required" maxlength="50" placeholder="Name*" type="text"></td>
              </tr>
              <tr>
                <td><input required="required" aria-required="true" id="username" name="username" size="40" value="" class="inputbox required validate-username" maxlength="25" placeholder="Username*" type="text"></td>
              </tr>
              <tr>
                <td><input required="required" aria-required="true" id="email" name="email1" size="40" value="" class="inputbox required validate-email" maxlength="100" placeholder="Email*" type="email"></td>
              </tr>
              <tr>
                <td><!--<input type="password" name="password" id="password" size="20" onkeyup="return check_password_safety(this.value);" /> -->
                  
                  <input required="required" aria-required="true" class="inputbox required validate-password" id="password" name="password1" size="40" value="" placeholder="Password*" onkeyup="return check_password_safety(this.value);" type="password">
                  <span id="password_info"></span></td>
              </tr>
              
              <!-- K2 attached fields --> 
              
              <!-- Joomla! 1.6+ JForm implementation -->
              
              <tr>
                  </td>
              </tr>
            </tbody>
          </table>
          <table>
          </table>
          
          <!--<div class="captcha_box">

 <div id="html_element"></div> 
 
</div>--> 
          
          <!-- <div class="captcha_box">
		

		</div>-->
          
          <div class="k2AccountPageNotice"></div>
          <div class="k2AccountPageUpdate">
            <input type="hidden" id="source" name="source" value="">
            <?php $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>
            <script> 
                var type = window.location.hash.substr(1);
                var url="<?php echo $url; ?>";
              document.getElementById("source").value =  type;
 
              </script> 
            <script type="text/javascript">
function popup() {
    w2popup.open({
        title: 'Popup Title',
        body: '<div class="w2ui-centered">This is text inside the popup</div>'
    });
}
</script>
            <button class="new-reg-button" id="save" name="save" type="submit"  onclick="popup()"> Register </button>
            </a> </div>
        </div>
        </div>
      </form>
    </div>
  </div>
  <div class="text-center"><img src="http://www.rasmentorshipforum.com/images/shadow.png" alt=""></div>
</div>

<div class="col-sm-12"> <br />
  <div class="text-center contest-title"> Join the discussion at RAS Mentorship Forum for your chance to win a pair of movie tickets every week. </div>
  <h3 class="text-center"><span class="red_text">Follow these simple steps</span></h3>
  <div class="text-center"> <img src="/images/arrow.png" alt="" /> </div>
  <br />
</div>
<div class="col-sm-4">
  <div class="text-center"> <img src="/images/sign-up.png" alt="" />
    <div class="yellow-heading">Sign up</div>
    <p class="blue_text text-center"><strong>Sign up for a free account at <br />
      RAS Mentorship Forum.</strong></p>
    <br />
  </div>
</div>
<div class="col-sm-4">
  <div class="text-center"> <img src="/images/join-the-discuss.png" alt="" />
    <div class="yellow-heading">Join the Discussion</div>
    <p class="blue_text text-center"><strong>Participate in the contest by <br />
      answering a simple question.</strong></p>
    <br />
  </div>
</div>
<div class="col-sm-4">
  <div class="text-center"> <img src="/images/win-movie.png" alt="" />
    <div class="yellow-heading">Win Movie tickets</div>
    <p class="blue_text text-center"><strong>Voila! The most creative <br />
      answer will walk away with a<br />
      pair of movie tickets from<br/>
      Golden Village!</strong></p>
  </div>
</div>
<div class="col-sm-12">
  <div class="col-sm-3"></div>
  <div class="col-sm-6"> 
    <!--<div class="new-red-button">Join the Discussion</div>--> 
    <br/>
    <div class="text-center"><a class="red_text" href="/winners">View Winners</a> | <a class="red_text" href="/terms">T&C Apply*</a></div></div>
  </div>
  <div class="col-sm-3"></div>
</div>
</div>
</div>
