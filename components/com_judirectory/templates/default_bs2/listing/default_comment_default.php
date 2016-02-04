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
<div id="judir-comments" class="judir-comments clearfix">
<?php
if ($this->item->params->get('access-comment'))
{
	?>
	<div class="comment-form-container clearfix">
		<form name="judir-comment-form" id="judir-comment-form" class="comment-form form-horizontal" method="post" action="#">
			<fieldset>
				<legend><?php echo JText::_('COM_JUDIRECTORY_LEAVE_COMMENT'); ?></legend>
				<div class="judir-comment-wrapper clearfix">
					<!-- div.judir-comment -->
					<div class="comment-message-container"></div>

					<div class="judir-comment">
						<p class="note-required">
							<?php echo JText::sprintf('COM_JUDIRECTORY_ALL_FIELDS_HAVE_STAR_ARE_REQUIRED', '<span class="required">*</span>'); ?>
						</p>

						<div class="comment-header row-fluid">
							<div class="comment-user-info span8">
								<div class="control-group">
									<label class="control-label"
									       for="comment-title"><?php echo JText::_('COM_JUDIRECTORY_COMMENT_TITLE'); ?>
										<span class="required">*</span></label>

									<div class="controls">
										<input name="title" class="required comment-title" id="comment-title"
										       type="text" value="<?php echo $this->title; ?>"/>
									</div>
								</div>

								<?php
								if ($this->user->get('guest'))
								{
									?>
									<div class="control-group">
										<label class="control-label"
										       for="comment-name"><?php echo JText::_('COM_JUDIRECTORY_COMMENT_NAME'); ?>
											<span class="required">*</span></label>

										<div class="controls">
											<input name="guest_name" class="required" id="comment-name"
											       type="text" value="<?php echo $this->name; ?>"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label"
										       for="comment-email"><?php echo JText::_('COM_JUDIRECTORY_COMMENT_EMAIL'); ?>
											<span class="required">*</span></label>

										<div class="controls">
											<input name="guest_email" class="email required" id="comment-email"
											       type="text" value="<?php echo $this->email; ?>"/>
										</div>
									</div>
								<?php
								}
								else
								{
									?>
									<div class="control-group">
										<label class="control-label"
										       for="comment-name"><?php echo JText::_('COM_JUDIRECTORY_COMMENT_NAME'); ?>
											<span class="required">*</span></label>

										<div class="controls">
											<input name="guest_name" type="text" id="comment-name"
											       value="<?php echo $this->user->name; ?>" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label"
										       for="comment-email"><?php echo JText::_('COM_JUDIRECTORY_COMMENT_EMAIL'); ?>
											<span class="required ">*</span></label>

										<div class="controls">
											<input name="guest_email" type="text" id="comment-email"
											       value="<?php echo $this->user->email; ?>" readonly="readonly"/>
										</div>
									</div>
								<?php
								} ?>

								<?php
								if ($this->params->get('filter_comment_language', 0))
								{
									?>
									<div class="control-group">
										<label class="control-label"
										       for="comment-language"><?php echo JText::_('COM_JUDIRECTORY_LANGUAGE'); ?>
											<span class="required">*</span></label>

										<div class="controls">
											<select name="comment_language" class="required" id="comment-language">
												<?php echo JHtml::_('select.options', $this->langArray, 'value', 'text', $this->language); ?>
											</select>
										</div>
									</div>
								<?php
								} ?>

								<?php
								if ($this->website_field_in_comment_form)
								{
									$require = ($this->website_field_in_comment_form == 1) ? '' : '<span class=required>*</span>';
									$class   = ($this->website_field_in_comment_form == 1) ? '' : 'required';
									?>
									<div class="control-group">
										<label class="control-label"
										       for="comment-website"><?php echo JText::_('COM_JUDIRECTORY_COMMENT_WEBSITE'); ?><?php echo $require; ?></label>

										<div class="controls">
											<input name="website" class="url <?php echo $class; ?>" id="comment-website"
											       type="text" value="<?php echo $this->website; ?>"/>
										</div>
									</div>
								<?php
								}
								?>
							</div>

							<?php
							if (isset($this->item->fields['rating']) && $this->item->fields['rating']->canView())
							{
								echo '<div class="comment-rating span4">';
								echo $this->item->fields['rating']->getOutput(array("view" => "details", "template" => $this->template, "type" => "comment_form", "prefixId"=>"form-"));
								echo '</div>';
							}
							?>
						</div>

						<div class="comment-body">
							<div class="comment-row">
								<label for="comment-editor"><?php echo JText::_('COM_JUDIRECTORY_COMMENT'); ?>
									<span class="required">*</span></label>
									<textarea name="comment" class="comment-editor" id="comment-editor"
									          cols="100" rows="8"><?php echo $this->comment; ?></textarea>
							</div>
							<?php
							if (JUDIRPROVERSION && $this->params->get('can_subscribe_own_comment', 1))
							{
								?>
								<div class="comment-row">
									<label class="comment-subscribe-lbl" for="comment-subscribe">
										<input name="subscribe" class="comment-subscribe" id="comment-subscribe"
										       type="checkbox" value="1"/>
										<?php echo JText::_('COM_JUDIRECTORY_COMMENT_SUBSCRIBE'); ?>
									</label>
								</div>
							<?php
							}
							if (JUDirectoryFrontHelperPermission::showCaptchaWhenComment($this->item->id))
							{
								echo JUDirectoryFrontHelperCaptcha::getCaptcha(true);
							}
							?>
						</div>
					</div>
					<!--end div.judir-comment -->

					<div class="comment-form-submit clearfix">
						<button type="button" class="btn btn-primary"
							onclick="Joomla.submitbutton('listing.addComment', 'judir-comment-form');"><?php echo JText::_('COM_JUDIRECTORY_SUBMIT'); ?>
						</button>
						<button id="btn-reset" name="comment-reset" type="reset"
						        class="btn"><?php echo JText::_('COM_JUDIRECTORY_RESET'); ?></button>
					</div>

					<div>
						<input name="task" value="" type="hidden"/>
						<input name="parent_id" value="<?php echo $this->root_comment->id; ?>" type="hidden"/>
						<input name="listing_id" value="<?php echo $this->item->id; ?>" type="hidden"/>
						<?php echo JHtml::_('form.token'); ?>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
<?php
}
?>
<input name="min-comment-characters" value="<?php echo $this->min_comment_characters ?>" type="hidden"/>
<input name="max-comment-characters" value="<?php echo $this->max_comment_characters ?>" type="hidden"/>

<?php
if ($this->item->comment->total_comments_no_filter)
{
	?>
	<h3 class="total-comments clearfix"><?php echo JText::plural('COM_JUDIRECTORY_N_COMMENT', $this->item->comment->total_comments); ?></h3>

	<form name="judir-comment-filter-sort-form" class="comment-filter-sort-form clearfix" method="POST"
	      action="<?php echo JRoute::_(JUDirectoryHelperRoute::getListingRoute($this->item->id)); ?>">
		<div class="filter-sort pull-right">
			<?php
			if ($this->params->get('filter_comment_language', 0))
			{
				?>
				<select name="filter_lang" onchange="this.form.submit()" class="input-medium">
					<?php echo JHtml::_('select.options', $this->langArray, 'value', 'text', $this->list_lang_comment); ?>
				</select>
			<?php
			}

			if($this->params->get('filter_comment_rating', 1))
			{
				?>
				<select name="star_filter" onchange="this.form.submit()" class="input-small">
					<?php echo JHtml::_('select.options', $this->filter_comment_stars, 'value', 'text', $this->filter_comment_star); ?>
				</select>
			<?php
			}

			if($this->params->get('show_comment_direction', 1))
			{
				?>
				<select name="filter_order" onchange="this.form.submit()" class="input-medium">
					<?php echo JHtml::_('select.options', $this->order_comment_name_array, 'value', 'text', $this->list_order_comment); ?>
				</select>
				<select name="filter_order_Dir" onchange="this.form.submit()" class="input-small">
					<?php echo JHtml::_('select.options', $this->order_comment_dir_array, 'value', 'text', $this->list_dir_comment); ?>
				</select>
			<?php
			}

			if ($this->params->get('show_comment_pagination', 0))
			{
				echo $this->item->comment->pagination->getLimitBox();
			} ?>
		</div>
	</form>
	<?php
	if ($this->item->comment->total_comments > 0)
	{
		echo $this->loadTemplate('comment_default_recursive');
		?>
		<input type="hidden" id="token" value="<?php echo $this->token ?>">
		<?php
		if ($this->item->comment->pagination->total > $this->item->comment->pagination->limit)
		{
			?>
			<div class="pagination clearfix">
				<?php echo $this->item->comment->pagination->getPagesLinks(); ?>
			</div>
		<?php
		}
		?>
	<?php
	}
	?>
<?php
} ?>
</div>
