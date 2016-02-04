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
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$app = JFactory::getApplication();
$id = $app->input->getInt('id', 0);
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'modpendingcomment.cancel' || task == 'modcomment.cancel' || document.formvalidator.isValid(document.id('comment-form'))) {
			Joomla.submitform(task, document.getElementById('comment-form'));
		}
	};

	function jInsertFieldValue(value, id) {
		var old_value = document.id(id).value,
			new_value;
		if (old_value != value) {
			var elem = document.id(id);
			if (value != '') {
				new_value = value;
			} else {
				new_value = '';
			}
			elem.value = new_value;
			elem.fireEvent("change");
			if (typeof(elem.onchange) === "function") {
				elem.onchange();
			}
			jMediaRefreshPreview(id);
		}
	}

	function jMediaRefreshPreview(id) {
		var value = document.id(id).value;
		var img = document.id(id + "_preview");
		if (img) {
			if (value) {
				img.src = value;
				document.id(id + "_preview_empty").setStyle("display", "none");
				document.id(id + "_preview_img").setStyle("display", "");
			} else {
				img.src = "";
				document.id(id + "_preview_empty").setStyle("display", "");
				document.id(id + "_preview_img").setStyle("display", "none");
			}
		}
	}

	function jSelectUser_jform_user_id(id, title) {
		var old_id = document.getElementById("jform_user_id").value;
		if (old_id != id) {
			document.getElementById("jform_user_id").value = id;
			document.getElementById("jform_author_name").value = title;

		}
		SqueezeBox.close();
	}
</script>

<div id="judir-container" class="jubootstrap component judir-container view-modcomment">
	<h2><?php echo JText::_('COM_JUDIRECTORY_EDIT_COMMENT'); ?></h2>

	<form
		action="<?php echo JRoute::_('index.php?option=com_judirectory&layout=edit&id=' . (int) $this->item->id); ?>"
		method="post" name="comment-form" id="comment-form" class="form-validate">

		<?php
		// Approval box
		if (JFactory::getApplication()->input->getInt('approve', 0) == 1)
		{
			?>
			<div class="judir-submit-buttons clearfix">
				<button type="button" class="btn btn-default btn-primary"
				        onclick="Joomla.submitbutton('modpendingcomment.save')">
					<i class="fa fa-save"></i> <?php echo JText::_('COM_JUDIRECTORY_SAVE'); ?>
				</button>
				<button type="button" class="btn btn-default"
				        onclick="Joomla.submitbutton('modpendingcomment.cancel')">
					<?php echo JText::_('COM_JUDIRECTORY_CANCEL'); ?>
				</button>
			</div>

			<div class="judir-approval-container clearfix">
				<div class="judir-approval-inner">
					<?php
					// @todo recheck hosting
					require_once JPATH_SITE . '/components/com_judirectory/models/modpendingcomments.php';
					JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_judirectory/models');
					$modelModPendingComments = JModelLegacy::getInstance('ModPendingComments','JUDirectoryModel');
					$totalPrevious = $modelModPendingComments->getTotalCommentsModCanApprove('prev', $this->item->id);
					if ($totalPrevious)
					{
						?>
						<button type="button" class="judir-previous btn btn-default btn-info"
						        onclick="Joomla.submitbutton('modpendingcomment.saveAndPrev')">
							<i class="fa fa-arrow-circle-o-left"></i>
							<?php echo JText::sprintf('COM_JUDIRECTORY_SAVE_AND_PREV_N', $totalPrevious); ?>
						</button>
					<?php
					}
					?>

					<div class="judir-approval-options">
						<div class="judir-option-approve-item input-prepend input-append pull-left">
							<span class="add-on">
								<input type="radio" name="approval_option" value="ignore" checked id="ignore-comment"/>
							</span>
							<label for="ignore-comment" class="btn btn-default">
								<i class="fa fa-question-circle"></i>
								<?php echo JText::_('COM_JUDIRECTORY_IGNORE'); ?>
							</label>
						</div>
						<div class="judir-option-approve-item input-prepend input-append pull-left">
							<span class="add-on">
								<input type="radio" name="approval_option" value="approve" id="approval-comment"/>
							</span>
							<label for="approval-comment" class="btn btn-default btn-success">
								<i class="fa fa-check-circle"></i>
								<?php echo JText::_('COM_JUDIRECTORY_APPROVE'); ?>
							</label>
						</div>
						<div class="judir-option-approve-item input-prepend input-append pull-left">
							<span class="add-on">
								<input type="radio" name="approval_option" value="delete" id="reject-comment"/>
							</span>
							<label for="reject-comment" class="btn btn-default btn-danger">
								<i class="fa fa-times"></i>
								<?php echo JText::_('COM_JUDIRECTORY_REJECT'); ?>
							</label>
						</div>
						<div class="clr"></div>
					</div>

					<?php
					$totalNext = $modelModPendingComments->getTotalCommentsModCanApprove('next', $this->item->id);
					if ($totalNext)
					{
						?>
						<button type="button" class="judir-next btn btn-default btn-info"
						        onclick="Joomla.submitbutton('modpendingcomment.saveAndNext')">
							<?php echo JText::sprintf('COM_JUDIRECTORY_SAVE_AND_NEXT_N', $totalNext); ?>
							<i class="fa fa-arrow-circle-o-right"></i>
						</button>
					<?php
					}
					?>
				</div>
			</div>
		<?php
		}
		// Action box
		else
		{
			?>
			<div class="judir-submit-buttons clearfix">
				<button type="button" class="btn btn-default btn-primary" onclick="Joomla.submitbutton('modcomment.save')">
					<i class="fa fa-save"></i> <?php echo JText::_('COM_JUDIRECTORY_SAVE'); ?>
				</button>
				<button type="button" class="btn btn-default" onclick="Joomla.submitbutton('modcomment.cancel')">
					<?php echo JText::_('COM_JUDIRECTORY_CANCEL'); ?>
				</button>
			</div>
		<?php
		}
		?>

		<div class="main-form">
			<ul>
				<?php foreach ($this->form->getFieldset('details') AS $field): ?>
					<?php
					$pClass = substr($field->id, 6);
					?>
					<li>
						<div class="flabel">
							<?php echo $field->label; ?>
						</div>
						<div class="finput <?php echo $pClass; ?>">
							<?php echo $field->input; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div>
			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>