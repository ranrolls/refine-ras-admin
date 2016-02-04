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


jimport('joomla.application.component.controllerform');


class JUDirectoryControllerGlobalConfig extends JControllerForm
{
	
	protected $text_prefix = 'COM_JUDIRECTORY_GLOBALCONFIG';

	
	public function save($key = null, $urlVar = null)
	{
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$app   = JFactory::getApplication();
		$data  = $app->input->post->get('jform', array(), 'array');
		$model = $this->getModel();
		$task  = $this->getTask();

		
		
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$this->setMessage($model->getError(), 'error');

			return false;
		}

		
		$validData = $model->validate($form, $data);

		if ($validData === false)
		{
			$app = JFactory::getApplication();
			
			$errors = $model->getErrors();

			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$context = "$this->option.edit.$this->context";
			
			$app->setUserState($context . '.data', $data);

			
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item, false
				)
			);

			return false;
		}

		if (isset($validData) && is_array($validData))
		{
			$registry = new JRegistry;
			$registry->loadArray($validData);
			$validData = (string) $registry;
		}

		$config = $validData;
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->update('#__judirectory_categories');
		$query->set('config_params = ' . $db->quote($config));
		$query->where('parent_id = 0');
		$query->where('level = 0');
		$db->setQuery($query);
		$db->execute();
		if ($task == "apply")
		{
			$this->setRedirect("index.php?option=com_judirectory&view=globalconfig&layout=edit", JText::_('COM_JUDIRECTORY_GLOBALCONFIG_SAVED'));
		}
		else
		{
			$this->setRedirect("index.php?option=com_judirectory&view=dashboard", JText::_('COM_JUDIRECTORY_GLOBALCONFIG_SAVED'));
		}
	}

	
	public function cancel($key = null)
	{
		$this->setRedirect("index.php?option=com_judirectory&view=dashboard");
	}

	public function resetDefault()
	{
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$rootCat       = JUDirectoryFrontHelperCategory::getRootCategory();
		$defaultConfig = '{"activate_maintenance":"0","maintenance_message":"Directory area is down for maintenance.<br \/> Please check back again soon.","number_rating_stars":5,"rating_star_width":16,"split_star":"2","enable_listing_rate":"1","enable_listing_rate_in_comment_form":"1","require_listing_rate_in_comment_form":"1","rating_interval":86400,"only_calculate_last_rating":"0","rating_explanation":"1:Bad\r\n3:Poor\r\n5:Fair\r\n7:Good\r\n9:Excellent","rating_statistic":"","min_rates_to_show_rating":0,"min_rates_for_top_rated":0,"listing_report_subjects":"Broken link\r\nCopyright infringement\r\nWrong category","comment_report_subjects":"Spam\r\nInappropriate","listing_owner_use_captcha_when_report":"0","collection_allow_vote":"1","collection_allow_vote_down":"1","collection_allow_owner_vote":"0","collection_allow_guest_vote":"1","collection_desc_limit":500,"log_events_for_guest":"0","captcha_width":155,"captcha_height":50,"captcha_length":6,"captcha_color":"#050505","captcha_bg_color":"#ffffff","captcha_line_color":"#707070","captcha_noise_color":"#707070","captcha_num_lines":5,"captcha_noise_level":2,"captcha_perturbation":5,"captcha_font":"AHGBold.ttf","map_api_key":"","map_center":"62.323907,-150.109291","map_zoom":"2","map_fitbound_maxzoom":"13","map_language":"","map_region":"ar","edit_account_details":"1","public_user_dashboard":"0","searchword_min_length":"3","searchword_max_length":"30","limit_string":"5,10,15,20,25,30,50","plugin_support":"0","activate_subscription_by_email":"1","field_attachment_directory":"media\/com_judirectory\/field_attachments\/","category_fields_listview_ordering":{"title":"2","id":"2","alias":"0","parent_id":"0","rel_cats":"0","access":"0","lft":"0","fieldgroup_id":"0","criteriagroup_id":"0","featured":"0","published":"0","show_item":"0","description":"0","intro_image":"0","detail_image":"0","publish_up":"0","publish_down":"0","created_by":"0","created":"0","modified_by":"0","modified":"0","style_id":"0","layout":"0","metatitle":"0","metakeyword":"0","metadescription":"0","metadata":"0","total_categories":"0","total_listings":"0"},"template_upload_limit":"2","template_image_formats":"gif,bmp,jpg,jpeg,png","template_source_formats":"txt,less,ini,xml,js,php,css","template_font_formats":"woff,ttf,otf","template_compressed_formats":"zip","allow_add_listing_to_root":"0","reset_listing_alias_when_approving":"1","listing_owner_can_view_unpublished_listing":"0","listing_owner_can_edit_listing_auto_approval":"1","auto_approval_listing_threshold":0,"listing_owner_can_edit_state_listing":"0","listing_owner_can_report_listing":"1","claim_type":"groups","claim_groups":["8"],"claim_users":"","max_recently_viewed_listings":12,"required_fields_to_mark_listing_as_updated":"","can_change_main_category":"1","can_change_secondary_categories":"1","max_cats_per_listing":10,"max_images_per_listing":8,"max_tags_per_listing":10,"submit_listing_interval":30,"assign_itemid_to_submit_link":"currentItemid","predefined_itemid_for_submit_link":0,"max_related_listings":12,"related_listings_ordering":"listingrel.ordering","related_listings_direction":"ASC","imagequality":90,"customfilters":"","sharpen":"0","canvastransparency":"1","canvascolour":"#ffffff","listing_small_image_width":100,"listing_small_image_height":100,"listing_small_image_zoomcrop":"1","listing_small_image_alignment":"c","listing_big_image_width":600,"listing_big_image_height":600,"listing_big_image_zoomcrop":"3","listing_big_image_alignment":"c","use_watermark":"0","watermark_image":"","watermark_text":"","watermark_font":"arial.ttf","watermark_fontsize":14,"watermark_fontcolor":"#ffffff","watermark_backgroundcolor":"#144274","watermark_halign":"0","watermark_valign":"0","watermark_offsetx":0,"watermark_offsety":0,"watermark_opacity":"0.8","watermark_rotate":0,"image_min_width":50,"image_min_height":50,"image_max_width":1024,"image_max_height":1024,"image_max_size":400,"listing_default_image":"-1","listing_image_width":100,"listing_image_height":100,"listing_image_zoomcrop":"1","listing_image_alignment":"c","category_intro_image_width":200,"category_intro_image_height":200,"category_intro_image_zoomcrop":"1","category_intro_image_alignment":"c","category_detail_image_width":200,"category_detail_image_height":200,"category_detail_image_zoomcrop":"1","category_detail_image_alignment":"c","avatar_source":"juavatar","default_avatar":"default-avatar.png","avatar_width":120,"avatar_height":120,"avatar_zoomcrop":"1","avatar_alignment":"c","collection_default_icon":"-1","collection_icon_width":100,"collection_icon_height":100,"collection_icon_zoomcrop":"1","collection_icon_alignment":"c","listing_image_filename_rule":"{image_name}","listing_original_image_directory":"media\/com_judirectory\/images\/gallery\/original\/","listing_small_image_directory":"media\/com_judirectory\/images\/gallery\/small\/","listing_big_image_directory":"media\/com_judirectory\/images\/gallery\/big\/","listing_image_directory":"media\/com_judirectory\/images\/listing\/","category_image_filename_rule":"{category}","category_intro_image_directory":"media\/com_judirectory\/images\/category\/intro\/","category_detail_image_directory":"media\/com_judirectory\/images\/category\/detail\/","avatar_directory":"media\/com_judirectory\/images\/avatar\/","collection_icon_directory":"media\/com_judirectory\/images\/collection\/","comment_system":"default","disqus_username":"","show_comment_direction":"1","comment_ordering":"cm.created","comment_direction":"DESC","show_comment_pagination":"0","comment_pagination":10,"filter_comment_rating":"1","filter_comment_language":"0","max_comment_level":5,"auto_link_url_in_comment":"1","nofollow_link_in_comment":"1","trim_long_url_in_comment":0,"front_portion_url_in_comment":0,"back_portion_url_in_comment":0,"auto_embed_youtube_in_comment":"0","auto_embed_vimeo_in_comment":"0","video_width_in_comment":360,"video_height_in_comment":240,"comment_interval":60,"comment_interval_in_same_listing":60,"auto_approval_comment_threshold":0,"auto_approval_comment_reply_threshold":0,"allow_edit_comment_within":600,"unpublish_comment_by_reporting_threshold":10,"allow_vote_comment":"1","allow_vote_down_comment":"1","can_reply_own_comment":"0","can_vote_own_comment":"0","can_subscribe_own_comment":"1","can_report_own_comment":"1","delete_own_comment":"0","listing_owner_can_comment":"0","listing_owner_can_comment_many_times":"0","listing_owner_auto_approval_when_comment":"0","listing_owner_can_reply_comment":"1","listing_owner_auto_approval_when_reply_comment":"0","listing_owner_use_captcha_when_comment":"1","listing_owner_can_vote_comment":"1","listing_owner_can_report_comment":"1","website_field_in_comment_form":"0","comment_form_editor":"wysibb","min_comment_characters":20,"max_comment_characters":1000,"bb_bold_tag":"Bold","bb_italic_tag":"Italic","bb_underline_tag":"Underline","bb_img_tag":"Picture","bb_link_tag":"Link","bb_video_tag":"Video","bb_color_tag":"Colors","bb_smilebox_tag":"Smilebox","bb_fontsize_tag":"Fontsize","bb_bulleted_list":"Bulleted-list","bb_numeric_list":"Numeric-list","bb_quote_tag":"Quotes","bb_readmore_tag":"Readmore","bb_code_tag":"Code","bb_align_left":"alignleft","bb_align_center":"aligncenter","bb_align_right":"alignright","userid_blacklist":"","forbidden_names":"","forbidden_words":"","forbidden_words_replaced_by":"***","block_ip":"0","ip_whitelist":"","ip_blacklist":"","top_comment_level":"all","top_comments_limit":100,"email_attachment_directory":"media\/com_judirectory\/email_attachments\/","email_upload_maxsize":10240,"email_upload_legal_extensions":"bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,zip,rar","email_check_mime":"0","email_image_legal_extensions":"bmp,gif,jpg,png","email_ignored_extensions":"","email_upload_legal_mime":"image\/jpeg,image\/gif,image\/png,image\/bmp,application\/x-shockwave-flash,application\/msword,application\/excel,application\/pdf,application\/powerpoint,text\/plain,application\/zip","email_embedded_files":"0","email_charset":"UTF-8","enable_mailq":"0","use_mailq_default":"0","send_mailqs_on_pageload":"0","total_mailqs_sent_each_time":5,"mailq_max_attempts":5,"delete_error_mailq":"0","all_categories_show_category_title":"1","all_categories_subcategory_level":"-1","all_categories_show_empty_category":"1","all_categories_show_total_subcategories":"1","all_categories_show_total_listings":"1","all_categories_columns":2,"all_categories_column_class":"","all_categories_row_class":"","show_featured_label":"1","show_hot_label":"1","num_hit_per_day_to_be_hot":100,"show_new_label":"1","num_day_to_show_as_new":10,"show_updated_label":"1","num_day_to_show_as_updated":10,"show_empty_field":"0","submit_form_show_tab_related":"0","submit_form_show_tab_plugin_params":"0","submit_form_show_tab_publishing":"0","submit_form_show_tab_style":"0","submit_form_show_tab_meta_data":"0","submit_form_show_tab_params":"0","submit_form_show_tab_permissions":"0","show_header_sort":"1","listing_pagination":10,"show_pagination":"1","default_view_mode":"2","allow_user_select_view_mode":"1","listing_columns":2,"listing_column_class":"","listing_row_class":"","show_compare_btn_in_listview":"0","list_alpha":"0-9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z","top_listings_limit":100,"show_submit_listing_btn_in_category":"1","category_show_description":"1","category_desc_limit":0,"category_show_image":"1","category_image_width":200,"category_image_height":200,"related_category_ordering":"crel.ordering","related_category_direction":"ASC","show_empty_related_category":"1","show_total_subcats_of_relcat":"0","show_total_listings_of_relcat":"0","related_category_show_introtext":"1","related_category_introtext_character_limit":500,"related_category_show_intro_image":"1","related_category_intro_image_width":200,"related_category_intro_image_height":200,"related_category_columns":2,"related_category_column_class":"","related_category_row_class":"","subcategory_ordering":"title","subcategory_direction":"ASC","show_empty_subcategory":"1","show_total_subcats_of_subcat":"0","show_total_listings_of_subcat":"0","subcategory_show_introtext":"1","subcategory_introtext_character_limit":500,"subcategory_show_intro_image":"1","subcategory_intro_image_width":200,"subcategory_intro_image_height":200,"subcategory_columns":2,"subcategory_column_class":"","subcategory_row_class":"","display_params":{"listing":{"show_comment":"1","fields":{"title":{"details_view":"1"},"created":{"details_view":"1"},"author":{"details_view":"1"},"cat_id":{"details_view":"1"},"rating":{"details_view":"1"}}},"cat":{"show_description":"1"}},"seo_replace_title_option":"replace","seo_replace_description_option":"replace","seo_replace_keywords_option":"replace","seo_title_length":64,"seo_description_length":160,"seo_keywords_length":160,"seo_user_title":"{user_name}","seo_user_description":"{meta_description}","seo_user_keywords":"{meta_keywords}","seo_collection_title":"{collection_title}","seo_collection_description":"{meta_description}","seo_collection_keywords":"{meta_keywords}","seo_listing_title":"{listing_title}","seo_listing_description":"{meta_description}","seo_listing_keywords":"{meta_keywords}","seo_category_title":"{cat_title}","seo_category_description":"{meta_description}","seo_category_keywords":"{meta_keywords}","seo_field_title":"{field_title}","seo_field_description":"{meta_description}","seo_field_keywords":"{meta_keywords}","seo_tag_title":"{tag_title}","seo_tag_description":"{meta_description}","seo_tag_keywords":"{meta_keywords}","sef_category_full_path":"0","sef_listing_full_path":"0","sef_categories":"categories","sef_tree":"tree","sef_featured":"featured","sef_list_all":"list-all","sef_list_alpha":"list-alpha","sef_tags":"tags","sef_tag":"tag","sef_collections":"collections","sef_collection":"collection","sef_custom_list":"custom-list","sef_advanced_search":"advsearch","sef_search":"search","sef_searchby":"searchby","sef_guest_subscribe":"guest-subscribe","sef_maintenance":"maintenance","sef_listings":"modal-listings","sef_contact":"contact","sef_claim":"claim","sef_compare":"compare","sef_comment_tree":"comment-tree","sef_top_comments":"top-comments","sef_top_listings_latest":"latest-listings","sef_top_listings_featured":"top-featured-listings","sef_top_listings_recent_modified":"recent-modified-listings","sef_top_listings_recent_updated":"recent-updated-listings","sef_top_listings_popular":"popular-listings","sef_top_listings_most_rated":"most-rated-listings","sef_top_listings_top_rated":"top-rated-listings","sef_top_listings_latest_rated":"latest-rated-listings","sef_top_listings_most_commented":"most-commented-listings","sef_top_listings_latest_commented":"latest-commented-listings","sef_top_listings_recently_viewed":"recent-viewed-listings","sef_top_listings_alpha_ordered":"alpha-ordered-listings","sef_top_listings_random":"random-listings","sef_top_listings_random_fast":"random-fast-listings","sef_top_listings_random_featured":"random-featured-listings","sef_add":"add","sef_edit":"edit","sef_delete":"delete","sef_publish":"publish","sef_unpublish":"unpublish","sef_checkin":"checkin","sef_approve":"approve","sef_subscribe":"subscribe","sef_unsubscribe":"unsubscribe","sef_activate_subscription":"activate-subscription","sef_print":"print","sef_download_email_attachment":"download-attachment","sef_remove_compare":"remove-compare","sef_remove_compare_all":"all","sef_redirect_url":"redirect-url","sef_dashboard":"dashboard","sef_profile":"profile","sef_user_listings":"listings","sef_published":"published","sef_unpublished":"unpublished","sef_pending":"pending","sef_user_subscriptions":"subscriptions","sef_user_comments":"comments","sef_mod_listings":"mod-listings","sef_mod_comments":"mod-comments","sef_mod_comment":"mod-comment","sef_mod_pending_listings":"mod-pending-listings","sef_mod_pending_listing":"mod-pending-listing","sef_mod_pending_comments":"mod-pending-comments","sef_mod_pending_comment":"mod-pending-comment","sef_mod_permissions":"mod-permissions","sef_mod_permission":"mod-permission","sef_root_cat":"root","sef_rss":"rss","sef_report":"report","sef_layout":"layout","sef_page":"page-","sef_all":"all","sef_new_listing":"new-listing","sef_comment":"comment","sef_component":"component","sef_file":"file","sef_raw_data":"raw-data","sef_space":"-","rss_display_icon":"1","rss_number_items_in_feed":10,"rss_show_thumbnail":"1","rss_thumbnail_source":"image","rss_thumbnail_alignment":"left","rss_email":"none","load_jquery":"2","load_jquery_ui":"2"}';
		$db            = JFactory::getDbo();
		$query         = 'UPDATE #__judirectory_categories SET config_params=' . $db->quote($defaultConfig) . ' WHERE id = ' . $rootCat->id;
		$db->setQuery($query);
		if ($db->execute())
		{
			$this->setRedirect("index.php?option=com_judirectory&view=globalconfig&layout=edit", JText::_('COM_JUDIRECTORY_GLOBAL_CONFIG_RESET_DEFAULT_SUCCESS'));
		}
		else
		{
			$this->setRedirect("index.php?option=com_judirectory&view=globalconfig&layout=edit", JText::_('COM_JUDIRECTORY_GLOBAL_CONFIG_RESET_DEFAULT_FAILED'));
		}
	}
}
