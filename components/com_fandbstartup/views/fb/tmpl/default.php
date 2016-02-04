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


?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_FANDBSTARTUP_FORM_LBL_FB_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FANDBSTARTUP_FORM_LBL_FB_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FANDBSTARTUP_FORM_LBL_FB_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FANDBSTARTUP_FORM_LBL_FB_FILETYPE'); ?></th>
			<td>
			<?php $uploadPath = 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_fandbstartup' . DIRECTORY_SEPARATOR . 'images/fnb' . DIRECTORY_SEPARATOR . $this->item->filetype; ?>
			<a href="<?php echo JRoute::_(JUri::base() . $uploadPath, false); ?>" target="_blank"><?php echo $this->item->filetype; ?></a></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FANDBSTARTUP_FORM_LBL_FB_DESCRIPTION'); ?></th>
			<td><?php echo $this->item->description; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FANDBSTARTUP_FORM_LBL_FB_CREATED_DATE'); ?></th>
			<td><?php echo $this->item->created_date; ?></td>
</tr>

        </table>
    </div>
    
    <?php
else:
    echo JText::_('COM_FANDBSTARTUP_ITEM_NOT_LOADED');
endif;
?>
