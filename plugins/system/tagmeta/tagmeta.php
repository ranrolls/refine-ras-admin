<?php
/**
 * Tag Meta Community plugin for Joomla
 *
 * @author selfget.com (info@selfget.com)
 * @package TagMeta
 * @copyright Copyright 2009 - 2013
 * @license GNU Public License
 * @link http://www.selfget.com
 * @version 1.7.2
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

defined("WORD_COUNT_MASK") or define("WORD_COUNT_MASK", "/\p{L}[\p{L}\p{N}\p{Mn}\p{Pd}'\x{2019}]*/u");
defined("PLACEHOLDERS_MATCH_MASK") or define("PLACEHOLDERS_MATCH_MASK", '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/');
defined("PLACEHOLDERS_MATCH_ALL_MASK") or define("PLACEHOLDERS_MATCH_ALL_MASK", '/\$\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/U');

class plgSystemTagMeta extends JPlugin
{
  /**
  *
  * @var boolean
  * @access  private
  */
  private $_clean_generator = false;

  public function __construct(& $subject, $config)
  {
    parent::__construct($subject, $config);
    $this->loadLanguage();
  }

  /**
   *
   * Build and return the (called) prefix (e.g. http://www.youdomain.com) from the current server variables
   *
   * We say 'called' 'cause we use HTTP_HOST (taken from client header) and not SERVER_NAME (taken from server config)
   *
   */
  private static function getPrefix()
  {
    if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
      $https = 's://';
    } else {
      $https = '://';
    }
    return 'http' . $https . $_SERVER['HTTP_HOST'];
  }

  /**
   *
   * Build and return the (called) base path for site (e.g. http://www.youdomain.com/path/to/site)
   *
   * @param  boolean  If true returns only the path part (e.g. /path/to/site)
   *
   */
  private static function getBase($pathonly = false)
  {
    if (strpos(php_sapi_name(), 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI'])) {
      // PHP-CGI on Apache with "cgi.fix_pathinfo = 0"

      // We use PHP_SELF
      if (!empty($_SERVER["PATH_INFO"])) {
        $p = strrpos($_SERVER["PHP_SELF"], $_SERVER["PATH_INFO"]);
        if ($p !== false) { $s = substr($_SERVER["PHP_SELF"], 0, $p); }
      } else {
        $p = $_SERVER["PHP_SELF"];
      }
      $base_path =  rtrim(dirname(str_replace(array('"', '<', '>', "'"), '', $p)), '/\\');
      // Check if base path was correctly detected, or use another method
      /*
         On some Apache servers (mainly using cgi-fcgi) it happens that the base path is not correctly detected.
         For URLs like http://www.site.com/index.php/content/view/123/5 the server returns a wrong PHP_SELF variable.

         WRONG:
         [REQUEST_URI] => /index.php/content/view/123/5
         [PHP_SELF] => /content/view/123/5

         CORRECT:
         [REQUEST_URI] => /index.php/content/view/123/5
         [PHP_SELF] => /index.php/content/view/123/5

         And this lead to a wrong result for JURI::base function.

         WRONG:
         JURI::base(true) => /content/view/123
         JURI::base(false) => http://www.site.com/content/view/123/

         CORRECT:
         getBase(true) =>
         getBase(false):http://www.site.com/
      */
      if (strlen($base_path) > 0) {
        if (strpos($_SERVER['REQUEST_URI'], $base_path) !== 0) {
          $base_path = trim(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
        }
      }
    } else {
      // We use SCRIPT_NAME
      $base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    return $pathonly === false ? self::getPrefix() . $base_path . '/' : $base_path;
  }

  /**
   *
   * Build and return the REQUEST_URI (e.g. /site/index.php?id=1&page=3)
   *
   */
  private static function getRequestURI($redirect_mode = 0)
  {
    if ( ($redirect_mode === 1) && ( (isset($_SERVER['REDIRECT_URL'])) || (isset($_SERVER['HTTP_X_REWRITE_URL'])) ) ) {
      $uri = (isset($_SERVER['HTTP_X_REWRITE_URL'])) ? $_SERVER['HTTP_X_REWRITE_URL'] : $_SERVER['REDIRECT_URL'];
    } else {
      $uri = $_SERVER['REQUEST_URI'];
    }
    return $uri;
  }

  /**
   *
   * Build and return the (called) {siteurl} macro value
   *
   */
  private static function getSiteURL()
  {
    $siteurl = str_replace( 'https://', '', self::getBase() );
    return rtrim(str_replace('http://', '', $siteurl), '/');
  }

  /**
   *
   * Build and return the (called) full URL (e.g. http://www.youdomain.com/site/index.php?id=12) from the current server variables
   *
   */
  private static function getURL($redirect_mode = 0)
  {
    return self::getPrefix() . self::getRequestURI($redirect_mode);
  }

  /**
   *
   * Return the host name from the given address
   *
   * Reference http://www.php.net/manual/en/function.parse-url.php#93983
   *
   */
  private static function getHost($address)
  {
    $parsedUrl = parse_url(trim($address));
    return @trim($parsedUrl['host'] ? $parsedUrl['host'] : array_shift(explode('/', $parsedUrl['path'], 2)));
  }

  /**
   *
   * Build list of keys in the form "key1|key2|...|keyN" for using into REGEXP
   *
   */
  private static function buildMatchKeywords($keywords)
  {
    $keylist = array_map('trim', explode(",", $keywords));
    $matchkeys = '';
    foreach ($keylist as $k => $v)
    {
      $v = preg_quote($v); // Current key should be cleaned escaping chars for REGEXP
      if ($v != '') { $matchkeys .= $v . "|"; }
    }
    $matchkeys = rtrim($matchkeys, '|'); // Drop last char if is a pipe
    return $matchkeys;
  }

  /**
   *
   * Return the routed (relative) URL
   *
   * @param  string  $url   Visited full URL
   * @param  array   $vars  List of variables to use (if empty use all current variables)
   *
   * @return  string  The routed (relative) URL
   *
   */
  private static function route_url($url, $vars)
  {
    $uri_visited_full_url = JURI::getInstance($url);
    $router = JSite::getRouter();
    $parsed = $router->parse($uri_visited_full_url);
    $suffix = isset($parsed['format']) ? $parsed['format'] : '';
    if (@count($vars)) // True if $vars is an array and with at least one element
    {
      // First drop unwanted vars
      foreach ($vars as $k => $v)
      {
        if (preg_match('/^!(.*)/', $k, $m) === 1)
        {
          if (isset($parsed[$m[1]])) { unset($parsed[$m[1]]); }
          unset($vars[$k]);
        }
      }
      if (@count($vars)) // True if there is still at least one element
      {
        foreach ($vars as $k => $v)
        {
          if ($v === null)
          {
            if (isset($parsed[$k]))
            {
              $vars[$k] = $parsed[$k];
            } else {
              unset($vars[$k]);
            }
          }
        }
        $p =& $vars;
      } else {
        $p =& $parsed;
      }
    } else {
      // Variables to use/not use are not specified
      foreach ($parsed as $k => $v)
      {
        if ($v === null)
        {
          unset($parsed[$k]);
        }
      }
      $p =& $parsed;
    }
    if (isset($p['option']))
    {
      // For components search for a menu item
      $q = $p;
      unset($q['Itemid']);
      unset($q['format']);
      foreach ($q as $k => $v)
      {
        // Remove the slug part if present
        $parts = explode(":", $v);
        $q[$k] = $parts[0];
      }
      // Build the URL to route
      $link = 'index.php';
      foreach ($q as $k => $v)
      {
        $link .= (($link === 'index.php') ? '?' : '&') . $k . '=' . $v;
      }
      $routed_link = JRoute::_($link, false);
      $app = JFactory::getApplication();
      $menu = $app->getMenu();
      $items = $menu->getItems('component', $q['option']);
      $found = false;
      $menu_route = '';
      foreach ($items as $k => $v)
      {
        $current_link = (JRoute::_($v->link, false));
        if ($current_link == $routed_link)
        {
          $found = true;
          $menu_route = $v->route;
          break;
        }
      }
      if ($found)
      {
        $sef_prefix = ($app->getCfg('sef_rewrite')) ? '' : 'index.php/';
        $routed = '/' . $sef_prefix . trim($menu_route, '/') . ((strlen($suffix) > 0) ? '.' . $suffix : '');
        return $routed;
      }
    }
    // Build the URL to route
    $build = 'index.php';
    foreach ($p as $pk => $pv)
    {
      $build .= (($build === 'index.php') ? '?' : '&') . $pk . '=' . $pv;
    }
    $routed = JRoute::_($build, false);
    return $routed;
  }

  /**
   *
   * Returns patterns and replacements for supported macros,
	 * only creating them if they didn't already exist
   *
   */
  private static function getMacros(&$macropatterns, &$macroreplacements)
  {
    /*
      http://fredbloggs:itsasecret@www.example.com:8080/path/to/Joomla/section/cat/index.php?task=view&id=32#anchorthis
      \__/   \________/ \________/ \_____________/ \__/\___________________________________/ \_____________/ \________/
       |          |         |              |        |                   |                           |             |
     scheme     user       pass          host      port                path                       query       fragment

    Supported URL macros:
       {siteurl}                                                    www.example.com/path/to/Joomla
       {scheme}                                                     http
       {host}                                                       www.example.com
       {port}                                                       8080
       {user}                                                       fredbloggs
       {pass}                                                       itsasecret
       {path}                                                       /path/to/Joomla/section/cat/index.php
       {query}                                                      task=view&id=32
       {queryfull}                                                  ?task=view&id=32
       {querybuild id,task}                                         id=32&task=view
       {querybuild id,task=edit}                                    id=32&task=edit
       {querybuild id,task=view,ItemId=12}                          id=32&task=view&ItemId=12
       {querybuildfull id,task}                                     ?id=32&task=view
       {querybuildfull id,task=save}                                ?id=32&task=save
       {querybuildfull id,task,action=close}                        ?id=32&task=view&action=close
       {querydrop task}                                             id=32
       {querydrop id,task=edit}                                     task=edit
       {querydrop id,task=save,action=close}                        task=save&action=close
       {querydropfull task}                                         ?id=32
       {querydropfull id,task=save}                                 ?task=save
       {querydropfull id,task=edit,action=close}                    ?task=edit&action=close
       {queryvar varname,default}                                   Returns the current value for the variable 'varname' of the URL, or the value 'default' if 'varname' is not defined (where default = '' when not specified)
       {queryvar task}                                              view
       {queryvar id}                                                32
       {queryvar maxsize,234}                                       234
       {requestvar varname,default}                                 Returns the current value for the variable 'varname' of the request, no matter about method (GET, POST, ...), or the value 'default' if 'varname' is not defined (where default = '' when not specified)
       {requestvar id}                                              32
       {requestvar limit,100}                                       100
       {authority}                                                  fredbloggs:itsasecret@www.example.com:8080
       {baseonly}                                                   /path/to/Joomla (empty when installed on root, i.e. it will never contains a trailing slash)
       {pathfrombase}                                               /section/cat/index.php
       {pathltrim /path/to}                                         /Joomla/section/cat/index.php
       {pathrtrim /index.php}                                       /path/to/Joomla/section/cat
       {pathfrombaseltrim /section}                                 /cat/index.php
       {pathfrombasertrim index.php}                                /section/cat/
       {preg_match N}pattern{/preg_match}                           (return the N-th matched pattern on the full source url, where N = 0 when not specified)
       {preg_match}/([^\/]+)(\.php|\.html|\.htm)/i{/preg_match}     index.php
       {preg_match 2}/([^\/]+)(\.php|\.html|\.htm)/i{/preg_match}   .php
       {preg_select table,column,key,N}pattern{/preg_select}        (uses the N-th matched result to execute a SQL query (SELECT column FROM table WHERE key = matchN). Support #__ notation for table name)
       {routeurl}                                                   Return the routed (relative) URL using all current variables
       {routeurl var1,var2,var3=myvalue,..,varN}                    Return the routed (relative) URL for specified variables (index.php?var1=value1&var2=value2&var3=myvalue&..&varN=valueN)
    */

    static $patterns;
    static $replacements;

    if (!isset($patterns)) {
      // Supported macros patterns
      $patterns = array();
      // URL macros
      $patterns[0] = "/\{siteurl\}/";
      $patterns[1] = "/\{scheme\}/";
      $patterns[2] = "/\{host\}/";
      $patterns[3] = "/\{port\}/";
      $patterns[4] = "/\{user\}/";
      $patterns[5] = "/\{pass\}/";
      $patterns[6] = "/\{path\}/";
      $patterns[7] = "/\{query\}/";
      $patterns[8] = "/\{queryfull\}/";
      $patterns[9] = "/\{querybuild ([^\}]+)\}/seuU";
      $patterns[10] = "/\{querybuildfull ([^\}]+)\}/seuU";
      $patterns[13] = "/\{queryvar ([^\}]+)\}/seuU";
      $patterns[14] = "/\{queryvar ([^\}]+),([^\}]+)\}/seuU";
      $patterns[15] = "/\{requestvar ([^\}\,]+),?\}/seuU";
      $patterns[16] = "/\{requestvar ([^\}]+),([^\}]+)\}/seuU";
      $patterns[17] = "/\{authority\}/";
      $patterns[18] = "/\{baseonly\}/";
      $patterns[19] = "/\{pathfrombase\}/";
      $patterns[20] = "/\{pathltrim ([^\}]+)\}/seuU";
      $patterns[21] = "/\{pathrtrim ([^\}]+)\}/seuU";
      $patterns[27] = "/\{routeurl\}/e";
      $patterns[28] = "/\{routeurl ([^\}]+)\}/seuU";
      // Site macros
      $patterns[29] = "/\{sitename\}/";
      $patterns[30] = "/\{globaldescription\}/";
      $patterns[31] = "/\{globalkeywords\}/";
      $patterns[32] = "/\{currenttitle\}/";
      $patterns[33] = "/\{currentdescription\}/";
      $patterns[34] = "/\{currentkeywords\}/";
      $patterns[35] = "/\{currentauthor\}/";
      $patterns[36] = "/\{currentgenerator\}/";
      // Content macros
      // Database macros
      $patterns[40] = "/\{tableselect ([^\}]+)\}(.*)\{\/tableselect\}/seuU";
      // String macros
      $patterns[41] = "/\{strip_tags\}(.*)\{\/strip_tags\}/seuU";
      $patterns[42] = "/\{substr ([^\}]+)\}(.*)\{\/substr\}/seuU";
      $patterns[43] = "/\{extract ([^\}]+)\}(.*)\{\/extract\}/seuU";
      $patterns[44] = "/\{extractp ([^\}]+)\}(.*)\{\/extractp\}/seuU";
      $patterns[45] = "/\{extractdiv ([^\}]+)\}(.*)\{\/extractdiv\}/seuU";
    }

    if (!isset($replacements))
    {
      // Get data needed for macros replacements
      $visited_siteurl = self::manageMacroParams('getsiteurl');
      $uri_visited_full_url_parts = self::manageMacroParams('geturlparts');
      $uri_visited_full_url_paths = self::manageMacroParams('geturlpaths');
      $global_info = self::manageMacroParams('getglobalinfo');
      $document_info = self::manageMacroParams('getdocumentinfo');

      // Supported macros replacements
      $replacements = array();
      // URL macros
      $replacements[0] = $visited_siteurl;
      $replacements[1] = $uri_visited_full_url_parts['scheme'];
      $replacements[2] = $uri_visited_full_url_parts['host'];
      $replacements[3] = $uri_visited_full_url_parts['port'];
      $replacements[4] = $uri_visited_full_url_parts['user'];
      $replacements[5] = $uri_visited_full_url_parts['pass'];
      $replacements[6] = $uri_visited_full_url_parts['path'];
      $replacements[7] = $uri_visited_full_url_parts['query'];
      $replacements[8] = (isset($uri_visited_full_url_parts['query'])) ? '?' . $uri_visited_full_url_parts['query'] : '';
      $replacements[9] = "plgSystemTagMeta::manageMacroParams('querybuild', array(0 => '\\1'))";
      $replacements[10] = "plgSystemTagMeta::manageMacroParams('querybuildfull', array(0 => '\\1'))";
      $replacements[13] = "plgSystemTagMeta::manageMacroParams('queryvar', array(0 => '\\1'))";
      $replacements[14] = "plgSystemTagMeta::manageMacroParams('queryvar', array(0 => '\\1', 1 => '\\2'))";
      $replacements[15] = "plgSystemTagMeta::manageMacroParams('requestvar', array(0 => '\\1'))";
      $replacements[16] = "plgSystemTagMeta::manageMacroParams('requestvar', array(0 => '\\1', 1 => '\\2'))";
      $replacements[17] = $uri_visited_full_url_parts['authority'];
      $replacements[18] = $uri_visited_full_url_paths['baseonly'];
      $replacements[19] = $uri_visited_full_url_paths['pathfrombase'];
      $replacements[20] = "plgSystemTagMeta::manageMacroParams('pathltrim', array(0 => '\\1'))";
      $replacements[21] = "plgSystemTagMeta::manageMacroParams('pathrtrim', array(0 => '\\1'))";
      $replacements[27] = "plgSystemTagMeta::manageMacroParams('routeurl', array())";
      $replacements[28] = "plgSystemTagMeta::manageMacroParams('routeurl', array(0 => '\\1'))";
      // Site macros
      $replacements[29] = $global_info['sitename'];
      $replacements[30] = $global_info['MetaDesc'];
      $replacements[31] = $global_info['MetaKeys'];
      $replacements[32] = $document_info['title'];
      $replacements[33] = $document_info['description'];
      $replacements[34] = $document_info['keywords'];
      $replacements[35] = $document_info['author'];
      $replacements[36] = $document_info['generator'];
      // Content macros
      // Database macros
      $replacements[40] = "plgSystemTagMeta::manageMacroParams('tableselect', array(0 => '\\1', 1 => '\\2'))";
      // String macros
      $replacements[41] = "plgSystemTagMeta::manageMacroParams('strip_tags', array(0 => '\\1'))";
      $replacements[42] = "plgSystemTagMeta::manageMacroParams('substr', array(0 => '\\1', 1 => '\\2'))";
      $replacements[43] = "plgSystemTagMeta::manageMacroParams('extract', array(0 => '\\1', 1 => '\\2'))";
      $replacements[44] = "plgSystemTagMeta::manageMacroParams('extractp', array(0 => '\\1', 1 => '\\2'))";
      $replacements[45] = "plgSystemTagMeta::manageMacroParams('extractdiv', array(0 => '\\1', 1 => '\\2'))";
    }

    $macropatterns = $patterns;
    $macroreplacements = $replacements;
    return true;
  }

  /**
   *
   * Manage params for supported macros (e.g. used as callback function to replace macros with parameters)
   *
   */
  private static function manageMacroParams($macro, $params = null)
  {
    static $siteurl = '';             // The (called) {siteurl} (e.g. www.youdomain.com/site) from the current server variables
    static $url = '';                 // The (called) full URL (e.g. http://www.youdomain.com/site/index.php?id=12) from the current server variables
    static $urlparts = array();       // Array of JURI parts for the (called) full URL
    static $urlvars = array();        // Array of all query variables (e.g. array([task] => view, [id] => 32) )
    static $urlpaths = array();       // Array of paths (e.g. array([baseonly] => /path/to/Joomla, [path] => /path/to/Joomla/section/cat/index.php, [pathfrombase] => /section/cat/index.php) )
    static $globalinfo = array();     // Array of global info
    static $documentinfo = array();   // Array of document info

    $value = '';
    switch ($macro)
    {
      // set methods
      case 'setsiteurl':
        $siteurl = $params;
        break;
      case 'seturl':
        $url = $params;
        break;
      case 'seturlparts':
        $urlparts = $params;
        break;
      case 'seturlvars':
        $urlvars = $params;
        break;
      case 'seturlpaths':
        $urlpaths = $params;
        break;
      case 'setglobalinfo':
        $globalinfo = $params;
        break;
      case 'setdocumentinfo':
        $documentinfo = $params;
        break;
      // get methods
      case 'getsiteurl':
        $value = $siteurl;
        break;
      case 'geturl':
        $value = $url;
        break;
      case 'geturlparts':
        $value = $urlparts;
        break;
      case 'geturlvars':
        $value = $urlvars;
        break;
      case 'geturlpaths':
        $value = $urlpaths;
        break;
      case 'getglobalinfo':
        $value = $globalinfo;
        break;
      case 'getdocumentinfo':
        $value = $documentinfo;
        break;
      // macro methods
      case 'querybuild':
      case 'querybuildfull':
        $build_vars = explode(',', $params[0]);
        foreach ($build_vars as $k => $v)
        {
          $p = strpos($v, "=");
          if ($p === false)
          {
            // Only parameter name
            if (isset($urlvars[$v])) // Need to check only not-null values
            {
              $value .= (($value === '') ? '' : '&') . $v . '=' . $urlvars[$v];
            }
          } else {
            // New parameter or overrides existing
            $pn = substr($v, 0, $p);
            $pv = substr($v, $p + 1, strlen($v) - $p - 1);
            if ((strlen($pn) > 0) && (strlen($pv) > 0)) // Need to take only not-null names and values
            {
              $value .= (($value === '') ? '' : '&') . $pn . '=' . $pv;
            }
          }
        }
        if ( ($macro === 'querybuildfull') && (strlen($value) > 0) ) { $value = '?' . $value; }
        break;
      case 'queryvar':
        // $params[0] =  variable name
        // $params[1] =  default value if variable is not set (optional)
        @$value = $params[1];
        if (array_key_exists($params[0], $urlvars)) { $value = $urlvars[$params[0]]; }
        break;
      case 'requestvar':
        // $params[0] =  variable name
        // $params[1] =  default value if variable is not set (optional)
        if ( isset($params[1]) )
        {
          $value = JFactory::getApplication()->input->get($params[0], $params[1]);
        } else {
          $value = JFactory::getApplication()->input->get($params[0]);
        }
        break;
      case 'pathltrim':
        $value = $urlpaths['path'];
        if (strpos($value, $params[0]) === 0)
        {
          $value = substr($value, strlen($params[0]), strlen($value) - strlen($params[0]));
        }
        break;
      case 'pathrtrim':
        $value = $urlpaths['path'];
        if (strpos($value, $params[0]) === (strlen($value) - strlen($params[0])))
        {
          $value = substr($value, 0, strlen($value) - strlen($params[0]));
        }
        break;
      case 'routeurl':
        $vars = array();
        if (isset($params[0]))
        {
          $build_vars = explode(',', $params[0]);
          foreach ($build_vars as $k => $v)
          {
            $p = strpos($v, "=");
            if ($p === false)
            {
              // Only parameter name
              $vars[$v] = null;
            } else {
              // New parameter or overrides existing
              $pn = substr($v, 0, $p);
              $pv = substr($v, $p + 1, strlen($v) - $p - 1);
              if ((strlen($pn) > 0) && (strlen($pv) > 0)) // Need to take only not-null names and values
              {
                $vars[$pn] = $pv;
              }
            }
          }
        }
        $value = self::route_url($url, $vars);
        break;
      case 'tableselect':
        // $params[0] = table,column,key
        // $params[1] = value
        // table: table name to query (support #__ notation)
        // column: field name to return
        // key: field name to use as selector in where condition
        // value: value to use for comparison in the where condition (WHERE key = value)
        $arg = explode(",", trim($params[0]));
        if (count($arg) == 3)
        {
          $arg = array_map("trim", $arg);
          $value = $params[1];
          if ($value != '')
          {
            // Perform a DB query
            $db = JFactory::getDBO();
            $db->setQuery("SELECT `" . $arg[1] . "` FROM `" . $arg[0] . "` WHERE `" . $arg[2] . "`=" . $db->quote($value));
            $result = $db->loadResult();
            if (isset($result)) { $value = $result; }
          }
        }
        break;
      case 'strip_tags':
        $value = strip_tags($params[0]);
        break;
      case 'substr':
        // $params[0] = start,length
        // $params[1] = input string
        // start: start index for the portion string (0 based)
        // length: lenght of the portion string
        $arg = explode(",", trim($params[0]));
        if ( (count($arg) == 1) || (count($arg) == 2) )
        {
          $arg = array_map("trim", $arg);
          $value = $params[1];
          if ($value != '')
          {
            $value = (count($arg) == 1) ? substr($value, (int) $arg[0]) : substr($value, (int) $arg[0], (int) $arg[1]);
          }
        }
        break;
      case 'extract':
        $rows = preg_split("/[\r\n]+/iU" , $params[1]);
        $index = (int) $params[0] - 1;
        if (isset($rows[$index])) { $value = strip_tags($rows[$index]); }
        break;
      case 'extractp':
        preg_match_all("/<p>(.*)<\/p>/iU" , $params[1], $rows);
        $index = (int) $params[0] - 1;
        if (isset($rows[1][$index])) { $value = strip_tags($rows[1][$index]); }
        break;
      case 'extractdiv':
        preg_match_all("/<div>(.*)<\/div>/iU" , $params[1], $rows);
        $index = (int) $params[0] - 1;
        if (isset($rows[1][$index])) { $value = strip_tags($rows[1][$index]); }
        break;
    }
    return $value;
  }

  /**
   *
   * Evaluate and return placeholders defined in the list
   *
   */
  private static function evaluatePlaceholders($list)
  {
    $placeholders = array();
    $placeholders_count = 0;
    $rows = preg_split("/[\r\n]+/", $list);
    foreach ($rows as $row_key => $row_value)
    {
      $row_value = trim($row_value);
      if (strlen($row_value) > 0)
      {
        $parts = array_map('trim', explode("=", $row_value, 2));
        $parts_key = @$parts[0];
        /* Placeholder names follow the same rules as other labels and variables in PHP. A valid placeholder name starts with a letter or underscore, followed by any number of letters, numbers, or underscores. As a regular expression, it would be expressed thus: '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*' */
        if ((strlen($parts_key) > 0) && (preg_match(PLACEHOLDERS_MATCH_MASK,$parts_key) === 1))
        {
            $parts_value = @$parts[1];
            if (strlen($parts_value) > 0)
            {
                $placeholders[$parts_key] = $parts_value;
                // Replace already defined placeholders in the new placeholder
                preg_match_all(PLACEHOLDERS_MATCH_ALL_MASK, $placeholders[$parts_key], $matches);
                foreach ($matches[1] as $current)
                {
                  if (array_key_exists($current, $placeholders))
                  {
                    $placeholders[$parts_key] = str_replace('${' . $current . '}', $placeholders[$current], $placeholders[$parts_key]);
                  }
                }

                // Get supported macros patterns and replacements
                 self::getMacros($patterns, $replacements);

                // Replace macros
                $placeholders[$parts_key] = preg_replace($patterns, $replacements, $placeholders[$parts_key]);

                $placeholders_count++;
            } else {
                // Unset placeholder
                unset($placeholders[$parts_key]);
            }
        }
      }
    }
    return $placeholders;
  }

  /**
   *
   * Replace and return defined array of placeholders from the input string
   *
   */
  private static function replacePlaceholders($placeholders, $string)
  {
    $value = $string;
    preg_match_all(PLACEHOLDERS_MATCH_ALL_MASK, $value, $matches);
    foreach ($matches[1] as $current)
    {
      if (array_key_exists($current, $placeholders))
      {
        $value = str_replace('${' . $current . '}', $placeholders[$current], $value);
      }
    }
    return $value;
  }

  public function onAfterDispatch()
  {
    $document = JFactory::getDocument();
    $docType = $document->getType();

    // Get the application object
    $app = JFactory::getApplication();

    // Make sure we are not in the administrator
    if ( $app->isAdmin() ) return;

    // Only site pages that are html docs
    if ( JFactory::getDocument()->getType() !== 'html' ) return;

    // Set sitename
    $sitename = $app->getCfg('sitename');

    $currenturi_encoded = self::getRequestURI( $this->params->get('redirect', 0) ); // Raw (encoded): with %## chars
    // Remove the base path
    $basepath = trim($this->params->def('basepath', ''), ' /'); // Decoded: without %## chars (now you can see spaces, cyrillics, ...)
    $basepath = urlencode(utf8_encode($basepath)); // Raw (encoded): with %## chars
    $basepath = str_replace('%2F', '/', $basepath);
    $basepath = str_replace('%3A', ':', $basepath);
    if ($basepath != '')
    {
      if (strpos($currenturi_encoded, '/'.$basepath.'/') === 0)
      {
        $currenturi_encoded = substr($currenturi_encoded, strlen($basepath) + 1); // Raw (encoded): with %## chars
      }
    }
    $currenturi = utf8_decode(urldecode($currenturi_encoded)); // Decoded: without %## chars (now you can see spaces, cyrillics, ...)
    $currentfullurl_encoded = self::getPrefix() . $currenturi_encoded; // Raw (encoded): with %## chars
    $currentfullurl = utf8_decode(urldecode($currentfullurl_encoded)); // Decoded: without %## chars (now you can see spaces, cyrillics, ...)

    $preserve_title = false;

    $db = JFactory::getDBO();
    $db->setQuery('SELECT * FROM #__tagmeta_rules '
    . 'WHERE ( '
    . '( (' . $db->quote($currenturi) . ' REGEXP BINARY url)>0 AND (case_sensitive<>0) AND (decode_url<>0) AND (request_only<>0) ) '
    . 'OR ( (' . $db->quote($currenturi_encoded) . ' REGEXP BINARY url)>0 AND (case_sensitive<>0) AND (decode_url=0) AND (request_only<>0) ) '
    . 'OR ( (' . $db->quote($currentfullurl) . ' REGEXP BINARY url)>0 AND (case_sensitive<>0) AND (decode_url<>0) AND (request_only=0) ) '
    . 'OR ( (' . $db->quote($currentfullurl_encoded) . ' REGEXP BINARY url)>0 AND (case_sensitive<>0) AND (decode_url=0) AND (request_only=0) ) '
    . 'OR ( (' . $db->quote($currenturi) . ' REGEXP url)>0 AND (case_sensitive=0) AND (decode_url<>0) AND (request_only<>0) ) '
    . 'OR ( (' . $db->quote($currenturi_encoded) . ' REGEXP url)>0 AND (case_sensitive=0) AND (decode_url=0) AND (request_only<>0) ) '
    . 'OR ( (' . $db->quote($currentfullurl) . ' REGEXP url)>0 AND (case_sensitive=0) AND (decode_url<>0) AND (request_only=0) ) '
    . 'OR ( (' . $db->quote($currentfullurl_encoded) . ' REGEXP url)>0 AND (case_sensitive=0) AND (decode_url=0) AND (request_only=0) ) '
    . ') '
    . 'AND published=1 '
    . 'ORDER BY ordering');
    $items = $db->loadObjectList();
    $itemsfound = count($items);
    if ($itemsfound > 0)
    {
        // Initialize URL related variables
        $visited_siteurl = self::getSiteURL();

        $visited_full_url = self::getURL();

        $uri_visited_full_url = JURI::getInstance($visited_full_url);
        $uri_visited_full_url_parts['scheme'] = $uri_visited_full_url->getScheme();
        $uri_visited_full_url_parts['host'] = $uri_visited_full_url->getHost();
        $uri_visited_full_url_parts['port'] = $uri_visited_full_url->getPort();
        $uri_visited_full_url_parts['user'] = $uri_visited_full_url->getUser();
        $uri_visited_full_url_parts['pass'] = $uri_visited_full_url->getPass();
        $uri_visited_full_url_parts['path'] = $uri_visited_full_url->getPath();
        $uri_visited_full_url_parts['query'] = $uri_visited_full_url->getQuery();
        $uri_visited_full_url_parts['authority'] = (isset($uri_visited_full_url_parts['port'])) ? $uri_visited_full_url_parts['host'] . ':' . $uri_visited_full_url_parts['port'] : $uri_visited_full_url_parts['host'];
        $uri_visited_full_url_parts['authority'] = (isset($uri_visited_full_url_parts['user'])) ? $uri_visited_full_url_parts['user'] . ':' . $uri_visited_full_url_parts['pass'] . '@' . $uri_visited_full_url_parts['authority'] : $uri_visited_full_url_parts['authority'];

        $uri_visited_full_url_vars = $uri_visited_full_url->getQuery(true);

        $baseonly = self::getBase(true);
        $pathfrombase = (strlen($baseonly) > 0) ? substr($uri_visited_full_url_parts['path'], strlen($baseonly), strlen($uri_visited_full_url_parts['path']) - strlen($baseonly)) : $uri_visited_full_url_parts['path'];
        $uri_visited_full_url_paths['baseonly'] = $baseonly;
        $uri_visited_full_url_paths['path'] = $uri_visited_full_url_parts['path'];
        $uri_visited_full_url_paths['pathfrombase'] = $pathfrombase;

        // Set URL related variables in callback function
        self::manageMacroParams('setsiteurl', $visited_siteurl);
        self::manageMacroParams('seturl', $visited_full_url);
        self::manageMacroParams('seturlparts', $uri_visited_full_url_parts);
        self::manageMacroParams('seturlvars', $uri_visited_full_url_vars);
        self::manageMacroParams('seturlpaths', $uri_visited_full_url_paths);

        // Set global info in callback function
        $global_info['sitename'] = $app->getCfg('sitename');
        $global_info['MetaDesc'] = $app->getCfg('MetaDesc');
        $global_info['MetaKeys'] = $app->getCfg('MetaKeys');
        self::manageMacroParams('setglobalinfo', $global_info);

        // Set document info in callback function
        $document_info['title'] = $document->getTitle();
        $document_info['description'] = $document->getDescription();
        $document_info['keywords'] = $document->getMetaData('keywords');
        $document_info['author'] = $document->getMetaData('author');
        $document_info['generator'] = $document->getGenerator();
        self::manageMacroParams('setdocumentinfo', $document_info);

        // Load patterns and replacements for supported macros
        self::getMacros($patterns, $replacements);

        $currentitem = 0;
        $continue = true;
        while ($continue)
        {
            // Update hits
            $last_visit = date("Y-m-d H:i:s");
            $db->setQuery("UPDATE #__tagmeta_rules SET hits = hits + 1, last_visit = " . $db->quote( $last_visit ) . " WHERE id = " . $db->quote( $items[$currentitem]->id ));
            $res = @$db->query();

            // Evaluate placeholders
            $placeholders = self::evaluatePlaceholders($items[$currentitem]->placeholders);

            // Replace placeholders
            $items[$currentitem]->title = self::replacePlaceholders($placeholders, $items[$currentitem]->title);
            $items[$currentitem]->description = self::replacePlaceholders($placeholders, $items[$currentitem]->description);
            $items[$currentitem]->author = self::replacePlaceholders($placeholders, $items[$currentitem]->author);
            $items[$currentitem]->keywords = self::replacePlaceholders($placeholders, $items[$currentitem]->keywords);
            $items[$currentitem]->rights = self::replacePlaceholders($placeholders, $items[$currentitem]->rights);
            $items[$currentitem]->xreference = self::replacePlaceholders($placeholders, $items[$currentitem]->xreference);
            $items[$currentitem]->canonical = self::replacePlaceholders($placeholders, $items[$currentitem]->canonical);

            // Replace macros
            $items[$currentitem]->title = preg_replace($patterns, $replacements, $items[$currentitem]->title);
            $items[$currentitem]->description = preg_replace($patterns, $replacements, $items[$currentitem]->description);
            $items[$currentitem]->author = preg_replace($patterns, $replacements, $items[$currentitem]->author);
            $items[$currentitem]->keywords = preg_replace($patterns, $replacements, $items[$currentitem]->keywords);
            $items[$currentitem]->rights = preg_replace($patterns, $replacements, $items[$currentitem]->rights);
            $items[$currentitem]->xreference = preg_replace($patterns, $replacements, $items[$currentitem]->xreference);
            $items[$currentitem]->canonical = preg_replace($patterns, $replacements, $items[$currentitem]->canonical);

            // Check for synonyms
            if (($items[$currentitem]->synonyms != 0) && ($items[$currentitem]->synonmax > 0)) {
              $binarymatch = ($items[$currentitem]->synonyms == 1) ? '' : 'BINARY'; // Add "BINARY" to "REGEXP" for case sensitive match
              $orderingmatch = ($items[$currentitem]->synonweight) ? 'weight DESC' : 'ordering ASC';
              $keywordsmatch = $this->buildMatchKeywords($items[$currentitem]->keywords);

              $db->setQuery("SELECT * FROM #__tagmeta_synonyms WHERE keywords REGEXP " . $binarymatch . " '(" . $keywordsmatch . ")' and published='1' ORDER BY " . $orderingmatch);
              $synonymsitems = $db->loadObjectList();
              if ( count($synonymsitems) > 0 ) {
                $keywords_list = $items[$currentitem]->keywords;
                if ($items[$currentitem]->synonyms == 1) { $keywords_list = strtolower($keywords_list); }
                $keywords_array = array_count_values(explode(",", $keywords_list)); // Not case sensitive: all to lowercase
                $keywordsmatch = '';
                $addedmatch = 0;
                $usedidmatch = array();
                foreach ($synonymsitems as $synonymskey => $synonymsvalue) {
                  if ($items[$currentitem]->synonyms == 1) { $synonymsvalue->synonyms = strtolower($synonymsvalue->synonyms); } // Not case sensitive: all to lowercase
                  $current_synonyms_list = array_count_values(array_map('trim', explode(",", $synonymsvalue->synonyms)));
                  foreach ($current_synonyms_list  as $current_synonyms_key => $current_synonyms_value) {
                    if ( ($current_synonyms_key != '') && (!isset($keywords_array[$current_synonyms_key])) ) {
                      // Add current keyword
                      $keywordsmatch .= $current_synonyms_key . ",";
                      $keywords_array[$current_synonyms_key] = isset($keywords_array[$current_synonyms_key]) ? $keywords_array[$current_synonyms_key] + 1 : 1;
                      $addedmatch++;
                      $usedidmatch[$synonymsvalue->id] = isset($usedidmatch[$synonymsvalue->id]) ? $usedidmatch[$synonymsvalue->id] + 1 : 1;
                      if ($addedmatch >= $items[$currentitem]->synonmax) { break; }
                    }
                  }
                  if ($addedmatch >= $items[$currentitem]->synonmax) { break; }
                }
                $keywordsmatch = rtrim($keywordsmatch, ','); // Drop last char if is a comma
                // Update keywords list
                $items[$currentitem]->keywords .= "," . $keywordsmatch;
                $items[$currentitem]->keywords = ltrim($items[$currentitem]->keywords, ','); // Drop first char if is a comma
                // Update hits on used synonyms items
                if ($addedmatch > 0) {
                  $usedidlist = '';
                  foreach ($usedidmatch as $usedidkey => $usedidvalue) {
                    $usedidlist .= $usedidkey . ",";
                  }
                  $usedidlist = rtrim($usedidlist, ','); // Drop last char if is a comma
                  $db->setQuery("UPDATE #__tagmeta_synonyms SET hits = hits + 1, last_visit = " . $db->quote( $last_visit ) . " WHERE id IN (" . $usedidlist . ")");
                  $res = @$db->query();
                }
              }
            }

            if ( !empty($items[$currentitem]->title) ) { $document->setTitle($items[$currentitem]->title); }
            if ( !empty($items[$currentitem]->description) ) { $document->setDescription(str_replace('"', '&quot;', $items[$currentitem]->description)); }
            if ( !empty($items[$currentitem]->author) ) { $document->setMetaData('author', $items[$currentitem]->author); }
            if ( !empty($items[$currentitem]->keywords) ) { $document->setMetaData('keywords', str_replace('"', '&quot;', $items[$currentitem]->keywords)); }
            if ( !empty($items[$currentitem]->rights) ) { $document->setMetaData('rights', str_replace('"', '&quot;', $items[$currentitem]->rights)); }
            if ( !empty($items[$currentitem]->xreference) ) { $document->setMetaData('xreference', str_replace('"', '&quot;', $items[$currentitem]->xreference)); }
            if ( !empty($items[$currentitem]->canonical) ) { $document->addHeadLink($items[$currentitem]->canonical, 'canonical', 'rel'); }

            // Robots meta options: 0=No,1=Yes,2=Skip
            $robots = '';
            if ($items[$currentitem]->rindex != 2) { $robots .= ($items[$currentitem]->rindex) ? 'index,' : 'noindex,'; }
            if ($items[$currentitem]->rfollow != 2) { $robots .= ($items[$currentitem]->rfollow) ? 'follow,' : 'nofollow,'; }
            if ($items[$currentitem]->rsnippet != 2) { $robots .= ($items[$currentitem]->rsnippet) ? 'snippet,' : 'nosnippet,'; }
            if ($items[$currentitem]->rarchive != 2) { $robots .= ($items[$currentitem]->rarchive) ? 'archive,' : 'noarchive,'; }
            if ($items[$currentitem]->rodp != 2) { $robots .= ($items[$currentitem]->rodp) ? 'odp,' : 'noodp,'; }
            if ($items[$currentitem]->rimageindex != 2) { $robots .= ($items[$currentitem]->rimageindex) ? 'imageindex,' : 'noimageindex,'; }
            $robots = rtrim($robots, ','); // Drop last char if is a comma
            if ( !empty($robots) ) { $document->setMetaData('robots', $robots); }

            $preserve_title = $items[$currentitem]->preserve_title;
            $last_rule = ($items[$currentitem]->last_rule);
            $currentitem++;
            $continue = ( (!$last_rule) && ($currentitem < $itemsfound) );
        }
    }

    $replacegenerator = $this->params->get('replacegenerator', 0);
    if ($replacegenerator != 0) {
      if ($replacegenerator == 3) {
        if ($document->getMetaData('generator')) {
          $document->setGenerator('');
          $this->_clean_generator = true; // Clean if exists
        }
      } else {
        $customgenerator = $this->params->get('customgenerator', '');
        if ( (($document->getMetaData('generator')) && ($replacegenerator == 1)) || ($replacegenerator == 2) ) {
          $document->setGenerator(str_replace('"', '&quot;', $customgenerator)); // Replace existing or force
        }
      }
    }

    $addsitename = $this->params->get('addsitename', 0);
    if (($addsitename != 0) && (!$preserve_title)) {
      // Add site name before or after the page title
      $separator = str_replace( '\b', ' ', $this->params->get('separator', '\b-\b') );
      $currenttitle = $document->getTitle();
      if ( $addsitename == 1 ) {
        $newtitle = $sitename . $separator . $currenttitle; // Before
      } else {
        $newtitle = $currenttitle . $separator . $sitename; // After
      }
      $document->setTitle( htmlspecialchars_decode($newtitle) );
    }

    if ( $this->params->get('cleandefaultpage') == 1 ) {
      // Check if this is the default page (home page)
      $menu =& JSite::getMenu();
      if ( $menu->getActive() == $menu->getDefault() ) {
        $document->setTitle($sitename);
      }
    }

    $metatitle = $this->params->get('metatitle', 1);
    if ( $metatitle ) {
          $tagvalue = $document->getTitle();
          if ( empty($tagvalue) ) {
                // Tag title is empty
                $metavalue = $document->getMetaData('title');
                if ( ( !empty($metavalue) ) && ($metatitle == 2) ) {
                  $document->setTitle($metavalue);
                }
          } else {
                // Tag title is not empty
                $metavalue = $document->getMetaData('title');
                if ( ( !empty($metavalue) ) || ($metatitle == 2) ) {
                  $document->setMetaData('title', str_replace('"', '&quot;', $tagvalue));
                }
          }
    }

    $customauthor = $this->params->get('customauthor', '');
    $addauthor = $this->params->get('addauthor', 0);
    if ( ( $customauthor ) && ($addauthor != 0) ) {
          $currentauthor = $document->getMetaData('author');
          if ( ($addauthor == 1) || ( empty($currentauthor) ) ) {
                $document->setMetaData('author', str_replace('"', '&quot;', $customauthor));
          }
    }

    $customcopyright = $this->params->get('customcopyright', '');
    $addcopyright = $this->params->get('addcopyright', 0);
    if ( ( $customcopyright ) && ($addcopyright != 0) ) {
          $currentcopyright = $document->getMetaData('copyright');
          if ( ($addcopyright == 1) || ( empty($currentcopyright) ) ) {
                $document->setMetaData('copyright', str_replace('"', '&quot;', $customcopyright));
          }
    }

  }

  public function onAfterRender()
  {
    // Get the application object
    $app = JFactory::getApplication();

    // Make sure we are not in the administrator
    if ( $app->isAdmin() ) return;

    // Only site pages that are html docs
    if ( JFactory::getDocument()->getType() !== 'html' ) return;

    $content = JResponse::getBody();
    $changed = false;

    // Clean meta tag generator
    if ($this->_clean_generator) {
      $content = preg_replace('/<meta.*name=[\",\']generator[\",\'].*\/?>/i', '', $content);
      $changed = true;
    }

    if ($changed) { JResponse::setBody($content); }
  }

}
