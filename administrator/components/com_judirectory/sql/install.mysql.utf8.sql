/*Table structure for table `#__judirectory_addresses` */

DROP TABLE IF EXISTS `#__judirectory_addresses`;

CREATE TABLE `#__judirectory_addresses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_lft_rgt` (`lft`,`rgt`),
  KEY `idx_parentid_level` (`parent_id`,`level`),
  KEY `idx_rgt` (`rgt`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_published` (`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_addresses` */

/*Table structure for table `#__judirectory_backend_permission` */

DROP TABLE IF EXISTS `#__judirectory_backend_permission`;

CREATE TABLE `#__judirectory_backend_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL DEFAULT '0',
  `permission` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_groupid` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_backend_permission` */

/*Table structure for table `#__judirectory_categories` */

DROP TABLE IF EXISTS `#__judirectory_categories`;

CREATE TABLE `#__judirectory_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '1',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `selected_fieldgroup` int(11) NOT NULL DEFAULT '-1' COMMENT 'Extra field group id that user selected',
  `fieldgroup_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'The real extra field group id(calculated for inherited value)',
  `selected_criteriagroup` int(11) NOT NULL DEFAULT '-1',
  `criteriagroup_id` int(11) unsigned NOT NULL DEFAULT '0',
  `images` text NOT NULL,
  `introtext` text NOT NULL,
  `fulltext` text NOT NULL,
  `show_item` tinyint(3) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `style_id` int(11) NOT NULL DEFAULT '-1',
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL,
  `class_sfx` varchar(255) NOT NULL,
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  `asset_id` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `field_ordering_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `config_params` mediumtext NOT NULL,
  `template_params` text NOT NULL,
  `plugin_params` text NOT NULL,
  `params` text NOT NULL,
  `metatitle` varchar(255) NOT NULL,
  `metakeyword` varchar(1024) NOT NULL,
  `metadescription` varchar(1024) NOT NULL,
  `metadata` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_lft_rgt` (`lft`,`rgt`),
  KEY `idx_title` (`title`),
  KEY `idx_alias` (`alias`),
  KEY `idx_language` (`language`),
  KEY `idx_featured` (`featured`),
  KEY `idx_publishing` (`published`,`publish_up`,`publish_down`),
  KEY `idx_catid_published` (`id`,`published`),
  KEY `idx_level` (`level`),
  KEY `idx_fieldgroupid` (`fieldgroup_id`),
  KEY `idx_criteriagroupid` (`criteriagroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_categories` */

insert  into `#__judirectory_categories`(`id`,`title`,`alias`,`parent_id`,`lft`,`rgt`,`level`,`selected_fieldgroup`,`fieldgroup_id`,`selected_criteriagroup`,`criteriagroup_id`,`images`,`introtext`,`fulltext`,`show_item`,`created`,`created_by`,`modified`,`modified_by`,`style_id`,`featured`,`published`,`publish_up`,`publish_down`,`language`,`class_sfx`,`access`,`asset_id`,`checked_out`,`checked_out_time`,`field_ordering_type`,`config_params`,`template_params`,`plugin_params`,`params`,`metatitle`,`metakeyword`,`metadescription`,`metadata`) values (1,'Root','root',0,0,1,0,0,0,0,0,'','','',1,'0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0,-2,0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00','*','',1,78,0,'0000-00-00 00:00:00',0,'{\"activate_maintenance\":\"0\",\"maintenance_message\":\"Directory area is down for maintenance.<br \\/> Please check back again soon.\",\"number_rating_stars\":5,\"rating_star_width\":16,\"split_star\":\"2\",\"enable_listing_rate\":\"1\",\"enable_listing_rate_in_comment_form\":\"1\",\"require_listing_rate_in_comment_form\":\"1\",\"rating_interval\":86400,\"only_calculate_last_rating\":\"0\",\"rating_explanation\":\"1:Bad\\r\\n3:Poor\\r\\n5:Fair\\r\\n7:Good\\r\\n9:Excellent\",\"rating_statistic\":\"\",\"min_rates_to_show_rating\":0,\"min_rates_for_top_rated\":0,\"listing_report_subjects\":\"Broken link\\r\\nCopyright infringement\\r\\nWrong category\",\"comment_report_subjects\":\"Spam\\r\\nInappropriate\",\"listing_owner_use_captcha_when_report\":\"0\",\"collection_allow_vote\":\"1\",\"collection_allow_vote_down\":\"1\",\"collection_allow_owner_vote\":\"0\",\"collection_allow_guest_vote\":\"1\",\"collection_desc_limit\":500,\"log_events_for_guest\":\"0\",\"captcha_width\":155,\"captcha_height\":50,\"captcha_length\":6,\"captcha_color\":\"#050505\",\"captcha_bg_color\":\"#ffffff\",\"captcha_line_color\":\"#707070\",\"captcha_noise_color\":\"#707070\",\"captcha_num_lines\":5,\"captcha_noise_level\":2,\"captcha_perturbation\":5,\"captcha_font\":\"AHGBold.ttf\",\"map_api_key\":\"\",\"map_center\":\"62.323907,-150.109291\",\"map_zoom\":\"2\",\"map_fitbound_maxzoom\":\"13\",\"map_language\":\"\",\"map_region\":\"ar\",\"edit_account_details\":\"1\",\"public_user_dashboard\":\"0\",\"searchword_min_length\":\"3\",\"searchword_max_length\":\"30\",\"limit_string\":\"5,10,15,20,25,30,50\",\"plugin_support\":\"0\",\"activate_subscription_by_email\":\"1\",\"field_attachment_directory\":\"media\\/com_judirectory\\/field_attachments\\/\",\"category_fields_listview_ordering\":{\"title\":\"2\",\"id\":\"2\",\"alias\":\"0\",\"parent_id\":\"0\",\"rel_cats\":\"0\",\"access\":\"0\",\"lft\":\"0\",\"fieldgroup_id\":\"0\",\"criteriagroup_id\":\"0\",\"featured\":\"0\",\"published\":\"0\",\"show_item\":\"0\",\"description\":\"0\",\"intro_image\":\"0\",\"detail_image\":\"0\",\"publish_up\":\"0\",\"publish_down\":\"0\",\"created_by\":\"0\",\"created\":\"0\",\"modified_by\":\"0\",\"modified\":\"0\",\"style_id\":\"0\",\"layout\":\"0\",\"metatitle\":\"0\",\"metakeyword\":\"0\",\"metadescription\":\"0\",\"metadata\":\"0\",\"total_categories\":\"0\",\"total_listings\":\"0\"},\"template_upload_limit\":\"2\",\"template_image_formats\":\"gif,bmp,jpg,jpeg,png\",\"template_source_formats\":\"txt,less,ini,xml,js,php,css\",\"template_font_formats\":\"woff,ttf,otf\",\"template_compressed_formats\":\"zip\",\"allow_add_listing_to_root\":\"0\",\"reset_listing_alias_when_approving\":\"1\",\"listing_owner_can_view_unpublished_listing\":\"0\",\"listing_owner_can_edit_listing_auto_approval\":\"1\",\"auto_approval_listing_threshold\":0,\"listing_owner_can_edit_state_listing\":\"0\",\"listing_owner_can_report_listing\":\"1\",\"claim_type\":\"groups\",\"claim_groups\":[\"8\"],\"claim_users\":\"\",\"max_recently_viewed_listings\":12,\"required_fields_to_mark_listing_as_updated\":\"\",\"can_change_main_category\":\"1\",\"can_change_secondary_categories\":\"1\",\"max_cats_per_listing\":10,\"max_images_per_listing\":8,\"max_tags_per_listing\":10,\"submit_listing_interval\":30,\"assign_itemid_to_submit_link\":\"currentItemid\",\"predefined_itemid_for_submit_link\":0,\"max_related_listings\":12,\"related_listings_ordering\":\"listingrel.ordering\",\"related_listings_direction\":\"ASC\",\"imagequality\":90,\"customfilters\":\"\",\"sharpen\":\"0\",\"canvastransparency\":\"1\",\"canvascolour\":\"#ffffff\",\"listing_small_image_width\":100,\"listing_small_image_height\":100,\"listing_small_image_zoomcrop\":\"1\",\"listing_small_image_alignment\":\"c\",\"listing_big_image_width\":600,\"listing_big_image_height\":600,\"listing_big_image_zoomcrop\":\"3\",\"listing_big_image_alignment\":\"c\",\"use_watermark\":\"0\",\"watermark_image\":\"\",\"watermark_text\":\"\",\"watermark_font\":\"arial.ttf\",\"watermark_fontsize\":14,\"watermark_fontcolor\":\"#ffffff\",\"watermark_backgroundcolor\":\"#144274\",\"watermark_halign\":\"0\",\"watermark_valign\":\"0\",\"watermark_offsetx\":0,\"watermark_offsety\":0,\"watermark_opacity\":\"0.8\",\"watermark_rotate\":0,\"image_min_width\":50,\"image_min_height\":50,\"image_max_width\":1024,\"image_max_height\":1024,\"image_max_size\":400,\"listing_default_image\":\"-1\",\"listing_image_width\":100,\"listing_image_height\":100,\"listing_image_zoomcrop\":\"1\",\"listing_image_alignment\":\"c\",\"category_intro_image_width\":200,\"category_intro_image_height\":200,\"category_intro_image_zoomcrop\":\"1\",\"category_intro_image_alignment\":\"c\",\"category_detail_image_width\":200,\"category_detail_image_height\":200,\"category_detail_image_zoomcrop\":\"1\",\"category_detail_image_alignment\":\"c\",\"avatar_source\":\"juavatar\",\"default_avatar\":\"default-avatar.png\",\"avatar_width\":120,\"avatar_height\":120,\"avatar_zoomcrop\":\"1\",\"avatar_alignment\":\"c\",\"collection_default_icon\":\"-1\",\"collection_icon_width\":100,\"collection_icon_height\":100,\"collection_icon_zoomcrop\":\"1\",\"collection_icon_alignment\":\"c\",\"listing_image_filename_rule\":\"{image_name}\",\"listing_original_image_directory\":\"media\\/com_judirectory\\/images\\/gallery\\/original\\/\",\"listing_small_image_directory\":\"media\\/com_judirectory\\/images\\/gallery\\/small\\/\",\"listing_big_image_directory\":\"media\\/com_judirectory\\/images\\/gallery\\/big\\/\",\"listing_image_directory\":\"media\\/com_judirectory\\/images\\/listing\\/\",\"category_image_filename_rule\":\"{category}\",\"category_intro_image_directory\":\"media\\/com_judirectory\\/images\\/category\\/intro\\/\",\"category_detail_image_directory\":\"media\\/com_judirectory\\/images\\/category\\/detail\\/\",\"avatar_directory\":\"media\\/com_judirectory\\/images\\/avatar\\/\",\"collection_icon_directory\":\"media\\/com_judirectory\\/images\\/collection\\/\",\"comment_system\":\"default\",\"disqus_username\":\"\",\"show_comment_direction\":\"1\",\"comment_ordering\":\"cm.created\",\"comment_direction\":\"DESC\",\"show_comment_pagination\":\"0\",\"comment_pagination\":10,\"filter_comment_rating\":\"1\",\"filter_comment_language\":\"0\",\"max_comment_level\":5,\"auto_link_url_in_comment\":\"1\",\"nofollow_link_in_comment\":\"1\",\"trim_long_url_in_comment\":0,\"front_portion_url_in_comment\":0,\"back_portion_url_in_comment\":0,\"auto_embed_youtube_in_comment\":\"0\",\"auto_embed_vimeo_in_comment\":\"0\",\"video_width_in_comment\":360,\"video_height_in_comment\":240,\"comment_interval\":60,\"comment_interval_in_same_listing\":60,\"auto_approval_comment_threshold\":0,\"auto_approval_comment_reply_threshold\":0,\"allow_edit_comment_within\":600,\"unpublish_comment_by_reporting_threshold\":10,\"allow_vote_comment\":\"1\",\"allow_vote_down_comment\":\"1\",\"can_reply_own_comment\":\"0\",\"can_vote_own_comment\":\"0\",\"can_subscribe_own_comment\":\"1\",\"can_report_own_comment\":\"1\",\"delete_own_comment\":\"0\",\"listing_owner_can_comment\":\"0\",\"listing_owner_can_comment_many_times\":\"0\",\"listing_owner_auto_approval_when_comment\":\"0\",\"listing_owner_can_reply_comment\":\"1\",\"listing_owner_auto_approval_when_reply_comment\":\"0\",\"listing_owner_use_captcha_when_comment\":\"1\",\"listing_owner_can_vote_comment\":\"1\",\"listing_owner_can_report_comment\":\"1\",\"website_field_in_comment_form\":\"0\",\"comment_form_editor\":\"wysibb\",\"min_comment_characters\":20,\"max_comment_characters\":1000,\"bb_bold_tag\":\"Bold\",\"bb_italic_tag\":\"Italic\",\"bb_underline_tag\":\"Underline\",\"bb_img_tag\":\"Picture\",\"bb_link_tag\":\"Link\",\"bb_video_tag\":\"Video\",\"bb_color_tag\":\"Colors\",\"bb_smilebox_tag\":\"Smilebox\",\"bb_fontsize_tag\":\"Fontsize\",\"bb_bulleted_list\":\"Bulleted-list\",\"bb_numeric_list\":\"Numeric-list\",\"bb_quote_tag\":\"Quotes\",\"bb_readmore_tag\":\"Readmore\",\"bb_code_tag\":\"Code\",\"bb_align_left\":\"alignleft\",\"bb_align_center\":\"aligncenter\",\"bb_align_right\":\"alignright\",\"userid_blacklist\":\"\",\"forbidden_names\":\"\",\"forbidden_words\":\"\",\"forbidden_words_replaced_by\":\"***\",\"block_ip\":\"0\",\"ip_whitelist\":\"\",\"ip_blacklist\":\"\",\"top_comment_level\":\"all\",\"top_comments_limit\":100,\"email_attachment_directory\":\"media\\/com_judirectory\\/email_attachments\\/\",\"email_upload_maxsize\":10240,\"email_upload_legal_extensions\":\"bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,zip,rar\",\"email_check_mime\":\"0\",\"email_image_legal_extensions\":\"bmp,gif,jpg,png\",\"email_ignored_extensions\":\"\",\"email_upload_legal_mime\":\"image\\/jpeg,image\\/gif,image\\/png,image\\/bmp,application\\/x-shockwave-flash,application\\/msword,application\\/excel,application\\/pdf,application\\/powerpoint,text\\/plain,application\\/zip\",\"email_embedded_files\":\"0\",\"email_charset\":\"UTF-8\",\"enable_mailq\":\"0\",\"use_mailq_default\":\"0\",\"send_mailqs_on_pageload\":\"0\",\"total_mailqs_sent_each_time\":5,\"mailq_max_attempts\":5,\"delete_error_mailq\":\"0\",\"all_categories_show_category_title\":\"1\",\"all_categories_subcategory_level\":\"-1\",\"all_categories_show_empty_category\":\"1\",\"all_categories_show_total_subcategories\":\"1\",\"all_categories_show_total_listings\":\"1\",\"all_categories_columns\":2,\"all_categories_column_class\":\"\",\"all_categories_row_class\":\"\",\"show_featured_label\":\"1\",\"show_hot_label\":\"1\",\"num_hit_per_day_to_be_hot\":100,\"show_new_label\":\"1\",\"num_day_to_show_as_new\":10,\"show_updated_label\":\"1\",\"num_day_to_show_as_updated\":10,\"show_empty_field\":\"0\",\"submit_form_show_tab_related\":\"0\",\"submit_form_show_tab_plugin_params\":\"0\",\"submit_form_show_tab_publishing\":\"0\",\"submit_form_show_tab_style\":\"0\",\"submit_form_show_tab_meta_data\":\"0\",\"submit_form_show_tab_params\":\"0\",\"submit_form_show_tab_permissions\":\"0\",\"show_header_sort\":\"1\",\"listing_pagination\":10,\"show_pagination\":\"1\",\"default_view_mode\":\"2\",\"allow_user_select_view_mode\":\"1\",\"listing_columns\":2,\"listing_column_class\":\"\",\"listing_row_class\":\"\",\"show_compare_btn_in_listview\":\"0\",\"list_alpha\":\"0-9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z\",\"top_listings_limit\":100,\"show_submit_listing_btn_in_category\":\"1\",\"category_show_description\":\"1\",\"category_desc_limit\":0,\"category_show_image\":\"1\",\"category_image_width\":200,\"category_image_height\":200,\"related_category_ordering\":\"crel.ordering\",\"related_category_direction\":\"ASC\",\"show_empty_related_category\":\"1\",\"show_total_subcats_of_relcat\":\"0\",\"show_total_listings_of_relcat\":\"0\",\"related_category_show_introtext\":\"1\",\"related_category_introtext_character_limit\":500,\"related_category_show_intro_image\":\"1\",\"related_category_intro_image_width\":200,\"related_category_intro_image_height\":200,\"related_category_columns\":2,\"related_category_column_class\":\"\",\"related_category_row_class\":\"\",\"subcategory_ordering\":\"title\",\"subcategory_direction\":\"ASC\",\"show_empty_subcategory\":\"1\",\"show_total_subcats_of_subcat\":\"0\",\"show_total_listings_of_subcat\":\"0\",\"subcategory_show_introtext\":\"1\",\"subcategory_introtext_character_limit\":500,\"subcategory_show_intro_image\":\"1\",\"subcategory_intro_image_width\":200,\"subcategory_intro_image_height\":200,\"subcategory_columns\":2,\"subcategory_column_class\":\"\",\"subcategory_row_class\":\"\",\"display_params\":{\"listing\":{\"show_comment\":\"1\",\"fields\":{\"title\":{\"details_view\":\"1\"},\"created\":{\"details_view\":\"1\"},\"author\":{\"details_view\":\"1\"},\"cat_id\":{\"details_view\":\"1\"},\"rating\":{\"details_view\":\"1\"}}},\"cat\":{\"show_description\":\"1\"}},\"seo_replace_title_option\":\"replace\",\"seo_replace_description_option\":\"replace\",\"seo_replace_keywords_option\":\"replace\",\"seo_title_length\":64,\"seo_description_length\":160,\"seo_keywords_length\":160,\"seo_user_title\":\"{user_name}\",\"seo_user_description\":\"{meta_description}\",\"seo_user_keywords\":\"{meta_keywords}\",\"seo_collection_title\":\"{collection_title}\",\"seo_collection_description\":\"{meta_description}\",\"seo_collection_keywords\":\"{meta_keywords}\",\"seo_listing_title\":\"{listing_title}\",\"seo_listing_description\":\"{meta_description}\",\"seo_listing_keywords\":\"{meta_keywords}\",\"seo_category_title\":\"{cat_title}\",\"seo_category_description\":\"{meta_description}\",\"seo_category_keywords\":\"{meta_keywords}\",\"seo_field_title\":\"{field_title}\",\"seo_field_description\":\"{meta_description}\",\"seo_field_keywords\":\"{meta_keywords}\",\"seo_tag_title\":\"{tag_title}\",\"seo_tag_description\":\"{meta_description}\",\"seo_tag_keywords\":\"{meta_keywords}\",\"sef_category_full_path\":\"0\",\"sef_listing_full_path\":\"0\",\"sef_categories\":\"categories\",\"sef_tree\":\"tree\",\"sef_featured\":\"featured\",\"sef_list_all\":\"list-all\",\"sef_list_alpha\":\"list-alpha\",\"sef_tags\":\"tags\",\"sef_tag\":\"tag\",\"sef_collections\":\"collections\",\"sef_collection\":\"collection\",\"sef_custom_list\":\"custom-list\",\"sef_advanced_search\":\"advsearch\",\"sef_search\":\"search\",\"sef_searchby\":\"searchby\",\"sef_guest_subscribe\":\"guest-subscribe\",\"sef_maintenance\":\"maintenance\",\"sef_listings\":\"modal-listings\",\"sef_contact\":\"contact\",\"sef_claim\":\"claim\",\"sef_compare\":\"compare\",\"sef_comment_tree\":\"comment-tree\",\"sef_top_comments\":\"top-comments\",\"sef_top_listings_latest\":\"latest-listings\",\"sef_top_listings_featured\":\"top-featured-listings\",\"sef_top_listings_recent_modified\":\"recent-modified-listings\",\"sef_top_listings_recent_updated\":\"recent-updated-listings\",\"sef_top_listings_popular\":\"popular-listings\",\"sef_top_listings_most_rated\":\"most-rated-listings\",\"sef_top_listings_top_rated\":\"top-rated-listings\",\"sef_top_listings_latest_rated\":\"latest-rated-listings\",\"sef_top_listings_most_commented\":\"most-commented-listings\",\"sef_top_listings_latest_commented\":\"latest-commented-listings\",\"sef_top_listings_recently_viewed\":\"recent-viewed-listings\",\"sef_top_listings_alpha_ordered\":\"alpha-ordered-listings\",\"sef_top_listings_random\":\"random-listings\",\"sef_top_listings_random_fast\":\"random-fast-listings\",\"sef_top_listings_random_featured\":\"random-featured-listings\",\"sef_add\":\"add\",\"sef_edit\":\"edit\",\"sef_delete\":\"delete\",\"sef_publish\":\"publish\",\"sef_unpublish\":\"unpublish\",\"sef_checkin\":\"checkin\",\"sef_approve\":\"approve\",\"sef_subscribe\":\"subscribe\",\"sef_unsubscribe\":\"unsubscribe\",\"sef_activate_subscription\":\"activate-subscription\",\"sef_print\":\"print\",\"sef_download_email_attachment\":\"download-attachment\",\"sef_remove_compare\":\"remove-compare\",\"sef_remove_compare_all\":\"all\",\"sef_redirect_url\":\"redirect-url\",\"sef_dashboard\":\"dashboard\",\"sef_profile\":\"profile\",\"sef_user_listings\":\"listings\",\"sef_published\":\"published\",\"sef_unpublished\":\"unpublished\",\"sef_pending\":\"pending\",\"sef_user_subscriptions\":\"subscriptions\",\"sef_user_comments\":\"comments\",\"sef_mod_listings\":\"mod-listings\",\"sef_mod_comments\":\"mod-comments\",\"sef_mod_comment\":\"mod-comment\",\"sef_mod_pending_listings\":\"mod-pending-listings\",\"sef_mod_pending_listing\":\"mod-pending-listing\",\"sef_mod_pending_comments\":\"mod-pending-comments\",\"sef_mod_pending_comment\":\"mod-pending-comment\",\"sef_mod_permissions\":\"mod-permissions\",\"sef_mod_permission\":\"mod-permission\",\"sef_root_cat\":\"root\",\"sef_rss\":\"rss\",\"sef_report\":\"report\",\"sef_layout\":\"layout\",\"sef_page\":\"page-\",\"sef_all\":\"all\",\"sef_new_listing\":\"new-listing\",\"sef_comment\":\"comment\",\"sef_component\":\"component\",\"sef_file\":\"file\",\"sef_raw_data\":\"raw-data\",\"sef_space\":\"-\",\"rss_display_icon\":\"1\",\"rss_number_items_in_feed\":10,\"rss_show_thumbnail\":\"1\",\"rss_thumbnail_source\":\"image\",\"rss_thumbnail_alignment\":\"left\",\"rss_email\":\"none\",\"load_jquery\":\"2\",\"load_jquery_ui\":\"2\"}','','','','Root','','','');

/*Table structure for table `#__judirectory_categories_relations` */

DROP TABLE IF EXISTS `#__judirectory_categories_relations`;

CREATE TABLE `#__judirectory_categories_relations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id_related` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_catid_relcatid` (`cat_id`,`cat_id_related`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_categories_relations` */

/*Table structure for table `#__judirectory_claims` */

DROP TABLE IF EXISTS `#__judirectory_claims`;

CREATE TABLE `#__judirectory_claims` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `admin_notes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_listingid` (`listing_id`),
  KEY `idx_userid` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_claims` */

/*Table structure for table `#__judirectory_collections` */

DROP TABLE IF EXISTS `#__judirectory_collections`;

CREATE TABLE `#__judirectory_collections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `icon` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `total_votes` int(11) unsigned NOT NULL DEFAULT '0',
  `helpful_votes` int(11) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `private` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `global` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `metatitle` varchar(255) NOT NULL,
  `metakeyword` varchar(1024) NOT NULL,
  `metadescription` varchar(1024) NOT NULL,
  `metadata` varchar(2048) NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_global` (`global`),
  KEY `idx_featured` (`featured`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_alias` (`alias`),
  KEY `idx_private` (`private`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_collections` */

/*Table structure for table `#__judirectory_collections_items` */

DROP TABLE IF EXISTS `#__judirectory_collections_items`;

CREATE TABLE `#__judirectory_collections_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `collection_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_collectionid_listingid` (`collection_id`,`listing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_collections_items` */

/*Table structure for table `#__judirectory_comments` */

DROP TABLE IF EXISTS `#__judirectory_comments`;

CREATE TABLE `#__judirectory_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `guest_name` varchar(255) NOT NULL,
  `guest_email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `total_votes` int(11) unsigned NOT NULL DEFAULT '0',
  `helpful_votes` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `approved` tinyint(3) NOT NULL DEFAULT '0',
  `approved_by` int(11) unsigned NOT NULL DEFAULT '0',
  `approved_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `rating_id` int(11) unsigned NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `language` char(7) NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_lft_rgt` (`lft`,`rgt`),
  KEY `idx_listingid_published_approved` (`listing_id`,`published`,`approved`),
  KEY `idx_approved` (`approved`),
  KEY `idx_parentid_level` (`parent_id`,`level`),
  KEY `idx_top_comments` (`rating_id`,`published`,`approved`),
  KEY `idx_language_published_approved` (`language`,`published`,`approved`),
  KEY `idx_rgt` (`rgt`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_comments` */

insert  into `#__judirectory_comments`(`id`,`title`,`comment`,`user_id`,`guest_name`,`guest_email`,`website`,`total_votes`,`helpful_votes`,`created`,`approved`,`approved_by`,`approved_time`,`modified`,`modified_by`,`published`,`parent_id`,`lft`,`rgt`,`level`,`listing_id`,`rating_id`,`ip_address`,`language`,`checked_out`,`checked_out_time`) values (1,'Root','',0,'','','',0,0,'0000-00-00 00:00:00',1,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,1,0,0,1,0,0,0,'','',0,'0000-00-00 00:00:00');

/*Table structure for table `#__judirectory_criterias` */

DROP TABLE IF EXISTS `#__judirectory_criterias`;

CREATE TABLE `#__judirectory_criterias` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `tooltips` varchar(512) NOT NULL,
  `weights` int(11) unsigned NOT NULL DEFAULT '1',
  `required` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `group_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_groupid` (`group_id`),
  KEY `idx_published` (`published`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_criterias` */

/*Table structure for table `#__judirectory_criterias_groups` */

DROP TABLE IF EXISTS `#__judirectory_criterias_groups`;

CREATE TABLE `#__judirectory_criterias_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `params` text NOT NULL,
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `asset_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_published` (`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_criterias_groups` */

/*Table structure for table `#__judirectory_criterias_values` */

DROP TABLE IF EXISTS `#__judirectory_criterias_values`;

CREATE TABLE `#__judirectory_criterias_values` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rating_id` int(11) unsigned NOT NULL DEFAULT '0',
  `criteria_id` int(11) unsigned NOT NULL DEFAULT '0',
  `value` float(8,6) unsigned NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`id`),
  KEY `idx_ratingid_criteriaid` (`rating_id`,`criteria_id`),
  KEY `idx_criteriaid` (`criteria_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_criterias_values` */

/*Table structure for table `#__judirectory_custom_lists` */

DROP TABLE IF EXISTS `#__judirectory_custom_lists`;

CREATE TABLE `#__judirectory_custom_lists` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `query_type` tinyint(3) unsigned NOT NULL,
  `search_conditions` text NOT NULL,
  `custom_query` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) unsigned NOT NULL DEFAULT '1',
  `language` char(7) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` text NOT NULL,
  `metatitle` varchar(255) NOT NULL,
  `metadescription` varchar(1024) NOT NULL,
  `metakeyword` varchar(1024) NOT NULL,
  `metadata` varchar(2048) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`),
  KEY `idx_alias` (`alias`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_publishing` (`published`,`publish_up`,`publish_down`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_custom_lists` */

/*Table structure for table `#__judirectory_emails` */

DROP TABLE IF EXISTS `#__judirectory_emails`;

CREATE TABLE `#__judirectory_emails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `recipients` text NOT NULL,
  `cc` text NOT NULL,
  `bcc` text NOT NULL,
  `reply_to` text NOT NULL,
  `reply_to_name` text NOT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `body_html` mediumtext NOT NULL,
  `body_text` text NOT NULL,
  `mode` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `attachments` text NOT NULL,
  `event` varchar(64) NOT NULL,
  `language` char(7) NOT NULL,
  `use_mailq` tinyint(3) NOT NULL DEFAULT '-2',
  `priority` tinyint(2) unsigned NOT NULL DEFAULT '5',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_event` (`event`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_publishing` (`published`,`publish_up`,`publish_down`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_emails` */

/*Table structure for table `#__judirectory_emails_xref` */

DROP TABLE IF EXISTS `#__judirectory_emails_xref`;

CREATE TABLE `#__judirectory_emails_xref` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_emailid_catid` (`email_id`,`cat_id`),
  KEY `idx_catid` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_emails_xref` */

/*Table structure for table `#__judirectory_fields` */

DROP TABLE IF EXISTS `#__judirectory_fields`;

CREATE TABLE `#__judirectory_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL DEFAULT '0',
  `plugin_id` int(11) unsigned NOT NULL DEFAULT '0',
  `field_name` varchar(128) NOT NULL,
  `caption` varchar(255) NOT NULL DEFAULT '',
  `hide_caption` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hide_label` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `attributes` varchar(1024) NOT NULL,
  `predefined_values_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `predefined_values` text NOT NULL,
  `php_predefined_values` mediumtext NOT NULL,
  `prefix_text_mod` varchar(255) NOT NULL,
  `suffix_text_mod` varchar(255) NOT NULL,
  `prefix_text_display` varchar(255) NOT NULL,
  `suffix_text_display` varchar(255) NOT NULL,
  `prefix_suffix_wrapper` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `list_view` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `details_view` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `simple_search` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `advanced_search` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `filter_search` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `allow_priority` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `priority_direction` varchar(8) NOT NULL DEFAULT 'asc',
  `backend_list_view` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `backend_list_view_ordering` int(11) NOT NULL DEFAULT '0',
  `required` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  `params` text NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(11) unsigned NOT NULL DEFAULT '1',
  `asset_id` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `frontend_ordering` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `metatitle` varchar(255) NOT NULL,
  `metakeyword` varchar(1024) NOT NULL,
  `metadescription` varchar(1024) NOT NULL,
  `metadata` varchar(2048) NOT NULL,
  `ignored_options` varchar(1024) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_groupid` (`group_id`),
  KEY `idx_publishing` (`published`,`publish_up`,`publish_down`),
  KEY `idx_fieldname` (`field_name`),
  KEY `idx_alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_fields` */

insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (1,1,1,'id','Id',0,'id','','',1,'','','','','','',1,0,0,1,1,0,0,23,'asc',2,40,0,'*','null',0,'0000-00-00 00:00:00',1,903,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',1,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','required,published,publish_up,publish_down,frontend_ordering','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (2,1,2,'title','Title',0,'title','','',1,'','','','','','',1,1,1,1,1,1,1,3,'asc',2,1,1,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\",\"max_length_list_view\":\"0\",\"max_length_details_view\":\"0\"}',0,'0000-00-00 00:00:00',1,904,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',2,1,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','backend_list_view,required,published,publish_up,publish_down','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (3,1,3,'alias','Alias',0,'alias','','',1,'','','','','','',1,0,0,0,0,0,0,29,'asc',0,27,0,'*','{\"size\":\"30\",\"placeholder\":\"\",\"auto_suggest\":\"0\"}',0,'0000-00-00 00:00:00',1,905,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',3,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','required,published,publish_up,publish_down','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (4,1,4,'image','Image',0,'image','','',1,'','','','','','',1,1,1,0,0,0,0,18,'asc',0,14,0,'*','{\"list_view_set_icon_dimension\":\"1\",\"list_view_icon_width\":\"100\",\"list_view_icon_height\":\"100\",\"details_view_set_icon_dimension\":\"1\",\"details_view_icon_width\":\"100\",\"details_view_icon_height\":\"100\"}',0,'0000-00-00 00:00:00',1,906,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',4,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (5,1,5,'description','Description',0,'description','','',1,'','','','','','',1,1,1,0,1,0,0,30,'asc',0,34,1,'*','{\"width\":\"700\",\"height\":\"300\",\"cols\":\"70\",\"rows\":\"10\",\"use_editor_back_end\":\"1\",\"backend_editor\":\"tinymce\",\"use_editor_front_end\":\"1\",\"frontend_editor\":\"tinymce\",\"groups_can_use_frontend_editor\":[\"1\"],\"placeholder\":\"\",\"strip_tags_list_view\":\"1\",\"use_html_entities\":\"0\",\"strip_tags_details_view\":\"0\",\"strip_tags_before_save\":\"0\",\"allowable_tags\":\"u,b,i,a,ul,li,pre,blockquote,strong,em\",\"truncate\":\"1\",\"limit_char_in_list_view\":\"200\",\"show_introtext_in_details_view\":\"1\",\"auto_link\":\"1\",\"nofollow_link\":\"1\",\"trim_long_url\":\"0\",\"front_portion_url\":\"0\",\"back_portion_url\":\"0\",\"parse_plugin\":\"0\",\"nl2br_details_view\":\"0\",\"filter\":\"RAW\",\"show_readmore\":\"0\",\"readmore_text\":\"Read more...\",\"show_readmore_when\":\"1\",\"trigger_window_resize\":\"0\"}',0,'0000-00-00 00:00:00',1,907,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',5,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (6,1,6,'author','Author',0,'author','','',1,'','','','','','',1,0,1,0,1,0,0,17,'asc',0,28,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\"}',0,'0000-00-00 00:00:00',1,908,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',6,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (7,1,7,'email','Email',0,'email','','',1,'','','','','','',1,0,0,0,0,0,0,26,'asc',0,29,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\"}',0,'0000-00-00 00:00:00',1,909,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',7,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (8,1,8,'url','Url',0,'url','','',1,'','','','','','',1,0,1,0,0,0,0,24,'asc',0,22,0,'*','{\"link_text\":\"\",\"trim_long_url\":\"0\",\"front_portion_url\":\"0\",\"back_portion_url\":\"0\",\"strip_http\":\"0\",\"open_in\":\"_blank\",\"popup_width\":\"800\",\"popup_height\":\"500\",\"show_go_button\":\"1\",\"use_nofollow\":\"1\",\"regex\":\"\\/^(https?:\\\\\\/\\\\\\/)?([\\\\da-z\\\\.-]+)\\\\.([a-z\\\\.]{2,6})([\\\\\\/\\\\w \\\\.-]*)*\\\\\\/?$\\/\",\"invalid_message\":\"\",\"size\":\"32\",\"placeholder\":\"\",\"link_counter\":\"1\",\"show_link_counter_input\":\"0\",\"show_link_counter_output\":\"0\"}',0,'0000-00-00 00:00:00',1,910,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',8,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (9,1,9,'visits','Visits',0,'visits','','',1,'','','','','','',1,0,0,0,0,0,0,25,'asc',0,23,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\",\"is_numeric\":\"0\",\"digits_in_total\":\"11\",\"digits_after_decimal\":\"2\",\"dec_point\":\".\",\"use_thousands_sep\":\"0\",\"thousands_sep\":\",\"}',0,'0000-00-00 00:00:00',1,997,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',9,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (10,1,10,'telephone','Telephone',0,'telephone','','',1,'','','','','','',1,0,1,0,1,0,0,27,'asc',2,20,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\"}',0,'0000-00-00 00:00:00',1,434,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',10,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (11,1,11,'fax','Fax',0,'fax','','',1,'','','','','','',1,0,1,0,1,0,0,28,'asc',2,21,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\"}',0,'0000-00-00 00:00:00',1,437,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',11,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (12,1,12,'hits','Hits',0,'hits','','',1,'','','','','','',1,1,1,0,0,0,0,7,'asc',2,7,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\",\"is_numeric\":\"1\",\"digits_in_total\":\"11\",\"digits_after_decimal\":\"0\",\"dec_point\":\".\",\"use_thousands_sep\":\"0\",\"thousands_sep\":\",\"}',0,'0000-00-00 00:00:00',1,914,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',12,1,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (13,1,13,'price','Price',0,'price','','',1,'','','','$','','$',1,0,1,0,1,0,0,6,'asc',2,24,0,'*','{\"regex\":\"\\/^\\\\d+(\\\\.\\\\d+)?$\\/\",\"custom_regex\":\"\",\"invalid_message\":\"\",\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\",\"is_numeric\":\"1\",\"digits_in_total\":\"11\",\"digits_after_decimal\":\"2\",\"dec_point\":\".\",\"use_thousands_sep\":\"0\",\"thousands_sep\":\",\"}',0,'0000-00-00 00:00:00',1,602,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',13,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (14,1,14,'rating','Rating',0,'rating','','',1,'','','','','','',1,1,1,0,1,0,0,4,'asc',2,8,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\"}',0,'0000-00-00 00:00:00',1,917,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',14,1,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (15,1,15,'total_votes','Total votes',0,'total-votes','','',1,'','','','','','',1,0,0,0,0,0,0,8,'asc',0,13,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\"}',0,'0000-00-00 00:00:00',1,918,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',15,1,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (16,1,16,'created','Created',0,'created','','',1,'','','','','','',1,0,1,0,0,0,1,2,'desc',0,15,0,'*','{\"dateformat\":\"l, d F Y\",\"custom_dateformat\":\"\",\"filter\":\"USER_UTC\",\"size\":\"32\"}',0,'0000-00-00 00:00:00',1,919,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',16,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (17,1,17,'created_by','Created by',0,'created-by','','',1,'','','','','','',1,1,1,0,0,0,0,13,'asc',0,32,0,'*','null',0,'0000-00-00 00:00:00',1,920,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',17,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (18,1,18,'created_by_alias','Create by alias',0,'created-by-alias','','',1,'','','','','','',1,0,0,0,0,0,0,31,'asc',0,33,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\",\"tag_search\":\"0\"}',0,'0000-00-00 00:00:00',1,921,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',18,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (19,1,19,'modified','Modified',0,'modified','','',1,'','','','','','',1,0,0,0,0,0,0,12,'asc',0,30,0,'*','{\"dateformat\":\"l, d F Y\",\"custom_dateformat\":\"\",\"filter\":\"USER_UTC\",\"size\":\"32\"}',0,'0000-00-00 00:00:00',1,922,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',19,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (20,1,20,'modified_by','Modified by',0,'modified-by','','',1,'','','','','','',1,0,0,0,0,0,0,19,'asc',0,31,0,'*','null',0,'0000-00-00 00:00:00',1,923,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',20,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (21,1,21,'featured','Featured',0,'featured','','',1,'0','','','','','',1,1,1,0,1,0,1,1,'desc',2,3,0,'*','null',0,'0000-00-00 00:00:00',1,924,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',21,1,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (22,1,22,'published','Published',0,'published','','',1,'1','','','','','',1,0,0,0,0,0,0,34,'asc',2,4,0,'*','null',0,'0000-00-00 00:00:00',1,925,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',22,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (23,1,23,'publish_up','Publish up',0,'publish-up','','',1,'','','','','','',1,1,1,0,1,0,0,11,'asc',0,16,0,'*','{\"dateformat\":\"l, d F Y\",\"custom_dateformat\":\"\",\"filter\":\"USER_UTC\",\"size\":\"32\"}',0,'0000-00-00 00:00:00',1,926,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',23,1,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (24,1,24,'publish_down','Publish down',0,'publish-down','','',1,'','','','','','',1,0,0,0,0,0,0,20,'asc',0,19,0,'*','{\"dateformat\":\"l, d F Y\",\"custom_dateformat\":\"\",\"filter\":\"USER_UTC\",\"size\":\"32\"}',0,'0000-00-00 00:00:00',1,927,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',24,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (25,1,25,'updated','Updated',0,'updated','','',1,'','','','','','',1,1,1,0,1,0,0,5,'asc',1,5,0,'*','{\"dateformat\":\"l, d F Y\",\"custom_dateformat\":\"\",\"filter\":\"USER_UTC\",\"size\":\"32\"}',0,'0000-00-00 00:00:00',1,928,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',25,1,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (26,1,26,'approved','Approved',0,'approved','','',1,'1','','','','','',1,0,0,0,0,0,0,33,'asc',0,35,0,'*','null',0,'0000-00-00 00:00:00',3,929,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',26,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (27,1,27,'approved_by','Approved by',0,'approved-by','','',1,'','','','','','',1,0,0,0,0,0,0,21,'asc',0,17,0,'*','null',0,'0000-00-00 00:00:00',1,930,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',27,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (28,1,28,'approved_time','Approved time',0,'approved-time','','',1,'','','','','','',1,0,0,0,0,0,0,16,'asc',0,18,0,'*','{\"dateformat\":\"l, d F Y\",\"custom_dateformat\":\"\",\"filter\":\"USER_UTC\",\"size\":\"32\"}',0,'0000-00-00 00:00:00',1,931,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',28,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (29,1,29,'language','Language',0,'language','','',1,'*','','','','','',1,0,0,0,1,0,0,15,'asc',0,12,0,'*','null',0,'0000-00-00 00:00:00',1,932,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',29,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (30,1,30,'class_sfx','Class suffix',0,'class-suffix','','',1,'','','','','','',1,0,0,0,0,0,0,32,'asc',0,26,0,'*','{\"size\":\"32\",\"placeholder\":\"\",\"auto_suggest\":\"0\"}',0,'0000-00-00 00:00:00',1,934,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',30,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (31,1,31,'access','Access',0,'access','','',1,'1','','','','','',1,0,0,0,0,0,0,22,'asc',2,6,0,'*','null',0,'0000-00-00 00:00:00',1,935,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',31,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (32,1,32,'comments','Comments',0,'comments','','',1,'','','','','','',1,1,0,0,0,0,0,9,'asc',2,9,0,'*','null',0,'0000-00-00 00:00:00',1,936,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',32,1,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (33,1,33,'reports','Reports',0,'reports','','',1,'','','','','','',1,0,0,0,0,0,0,14,'asc',1,10,0,'*','null',0,'0000-00-00 00:00:00',1,937,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',33,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (34,1,34,'subscriptions','Subscriptions',0,'subscriptions','','',1,'','','','','','',1,0,0,0,0,0,0,10,'asc',1,11,0,'*','null',0,'0000-00-00 00:00:00',1,938,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',34,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (35,1,35,'cat_id','Categories',0,'categories','','',1,'','','','','','',1,1,1,0,1,0,0,35,'asc',1,2,1,'*','null',0,'0000-00-00 00:00:00',1,939,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',35,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','required,published,publish_up,publish_down,frontend_ordering','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (36,1,36,'tags','Tags',0,'tags','','',1,'','','','','','',1,1,1,0,1,0,0,36,'asc',0,25,0,'*','{\"tag_ordering\":\"t.title\",\"tag_direction\":\"ASC\"}',0,'0000-00-00 00:00:00',1,940,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',36,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (37,1,37,'gallery','Gallery',0,'gallery','','',1,'','','','','','',1,0,1,0,0,0,0,39,'asc',0,36,0,'*','{\"image_display_mode\":\"fancybox\"}',0,'0000-00-00 00:00:00',1,617,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',37,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (38,1,38,'locations','Locations',0,'locations','','',1,'','','','','','',1,0,1,0,1,0,0,37,'asc',0,37,0,'*','{\"search_operator\":\"0\"}',0,'0000-00-00 00:00:00',1,943,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',38,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (39,1,39,'addresses','Addresses',0,'addresses','','',1,'','','','','','',1,0,1,0,0,0,0,38,'asc',0,38,0,'*','{\"tag_search\":\"1\",\"ordering\":\"ordering\",\"ordering_direction\":\"asc\"}',0,'0000-00-00 00:00:00',1,944,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',39,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);
insert  into `#__judirectory_fields`(`id`,`group_id`,`plugin_id`,`field_name`,`caption`,`hide_caption`,`alias`,`description`,`attributes`,`predefined_values_type`,`predefined_values`,`php_predefined_values`,`prefix_text_mod`,`suffix_text_mod`,`prefix_text_display`,`suffix_text_display`,`prefix_suffix_wrapper`,`list_view`,`details_view`,`simple_search`,`advanced_search`,`filter_search`,`allow_priority`,`priority`,`priority_direction`,`backend_list_view`,`backend_list_view_ordering`,`required`,`language`,`params`,`checked_out`,`checked_out_time`,`access`,`asset_id`,`published`,`publish_up`,`publish_down`,`ordering`,`frontend_ordering`,`metatitle`,`metakeyword`,`metadescription`,`metadata`,`ignored_options`,`created`,`created_by`,`modified`,`modified_by`) values (40,1,51,'','Captcha',0,'captcha','','',1,'','','','','','',1,0,0,0,0,0,0,40,'asc',0,39,1,'*','{\"invalid_message\":\"\",\"size\":\"32\",\"placeholder\":\"\"}',0,'0000-00-00 00:00:00',1,941,1,'2015-01-12 00:00:00','0000-00-00 00:00:00',40,0,'','','','{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','list_view,details_view,allow_priority,simple_search,advanced_search,filter_search,required,backend_list_view,frontend_ordering','2015-01-12 00:00:00',241,'0000-00-00 00:00:00',0);

/*Table structure for table `#__judirectory_fields_groups` */

DROP TABLE IF EXISTS `#__judirectory_fields_groups`;

CREATE TABLE `#__judirectory_fields_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `asset_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `listing_metatitle` varchar(255) NOT NULL,
  `listing_metakeyword` varchar(1024) NOT NULL,
  `listing_metadescription` varchar(1024) NOT NULL,
  `field_ordering_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_published` (`published`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_fields_groups` */

insert  into `#__judirectory_fields_groups`(`id`,`name`,`description`,`access`,`params`,`asset_id`,`ordering`,`checked_out`,`checked_out_time`,`published`,`listing_metatitle`,`listing_metakeyword`,`listing_metadescription`,`field_ordering_type`,`created`,`created_by`,`modified`,`modified_by`) values (1,'Core Fields','<p>Core field group</p>',1,'',79,1,0,'0000-00-00 00:00:00',1,'','','',0,'2014-06-12 16:59:08',184,'0000-00-00 00:00:00',0);

/*Table structure for table `#__judirectory_fields_ordering` */

DROP TABLE IF EXISTS `#__judirectory_fields_ordering`;

CREATE TABLE `#__judirectory_fields_ordering` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL,
  `field_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_itemid_type_fieldid` (`item_id`,`type`,`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_fields_ordering` */

/*Table structure for table `#__judirectory_fields_values` */

DROP TABLE IF EXISTS `#__judirectory_fields_values`;

CREATE TABLE `#__judirectory_fields_values` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `field_id` int(11) unsigned NOT NULL DEFAULT '0',
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `value` mediumtext NOT NULL,
  `counter` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_fieldid_listingid` (`field_id`,`listing_id`),
  KEY `idx_listingid` (`listing_id`),
  KEY `idx_value` (`value`(8))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_fields_values` */

/*Table structure for table `#__judirectory_following` */

DROP TABLE IF EXISTS `#__judirectory_following`;

CREATE TABLE `#__judirectory_following` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL,
  `item_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip_address` varchar(45) NOT NULL,
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_itemid_type` (`item_id`,`type`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_following` */

/*Table structure for table `#__judirectory_images` */

DROP TABLE IF EXISTS `#__judirectory_images`;

CREATE TABLE `#__judirectory_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_listingid` (`listing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_images` */

/*Table structure for table `#__judirectory_listings` */

DROP TABLE IF EXISTS `#__judirectory_listings`;

CREATE TABLE `#__judirectory_listings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL,
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `author` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `url` varchar(512) NOT NULL,
  `visits` int(11) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `price` double(12,2) unsigned NOT NULL DEFAULT '0.00',
  `style_id` int(11) NOT NULL DEFAULT '-1',
  `rating` float(8,6) unsigned NOT NULL DEFAULT '0.000000',
  `total_votes` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `approved` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) unsigned NOT NULL DEFAULT '0',
  `approved_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL,
  `class_sfx` varchar(255) NOT NULL,
  `template_params` text NOT NULL,
  `plugin_params` text NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  `metatitle` varchar(255) NOT NULL,
  `metakeyword` varchar(1024) NOT NULL,
  `metadescription` varchar(1024) NOT NULL,
  `metadata` varchar(2048) NOT NULL,
  `notes` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rating` (`rating`),
  KEY `idx_title` (`title`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_alias` (`alias`),
  KEY `idx_language` (`language`),
  KEY `idx_featured` (`featured`,`approved`,`published`,`publish_up`,`publish_down`),
  KEY `idx_user_listings` (`created_by`,`approved`,`published`,`publish_up`,`publish_down`),
  KEY `idx_publishing` (`published`,`approved`,`publish_up`,`publish_down`),
  KEY `idx_approved` (`approved`),
  FULLTEXT KEY `idx_desc` (`introtext`,`fulltext`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_listings` */

/*Table structure for table `#__judirectory_listings_relations` */

DROP TABLE IF EXISTS `#__judirectory_listings_relations`;

CREATE TABLE `#__judirectory_listings_relations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `listing_id_related` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_listingid_rellistingid` (`listing_id`,`listing_id_related`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_listings_relations` */

/*Table structure for table `#__judirectory_listings_xref` */

DROP TABLE IF EXISTS `#__judirectory_listings_xref`;

CREATE TABLE `#__judirectory_listings_xref` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0',
  `main` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Main category',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_cat_id` (`cat_id`),
  KEY `idx_listingid_main` (`listing_id`,`main`),
  KEY `idx_listingid_catid` (`listing_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_listings_xref` */

/*Table structure for table `#__judirectory_locations` */

DROP TABLE IF EXISTS `#__judirectory_locations`;

CREATE TABLE `#__judirectory_locations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) unsigned NOT NULL,
  `address_id` int(11) NOT NULL DEFAULT '0',
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `address` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `postcode` varchar(255) NOT NULL,
  `marker_icon` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `published` tinyint(3) unsigned NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_listingid_published` (`listing_id`,`published`),
  KEY `idx_addressid` (`address_id`),
  KEY `idx_address` (`address`),
  KEY `idx_postcode` (`postcode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_locations` */

/*Table structure for table `#__judirectory_logs` */

DROP TABLE IF EXISTS `#__judirectory_logs`;

CREATE TABLE `#__judirectory_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `event` varchar(64) NOT NULL,
  `item_id` int(11) unsigned NOT NULL DEFAULT '0',
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `browser` varchar(255) NOT NULL,
  `platform` varchar(255) NOT NULL,
  `user_agent` varchar(512) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `value` float(16,6) NOT NULL DEFAULT '0.000000',
  `reference` text NOT NULL COMMENT 'Reference data, for example download multi files',
  PRIMARY KEY (`id`),
  KEY `idx_listingid_userid` (`listing_id`,`user_id`),
  KEY `idx_listingid_ip` (`listing_id`,`ip_address`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_ip_userid` (`ip_address`,`user_id`),
  KEY `idx_itemid` (`item_id`),
  KEY `idx_date` (`date`),
  KEY `idx_value` (`value`),
  KEY `idx_reference` (`reference`(8)),
  KEY `idx_event` (`event`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_logs` */

/*Table structure for table `#__judirectory_mailqs` */

DROP TABLE IF EXISTS `#__judirectory_mailqs`;

CREATE TABLE `#__judirectory_mailqs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email_id` int(11) unsigned NOT NULL DEFAULT '0',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `send_date` int(11) unsigned NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_attempt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_emailid` (`email_id`),
  KEY `idx_itemid` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_mailqs` */

/*Table structure for table `#__judirectory_moderators` */

DROP TABLE IF EXISTS `#__judirectory_moderators`;

CREATE TABLE `#__judirectory_moderators` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `listing_view` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `listing_view_unpublished` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `listing_create` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `listing_edit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `listing_edit_state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `listing_edit_own` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `listing_delete` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `listing_delete_own` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `listing_approve` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `comment_edit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `comment_edit_state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `comment_delete` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `comment_approve` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_userid_published` (`user_id`,`published`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_publishing` (`published`,`publish_up`,`publish_down`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_moderators` */

/*Table structure for table `#__judirectory_moderators_xref` */

DROP TABLE IF EXISTS `#__judirectory_moderators_xref`;

CREATE TABLE `#__judirectory_moderators_xref` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mod_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_modid_catid` (`mod_id`,`cat_id`),
  KEY `idx_catid` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_moderators_xref` */

/*Table structure for table `#__judirectory_plugins` */

DROP TABLE IF EXISTS `#__judirectory_plugins`;

CREATE TABLE `#__judirectory_plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL DEFAULT 'field',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `version` varchar(64) NOT NULL,
  `author` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `date` varchar(64) NOT NULL,
  `license` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `core` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `default` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` text NOT NULL,
  `extension_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_folder` (`folder`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_extension_id` (`extension_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_plugins` */

insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (1,'field','Core Id','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_id',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (2,'field','Core Title','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_title',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (3,'field','Core Alias','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_alias',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (4,'field','Core Image','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_image',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (5,'field','Core Description','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_description',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (6,'field','Core Author','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_author',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (7,'field','Core Email','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_email',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (8,'field','Core URL','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_url',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (9,'field','Core Visits','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_visits',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (10,'field','Core Telephone','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_telephone',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (11,'field','Core Fax','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_fax',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (12,'field','Core Hits','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_hits',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (13,'field','Core Price','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_price',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (14,'field','Core Rating','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_rating',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (15,'field','Core Total votes','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_total_votes',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (16,'field','Core Created','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_created',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (17,'field','Core Created by','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_created_by',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (18,'field','Core Created by alias','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_created_by_alias',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (19,'field','Core Modified','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_modified',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (20,'field','Core Modified by','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_modified_by',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (21,'field','Core Featured','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_featured',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (22,'field','Core Published','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_published',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (23,'field','Core Publish up','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_publish_up',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (24,'field','Core Publish down','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_publish_down',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (25,'field','Core Updated','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_updated',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (26,'field','Core Approved','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_approved',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (27,'field','Core Approved by','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_approved_by',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (28,'field','Core Approved time','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_approved_time',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (29,'field','Core Language','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_language',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (30,'field','Core Class suffix','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_classsfx',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (31,'field','Core Access','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_access',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (32,'field','Core Comments','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_comments',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (33,'field','Core Reports','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_reports',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (34,'field','Core Subscriptions','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_subscriptions',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (35,'field','Core Categories','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_categories',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (36,'field','Core Tags','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_tags',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (37,'field','Core Gallery','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_gallery',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (38,'field','Core Locations','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_locations',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (39,'field','Core Addresses','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','core_addresses',1,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (40,'field','Text','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','text',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (41,'field','Link','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','link',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (42,'field','Date Time','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','datetime',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (43,'field','Textarea','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','textarea',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (44,'field','Radio','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','radio',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (45,'field','Checkboxes','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','checkboxes',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (46,'field','Dropdown List','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','dropdownlist',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (47,'field','Multiple Select','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','multipleselect',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (48,'field','Files','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','files',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (49,'field','Images','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','images',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (50,'field','Free Text','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','freetext',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (51,'field','Captcha','','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','captcha',0,1,0,'0000-00-00 00:00:00','',0);
insert  into `#__judirectory_plugins`(`id`,`type`,`title`,`description`,`version`,`author`,`email`,`website`,`date`,`license`,`folder`,`core`,`default`,`checked_out`,`checked_out_time`,`params`,`extension_id`) values (52,'template','Default','Default JUDirectory Template','1.0','JoomUltra','admin@joomultra.com','http://www.joomultra.com','18 July 2014','GNU/GPL','default',0,1,0,'0000-00-00 00:00:00','',0);

/*Table structure for table `#__judirectory_rating` */

DROP TABLE IF EXISTS `#__judirectory_rating`;

CREATE TABLE `#__judirectory_rating` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `score` float(8,6) unsigned NOT NULL DEFAULT '0.000000',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_listingid_userid` (`listing_id`,`user_id`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_score` (`score`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_rating` */

/*Table structure for table `#__judirectory_reports` */

DROP TABLE IF EXISTS `#__judirectory_reports`;

CREATE TABLE `#__judirectory_reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `report` text NOT NULL,
  `type` varchar(32) NOT NULL,
  `item_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `guest_name` varchar(255) NOT NULL,
  `guest_email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `read` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `admin_notes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_itemid_type` (`item_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_reports` */

/*Table structure for table `#__judirectory_subscriptions` */

DROP TABLE IF EXISTS `#__judirectory_subscriptions`;

CREATE TABLE `#__judirectory_subscriptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `item_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_itemid_type_published` (`item_id`,`type`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_subscriptions` */

/*Table structure for table `#__judirectory_tags` */

DROP TABLE IF EXISTS `#__judirectory_tags`;

CREATE TABLE `#__judirectory_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `access` int(10) unsigned NOT NULL,
  `language` char(7) NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `metatitle` varchar(255) NOT NULL,
  `metakeyword` varchar(1024) NOT NULL,
  `metadescription` varchar(1024) NOT NULL,
  `metadata` varchar(2048) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_alias` (`alias`),
  KEY `idx_publishing` (`published`,`publish_up`,`publish_down`),
  KEY `idx_access` (`access`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_tags` */

/*Table structure for table `#__judirectory_tags_xref` */

DROP TABLE IF EXISTS `#__judirectory_tags_xref`;

CREATE TABLE `#__judirectory_tags_xref` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) unsigned NOT NULL DEFAULT '0',
  `listing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_tagid_listingid` (`tag_id`,`listing_id`),
  KEY `idx_listingid` (`listing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_tags_xref` */

/*Table structure for table `#__judirectory_template_styles` */

DROP TABLE IF EXISTS `#__judirectory_template_styles`;

CREATE TABLE `#__judirectory_template_styles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `template_id` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `home` char(7) NOT NULL DEFAULT '0',
  `default` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_templateid` (`template_id`),
  KEY `idx_parentid` (`parent_id`),
  KEY `idx_home` (`home`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_template_styles` */

insert  into `#__judirectory_template_styles`(`id`,`title`,`template_id`,`parent_id`,`lft`,`rgt`,`level`,`home`,`default`,`checked_out`,`checked_out_time`,`created`,`created_by`,`modified`,`modified_by`,`params`) values (1,'Root',1,0,0,3,0,'0',1,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0,'');
insert  into `#__judirectory_template_styles`(`id`,`title`,`template_id`,`parent_id`,`lft`,`rgt`,`level`,`home`,`default`,`checked_out`,`checked_out_time`,`created`,`created_by`,`modified`,`modified_by`,`params`) values (2,'Default',2,1,1,2,1,'1',1,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0,'');

/*Table structure for table `#__judirectory_templates` */

DROP TABLE IF EXISTS `#__judirectory_templates`;

CREATE TABLE `#__judirectory_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_lft_rgt` (`lft`,`rgt`),
  KEY `idx_parentid` (`parent_id`),
  KEY `idx_pluginid` (`plugin_id`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_templates` */

insert  into `#__judirectory_templates`(`id`,`plugin_id`,`parent_id`,`lft`,`rgt`,`level`,`checked_out`,`checked_out_time`) values (1,0,0,0,3,0,0,'0000-00-00 00:00:00');
insert  into `#__judirectory_templates`(`id`,`plugin_id`,`parent_id`,`lft`,`rgt`,`level`,`checked_out`,`checked_out_time`) values (2,52,1,1,2,1,0,'0000-00-00 00:00:00');

/*Table structure for table `#__judirectory_users` */

DROP TABLE IF EXISTS `#__judirectory_users`;

CREATE TABLE `#__judirectory_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `avatar` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `homepage` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `metatitle` varchar(255) NOT NULL,
  `metakeyword` varchar(1024) NOT NULL,
  `metadescription` varchar(1024) NOT NULL,
  `metadata` varchar(2048) NOT NULL,
  `notes` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_featured` (`featured`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__judirectory_users` */