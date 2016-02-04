<?php
/**
 * Kunena SEF extension for ARTIO JoomSEF
 * 
 * @package   JoomSEF
 * @author    ARTIO s.r.o., http://www.artio.net
 * @copyright Copyright (C) 2014 ARTIO s.r.o. 
 * @license   GNU/GPLv3 http://www.artio.net/license/gnu-general-public-license
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class SefExt_com_kunena extends SefExt
{

    static $kunenaprefix = null;
    static $tableMessages = null;
    static $tableCategories = null;
    static $tableAnnouncement = null;
    static $is16 = false;
    static $is20 = false;
    static $fieldCatParent = null;
    static $kunenaConfig = null;
    static $hasAlias = false;
    
    function SefExt_com_kunena()
    {
        if (is_null(self::$kunenaprefix)) {
            // Check Kunena component version
            $db =& JFactory::getDbo();
            $query = "SELECT `manifest_cache` FROM `#__extensions` WHERE `type` = 'component' AND `element` = 'com_kunena'";
            $db->setQuery($query);
            $data = $db->loadResult();
            
            if (is_null($data)) {
                // Version < 1.6 (used manifest.xml)
                $ver = '1.5';
            }
            else {
                // Check version
                $data = json_decode($data);
                
                if (isset($data->version)) {
                    $ver = $data->version;
                }
                else {
                    $ver = '1.5';
                }
            }

            if (version_compare($ver, '1.6.0', '<')) {
                // Before 1.6
                self::$kunenaprefix = 'fb';
                self::$fieldCatParent = 'parent';
            }
            else {
                // 1.6 or higher
                self::$is16 = true;
                self::$kunenaprefix = 'kunena';
                
                if (version_compare($ver, '2.0.0', '<')) {
                    // Lower than 2.0
                    self::$fieldCatParent = 'parent';
                }
                else {
                    // 2.0 or higher
                    self::$is20 = true;
                    self::$fieldCatParent = 'parent_id';
                    
                    if (version_compare($ver, '2.0.2', '>=')) {
                        // 2.0.2 or higher
                        self::$hasAlias = true;
                    }
                }
            }
            
            self::$tableMessages = '`#__'.self::$kunenaprefix.'_messages`';
            self::$tableCategories = '`#__'.self::$kunenaprefix.'_categories`';
            self::$tableAnnouncement = '`#__'.self::$kunenaprefix.'_announcement`';
            
            // Get Kunena configuration
            global $fbConfig;
            
            if (is_object($fbConfig)) {
                self::$kunenaConfig = $fbConfig;
            }
            else {
                $file = JPATH_ROOT.'/administrator/components/com_kunena/libraries/config.php';
                $file2 = JPATH_ROOT.'/components/com_kunena/lib/kunena.config.class.php';
                $file3 = JPATH_ROOT.'/libraries/kunena/config.php';
                if (file_exists($file3)) {
                    jimport('kunena.config');
                    self::$kunenaConfig = KunenaConfig::getInstance();
                }
                else if (file_exists($file)) {
                    require_once($file);
                    self::$kunenaConfig = KunenaConfig::getInstance();
                }
                else if (file_exists($file2)) {
                    require_once($file2);
                    self::$kunenaConfig = new CKunenaConfig();
                    self::$kunenaConfig->load();
                }
                
                $fbConfig = self::$kunenaConfig;
            }
        }
        
        // call parent constructor
        parent::__construct();
    }
    
    function getNonSefVars(&$uri)
    {
        $this->_createNonSefVars($uri);
        
        return array($this->nonSefVars, $this->ignoreVars);
    }
    
    function _createNonSefVars(&$uri)
    {
        if (isset($this->nonSefVars) && isset($this->ignoreVars))
            return;
            
        $this->params = SEFTools::getExtParams('com_kunena');
        
        $this->nonSefVars = array();
        $this->ignoreVars = array();
        
        // Set non-sef vars according to settings
        $doOptimize = ($uri->getVar('func') != 'announcement') &&
        (in_array($uri->getVar('do'), array('reply', 'quote', 'delete', 'edit', 'move', 'sticky', 'lock', 'deletethread', 'favorite', 'unsubscribe', 'moderatethread', 'moderate'))
        || in_array($uri->getVar('func'), array('report', 'thankyou')));
        
        if ($this->params->get('idExclude', true) && $doOptimize) {
            if (!is_null($uri->getVar('id')))
                $this->nonSefVars['id'] = $uri->getVar('id');
            if (!is_null($uri->getVar('replyto')))
                $this->nonSefVars['replyto'] = $uri->getVar('replyto');
            if (!is_null($uri->getVar('msg_id')))
                $this->nonSefVars['msg_id'] = $uri->getVar('msg_id');
        }
        
        if (!is_null($uri->getVar('mesid')))
            $this->nonSefVars['mesid'] = $uri->getVar('mesid');
            
        // Always set PID as non-SEF for Thank you and Karma functionality
        if (!is_null($uri->getVar('pid')))
            $this->nonSefVars['pid'] = $uri->getVar('pid');
        if ($this->params->get('optimize', true) && $doOptimize) {
            if (!is_null($uri->getVar('fb_thread')))
                $this->nonSefVars['fb_thread'] = $uri->getVar('fb_thread');
            if (!is_null($uri->getVar('name')))
                $this->nonSefVars['name'] = $uri->getVar('name');
        }

        if ($this->params->get('doExclude', true)) {        	        
        	if (!is_null($uri->getVar('do')))
                $this->nonSefVars['do'] = $uri->getVar('do');
        } 

        // Non-SEF pagination for search
        $func = $uri->getVar('view');
        if (is_null($func)) {
            $func = $uri->getVar('func');
            if (is_null($func)) {
                $func = $uri->getVar('task');
            }
        }
        if (in_array($func, array('search', 'advsearch'))) {
            if (!is_null($uri->getVar('limitstart')))
                $this->ignoreVars['limitstart'] = $uri->getVar('limitstart');
            if (!is_null($uri->getVar('limit')))
                $this->ignoreVars['limit'] = $uri->getVar('limit');
        }
        
        if (!is_null($uri->getVar('q')))
            $this->ignoreVars['q'] = $uri->getVar('q');
        if (!is_null($uri->getVar('beforeafter')))
            $this->ignoreVars['beforeafter'] = $uri->getVar('beforeafter');
        if (!is_null($uri->getVar('catids')))
            $this->ignoreVars['catids'] = $uri->getVar('catids');
        if (!is_null($uri->getVar('exactname')))
            $this->ignoreVars['exactname'] = $uri->getVar('exactname');
        if (!is_null($uri->getVar('order')))
            $this->ignoreVars['order'] = $uri->getVar('order');
        if (!is_null($uri->getVar('searchdate')))
            $this->ignoreVars['searchdate'] = $uri->getVar('searchdate');
        if (!is_null($uri->getVar('searchuser')))
            $this->ignoreVars['searchuser'] = $uri->getVar('searchuser');
        if (!is_null($uri->getVar('sortby')))
            $this->ignoreVars['sortby'] = $uri->getVar('sortby');
        if (!is_null($uri->getVar('titleonly')))
            $this->ignoreVars['titleonly'] = $uri->getVar('titleonly');
        if (!is_null($uri->getVar('childforums')))
            $this->ignoreVars['childforums'] = $uri->getVar('childforums');
        if (!is_null($uri->getVar('direction')))
            $this->ignoreVars['direction'] = $uri->getVar('direction');
        if (!is_null($uri->getVar('orderby')))
            $this->ignoreVars['orderby'] = $uri->getVar('orderby');
            
        // Find the token variable and remove it
        $vars = $uri->getQuery(true);
        $keys = array_keys($vars);
        foreach ($keys as $key) {
            if (strlen($key) == 32 && preg_match('/^[0-9a-f]+$/i', $key)) {
                $this->ignoreVars[$key] = $uri->getVar($key);
            }
        }
    }

    function beforeCreate(&$uri)
    {
        // Catid for karma task
        if (in_array($uri->getVar('task'), array('karmaup', 'karmadown'))) {
            $uri->delVar('catid');
        }
        
        // Remove default layout
        if ($uri->getVar('layout') == 'default') {
            // In 2.0 don't remove layout for topics view
            if (!self::$is20 || ($uri->getVar('view') != 'topics')) {
                $uri->delVar('layout');
            }
        }
        
        // Always add default mode
        if ($uri->getVar('view') == 'topics' && is_null($uri->getVar('mode'))) {
            $uri->setVar('mode', 'replies');
        }
        
        // Don't handle further for 2.0 and newer
        if (self::$is20) {
            if ($uri->getVar('view') == 'category' && !is_null($uri->getVar('id'))) {
                $uri->setVar('view', 'topic');
            }
            
            return;
        }
        
        // Older versions
        global $fbConfig, $mainframe;
        
        $database =& JFactory::getDBO();
        $params = SEFTools::getExtParams('com_kunena');
        
        // Fix func and view variables
        if (is_null($uri->getVar('func')) && !is_null($uri->getVar('view'))) {
            $uri->setVar('func', $uri->getVar('view'));
            $uri->delVar('view');
        }
        
        $vars = $uri->getQuery(true);
        extract($vars);

        if (isset($func) && ($func == 'showcat') && isset($id)) {
            $uri->delVar('id');
        }
        elseif (isset($id) && $params->get('smarturls', true) && (@$func != 'post' && @$do != 'edit')) {
            // Find the root msg
            $oldid = $id;
            
            $query = "SELECT `id` FROM ".self::$tableMessages." WHERE (`parent` = '0') AND (`thread` = (SELECT `thread` FROM ".self::$tableMessages." WHERE `id` = '$id'))";
            $database->setQuery($query);
            $id = $database->loadResult();
            
            $uri->setVar('id', $id);
            
            if ($oldid != $id) {
                // Get the message number
                $query = "SELECT COUNT(`id`) FROM ".self::$tableMessages." WHERE (`id` < '$oldid') AND (`thread` = (SELECT `thread` FROM ".self::$tableMessages." WHERE `id` = '$oldid')) ORDER BY `id`";
                $database->setQuery($query);
                $num = $database->loadResult();
                
                // Compute the limitstart
                $l = $fbConfig->messages_per_page;
                if( $num >= $l ) {
                    $ls = intval($num / $l) * $l;
                    $uri->setVar('limitstart', $ls);
                }
            }
        }
        
        if (is_null($uri->getVar('limitstart')) && !is_null($uri->getVar('limit')) ) {
            $uri->delVar('limit');
        }
        
        if (!is_null($uri->getVar('limitstart')) && is_null($uri->getVar('limit')) ) {
            if( !in_array($uri->getVar('func'), array('search', 'advsearch')) ) {
                $uri->setVar('limit', $fbConfig->messages_per_page);
            }
        }
        
        // Category listing
        if( ($uri->getVar('func') == 'showcat') && ($uri->getVar('catid') == '0') ) {
            $uri->delVar('catid');
            $uri->setVar('func', 'listcat');
        }
        
        // Go variable
        if (!is_null($uri->getVar('Go'))) {
            $uri->delVar('Go');
        }
    }
    
    function GetCategories($id)
    {
        $db =& JFactory::getDBO();
        $sefConfig =& SEFConfig::getConfig();
        
        $categories = array();
        
        // Check if we want our URLs translated
        if ($sefConfig->translateNames) {
            $jfTranslate = ', `id`';
        } else {
            $jfTranslate = '';
        }
        
        $aliasField = '';
        if (self::$hasAlias) {
            $aliasField = ', `alias`';
        }
        
        while ($id > 0) {
            $db->setQuery("SELECT `name`{$aliasField}, `".self::$fieldCatParent."` AS `parent`$jfTranslate FROM ".self::$tableCategories." WHERE `id` = '{$id}'");
            $row = $db->loadObject();
            
            if (is_null($row)) {
                return null;
            }
            
            $row->id = $id;
            $name = $this->BuildCategoryName($row);
            array_unshift($categories, $name);
            
            $id = $row->parent;
        }
        
        return $categories;
    }

    function GetMessageTitle($id)
    {
        $db =& JFactory::getDBO();
        $sefConfig =& SEFConfig::getConfig();
        
        // Check if we want our URLs translated
        if ($sefConfig->translateNames) {
            $jfTranslate = ', `id`';
        } else {
            $jfTranslate = '';
        }
        
        $db->setQuery("SELECT `subject`$jfTranslate FROM ".self::$tableMessages." WHERE `id` = '{$id}'");
        $row = $db->loadObject();
        
        if (is_null($row)) {
            return null;
        }
        
        $row->id = $id;
        $name = $this->BuildMessageName($row);
        
        return $name;
    }
    
    function GetTopicTitle($id)
    {
        $db =& JFactory::getDBO();
        $sefConfig =& SEFConfig::getConfig();
        
        // Check if we want our URLs translated
        if ($sefConfig->translateNames) {
            $jfTranslate = ', `id`';
        } else {
            $jfTranslate = '';
        }
        
        $db->setQuery("SELECT `subject`$jfTranslate FROM `#__kunena_topics` WHERE `id` = '{$id}'");
        $row = $db->loadObject();
        
        if (is_null($row)) {
            return null;
        }
        
        $row->id = $id;
        $name = $this->BuildMessageName($row);
        
        return $name;
    }
    
    function BuildAnnouncementName($row)
    {
        $name = array();
        $row->text = $this->params->get('announcenametext', 'Announcement');
        $this->AddNamePart($name, $row, $this->params->get('announcename1', 'none'));
        $this->AddNamePart($name, $row, $this->params->get('announcename2', 'title'));
        
        return implode('-', $name);
    }
    
    function BuildCategoryName($category)
    {
        $name = array();
        $category->text = $this->params->get('categorynametext', 'Forum');
        $c1 = $this->params->get('categoryname1', 'none');
        if ($c1 == 'title') {
            $c1 = 'name';
        }
        $this->AddNamePart($name, $category, $c1);
        $c2 = $this->params->get('categoryname2', 'name');
        if ($c2 == 'title') {
            $c2 = 'name';
        }
        $this->AddNamePart($name, $category, $c2);
        
        return implode('-', $name);
    }
    
    function BuildMessageName($message)
    {
        $name = array();
        $message->text = $this->params->get('topicnametext', 'Topic');
        $t1 = $this->params->get('topicname1', 'none');
        if ($t1 == 'title') {
            $t1 = 'subject';
        }
        $this->AddNamePart($name, $message, $t1);
        $t2 = $this->params->get('topicname2', 'subject');
        if ($t2 == 'title') {
            $t2 = 'subject';
        }
        $this->AddNamePart($name, $message, $t2);
        
        return implode('-', $name);
    }
    
    function AddNamePart(&$name, $object, $part)
    {
        if ($part == 'alias' && empty($object->alias)) {
            $part = 'name';
        }
        
        if (isset($object->$part)) {
            $name[] = $object->$part;
        }
    }

    function GetAnnouncementTitle($id)
    {
        $db =& JFactory::getDBO();
        $sefConfig =& SEFConfig::getConfig();
        
        // Check if we want our URLs translated
        if ($sefConfig->translateNames) {
            $jfTranslate = ', `id`';
        } else {
            $jfTranslate = '';
        }
        
        $db->setQuery("SELECT `title`$jfTranslate FROM ".self::$tableAnnouncement." WHERE `id` = '{$id}'");
        $row = $db->loadObject();
        
        if (is_null($row)) {
            return null;
        }
        
        $row->id = $id;
        $name = $this->BuildAnnouncementName($row);
        
        return $name;
    }

    function getDefaultForumMenuTitle()
    {
        $db =& JFactory::getDBO();
        $sefConfig =& SEFConfig::getConfig();
        
        $jfTranslate = $sefConfig->translateNames ? ', `id`' : '';
        $column = $sefConfig->useAlias ? 'alias' : 'title';
        
        $view = self::$is20 ? 'home' : 'entrypage';
        $sql = "SELECT `$column` AS `name`$jfTranslate FROM `#__menu` WHERE `link` LIKE '%option=com_kunena&view={$view}%' AND `published` > 0";        
        $db->setQuery($sql);

        return $db->loadResult();
    }
    
    
    function create(&$uri)
    {
        $vars = $uri->getQuery(true);
        extract($vars);
        $title=array();
        
        // Don't SEF if no variables
        if (self::$is20 && is_null($uri->getVar('view'))) {
            return $uri;
        }
        
        // JF translate extension.
        $sefConfig =& SEFConfig::getConfig();
        $database =& JFactory::getDBO();

        $jfTranslate = $sefConfig->translateNames ? ', `id`' : '';

        // load params
        $this->params = SEFTools::getExtParams('com_kunena');

        $catRewrite = true;
        $msgRewrite = true;
        $usrRewrite = true;
        
        /*if (isset($view) && ($view == 'category')) {
            $msgRewrite = false;
        }*/

        if ($msgRewrite || $catRewrite) {
            if ($catRewrite && !empty($catid)) {
                $categories = $this->GetCategories($catid);
                if (is_null($categories)) {
                    // Error, don't SEF
                    return $uri;
                }
            }
            if (isset($id)) $msgID = $id;
            elseif (isset($msg_id)) $msgID = $msg_id;
            elseif (isset($replyto)) $msgID = $replyto;
            elseif (isset($pid)) $msgID = $pid;
            else $msgID = null;
            if ($msgRewrite && !empty($msgID)) {
                if (isset($func) && ($func == 'announcement')) {
                    $msgTitle = $this->GetAnnouncementTitle($msgID);
                }
                else if (self::$is20) {
                    $msgTitle = $this->GetTopicTitle($msgID);
                }
                else {
                    $msgTitle = $this->GetMessageTitle($msgID);
                }
                if (is_null($msgTitle)) {
                    // Error, don't SEF
                    return $uri;
                }
            }
        }

        // Set non-sef vars according to settings
        $this->_createNonSefVars($uri);

        // this needs to follow previous that use do
        if ($this->params->get('doExclude', true)) {
            unset($do);
        }

        // get user ID
        if ($usrRewrite && isset($userid)) {                              
            if ($this->params->get('userIdInsteadOfLogin', false)) {
        	 	$usrTitle = 'user-'.$userid;        	 	
            }
            else {
	            $query = "SELECT `username` FROM `#__users` WHERE `id` = $userid";
	            $database->setQuery($query);
	            $usrTitle = $database->loadResult();
            }    
        }

        // use view if func not set
        if (!isset($func) && isset($view)) {
            $func = $view;
            unset($view);
        }
        
        // if task is not set, use do
        if (empty($task) && isset($func) && $func == 'post' && isset($do)) {
            $task = $do;
            unset($func); unset($do);
        }

        if (empty($task) && isset($func)) {
            $task = $func;
            unset($func);
        }

        // First subdir
        if (!empty($option) && !self::$is16) {
            $title[] = JoomSEF::_getMenuTitle($option, @$task, @$Itemid);
        }
        elseif (self::$is16) {
            $menuTitle = $this->getDefaultForumMenuTitle();
            if (!is_null($menuTitle)) {
                $title[] = $menuTitle;
            }
        }
       
        // Category
        if (isset($categories) && !empty($categories)) {
            $addCat = $this->params->get('categories', '2');
            if ($addCat == '2') {
                $title = array_merge($title, $categories);
            }
            else if ($addCat == '1' || empty($msgID)) {
                $title[] = $categories[count($categories) - 1];
            }
        }

        // Announcement
        if (@$task == 'announcement') {
            $title[] = JText::_('Announcements');
        }
        
        // Topic
        if (isset($msgTitle) && !empty($msgTitle) && isset($task) && ($task != 'showcat')) {
            //$title[] = (!isset($do) && !isset($func)) ? $msgTitle.$sefConfig->suffix : $msgTitle;
            $title[] = $msgTitle;
        }

        if (isset($task) && in_array($task, array('search', 'advsearch'))) {
            if ($task == 'advsearch') $title[] = JText::_('Advanced Search');
            else $title[] = JText::_($task);
            
            if (isset($limitstart)) unset($limitstart);
            if (isset($limit)) unset($limit);
            unset($task);
        }
        
        if (isset($task) && ($task == 'topics')) {
            $title[] = $task;
            unset($task);
        }
        
        // Cleanout some views
        if (in_array(@$view, array('entrypage', 'category', 'topic', 'home'))) {
            unset($view);
        }
        // Cleanout some funcs
        if (in_array(@$func, array('showcat', 'view', 'announcement', 'entrypage', 'rules', 'category', 'topic', 'home'))) {
            unset($func);
        }
        // Cleanout some tasks
        if (in_array(@$task, array('showcat', 'view', 'announcement', 'entrypage', 'category', 'topic', 'home'))) {
            unset($task);
        }
        
        // View
        if (isset($view)) {
            $title[] = $view;
        }
        
        // JSON
        if (isset($task) && ($task == 'json')) {
            $title[] = $task;
            unset($task);
        }
        
        // Action
        if (isset($action)) {
            $title[] = $action;
            unset($action);
        }
        
        // User
        if (@$task == 'user') {
            if (empty($Itemid)) {
                $title[] = 'user';
            }
            else {
                $app = JFactory::getApplication();
                $menu = $app->getMenu('site');
                $item = $menu->getItem($Itemid);
                if (!is_object($item) || !isset($item->query['view']) || ($item->query['view'] != 'user')) {
                    $title[] = 'user';
                }
                else {
                    $title[] = 'profile';
                }
            }
            $task = null;
        }
        
        if (isset($usrTitle) && !empty($usrTitle)) {
            if (@$task == 'fbprofile') {
                $title[] = 'users';
                $task = null;
            }
            elseif (@$task == 'profile') {
                $title[] = 'profile';
                $task = null;
            }
            elseif (@$task == 'showprf') {
                $task = null;
            }
            $title[] = $usrTitle;
        }
        
        // Misc
        if (@$task == 'misc' && !empty($Itemid)) {
            // Use correct menu title
            $title[] = JoomSEF::_getMenuTitle(null, null, $Itemid);
            $task = null;
        }
        
        // Layout
        if (isset($layout)) {
            switch($layout) {
                case 'reply':
                    if (isset($quote) && $quote) {
                        $layout = 'quote';
                    }
                    $title[] = $layout;
                    break;
                    
                case 'list':
                    if ($uri->getVar('view') == 'category') {
                        $layout = 'index';
                        $title[] = $layout;
                    }
                    else if ($uri->getVar('view') == 'user') {
                        $title[] = $layout;
                    }
                    break;
                    
                case 'default':
                    // Don't add
                    break;
                    
                default:
                    $title[] = $layout;
                    break;
            }
            
            unset($layout);
        }
        
        // Gallery
        if (isset($gallery)) {
            $title[] = $gallery;
        }
        
        // Func and do
        if (isset($do) || isset($func)) {
            if (isset($func)) {
                if ($func == 'search') $oper[] = 'do';
                $oper[] = $func;            
            }
            
            if (isset($do))   $oper[] = $do;
            $title[] = join('-', $oper).$sefConfig->suffix;
        }
        
        // Fix some task texts
        if (isset($task)) {
            $task = str_replace(array('listcat', 'userlist'), array('categories', 'users'), $task);
        }

        // Mode
        if (isset($mode) && ($mode == 'noreplies')) {
            $title[] = $mode;
        }
        
        // Feeds
        if (isset($format)) {
            switch ($format) {
                case 'feed':
                    if (!isset($type)) {
                        $type = 'rss';
                    }
                    
                    $title[] = $type;
                    break;
                
                case 'raw':
                    $title[] = $format;
                    break;
            }
        }

        // Page number
        if (isset($limitstart)) {
            // Limit should be set due to beforeCreate function, but to be sure
            if (!isset($limit) ) {
                if (in_array($uri->getVar('view'), array('category', 'topics')) && isset(self::$kunenaConfig->threads_per_page)) {
                    $limit = self::$kunenaConfig->threads_per_page;
                }
                elseif ($uri->getVar('view') == 'user') {
                    $limit = self::$kunenaConfig->userlist_rows;
                }
                else {
                    $limit = self::$kunenaConfig->messages_per_page;
                }
            }
            
            $pageNum = intval($limitstart / $limit) + 1;
            $pagetext = strval($pageNum);
            if (($cnfPageText = $sefConfig->getPageText())) {
                $pagetext = str_replace('%s', $pageNum, $cnfPageText);
            }
            $title = array_merge($title, explode('/', $pagetext));
            //$title[] = $pagetext;
        }

        // Selection
        if (!empty($sel)) {
            if (isset($task) && ($task == 'latest')) {
                $num = ($sel >= 24) ? $sel / 24 : $sel;
                $title[] = $task . '-' . $num;
                unset($task);
            }
            else {
                $title[] = $sel;
            }
        }

        if (isset($page) && $page > 1) {
            $pagetext = strval($page);
            if ($cnfPageText = $sefConfig->getPageText()) {
                $pagetext = str_replace('%s', $page, $cnfPageText);
            }            
            $title[] = $pagetext;
        }
        
        // Task
        if (isset($task)) {
            $title[] = $task;
            unset($task);
        }
        
        $newUri = $uri;
        if (count($title) > 0) {
            $newUri = JoomSEF::_sefGetLocation($uri, $title, null, @$limit, @$limitstart, @$lang, $this->nonSefVars, $this->ignoreVars, null, null, true);
        }
		
        return $newUri;
    }

}
?>