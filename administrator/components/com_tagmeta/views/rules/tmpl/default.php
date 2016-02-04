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
$saveOrder = $listOrder=='a.ordering';
if ($saveOrder)
{
  $saveOrderingUrl = 'index.php?option=com_tagmeta&task=rules.saveOrderAjax&tmpl=component';
  JHtml::_('sortablelist.sortable', 'ruleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
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

<form action="<?php echo JRoute::_('index.php?option=com_tagmeta&view=rules'); ?>" method="post" name="adminForm" id="adminForm">
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

    <table class="table table-striped" id="ruleList">
      <thead>
        <tr>
          <th width="5%" class="center hidden-phone">
            <?php echo JText::_('COM_TAGMETA_NUM'); ?>
          </th>
          <th width="5%" class="nowrap center hidden-phone">
            <?php if ($canOrder && $saveOrder): ?>
            <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
            <?php endif; ?>
          </th>
          <th width="5%" class="center hidden-phone">
            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
          </th>
          <th width="5%" class="nowrap center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
          </th>
          <th width="10%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_URL', 'a.url', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_CASE_SENSITIVE', 'a.case_sensitive', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_REQUEST_ONLY', 'a.request_only', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_DECODE_URL', 'a.decode_url', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_LAST_RULE', 'a.last_rule', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_PRESERVE_TITLE', 'a.preserve_title', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_SYNONYMS', 'a.synonyms', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_COMMENT', 'a.comment', $listDirn, $listOrder); ?>
          </th>
          <th width="50%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_TITLE', 'a.title', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_DESCRIPTION', 'a.description', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_AUTHOR', 'a.author', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_KEYWORDS', 'a.keywords', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_RIGHTS', 'a.rights', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_XREFERENCE', 'a.xreference', $listDirn, $listOrder); ?>
            <br />
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_CANONICAL', 'a.canonical', $listDirn, $listOrder); ?>
          </th>
          <th width="5%" class="center">
            <?php echo JText::_('COM_TAGMETA_HEADING_RULES_ROBOTS'); ?>
          </th>
          <th width="5%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_HITS', 'a.hits', $listDirn, $listOrder); ?>
          </th>
          <th width="5%" class="center">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_LAST_VISIT', 'a.last_visit', $listDirn, $listOrder); ?>
          </th>
          <th width="5%" class="nowrap center hidden-phone">
            <?php echo JHTML::_('grid.sort', 'COM_TAGMETA_HEADING_RULES_ID', 'a.id', $listDirn, $listOrder); ?>
          </th>
        </tr>
      </thead>
      <tbody>
      <?php
        if( count( $this->items ) > 0 ) {
          foreach ($this->items as $i => $item) :
            $ordering   = ($listOrder == 'a.ordering');
            $canCreate  = $user->authorise('core.create',     'com_tagmeta.rule');
            $canEdit    = $user->authorise('core.edit',       'com_tagmeta.rule.'.$item->id);
            $canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
            $canChange  = $user->authorise('core.edit.state', 'com_tag_meta.rule.'.$item->id) && $canCheckin;
            $item_link = JRoute::_('index.php?option=com_tagmeta&task=rule.edit&id='.(int)$item->id);
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
              <?php echo JHtml::_('jgrid.published', $item->published, $i, 'rules.', $canChange, 'cb'); ?>
            </div>
          </td>
          <td class="small">
            <span style="display:block; word-wrap:break-word;">
            <?php
              $max_chars = 100;
              $item_url = TagMetaHelper::trimText($item->url, $max_chars);
              if ($canEdit) : ?>
                <a href="<?php echo $item_link; ?>" title="<?php echo JText::_('COM_TAGMETA_EDIT_ITEM'); ?>"><?php echo $this->escape($item_url); ?></a>
              <?php else : ?>
                <span title="<?php echo JText::sprintf('COM_TAGMETA_HEADING_RULES_URL', $this->escape($item_url)); ?>"><?php echo $this->escape($item_url); ?></span>
              <?php endif; ?>
            </span>
            <br />
            <table class="jrules">
            <?php echo '<tr><td>'.JText::_('COM_TAGMETA_FIELD_RULES_CASE_SENSITIVE_LABEL').'</td>';
            if ($item->case_sensitive) {
              $jtask = 'rules.case_off'; $jtext = JText::_( 'JYES' ); $jstate = 'publish';
            } else {
              $jtask = 'rules.case_on'; $jtext = JText::_( 'JNO' ); $jstate = 'unpublish';
            } ?>
            <td><div class="btn-group"><a class="btn btn-micro active" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i; ?>','<?php echo $jtask; ?>')" title="<?php echo $jtext; ?>"><i class="icon-<?php echo $jstate; ?>"></i></a></div></td></tr>
            <?php echo '<tr><td>'.JText::_('COM_TAGMETA_FIELD_RULES_REQUEST_ONLY_LABEL').'</td>';
            if ($item->request_only) {
              $jtask = 'rules.request_off'; $jtext = JText::_( 'JYES' ); $jstate = 'publish';
            } else {
              $jtask = 'rules.request_on'; $jtext = JText::_( 'JNO' ); $jstate = 'unpublish';
            } ?>
            <td><div class="btn-group"><a class="btn btn-micro active" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i; ?>','<?php echo $jtask; ?>')" title="<?php echo $jtext; ?>"><i class="icon-<?php echo $jstate; ?>"></i></a></div></td></tr>
            <?php echo '<tr><td>'.JText::_('COM_TAGMETA_FIELD_RULES_DECODE_URL_LABEL').'</td>';
            if ($item->decode_url) {
              $jtask = 'rules.decode_off'; $jtext = JText::_( 'JYES' ); $jstate = 'publish';
            } else {
              $jtask = 'rules.decode_on'; $jtext = JText::_( 'JNO' ); $jstate = 'unpublish';
            } ?>
            <td><div class="btn-group"><a class="btn btn-micro active" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i; ?>','<?php echo $jtask; ?>')" title="<?php echo $jtext; ?>"><i class="icon-<?php echo $jstate; ?>"></i></a></div></td></tr>
            <?php echo '<tr><td>'.JText::_('COM_TAGMETA_FIELD_RULES_LAST_RULE_LABEL').'</td>';
            if ($item->last_rule) {
              $jtask = 'rules.last_off'; $jtext = JText::_( 'JYES' ); $jstate = 'publish';
            } else {
              $jtask = 'rules.last_on'; $jtext = JText::_( 'JNO' ); $jstate = 'unpublish';
            } ?>
            <td><div class="btn-group"><a class="btn btn-micro active" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i; ?>','<?php echo $jtask; ?>')" title="<?php echo $jtext; ?>"><i class="icon-<?php echo $jstate; ?>"></i></a></div></td></tr>
            <?php echo '<tr><td>'.JText::_('COM_TAGMETA_FIELD_RULES_PRESERVE_TITLE_LABEL').'</td>';
            if ($item->preserve_title) {
              $jtask = 'rules.preserve_off'; $jtext = JText::_( 'JYES' ); $jstate = 'publish';
            } else {
              $jtask = 'rules.preserve_on'; $jtext = JText::_( 'JNO' ); $jstate = 'unpublish';
            } ?>
            <td><div class="btn-group"><a class="btn btn-micro active" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i; ?>','<?php echo $jtask; ?>')" title="<?php echo $jtext; ?>"><i class="icon-<?php echo $jstate; ?>"></i></a></div></td></tr>
            <tr><td><?php echo JText::_('COM_TAGMETA_FIELD_RULES_PLACEHOLDERS_LABEL') . '&nbsp;' . JHTML::tooltip(nl2br($item->placeholders), JText::_('COM_TAGMETA_FIELD_RULES_PLACEHOLDERS_LABEL'), 'tooltip.png', '', ''); ?></td><td><?php echo count(array_filter(explode("\n", trim($item->placeholders)))); ?></td></tr>
            <?php $synonyms_settings = JText::_('COM_TAGMETA_FIELD_RULES_SYNONYMS_MAX_LABEL') . ' ' . $item->synonmax . ' / ' . (($item->synonweight) ? JText::_('COM_TAGMETA_FIELD_RULES_SYNONYMS_WEIGHT_LABEL') : JText::_('COM_TAGMETA_FIELD_RULES_SYNONYMS_ORDERING_LABEL'));
            echo '<tr><td>'.JText::_('COM_TAGMETA_FIELD_RULES_SYNONYMS_LABEL').'&nbsp;'.JHTML::tooltip($synonyms_settings, JText::_('COM_TAGMETA_FIELD_RULES_SYNONYMS_SETTINGS_LABEL'), 'tooltip.png', '', '').'</td>';
            switch ($item->synonyms) {
              case 0:
                $jtask = 'rules.synonyms_on';
                $jtext = JText::_( 'JNO' );
                $jtext2 = '';
                $jstate = 'unpublish';
                break;
              case 1:
                $jtask = 'rules.synonyms_on_cs';
                $jtext = JText::_( 'JYES' );
                $jtext2 = '';
                $jstate = 'publish';
                break;
              case 2:
              default:
                $jtask = 'rules.synonyms_off';
                $jtext = JText::_('JYES' ) . ' (' . JText::_('COM_TAGMETA_FIELD_RULES_SYNONYMS_CASE_SENSITIVE_LABEL') . ')';
                $jtext2 = '&nbsp;(' . JText::_('COM_TAGMETA_FIELD_RULES_SYNONYMS_CASE_SENSITIVE_LABEL') . ')';
                $jstate = 'publish';
            } ?>
            <td><div class="btn-group"><a class="btn btn-micro active" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i; ?>','<?php echo $jtask; ?>')" title="<?php echo $jtext; ?>"><i class="icon-<?php echo $jstate; ?>"></i></a></div></td></tr>
            </table>
            <br />
            <div style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px; height: 45px; width: 120px; word-wrap:break-word;" class="editlinktip hasTip" title="<?php echo JText::_('COM_TAGMETA_FIELD_RULES_COMMENT_LABEL')."::".htmlspecialchars($item->comment, ENT_QUOTES); ?>">
              <?php
                $max_chars = 100;
                echo TagMetaHelper::trimText(htmlspecialchars($item->comment, ENT_QUOTES), $max_chars);
              ?>
            </div>
          </td>
          <td>
            <div style="float: left; border: 1px dashed silver; padding: 5px; margin-bottom: 10px; height: 45px; width: 200px; word-wrap:break-word;" class="editlinktip hasTip" title="<?php echo JText::_('COM_TAGMETA_FIELD_RULES_TITLE_LABEL')."::".htmlspecialchars($item->title, ENT_QUOTES); ?>">
              <?php
                $max_chars = 100;
                echo TagMetaHelper::trimText(htmlspecialchars($item->title, ENT_QUOTES), $max_chars);
              ?>
            </div>
            <div style="float: right; border: 1px dashed silver; padding: 5px; margin-bottom: 10px; height: 45px; width: 200px; word-wrap:break-word;" class="editlinktip hasTip" title="<?php echo JText::_('COM_TAGMETA_FIELD_RULES_DESCRIPTION_LABEL')."::".htmlspecialchars($item->description, ENT_QUOTES); ?>">
              <?php
                $max_chars = 100;
                echo TagMetaHelper::trimText(htmlspecialchars($item->description, ENT_QUOTES), $max_chars);
              ?>
            </div>
            <div style="float: left; border: 1px dashed silver; padding: 5px; margin-bottom: 10px; height: 45px; width: 200px; word-wrap:break-word;" class="editlinktip hasTip" title="<?php echo JText::_('COM_TAGMETA_FIELD_RULES_AUTHOR_LABEL')."::".htmlspecialchars($item->author, ENT_QUOTES); ?>">
              <?php
                $max_chars = 100;
                echo TagMetaHelper::trimText(htmlspecialchars($item->author, ENT_QUOTES), $max_chars);
              ?>
            </div>
            <div style="float: right; border: 1px dashed silver; padding: 5px; margin-bottom: 10px; height: 45px; width: 200px; word-wrap:break-word;" class="editlinktip hasTip" title="<?php echo JText::_('COM_TAGMETA_FIELD_RULES_KEYWORDS_LABEL')."::".htmlspecialchars($item->keywords, ENT_QUOTES); ?>">
              <?php
                $max_chars = 100;
                echo TagMetaHelper::trimText(htmlspecialchars($item->keywords, ENT_QUOTES), $max_chars);
              ?>
            </div>
            <div style="float: left; border: 1px dashed silver; padding: 5px; margin-bottom: 10px; height: 45px; width: 200px; word-wrap:break-word;" class="editlinktip hasTip" title="<?php echo JText::_('COM_TAGMETA_FIELD_RULES_RIGHTS_LABEL')."::".htmlspecialchars($item->rights, ENT_QUOTES); ?>">
              <?php
                $max_chars = 100;
                echo TagMetaHelper::trimText(htmlspecialchars($item->rights, ENT_QUOTES), $max_chars);
              ?>
            </div>
            <div style="float: right; border: 1px dashed silver; padding: 5px; margin-bottom: 10px; height: 45px; width: 200px; word-wrap:break-word;" class="editlinktip hasTip" title="<?php echo JText::_('COM_TAGMETA_FIELD_RULES_XREFERENCE_LABEL')."::".htmlspecialchars($item->xreference, ENT_QUOTES); ?>">
              <?php
                $max_chars = 100;
                echo TagMetaHelper::trimText(htmlspecialchars($item->xreference, ENT_QUOTES), $max_chars);
              ?>
            </div>
            <div style="float: left; border: 1px dashed silver; padding: 5px; margin-bottom: 10px; height: 45px; width: 200px; word-wrap:break-word;" class="editlinktip hasTip" title="<?php echo JText::_('COM_TAGMETA_FIELD_RULES_CANONICAL_LABEL')."::".htmlspecialchars($item->canonical, ENT_QUOTES); ?>">
              <?php
                $max_chars = 100;
                echo TagMetaHelper::trimText(htmlspecialchars($item->canonical, ENT_QUOTES), $max_chars);
              ?>
            </div>
          </td>
          <td class="center">
            <?php
              $robots = '';
              if ($item->rindex != 2) { $robots .= ($item->rindex) ? 'index,' : 'noindex,'; }
              if ($item->rfollow != 2) { $robots .= ($item->rfollow) ? 'follow,' : 'nofollow,'; }
              if ($item->rsnippet != 2) { $robots .= ($item->rsnippet) ? 'snippet,' : 'nosnippet,'; }
              if ($item->rarchive != 2) { $robots .= ($item->rarchive) ? 'archive,' : 'noarchive,'; }
              if ($item->rodp != 2) { $robots .= ($item->rodp) ? 'odp' : 'noodp'; }
              if ($item->rimageindex != 2) { $robots .= ($item->rimageindex) ? 'imageindex,' : 'noimageindex,'; }
              $robots = rtrim($robots, ','); // Drop last char if is a comma
              echo $robots;
            ?>
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
