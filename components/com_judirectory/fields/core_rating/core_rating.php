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

class JUDirectoryFieldCore_rating extends JUDirectoryFieldBase
{
	
	protected $field_name = 'rating';

	
	protected $regex = "/^\d+(\.\d+)?$/";

	
	public $parent;

	
	public $multiRating;

	
	public $totalStars;

	
	public $starWidth;

	
	public $starParts;

	
	public $totalInputs;

	
	public $explanation;

	
	public $scoreIncrement;

	
	public $juparams;

	
	public $canRateListing = false;

	
	public $token;

	
	public $criteriaGroupId = 0;

	
	public $criteriaObjectList = array();

	
	public $selectedStar;

	
	public $templatePath;

	public function initMultipleRatingField()
	{
		
		if (isset($this->listing) && $this->listing->cat_id)
		{
			$juparams = JUDirectoryHelper::getParams($this->listing->cat_id);
		}
		else
		{
			$juparams = JUDirectoryHelper::getParams(null, $this->listing_id);
		}
		$this->juparams = $juparams;

		$this->totalStars        = (int) $this->juparams->get('number_rating_stars', 5);
		$this->starWidth         = (int) $this->juparams->get('rating_star_width', 16);
		$this->starParts         = (int) $this->juparams->get('split_star', 2);
		$this->totalInputs       = $this->totalStars * $this->starParts;
		$this->ratingExplanation = $this->getRatingExplanation();
		$this->scoreIncrement    = $this->calculateValuePerInput($this->totalStars, $this->starParts);
		$this->token             = JSession::getFormToken();

		if (JUDirectoryHelper::hasMultiRating())
		{
			$this->multiRating = new JUDirectoryMultiRating($this);
		}
	}

	protected function getValue()
	{
		return $this->listing->rating;
	}

	public function getPredefinedValuesHtml()
	{
		return '<span class="readonly">' . JText::_('COM_JUDIRECTORY_NONE') . '</span>';
	}

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$this->setAttribute("type", "text", "input");
		$this->addAttribute("class", $this->getInputClass(), "input");

		$value = !is_null($fieldValue) ? $fieldValue : $this->value;

		if ((int) $this->params->get("size", 32))
		{
			$this->setAttribute("size", (int) $this->params->get("size", 32), "input");
		}

		if ($this->params->get("placeholder", ""))
		{
			$placeholder = htmlspecialchars($this->params->get("placeholder", ""), ENT_COMPAT, 'UTF-8');
			$this->setAttribute("placeholder", $placeholder, "input");
		}

		$app = JFactory::getApplication();
		if ($app->isSite())
		{
			$this->setAttribute("readonly", "readonly", "input");
		}

		$this->setVariable('value', $value);

		return $this->fetch("input.php", __CLASS__);
	}

	public function getOutput($options = array())
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$options = new JRegistry($options);

		$this->initMultipleRatingField();

		
		$this->loadDefaultAssets();

		if ($this->listing->cat_id)
		{
			$this->criteriaGroupId = JUDirectoryFrontHelperCriteria::getCriteriaGroupIdByCategoryId($this->listing->cat_id);
		}

		if (JUDirectoryHelper::hasMultiRating())
		{
			if ($this->criteriaGroupId)
			{
				$this->criteriaObjectList = JUDirectoryFrontHelperCriteria::getCriteriasByCatId($this->listing->cat_id);
			}
		}

		
		$this->selectedStar = round($this->listing->rating * $this->totalStars / 10, 2);

		
		if ($this->isDetailsView($options))
		{
			$this->canRateListing = JUDirectoryFrontHelperPermission::canRateListing($this->listing_id);

			$document = JFactory::getDocument();

			
			$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/css/jquery.rating.css");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/jquery.MetaData.js");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/jquery.rating.js");

			$singleRatingScript = '
				jQuery(document).ready(function ($) {
					$(".judir-single-rating").rating({
						callback: function (value) {
									var str = $(this).attr("name");
									var patt = /^judir-listing-rating-result-(.*)$/i;
							var result = str.match(patt);
							var listingId = result[1];
							var ratingValue = $(this).val();
							var objectListing = {};
							var token = $("#judir-single-rating-token").attr("name");
							objectListing.listing_id = listingId;
							objectListing.ratingValue = ratingValue;
							if ($.isNumeric(listingId) && (ratingValue > 0 && ratingValue <= 10)) {
								$.ajax({
									type: "POST",
									url : "index.php?option=com_judirectory&task=listing.singleRating&" + token + "=1",
									data: objectListing
								}).done(function (msg) {
									alert(msg);
									
								});
							}
						}
					});
				});';

			if ($this->canRateListing)
			{
				$document->addScriptDeclaration($singleRatingScript);
			}
		}

		if (is_object($this->multiRating))
		{
			return $this->multiRating->getOutput($options);
		}
		else
		{
			$this->setVariable('options', $options);
			$this->setVariable('className', __CLASS__);

			return $this->fetch('output.php', __CLASS__);
		}
	}

	
	public function getStatisticByNumberStars($totalStars, $ratingScoreObjectList)
	{
		$statisticStarRated = array();
		
		for ($i = 1; $i <= $totalStars; $i++)
		{
			$statisticStarRated[$i] = 0;
		}

		foreach ($ratingScoreObjectList AS $ratingScoreObject)
		{
			
			$ratedStar = $ratingScoreObject->score * $totalStars / 10;
			$ratedStar = (int) ceil($ratedStar);

			$statisticStarRated[$ratedStar] += 1;
		}

		return $statisticStarRated;
	}

	
	public static function getAllRatingScoresOfListing($listingId)
	{
		$params = JUDirectoryHelper::getParams(null, $listingId);
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('r.score');
		$query->from('#__judirectory_rating AS r');
		$query->where('r.listing_id = ' . $listingId);
		$query->join('LEFT', '#__judirectory_comments AS cm ON r.id = cm.rating_id');
		$query->where('(cm.approved = 1 OR cm.approved IS NULL)');
		$query->where('r.user_id > 0');
		if ($params->get('only_calculate_last_rating', 0))
		{
			$subQuery = '(SELECT MAX(lastrated_tbl.created) FROM #__judirectory_rating AS lastrated_tbl WHERE r.user_id = lastrated_tbl.user_id)';
			$query->where('(r.created = ' . $subQuery . ')');
			$query->group('r.user_id, r.created');
		}
		$db->setQuery($query);
		$userRating = $db->loadObjectList();
		if (!is_array($userRating))
		{
			$userRating = array();
		}

		$query = $db->getQuery(true);
		$query->select('r.score');
		$query->from('#__judirectory_rating AS r');
		$query->where('r.listing_id = ' . $listingId);
		$query->join('LEFT', '#__judirectory_comments AS cm ON r.id = cm.rating_id');
		$query->where('(cm.approved = 1 OR cm.approved IS NULL)');
		$query->where('r.user_id = 0');
		$db->setQuery($query);
		$guestRating = $db->loadObjectList();
		if (!is_array($guestRating))
		{
			$guestRating = array();
		}

		$listingRatings = array_merge($userRating, $guestRating);

		return $listingRatings;
	}

	
	protected function getRatingExplanation()
	{
		$totalStars                 = $this->juparams->get('number_rating_stars', 5);
		$starSplit                  = $this->juparams->get('split_star', 2);
		$totalInputs                = $totalStars * $starSplit;
		$ratingExplanationConfigStr = $this->juparams->get('rating_explanation', "1:Bad\n3:Poor\n5:Fair\n7:Good\n9:Excellent");
		$ratingExplanationConfigArr = explode("\n", $ratingExplanationConfigStr);

		$ratingExplanationArr = array();
		
		for ($i = 1; $i <= $totalInputs; $i++)
		{
			$ratingExplanationArr[$i] = null;
		}

		
		foreach ($ratingExplanationConfigArr AS $ratingExplanationConfigItem)
		{
			$ratingExplanationItemArr = explode(":", $ratingExplanationConfigItem, 2);
			if (count($ratingExplanationItemArr) == 2 && is_numeric($ratingExplanationItemArr[0]))
			{
				$ratingExplanationArr[$ratingExplanationItemArr[0]] = $ratingExplanationItemArr[1];
			}
		}

		
		foreach ($ratingExplanationArr AS $starPart => $explanation)
		{
			if (is_null($explanation) && $starPart > 1)
			{
				$ratingExplanationArr[$starPart] = $ratingExplanationArr[$starPart - 1];
			}
		}

		return $ratingExplanationArr;
	}

	
	protected function calculateValuePerInput($totalStars, $splitStar = 1)
	{
		if ($totalStars == 0)
		{
			return 0;
		}

		$valuePerInput = 0;

		if ($totalStars * $splitStar)
		{
			$totalInputs   = $totalStars * $splitStar;
			$valuePerInput = 10 / $totalInputs;
			
			$valuePerInput = round($valuePerInput, 6, PHP_ROUND_HALF_DOWN);
		}

		return $valuePerInput;
	}

}

?>