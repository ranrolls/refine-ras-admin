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
class TagMetaViewRules extends JViewLegacy
{
  protected $enabled;
  protected $items;
  protected $pagination;
  protected $state;

  public function display($tpl = null)
  {
    if ($this->getLayout() !== 'modal')
    {
      TagMetaHelper::addSubmenu('rules');
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
      JToolBarHelper::custom('rules.copy', 'copy.png', 'copy_f2.png', JText::_('COM_TAGMETA_TOOLBAR_COPY'));
      JToolBarHelper::addNew('rule.add');
    }

    if ($canDo->get('core.edit')) {
      JToolBarHelper::editList('rule.edit');
    }

    JToolBarHelper::divider();

    if ($canDo->get('core.edit.state')) {
      if ($this->state->get('filter.state') != 2){
        JToolBarHelper::publish('rules.publish', 'JTOOLBAR_PUBLISH', true);
        JToolBarHelper::unpublish('rules.unpublish', 'JTOOLBAR_UNPUBLISH', true);
      }

      JToolBarHelper::divider();

      if ($this->state->get('filter.state') != -1 ) {
        if ($this->state->get('filter.state') != 2) {
          JToolBarHelper::archiveList('rules.archive');
        }
        else if ($this->state->get('filter.state') == 2) {
          JToolBarHelper::unarchiveList('rules.publish');
        }
      }

      //JToolBarHelper::checkin('rules.checkin');
      JToolBarHelper::custom('rules.checkin', 'checkin', '', 'JTOOLBAR_CHECKIN', true);
      JToolBarHelper::trash('rules.trash');

    }

    if ( $canDo->get('core.delete')) {
      JToolBarHelper::deleteList('', 'rules.delete', 'JTOOLBAR_EMPTY_TRASH');
    }

    JToolBarHelper::divider();

    if ($canDo->get('core.admin')) {
      JToolBarHelper::preferences('com_tagmeta');
    }

    JHtmlSidebar::setAction('index.php?option=com_tagmeta&view=rules');

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
      'a.id' => JText::_('COM_TAGMETA_HEADING_RULES_ID'),
      'a.url' => JText::_('COM_TAGMETA_HEADING_RULES_URL'),
      'a.case_sensitive' => JText::_('COM_TAGMETA_HEADING_RULES_CASE_SENSITIVE'),
      'a.request_only' => JText::_('COM_TAGMETA_HEADING_RULES_REQUEST_ONLY'),
      'a.decode_url' => JText::_('COM_TAGMETA_HEADING_RULES_DECODE_URL'),
      'a.last_rule' => JText::_('COM_TAGMETA_HEADING_RULES_LAST_RULE'),
      'a.title' => JText::_('COM_TAGMETA_HEADING_RULES_TITLE'),
      'a.description' => JText::_('COM_TAGMETA_HEADING_RULES_DESCRIPTION'),
      'a.author' => JText::_('COM_TAGMETA_HEADING_RULES_AUTHOR'),
      'a.keywords' => JText::_('COM_TAGMETA_HEADING_RULES_KEYWORDS'),
      'a.rights' => JText::_('COM_TAGMETA_HEADING_RULES_RIGHTS'),
      'a.xreference' => JText::_('COM_TAGMETA_HEADING_RULES_XREFERENCE'),
      'a.canonical' => JText::_('COM_TAGMETA_HEADING_RULES_CANONICAL'),
      'a.comment' => JText::_('COM_TAGMETA_HEADING_RULES_COMMENT'),
      'a.synonyms' => JText::_('COM_TAGMETA_HEADING_RULES_SYNONYMS'),
      'a.preserve_title' => JText::_('COM_TAGMETA_HEADING_RULES_PRESERVE_TITLE'),
      'a.hits' => JText::_('COM_TAGMETA_HEADING_RULES_HITS'),
      'a.last_visit' => JText::_('COM_TAGMETA_HEADING_RULES_LAST_VISIT'),
      'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
      'a.published' => JText::_('COM_TAGMETA_HEADING_RULES_PUBLISHED')
    );
  }

}
?>
