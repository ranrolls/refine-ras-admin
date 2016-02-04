 <?php
/**
* @version		$Id:default.php 1 2015-06-04 06:35:13Z  $
* @copyright	Copyright (C) 2015, . All rights reserved.
* @license 		
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$params		= (isset($this->state->params)) ? $this->state->params : new JObject;
$saveOrder	= $listOrder == 'ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_fb&task=fbs.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="index.php?option=com_fb&view=fb" method="post" name="adminForm" id="adminForm">

	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">				
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>
		<div class="clearfix"> </div>

	
<div id="editcell">
	<table class="adminlist table table-striped" id="articleList">
		<thead>
			<tr>
							<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
					
				<th width="20">
					<input type="checkbox" name="checkall-toggle" value="" title="(<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'Title', 'a.title', $listDirn, $listOrder ); ?>
				</th>
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'Filetype', 'a.filetype', $listDirn, $listOrder ); ?>
				</th>
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'Created_date', 'a.created_date', $listDirn, $listOrder ); ?>
				</th>
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'Ordering', 'a.ordering', $listDirn, $listOrder ); ?>
				</th>
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'State', 'a.state', $listDirn, $listOrder ); ?>
				</th>
								<th class="title">
					<?php echo JHTML::_('grid.sort', 'Id', 'a.id', $listDirn, $listOrder ); ?>
				</th>
							</tr> 			
		</thead>
		<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
<?php
  if (count($this->items)) : 
  		foreach ($this->items as $i => $item) :
								$ordering  = ($listOrder == 'ordering');
								  				$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
  												$canCreate  = $user->authorise('core.create');
				$canEdit    = $user->authorise('core.edit');				
				$canChange  = $user->authorise('core.edit.state'); 				
					
				$disableClassName = '';
				$disabledLabel	  = '';
				if (!$saveOrder) {
					$disabledLabel    = JText::_('JORDERINGDISABLED');
					$disableClassName = 'inactive tip-top';
				} 
	
 				$onclick = "";
  	
    			if (JFactory::getApplication()->input->get('function', null)) {
    				$onclick= "onclick=\"window.parent.jSelectFb_id('".$item->id."', '".$this->escape($item->title)."', '','id')\" ";
    			}  	
    
 				$link = JRoute::_( 'index.php?option=com_fb&view=fb&task=fb.edit&id='. $item->id );
 	
 				$checked = JHTML::_('grid.checkedout', $item, $i );
 	 	
  		?>
				<tr class="row<?php echo $i % 2; ?>"">
									<td class="order nowrap center hidden-phone">
					<?php if ($canChange) : ?>					
						<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5"
							value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
					      
        			<td><?php echo $checked;  ?></td>
        				
									        <td class="nowrap has-context">
					<div class="pull-left">
														<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->checked_out_by, $item->checked_out_time, 'fbs.', $canCheckin); ?>
							<?php endif; ?>
														<?php if ($canEdit) : ?>
								<a href="<?php  echo $link; ?>">
									<?php  echo $this->escape($item->title); ?></a>
							<?php  else : ?>
								<?php  echo $this->escape($item->title); ?>
							<?php  endif; ?>
							
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'fb.');
																JHtml::_('dropdown.divider');
								if ($item->state) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'fbs.');
								else :
									JHtml::_('dropdown.publish', 'cb' . $i, 'fbs.');
								endif;									
								JHtml::_('dropdown.divider');

								if ($archived) :
									JHtml::_('dropdown.unarchive', 'cb' . $i, 'fbs.');
								else :
									JHtml::_('dropdown.archive', 'cb' . $i, 'fbs.');
								endif;
								
								if ($trashed) :
									JHtml::_('dropdown.untrash', 'cb' . $i, 'fbs.');
								else :
									JHtml::_('dropdown.trash', 'cb' . $i, 'fbs.');
								endif;								
																								if ($item->checked_out) :
									JHtml::_('dropdown.checkin', 'cb' . $i, 'fbs.');
								endif;
								
								// render dropdown list
								echo JHtml::_('dropdown.render');
								?>
						</div>
						</td>
								 		
						<td><?php echo $item->filetype; ?></td>
								 		
						<td><?php echo $item->created_date; ?></td>
								 		
						<td><?php echo $item->ordering; ?></td>
														<td>
							<?php echo JHtml::_('jgrid.published', $item->state, $i, 'fbs.', $canChange, 'cb'); ?>
						</td>		
								 		
						<td><?php echo $item->id; ?></td>
						</tr>
<?php

  endforeach;
  else:
  ?>
	<tr>
		<td colspan="12">
			<?php echo JText::_( 'There are no items present' ); ?>
		</td>
	</tr>
	<?php
  endif;
  ?>
</tbody>
</table>
</div>
<input type="hidden" name="option" value="com_fb" />
<input type="hidden" name="task" value="fb" />
<input type="hidden" name="view" value="fbs" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>  	