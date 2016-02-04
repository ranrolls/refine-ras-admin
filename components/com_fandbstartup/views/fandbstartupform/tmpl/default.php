<?php
/**
 * @version     1.0.0
 * @package     com_fandbstartup
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Refine <ravindar.k@refine-interactive.com> - http://
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_fandbstartup', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_fandbstartup/assets/js/form.js');


?>
</style>
<script type="text/javascript">
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function() {
        jQuery(document).ready(function() {
            jQuery('#form-fandbstartup').submit(function(event) {
                
		if(jQuery('#jform_filetype').val() != ''){
			jQuery('#jform_filetype_hidden').val(jQuery('#jform_filetype').val());
		}
		if (jQuery('#jform_filetype').val() == '' && jQuery('#jform_filetype_hidden').val() == '') {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			event.preventDefautl();
		}
            });

            
        });
    });

</script>

<div class="fandbstartup-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-fandbstartup" action="<?php echo JRoute::_('index.php?option=com_fandbstartup&task=fandbstartup.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_fandbstartup&task=fandbstartupform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_fandbstartup" />
        <input type="hidden" name="task" value="fandbstartupform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
