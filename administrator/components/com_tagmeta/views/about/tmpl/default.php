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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
?>
<?php if (!empty( $this->sidebar)): ?>
  <div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
  </div>
  <div id="j-main-container" class="span10">
<?php else : ?>
  <div id="j-main-container">
<?php endif;?>
<?php
  echo "<div style=\"float: left; margin-right: 20px;\"><img src=\"".JURI::root()."administrator/components/com_tagmeta/images/logo.png\" alt=\"Tag Meta\"/></div>";
  echo "<div style=\"float: none;\">".JText::_('COM_TAGMETA_COPYRIGHT')."</div>";
  echo "<div>".JText::_('COM_TAGMETA_ABOUT_DESC')."<br /><br /></div>";
  echo "<div>".JText::_('COM_TAGMETA_DONATE')."<br /><br /></div>";
  echo "<div>".JText::_('COM_TAGMETA_DONATE_PAYPAL')."</div>";
?>
  </div>