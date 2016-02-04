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
jimport('joomla.application.component.modeladmin');

class TagMetaModelRule extends JModelAdmin
{
  /**
   * Returns a reference to the a Table object, always creating it
   *
   * @param  type  The table type to instantiate
   * @param  string  A prefix for the table class name. Optional
   * @param  array  Configuration array for model. Optional
   * @return  JTable  A database object
   */
  public function getTable($type = 'Rule', $prefix = 'TagMetaTable', $config = array())
  {
    return JTable::getInstance($type, $prefix, $config);
  }

  /**
   * Method to get the record form
   *
   * @param  array  $data    Data for the form
   * @param  boolean  $loadData  True if the form is to load its own data (default case), false if not
   * @return  mixed  A JForm object on success, false on failure
   */
  public function getForm($data = array(), $loadData = true)
  {
    // Get the form
    $form = $this->loadForm('com_tagmeta.rule', 'rule', array('control' => 'jform', 'load_data' => $loadData));
    if (empty($form)) {
      return false;
    }

    return $form;
  }

  /**
   * Method to get the data that should be injected in the form
   *
   * @return  mixed  The data for the form
   */
  protected function loadFormData()
  {
    // Check the session for previously entered form data
    $data = JFactory::getApplication()->getUserState('com_tagmeta.edit.rule.data', array());

    if (empty($data)) {
      $data = $this->getItem();
    }

    return $data;
  }

  /**
   * Prepare and sanitise the table data prior to saving
   *
   * @param  JTable  A JTable object
   */
  protected function prepareTable($table)
  {
    if (JFactory::getApplication()->input->get('task') == 'save2copy')
    {
      $table->url = JText::_('COM_TAGMETA_COPY_OF') . $table->url;
    }
    parent::prepareTable($table);
  }

  /**
   * Method to set case_sensitive property for specified items
   *
   * @access  public
   * @return  boolean  True on success
   */
  public function setcase($cid = array(), $case_sensitive = 1)
  {
    $user = JFactory::getUser();

    if (count($cid))
    {
      JArrayHelper::toInteger($cid);
      $cids = implode(',', $cid);

      $db = $this->getDbo();
      $query = 'UPDATE #__tagmeta_rules'
        . ' SET case_sensitive = ' . (int) $case_sensitive
        . ' WHERE id IN ( ' . $cids . ' )'
        . ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id') . ' ) )'
      ;
      $db->setQuery( $query );
      if(!$db->query()) {
        $this->setError($db->getErrorMsg());
        return false;
      }
    }

    return true;
  }

  /**
   * Method to set request_only property for specified items
   *
   * @access  public
   * @return  boolean  True on success
   */
  public function setrequest($cid = array(), $request_only = 1)
  {
    $user = JFactory::getUser();

    if (count($cid))
    {
      JArrayHelper::toInteger($cid);
      $cids = implode(',', $cid);

      $db = $this->getDbo();
      $query = 'UPDATE #__tagmeta_rules'
        . ' SET request_only = ' . (int) $request_only
        . ' WHERE id IN ( ' . $cids . ' )'
        . ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id') . ' ) )'
      ;
      $db->setQuery( $query );
      if(!$db->query()) {
        $this->setError($db->getErrorMsg());
        return false;
      }
    }

    return true;
  }

  /**
   * Method to set decode_url property for specified items
   *
   * @access  public
   * @return  boolean  True on success
   */
  public function setdecode($cid = array(), $decode_url = 1)
  {
    $user = JFactory::getUser();

    if (count($cid))
    {
      JArrayHelper::toInteger($cid);
      $cids = implode(',', $cid);

      $db = $this->getDbo();
      $query = 'UPDATE #__tagmeta_rules'
        . ' SET decode_url = ' . (int) $decode_url
        . ' WHERE id IN ( ' . $cids . ' )'
        . ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id') . ' ) )'
      ;
      $db->setQuery( $query );
      if(!$db->query()) {
        $this->setError($db->getErrorMsg());
        return false;
      }
    }

    return true;
  }

  /**
   * Method to set last_rule property for specified items
   *
   * @access  public
   * @return  boolean  True on success
   */
  public function setlastrule($cid = array(), $last_rule = 1)
  {
    $user = JFactory::getUser();

    if (count($cid))
    {
      JArrayHelper::toInteger($cid);
      $cids = implode(',', $cid);

      $db = $this->getDbo();
      $query = 'UPDATE #__tagmeta_rules'
        . ' SET last_rule = ' . (int) $last_rule
        . ' WHERE id IN ( ' . $cids . ' )'
        . ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id') . ' ) )'
      ;
      $db->setQuery( $query );
      if(!$db->query()) {
        $this->setError($db->getErrorMsg());
        return false;
      }
    }

    return true;
  }

  /**
   * Method to set preserve_title property for specified items
   *
   * @access  public
   * @return  boolean  True on success
   */
  public function setpreservetitle($cid = array(), $preserve_title = 1)
  {
    $user = JFactory::getUser();

    if (count($cid))
    {
      JArrayHelper::toInteger($cid);
      $cids = implode(',', $cid);

      $db = $this->getDbo();
      $query = 'UPDATE #__tagmeta_rules'
        . ' SET preserve_title = ' . (int) $preserve_title
        . ' WHERE id IN ( ' . $cids . ' )'
        . ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id') . ' ) )'
      ;
      $db->setQuery( $query );
      if(!$db->query()) {
        $this->setError($db->getErrorMsg());
        return false;
      }
    }

    return true;
  }

  /**
   * Method to set synonyms property for specified items
   *
   * @access  public
   * @return  boolean  True on success
   */
  public function setsynonyms($cid = array(), $synonyms = 0)
  {
    $user = JFactory::getUser();

    if (count($cid))
    {
      JArrayHelper::toInteger($cid);
      $cids = implode(',', $cid);

      $db = $this->getDbo();
      $query = 'UPDATE #__tagmeta_rules'
        . ' SET synonyms = ' . (int) $synonyms
        . ' WHERE id IN ( ' . $cids . ' )'
        . ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id') . ' ) )'
      ;
      $db->setQuery( $query );
      if(!$db->query()) {
        $this->setError($db->getErrorMsg());
        return false;
      }
    }

    return true;
  }

}
?>
