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

class JUDirectoryFieldCore_description extends JUDirectoryFieldTextarea
{
	protected $field_name = 'description';
	protected $filter = 'raw';

	public function onSearch(&$query, &$where, $search)
	{
		if ($search !== "")
		{
			$db      = JFactory::getDbo();
			$where[] = "(listing.introtext LIKE '%" . $db->escape($search, true) . "%' OR listing.fulltext LIKE '%" . $db->escape($search, true) . "%')";
		}
	}

	protected function getValue()
	{
		$value            = new stdClass();
		$value->introtext = $this->listing->introtext;
		$value->fulltext  = $this->listing->fulltext;

		return $value;
	}

	public function parseValue($value)
	{
		if (!$this->isPublished())
		{
			return null;
		}

		if ($value && is_object($value))
		{
			$redmore = $this->getEditorName() == 'wysibb' ? '[READMORE]' : '<hr id="system-readmore" />';

			return trim($value->fulltext) != '' ? $value->introtext . $redmore . $value->fulltext : $value->introtext;
		}

		return "";
	}

	public function getOutput($options = array())
	{
		if (!$this->isPublished())
		{
			return "";
		}

		if (!$this->value)
		{
			return "";
		}

		$options = (array) $options;
		
		if ($this->isDetailsView($options))
		{
			if ($this->params->get("show_introtext_in_details_view", 1))
			{
				$description = $this->value;
			}
			else
			{
				$description = $this->listing->fulltext;
			}

			if ($this->params->get("strip_tags_details_view", 0))
			{
				$allowable_tags = $this->params->get("allowable_tags", "u,b,i,a,ul,li,pre,blockquote,strong,em");
				$allowable_tags = str_replace(' ', '', $allowable_tags);
				$allowable_tags = "<" . str_replace(',', '><', $allowable_tags) . ">";
				$description    = strip_tags($description, $allowable_tags);
			}

			if ($this->params->get("parse_plugin", 0))
			{
				$description = JHtml::_('content.prepare', $description);
			}

			if ($this->params->get("auto_link", 1))
			{
				$trim_long_url     = $this->params->get('trim_long_url', 0);
				$front_portion_url = $this->params->get('front_portion_url', 0);
				$back_portion_url  = $this->params->get('back_portion_url', 0);
				$regex             = "#http(?:s)?:\/\/(?:www\.)?[\.0-9a-z]{1,255}(\.[a-z]{2,4}){1,2}([\/\?][^\s]{1,}){0,}[\/]?#i";
				preg_match_all($regex, $description, $matches);

				$matches = array_unique($matches[0]);

				if (count($matches) > 0)
				{
					foreach ($matches AS $url)
					{
						$shortenUrl = urldecode($url);
						
						if ($trim_long_url > 0 && strlen($shortenUrl) > $trim_long_url)
						{
							if ($front_portion_url > 0 || $back_portion_url > 0)
							{
								$frontStr   = $front_portion_url > 0 ? substr($shortenUrl, 0, $front_portion_url) : "";
								$backStr    = $back_portion_url > 0 ? substr($shortenUrl, (int) (0 - $back_portion_url)) : "";
								$shortenUrl = $frontStr . '...' . $backStr;
							}

							$shortenUrl  = '<a href="' . $url . '">' . $shortenUrl . '</a> ';
							$description = str_replace(trim($url), $shortenUrl, $description);
							$description = JUDirectoryFrontHelperString::replaceIgnore(trim($url), $shortenUrl, $description);
						}
						
						else
						{
							$description = JUDirectoryFrontHelperString::replaceIgnore($url, '<a href="' . $url . '">' . trim($shortenUrl) . '</a> ', $description);
						}
					}
				}
			}

			if ($this->params->get("nl2br_details_view", 0))
			{
				$description = nl2br($description);
			}
		}
		
		else
		{
			$description = $this->listing->introtext;
			if ($this->params->get("strip_tags_list_view", 1))
			{
				$allowable_tags = $this->params->get("allowable_tags", "u,b,i,a,ul,li,pre,blockquote,strong,em");
				$allowable_tags = str_replace(' ', '', $allowable_tags);
				$allowable_tags = "<" . str_replace(',', '><', $allowable_tags) . ">";
				$description    = strip_tags($description, $allowable_tags);
			}

			if ($this->params->get("use_html_entities", 0))
			{
				$description = htmlentities($description);
			}

			$isTruncated = false;
			if ($this->params->get("truncate", 1))
			{
				if ($this->params->get("limit_char_in_list_view", 200) < strlen($description))
				{
					$isTruncated = true;
				}

				$description = JUDirectoryFrontHelperString::truncateHtml($description, $this->params->get("limit_char_in_list_view", 200));
			}

			if ($this->params->get("parse_plugin", 0))
			{
				$description = JHtml::_('content.prepare', $description);
			}

			if ($this->params->get("show_readmore", 0))
			{
				if ($this->params->get("show_readmore_when", 1) == 2 || ($this->params->get("show_readmore_when", 1) == 1 && $isTruncated))
				{
					$description .= ' <a class="readmore" href="' . JRoute::_(JUDirectoryHelperRoute::getListingRoute($this->listing_id)) . '">' . $this->params->get("readmore_text", 'Read more...') . '</a>';
				}
			}
		}

		$this->setVariable('value', $description);

		return $this->fetch('output.php', __CLASS__);
	}

	public function PHPValidate($values)
	{
		
		if (($values === "" || $values === null) && !$this->isRequired())
		{
			return true;
		}

		$validate = (string) $this->params->get("validate", "");

		
		if (strpos($validate, '::') !== false && is_callable(explode('::', $validate)))
		{
			return call_user_func(explode('::', $validate), $values);
		}
		
		elseif (function_exists($validate))
		{
			return call_user_func($validate, $values);
		}

		if ($this->isRequired())
		{
			if (trim($values) == '')
			{
				return JText::_('COM_JUDIRECTORY_LISTING_MUST_HAVE_TEXT');
			}
		}

		return true;
	}

	public function storeValue($value)
	{
		
		$db = JFactory::getDbo();

		$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
		$tagPos  = preg_match($pattern, $value);

		if ($tagPos == 0)
		{
			$introtext = $value;
			$fulltext  = '';
		}
		else
		{
			list ($introtext, $fulltext) = preg_split($pattern, $value, 2);
		}

		$query = "UPDATE #__judirectory_listings SET `introtext` = " . $db->quote($introtext) . ", `fulltext` = " . $db->quote($fulltext) . " WHERE id = " . $this->listing_id;
		$db->setQuery($query);
		$db->execute();
	}

	protected function getEditorName()
	{
		$app = JFactory::getApplication();
		if ($app->isAdmin())
		{
			if ($this->params->get('use_editor_back_end', 1))
			{
				return $this->params->get('backend_editor', 'tinymce');
			}
		}
		else
		{
			if ($this->params->get('use_editor_front_end', 1))
			{
				return $this->params->get('frontend_editor', 'none');
			}
		}

		return '';
	}

	public function orderingPriority(&$query = null)
	{
		$this->appendQuery($query, 'select', 'CONCAT(listing.introtext, listing.fulltext) AS description');

		return array('ordering' => 'description', 'direction' => $this->priority_direction);
	}

	public function onExport()
	{
		if ($this->listing->introtext && $this->listing->fulltext)
		{
			return $this->listing->introtext . "<hr id=\"system-readmore\" />" . $this->listing->fulltext;
		}
		elseif ($this->listing->introtext)
		{
			return $this->listing->introtext;
		}
		else
		{
			return $this->listing->fulltext;
		}
	}
}

?>