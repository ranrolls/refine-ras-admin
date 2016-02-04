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
}else{
	JHtml::_('behavior.tooltip');
	$separator = "::";
	$classTooltip = "hasTip";
}

$session = JFactory::getSession();

$copied_listing_id = $session->get('copied_listing_id');
$listings = array();
foreach($copied_listing_id as $doc_id){
	$listings[] = JUDirectoryHelper::getListingById($doc_id);
}

?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		var category = document.getElementsByName("categories[]")[0].value;
		if (task == 'listings.copyListings' && category == '') {
			alert("<?php echo JText::_('COM_JUDIRECTORY_PLEASE_SELECT_CATEGORY'); ?>");
			return false;
		}

		Joomla.submitform(task, document.getElementById('adminForm'));
	};
</script>

<div class="jubootstrap">

	<div id="iframe-help"></div>

	<form action="<?php echo JRoute::_('index.php?option=com_judirectory&view=listings'); ?>" method="post" name="adminForm" id="adminForm">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JUDIRECTORY_COPY_LISTINGS'); ?></legend>

			<div class="form-horizontal">
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group ">
							<div class="control-label">
								<label for="categories"><?php echo JText::_('COM_JUDIRECTORY_COPY_TO_CATEGORIES'); ?></label>
							</div>
							<div class="controls">
								<?php
                                    $fetchSelf = true;
                                    $params = JUDirectoryHelper::getParams();
                                    if (!$params->get('allow_add_listing_to_root', 0))
                                    {
                                        $fetchSelf = false;
                                    }
                                    $options = JUDirectoryHelper::getCategoryOptions(1, $fetchSelf, "listing");
    								echo JHtml::_('select.genericList', $options, 'categories[]', 'multiple size="10"', 'value', 'text', '', 'categories');
								?>
							</div>
						</div>

						<div class="row-fluid">
							<div class="span6">
								<div class="control-group ">
									<div class="control-label">
										<label class="<?php echo $classTooltip; ?>" for="extra_fields" title="<?php echo JText::_('COM_JUDIRECTORY_COPY_EXTRA_FIELDS') . $separator . JText::_('COM_JUDIRECTORY_COPY_EXTRA_FIELDS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_COPY_EXTRA_FIELDS'); ?></label>
									</div>
									<div class="controls">
										<input type="checkbox" checked="checked" name="copy_options[]" value="copy_extra_fields" id="extra_fields" />
									</div>
								</div>
								<div class="control-group ">
									<div class="control-label">
										<label class="<?php echo $classTooltip; ?>" for="related_listings" title="<?php echo JText::_('COM_JUDIRECTORY_COPY_RELATED_LISTINGS') . $separator . JText::_('COM_JUDIRECTORY_COPY_RELATED_LISTINGS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_COPY_RELATED_LISTINGS'); ?></label>
									</div>
									<div class="controls">
										<input type="checkbox" name="copy_options[]" value="copy_related_listings" id="related_listings" />
									</div>
								</div>
								<div class="control-group ">
									<div class="control-label">
										<label class="<?php echo $classTooltip; ?>" for="comments" title="<?php echo JText::_('COM_JUDIRECTORY_COPY_COMMENTS') . $separator . JText::_('COM_JUDIRECTORY_COPY_COMMENTS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_COPY_COMMENTS'); ?></label>
									</div>
									<div class="controls">
										<input type="checkbox" name="copy_options[]" value="copy_comments" id="comments" />
									</div>
								</div>
								<div class="control-group ">
									<div class="control-label">
										<label class="<?php echo $classTooltip; ?>" for="reports" title="<?php echo JText::_('COM_JUDIRECTORY_COPY_REPORTS') . $separator . JText::_('COM_JUDIRECTORY_COPY_REPORTS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_COPY_REPORTS'); ?></label>
									</div>
									<div class="controls">
										<input type="checkbox" name="copy_options[]" value="copy_reports" id="reports" />
									</div>
								</div>
								<div class="control-group ">
									<div class="control-label">
										<label class="<?php echo $classTooltip; ?>" for="rates" title="<?php echo JText::_('COM_JUDIRECTORY_COPY_RATES') . $separator . JText::_('COM_JUDIRECTORY_COPY_RATES_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_COPY_RATES'); ?></label>
									</div>
									<div class="controls">
										<input type="checkbox" name="copy_options[]" value="copy_rates" id="rates" />
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group ">
									<div class="control-label">
										<label class="<?php echo $classTooltip; ?>" for="hits" title="<?php echo JText::_('COM_JUDIRECTORY_COPY_HITS') . $separator . JText::_('COM_JUDIRECTORY_COPY_HITS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_COPY_HITS'); ?></label>
									</div>
									<div class="controls">
										<input type="checkbox" name="copy_options[]" value="copy_hits" id="hits" />
									</div>
								</div>
								<div class="control-group ">
									<div class="control-label">
										<label class="<?php echo $classTooltip; ?>" for="permission" title="<?php echo JText::_('COM_JUDIRECTORY_COPY_PERMISSION') . $separator . JText::_('COM_JUDIRECTORY_COPY_PERMISSION_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_COPY_PERMISSION'); ?></label>
									</div>
									<div class="controls">
										<input type="checkbox" checked="checked" name="copy_options[]" value="copy_permission" id="permission" />
									</div>
								</div>
								<?php
								if(JUDIRPROVERSION)
								{
									?>
									<div class="control-group ">
										<div class="control-label">
											<label class="<?php echo $classTooltip; ?>" for="subscriptions"
												title="<?php echo JText::_('COM_JUDIRECTORY_COPY_SUBSCRIPTIONS') . $separator . JText::_('COM_JUDIRECTORY_COPY_SUBSCRIPTIONS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_COPY_SUBSCRIPTIONS'); ?></label>
										</div>
										<div class="controls">
											<input type="checkbox" name="copy_options[]" value="copy_subscriptions"
												id="subscriptions"/>
										</div>
									</div>
									<div class="control-group ">
										<div class="control-label">
											<label class="<?php echo $classTooltip; ?>" for="logs"
												title="<?php echo JText::_('COM_JUDIRECTORY_COPY_LOGS') . $separator . JText::_('COM_JUDIRECTORY_COPY_LOGS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_COPY_LOGS'); ?></label>
										</div>
										<div class="controls">
											<input type="checkbox" name="copy_options[]" value="copy_logs" id="logs"/>
										</div>
									</div>
								<?php
								}
								?>
								<div class="control-group ">
									<div class="control-label">
										<label class="<?php echo $classTooltip; ?>" for="keep_template_params" title="<?php echo JText::_('COM_JUDIRECTORY_KEEP_TEMPLATE_PARAMS') . $separator . JText::_('COM_JUDIRECTORY_KEEP_TEMPLATE_PARAMS_DESC'); ?>"><?php echo JText::_('COM_JUDIRECTORY_KEEP_TEMPLATE_PARAMS'); ?></label>
									</div>
									<div class="controls">
										<input checked="checked" type="checkbox" name="copy_options[]" value="keep_template_params" id="keep_template_params" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="span6">
						<div>
							<?php
							echo JText::plural('COM_JUDIRECTORY_TOTAL_N_LISTINGS_WILL_BE_COPIED', count($listings));
							?>
							<ul>
								<?php foreach($listings as $listing)
								{ ?>
									<li>
										<?php echo $listing->title;?>
									</li>
								<?php
								} ?>
							</ul>
						</div>
						<?php echo JText::_('COM_JUDIRECTORY_COPY_LISTINGS_MESSAGE'); ?>
					</div>
				</div>

			</div>

			<div>
				<input type="hidden" name="task" value="listings.copyListings" />
				<input type="hidden" name="option" value="com_judirectory" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</fieldset>
	</form>
</div>