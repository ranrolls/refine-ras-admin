<?php


define( '_JEXEC', 1 );
define( 'JPATH_BASE', str_replace('/webservice/module','',dirname(__FILE__)) ); 	# This is when we are in the root
define( 'DS', DIRECTORY_SEPARATOR );
require_once( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'framework.php' );
jimport('joomla.user.helper');
  

  echo $activation = JApplicationHelper::getHash(JUserHelper::genRandomPassword());

 //$activation = md5($password);


?>