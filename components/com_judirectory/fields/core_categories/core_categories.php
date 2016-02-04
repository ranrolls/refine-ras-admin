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

class JUDirectoryFieldCore_categories extends JUDirectoryFieldBase
{
	protected $field_name = 'cat_id';
	protected $fieldvalue_column = 'c.title';

	protected function getValue()
	{
		$app = JFactory::getApplication();
		// At the frontend, if we already select cats in getListQuery() => use it, first cat is main cat
		if (isset($this->listing->cat_ids) && isset($this->listing->cat_titles) && !is_null($this->listing->cat_ids) && !is_null($this->listing->cat_titles))
		{
			$categories = array();

			$catIdArr    = explode(",", $this->listing->cat_ids);
			$catTitleArr = explode("|||", $this->listing->cat_titles);
			foreach ($catIdArr AS $key => $catId)
			{
				$category        = new stdClass();
				$category->id    = $catIdArr[$key];
				$category->title = $catTitleArr[$key];
				if ($key == 0)
				{
					$category->main = 1;
				}
				else
				{
					$category->main = 0;
				}
				$categories[] = $category;
			}
			$value = $categories;
		}
		else
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select("c.id, c.title, c.parent_id, listingxref.main");
			$query->from("#__judirectory_categories AS c");
			$query->join("", "#__judirectory_listings_xref AS listingxref ON (c.id = listingxref.cat_id)");
			$query->where("listingxref.listing_id = " . $this->listing_id);
			if ($app->isSite())
			{
				// Where in categoryIds can access at frontend
				$categoryIdArrayCanAccess = JUDirectoryFrontHelperPermission::getAccessibleCategoryIds();
				if (is_array($categoryIdArrayCanAccess) && count($categoryIdArrayCanAccess) > 0)
				{
					$query->where('c.id IN(' . implode(",", $categoryIdArrayCanAccess) . ')');
				}
				else
				{
					$query->where('c.id IN("")');
				}
			}
			$query->order("listingxref.main DESC, listingxref.ordering ASC");
			$db->setQuery($query);
			$categories = $db->loadObjectList();
			$value      = $categories;
		}

		return $value;
	}

	public function getLabel($required = true)
	{
		$label = parent::getLabel();

		if ($label == "")
		{
			return "";
		}

		return $this->fetch('label.php', __CLASS__);
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

		$this->setVariable('value', $this->value);

		return $this->fetch('output.php', __CLASS__);
	}

	public function getBackendOutput()
	{
		$categories = $this->value;
		$html       = array();
		if ($categories)
		{
			foreach ($categories AS $category)
			{
				$html[] = '<a href="index.php?option=com_judirectory&view=listcats&cat_id=' . $category->id . '">' . $category->title . '</a>';
			}
		}

		return implode(", ", $html);
	}

	public function getPredefinedValuesHtml()
	{
		return '<span class="readonly">' . JText::_('COM_JUDIRECTORY_NOT_SET') . '</span>';
	}

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		if ((JUDirectoryHelper::getListingSubmitType($this->listing_id) == 'submit' && $this->canSubmit())
			|| (JUDirectoryHelper::getListingSubmitType($this->listing_id) == 'edit' && $this->canEdit())
		)
		{
			$disabled = false;
		}
		else
		{
			$disabled = true;
		}

		$document = JFactory::getDocument();
		$rootCat  = JUDirectoryFrontHelperCategory::getRootCategory();
		JText::script('COM_JUDIRECTORY_TOTAL_CATS_OVER_MAXIMUM_N_CATS');
		JText::script('COM_JUDIRECTORY_CATEGORY_X_ALREADY_EXIST');
		$app = JFactory::getApplication();
		// getParams by cat_id if possible
		if (isset($this->listing) && $this->listing->cat_id)
		{
			$params = JUDirectoryHelper::getParams($this->listing->cat_id);
		}
		else
		{
			$params = JUDirectoryHelper::getParams(null, $this->listing_id);
		}

		$db              = JFactory::getDbo();
		$listingId       = $this->listing_id;
		$listingObject   = JUDirectoryHelper::getListingById($listingId);
		$secondaryCatIds = $secondaryCatIdsStr = "";

		// Edit listing, but not valid data -> $fieldValue = user selected categories
		if ($fieldValue && !empty($fieldValue['main']))
		{
			$categoryId = (int) $fieldValue['main'];
			if ($fieldValue['secondary'])
			{
				$secondaryCatIdsStr = $fieldValue['secondary'];
				$secondaryCatIds    = explode(",", $secondaryCatIdsStr);
			}
			$query = $db->getQuery(true);
			$query->select("c.id, c.parent_id");
			$query->from("#__judirectory_categories AS c");
			$query->select("field_group.id AS fieldgroup_id, field_group.name AS fieldgroup_name");
			$query->join("LEFT", "#__judirectory_fields_groups AS field_group ON (field_group.id = c.fieldgroup_id AND field_group.published = 1)");
			$query->where("c.id = " . $categoryId);
			$db->setQuery($query);
			$mainCategory = $db->loadObject();
		}
		// Edit listing, $fieldValue = null
		elseif ($listingId)
		{
			$categories = $this->value;
			foreach ($categories AS $category)
			{
				if ($category->main == 1)
				{
					$mainCategory = $category;

					$query = $db->getQuery(true);
					$query->select("field_group.id, field_group.name");
					$query->from("#__judirectory_fields_groups AS field_group");
					$query->join("", "#__judirectory_categories AS c on c.fieldgroup_id = field_group.id");
					$query->where("c.id = " . $mainCategory->id);
					$query->where("field_group.published = 1");
					$db->setQuery($query);
					$fieldGroup = $db->loadObject();
					if (is_object($fieldGroup))
					{
						$mainCategory->fieldgroup_name = $fieldGroup->name;
						$mainCategory->fieldgroup_id   = $fieldGroup->id;
					}
					else
					{
						$mainCategory->fieldgroup_name = null;
						$mainCategory->fieldgroup_id   = null;
					}
				}
				else
				{
					$secondaryCatIds[] = $category->id;
				}
			}

			if ($secondaryCatIds)
			{
				$secondaryCatIdsStr = implode(",", $secondaryCatIds);
			}
		}
		// Add listing to specified category
		elseif ($app->input->getInt('cat_id'))
		{
			$categoryId   = $app->input->getInt('cat_id');
			$mainCategory = JUDirectoryHelper::getCategoryById($categoryId);
			$query        = "SELECT id, name FROM #__judirectory_fields_groups WHERE id= " . $mainCategory->fieldgroup_id . " AND published = 1";
			$db->setQuery($query);
			$fieldGroup = $db->loadObject();
			if (is_object($fieldGroup))
			{
				$mainCategory->fieldgroup_name = $fieldGroup->name;
				$mainCategory->fieldgroup_id   = $fieldGroup->id;
			}
			else
			{
				$mainCategory->fieldgroup_name = null;
				$mainCategory->fieldgroup_id   = null;
			}
		}
		// Add listing, no specified category
		else
		{
			$mainCategory                  = new stdClass();
			$mainCategory->id              = '';
			$mainCategory->parent_id       = $rootCat->id;
			$mainCategory->fieldgroup_name = null;
			$mainCategory->fieldgroup_id   = null;
		}

		$document->addStyleSheet(JUri::root() . "components/com_judirectory/fields/" . $this->folder . "/core_categories.css");

		if (!$disabled)
		{
			$document->addScript(JUri::root() . "components/com_judirectory/fields/" . $this->folder . "/core_categories.js");

			if (JUDirectoryHelper::isJoomla3x())
			{
				$jsIsJoomla3x = 1;
			}
			else
			{
				$jsIsJoomla3x = 0;
			}

			$script = "jQuery(document).ready(function($){
								$('.category_selection').listingChangeCategory({
									listing_id: '" . $listingId . "',
									is_joomla_3x: '" . $jsIsJoomla3x . "',
									main_cat_id: '" . $mainCategory->id . "',
									fieldgroup_id: '" . $mainCategory->fieldgroup_id . "',
									fieldgroup_name : '" . $mainCategory->fieldgroup_name . "',
									max_cats : " . (int) $params->get("max_cats_per_listing", 0) . "
								});
						});";

			$document->addScriptDeclaration($script);
		}

		$this->addAttribute("class", "categories", "input");
		$this->addAttribute("class", $this->getInputClass(), "input");

		$this->setVariable('mainCategory', $mainCategory);
		$this->setVariable('secondaryCatIds', $secondaryCatIds);
		$this->setVariable('listingObject', $listingObject);
		$this->setVariable('disabled', $disabled);
		$this->setVariable('secondaryCatIdsStr', $secondaryCatIdsStr);
		$this->setVariable('rootCat', $rootCat);
		$this->setVariable('params', $params);

		return $this->fetch('input.php', __CLASS__);
	}

	public function PHPValidate($values)
	{
		$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();
		// getParams by cat_id if possible
		if (isset($this->listing) && $this->listing->cat_id)
		{
			$params = JUDirectoryHelper::getParams($this->listing->cat_id);
		}
		else
		{
			$params = JUDirectoryHelper::getParams(null, $this->listing_id);
		}

		$mainCatId       = $values['main'];
		$secondaryCatIds = array_filter(explode(",", $values['secondary']));

		if (!$mainCatId)
		{
			return JText::_("COM_JUDIRECTORY_PLEASE_SELECT_A_CATEGORY");
		}

		if ($mainCatId == $rootCat->id && !$params->get('allow_add_listing_to_root', 0))
		{
			return JText::_("COM_JUDIRECTORY_CAN_NOT_ADD_LISTING_TO_ROOT_CATEGORY");
		}

		if (!JUDirectoryHelper::getCategoryById($mainCatId))
		{
			return JText::_("COM_JUDIRECTORY_INVALID_CATEGORY");
		}

		if ($params->get('max_cats_per_listing', 0) && (count($secondaryCatIds) + 1 > $params->get('max_cats_per_listing', 0)))
		{
			return JText::sprintf("COM_JUDIRECTORY_NUMBER_OF_CATEGORY_OVER_MAX_N_CATEGORIES", $params->get('max_cats_per_listing', 0));
		}

		if (!$this->listing_id)
		{
			// If has not permission to submit in this main category
			if (!JUDirectoryFrontHelperPermission::canSubmitListing($mainCatId))
			{
				$category = JUDirectoryHelper::getCategoryById($mainCatId);

				return JText::sprintf("COM_JUDIRECTORY_YOU_ARE_NOT_AUTHORIZED_TO_SUBMIT_LISTING_TO_THIS_CATEGORY", $category->title);
			}
		}
		else
		{
			$mainCatIdDB = JUDirectoryFrontHelperCategory::getMainCategoryId($this->listing_id);

			// Change category -> check submit permission in new cat
			if ($mainCatId != $mainCatIdDB)
			{
				// If has not permission to submit in this main category
				if (!JUDirectoryFrontHelperPermission::canSubmitListing($mainCatId))
				{
					$category = JUDirectoryHelper::getCategoryById($mainCatId);

					return JText::sprintf("COM_JUDIRECTORY_YOU_ARE_NOT_AUTHORIZED_TO_SUBMIT_LISTING_TO_THIS_CATEGORY", $category->title);
				}
			}

			$app = JFactory::getApplication();
			// Front-end
			if ($app->isSite())
			{
				// Can not change main cat when edit listing
				if ($mainCatId != $mainCatIdDB)
				{
					if (!$params->get('can_change_main_category', 1))
					{
						return false;
					}
				}

				// Can not change secondary cats when edit listing
				if (!$params->get('can_change_secondary_categories', 1))
				{
					$secondaryCatIdsDB = $this->getSecondaryCategoryIds($this->listing_id);
					if (count($secondaryCatIds) && count($secondaryCatIdsDB))
					{
						if (array_diff($secondaryCatIds, $secondaryCatIdsDB) || array_diff($secondaryCatIdsDB, $secondaryCatIds))
						{
							return false;
						}
					}
				}
			}
		}

		return true;
	}

	public function filterField($value)
	{
		$secondaryCatIds = explode(",", $value['secondary']);
		$secondaryCatIds = array_unique($secondaryCatIds);

		// Strip new secondary cats that user can not submit to, ignore exist cats
		if (is_array($secondaryCatIds) && count($secondaryCatIds) > 0)
		{
			$secondaryCatIdsDB = $this->getSecondaryCategoryIds($this->listing_id);

			foreach ($secondaryCatIds AS $i => $secondaryCatId)
			{
				// Ignore exist cats
				if (!in_array($secondaryCatId, $secondaryCatIdsDB))
				{
					// If has not permission to submit in $secondaryCatId -> strip it
					if (!JUDirectoryFrontHelperPermission::canSubmitListing($secondaryCatId))
					{
						unset($secondaryCatIds[$i]);
					}
				}
			}
		}

		$value['secondary'] = implode(",", $secondaryCatIds);

		return $value;
	}

	public function onMigrateListing($value = '')
	{
		return $value;
	}

	public function onSaveListing($value = '')
	{
		return $value;
	}


	public function storeValue($value)
	{
		$db = JFactory::getDbo();

		$mainCatId = isset($value['main']) ? $value['main'] : '';
		if (isset($value['secondary']))
		{
			$secondaryCatIds = explode(",", $value['secondary']);
			$secondaryCatIds = array_unique($secondaryCatIds);

			if (is_array($secondaryCatIds) && count($secondaryCatIds) > 0)
			{
				foreach ($secondaryCatIds AS $i => $secondaryCatId)
				{
					if (!is_numeric($secondaryCatId) || $secondaryCatId <= 0 || $secondaryCatId == $mainCatId)
					{
						unset($secondaryCatIds[$i]);
					}
				}
			}
		}

		if ($mainCatId)
		{
			if (!isset($this->listing->cat_id) || !$this->listing->cat_id)
			{
				// Insert main category
				$saveMainCat = JUDirectoryHelper::addCategory($this->listing_id, $mainCatId, 1);
				if (!$saveMainCat)
				{
					return false;
				}
			}
			else
			{
				// Update main cat id
				if ($this->listing->cat_id != $mainCatId)
				{
					$query = "UPDATE #__judirectory_listings_xref SET cat_id = " . $mainCatId . " WHERE listing_id= " . $this->listing_id . " AND main = 1";
					$db->setQuery($query);
					$db->execute();
				}
			}
		}

		/* Add/Update secondary categories */
        $allowCatIds = array();
        if(isset($secondaryCatIds)){
            if($secondaryCatIds) {
                foreach ($secondaryCatIds AS $key => $catId)
                {
                    $query = "SELECT id FROM #__judirectory_listings_xref WHERE listing_id = " . $this->listing_id . " AND cat_id = $catId AND main = 0";
                    $db->setQuery($query);
                    $itemId = $db->loadResult();
                    if ($itemId) {
                        $allowCatIds[] = $catId;
                        $query = "UPDATE #__judirectory_listings_xref SET ordering = " . ($key + 1) . " WHERE id = " . $itemId;
                        $db->setQuery($query);
                        $db->execute();
                    } else {
                        // Insert new secondary cat if it is not exists
                        if(JUDirectoryHelper::addCategory($this->listing_id, $catId, 0, $key + 1)){
                            $allowCatIds[] = $catId;
                        }
                    }
                }
            }

			$query = $db->getQuery(true);
			$query->delete('#__judirectory_listings_xref')
				->where('listing_id = ' . $this->listing_id)
				->where('main = 0');
			if ($allowCatIds)
			{
				$query->where('cat_id NOT IN (' . implode(',', $allowCatIds) . ')');
			}
			$db->setQuery($query);
			$db->execute();
		}

		/* End - Process secondary categories */

		return true;
	}

	//Category must always can submit, so we can assign cat to listing
	public function canSubmit($userID = null)
	{
		return true;
	}

	/* --------------------
	 * HELPER FUNCTIONS
	 * --------------------
	 * */

	protected function getTotalCategories()
	{
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();
		if ($app->isSite())
		{
			$query = "SELECT COUNT(*) FROM #__judirectory_categories WHERE published = 1";
		}
		else
		{
			$query = "SELECT COUNT(*) FROM #__judirectory_categories";
		}
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Method get all secondary category ids
	 *
	 * @param $listingId
	 *
	 * @return mixed
	 */
	protected function getSecondaryCategoryIds($listingId)
	{
		if (!$listingId)
		{
			return array();
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('cat_id');
		$query->from('#__judirectory_listings_xref');
		$query->where('listing_id = ' . $listingId);
		$query->where('main = 0');
		$db->setQuery($query);

		$catIds = $db->loadColumn();

		if ($catIds)
		{
			return $catIds;
		}
		else
		{
			return array();
		}

	}

	protected function getChildCategoryOptions($parentCatId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('title, id, published, parent_id');
		$query->from('#__judirectory_categories');
		$query->where('parent_id = ' . (int) $parentCatId);
		$query->order('lft');
		$db->setQuery($query);
		$categoryObjectList = $db->loadObjectList();
		foreach ($categoryObjectList AS $key => $cat)
		{
			$canSubmitListing = JUDirectoryFrontHelperPermission::canSubmitListing($cat->id);

			// Unset category that user can not submit listing
			if (!$canSubmitListing)
			{
				unset($categoryObjectList[$key]);
				continue;
			}

			// Unpublished category wrap by []
			if ($cat->published != 1)
			{
				$categoryObjectList[$key]->title = "[" . $cat->title . "]";
			}
		}

		$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();

		// getParams by cat_id if possible
		if (isset($this->listing) && $this->listing->cat_id)
		{
			$params = JUDirectoryHelper::getParams($this->listing->cat_id);
		}
		else
		{
			$params = JUDirectoryHelper::getParams(null, $this->listing_id);
		}

		if ($parentCatId != 0 && ($parentCatId != $rootCat->id || ($parentCatId == $rootCat->id && $params->get('allow_add_listing_to_root', 0))))
		{
			$catParent = JUDirectoryHelper::getCategoryByID($parentCatId);
			array_unshift($categoryObjectList, JHtml::_('select.option', $catParent->parent_id, JText::_('COM_JUDIRECTORY_BACK_TO_PARENT_CATEGORY'), 'id', 'title'));
		}

		return $categoryObjectList;
	}

	protected function getAllCategoryOptions()
	{
		$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();

		JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_judirectory/tables");
		$categoryTable = JTable::getInstance('Category', 'JUDirectoryTable');
		$categoryTree  = $categoryTable->getTree($rootCat->id);

		foreach ($categoryTree AS $key => $cat)
		{
			$canSubmitListing = JUDirectoryFrontHelperPermission::canSubmitListing($cat->id);

			// Unset category that user can not submit listing
			if (!$canSubmitListing)
			{
				unset($categoryTree[$key]);
				continue;
			}

			// Unpublished category wrap by []
			if ($cat->published != 1)
			{
				$categoryTree[$key]->title = "[" . $cat->title . "]";
			}

			$categoryTree[$key]->title = str_repeat('|—', $cat->level) . $categoryTree[$key]->title;
		}

		// getParams by cat_id if possible
		if (isset($this->listing) && $this->listing->cat_id)
		{
			$params = JUDirectoryHelper::getParams($this->listing->cat_id);
		}
		else
		{
			$params = JUDirectoryHelper::getParams(null, $this->listing_id);
		}

		if ($params->get('allow_add_listing_to_root', 0))
		{
			array_unshift($categoryTree, JHtml::_('select.option', $rootCat->id, JText::_('COM_JUDIRECTORY_ROOT'), 'id', 'title'));
		}

		return $categoryTree;
	}

	public function orderingPriority(&$query = null)
	{
		return array('ordering' => 'c.title', 'direction' => $this->priority_direction);
	}

	public function onCopy($toListingId, &$fieldData = array())
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from('#__judirectory_listings_xref');
		$query->where('listing_id = ' . $this->listing_id);
		$query->where('main = 0');

		$db->setQuery($query);
		$secondaryCategories = $db->loadObjectList();

		if (!empty($secondaryCategories))
		{
			foreach ($secondaryCategories as $secondaryCategory)
			{
				$query = $db->getQuery(true);
				$query->select('id');
				$query->from('#__judirectory_listings_xref');
				$query->where('listing_id = ' . $toListingId);
				$query->where('cat_id =' . $secondaryCategory->cat_id);
				$db->setQuery($query);
				$isExisted = $db->loadResult();

				if (!$isExisted)
				{
					$query = $db->getQuery(true);

					$query->insert('#__judirectory_listings_xref');
					$query->columns('listing_id, cat_id, main, ordering');
					$query->values("$toListingId,$secondaryCategory->cat_id,0,$secondaryCategory->ordering");

					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}

	public function onExport()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("c.id");
		$query->from("#__judirectory_categories AS c");
		$query->join("", "#__judirectory_listings_xref AS listingxref ON (c.id = listingxref.cat_id)");
		$query->where("listingxref.listing_id = " . $this->listing_id);
		$query->order('listingxref.main DESC, listingxref.ordering ASC');
		$db->setQuery($query);
		$catIds = $db->loadColumn();

		return implode(',', $catIds);
	}

	public function onImport($value, &$message = '')
	{
		if ($value)
		{
			$categoryTable = JTable::getInstance('Category', 'JUDirectoryTable');
			$categoryIds   = explode(",", $value);
			$data          = array();
			$data['main']  = array_shift($categoryIds);
			if ($data['main'] && !$categoryTable->load($data['main'], true))
			{
				//@todo translate
				$message = JText::_('Error - Not found CategoryId ' . $data['main']);

				return false;
			}

			if ($categoryIds)
			{
				$data['secondary'] = array();
				foreach ($categoryIds AS $categoryId)
				{
					if (!$categoryTable->load($categoryId, true))
					{
						$message .= JText::_('Warning - Not found CategoryId ' . $categoryId) . "\n";
						continue;
					}

					$data['secondary'] = $categoryId;
				}

				$data['secondary'] = implode(',', $data['secondary']);
			}

			return $data;
		}

		return false;
	}
}

?>