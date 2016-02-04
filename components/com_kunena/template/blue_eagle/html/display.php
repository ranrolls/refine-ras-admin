<?php
/**
 * Kunena Component
 * @package Kunena.Template.Blue_Eagle
 *
 * @copyright (C) 2008 - 2015 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();
?>

<h1 style="font-size: 24px; font-weight: bold;color:#2b4d76;font-family:SlabThing;">Discuss All About the F&B Industry in Singapore</h1>
<div id="Kunena" class="layout container-fluid">
<?php
if ($this->ktemplate->params->get('displayMenu', 1)) {
	$this->displayMenu ();

}
  
define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__) );//this is when we are in the root
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
$user = JFactory::getUser();

//echo '<pre/>';
//print_r($user);
$serverlink=  $_SERVER['REQUEST_URI'];

//$url = JUri::current();
//echo $url;

//$user = &JFactory::getUser();
$user_id = $user->username;
 
if($user_id){
echo '';
}
else if($serverlink=='/my-account'){
echo '';
}


else{
 //Please login first to view the forums.
 

echo '<div style="padding: 10px 0px 10px 0px; font-weight:bold;" > Welcome to the discussion forum. If this is your first visit, be sure to read the <a href="/guideline" target="_new"><strong>Guideline</strong></a> first. You will need to <a href="/register" target="_new"><strong>Register</strong></a> before you can read and post in the discussion forum. To start viewing the ongoing discussion, select the discussion area that you want to visit from the categories below.
</div>';
 


}

$this->displayLoginBox ();

$this->displayBreadcrumb ();

// Display current view/layout
$this->displayLayout();

$this->displayBreadcrumb ();
$this->displayFooter ();
?>
</div>
