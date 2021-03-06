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
JHtml::_('behavior.multiselect');

if(JUDirectoryHelper::isJoomla3x()){
	$classTooltip =  "hasTooltip";
	$separator = "<br/>";
	JHtml::_('bootstrap.tooltip');
} else {
	JHtml::_('behavior.tooltip');
	$separator = "::";
	$classTooltip = "hasTip";
}

$session = JFactory::getSession();

$ignored_cat_id = $session->get('moved_cat_id_arr');
$move_cat_id_arr = $session->get('moved_cat_id_arr');
$categories = array();
foreach($move_cat_id_arr as $cat_id){
	$categories[] = JUDirectoryHelper::getCategoryById($cat_id);
}
?>

<div class="jubootstrap">

	<div id="iframe-help"></div>

	<form action="<?php echo JRoute::_('index.php?option=com_judirectory&view=listings'); ?>" method="post" name="adminForm" id="adminForm">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JUDIRECTORY_MOVE_CATEGORIES'); ?></legend>
			<div class="span6">
				<div class="form-horizontal">
					<div class="control-group">
						<div class="control-label">
								<label><?php echo JText::_('COM_JUDIRECTORY_MOVE_TO_CATEGORY'); ?></label>
						</div>
						<div class="controls">
							<?php
								$options = JUDirectoryHelper::getCategoryOptions(1, true, "category", false, $ignored_cat_id);
								echo JHtml::_('select.genericList', $options, 'categories', '', 'value', 'text', "");
							?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<label class="<?php echo $classTooltip; ?>" for="extra_fields" title="<?php echo JText::_('COM_JUDIRECTORY_KEEP_EXTRA_FIELDS') . $separator . JText::_('COM_JUDIRECTORY_KEEP_EXTRA_FIELDS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_KEEP_EXTRA_FIELDS'); ?></label>
						</div>
						<div class="controls">
							<input type="checkbox" checked="checked" name="move_options[]" value="keep_extra_fields" id="extra_fields" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<label class="<?php echo $classTooltip; ?>" for="keep_rates" title="<?php echo JText::_('COM_JUDIRECTORY_KEEP_RATES') . $separator . JText::_('COM_JUDIRECTORY_KEEP_RATES_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_KEEP_RATES'); ?></label>
						</div>
						<div class="controls">
							<input type="checkbox" checked="checked" name="move_options[]" value="keep_rates" id="keep_rates" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<label class="<?php echo $classTooltip; ?>" for="permission" title="<?php echo JText::_('COM_JUDIRECTORY_KEEP_PERMISSION') . $separator . JText::_('COM_JUDIRECTORY_KEEP_PERMISSION_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_KEEP_PERMISSION'); ?></label>
						</div>
						<div class="controls">
							<input type="checkbox" checked="checked" name="move_options[]" value="keep_permission" id="permission" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<label class="<?php echo $classTooltip; ?>" for="keep_template_params" title="<?php echo JText::_('COM_JUDIRECTORY_KEEP_TEMPLATE_PARAMS') . $separator . JText::_('COM_JUDIRECTORY_KEEP_TEMPLATE_PARAMS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_KEEP_TEMPLATE_PARAMS'); ?></label>
						</div>
						<div class="controls">
							<input type="checkbox" checked="checked" name="move_options[]" value="keep_template_params" id="keep_template_params" />
						</div>
					</div>
				</div>
			</div>
			<div class="span6">
				<div>
					<?php
					echo JText::plural('COM_JUDIRECTORY_TOTAL_N_CATEGORIES_WILL_BE_MOVED', count($categories));
					?>
					<ul>
						<?php
						foreach($categories as $category)
						{ ?>
							<li>
								<?php echo $category->title;?>
							</li>
						<?php
						} ?>
					</ul>
				</div>
				<div class="right"><?php echo JText::_('COM_JUDIRECTORY_MOVE_CATEGORIES_MESSAGE'); ?></div>
			</div>

			<div>
				<input type="hidden" name="task" value="listings.moveListings" />
				<input type="hidden" name="option" value="com_judirectory" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</fieldset>
	</form>
</div>