<?php
/**
 * Tag Meta Community component for Joomla
 *
 * @author selfget.com (info@selfget.com)
 * @package TagMeta
 * @copyright Copyright 2009 - 2011
 * @license GNU Public License
 * @link http://www.selfget.com
 * @version 1.7.2
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controllerform');

/**
 * Tag Meta Controller Rule
 *
 * @package TagMeta
 *
 */
class TagMetaControllerRule extends JControllerForm
{
  public function save($key = null, $urlVar = null)
  {
    // Check for request forgeries.
    JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

    // Fill the form data with checkbox values
    $data = $this->input->post->get('jform', array(), 'array');
    $data['url'] = array_key_exists('url', $data) ? trim($data['url']) : '.';
    $data['case_sensitive'] = array_key_exists('case_sensitive', $data) ? 1 : 0;
    $data['request_only'] = array_key_exists('request_only', $data) ? 1 : 0;
    $data['decode_url'] = array_key_exists('decode_url', $data) ? 1 : 0;
    $data['last_rule'] = array_key_exists('last_rule', $data) ? 1 : 0;
    $data['synonyms_weight'] = array_key_exists('synonyms_weight', $data) ? 1 : 0;
    $data = $this->input->post->set('jform', $data);

    return parent::save($key, $urlVar);
  }

}