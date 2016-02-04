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
 * HTML View class for the Tag Meta Rules component
 *
 * @package TagMeta
 *
 */
class TagMetaViewSynonyms extends JViewLegacy
{
  protected $enabled;
  protected $items;
  protected $pagination;
  protected $state;

  public function display($tpl = null)
  {
    if ($this->getLayout() !== 'modal')
    {
      TagMetaHelper::addSubmenu('synonyms');
    }

    // Initialise variables
    $this->enabled = TagMetaHelper::isEnabled();
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

    $canDo = TagMetaHelper::getActions();

    if ($canDo->get('core.create')) {
      JToolBarHelper::custom('synonyms.copy', 'copy.png', 'copy_f2.png', JText::_('COM_TAGMETA_TOOLBAR_COPY'));
      JToolBarHelper::addNew('synonym.add');
    }

    if ($canDo->get('core.edit')) {
      JToolBarHelper::editList('synonym.edit');
    }

    JToolBarHelper::divider();

    if ($canDo->get('core.edit.state')) {
      if ($this->state->get('filter.state') != 2){
        JToolBarHelper::publish('synonyms.publish', 'JTOOLBAR_PUBLISH', true);
        JToolBarHelper::unpublish('synonyms.unpublish', 'JTOOLBAR_UNPUBLISH', true);
      }

      JToolBarHelper::divider();

      if ($this->state->get('filter.state') != -1 ) {
        if ($this->state->get('filter.state') != 2) {
          JToolBarHelper::archiveList('synonyms.archive');
        }
        else if ($this->state->get('filter.state') == 2) {
          JToolBarHelper::unarchiveList('synonyms.publish');
        }
      }

      //JToolBarHelper::checkin('synonyms.checkin');
      JToolBarHelper::custom('synonyms.checkin', 'checkin', '', 'JTOOLBAR_CHECKIN', true);
      JToolBarHelper::trash('synonyms.trash');

    }

    if ( $canDo->get('core.delete')) {
      JToolBarHelper::deleteList('', 'synonyms.delete', 'JTOOLBAR_EMPTY_TRASH');
    }

    JToolBarHelper::divider();

    if ($canDo->get('core.admin')) {
      JToolBarHelper::preferences('com_tagmeta');
    }

    JHtmlSidebar::setAction('index.php?option=COM_TAGMETA&view=synonyms');

    JHtmlSidebar::addFilter(
      JText::_('JOPTION_SELECT_PUBLISHED'),
      'filter_state',
      JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
    );

  }

  /**
   * Returns an array of fields the table can be sorted by
   *
   * @return  array  Array containing the field name to sort by as the key and display text as value
   *
   * @since   3.0
   */
  protected function getSortFields()
  {
    return array(
      's.id' => JText::_('COM_TAGMETA_HEADING_SYNONYMS_ID'),
      's.keywords' => JText::_('COM_TAGMETA_HEADING_SYNONYMS_KEYWORDS'),
      's.synonyms' => JText::_('COM_TAGMETA_HEADING_SYNONYMS_SYNONYMS'),
      's.weight' => JText::_('COM_TAGMETA_HEADING_SYNONYMS_WEIGHT'),
      's.comment' => JText::_('COM_TAGMETA_HEADING_SYNONYMS_COMMENT'),
      's.hits' => JText::_('COM_TAGMETA_HEADING_SYNONYMS_HITS'),
      's.last_visit' => JText::_('COM_TAGMETA_HEADING_SYNONYMS_LAST_VISIT'),
      's.ordering' => JText::_('JGRID_HEADING_ORDERING'),
      's.published' => JText::_('COM_TAGMETA_HEADING_SYNONYMS_PUBLISHED')
    );
  }

}
?>
