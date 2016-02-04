<?php
/**
 * @package         Advanced Module Manager
 * @version         4.22.7
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_advancedmodules/views/module/view.html.php';
require_once JPATH_ADMINISTRATOR . '/components/com_advancedmodules/helpers/modules.php';

class AdvancedModulesViewEdit extends AdvancedModulesViewModule
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->setLayout('edit');

		parent::display($tpl);
	}

	/**
	 * Function that gets the config settings
	 *
	 * @return    Object
	 */
	protected function addToolbar()
	{
		return ;
	}

}
