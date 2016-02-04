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

/**
 * Tag Meta Helper
 *
 * @package TagMeta
 *
 */
class TagMetaHelper
{
  /**
   * Configure the Linkbar
   *
   * @param string The name of the active view
   *
   * @return void
   */
  public static function addSubmenu($vName)
  {
    $document = JFactory::getDocument();
    $document->addStyleDeclaration('.icon-48-tagmeta {background-image: url(../administrator/components/com_tagmeta/images/icon-48-tagmeta.png);}');

    JHtmlSidebar::addEntry(
      JText::_('COM_TAGMETA_MENU_RULES'),
      'index.php?option=com_tagmeta&view=rules',
      $vName == 'rules'
    );

    JHtmlSidebar::addEntry(
      JText::_('COM_TAGMETA_MENU_SYNONYMS'),
      'index.php?option=com_tagmeta&view=synonyms',
      $vName == 'synonyms'
    );

    JHtmlSidebar::addEntry(
      JText::_('COM_TAGMETA_MENU_ABOUT'),
      'index.php?option=com_tagmeta&view=about',
      $vName == 'about'
    );

  }

  /**
   * Gets a list of the actions that can be performed
   *
   * @return JObject
   * @since 1.6
   */
  public static function getActions()
  {
    $user = JFactory::getUser();
    $result = new JObject;

    $assetName = 'com_tagmeta';

    $actions = array(
      'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
    );

    foreach ($actions as $action) {
      $result->set($action,  $user->authorise($action, $assetName));
    }

    return $result;
  }

  /**
   * Return utf-8 substrings
   *
   * http://www.php.net/manual/en/function.substr.php#90148
   *
   */
  public static function substru($str, $from, $len)
  {
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $from .'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $len .'}).*#s','$1', $str);
  }

  public static function trimText($text_to_trim, $max_chars = '50')
  {
    $to_be_continued = '...';
    
    if ( (function_exists('mb_strlen')) && (function_exists('mb_substr')) )
    {
      // MultiByte version
      if( mb_strlen( $text_to_trim, 'UTF-8' ) > $max_chars ) {
        return mb_substr( $text_to_trim, 0, $max_chars, 'UTF-8' ) . $to_be_continued;
      } else {
        return $text_to_trim;
      }
    } else {
      // Safe version
      $text_trimmed = self::substru($text_to_trim, 0, $max_chars);
      if ( strlen($text_trimmed) < strlen ($text_to_trim) )
      {
        return $text_trimmed . $to_be_continued;
      } else {
        return $text_to_trim;
      }
    }
  }

  /**
   * Determines if the plugin for Tag Meta to work is enabled
   *
   * @return boolean
   */
  public static function isEnabled()
  {
    $db = JFactory::getDbo();
    $db->setQuery(
      'SELECT enabled' .
      ' FROM #__extensions' .
      ' WHERE folder = '.$db->quote('system').
      '  AND element = '.$db->quote('tagmeta')
    );
    $result = (boolean) $db->loadResult();
    if ($error = $db->getErrorMsg()) {
      JError::raiseWarning(500, $error);
    }
    return $result;
  }

}
