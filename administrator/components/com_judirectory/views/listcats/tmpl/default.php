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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$db = JFactory::getDbo();
$user = JFactory::getUser();
$app = JFactory::getApplication();
$cat_id = $app->input->getInt('cat_id', 1);
$listOrder_listing = $this->escape($this->state->get('list.ordering'));
$listDirn_listing = $this->escape($this->state->get('list.direction'));
$listOrder_cat = $this->escape($this->state->get('filter.ordering_cat'));
$listDirn_cat = $this->escape($this->state->get('filter.direction_cat'));
?>
<script type="text/javascript">
	function listing_isChecked(isitchecked) {
		if (isitchecked == true) {
			document.adminForm.listing_boxchecked.value++;
		}
		else {
			document.adminForm.listing_boxchecked.value--;
		}
	}

	function listing_checkAll(n) {
		var f = document.adminForm;
		var c = f.listing_toggle.checked;
		var n2 = 0;
		for (i = 0; i < n; i++) {
			lb = eval('f.listing' + i);
			if (lb) {
				lb.checked = c;
				n2++;
			}
		}
		if (c) {
			document.adminForm.listing_boxchecked.value = n2;
		} else {
			document.adminForm.listing_boxchecked.value = 0;
		}
	}

    Joomla.orderTable = function (element) {
        if(element == "cat"){
            table = document.getElementById("sortTable_cat");
            direction = document.getElementById("directionTable_cat");
            order = table.options[table.selectedIndex].value;
            if (order != '<?php echo $listOrder_cat; ?>') {
                dirn = 'asc';
            } else {
                dirn = direction.options[direction.selectedIndex].value;
            }
            order = "cat."+order;
        }else{
            table = document.getElementById("sortTable");
            direction = document.getElementById("directionTable");
            order = table.options[table.selectedIndex].value;
            if (order != '<?php echo $listOrder_listing; ?>') {
                dirn = 'asc';
            } else {
                dirn = direction.options[direction.selectedIndex].value;
            }
        }

        Joomla.tableOrdering(order, dirn, '');
    };

    
    Joomla.tableOrdering = function(order, dir, task, form) {
        if (typeof(form) === 'undefined') {
            form = document.getElementById('adminForm');
        }

        if(order.indexOf('cat.') > -1){
            form.filter_order_cat.value = order.replace('cat.','');
            form.filter_order_Dir_cat.value = dir;
        }else{
            form.filter_order.value = order;
            form.filter_order_Dir.value = dir;
        }

        Joomla.submitform(task, form);
    };


    function listItemTask(id, task) {
		var f = document.adminForm;
		if (task == 'listings.orderdown' || task == 'listings.orderup' || task == 'listings.unpublish' || task == 'listings.publish' || task == 'listings.featured' || task == 'listings.unfeatured' || task == 'listings.checkin') {
			id = id.replace('cb', 'listing');
			var item = 'listing';
		} else {
			var item = 'cb';
		}
		var cb = f[id];
		if (cb) {
			for (var i = 0; true; i++) {
				var cbx = f[item + i];
				if (!cbx)
					break;
				cbx.checked = false;
			} 
			cb.checked = true;
			f.boxchecked.value = 1;
			submitbutton(task);
		}
		return false;
	}

	Joomla.submitbutton = function (pressbutton) {
		if (pressbutton == "listings.delete" || pressbutton == "listings.moveListings" || pressbutton == "listings.copyListings") {
			if (document.adminForm.listing_boxchecked.value == 0) {
				alert("<?php echo JText::_('COM_JUDIRECTORY_PLEASE_FIRST_MAKE_A_SELECTION_FROM_THE_LIST'); ?>");
				return false;
			}
		}

		if (pressbutton == "categories.delete") {
			var r = confirm('<?php echo JText::_("COM_JUDIRECTORY_WARNING_WHEN_DELETE_CATEGORIES"); ?>');
			if (r == false) {
				return false;
			}

		}

		if (pressbutton == "listings.delete") {
			var r = confirm('<?php echo JText::_("COM_JUDIRECTORY_WARNING_WHEN_DELETE_LISTINGS"); ?>');
			if (r == false) {
				return false;
			}

		}

		Joomla.submitform(pressbutton);
	};
</script>

<div class="jubootstrap">

	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<div id="splitterContainer">
		<div id="leftPane">
			<div class="inner-pane">
				<?php echo $this->loadTemplate('left'); ?>
			</div>
		</div>

		<div id="rightPane">
			<div class="inner-pane">
				<?php echo JUDirectoryHelper::generateCategoryPath($cat_id, "li", true, false); ?>

				<div class="list-item">
					<form
						action="<?php echo JRoute::_('index.php?option=com_judirectory&view=listcats&cat_id=' . $cat_id); ?>"
						method="post" name="adminForm" id="adminForm">
						<?php
						echo $this->loadTemplate('categories');
						if ($this->allowAddListing)
						{
							echo $this->loadTemplate('listings');
						}
						?>
						<div>
							<input type="hidden" name="task" value="" />
							<input type="hidden" name="boxchecked" value="0" />
							<input type="hidden" name="filter_order" value="<?php echo $listOrder_listing; ?>" />
							<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn_listing; ?>" />
                            <input type="hidden" name="filter_order_cat" value="<?php echo $listOrder_cat; ?>" />
                            <input type="hidden" name="filter_order_Dir_cat" value="<?php echo $listDirn_cat; ?>" />
							<input type="hidden" name="listing_boxchecked" value="0" />
							<input type="hidden" name="cat_id" value="<?php echo $this->cat_id; ?>" />
							<?php echo JHtml::_('form.token'); ?>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
