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

class JUDirectoryFrontHelperEditor
{
	
	protected static $cache = array();

	
	public static function getWysibbEditor($jQuerySelector = '.wysibb', $returnJS = false, $readmore = false)
	{
		$params                      = JUDirectoryHelper::getParams();
		$wysibbButtons['bold,']      = $params->get('bb_bold_tag', 'Bold');
		$wysibbButtons['italic,']    = $params->get('bb_italic_tag', 'Italic');
		$wysibbButtons['underline,'] = $params->get('bb_underline_tag', 'Underline');

		$wysibbButtons['img,']   = $params->get('bb_img_tag', 'Picture');
		$wysibbButtons['link,']  = $params->get('bb_link_tag', 'Link');
		$wysibbButtons['video,'] = $params->get('bb_video_tag', 'Video');

		$wysibbButtons['smilebox,']  = $params->get('bb_smilebox_tag', 'Smilebox');
		$wysibbButtons['fontcolor,'] = $params->get('bb_color_tag', 'Colors');
		$wysibbButtons['fontsize,']  = $params->get('bb_fontsize_tag', 'Fontsize');

		$wysibbButtons['justifyleft,']   = $params->get('bb_align_left', 'alignleft');
		$wysibbButtons['justifycenter,'] = $params->get('bb_align_center', 'aligncenter');
		$wysibbButtons['justifyright,']  = $params->get('bb_align_right', 'alignright');

		$wysibbButtons['bullist,'] = $params->get('bb_bulleted_list', 'Bulleted-list');
		$wysibbButtons['numlist,'] = $params->get('bb_numeric_list', 'Numeric-list');
		$wysibbButtons['quote,']   = $params->get('bb_quote_tag', 'Quotes');
		if ($readmore == true)
		{
			$wysibbButtons['readmore,'] = $params->get('bb_readmore_tag', 'Readmore');
		}

		$buttons = '';
		$i       = 0;
		foreach ($wysibbButtons AS $key => $value)
		{
			if ($i % 3 == 0)
			{
				$buttons .= "|,";
			}
			
			if ($value)
			{
				$buttons .= $key;
			}

			$i++;
		}

		$script = " jQuery(document).ready(function($){
						judirWbbOpt.minCommentChar = " . (int) $params->get('min_comment_characters', 20) . ";
						judirWbbOpt.maxCommentChar = " . (int) $params->get('max_comment_characters', 1000) . ";
						judirWbbOpt.buttons = '$buttons';
						judirWbbOpt.lang = 'en';

						$('$jQuerySelector').wysibb(judirWbbOpt);
					}); ";

		if ($returnJS == true)
		{
			return '<script type="text/javascript">' . $script . '</script>';
		}
		else
		{
			JText::script('COM_JUDIRECTORY_READMORE_WYSIBB_ALREADY_EXISTS');
			JText::script('COM_JUDIRECTORY_PLEASE_ENTER_AT_LEAST_N_CHARACTERS');
			JText::script('COM_JUDIRECTORY_CONTENT_LENGTH_REACH_MAX_N_CHARACTERS');

			
			$document = JFactory::getDocument();
			$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/wysibb/theme/default/wbbtheme.css");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/wysibb/jquery.wysibb.js");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/wysibb/override.jquery.wysibb.js");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/wysibb/preset/phpbb3.js");
			$document->addScriptDeclaration($script);
		}
		
	}
}