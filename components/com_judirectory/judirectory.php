<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
 <script src="/media/jui/js/jquery.min.js" type="text/javascript"></script>
   <script type="text/javascript">
   var check1234 = 0;
 $(function(){
 var pathname = window.location.href;
 var words = pathname.split("#");
 var trimmedString = words[1];
  document.getElementById(trimmedString).style.paddingTop = "100px";
  setTimeout(scrollDone,1000);
});
function scrollDone(){
	check1234= 1;
$(window).scroll(function(){
	if(check1234==1){
		$('.judir-listing').css({'padding-top':'0px'});
	}
});        
}</script>
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


jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');

// Don't change this constant if you don't know what are you doing, it can break your site
define('JUDIRPROVERSION', false);


JLoader::register('JUDirectoryFrontHelper', JPATH_SITE . '/components/com_judirectory/helpers/helper.php');
JLoader::register('JUDirectoryFrontHelperBreadcrumb', JPATH_SITE . '/components/com_judirectory/helpers/breadcrumb.php');
JLoader::register('JUDirectoryFrontHelperCaptcha', JPATH_SITE . '/components/com_judirectory/helpers/captcha.php');
JLoader::register('JUDirectoryFrontHelperCategory', JPATH_SITE . '/components/com_judirectory/helpers/category.php');
JLoader::register('JUDirectoryFrontHelperComment', JPATH_SITE . '/components/com_judirectory/helpers/comment.php');
JLoader::register('JUDirectoryFrontHelperCriteria', JPATH_SITE . '/components/com_judirectory/helpers/criteria.php');
JLoader::register('JUDirectoryFrontHelperListing', JPATH_SITE . '/components/com_judirectory/helpers/listing.php');
JLoader::register('JUDirectoryFrontHelperEditor', JPATH_SITE . '/components/com_judirectory/helpers/editor.php');
JLoader::register('JUDirectoryFrontHelperField', JPATH_SITE . '/components/com_judirectory/helpers/field.php');
JLoader::register('JUDirectoryFrontHelperLanguage', JPATH_SITE . '/components/com_judirectory/helpers/language.php');
JLoader::register('JUDirectoryFrontHelperLog', JPATH_SITE . '/components/com_judirectory/helpers/log.php');
JLoader::register('JUDirectoryFrontHelperMail', JPATH_SITE . '/components/com_judirectory/helpers/mail.php');
JLoader::register('JUDirectoryFrontHelperModerator', JPATH_SITE . '/components/com_judirectory/helpers/moderator.php');
JLoader::register('JUDirectoryFrontHelperPermission', JPATH_SITE . '/components/com_judirectory/helpers/permission.php');
JLoader::register('JUDirectoryFrontHelperPluginParams', JPATH_SITE . '/components/com_judirectory/helpers/pluginparams.php');
JLoader::register('JUDirectoryFrontHelperRating', JPATH_SITE . '/components/com_judirectory/helpers/rating.php');
JLoader::register('JUDirectoryFrontHelperSeo', JPATH_SITE . '/components/com_judirectory/helpers/seo.php');
JLoader::register('JUDirectoryFrontHelperString', JPATH_SITE . '/components/com_judirectory/helpers/string.php');
JLoader::register('JUDirectoryFrontHelperTemplate', JPATH_SITE . '/components/com_judirectory/helpers/template.php');

JLoader::register('JUDirectoryHelperRoute', JPATH_SITE . '/components/com_judirectory/helpers/route.php');


JLoader::register('JUDirectoryHelper', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/judirectory.php');
JLoader::register('JUTimThumb', JPATH_ADMINISTRATOR . '/components/com_judirectory/timthumb/timthumb.php');
JLoader::register('Watermark', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/watermark.class.php');
JLoader::register('JUDIRView', JPATH_SITE . '/components/com_judirectory/helpers/judirview.php');
JLoader::register('JUDIRPagination', JPATH_SITE . '/components/com_judirectory/helpers/judirpagination.php');
JLoader::register('JUDIRModelList', JPATH_SITE . '/components/com_judirectory/helpers/judirmodellist.php');

JLoader::register('JUDirectorySearchHelper', JPATH_SITE . '/components/com_judirectory/helpers/search.php');

spl_autoload_register(array('JUDirectoryHelper', 'autoLoadFieldClass'));


JUDirectoryFrontHelperLanguage::loadLanguageForTopLevelCat();


JUDirectoryFrontHelperLanguage::loadLanguageFile("com_judirectory.custom");

if (JUDirectoryHelper::isJoomla3x())
{
	JHtml::_('script', 'system/core.js', false, true);
}

$app  = JFactory::getApplication();
$task = $app->input->get('task');

switch ($task)
{
	case 'captcha':
		$namespace = $app->input->getString('captcha_namespace', '');
		JUDirectoryFrontHelperCaptcha::captchaSecurityImages($namespace);
		exit;
		break;

	case 'rawdata':
		$field_id   = $app->input->getInt('field_id', 0);
		$listing_id = $app->input->getInt('listing_id', 0);
		$fieldObj   = JUDirectoryFrontHelperField::getField($field_id, $listing_id);
		JUDirectoryHelper::obCleanData();
		$fieldObj->getRawData();
		exit;
		break;

	case 'cron':
		
		JUDirectoryFrontHelperMail::sendMailq();
		exit;
		break;

	default:
		$controller = JControllerLegacy::getInstance('judirectory');

		
		$controller->execute($app->input->get('task'));

		
		$controller->redirect();
		break;
}


$params = JUDirectoryHelper::getParams();
if ($params->get('send_mailqs_on_pageload', 0))
{
	JUDirectoryFrontHelperMail::sendMailq();
}
