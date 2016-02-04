<?php
/**
 * ------------------------------------------------------------------------
 * JUDirectory for Joomla 2.5, 3.x
 * ------------------------------------------------------------------------
 *
 * @copyright      Copyright (C) 2010-2015 JoomUltra Co., Ltd. All Rights Reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @author         JoomUltra Co., Ltd
 * @website        http://www.joomultra.com
 * @----------------------------------------------------------------------@
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();

JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
$function = $app->input->get('function', 'jSelectImage');
$markerId = $app->input->get('markerId', '0');
?>
<form
	action="<?php echo JRoute::_('index.php?option=com_judirectory&view=defaultimages&tmpl=component'); ?>"
	method="post" name="adminForm" id="adminForm" class="form-inline <?php echo $this->type; ?>">
	<div class="folders">
		<label for="folders"><?php echo JText::_('COM_JUDIRECTORY_FOLDER'); ?></label>
		<?php echo JHtml::_('select.genericlist', $this->folders, 'folder', 'onchange="this.form.submit();"', 'value', 'text', $this->folder, 'folders') ?>
	</div>

	<?php if (empty($this->images)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('COM_JUDIRECTORY_NO_IMAGE_FOUND'); ?>
		</div>
	<?php else : ?>
		<ul class="list-image">
			<?php
			foreach ($this->images as $image)
			{
				?>
				<li>
					<a href="javascript:void(0)"
					   onclick="if (window.parent) window.parent.<?php echo $this->escape($function); ?>('<?php echo $image; ?>', '<?php echo $markerId;?>');">
						<img src="<?php echo $this->image_url . $image; ?>"/>
					</a>
				</li>
			<?php } ?>
		</ul>
	<?php endif; ?>
	<div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="type" value="<?php echo $this->type; ?>"/>
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>