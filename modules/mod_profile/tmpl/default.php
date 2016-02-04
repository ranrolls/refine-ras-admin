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
?>

<?php $user =& JFactory::getUser();
$myid=$user->id;
 
$db=&JFactory::getDBO();
$query="SELECT * FROM `ras_kunena_users` WHERE `userid` ='".$myid."'"; 

$db->setQuery($query);
$result = $db->loadObject();
$profileimg=$result->avatar ;

 
?>

 


<div class="profile<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<img src='<?php echo JURI::root()."/media/kunena/avatars/".$profileimg ?>'>
</div>

