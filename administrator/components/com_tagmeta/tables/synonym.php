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
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
* Tag Meta Synonym Table class
*
* @package TagMeta
*
*/
class TagMetaTableSynonym extends JTable
{
 function __construct(& $db) {
  parent::__construct('#__tagmeta_synonyms', 'id', $db);
 }

}
?>
