<?php
/**
* @version		$Id:fb.php 1 2015-06-04 06:35:13Z  $
* @package		Fb
* @subpackage 	Views
* @copyright	Copyright (C) 2015, . All rights reserved.
* @license #
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

 
class FbViewfbs  extends JViewLegacy {


	protected $items;

	protected $pagination;

	protected $state;
	
	
	/**
	 *  Displays the list view
 	 * @param string $tpl   
     */
	public function display($tpl = null)
	{
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		FbHelper::addSubmenu('fbs');

		$this->addToolbar();
		if(!version_compare(JVERSION,'3','<')){
			$this->sidebar = JHtmlSidebar::render();
		}
		
		if(version_compare(JVERSION,'3','<')){
			$tpl = "25";
		}
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		
		$canDo = FbHelper::getActions();
		$user = JFactory::getUser();
		JToolBarHelper::title( JText::_( 'Fb' ), 'generic.png' );
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('fb.add');
		}	
		
		if (($canDo->get('core.edit')))
		{
			JToolBarHelper::editList('fb.edit');
		}
		
				
		if ($this->state->get('filter.state') != 2)
		{
			JToolbarHelper::publish('fbs.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('fbs.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		}
				
		if ($canDo->get('core.edit.state'))
		{
			if ($this->state->get('filter.state') != -1)
			{
				if ($this->state->get('filter.state') != 2)
				{
					JToolbarHelper::archiveList('fbs.archive');
				}
				elseif ($this->state->get('filter.state') == 2)
				{
					JToolbarHelper::unarchiveList('fbs.publish');
				}
			}
			
		}
				
				if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::checkin('fbs.checkin');
		}
				

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'fbs.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
				elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('fbs.trash');
		}		
				
		
		JToolBarHelper::preferences('com_fb', '550');  
		if(!version_compare(JVERSION,'3','<')){		
			JHtmlSidebar::setAction('index.php?option=com_fb&view=fbs');
		}
				if(!version_compare(JVERSION,'3','<')){
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_state',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
			);
		}
				
					
	}	
	

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
		 	          'a.title' => JText::_('Title'),
	     	          'a.filetype' => JText::_('Filetype'),
	     	          'a.created_date' => JText::_('Created_date'),
	     	          'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
	     	          'a.state' => JText::_('JSTATUS'),
	     	          'a.id' => JText::_('JGRID_HEADING_ID'),
	     		);
	}	
}
?>
