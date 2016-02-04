<?php defined('_JEXEC') or die('Restricted access'); ?>
##codestart##
/**
* @version		$Id:controller.php  1 ##date##Z ##sauthor## $
* @package		##Component##
* @subpackage 	Controllers
* @copyright	Copyright (C) ##year##, ##author##. All rights reserved.
* @license 		##license##
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * ##Component## Controller
 *
 * @package    
 * @subpackage Controllers
 */
class ##Component##Controller extends JControllerLegacy
{

    /**
    * Constructor.
    *
    * @param	array An optional associative array of configuration settings.
    * @see		JController
    */
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->input = JFactory::getApplication()->input;
    }

	public function display($cachable = false, $urlparams = false)
	{
		$cachable	= true;	
		<?php if($this->uses_categories): ?> 
		$user		= JFactory::getUser();

		$id    = $this->input->getInt('id', null);
		$vName = $this->input->get('view', 'categories');
		$this->input->set('view', $vName);
		$rMethod = $this->input->getMethod();

		if ($user->get('id') ||($rMethod == 'POST' && $vName = 'categories'))
		{
			$cachable = false;
		}

		$safeurlparams = array(
			'id'				=> 'INT',
			'limit'				=> 'UINT',
			'limitstart'		=> 'UINT',
			'filter_order'		=> 'CMD',
			'filter_order_Dir'	=> 'CMD',
			'lang'				=> 'CMD'
		);

		// Check for edit form.
		if ($vName == 'form' && !$this->checkEditId('com_##component##.edit.##firstnames##', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}
		<?php else: ?>
		$vName = $this->input->get('view', '##firstnames##');
		$this->input->set('view', $vName);
		$safeurlparams = array('id' => 'INT');
		<?php endif; ?> 
		return parent::display($cachable, $safeurlparams);
	}	
	

}// class
##codeend##