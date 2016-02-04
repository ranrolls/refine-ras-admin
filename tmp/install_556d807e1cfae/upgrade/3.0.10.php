<?php
/**
 * Kunena SEF extension for ARTIO JoomSEF
 * 
 * @package   JoomSEF
 * @author    ARTIO s.r.o., http://www.artio.net
 * @copyright Copyright (C) 2014 ARTIO s.r.o. 
 * @license   GNU/GPLv3 http://www.artio.net/license/gnu-general-public-license
 */

defined('_JEXEC') or die();

$this->_addFileOp('/components/com_sef/sef_ext/com_kunena.php', 'upgrade', '/com_kunena.php');
$this->_addFileOp('/components/com_sef/sef_ext/com_kunena.xml', 'upgrade', '/com_kunena.xml');

?>