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

?>

<?php
 
//print_r($this->categoryButtons);

  
$but= array_keys($this->categoryButtons)[0] . "\r\n";

define('JPATH_BASE', dirname(__FILE__) );//this is when we are in the root
define( 'DS', DIRECTORY_SEPARATOR );
 

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
  
$seralias = basename($_SERVER['REQUEST_URI']);

$db = JFactory::getDBO();

$userQuery = "SELECT * FROM `ras_kunena_categories` WHERE alias = '".$seralias."' and parent_id='1' ";
  
$db->setQuery($userQuery);

$userData = $db->loadObjectList();
 
 //print_r($userData);

foreach($userData as $fulldata){

$alias= $fulldata->alias; 

if($alias==$seralias){

//echo "vishal";

echo '<td class="klist-actions-forum">';
 
echo '<div class="kmessage-buttons-row"></div>';  
  
echo '</td>';
  
}
 
 

else{
  
}


}

?>
  
 <?php if (($this->categoryButtons) && ($alias==$seralias)){  
 
//echo "vishal";

?>
      <td class="klist-actions-forum">
 
      <div class="kmessage-buttons-row"></div>  
  
      </td>

 

 <?php }  else {

//echo "test";
$aa= implode(' ', $this->categoryButtons);
echo '<td class="klist-actions-forum">';
 
      echo '<div class="kmessage-buttons-row">'.$aa.'</div>';  
  
      echo '</td>';


}


?>
 
    
 
 
 