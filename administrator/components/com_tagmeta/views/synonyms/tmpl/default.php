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

// Load the tooltip behavior
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// Add tooltip style
$document = JFactory::getDocument();
$document->addStyleDeclaration( '.tip-text {word-wrap: break-word !important;}' );
$document->addStyleDeclaration( '.jrules td {padding: 0 10px 2px 0 !important; border: none !important;}' );
$document->addStyleDeclaration( 'span.hasTip {float: right !important;}' );

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state');
$saveOrder = $listOrder=='s.ordering';
if ($saveOrder)
{
  $saveOrderingUrl = 'index.php?option=com_tagmeta&task=synonyms.saveOrderAjax&tmpl=component';
  JHtml::_('sortablelist.sortable', 'synonymList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
  Joomla.orderTable = function() {
    table = document.getElementById("sortTable");
    direction = document.getElementById("directionTable");
    order = table.options[table.selectedIndex].value;
    if (order != '<?php echo $listOrder; ?>') {
      dirn = 'asc';
    } else {
      dirn = direction.options[direction.selectedIndex].value;
    }
    Joomla.tableOrdering(order, dirn, '');
  }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tagmeta&view=synonyms'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)): ?>
  <div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
  </div>
  <div id="j-main-container" class="span10">
<?php else : ?>
  <div id="j-main-container">
<?php endif;?>
    <div id="filter-bar" class="btn-toolbar">
      <div class="filter-search btn-group pull-left">
        <label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
        <input type="text" name="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_TAGMETA_FILTER'); ?>" />
      </div>
      <div class="btn-group pull-left hidden-phone">
        <button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
        <button class="btn tip hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
      </div>
      <div class="btn-group pull-right hidden-phone">
        <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
        <?php echo $this->pagination->getLimitBox(); ?>
      </div>
      <div class="btn-group pull-right hidden-phone">
        <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
        <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
          <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
          <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
          <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');  ?></option>
        </select>
      </div>
      <div class="btn-group pull-right">
        <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
        <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
          <option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
          <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
        </select>
      </div>
    </div>
    <div class="clearfix"> </div>

    <table class="table table-striped" id="synonymList">
      <thead>
        <tr>
          <th width="4%" class="center hidden-phone">
            <?php echo JText::_('COM_TAGMETA_NUM'); ?>
          </th>
          <th width="10%" class="nowrap center hidden-phone">
            <?php if ($canOrder && $saveOrder): ?>
            <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 's.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
            <?php endif; ?>
          </th>
          <th width="4%" class="center hidden-phone">
            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
          </th>
          <th width="5%" style="min-width:55px" class="nowrap center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_SYNONYMS_PUBLISHED', 's.published', $listDirn, $listOrder); ?>
          </th>
          <th width="20%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_SYNONYMS_KEYWORDS', 's.keywords', $listDirn, $listOrder); ?>
          </th>
          <th width="20%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_SYNONYMS_SYNONYMS', 's.synonyms', $listDirn, $listOrder); ?>
          </th>
          <th width="5%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_SYNONYMS_WEIGHT', 's.weight', $listDirn, $listOrder); ?>
          </th>
          <th width="18%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_SYNONYMS_COMMENT', 's.comment', $listDirn, $listOrder); ?>
          </th>
          <th width="5%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_SYNONYMS_HITS', 's.hits', $listDirn, $listOrder); ?>
          </th>
          <th width="5%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_SYNONYMS_LAST_VISIT', 's.last_visit', $listDirn, $listOrder); ?>
          </th>
          <th width="4%" class="nowrap center hidden-phone">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_SYNONYMS_ID', 's.id', $listDirn, $listOrder); ?>
          </th>
        </tr>
      </thead>
      <tbody>
      <?php
        if( count( $this->items ) > 0 ) {
          foreach ($this->items as $i => $item) :
            $ordering   = ($listOrder == 's.ordering');
            $canCreate  = $user->authorise('core.create',     'com_tagmeta.synonym');
            $canEdit    = $user->authorise('core.edit',       'com_tagmeta.synonym.'.$item->id);
            $canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
            $canChange  = $user->authorise('core.edit.state', 'com_tag_meta.synonym.'.$item->id) && $canCheckin;
            $item_link = JRoute::_('index.php?option=com_tagmeta&task=synonym.edit&id='.(int)$item->id);
      ?>
        <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->ordering; ?>">
          <td class="center hidden-phone">
            <?php echo $this->pagination->getRowOffset( $i ); ?>
          </td>
          <td class="order nowrap center hidden-phone">
          <?php if ($canChange) :
            $disableClassName = '';
            $disabledLabel    = '';

            if (!$saveOrder) :
              $disabledLabel    = JText::_('JORDERINGDISABLED');
              $disableClassName = 'inactive tip-top';
            endif;
          ?>
            <span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
              <i class="icon-menu"></i>
            </span>
            <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
          <?php else : ?>
            <span class="sortable-handler inactive" >
              <i class="icon-menu"></i>
            </span>
          <?php endif; ?>
          </td>
          <td class="center hidden-phone">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
          </td>
          <td class="center">
            <div class="btn-group">
              <?php echo JHtml::_('jgrid.published', $item->published, $i, 'synonyms.', $canChange, 'cb'); ?>
            </div>
          </td>
          <td class="small">
            <span style="display:block; width:200px; word-wrap:break-word;">
            <?php
              $max_chars = 100;
              $item_keywords = TagMetaHelper::trimText($item->keywords, $max_chars);
              if ($canEdit) : ?>
                <a href="<?php echo $item_link; ?>" title="<?php echo JText::_('COM_TAGMETA_EDIT_ITEM'); ?>"><?php echo $this->escape($item_keywords); ?></a>
              <?php else : ?>
                <span title="<?php echo JText::sprintf('COM_TAGMETA_HEADING_SYNONYMS_KEYWORDS', $this->escape($item_keywords)); ?>"><?php echo $this->escape($item_keywords); ?></span>
              <?php endif; ?>
            </span>
          </td>
          <td class="small">
            <span style="display:block; width:200px; word-wrap:break-word;">
            <?php
              $max_chars = 100;
              $item_synonyms = TagMetaHelper::trimText($item->synonyms, $max_chars);
              if ($canEdit) : ?>
                <a href="<?php echo $item_link; ?>" title="<?php echo JText::_('COM_TAGMETA_EDIT_ITEM'); ?>"><?php echo $this->escape($item_synonyms); ?></a>
              <?php else : ?>
                <span title="<?php echo JText::sprintf('COM_TAGMETA_HEADING_SYNONYMS_SYNONYMS', $this->escape($item_keywords)); ?>"><?php echo $this->escape($item_synonyms); ?></span>
              <?php endif; ?>
            </span>
          </td>
          <td class="center">
            <?php echo $item->weight; ?>
          </td>
          <td class="small">
            <span style="display:block; width:200px; word-wrap:break-word;">
            <?php
              $max_chars = 60;
              echo "<span class=\"editlinktip hasTip\" title=\"" . addslashes(htmlspecialchars(JText::_('COM_TAGMETA_HEADING_SYNONYMS_COMMENT') . '::' . $item->comment) ) . "\">";
              echo TagMetaHelper::trimText($item->comment, $max_chars);
              echo "</span>";
            ?>
            </span>
          </td>
          <td class="center">
            <?php echo $item->hits; ?>
          </td>
          <td class="center">
            <?php echo $item->last_visit; ?>
          </td>
          <td class="center hidden-phone">
            <?php echo $item->id; ?>
          </td>
        </tr>
      <?php
          endforeach;
        } else {
      ?>
        <tr>
          <td colspan="11">
            <?php echo JText::_('COM_TAGMETA_LIST_NO_ITEMS'); ?>
          </td>
        </tr>
      <?php
        }
      ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="11">
            <?php echo $this->pagination->getListFooter(); ?>
            <p class="footer-tip">
              <?php if ($this->enabled) : ?>
                <span class="enabled"><?php echo JText::sprintf('COM_TAGMETA_PLUGIN_ENABLED', JText::_('COM_TAGMETA_PLG_SYSTEM_TAGMETA')); ?></span>
              <?php else : ?>
                <span class="disabled"><?php echo JText::sprintf('COM_TAGMETA_PLUGIN_DISABLED', JText::_('COM_TAGMETA_PLG_SYSTEM_TAGMETA')); ?></span>
             <?php endif; ?>
            </p>
          </td>
        </tr>
      </tfoot>
    </table>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
