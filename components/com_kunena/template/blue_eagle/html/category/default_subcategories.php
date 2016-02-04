<?php
/**
 * Kunena Component
 * @package Kunena.Template.Blue_Eagle
 * @subpackage Category
 *
 * @copyright (C) 2008 - 2015 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();


 
define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__) );//this is when we are in the root
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
//$user = JFactory::getUser();

  $alias = basename($_SERVER['REQUEST_URI']);

$db = JFactory::getDBO();

   $userQuery = "SELECT *
FROM `ras_kunena_categories`
WHERE alias = '".$alias."' ";


//var_dump($_POST);

 $db->setQuery($userQuery);

 $userData = $db->loadObjectList();
 
//print_r($userData);
 foreach($userData as $fulldata){

 $title= $fulldata->name; 

 echo '<div class="kheader" style=" background:#2a4c75 !important;">
		 
 <h1><span> '.$title.'</span></h1>
 
 </div>';

}



 



include dirname(__FILE__) . '/list_embed.php';
