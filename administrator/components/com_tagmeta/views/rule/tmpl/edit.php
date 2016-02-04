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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
  Joomla.submitbutton = function(task)
  {
    if (task == 'rule.cancel' || document.formvalidator.isValid(document.id('rule-form'))) {
      Joomla.submitform(task, document.getElementById('rule-form'));
    }
  }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tagmeta&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="rule-form" class="form-validate form-horizontal">
  <div class="row-fluid">
    <!-- Begin Content -->
    <div class="span10 form-horizontal">

      <ul class="nav nav-tabs">
        <li class="active"><a href="#rule" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_TAGMETA_RULE_NEW_RULE') : JText::_('COM_TAGMETA_RULE_EDIT_RULE'); ?></a></li>
        <li><a href="#stats" data-toggle="tab"><?php echo JText::_('COM_TAGMETA_RULE_STATS'); ?></a></li>
        <li><a href="#meta" data-toggle="tab"><?php echo JText::_('COM_TAGMETA_RULE_META_OPTIONS'); ?></a></li>
        <li><a href="#robots" data-toggle="tab"><?php echo JText::_('COM_TAGMETA_RULE_ROBOTS_OPTIONS'); ?></a></li>
        <li><a href="#synonyms" data-toggle="tab"><?php echo JText::_('COM_TAGMETA_RULE_SYNONYMS_OPTIONS'); ?></a></li>
        <li><a href="#overrides" data-toggle="tab"><?php echo JText::_('COM_TAGMETA_RULE_GLOBAL_OVERRIDES'); ?></a></li>
        <li><a href="#help" data-toggle="tab"><?php echo JText::_('COM_TAGMETA_RULE_QUICK_HELP_LABEL'); ?></a></li>
      </ul>

      <fieldset>
      <div class="tab-content">

        <div class="tab-pane active" id="rule">
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('url'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('url'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('case_sensitive'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('case_sensitive'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('request_only'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('request_only'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('decode_url'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('decode_url'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('last_rule'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('last_rule'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('placeholders'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('placeholders'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('comment'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('comment'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
          </div>
        </div>

        <div class="tab-pane" id="stats">
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('hits'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('hits'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('last_visit'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('last_visit'); ?></div>
          </div>
        </div>

        <div class="tab-pane" id="meta">
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('description'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('author'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('author'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('keywords'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('keywords'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('rights'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('rights'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('xreference'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('xreference'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('canonical'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('canonical'); ?></div>
          </div>
        </div>

        <div class="tab-pane" id="robots">
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('rindex'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('rindex'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('rfollow'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('rfollow'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('rsnippet'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('rsnippet'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('rarchive'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('rarchive'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('rodp'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('rodp'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('rimageindex'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('rimageindex'); ?></div>
          </div>
        </div>

        <div class="tab-pane" id="synonyms">
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('synonyms'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('synonyms'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('synonmax'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('synonmax'); ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('synonweight'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('synonweight'); ?></div>
          </div>
        </div>

        <div class="tab-pane" id="overrides">
          <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('preserve_title'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('preserve_title'); ?></div>
          </div>
        </div>

        <div class="tab-pane" id="help">
          <?php echo JHtml::_('sliders.start','tagmeta-rule-sliders-'.$this->item->id, array('useCookie'=>1, 'allowAllClose'=>1, 'startOffset'=>-1)); ?>
          <?php echo JHtml::_('sliders.panel',JText::_('COM_TAGMETA_RULE_QUICK_HELP_LABEL'), 'quick-help'); ?>
          <fieldset class="panelform">
            <p><?php echo JText::_('COM_TAGMETA_RULE_QUICK_HELP_DESC'); ?></p>
          </fieldset>
          <?php echo JHtml::_('sliders.panel',JText::_('COM_TAGMETA_RULE_PLACEHOLDERS_LABEL'), 'placeholders'); ?>
          <fieldset class="panelform">
            <p><?php echo JText::_('COM_TAGMETA_RULE_PLACEHOLDERS_DESC'); ?></p>
          </fieldset>
          <?php echo JHtml::_('sliders.panel',JText::_('COM_TAGMETA_RULE_SUPPORTED_MACROS_LABEL'), 'supported-macros'); ?>
          <fieldset class="panelform">
            <p><?php echo JText::_('COM_TAGMETA_RULE_SUPPORTED_MACROS_DESC'); ?></p>
          </fieldset>
          <?php echo JHtml::_('sliders.end'); ?>
        </div>

      </div>
      </fieldset>

    </div>
    <!-- End Content -->

    <!-- Begin Sidebar -->
    <div class="span2">

      <h4><?php echo JText::_('COM_TAGMETA_RULE_PUBLISHING_OPTIONS');?></h4>
      <hr />
      <fieldset class="form-vertical">
      <div class="control-group">
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('published'); ?></div>
        </div>
      </div>
      </fieldset>

    </div>
    <!-- End Sidebar -->
  </div> <!-- row-fluid -->
  <input type="hidden" name="task" value="" />
  <?php echo JHtml::_('form.token'); ?>
</form>
