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

JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

<div id="iframe-help"></div>

<form
	action="<?php echo JRoute::_('index.php?option=com_judirectory&view=styles'); ?>"
	method="post" name="adminForm" id="adminForm">

	<div id="j-main-container" class="span12">

		<?php
		
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>

		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('COM_JUDIRECTORY_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped adminlist" id="data-list">
				<thead>
				<tr>
					<th style="width:2%" class="center hidden-phone">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th style="width:25%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_TEMPLATE_STYLE', 'style.title', $listDirn, $listOrder); ?>
					</th>
					<th style="width:5%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_DEFAULT', 'style.home', $listDirn, $listOrder); ?>
					</th>
					<th style="width:15%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_TEMPLATE', 'plg.title', $listDirn, $listOrder); ?>
					</th>
					<th style="width:5%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_ID', 'style.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
				</thead>

				<tfoot>
				<tr>
					<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
				</tfoot>

				<tbody>
				<?php
				foreach ($this->items AS $i => $item) :
					$canEdit    = $user->authorise('core.edit',       'com_judirectory') && $this->groupCanDoManage;
					$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
					$canEditOwn = $user->authorise('core.edit.own',   'com_judirectory') && $item->created_by == $user->id && $this->groupCanDoManage;
					$canChange  = $user->authorise('core.edit.state', 'com_judirectory') && $canCheckin && $this->groupCanDoManage;
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td>
							<?php echo str_repeat('<span class="gi">&mdash;</span>', $item->level - 1); ?>
							<?php if ($item->checked_out) : ?>
								<?php
								echo JHtml::_('jgrid.checkedout', $i, $item->checked_out_name, $item->checked_out_time, 'styles.', $canCheckin || $user->authorise('core.manage', 'com_checkin'));
								?>
							<?php endif; ?>
							<?php if ($canEdit || $canEditOwn)
							{
								?>
								<?php if($user->authorise('core.admin','com_judirectory')){ ?>
									<a target="_blank" href="<?php echo JUri::root().'index.php?option=com_judirectory&view=category&id=1&tplStyle='.(int) $item->id ?>" class="jgrid">
										<i class="icon-eye-open hasTooltip" title="<?php echo JHtml::tooltipText(JText::_('COM_JUDIRECTORY_TEMPLATE_PREVIEW'), $item->title, 0); ?>" ></i></a>
								<?php } ?>
								<a href="<?php echo JRoute::_('index.php?option=com_judirectory&task=style.edit&id=' . $item->id, false); ?>">
									<?php echo $item->title; ?>
								</a>
							<?php
							}
							else
							{
								?>
								<?php echo $item->title; ?>
							<?php
							} ?>
						</td>
						<td>
							<?php if ($item->home == '0' || $item->home == '1'):
								$enableIsDefault = false;
								?>
								<?php echo JHtml::_('jgrid.isdefault', $item->home != '0', $i, 'styles.', $canChange && $item->home != '1' && $enableIsDefault);?>
							<?php elseif ($canChange):?>
								<a href="<?php echo JRoute::_('index.php?option=com_judirectory&task=styles.unsetDefault&cid[]=' . $item->id . '&' . JSession::getFormToken().'=1'); ?>">
									<?php
									echo JHtml::_('image', 'mod_languages/'.$item->image.'.gif', $item->language_title, array('title' => JText::sprintf('COM_JUDIRECTORY_UNSET_DEFAULT_IN_LANGUAGE_X', $item->language_title)), true);?>
								</a>
							<?php else:?>
								<?php echo JHtml::_('image', 'mod_languages/'.$item->image.'.gif', $item->language_title, array('title' => $item->language_title), true);?>
							<?php endif;?>
						</td>
						<td>
							<?php
							if(JUDIRPROVERSION)
							{
								?>
								<a href="<?php echo JRoute::_('index.php?option=com_judirectory&view=template&id=' . (int) $item->template_id . '&file=' . base64_encode('home')); ?>">
									<?php echo $item->template_title; ?>
								</a>
							<?php
							}
							else
							{
								echo $item->template_title;
							}
							?>
						</td>
						<td><?php echo $item->id; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>