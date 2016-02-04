<?php
/**
 * Tag Meta Community component for Joomla
 *
 * @author selfget.com (info@selfget.com)
 * @package TagMeta
 * @copyright Copyright 2009 - 2013
 * @license GNU Public License
 * @link http://www.selfget.com
 * @version 1.7.2
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Tag Meta Controller About
 *
 * @package TagMeta
 *
 */
class TagMetaControllerAbout extends JControllerAdmin
{
    public function __construct($config = array())
    {
        parent::__construct($config);
    }
}
?>
