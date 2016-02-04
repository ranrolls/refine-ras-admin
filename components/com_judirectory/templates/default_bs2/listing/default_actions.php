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
?>
<div class="actions clearfix">
	<div class="general-actions">
	<?php if ($this->item->params->get('access-report'))
	{
		?>
		<span class="action-report">
			<?php echo '<a href="' . $this->item->report_link . '" title="' . JText::_('COM_JUDIRECTORY_REPORT') . '" class="hasTooltip report-task btn"><i class="fa fa-warning"></i></a>'; ?>
		</span>
	<?php
	}

	if ($this->item->params->get('access-contact'))
	{
		?>
		<span class="action-contact">
			<?php echo '<a href="' . $this->item->contact_link . '" title="' . JText::_('COM_JUDIRECTORY_CONTACT') . '" class="hasTooltip btn"><i class="fa fa-user"></i></a>'; ?>
		</span>
	<?php
	}

	if ($this->item->params->get('access-claim'))
	{
	?>
	<span class="action-claim">
		<?php
		echo '<a class="hasTooltip btn" title="' . JText::_('COM_JUDIRECTORY_CLAIM') . '" href="' . $this->item->claim_link . '"><i class="fa fa-flag" ></i></a>';
		?>
	</span>
	<?php
	} ?>

	<span class="action-print">
		<?php
			$windowOpenSpecs = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
			$onclick = "window.open(this.href, 'listing_print', '" . $windowOpenSpecs . "'); return false;";
			echo '<a class="hasTooltip btn" title="' . JText::_('COM_JUDIRECTORY_PRINT') . '" href="' . $this->item->print_link . '" rel="nofollow" onclick="' . $onclick . '"><i class="fa fa-print" ></i></a>';
		?>
	</span>

	<span class="action-mailtofriend">
		<a href="#judir-mailtofriend"
		   title="<?php echo JText::_('COM_JUDIRECTORY_SEND_EMAIL_TO_FRIEND'); ?>" class="hasTooltip btn"
		   data-toggle="modal"><i class="fa fa-envelope"></i></a>
	</span>

    <span class="action-compare">
        <a onclick="addToCompare(<?php echo $this->item->id; ?>);"
           title="<?php echo JText::_('COM_JUDIRECTORY_ADD_TO_COMPARE'); ?>" class="hasTooltip btn btn-default">
            <i class="fa fa-exchange"></i>
        </a>
    </span>

	<div class="modal hide fade" id="judir-mailtofriend" tabindex="-1" role="dialog"
	     aria-labelledby="judir-mailtofriend-label" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 class="modal-title"
				id="judir-mailtofriend-label"><?php echo JText::_('COM_JUDIRECTORY_SEND_EMAIL'); ?>
			</h3>
		</div>
		<div class="modal-body form-horizontal">
			<div class="message control-group hide">
			</div>
			<div class="control-group">
				<label class="control-label" for="inputEmail">
					<?php echo JText::_('COM_JUDIRECTORY_SEND_TO'); ?>
					<span style="color: red">*</span>
				</label>

				<div class="controls">
					<input id="inputEmail" type="text" name="to_email" size="32"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputUsername">
					<?php echo JText::_("COM_JUDIRECTORY_YOUR_NAME"); ?>
					<span style="color: red">*</span>
				</label>

				<div class="controls">
					<input id="inputUsername" type="text" name="name" <?php if (!$this->user->get('guest'))
					{
						echo 'readonly="readonly"';
					} ?> value="<?php echo $this->user->username; ?>" size="32"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputYourEmail">
					<?php echo JText::_("COM_JUDIRECTORY_YOUR_EMAIL"); ?>
					<span style="color: red">*</span>
				</label>

				<div class="controls">
					<input id="inputYourEmail" type="text" name="email" <?php if (!$this->user->get('guest'))
					{
						echo 'readonly="readonly"';
					} ?> value="<?php echo $this->user->email; ?>" size="32"/>
				</div>
			</div>
			<div>
				<input type="hidden" name="task" value="listing.sendemail"/>
				<input type="hidden" name="tmpl" value="component"/>
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary" id="send_mail_button"
					data-loading-text="<?php echo JText::_("COM_JUDIRECTORY_LOADING"); ?>"><?php echo JText::_("COM_JUDIRECTORY_SEND"); ?></button>
			<button class="btn" aria-hidden="true"
					data-dismiss="modal"><?php echo JText::_("COM_JUDIRECTORY_CANCEL"); ?></button>
		</div>
	</div>

	<?php if ($this->collection_popup)
	{
		?>
		<span class="action-addcollection">
	            <a class="hasTooltip btn judir-add-collection"
	               title="<?php echo JText::_('COM_JUDIRECTORY_ADD_TO_COLLECTIONS'); ?>">
		            <i class="fa fa-inbox"></i>
	            </a>
			</span>

		<div class="judir-collection-list" style="display: none;">
			<div class="collection-popup jubootstrap component judir-container">
				<ul class="collection-list">
					<?php
					foreach ($this->collections AS $collection)
					{
						$added = "";
						if ($collection->hasThisListing)
						{
							$added = " added";
						}
						echo "<li class='collection-item'>
	                                <i class='add-to-collection fa fa-check" . $added . "' id='collection-" . $collection->id . "'></i>
	                                <a class='collection-item-popup' href=\"" . $collection->collection_link . "\">" . $collection->title . "</a>
	                            </li>";
					}
					?>
				</ul>
				<div class="create-new-collection">
					<a href="#create-collection-modal" data-toggle="modal" id="create-new-collection">
						<i class='fa fa-plus'></i>
						<?php echo JText::_("COM_JUDIRECTORY_CREATE_A_NEW_COLLECTION"); ?>
					</a>
				</div>
			</div>
			<input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>">
		</div>

		<div class="modal hide fade" id="create-collection-modal" tabindex="-1" role="dialog"
		     aria-labelledby="create-collection-modal-label" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="create-collection-modal-label">
					<?php echo JText::_("COM_JUDIRECTORY_CREATE_A_NEW_COLLECTION"); ?>
				</h3>
			</div>
			<form action="#" method="post" name="create-new-collection" class="form-horizontal">
				<div class="modal-body">
					<div class="control-group">
						<label class="control-label"><?php echo JText::_("COM_JUDIRECTORY_FIELD_TITLE"); ?>
							<span style="color: red;">*</span></label>

						<div class="controls">
							<input type="text" name="title" size="53"/>
						</div>
					</div>

					<div class="control-group">
						<label
							class="control-label"><?php echo JText::_("COM_JUDIRECTORY_FIELD_DESCRIPTION"); ?></label>

						<div class="controls">
							<textarea name="description" cols="48" rows="3"></textarea>
						</div>
					</div>

					<div class="control-group">
						<label
							class="control-label"><?php echo JText::_("COM_JUDIRECTORY_FIELD_PRIVATE"); ?></label>

						<div class="controls">
							<label><input type="radio" name="private"
										  value="1"/> <?php echo JText::_("COM_JUDIRECTORY_ONLY_ME_CAN_VIEW_THIS_COLLECTION"); ?>
							</label>
							<label><input type="radio" name="private" value="0"
										  checked/> <?php echo JText::_("COM_JUDIRECTORY_ANYONE_CAN_VIEW_THIS_COLLECTION"); ?>
							</label>
						</div>
					</div>

					<?php echo JHtml::_('form.token'); ?>
					<input type="hidden" name="listing_id" value="<?php echo $this->item->id; ?>"/>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary"
						   value="<?php echo JText::_("COM_JUDIRECTORY_SUBMIT"); ?>"/>
					<input type="reset" class="btn" id="collection_form_reset"
						   value="<?php echo JText::_("COM_JUDIRECTORY_RESET"); ?>"/>
				</div>
			</form>
		</div>
		<!-- /.modal -->
	<?php
	} ?>
	</div>
</div>
<!-- /.actions -->