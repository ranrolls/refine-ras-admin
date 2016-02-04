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

JFormHelper::loadFieldClass('list');


class JFormFieldSelectItemSubscription extends JFormFieldList
{
	
	protected $type = 'selectitemsubscription';

	
	protected function getInput()
	{
		$document = JFactory::getDocument();
		$script   = 'jQuery(document).ready(function($){
						$("#type_"+$("#jform_type").val()).show();
						$("#jform_type").change(function(){
							$("#jform_item_id").val("") ;
							$("#item_name").val("");
							if($(this).val()==\'listing\'){
	                            $("#type_listing").show();
	                            $("#type_comment").hide();
	                        }else{
	                            $("#type_comment").show();
	                            $("#type_listing").hide();
	                        }
                        });
                    });';

		$script .= "\n" . "function jSelectComment(id, title, level) {
					document.getElementById(\"jform_item_id\").value = id;
					document.getElementById(\"item_name\").value = title;
					SqueezeBox.close();
					}

					function jSelectListing(id, title) {
						document.getElementById(\"jform_item_id\").value = id;
						document.getElementById(\"item_name\").value = title;
						SqueezeBox.close();
					}";

		$document->addScriptDeclaration($script);

		$app = JFactory::getApplication();

		
		$subId = $app->input->getInt('id', 0);

		$subscriptionModel = JModelLegacy::getInstance('Subscription', 'JUDirectoryModel');
		$subscription      = $subscriptionModel->getItem($subId);
		$itemType          = $subscription->type;

		$itemAlias = "";
		if ($itemType == 'listing')
		{
			$listingModel = JModelLegacy::getInstance('Listing', 'JUDirectoryModel');
			$listing      = $listingModel->getItem($this->value);
			$itemAlias    = $listing->title;
		}
		else
		{
			$commentModel = JModelLegacy::getInstance('Comment', 'JUDirectoryModel');
			$comment      = $commentModel->getItem($this->value);
			$itemAlias    = $comment->title;
		}

		$html = "";
		$html .= '<input type="hidden" id="' . $this->id . '" name="' . $this->name . '" value="' . $this->value . '">';
		$html .= '<input type="text" id="item_name" value="' . $itemAlias . '">';
		$html .= '<div class="button2-left">';
		$html .= '<div class="blank"><a class="modal listing" id="type_listing" style="display: none" rel="{handler: \'iframe\', size: {x: 800, y: 500}}" href="index.php?option=com_judirectory&amp;view=listings&amp;layout=modal&amp;tmpl=component&amp;function=jSelectListing" title="' . JText::_('COM_JUDIRECTORY_SELECT_LISTING') . '">';
		$html .= JText::_("COM_JUDIRECTORY_SELECT_LISTING");
		$html .= '</a></div>';
		$html .= '<div class="blank"><a class="modal comment" id="type_comment" style="display: none" rel="{handler: \'iframe\', size: {x: 800, y: 500}}" href="index.php?option=com_judirectory&amp;view=comments&amp;layout=modal&amp;tmpl=component&amp;function=jSelectComment" title="' . JText::_('COM_JUDIRECTORY_SELECT_COMMENT') . '">';
		$html .= JText::_("COM_JUDIRECTORY_SELECT_COMMENT");
		$html .= '</a></div>';
		$html .= '</div>';

		return $html;
	}
}
