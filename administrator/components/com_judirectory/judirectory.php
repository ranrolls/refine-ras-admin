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

JLoader::register('JUDirectoryHelper', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/judirectory.php');
JLoader::register('JUTimThumb', JPATH_ADMINISTRATOR . '/components/com_judirectory/timthumb/timthumb.php');
JLoader::register('Watermark', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/watermark.class.php');
JLoader::register('JUDIRViewAdmin', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/judirviewadmin.php');

JLoader::register('JUDirectorySearchHelper', JPATH_SITE . '/components/com_judirectory/helpers/search.php');

spl_autoload_register(array('JUDirectoryHelper', 'autoLoadFieldClass'));


jimport('joomla.application.component.controller');

$app = JFactory::getApplication();


$task       = $app->input->get('task');
$view       = $app->input->get('view');
$permission = JUDirectoryHelper::checkGroupPermission($task, $view);
if (!$permission)
{
	return JError::raiseError(403, JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
}


if (!JFactory::getUser()->authorise('core.manage', 'com_judirectory'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$document   = JFactory::getDocument();
$isJoomla3x = JUDirectoryHelper::isJoomla3x();
if ($isJoomla3x)
{
	$document->addStyleSheet(JUri::root(true) . '/administrator/components/com_judirectory/assets/css/styles.css');
}
else
{
	$document->addStyleSheet(JUri::root(true) . '/administrator/components/com_judirectory/assets/css/styles.j25.css');
	
	$document->addStyleSheet(JUri::root(true) . '/administrator/components/com_judirectory/assets/css/jicomoon.css');
}

JUDirectoryFrontHelper::loadjQuery();
JUDirectoryFrontHelper::loadBootstrap();

$document->addScript(JUri::root() . "components/com_judirectory/assets/js/jquery.dragsort.min.js");


if ($isJoomla3x && $view == 'subscriptions')
{
	$document->addScript(JUri::base() . "components/com_judirectory/models/forms/subscriptions.js");
}


if ($isJoomla3x && $view == 'collections')
{
	$document->addScript(JUri::base() . "components/com_judirectory/models/forms/collections.js");
}


$controller = JControllerLegacy::getInstance('JUDirectory');


$controller->execute($app->input->get('task'));


$controller->redirect();