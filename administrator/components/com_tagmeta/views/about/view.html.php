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

jimport( 'joomla.application.component.view');

require_once JPATH_COMPONENT.'/helpers/tagmeta.php';

/**
 * HTML View class for the Tag Meta About component
 *
 * @package TagMeta
 *
 */
class TagMetaViewAbout extends JViewLegacy
{
  protected $items;
  protected $pagination;
  protected $state;

  public function display($tpl = null)
  {
    if ($this->getLayout() !== 'modal')
    {
      TagMetaHelper::addSubmenu('about');
    }

    // Initialise variables
    $this->items = $this->get('Items');
    $this->pagination = $this->get('Pagination');
    $this->state = $this->get('State');

    // Check for errors
    if (count($errors = $this->get('Errors'))) {
      JError::raiseError(500, implode("\n", $errors));
      return false;
    }

    // We don't need toolbar in the modal window.
    if ($this->getLayout() !== 'modal') {
      $this->addToolbar();
      $this->sidebar = JHtmlSidebar::render();
    }

    parent::display($tpl);
  }

  /**
   * Add the page title and toolbar
   *
   */
  protected function addToolbar()
  {
    JToolBarHelper::title(JText::_('COM_TAGMETA_MANAGER'), 'tagmeta.png');

    require_once JPATH_COMPONENT.'/helpers/tagmeta.php';
    $canDo = TagMetaHelper::getActions();

    if ($canDo->get('core.admin')) {
      JToolBarHelper::preferences('com_tagmeta');
    }

    JHtmlSidebar::setAction('index.php?option=com_tagmeta&view=about');

  }

}
?>
