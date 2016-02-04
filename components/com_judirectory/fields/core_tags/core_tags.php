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

class JUDirectoryFieldCore_tags extends JUDirectoryFieldBase
{
	protected $field_name = 'tags';
	protected $fieldvalue_column = 't.title';

	protected function getValue()
	{
		$value = null;
		$app   = JFactory::getApplication();

		if ($app->isSite())
		{
			
			if (isset($this->listing->tag_titles) && !is_null($this->listing->tag_ids) && !is_null($this->listing->tag_titles))
			{
				$tags = array();
				
				if ($this->listing->tag_ids)
				{
					$tagIdArr    = explode(",", $this->listing->tag_ids);
					$tagTitleArr = explode("|||", $this->listing->tag_titles);
					foreach ($tagIdArr AS $key => $tagId)
					{
						$tag        = new stdClass();
						$tag->id    = $tagIdArr[$key];
						$tag->title = $tagTitleArr[$key];
						$tags[]     = $tag;
					}
				}

				$value = $tags;
			}
			else
			{
				$value = JUDirectoryFrontHelper::getTagsByListingId($this->listing_id, 't.*', true, true, true);
			}
		}
		else
		{
			$value = JUDirectoryFrontHelper::getTagsByListingId($this->listing_id, 't.*', false, false, false);
		}

		return $value;
	}

	public function getBackendOutput()
	{
		$tags = $this->value;
		$html = array();
		if ($tags)
		{
			foreach ($tags AS $tag)
			{
				$html[] = '<span><a href="index.php?option=com_judirectory&task=tag.edit&id=' . $tag->id . '">' . $tag->title . '</a></span>';
			}
		}

		return implode(", ", $html);
	}

	public function getOutput($options = array())
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$this->setVariable('value', $this->value);

		return $this->fetch('output.php', __CLASS__);
	}

	public function getPredefinedValuesHtml()
	{
		return '<span class="readonly">' . JText::_('COM_JUDIRECTORY_NOT_SET') . '</span>';
	}

	public function PHPValidate($values)
	{
		
		if (isset($this->listing) && $this->listing->cat_id)
		{
			$params = JUDirectoryHelper::getParams($this->listing->cat_id);
		}
		else
		{
			$params = JUDirectoryHelper::getParams(null, $this->listing_id);
		}

		$maxTags = $params->get("max_tags_per_listing", 10);
		if ($maxTags)
		{
			$values = str_replace("|", ",", $values);
			$tags   = explode(",", $values);

			if (count($tags) > $maxTags)
			{
				return JText::sprintf('COM_JUDIRECTORY_TOTAL_TAGS_OVER_MAX_X_TAGS', $maxTags);
			}
		}

		return parent::PHPValidate($values);
	}

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$app = JFactory::getApplication();
		
		if (isset($this->listing) && $this->listing->cat_id)
		{
			$params = JUDirectoryHelper::getParams($this->listing->cat_id);
		}
		else
		{
			$params = JUDirectoryHelper::getParams(null, $this->listing_id);
		}

		$document = JFactory::getDocument();
		JUDirectoryFrontHelper::loadjQueryUI();
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/css/tagit-style.css");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/tagit.js");
		if ($app->isAdmin())
		{
			$maxTags = "undefined";
		}
		else
		{
			$maxTags = $params->get("max_tags_per_listing", 10) == 0 ? "undefined" : $params->get("max_tags_per_listing", 10);
		}

		if ($this->params->get("sortable", "handle") == "handle")
		{
			$sortable = '"handle"';
		}
		else
		{
			$sortable = $this->params->get("sortable", "handle");
		}
		$tagScript = '
            jQuery(document).ready(function($){
                $("#' . $this->getId() . '_tags").tagit({
	                tagSource:' . $this->getTags() . ',
	                initialTags: ' . $this->getInitialTags($fieldValue) . ',
	                tagsChanged: function(){ getTags($("#' . $this->getId() . '_tags").tagit("tags")); },
	                triggerKeys:["enter", "comma", "tab"],
	                minLength: ' . $this->params->get("tag_min_length", 3) . ',
	                maxLength: ' . $this->params->get("tag_max_length", 50) . ',
	                maxTags: ' . $maxTags . ',
	                sortable: ' . $sortable . ',
	                placeholder: " ' . $this->params->get("sortable", "") . ' "
	            });

	            function getTags(tags) {
	                var newtags = [];
	                for (var i in tags){
	                    if (tags[i].label != undefined ){
	                        var tagvalue = tags[i].value.replace("|", "");
	                        newtags.push(tagvalue);
	                        $(tags[i].element[0]).find(".tagit-label").text(tagvalue);
	                    }
	                }
	                var newtags_str = newtags.join(",");
	                $("#' . $this->getId() . '").val(newtags_str);
	            }
            });
        ';

		$document->addScriptDeclaration($tagScript);
		$tag_name_arr = array();
		$value        = '';
		if (is_null($fieldValue))
		{
			$tags = $this->value;
			if ($tags)
			{
				foreach ($tags AS $tag)
				{
					$tag_name_arr[] = $tag->title;
				}
				$value = implode(',', $tag_name_arr);
			}
		}
		else
		{
			$value = $fieldValue;
		}

		$this->setVariable('value', $value);

		return $this->fetch('input.php', __CLASS__);
	}

	
	protected function getTags($filterAccess = false, $filterLanguage = false)
	{
		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();

		$query = $db->getQuery(true);
		$query->select('title');
		$query->from('#__judirectory_tags');
		$query->where('published = 1');
		$query->where('(publish_up = ' . $db->quote($nullDate) . ' OR publish_up <= ' . $db->quote($nowDate) . ')');
		$query->where('(publish_down = ' . $db->quote($nullDate) . ' OR publish_down >= ' . $db->quote($nowDate) . ')');
		if ($filterAccess)
		{
			
			$user      = JFactory::getUser();
			$levels    = $user->getAuthorisedViewLevels();
			$levelsStr = implode(',', $levels);
			$query->where('access IN (' . $levelsStr . ')');
		}

		if ($filterLanguage)
		{
			
			$app         = JFactory::getApplication();
			$languageTag = JFactory::getLanguage()->getTag();
			if ($app->isSite() && $app->getLanguageFilter())
			{
				$query->where('language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ',' . $db->quote('') . ')');
			}
		}

		$db->setQuery($query);
		$tags = $db->loadObjectList();
		$data = array();
		foreach ($tags AS $tag)
		{
			$data[] = $tag->title;
		}

		return json_encode($data);
	}

	
	protected function getInitialTags($defaultValue = null)
	{
		if ($defaultValue)
		{
			$defaultValue = explode(",", $defaultValue);

			return json_encode($defaultValue);
		}

		$initialTags = array();
		$tags        = $this->value;
		if ($tags)
		{
			foreach ($tags AS $tag)
			{
				$initialTags[] = $tag->title;
			}
		}

		return json_encode($initialTags);
	}

	public function getSearchInput($defaultValue = "")
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$document = JFactory::getDocument();
		JUDirectoryFrontHelper::loadjQueryUI();
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/css/tagit-style.css");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/tagit.js");

		if ($this->params->get("sortable", "handle") == "handle")
		{
			$sortable = '"handle"';
		}
		else
		{
			$sortable = $this->params->get("sortable", "handle");
		}

		$tagScript = '
            jQuery(document).ready(function($){
                $("#' . $this->getId() . '_tags").tagit({
	                tagSource:' . $this->getTags(true, true) . ',
	                initialTags: ' . $this->getInitialTags($defaultValue) . ',
	                tagsChanged: function(){ getTags($("#' . $this->getId() . '_tags").tagit("tags")); },
	                triggerKeys:["enter", "comma", "tab"],
	                minLength: ' . $this->params->get("tag_min_length", 3) . ',
	                maxLength: ' . $this->params->get("tag_max_length", 50) . ',
	                maxTags: 50,
	                sortable: ' . $sortable . ',
	                allowNewTags: false,
	                placeholder: " ' . $this->params->get("placeholder", "") . ' "
	            });

	            function getTags(tags) {
	                var newtags = [];
	                for (var i in tags){
	                    if (tags[i].label != undefined ){
	                        var tagvalue = tags[i].value.replace("|", "");
	                        newtags.push(tagvalue);
	                        $(tags[i].element[0]).find(".tagit-label").text(tagvalue);
	                    }
	                }
	                var newtags_str = newtags.join(",");
	                $("#' . $this->getId() . '").val(newtags_str);
	            }
            });
        ';
		$document->addScriptDeclaration($tagScript);

		$this->setVariable('value', $defaultValue);

		return $this->fetch('searchinput.php', __CLASS__);
	}

	public function onSearch(&$query, &$where, $search)
	{
		if ($search !== "")
		{
			$db       = JFactory::getDbo();
			$nullDate = $db->getNullDate();
			$nowDate  = JFactory::getDate()->toSql();

			$user      = JFactory::getUser();
			$levels    = $user->getAuthorisedViewLevels();
			$levelsStr = implode(',', $levels);

			
			$tagSearchCondition = ' AND t.published = 1';
			$tagSearchCondition .= ' AND (t.publish_up = ' . $db->quote($nullDate) . ' OR t.publish_up <= ' . $db->quote($nowDate) . ')';
			$tagSearchCondition .= ' AND (t.publish_down = ' . $db->quote($nullDate) . ' OR t.publish_down >= ' . $db->quote($nowDate) . ')';
			
			$tagSearchCondition .= ' AND t.access IN (' . $levelsStr . ')';
			
			$app = JFactory::getApplication();
			if ($app->isSite() && $app->getLanguageFilter())
			{
				$languageTag = JFactory::getLanguage()->getTag();
				$tagSearchCondition .= ' AND t.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ',' . $db->quote('') . ')';
			}
			$query->join('LEFT', "#__judirectory_tags_xref AS txref ON (listing.id = txref.listing_id)");
			$query->join('LEFT', "#__judirectory_tags AS t ON (t.id = txref.tag_id " . $tagSearchCondition . ")");

			$tags = explode(",", $search);
			foreach ($tags AS $key => $tag)
			{
				$tags[$key] = $db->quote($tag);
			}
			$where[] = $this->fieldvalue_column . " IN (" . implode(",", $tags) . ")";
		}
	}

	public function storeValue($value)
	{
		$db   = JFactory::getDbo();
		$date = JFactory::getDate();
		$tags = explode(",", $value);
		JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_judirectory/tables");
		$tag_table = JTable::getInstance('Tag', 'JUDirectoryTable');
		$user      = JFactory::getUser();
		$ordering  = 0;
		foreach ($tags AS $tag)
		{
			
			if ($tag != '')
			{
				$ordering++;
				
				
				$tag_table->reset();
				if ($tag_table->load(array('title' => trim($tag))))
				{
					$mapped_tag_id[] = $tag_table->id;
					
					$query = $db->getQuery(true);
					$query->SELECT('id');
					$query->FROM('#__judirectory_tags_xref');
					$query->WHERE('tag_id = ' . $tag_table->id . ' AND listing_id = ' . $this->listing_id);
					$db->setQuery($query);
					$tagxrefId = $db->loadResult();
					
					if (!$tagxrefId)
					{
						$query = $db->getQuery(true);
						$query->insert('#__judirectory_tags_xref');
						$query->set('tag_id = ' . $tag_table->id . ', listing_id = ' . $this->listing_id . ', ordering = ' . $ordering);
						$db->setQuery($query);
						$db->execute();
					}
					
					else
					{
						$query = "UPDATE #__judirectory_tags_xref SET ordering = " . $ordering . " WHERE id = " . $tagxrefId;
						$db->setQuery($query);
						$db->execute();
					}
				}
				
				else
				{
					
					$tag_table->bind(
						array(
							'id'         => 0,
							'title'      => trim($tag),
							'access'     => 1,
							'language'   => '*',
							'published'  => 1,
							'created_by' => $user->id,
							'created'    => $date->toSql()
						)
					);
					$db = JFactory::getDbo();
					$db->setQuery('SELECT MAX(ordering) FROM #__judirectory_tags');
					$max                 = $db->loadResult();
					$tag_table->ordering = $max + 1;
					if ($tag_table->check() && $tag_table->store())
					{
						$tag_id_new      = $tag_table->id;
						$mapped_tag_id[] = $tag_id_new;

						
						$query = $db->getQuery(true);
						$query->insert('#__judirectory_tags_xref');
						$query->set('tag_id = ' . $tag_id_new . ', listing_id = ' . $this->listing_id . ', ordering = ' . $ordering);
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}

		
		if (!empty($mapped_tag_id))
		{
			$mapped_tag_id_str = implode(",", $mapped_tag_id);
			$query             = "DELETE FROM #__judirectory_tags_xref WHERE listing_id = " . $this->listing_id . " AND tag_id NOT IN (" . $mapped_tag_id_str . ")";
		}
		else
		{
			
			$query = "DELETE FROM #__judirectory_tags_xref WHERE listing_id = " . $this->listing_id;
		}
		$db->setQuery($query);
		$db->execute();
	}

	public function onDelete($deleteAll = false)
	{
		if ($this->listing_id)
		{
			
			$db    = JFactory::getDbo();
			$query = "DELETE FROM #__judirectory_tags_xref WHERE listing_id= " . $this->listing_id;
			$db->setQuery($query);
			$db->execute();
		}
	}

	public function orderingPriority(&$query = null)
	{
		$where_str = '';
		$app       = JFactory::getApplication();
		if ($app->isSite())
		{
			$db        = JFactory::getDbo();
			$nullDate  = $db->getNullDate();
			$nowDate   = JFactory::getDate()->toSql();
			$where_str = ' WHERE t.published = 1'
				. ' AND (t.publish_up = ' . $db->quote($nullDate) . ' OR t.publish_up <= ' . $db->quote($nowDate) . ')'
				. ' AND (t.publish_down = ' . $db->quote($nullDate) . ' OR t.publish_down >= ' . $db->quote($nowDate) . ')';
		}

		$this->appendQuery($query, 'select', 'COUNT (t.*) AS tags' . $where_str);
		$this->appendQuery($query, 'left join', '#__judirectory_tags AS t ON t.id = txref.tag_id');
		$this->appendQuery($query, 'left join', '#__judirectory_tags_xref AS txref ON txref.listing_id = listing.id');

		return array('ordering' => 'tags', 'direction' => $this->priority_direction);
	}


	public function onCopy($toListingId, &$fieldsData = array())
	{
		$db = JFactory::getDbo();

		
		$query = "SELECT * FROM #__judirectory_tags_xref WHERE listing_id=" . $this->listing_id . " ORDER BY ordering ASC";
		$db->setQuery($query);
		$tags = $db->loadObjectList();
		if (!empty($tags))
		{
			foreach ($tags AS $tag)
			{
				$query = "INSERT INTO #__judirectory_tags_xref (tag_id, listing_id, ordering) VALUES (" . $tag->tag_id . ", " . $toListingId . ", " . $tag->ordering . ")";
				$db->setQuery($query);
				$db->execute();
			}
		}
	}

	public function onExport()
	{
		if ($this->listing_id)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('tag.id')
				->from('#__judirectory_tags AS tag')
				->join('', '#__judirectory_tags_xref AS tagxref ON tagxref.tag_id = tag.id')
				->join('', '#__judirectory_listings AS listing ON listing.id = tagxref.listing_id')
				->where('listing.id = ' . $this->listing_id)
				->order('tagxref.ordering');
			$db->setQuery($query);

			return implode(',', $db->loadColumn());
		}

		return '';
	}

	public function onImport($value, &$message = '')
	{
		$tagIds = array();
		$db     = JFactory::getDbo();
		if ($value)
		{
			$value = str_replace('|', ',', $value);
			$tags  = explode(',', $value);
			if ($tags)
			{
				$tagTable = JTable::getInstance('Tag', 'JUDirectoryTable');
				$date     = JFactory::getDate();
				$user     = JFactory::getUser();
				$ordering = 0;
				foreach ($tags AS $tag)
				{
					$tagTable->reset();
					$tagId = 0;
					if (is_numeric($tag))
					{
						if ($tagTable->load($tag))
						{
							$tagId = $tag;
						}
					}
					else
					{
						$tagAlias = JApplication::stringURLSafe($tag);
						if ($tagTable->load(array('alias' => $tagAlias)))
						{
							$tagId = $tagTable->id;
						}
						else
						{
							
							$newTagData               = array();
							$newTagData['id']         = 0;
							$newTagData['title']      = $tag;
							$newTagData['alias']      = $tagAlias;
							$newTagData['published']  = 1;
							$newTagData['created']    = $date->toSql();
							$newTagData['created_by'] = $user->id;
							$newTagData['access']     = 1;
							$newTagData['language']   = '*';
							$newTagData['ordering']   = $tagTable->getNextOrder();

							if ($tagTable->bind($newTagData) && $tagTable->store())
							{
								$tagId = $tagTable->id;
							}
						}
					}

					if ($tagId)
					{
						$tagIds[] = $tagId;
						$query    = $db->getQuery(true);
						$query->select('COUNT(*)')
							->from('#__judirectory_tags_xref')
							->where('listing_id = ' . $this->listing_id . ' AND tag_id = ' . $tagId);
						$db->setQuery($query);

						$ordering++;
						if ($db->loadResult())
						{
							$query = $db->getQuery(true);
							$query->update('#__judirectory_tags_xref')
								->set('ordering = ' . $ordering)
								->where('listing_id = ' . $this->listing_id . ' AND tag_id = ' . $tagId);
							$db->setQuery($query);
							$db->execute();
						}
						else
						{
							$query = $db->getQuery(true);
							
							$columns = array('tag_id', 'listing_id', 'ordering');
							
							$values = array($tagId, $this->listing_id, $ordering);

							$query->insert('#__judirectory_tags_xref')
								->columns($db->quoteName($columns))
								->values(implode(',', $values));

							$db->setQuery($query);
							$db->execute();
						}
					}
				}
			}
		}

		
		if (!$this->is_new)
		{
			$query = $db->getQuery(true);
			$query->delete('#__judirectory_tags_xref');
			if ($tagIds)
			{
				$query->where('listing_id = ' . $this->listing_id . ' AND tag_id NOT IN (' . implode(',', $tagIds) . ')');
			}
			else
			{
				$query->where('listing_id = ' . $this->listing_id);
			}

			$db->setQuery($query);
			$db->execute();
		}

		return false;
	}
}

?>