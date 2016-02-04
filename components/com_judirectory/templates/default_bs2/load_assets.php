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

require_once "template_helper.php";

$app      = JFactory::getApplication();
$document = JFactory::getDocument();

// Name of current template that component using
//$this->template

// Name of this template
$self_template = basename(dirname(__FILE__));

$templateStyle = JUDirectoryFrontHelperTemplate::getCurrentTemplateStyle();
$templateParams = $templateStyle->params;

//Load font awesome icon
$document->addStyleSheet(JUri::root(true) . '/components/com_judirectory/assets/css/font-awesome.min.css');

JUDirectoryFrontHelper::loadjQuery();
JUDirectoryFrontHelper::loadBootstrap(2, $templateParams->get('load_bootstrap', '2'));

$JUDIRTemplateDefaultHelper = new JUDIRTemplateDefaultHelper($self_template);

$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/css/reset.css");
$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/css/core.css");
$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/common.css");

// JText in core.js
JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THESE_LISTINGS');
JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_PUBLISH_THESE_LISTINGS');
JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_UNPUBLISH_THESE_LISTINGS');

$view = $this->getName();
switch ($view)
{
	case 'advsearch':
		if ($app->input->getInt('advancedsearch', 0) || !is_null($app->input->get('limitstart')))
		{
			// Load primary stylesheet
			$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

			// Load primary javascript
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

			$JUDIRTemplateDefaultHelper->loadTooltipster();

			// Load switch mode view
			if ($this->allow_user_select_view_mode)
			{
				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
			}
		}
		else
		{
			$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.advsearch.css");
		}
		break;

	case 'categories':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.categories.css");
		break;

	case 'category':
		// Load primary stylesheet
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.category.css");
		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
        $document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");
		if ($this->getLayout() != 'list')
		{
			$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

			$JUDIRTemplateDefaultHelper->loadTooltipster();

			// Load switch mode view
			if ($this->allow_user_select_view_mode)
			{
				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
			}
		}

		if($this->params->get('category_show_map', 1) && $this->locations)
		{
			$params = JUDirectoryHelper::getParams($this->category->id);
			$center   = $params->get('map_center', '62.323907,-150.109291');
			$center   = array_map('trim', explode(',', $center));
			$zoom     = $params->get('map_zoom', '2');
			$fitBoundMaxZoom = $params->get('map_fitbound_maxzoom', '13');

			$data   = addslashes(json_encode($this->locations));
			$script = 'jQuery(document).ready(function($){
				$("#julocation").mapbrowse({
					data : \'' . $data . '\',
					mapOptions :{
						zoom : '.$zoom.',
						center : {lat : '.$center[0].', lng : '.$center[1].'},
						fitBoundMaxZoom: '.$fitBoundMaxZoom.'
					},
					markerUrl : \'' . JUri::root(true) . '/media/com_judirectory/images/marker/\',
					JUriRoot: \'' . JUri::root(true) . '\'
				});
			});';

			$document->addScript("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places");
			$document->addScript("http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js");
			$document->addScript("http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/mapbrowse.js");
			$document->addScriptDeclaration($script);
		}
		break;

	case 'collection' :
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.collection.css");

		if ($this->getLayout() == 'edit')
		{
			JUDirectoryFrontHelper::loadjQueryUI();
			$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/css/typeahead.collection.css");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/handlebars.min.js");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/typeahead.bundle.min.js");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/jquery.dragsort.min.js");
			// JText in view.collection.js
			JText::script('COM_JUDIRECTORY_PENDING_ADD');
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/view.collection.js");
		}
		else
		{
			$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

			// Load primary javascript
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

			$JUDIRTemplateDefaultHelper->loadTooltipster();
		}

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'collections':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.collections.css");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/jquery.juvote.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/view.collections.js");

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'commenttree':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.commenttree.css");

		$JUDIRTemplateDefaultHelper->loadTooltipster();
		break;

	case 'compare':
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$document->addScript(JURI::root(true) . "/components/com_judirectory/assets/js/jquery.equal.height.row.js");

		$scriptEqual = 'jQuery(document).ready (function ($) {
			$("div.equal-height-row").equalHeightRow();
		});';
		$document->addScriptDeclaration($scriptEqual);

		break;

	case 'contact' :
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.contact.css");
		break;

	case 'customlist' :
		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");
		if ($this->getLayout() != 'list')
		{
			$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

			$JUDIRTemplateDefaultHelper->loadTooltipster();

			// Load switch mode view
			if ($this->allow_user_select_view_mode)
			{
				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
			}
		}

		if($this->params->get('category_show_map', 1) && $this->locations)
		{
			$zoom     = $params->get('map_zoom', '2');
			$center   = $params->get('map_center', '62.323907,-150.109291');
			$center   = array_map('trim', explode(',', $center));
			$fitBoundMaxZoom = $params->get('map_fitbound_maxzoom', '13');

			$data   = addslashes(json_encode($this->locations));
			$script = 'jQuery(document).ready(function($){
				$("#julocation").mapbrowse({
					data: \'' . $data . '\',
					mapOptions:{
						zoom: '.$zoom.',
						center: {lat: '.$center[0].', lng: '.$center[1].'},
						fitBoundMaxZoom: '.$fitBoundMaxZoom.'
					},
					markerUrl : \'' . JUri::root(true) . '/media/com_judirectory/images/marker/\',
					JUriRoot: \'' . JUri::root(true) . '\'
				});
			});';

			$document->addScript("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places");
			$document->addScript("http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js");
			$document->addScript("http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js");
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/mapbrowse.js");
			$document->addScriptDeclaration($script);
		}

		break;

	case 'dashboard' :
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.dashboard.css");
		break;

	case 'listings':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listings.css");
		break;

	case 'listing':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing.css");

		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
        $document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$script = 'var listingId = ' . $this->item->id . ',
						token = "' . $this->token . '";';
		$document->addScriptDeclaration($script);

		// JText in view.listing.js
		JText::script('COM_JUDIRECTORY_PLEASE_RATING_BEFORE_SUBMIT_COMMENT');
		JText::script('COM_JUDIRECTORY_THANK_YOU_FOR_VOTING');
		JText::script('COM_JUDIRECTORY_INVALID_FIELD');
		JText::script('COM_JUDIRECTORY_REQUIRED_FIELD');
		JText::script('COM_JUDIRECTORY_YOU_HAVE_NOT_ENTERED_COLLECTION_NAME');
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/view.listing.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

        if($this->_layout == 'default')
        {
            if ($this->item->comment->total_comments_no_filter)
            {
                // Vote comment
                $document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/jquery.juvote.js");

                // Readmore comment
                $document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/readmore.min.js");

                $readmoreCommentJS = "jQuery(document).ready(function($){
				            $('.comment-text .comment-content').readmore({
				                speed    : 300,
				                maxHeight: 150,
				                moreLink: '<span class=\"see-more\" title=\"" . JText::_('COM_JUDIRECTORY_SEE_MORE') . "\" href=\"#\"><i class=\"fa fa-chevron-down\"></i></span>',
				                lessLink: '<span class=\"see-less\" title=\"" . JText::_('COM_JUDIRECTORY_SEE_LESS') . "\" href=\"#\"><i class=\"fa fa-chevron-up\"></i></span>',
				                embedCSS: false
				            });
				        });";
                $document->addScriptDeclaration($readmoreCommentJS);
            }

            if ($this->params->get('comment_form_editor', 'wysibb') == 'wysibb' && $this->params->get('comment_system', 'default') == 'default')
            {
                JUDirectoryFrontHelperEditor::getWysibbEditor('.comment-editor');
                // JText in comment-wysibb.js
                JText::script('COM_JUDIRECTORY_UPDATE_COMMENT_ERROR');
                JText::script('COM_JUDIRECTORY_PLEASE_ENTER_AT_LEAST_N_CHARACTERS');
                JText::script('COM_JUDIRECTORY_CONTENT_LENGTH_REACH_MAX_N_CHARACTERS');
                $document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/comment-wysibb.js");
            }
        }

		break;

	case 'embedlisting':
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/view.embedlisting.js");
		break;

	case 'featured':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'form' :
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.form.css");

		JUDirectoryFrontHelper::loadjQueryUI();
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/handlebars.min.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/jquery.dragsort.min.js");
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/plupload/css/jquery.plupload.queue.css");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/plupload/js/plupload.full.min.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/plupload/js/jquery.plupload.queue.min.js");

		JUDirectoryHelper::formValidation();

		// JText in forms/listing.js
		JText::script('COM_JUDIRECTORY_INVALID_IMAGE');
		JText::script('COM_JUDIRECTORY_REMOVE');
		JText::script('COM_JUDIRECTORY_CAN_NOT_ADD_IMAGE_BECAUSE_MAX_NUMBER_OF_IMAGE_IS_N');
		JText::script('COM_JUDIRECTORY_TOGGLE_TO_PUBLISH');
		JText::script('COM_JUDIRECTORY_TOGGLE_TO_UNPUBLISH');
		JText::script('COM_JUDIRECTORY_CLICK_TO_REMOVE');
		JText::script('COM_JUDIRECTORY_YOU_MUST_UPLOAD_AT_LEAST_ONE_IMAGE');
		JText::script('COM_JUDIRECTORY_DESCRIPTION');
		JText::script('COM_JUDIRECTORY_FIELD_TITLE');
		JText::script('COM_JUDIRECTORY_FIELD_DESCRIPTION');
		JText::script('COM_JUDIRECTORY_FIELD_PUBLISHED');
		JText::script('COM_JUDIRECTORY_UPDATE');
		JText::script('COM_JUDIRECTORY_CANCEL');
		$document->addScript(JUri::root(true) . "/" . $this->script);
		break;

	case 'listall':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listall.css");
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'listalpha':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listalpha.css");
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'modcomment':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.modcomment.css");
		break;

	case 'modcomments':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.modcomments.css");

		// JText in comments.js
		JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_PUBLISH_THESE_COMMENTS');
		JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_UNPUBLISH_THESE_COMMENTS');
		JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THESE_COMMENTS');
		JText::script('COM_JUDIRECTORY_NO_ITEM_SELECTED');
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/comments.js");
		break;

	case 'modlistings' :
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.modlistings.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/view.modlistings.js");
		break;

	case 'modpermission':
		break;

	case 'modpermissions' :
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		break;

	case 'modpendingcomments':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.modpendingcomments.css");

		// JText in comments.js
		JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_PUBLISH_THESE_COMMENTS');
		JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_UNPUBLISH_THESE_COMMENTS');
		JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THESE_COMMENTS');
		JText::script('COM_JUDIRECTORY_NO_ITEM_SELECTED');
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/comments.js");
		break;

	case 'modpendinglistings' :
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.modpendinglistings.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/view.modpendinglistings.js");
		break;

	case 'profile':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.dashboard.css");
		break;

	case 'report':
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/view.report.js");
		break;

	case 'search':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'searchby':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'subscribe':
		break;

	case 'tag':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'tags':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.tags.css");
		break;

	case 'topcomments':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.topcomments.css");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Readmore comment
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/readmore.min.js");

		$readmoreCommentJS = "jQuery(document).ready(function($){
				            $('.comment-text .comment-content').readmore({
				                speed    : 300,
				                maxHeight: 150,
				                moreLink: '<span class=\"see-more\" title=\"" . JText::_('COM_JUDIRECTORY_SEE_MORE') . "\" href=\"#\"><i class=\"fa fa-chevron-down\"></i></span>',
				                lessLink: '<span class=\"see-less\" title=\"" . JText::_('COM_JUDIRECTORY_SEE_LESS') . "\" href=\"#\"><i class=\"fa fa-chevron-up\"></i></span>',
				                embedCSS: false
				            });
				        });";
		$document->addScriptDeclaration($readmoreCommentJS);
		break;

	case 'toplistings':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		// JText in view.toplistings.js
		JText::script('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_CLEAR_ALL_RECENTLY_VIEWED_LISTINGS');
		JText::script('COM_JUDIRECTORY_CLEAR_ALL_RECENTLY_VIEWED_LISTINGS_SUCCESSFULLY');
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/view.toplistings.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'tree':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.category.css");
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'usercomments':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.usercomments.css");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Readmore comment
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/readmore.min.js");

		$readmoreCommentJS = "jQuery(document).ready(function($){
				            $('.comment-text .comment-content').readmore({
				                speed    : 300,
				                maxHeight: 150,
				                moreLink: '<span class=\"see-more\" title=\"" . JText::_('COM_JUDIRECTORY_SEE_MORE') . "\" href=\"#\"><i class=\"fa fa-chevron-down\"></i></span>',
				                lessLink: '<span class=\"see-less\" title=\"" . JText::_('COM_JUDIRECTORY_SEE_LESS') . "\" href=\"#\"><i class=\"fa fa-chevron-up\"></i></span>',
				                embedCSS: false
				            });
				        });";
		$document->addScriptDeclaration($readmoreCommentJS);
		break;

	case 'userlistings':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.listing-list.css");

		// Load primary javascript
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/core.js");
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/compare.js");

		$JUDIRTemplateDefaultHelper->loadTooltipster();

		// Load switch mode view
		if ($this->allow_user_select_view_mode)
		{
			$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/switch.js");
		}
		break;

	case 'usersubscriptions':
		$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/templates/" . $self_template . "/assets/css/view.usersubscriptions.css");
		break;

	default:
		break;
}
?>