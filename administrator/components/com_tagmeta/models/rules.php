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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class TagMetaModelRules extends JModelList
{
  public function __construct($config = array())
  {
    if (empty($config['filter_fields'])) {
      $config['filter_fields'] = array(
        'id', 'a.id',
        'url', 'a.url',
        'case_sensitive', 'a.case_sensitive',
        'request_only', 'a.request_only',
        'decode_url', 'a.decode_url',
        'last_rule', 'a.last_rule',
        'placeholders', 'a.placeholders',
        'title', 'a.title',
        'description', 'a.description',
        'author', 'a.author',
        'keywords', 'a.keywords',
        'rights', 'a.rights',
        'xreference', 'a.xreference',
        'canonical', 'a.canonical',
        'rindex', 'a.rindex',
        'rfollow', 'a.rfollow',
        'rsnippet', 'a.rsnippet',
        'rarchive', 'a.rarchive',
        'rodp', 'a.rodp',
        'rimageindex', 'a.rimageindex',
        'comment', 'a.comment',
        'synonyms', 'a.synonyms',
        'synonmax', 'a.synonmax',        
        'synonweight', 'a.synonweight',
        'preserve_title', 'a.preserve_title',
        'hits', 'a.hits',
        'last_visit', 'a.last_visit',
        'ordering', 'a.ordering',
        'published', 'a.published',
        'checked_out', 'a.checked_out',
      );
    }

    parent::__construct($config);
  }

  /**
   * Method to auto-populate the model state
   *
   * Note. Calling getState in this method will result in recursion
   */
  protected function populateState($ordering = null, $direction = null)
  {
    $state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
    $this->setState('filter.state', $state);

    $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
    // Convert search to lower case
    $search = JString::strtolower($search);
    $this->setState('filter.search', $search);

    // Load the parameters.
    $params = JComponentHelper::getParams('com_tagmeta');
    $this->setState('params', $params);

    // List state information.
    parent::populateState('a.ordering', 'asc');
  }

  /**
   * Method to build an SQL query to load the list data
   *
   * @return string An SQL query
   */
  protected function getListQuery()
  {
    // Create a new query object
    $db = $this->getDbo();
    $query = $db->getQuery(true);
    // Select required fields
    $query->select('a.id, a.url, a.case_sensitive, a.request_only, a.decode_url, a.last_rule, a.placeholders, a.title, a.description, a.author, a.keywords, a.rights, a.xreference, a.canonical, a.rindex, a.rfollow, a.rsnippet, a.rarchive, a.rodp, a.rimageindex, a.comment, a.synonyms, a.synonmax, a.synonweight, a.preserve_title, a.hits, a.last_visit, a.ordering, a.published, a.checked_out');

    // From the table
    $query->from('#__tagmeta_rules AS a');

    // Filter by state
    $state = $this->getState('filter.state');
    if (is_numeric($state)) {
      $query->where('a.published = '.(int) $state);
    } else if ($state === '') {
      $query->where('(a.published IN (0, 1))'); // By default only published and unpublished
    }

    // Filter by search
    $search = $this->getState('filter.search');
    if (!empty($search)) {
      $query->where('LOWER(a.url) LIKE '.$db->Quote('%'.$db->escape($search, true).'%').'OR LOWER(a.title) LIKE '.$db->Quote('%'.$db->escape($search, true).'%')); // Search in url and title only
    }

    // Add the list ordering clause
    $orderCol = $this->state->get('list.ordering', 'a.ordering');
    $orderDirn = $this->state->get('list.direction', 'asc');
    $query->order($db->escape($orderCol.' '.$orderDirn));

    return $query;
  }

}
?>
