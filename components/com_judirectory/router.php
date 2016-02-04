<?php
/*
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
JLoader::register('JUDirectoryHelperRoute', JPATH_SITE . '/components/com_judirectory/helpers/route.php');


if (!class_exists('JComponentRouterBase'))
{
	
	abstract class JComponentRouterBase
	{
		
		public function preprocess($query)
		{
			return $query;
		}
	}
}


class JUDirectoryRouter extends JComponentRouterBase
{
	
	public function build(&$query)
	{
		$segments = array();

		
		$app   = JFactory::getApplication('site');
		$menus = $app->getMenu('site');
		
		$activeMenu = $menus->getActive();
		$params     = JUDirectoryHelper::getParams();
		$homeItemId = JUDirectoryHelperRoute::getHomeMenuItemId();

		
		if (empty($query['Itemid']) && isset($query['view']) && $query['view'] != 'category' && $query['view'] != 'listing')
		{
			$query['Itemid'] = JUDirectoryHelperRoute::findJUDirectoryTreeItemId();
		}

		if (isset($query['view']))
		{
			$menuItem = $menus->getItem($query['Itemid']);

			
			if (isset($menuItem) && ($menuItem->component != 'com_judirectory' && $menuItem->id != $homeItemId))
			{
				unset($query['Itemid']);
			}
		}

		if (!$query || (!isset($query['view']) && !isset($query['task'])))
		{
			
			if (isset($query['start']))
			{
				$sefPageConfig = JApplication::stringURLSafe('page');
				$pageX         = JUDirectoryHelperRoute::getPage($query['start'], $activeMenu->query['view']);
				$segments[]    = $sefPageConfig . ':' . $pageX;

				unset($query['start']);
			}

			JUDirectoryHelperRoute::seoFormat($query, $params, $segments);

			$total = count($segments);

			for ($i = 0; $i < $total; $i++)
			{
				$segments[$i] = str_replace(':', '-', $segments[$i]);
			}

			if (isset($query['limit']))
			{
				unset($query['limit']);
			}

			
			return $segments;
		}

		$hasActiveMenu = false;
		if (is_object($activeMenu) && isset($activeMenu->query))
		{
			if (isset($query['Itemid']) && ($query['Itemid'] == $activeMenu->id))
			{
				$hasActiveMenu = JUDirectoryHelperRoute::compareQuery($activeMenu, $query);
			}
		}

		

		
		if (isset($query['view']) && $query['view'] == 'categories')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['id']))
				{
					if (isset($query['Itemid']))
					{
						if ($query['Itemid'] == $homeItemId)
						{
							
							$sefRootCategory = JApplication::stringURLSafe('root');
							$segments[]      = JApplication::stringURLSafe($sefRootCategory);
						}
					}

					
					$sefCategoriesConfig = JApplication::stringURLSafe('categories');
					$segments[]          = JApplication::stringURLSafe($sefCategoriesConfig);

					$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);

					$segments[] = $query['id'] . ":" . (isset($categoryObject->alias) ? $categoryObject->alias : '');

					unset($query['id']);
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'category')
		{
			if (!$hasActiveMenu)
			{
				$fullPathCategory = $params->get('sef_category_full_path', 0);
				if (isset($query['id']))
				{
					$segments = JUDirectoryHelperRoute::getCategorySegment($query['id'], $query, $fullPathCategory);
					if ($segments !== false)
					{
						unset($query['id']);
					}
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			if (isset($query['format']))
			{
				$segments[] = JApplication::stringURLSafe('rss');
				unset($query['format']);
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'advsearch')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = JApplication::stringURLSafe('root');
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('advanced-search');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'collection')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				if (isset($query['id']) && $query['id'])
				{
					if (isset($query['user_id']))
					{
						$user       = JFactory::getUser($query['user_id']);
						$userAlias  = JApplication::stringURLSafe($user->username);
						$segments[] = $query['user_id'] . ':' . $userAlias;
						unset($query['user_id']);
					}

					$segments[] = JApplication::stringURLSafe('collection');

					$collectionObject = JUDirectoryFrontHelper::getCollectionById($query['id']);

					$segments[] = $query['id'] . ':' . (isset($collectionObject->alias) ? $collectionObject->alias : '');
					unset($query['id']);

					JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
				}
				else
				{
					$segments[] = JApplication::stringURLSafe('collection');

					$segments[] = JApplication::stringURLSafe('new-collection');

					JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

					if (isset($query['id']))
					{
						unset($query['id']);
					}
				}
			}
			else
			{
				if (isset($query['user_id']))
				{
					unset($query['user_id']);
				}

				if (isset($query['id']))
				{
					unset($query['id']);
				}
			}

			if (isset($query['format']))
			{
				$segments[] = JApplication::stringURLSafe('rss');
				unset($query['format']);
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'collections')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = JApplication::stringURLSafe('root');
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				if (isset($query['id']))
				{
					$user       = JFactory::getUser($query['id']);
					$userAlias  = JApplication::stringURLSafe($user->username);
					$segments[] = $query['id'] . ':' . $userAlias;
					unset($query['id']);
				}

				$segments[] = JApplication::stringURLSafe('collections');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'commenttree' && isset($query['id']))
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = JApplication::stringURLSafe('root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments[] = JApplication::stringURLSafe('comment-tree');

			$commentObject = JUDirectoryFrontHelperComment::getCommentObject($query['id']);

			$commentAlias = JApplication::stringURLSafe($commentObject->title);
			$segments[]   = $query['id'] . ':' . $commentAlias;

			if (isset($query['tmpl']))
			{
				$segments[] = JApplication::stringURLSafe('component');
				unset($query['tmpl']);
			}

			unset($query['id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'contact' && isset($query['listing_id']))
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = JApplication::stringURLSafe('root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments   = JUDirectoryHelperRoute::getListingSegment($query['listing_id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('contact');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['listing_id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'dashboard')
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = JApplication::stringURLSafe('root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			if (isset($query['id']))
			{
				$user       = JFactory::getUser($query['id']);
				$userAlias  = JApplication::stringURLSafe($user->username);
				$segments[] = $query['id'] . ':' . $userAlias;
				unset($query['id']);
			}

			$segments[] = JApplication::stringURLSafe('dashboard');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'listing')
		{
			if (!$hasActiveMenu)
			{
				$seoLayout = true;
				if (isset($query['id']))
				{
					$segments = JUDirectoryHelperRoute::getListingSegment($query['id'], $query, $params);
					unset($query['id']);
				}

				if (isset($query['print']))
				{
					$seoLayout  = false;
					$segments[] = JApplication::stringURLSafe('print');
					unset($query['print']);
					unset($query['layout']);
					unset($query['tmpl']);
				}

				if ($seoLayout)
				{
					JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
				}
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'listings')
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = JApplication::stringURLSafe('root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments[] = JApplication::stringURLSafe('modal-listings');

			if (isset($query['tmpl']))
			{
				$segments[] = $query['tmpl'];
				unset($query['tmpl']);
			}

			if (isset($query['function']))
			{
				$segments[] = $query['function'];
				unset($query['function']);
			}

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		


		
		if (isset($query['view']) && $query['view'] == 'featured')
		{
			if (!$hasActiveMenu)
			{
				$addCategoryToSegment = true;
				if (isset($query['Itemid']))
				{
					if ($query['Itemid'] == $homeItemId)
					{
						
						$sefRootCategory = JApplication::stringURLSafe('root');
						$segments[]      = JApplication::stringURLSafe($sefRootCategory);

						if (isset($query['id']))
						{
							$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
							if ($categoryObject->level > 0)
							{
								$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
							}
							unset($query['id']);
						}

						$addCategoryToSegment = false;
					}
					else
					{
						$assignMenuFeatured = $menus->getItem($query['Itemid']);
						if ($assignMenuFeatured && isset($assignMenuFeatured->query) && $assignMenuFeatured->query['view'] == 'tree'
							&& isset($assignMenuFeatured->query['id'])
						)
						{
							if (isset($query['id']))
							{
								$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
								if ($assignMenuFeatured->query['id'] != $categoryObject->id)
								{
									$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
								}
								unset($query['id']);
							}

							$addCategoryToSegment = false;
						}
					}
				}

				if ($addCategoryToSegment)
				{
					if (isset($query['id']))
					{
						$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
						$segments[]     = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
						unset($query['id']);
					}
				}

				$segments[] = JApplication::stringURLSafe('featured');

				if (isset($query['all']))
				{
					if ($query['all'] == 1)
					{
						$segments[] = JApplication::stringURLSafe('all');
					}
					unset($query['all']);
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['all']))
				{
					unset($query['all']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			if (isset($query['format']))
			{
				$segments[] = JApplication::stringURLSafe('rss');
				unset($query['format']);
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'form' && isset($query['layout']) && $query['layout'] == 'edit' && (!isset($query['id']) || (isset($query['id']) && !$query['id'])))
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = JApplication::stringURLSafe('root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			if (isset($query['cat_id']))
			{
				$categoryObject = JUDirectoryHelper::getCategoryById($query['cat_id']);
				$segments[]     = $query['cat_id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
				unset($query['cat_id']);
			}
			else
			{
				$categoryRoot = JUDirectoryFrontHelperCategory::getRootCategory();
				if (is_object($categoryRoot))
				{
					$segments[] = $categoryRoot->id . ':' . (isset($categoryRoot->alias) ? $categoryRoot->alias : '');
				}
			}

			$segments[] = JApplication::stringURLSafe('new-listing');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'form' && isset($query['layout']) && $query['layout'] == 'edit' && isset($query['id']) && $query['id'])
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = JApplication::stringURLSafe('root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments = JUDirectoryHelperRoute::getListingSegment($query['id'], $query, $params);
			if (isset($query['approve']) && $query['approve'] == 1)
			{
				$segments[] = JApplication::stringURLSafe('approve');
				unset($query['approve']);
			}

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'listall')
		{
			if (!$hasActiveMenu)
			{
				$addCategoryToSegment = true;
				if (isset($query['Itemid']))
				{
					if ($query['Itemid'] == $homeItemId)
					{
						
						$sefRootCategory = JApplication::stringURLSafe('root');
						$segments[]      = JApplication::stringURLSafe($sefRootCategory);

						if (isset($query['id']))
						{
							$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
							if ($categoryObject->level > 0)
							{
								$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
								unset($query['id']);
							}
						}

						$addCategoryToSegment = false;
					}
					else
					{
						$assignMenuListAll = $menus->getItem($query['Itemid']);
						if ($assignMenuListAll && isset($assignMenuListAll->query) && $assignMenuListAll->query['view'] == 'tree'
							&& isset($assignMenuListAll->query['id'])
						)
						{
							if (isset($query['id']))
							{
								$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
								if ($assignMenuListAll->query['id'] != $categoryObject->id)
								{
									$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
								}
								unset($query['id']);
							}

							$addCategoryToSegment = false;
						}
					}
				}

				if ($addCategoryToSegment)
				{
					if (isset($query['id']))
					{
						$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
						$segments[]     = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
						unset($query['id']);
					}
				}

				$segments[] = JApplication::stringURLSafe('list-all');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}
			}

			if (isset($query['format']))
			{
				$segments[] = JApplication::stringURLSafe('rss');
				unset($query['format']);
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'listalpha')
		{
			if (!$hasActiveMenu)
			{
				$addCategoryToSegment = true;
				if (isset($query['Itemid']))
				{
					if ($query['Itemid'] == $homeItemId)
					{
						
						$sefRootCategory = JApplication::stringURLSafe('root');
						$segments[]      = JApplication::stringURLSafe($sefRootCategory);

						if (isset($query['id']))
						{
							$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
							if ($categoryObject->level > 0)
							{
								$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
							}
							unset($query['id']);
						}

						$addCategoryToSegment = false;
					}
					else
					{
						$assignMenuListAlpha = $menus->getItem($query['Itemid']);
						if ($assignMenuListAlpha && isset($assignMenuListAlpha->query) && $assignMenuListAlpha->query['view'] == 'tree'
							&& isset($assignMenuListAlpha->query['id'])
						)
						{
							if (isset($query['id']))
							{
								$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
								if ($assignMenuListAlpha->query['id'] != $categoryObject->id)
								{
									$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
								}
								unset($query['id']);
							}

							$addCategoryToSegment = false;
						}
					}
				}

				if ($addCategoryToSegment)
				{
					if (isset($query['id']))
					{
						$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
						$segments[]     = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
						unset($query['id']);
					}
				}

				$segments[] = JApplication::stringURLSafe('list-alpha');

				if (isset($query['alpha']))
				{
					$segments[] = $query['alpha'];
					unset($query['alpha']);
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['alpha']))
				{
					unset($query['alpha']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			if (isset($query['format']))
			{
				$segments[] = JApplication::stringURLSafe('rss');
				unset($query['format']);
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'maintenance')
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = JApplication::stringURLSafe('root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments[] = JApplication::stringURLSafe('maintenance');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'modcomment' && isset($query['layout']) && $query['layout'] == 'edit' && isset($query['id']) && $query['id'])
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = JApplication::stringURLSafe('root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments[] = JApplication::stringURLSafe('mod-comment');

			$commentObject = JUDirectoryFrontHelperComment::getCommentObject($query['id']);
			if (is_object($commentObject))
			{
				$commentAlias = JApplication::stringURLSafe($commentObject->title);
				$segments[]   = $query['id'] . ':' . $commentAlias;
				unset($query['id']);
			}

			if (isset($query['approve']) && $query['approve'])
			{
				$segments[] = JApplication::stringURLSafe('approve');
				unset($query['approve']);
			}

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['view']);
			unset($query['layout']);
		}

		
		if (isset($query['view']) && $query['view'] == 'modcomments')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = JApplication::stringURLSafe('root');
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('mod-comments');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'modlistings')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = JApplication::stringURLSafe('root');
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('mod-listings');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'modpermission' && isset($query['id']))
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = JApplication::stringURLSafe('root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments[] = JApplication::stringURLSafe('mod-permission');
			$segments[] = $query['id'];

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'modpermissions')
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = 'root';
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments[] = JApplication::stringURLSafe('mod-permissions');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'modpendingcomments')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('mod-pending-comments');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'modpendinglistings')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('mod-pending-listings');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'profile')
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = 'root';
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments[] = JApplication::stringURLSafe('profile');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'claim' && isset($query['listing_id']))
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = 'root';
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments   = JUDirectoryHelperRoute::getListingSegment($query['listing_id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('claim');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['listing_id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'report' && isset($query['listing_id']))
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = 'root';
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments   = JUDirectoryHelperRoute::getListingSegment($query['listing_id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('report');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['listing_id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'report' && isset($query['comment_id']))
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = 'root';
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments[]    = JApplication::stringURLSafe('comment');
			$commentObject = JUDirectoryFrontHelperComment::getCommentObject($query['comment_id']);

			$commentAlias = JApplication::stringURLSafe($commentObject->title);
			$segments[]   = $query['comment_id'] . ':' . $commentAlias;
			unset($query['comment_id']);

			$segments[] = JApplication::stringURLSafe('report');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['comment_id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'search')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				if (isset($query['cat_id']))
				{
					$categoryObject = JUDirectoryHelper::getCategoryById($query['cat_id']);
					$segments[]     = $query['cat_id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
					unset($query['cat_id']);
				}

				if (isset($query['sub_cat']))
				{
					$segments[] = JApplication::stringURLSafe('all');
					unset($query['sub_cat']);
				}

				$segments[] = JApplication::stringURLSafe('search');

				if (isset($query['searchword']))
				{
					$segments[] = $query['searchword'];
					unset($query['searchword']);
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['cat_id']))
				{
					unset($query['cat_id']);
				}

				if (isset($query['searchword']))
				{
					unset($query['searchword']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'searchby')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('search-by');

				if (isset($query['field_id']))
				{
					$fieldObject = JUDirectoryFrontHelperField::getFieldById($query['field_id']);
					$segments[]  = $query['field_id'] . ':' . (isset($fieldObject->alias) ? $fieldObject->alias : '');
					unset($query['field_id']);
				}

				if (isset($query['value']))
				{
					$segments[] = $query['value'];
					unset($query['value']);
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['field_id']))
				{
					unset($query['field_id']);
				}

				if (isset($query['value']))
				{
					unset($query['value']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'subscribe' && isset($query['listing_id']))
		{
			if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
			{
				
				$sefRootCategory = 'root';
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
			}

			$segments   = JUDirectoryHelperRoute::getListingSegment($query['listing_id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('guest-subscribe');

			JUDirectoryHelperRoute::seoLayout($query, $segments, $params);

			unset($query['listing_id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'tag' && isset($query['id']))
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('tag');

				$tagObject = JUDirectoryFrontHelper::getTagById($query['id']);

				$segments[] = $query['id'] . ':' . (isset($tagObject->alias) ? $tagObject->alias : '');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoFormat($query, $params, $segments);

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'tags')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('tags');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'topcomments')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('top-comments');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'customlist')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('custom-list');

				if (isset($query['id']))
				{
					if ($query['id'])
					{
						$customListObject = JUDirectoryHelper::getCustomListById($query['id']);
						$segments[]       = $query['id'] . ':' . (isset($customListObject->alias) ? $customListObject->alias : '');
					}
					unset($query['id']);
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoFormat($query, $params, $segments);

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'compare')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$segments[] = JApplication::stringURLSafe('compare');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'toplistings')
		{
			$addCategoryToSegment = true;
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']))
				{
					if ($query['Itemid'] == $homeItemId)
					{
						
						$sefRootCategory = 'root';
						$segments[]      = JApplication::stringURLSafe($sefRootCategory);

						if (isset($query['id']))
						{
							$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
							if ($categoryObject->level > 0)
							{
								$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
							}
							unset($query['id']);
						}

						$addCategoryToSegment = false;
					}
					else
					{
						$assignMenuTopListings = $menus->getItem($query['Itemid']);
						if ($assignMenuTopListings && isset($assignMenuTopListings->query) && $assignMenuTopListings->query['view'] == 'tree'
							&& isset($assignMenuTopListings->query['id'])
						)
						{
							if (isset($query['id']))
							{
								$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
								if ($assignMenuTopListings->query['id'] != $categoryObject->id)
								{
									$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
								}
								unset($query['id']);
							}

							$addCategoryToSegment = false;
						}
					}
				}

				if ($addCategoryToSegment)
				{
					if (isset($query['id']))
					{
						$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
						$segments[]     = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
						unset($query['id']);
					}
				}

				if (isset($query['ordertype']))
				{
					switch ($query['ordertype'])
					{
						case 'new' :
							$segments[] = JApplication::stringURLSafe('latest-listings');
							break;
						case 'featured' :
							$segments[] = JApplication::stringURLSafe('top-featured-listings');
							break;
						case 'recent_modified' :
							$segments[] = JApplication::stringURLSafe('recent-modified-listings');
							break;
						case 'recent_updated' :
							$segments[] = JApplication::stringURLSafe('recent-updated-listings');
							break;
						case 'popular' :
							$segments[] = JApplication::stringURLSafe('popular-listings');
							break;
						case 'most_rated' :
							$segments[] = JApplication::stringURLSafe('most-rated-listings');
							break;
						case 'top_rated' :
							$segments[] = JApplication::stringURLSafe('top-rated-listings');
							break;
						case 'latest_rated' :
							$segments[] = JApplication::stringURLSafe('latest-rated-listings');
							break;
						case 'most_commented' :
							$segments[] = JApplication::stringURLSafe('most-commented-listings');
							break;
						case 'latest_commented' :
							$segments[] = JApplication::stringURLSafe('latest-commented-listings');
							break;
						case 'recently_viewed' :
							$segments[] = JApplication::stringURLSafe('recent-viewed-listings');
							break;
						case 'alpha_ordered' :
							$segments[] = JApplication::stringURLSafe('alpha-ordered-listings');
							break;
						case 'random' :
							$segments[] = JApplication::stringURLSafe('random-listings');
							break;
						case 'random_fast' :
							$segments[] = JApplication::stringURLSafe('random-fast-listings');
							break;
						case 'random_featured' :
							$segments[] = JApplication::stringURLSafe('random-featured-listings');
							break;
						default:
							$segments[] = JApplication::stringURLSafe('latest-listings');
					}
					unset($query['ordertype']);
				}

				if (isset($query['all']))
				{
					if ($query['all'] == 1)
					{
						$segments[] = JApplication::stringURLSafe('all');
					}

					unset($query['all']);
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['ordertype']))
				{
					unset($query['ordertype']);
				}

				if (isset($query['all']))
				{
					unset($query['all']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoFormat($query, $params, $segments);

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'tree' && isset($query['id']))
		{
			if (!$hasActiveMenu)
			{
				$addCategoryToSegment = true;
				if (isset($query['Itemid']))
				{
					if ($query['Itemid'] == $homeItemId)
					{
						
						$sefRootCategory = 'root';
						$segments[]      = JApplication::stringURLSafe($sefRootCategory);

						if (isset($query['id']))
						{
							$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
							if ($categoryObject->level > 0)
							{
								$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
							}
							unset($query['id']);
						}

						$addCategoryToSegment = false;
					}
					else
					{
						$assignMenuTree = $menus->getItem($query['Itemid']);
						if ($assignMenuTree && isset($assignMenuTree->query) && $assignMenuTree->query['view'] == 'tree'
							&& isset($assignMenuTree->query['id'])
						)
						{
							if (isset($query['id']))
							{
								$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
								if ($assignMenuTree->query['id'] != $categoryObject->id)
								{
									$segments[] = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
								}
								unset($query['id']);
							}

							$addCategoryToSegment = false;
						}
					}
				}

				$segments[] = 'tree';

				if ($addCategoryToSegment)
				{
					if (isset($query['id']))
					{
						$categoryObject = JUDirectoryHelper::getCategoryById($query['id']);
						$segments[]     = $query['id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
					}
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoFormat($query, $params, $segments);

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['id']);
			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'usercomments')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				if (isset($query['id']))
				{
					$user       = JFactory::getUser($query['id']);
					$userAlias  = JApplication::stringURLSafe($user->username);
					$segments[] = $query['id'] . ':' . $userAlias;
					unset($query['id']);
				}
				$segments[] = JApplication::stringURLSafe('comments');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}
				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'userlistings')
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				if (isset($query['id']))
				{
					$user       = JFactory::getUser($query['id']);
					$userAlias  = JApplication::stringURLSafe($user->username);
					$segments[] = $query['id'] . ':' . $userAlias;
					unset($query['id']);
				}

				$segments[] = JApplication::stringURLSafe('listings');

				if (isset($query['filter']))
				{
					if ($query['filter'] == 'pending')
					{
						$segments[] = JApplication::stringURLSafe('pending');
					}
					elseif ($query['filter'] == 'unpublished')
					{
						$segments[] = JApplication::stringURLSafe('unpublished');
					}
					else
					{
						$segments[] = JApplication::stringURLSafe('published');
					}
					unset($query['filter']);
				}

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if (isset($query['id']))
				{
					unset($query['id']);
				}

				if (isset($query['filter']))
				{
					unset($query['filter']);
				}

				if (isset($query['layout']))
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoFormat($query, $params, $segments);

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['view']);
		}

		
		if (isset($query['view']) && $query['view'] == 'usersubscriptions' && isset($query['id']))
		{
			if (!$hasActiveMenu)
			{
				if (isset($query['Itemid']) && $query['Itemid'] == $homeItemId)
				{
					
					$sefRootCategory = 'root';
					$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				}

				$user       = JFactory::getUser($query['id']);
				$userAlias  = JApplication::stringURLSafe($user->username);
				$segments[] = $query['id'] . ':' . $userAlias;
				$segments[] = JApplication::stringURLSafe('user-subscriptions');

				JUDirectoryHelperRoute::seoLayout($query, $segments, $params);
			}
			else
			{
				if ($query['layout'])
				{
					unset($query['layout']);
				}
			}

			JUDirectoryHelperRoute::seoPagination($query, $params, $segments);

			unset($query['id']);
			unset($query['view']);
		}

		

		
		if (isset($query['task']) && $query['task'] == 'form.add')
		{
			if (isset($query['cat_id']))
			{
				$categoryObject = JUDirectoryHelper::getCategoryById($query['cat_id']);
				if (is_object($categoryObject))
				{
					$segments[] = $query['cat_id'] . ':' . (isset($categoryObject->alias) ? $categoryObject->alias : '');
					unset($query['cat_id']);
				}
			}

			$segments[] = JApplication::stringURLSafe('add');
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'form.edit' && isset($query['id']))
		{
			$segments   = JUDirectoryHelperRoute::getListingSegment($query['id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('edit');

			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

			unset($query['id']);
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'forms.delete' && isset($query['id']))
		{
			$segments   = JUDirectoryHelperRoute::getListingSegment($query['id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('delete');

			unset($query['id']);
			unset($query['task']);
		}

		if (isset($query['task']) && $query['task'] == 'forms.checkin' && isset($query['id']))
		{
			$segments   = JUDirectoryHelperRoute::getListingSegment($query['id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('checkin');

			unset($query['id']);
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'listing.removeCompare' && isset($query['listing_id']))
		{
			$segments   = JUDirectoryHelperRoute::getListingSegment($query['listing_id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('remove-compare');

			unset($query['listing_id']);
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'listing.removeCompare' && isset($query['all']) && $query['all'] == 1)
		{
			$segments[] = JApplication::stringURLSafe('remove-compare');
			$segments[] = JApplication::stringURLSafe('all');

			unset($query['all']);
			unset($query['task']);
		}


		
		if (isset($query['task']) && $query['task'] == 'modpendinglisting.edit' && isset($query['id']))
		{
			$segments = JUDirectoryHelperRoute::getListingSegment($query['id'], $query, $params);

			if (isset($query['approve']))
			{
				$segments[] = JApplication::stringURLSafe('approve');
				unset($query['approve']);
			}

			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

			unset($query['id']);
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'forms.unpublish' && isset($query['id']))
		{
			$segments   = JUDirectoryHelperRoute::getListingSegment($query['id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('unpublish');

			unset($query['id']);
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'forms.publish' && isset($query['id']))
		{
			$segments   = JUDirectoryHelperRoute::getListingSegment($query['id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('publish');

			unset($query['id']);
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'subscribe.save' && isset($query['listing_id']) && !isset($query['comment_id']))
		{
			$segments   = JUDirectoryHelperRoute::getListingSegment($query['listing_id'], $query, $params);
			$segments[] = JApplication::stringURLSafe('subscribe');

			unset($query['listing_id']);
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'listing.redirecturl')
		{
			if (isset($query['listing_id']))
			{
				$segments = JUDirectoryHelperRoute::getListingSegment($query['listing_id'], $query, $params);
				unset($query['listing_id']);
			}

			if (isset($query['field_id']))
			{
				$fieldObject = JUDirectoryFrontHelperField::getFieldById($query['field_id']);
				$segments[]  = $query['field_id'] . ':' . (isset($fieldObject->alias) ? $fieldObject->alias : '');
				unset($query['field_id']);
			}

			$segments[] = JApplication::stringURLSafe('redirect-url');

			unset($query['task']);
		}

		

		
		if (isset($query['task']) && $query['task'] == 'modcomment.edit' && isset($query['id']))
		{
			$segments[] = JApplication::stringURLSafe('comment');

			$commentObject = JUDirectoryFrontHelperComment::getCommentObject($query['id']);

			$commentAlias = JApplication::stringURLSafe($commentObject->title);
			$segments[]   = $query['id'] . ':' . $commentAlias;
			unset($query['id']);

			$segments[] = JApplication::stringURLSafe('edit');

			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'modpendingcomment.edit' && isset($query['id']))
		{
			$segments[] = JApplication::stringURLSafe('comment');

			$commentObject = JUDirectoryFrontHelperComment::getCommentObject($query['id']);

			$commentAlias = JApplication::stringURLSafe($commentObject->title);
			$segments[]   = $query['id'] . ':' . $commentAlias;
			unset($query['id']);

			$segments[] = JApplication::stringURLSafe('approve');

			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

			unset($query['id']);
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'subscribe.save' && isset($query['comment_id']))
		{
			$segments[] = JApplication::stringURLSafe('comment');

			$commentObject = JUDirectoryFrontHelperComment::getCommentObject($query['comment_id']);

			$commentAlias = JApplication::stringURLSafe($commentObject->title);
			$segments[]   = $query['comment_id'] . ':' . $commentAlias;
			unset($query['comment_id']);

			$segments[] = JApplication::stringURLSafe('subscribe');
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'subscribe.remove' && isset($query['sub_id']))
		{
			$subscriptionObject = JUDirectoryFrontHelper::getSubscriptionObject($query['sub_id']);
			if ($subscriptionObject->type == 'listing')
			{
				$segments = JUDirectoryHelperRoute::getListingSegment($subscriptionObject->item_id, $query, $params);

				$segments[] = JApplication::stringURLSafe('unsubscribe');

				$segments[] = $query['sub_id'];

				if ($query['code'])
				{
					$segments[] = $query['code'];
					unset($query['code']);
				}

				unset($query['listing_id']);
				unset($query['task']);

				unset($query['sub_id']);
				unset($query['task']);
			}
			elseif ($subscriptionObject->type == 'comment')
			{
				$segments[] = JApplication::stringURLSafe('comment');

				$commentObject = JUDirectoryFrontHelperComment::getCommentObject($subscriptionObject->item_id);
				if (is_object($commentObject))
				{
					$commentAlias = JApplication::stringURLSafe($commentObject->title);
					$segments[]   = $commentObject->id . ':' . $commentAlias;
				}

				$segments[] = JApplication::stringURLSafe('unsubscribe');

				$segments[] = $query['sub_id'];

				if ($query['code'])
				{
					$segments[] = $query['code'];
					unset($query['code']);
				}

				unset($query['sub_id']);
				unset($query['task']);
			}
		}

		
		if (isset($query['task']) && $query['task'] == 'listing.deleteComment' && isset($query['comment_id']))
		{
			$segments[] = JApplication::stringURLSafe('comment');

			$commentObject = JUDirectoryFrontHelperComment::getCommentObject($query['comment_id']);

			$commentAlias = JApplication::stringURLSafe($commentObject->title);
			$segments[]   = $query['comment_id'] . ':' . $commentAlias;
			unset($query['comment_id']);

			$segments[] = JApplication::stringURLSafe('delete');
			unset($query['task']);
		}

		

		
		if (isset($query['task']) && $query['task'] == 'collection.add')
		{
			$segments[] = JApplication::stringURLSafe('collection');
			$segments[] = JApplication::stringURLSafe('add');

			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'collection.edit' && isset($query['id']))
		{
			if (isset($query['user_id']))
			{
				$user       = JFactory::getUser($query['user_id']);
				$userAlias  = JApplication::stringURLSafe($user->username);
				$segments[] = $query['user_id'] . ':' . $userAlias;
				unset($query['user_id']);
			}

			$segments[] = JApplication::stringURLSafe('collection');

			$collectionObject = JUDirectoryFrontHelper::getCollectionById($query['id']);

			$segments[] = $query['id'] . ':' . (isset($collectionObject->alias) ? $collectionObject->alias : '');
			unset($query['id']);

			$segments[] = JApplication::stringURLSafe('edit');

			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'collections.delete' && isset($query['cid']))
		{
			$segments[] = JApplication::stringURLSafe('collection');

			$collectionObject = JUDirectoryFrontHelper::getCollectionById($query['cid']);

			$segments[] = $query['cid'] . ':' . (isset($collectionObject->alias) ? $collectionObject->alias : '');
			unset($query['cid']);

			$segments[] = JApplication::stringURLSafe('delete');

			unset($query['task']);
		}

		

		
		if (isset($query['task']) && $query['task'] == 'usersubscriptions.unsubscribe' && isset($query['sub_id']))
		{
			$segments[] = JApplication::stringURLSafe('user-subscriptions');
			$segments[] = $query['sub_id'];
			$segments[] = JApplication::stringURLSafe('unsubscribe');

			unset($query['sub_id']);
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'rawdata')
		{
			if (isset($query['listing_id']))
			{
				$segments = JUDirectoryHelperRoute::getListingSegment($query['listing_id'], $query, $params);
				unset($query['listing_id']);
			}

			if (isset($query['field_id']))
			{
				$fieldObject = JUDirectoryFrontHelperField::getFieldById($query['field_id']);

				$segments[] = $query['field_id'] . ':' . (isset($fieldObject->alias) ? $fieldObject->alias : '');
				unset($query['field_id']);
			}

			$segments[] = JApplication::stringURLSafe('raw-data');
			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'subscribe.activate')
		{
			$segments[] = JApplication::stringURLSafe('activate-subscription');

			if ($query['id'])
			{
				$segments[] = $query['id'];
			}

			if (isset($query['code']))
			{
				$segments[] = $query['code'];
			}

			unset($query['task']);
		}

		
		if (isset($query['task']) && $query['task'] == 'email.downloadattachment')
		{
			$segments[] = JApplication::stringURLSafe('email');
			$segments[] = JApplication::stringURLSafe('download-attachment');

			if (isset($query['mail_id']))
			{
				$segments[] = $query['mail_id'];
				unset($query['mail_id']);
			}

			if (isset($query['file']))
			{
				$segments[] = $query['file'];
				unset($query['file']);
			}

			if (isset($query['code']))
			{
				$segments[] = $query['code'];
				unset($query['code']);
			}

			unset($query['task']);
		}

		$total = count($segments);

		for ($i = 0; $i < $total; $i++)
		{
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}

		if (isset($query['limit']))
		{
			unset($query['limit']);
		}

		return $segments;
	}

	
	public function parse(&$segments)
	{
		$total = count($segments);
		$vars  = array();

		for ($i = 0; $i < $total; $i++)
		{
			$segments[$i] = preg_replace('/:/', '-', $segments[$i], 1);
		}

		$params     = JUDirectoryHelper::getParams();
		$app        = JFactory::getApplication('site');
		$menu       = $app->getMenu();
		$activeMenu = $menu->getActive();

		$indexLastSegment = $total - 1;
		$endSegment       = end($segments);

		
		$searchViewApproveComment = array_search(JApplication::stringURLSafe('mod-comment'), $segments);
		if ($searchViewApproveComment !== false)
		{
			$vars['view'] = 'modcomment';
			if (isset($segments[$searchViewApproveComment + 1]))
			{
				$vars['id'] = (int) $segments[$searchViewApproveComment + 1];
			}

			if (isset($segments[$searchViewApproveComment + 2]))
			{
				if ($segments[$searchViewApproveComment + 2] == JApplication::stringURLSafe('approve'))
				{
					$vars['approve'] = 1;
				}
			}

			$previousIndexSegment = $total - 1;

			if (isset($segments[$previousIndexSegment]))
			{
				$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				if ($isLayout)
				{
					$previousIndexSegment -= 1;
				}
			}

			return $vars;
		}

		
		if (isset($segments[0]) && $segments[0] == JApplication::stringURLSafe('comment'))
		{
			if (isset($segments[2]))
			{
				switch ($segments[2])
				{
					case JApplication::stringURLSafe('edit') :
						$vars['task'] = 'modcomment.edit';
						if (isset($segments[1]))
						{
							$vars['id'] = (int) $segments[1];
						}
						break;
					case JApplication::stringURLSafe('approve') :
						$vars['task'] = 'modpendingcomment.edit';
						if (isset($segments[1]))
						{
							$vars['id'] = (int) $segments[1];
						}
						break;
					case JApplication::stringURLSafe('subscribe') :
						$vars['task'] = 'subscribe.save';
						if (isset($segments[1]))
						{
							$vars['comment_id'] = (int) $segments[1];
						}
						break;
					case JApplication::stringURLSafe('unsubscribe') :
						$vars['task'] = 'subscribe.remove';
						if (isset($segments[3]))
						{
							$vars['sub_id'] = (int) $segments[3];
						}
						if (isset($segments[4]))
						{
							$vars['code'] = $segments[4];
						}
						break;
					case JApplication::stringURLSafe('delete') :
						$vars['task'] = 'listing.deleteComment';
						if (isset($segments[1]))
						{
							$vars['comment_id'] = (int) $segments[1];
						}
						break;
					default :
						break;
				}

				if (isset($vars['task']))
				{
					return $vars;
				}
			}
		}

		
		$searchViewReportComment = array_search(JApplication::stringURLSafe('comment'), $segments);
		if ($searchViewReportComment !== false)
		{
			
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewReportComment, $validArrayIndex))
			{
				if (isset($segments[$searchViewReportComment + 2]))
				{
					if ($segments[$searchViewReportComment + 2] == JApplication::stringURLSafe('report'))
					{
						$vars['view'] = 'report';
						if (isset($segments[$searchViewReportComment + 1]))
						{
							$vars['comment_id'] = (int) $segments[$searchViewReportComment + 1];
						}

						$previousIndexSegment = $total - 1;

						if (isset($segments[$previousIndexSegment]))
						{
							$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
							if ($isLayout)
							{
								$previousIndexSegment -= 1;
							}
						}

						return $vars;
					}
				}
			}
		}

		
		$searchViewModeratorPermission = array_search(JApplication::stringURLSafe('mod-permission'), $segments);
		if ($searchViewModeratorPermission !== false)
		{
			
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewModeratorPermission, $validArrayIndex))
			{
				$vars['view'] = 'modpermission';
				if (isset($segments[$searchViewModeratorPermission + 1]))
				{
					$vars['id'] = (int) $segments[$searchViewModeratorPermission + 1];
				}

				$previousIndexSegment = $total - 1;

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}


		
		$searchViewCustomList = array_search(JApplication::stringURLSafe('custom-list'), $segments);
		if ($searchViewCustomList !== false)
		{
			
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewCustomList, $validArrayIndex))
			{
				$vars['view'] = 'customlist';

				if (isset($segments[$searchViewCustomList + 1]))
				{
					$vars['id'] = (int) $segments[$searchViewCustomList + 1];
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
					{
						$vars['format'] = 'feed';
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}


		
		$searchViewCompare = array_search(JApplication::stringURLSafe('compare'), $segments);
		if ($searchViewCompare !== false)
		{
			
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewCompare, $validArrayIndex))
			{
				$vars['view'] = 'compare';

				$previousIndexSegment = $total - 1;

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		if (isset($segments[0]) && $segments[0] == JApplication::stringURLSafe('user-subscriptions'))
		{
			if (isset($segments[2]))
			{
				if ($segments[2] == JApplication::stringURLSafe('unsubscribe'))
				{
					$vars['task'] = 'usersubscriptions.unsubscribe';
					if (isset($segments[1]))
					{
						$vars['sub_id'] = (int) $segments[1];
					}

					return $vars;
				}
			}
		}

		
		if (isset($segments[0]) && $segments[0] == JApplication::stringURLSafe('email'))
		{
			if (isset($segments[1]) && $segments[1] == JApplication::stringURLSafe('download-attachment'))
			{
				$vars['task'] = 'email.downloadattachment';

				if (isset($segments[2]))
				{
					$vars['mail_id'] = (int) $segments[2];
				}

				if (isset($segments[3]))
				{
					$vars['file'] = $segments[3];
				}

				if (isset($segments[4]))
				{
					$vars['code'] = $segments[4];
				}

			}

			return $vars;
		}

		
		$searchViewModeratorPermissions = array_search(JApplication::stringURLSafe('mod-permissions'), $segments);
		if ($searchViewModeratorPermissions !== false)
		{
			
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewModeratorPermissions, $validArrayIndex))
			{
				$vars['view'] = 'modpermissions';

				$previousIndexSegment = $total - 1;

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewProfile = array_search(JApplication::stringURLSafe('profile'), $segments);
		if ($searchViewProfile !== false)
		{
			
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewProfile, $validArrayIndex))
			{
				$vars['view'] = 'profile';

				$previousIndexSegment = $total - 1;

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewModeratorPendingListings = array_search(JApplication::stringURLSafe('mod-pending-listings'), $segments);
		if ($searchViewModeratorPendingListings !== false)
		{
			
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewModeratorPendingListings, $validArrayIndex))
			{
				$vars['view'] = 'modpendinglistings';

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewModeratorPendingComments = array_search(JApplication::stringURLSafe('mod-pending-comments'), $segments);
		if ($searchViewModeratorPendingComments !== false)
		{
			
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewModeratorPendingComments, $validArrayIndex))
			{
				$vars['view'] = 'modpendingcomments';

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewModeratorComments = array_search(JApplication::stringURLSafe('mod-comments'), $segments);
		if ($searchViewModeratorComments !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewModeratorComments, $validArrayIndex))
			{
				$vars['view'] = 'modcomments';

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewModeratorListings = array_search(JApplication::stringURLSafe('mod-listings'), $segments);
		if ($searchViewModeratorListings !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewModeratorListings, $validArrayIndex))
			{
				$vars['view'] = 'modlistings';

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		if (isset($segments[0]) && $segments[0] == JApplication::stringURLSafe('subscribe'))
		{
			if (isset($segments[1]) && $segments[1] == JApplication::stringURLSafe('activate'))
			{
				$vars['task'] = 'subscribe.activate';

				if (isset($segments[2]))
				{
					$vars['code'] = $segments[2];
				}

				if (isset($segments[3]))
				{
					$vars['id'] = (int) $segments[3];
				}

				if (isset($segments[4]))
				{
					$vars['listing_id'] = (int) $segments[4];
				}

				return $vars;
			}
		}

		
		$searchViewSearch = array_search(JApplication::stringURLSafe('search'), $segments);
		if ($searchViewSearch !== false)
		{
			$validArrayIndex = array(0, 1, 2, 3);
			if (in_array($searchViewSearch, $validArrayIndex))
			{
				$vars['view'] = 'search';

				if (isset($segments[$searchViewSearch - 1]))
				{
					if ($segments[$searchViewSearch - 1] == JApplication::stringURLSafe('all'))
					{
						$vars['sub_cat'] = 1;
						if (isset($segments[$searchViewSearch - 2]))
						{
							$vars['cat_id'] = (int) $segments[$searchViewSearch - 2];
						}
					}
					else
					{
						$vars['cat_id'] = (int) $segments[$searchViewSearch - 1];
					}
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]) && $previousIndexSegment > $searchViewSearch)
				{
					$vars['searchword'] = $segments[$previousIndexSegment];
					$previousIndexSegment -= 1;
				}

				return $vars;
			}
		}

		
		$searchViewCategories = array_search(JApplication::stringURLSafe('categories'), $segments);
		if ($searchViewCategories !== false)
		{
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewCategories, $validArrayIndex))
			{
				$vars['view'] = 'categories';
				if (isset($segments[$searchViewCategories + 1]))
				{
					$vars['id'] = (int) $segments[$searchViewCategories + 1];
				}

				if (isset($segments[$searchViewCategories + 2]))
				{
					JUDirectoryHelperRoute::parseLayout($segments[$searchViewCategories + 2], $vars, $params);
				}

				return $vars;
			}
		}

		
		$searchViewAdvancedSearch = array_search(JApplication::stringURLSafe('advanced-search'), $segments);
		if ($searchViewAdvancedSearch !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewAdvancedSearch, $validArrayIndex))
			{
				$vars['view'] = 'advsearch';

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewCommentTree = array_search(JApplication::stringURLSafe('comment-tree'), $segments);
		if ($searchViewCommentTree !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewCommentTree, $validArrayIndex))
			{
				$vars['view'] = 'commenttree';

				if (isset($segments[$searchViewCommentTree + 1]))
				{
					$vars['id'] = (int) $segments[$searchViewCommentTree + 1];
				}

				$previousIndexSegment = $total - 1;

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewListings = array_search(JApplication::stringURLSafe('modal-listings'), $segments);
		if ($searchViewListings !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewListings, $validArrayIndex))
			{
				$vars['view'] = 'listings';

				if (isset($segments[$searchViewListings + 1]))
				{
					$vars['tmpl'] = $segments[$searchViewListings + 1];
				}

				if (isset($segments[$searchViewListings + 2]))
				{
					$vars['function'] = $segments[$searchViewListings + 2];
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewMaintenance = array_search(JApplication::stringURLSafe('maintenance'), $segments);
		if ($searchViewMaintenance !== false)
		{
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewMaintenance, $validArrayIndex))
			{
				$vars['view'] = 'maintenance';

				$previousIndexSegment = $total - 1;

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewSearchBy = array_search(JApplication::stringURLSafe('search-by'), $segments);
		if ($searchViewSearchBy !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewSearchBy, $validArrayIndex))
			{
				$vars['view'] = 'searchby';

				if (isset($segments[$searchViewSearchBy + 1]))
				{
					$vars['field_id'] = (int) $segments[$searchViewSearchBy + 1];
				}

				if (isset($segments[$searchViewSearchBy + 2]))
				{
					$vars['value'] = $segments[$searchViewSearchBy + 2];
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewTag = array_search(JApplication::stringURLSafe('tag'), $segments);
		if ($searchViewTag !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchViewTag, $validArrayIndex))
			{
				$vars['view'] = 'tag';

				if (isset($segments[$searchViewTag + 1]))
				{
					$vars['id'] = (int) $segments[$searchViewTag + 1];
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
					{
						$vars['format'] = 'feed';
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewTags = array_search(JApplication::stringURLSafe('tags'), $segments);
		if ($searchViewTags !== false)
		{
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewTags, $validArrayIndex))
			{
				$vars['view'] = 'tags';

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewTopComments = array_search(JApplication::stringURLSafe('top-comments'), $segments);
		if ($searchViewTopComments !== false)
		{
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewTopComments, $validArrayIndex))
			{
				$vars['view'] = 'topcomments';

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchViewTree = array_search(JApplication::stringURLSafe('tree'), $segments);
		if ($searchViewTree !== false)
		{
			$validArrayIndex = array(0, 1);
			if (in_array($searchViewTree, $validArrayIndex))
			{
				$vars['view']         = 'tree';
				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
					{
						$vars['format'] = 'feed';
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('root'))
					{
						$rootCategory = JUDirectoryFrontHelperCategory::getRootCategory();
						$vars['id']   = $rootCategory->id;
					}
					else
					{
						$vars['id'] = (int) $segments[$previousIndexSegment];
					}
					$previousIndexSegment -= 1;
				}
				else
				{
					if ($activeMenu && isset($activeMenu->query) && isset($activeMenu->query['view']) && isset($activeMenu->query['id']) &&
						$activeMenu->query['view'] == 'tree'
					)
					{
						$vars['id'] = $activeMenu->query['id'];
					}
				}

				return $vars;
			}
		}

		
		$orderTypeTopListings = array();
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('latest-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('top-featured-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('recent-modified-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('recent-updated-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('popular-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('most-rated-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('top-rated-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('latest-rated-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('most-commented-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('latest-commented-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('recent-viewed-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('alpha-ordered-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('random-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('random-fast-listings');
		
		$orderTypeTopListings[] = JApplication::stringURLSafe('random-featured-listings');

		if (!empty($orderTypeTopListings))
		{
			foreach ($orderTypeTopListings as $orderTypeTopListingItem)
			{
				$searchViewTopListings = array_search($orderTypeTopListingItem, $segments);
				if ($searchViewTopListings !== false)
				{
					break;
				}
			}

			if ($searchViewTopListings !== false)
			{
				$validArrayIndex = array(0, 1, 2);
				if (in_array($searchViewTopListings, $validArrayIndex))
				{
					$vars['view'] = 'toplistings';

					switch ($segments[$searchViewTopListings])
					{
						case JApplication::stringURLSafe('latest-listings'):
							$vars['ordertype'] = 'new';
							break;
						case JApplication::stringURLSafe('top-featured-listings'):
							$vars['ordertype'] = 'featured';
							break;
						case JApplication::stringURLSafe('recent-modified-listings'):
							$vars['ordertype'] = 'recent_modified';
							break;
						case JApplication::stringURLSafe('recent-updated-listings'):
							$vars['ordertype'] = 'recent_updated';
							break;
						case JApplication::stringURLSafe('popular-listings'):
							$vars['ordertype'] = 'popular';
							break;
						case JApplication::stringURLSafe('most-rated-listings'):
							$vars['ordertype'] = 'most_rated';
							break;
						case JApplication::stringURLSafe('top-rated-listings'):
							$vars['ordertype'] = 'top_rated';
							break;
						case JApplication::stringURLSafe('latest-rated-listings'):
							$vars['ordertype'] = 'latest_rated';
							break;
						case JApplication::stringURLSafe('most-commented-listings'):
							$vars['ordertype'] = 'most_commented';
							break;
						case JApplication::stringURLSafe('latest-commented-listings'):
							$vars['ordertype'] = 'latest_commented';
							break;
						case JApplication::stringURLSafe('recent-viewed-listings'):
							$vars['ordertype'] = 'recently_viewed';
							break;
						case JApplication::stringURLSafe('alpha-ordered-listings'):
							$vars['ordertype'] = 'alpha_ordered';
							break;
						case JApplication::stringURLSafe('random-listings'):
							$vars['ordertype'] = 'random';
							break;
						case JApplication::stringURLSafe('random-fast-listings'):
							$vars['ordertype'] = 'random_fast';
							break;
						case JApplication::stringURLSafe('random-featured-listings'):
							$vars['ordertype'] = 'random_featured';
							break;
						default:
							$vars['ordertype'] = 'new';
							break;
					}

					if (isset($segments[$searchViewTopListings - 1]))
					{
						if ($segments[$searchViewTopListings - 1] == JApplication::stringURLSafe('root'))
						{
							$rootCategory = JUDirectoryFrontHelperCategory::getRootCategory();
							$vars['id']   = $rootCategory->id;
						}
						else
						{
							$vars['id'] = (int) $segments[$searchViewTopListings - 1];
						}
					}
					else
					{
						if ($activeMenu && isset($activeMenu->query) && isset($activeMenu->query['view']) && isset($activeMenu->query['id']) &&
							$activeMenu->query['view'] == 'tree'
						)
						{
							$vars['id'] = $activeMenu->query['id'];
						}
					}

					if (isset($segments[$searchViewTopListings + 1]))
					{
						if ($segments[$searchViewTopListings + 1] == JApplication::stringURLSafe('all'))
						{
							$vars['all'] = 1;
						}
					}

					$previousIndexSegment = $total - 1;

					$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
					if ($isPaged)
					{
						$previousIndexSegment -= 1;
					}

					if (isset($segments[$previousIndexSegment]))
					{
						if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
						{
							$vars['format'] = 'feed';
							$previousIndexSegment -= 1;
						}
					}

					if (isset($segments[$previousIndexSegment]))
					{
						$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
						if ($isLayout)
						{
							$previousIndexSegment -= 1;
						}
					}

					return $vars;
				}
			}
		}

		
		$searchSefRedirectUrl = array_search(JApplication::stringURLSafe('redirect-url'), $segments);
		if ($searchSefRedirectUrl !== false)
		{
			$vars['task'] = 'listing.redirecturl';
			if (isset($segments[$searchSefRedirectUrl - 1]))
			{
				$vars['field_id'] = (int) $segments[$searchSefRedirectUrl - 1];
			}

			if (isset($segments[$searchSefRedirectUrl - 2]))
			{
				$vars['listing_id'] = (int) $segments[$searchSefRedirectUrl - 2];
			}

			return $vars;
		}

		
		$searchSefTaskRawData = array_search(JApplication::stringURLSafe('raw-data'), $segments);
		if ($searchSefTaskRawData !== false)
		{
			$vars['task'] = 'rawdata';

			if (isset($segments[$searchSefTaskRawData - 1]))
			{
				$vars['field_id'] = (int) $segments[$searchSefTaskRawData - 1];
			}

			if (isset($segments[$searchSefTaskRawData - 2]))
			{
				$vars['listing_id'] = (int) $segments[$searchSefTaskRawData - 2];
			}

			return $vars;
		}

		$searchSefCollection = array_search(JApplication::stringURLSafe('collection'), $segments);
		if ($searchSefCollection !== false)
		{
			if (isset($segments[$searchSefCollection + 1]))
			{
				if ($segments[$searchSefCollection + 1] == JApplication::stringURLSafe('add'))
				{
					$vars['task'] = 'collection.add';

					return $vars;
				}
			}
		}

		$searchSefCollection = array_search(JApplication::stringURLSafe('collection'), $segments);
		if ($searchSefCollection !== false)
		{
			if (isset($segments[$searchSefCollection + 2]))
			{
				if ($segments[$searchSefCollection + 2] == JApplication::stringURLSafe('edit'))
				{
					if (isset($segments[$searchSefCollection - 1]))
					{
						$vars['user_id'] = (int) $segments[$searchSefCollection - 1];
					}
					$vars['id']   = (int) $segments[$searchSefCollection + 1];
					$vars['task'] = 'collection.edit';

					return $vars;
				}
			}
		}

		$searchSefCollection = array_search(JApplication::stringURLSafe('collection'), $segments);
		if ($searchSefCollection !== false)
		{
			if (isset($segments[$searchSefCollection + 2]))
			{
				if ($segments[$searchSefCollection + 2] == JApplication::stringURLSafe('delete'))
				{
					$vars['cid']  = (int) $segments[$searchSefCollection + 1];
					$vars['task'] = 'collections.delete';

					return $vars;
				}
			}
		}

		$searchSefCollection = array_search(JApplication::stringURLSafe('collection'), $segments);
		if ($searchSefCollection !== false)
		{
			if (isset($segments[$searchSefCollection + 1]))
			{
				if ($segments[$searchSefCollection + 1] == JApplication::stringURLSafe('new-collection'))
				{
					$vars['id']   = 0;
					$vars['view'] = 'collection';
					JUDirectoryHelperRoute::parseLayout($segments[$searchSefCollection + 2], $vars, $params);

					return $vars;
				}
			}
		}

		$searchSefCollection = array_search(JApplication::stringURLSafe('collection'), $segments);
		if ($searchSefCollection !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchSefCollection, $validArrayIndex))
			{
				$vars['view'] = 'collection';
				if (isset($segments[$searchSefCollection - 1]))
				{
					if ($segments[$searchSefCollection - 1] != 'root')
					{
						$vars['user_id'] = (int) $segments[$searchSefCollection - 1];
					}
				}

				if (isset($segments[$searchSefCollection + 1]))
				{
					$vars['id'] = (int) $segments[$searchSefCollection + 1];
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
					{
						$vars['format'] = 'feed';
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchSefClaimListing = array_search(JApplication::stringURLSafe('claim'), $segments);
		if ($searchSefClaimListing !== false)
		{
			$vars['view'] = 'claim';
			if (isset($segments[$searchSefClaimListing - 1]))
			{
				$vars['listing_id'] = (int) $segments[$searchSefClaimListing - 1];
			}

			$previousIndexSegment = $total - 1;

			if (isset($segments[$previousIndexSegment]))
			{
				$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				if ($isLayout)
				{
					$previousIndexSegment -= 1;
				}
			}

			return $vars;
		}

		
		$searchSefReportListing = array_search(JApplication::stringURLSafe('report'), $segments);
		if ($searchSefReportListing !== false)
		{
			$vars['view'] = 'report';
			if (isset($segments[$searchSefReportListing - 1]))
			{
				$vars['listing_id'] = (int) $segments[$searchSefReportListing - 1];
			}

			$previousIndexSegment = $total - 1;

			if (isset($segments[$previousIndexSegment]))
			{
				$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				if ($isLayout)
				{
					$previousIndexSegment -= 1;
				}
			}

			return $vars;
		}

		
		$searchSefRemoveCompare = array_search(JApplication::stringURLSafe('remove-compare'), $segments);
		if ($searchSefRemoveCompare !== false)
		{
			$vars['task'] == 'listing.removeCompare';

			if (isset($segments[$searchSefRemoveCompare + 1]))
			{
				if ($segments[$searchSefRemoveCompare + 1] == JApplication::stringURLSafe('all'))
				{
					$vars['all'] = 1;

					return $vars;
				}
			}

			if (isset($segments[$searchSefRemoveCompare - 1]))
			{
				$vars['listing_id'] = (int) $segments[$searchSefRemoveCompare - 1];

				return $vars;
			}

			return $vars;
		}

		
		$searchSefSubscribeListingForGuest = array_search(JApplication::stringURLSafe('guest-subscribe'), $segments);
		if ($searchSefSubscribeListingForGuest !== false)
		{
			$vars['view'] = 'subscribe';
			if (isset($segments[$searchSefSubscribeListingForGuest - 1]))
			{
				$vars['listing_id'] = (int) $segments[$searchSefSubscribeListingForGuest - 1];
			}

			$previousIndexSegment = $total - 1;

			if (isset($segments[$previousIndexSegment]))
			{
				$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				if ($isLayout)
				{
					$previousIndexSegment -= 1;
				}
			}

			return $vars;
		}

		
		$searchSefListAll = array_search('list-all', $segments);
		if ($searchSefListAll !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchSefListAll, $validArrayIndex))
			{
				$vars['view'] = 'listall';
				if (isset($segments[$searchSefListAll - 1]))
				{
					if ($segments[$searchSefListAll - 1] == JApplication::stringURLSafe('root'))
					{
						$rootCategory = JUDirectoryFrontHelperCategory::getRootCategory();
						$vars['id']   = $rootCategory->id;
					}
					else
					{
						$vars['id'] = (int) $segments[$searchSefListAll - 1];
					}
				}
				else
				{
					if ($activeMenu && isset($activeMenu->query) && isset($activeMenu->query['view']) && isset($activeMenu->query['id']) &&
						$activeMenu->query['view'] == 'tree'
					)
					{
						$vars['id'] = $activeMenu->query['id'];
					}
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
					{
						$vars['format'] = 'feed';
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				return $vars;
			}
		}

		
		$searchSefListAlpha = array_search(JApplication::stringURLSafe('list-alpha'), $segments);
		if ($searchSefListAlpha !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchSefListAlpha, $validArrayIndex))
			{
				$vars['view'] = 'listalpha';
				if (isset($segments[$searchSefListAlpha - 1]))
				{
					if ($segments[$searchSefListAlpha - 1] == JApplication::stringURLSafe('root'))
					{
						$rootCategory = JUDirectoryFrontHelperCategory::getRootCategory();
						$vars['id']   = $rootCategory->id;
					}
					else
					{
						$vars['id'] = (int) $segments[$searchSefListAlpha - 1];
					}
				}
				else
				{
					if ($activeMenu && isset($activeMenu->query) && isset($activeMenu->query['view']) && isset($activeMenu->query['id']) &&
						$activeMenu->query['view'] == 'tree'
					)
					{
						$vars['id'] = $activeMenu->query['id'];
					}
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
					{
						$vars['format'] = 'feed';
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
					if ($isLayout)
					{
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($previousIndexSegment > $searchSefListAlpha)
					{
						$vars['alpha'] = $segments[$previousIndexSegment];
					}
				}

				return $vars;
			}
		}

		
		$searchSefFeatured = array_search(JApplication::stringURLSafe('featured'), $segments);
		if ($searchSefFeatured !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchSefFeatured, $validArrayIndex))
			{
				$vars['view'] = 'featured';
				if (isset($segments[$searchSefFeatured - 1]))
				{
					if ($segments[$searchSefFeatured - 1] == JApplication::stringURLSafe('root'))
					{
						$rootCategory = JUDirectoryFrontHelperCategory::getRootCategory();
						$vars['id']   = $rootCategory->id;
					}
					else
					{
						$vars['id'] = (int) $segments[$searchSefFeatured - 1];
					}
				}
				else
				{
					if ($activeMenu && isset($activeMenu->query) && isset($activeMenu->query['view']) && isset($activeMenu->query['id']) &&
						$activeMenu->query['view'] == 'tree'
					)
					{
						$vars['id'] = $activeMenu->query['id'];
					}
				}

				if (isset($segments[$searchSefFeatured + 1]))
				{
					if ($segments[$searchSefFeatured + 1] == JApplication::stringURLSafe('all'))
					{
						$vars['all'] = 1;
					}
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
					{
						$vars['format'] = 'feed';
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				}

				return $vars;
			}
		}

		
		$searchSefCollections = array_search(JApplication::stringURLSafe('collections'), $segments);
		if ($searchSefCollections !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchSefCollections, $validArrayIndex))
			{
				$vars['view'] = 'collections';
				if (isset($segments[$searchSefCollections - 1]))
				{
					if ($segments[$searchSefCollections - 1] != JApplication::stringURLSafe('root'))
					{
						$vars['id'] = (int) $segments[$searchSefCollections - 1];
					}
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
					{
						$vars['format'] = 'feed';
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				}

				return $vars;
			}
		}

		
		$searchSefDashboard = array_search(JApplication::stringURLSafe('dashboard'), $segments);
		if ($searchSefDashboard !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchSefDashboard, $validArrayIndex))
			{
				$vars['view'] = 'dashboard';
				if (isset($segments[$searchSefDashboard - 1]))
				{
					if ($segments[$searchSefDashboard - 1] != JApplication::stringURLSafe('root'))
					{
						$vars['id'] = (int) $segments[$searchSefDashboard - 1];
					}
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				}

				return $vars;
			}
		}

		
		$searchSefUserComments = array_search(JApplication::stringURLSafe('comments'), $segments);
		if ($searchSefUserComments !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchSefUserComments, $validArrayIndex))
			{
				$vars['view'] = 'usercomments';
				if (isset($segments[$searchSefUserComments - 1]))
				{
					if ($segments[$searchSefUserComments - 1] != JApplication::stringURLSafe('root'))
					{
						$vars['id'] = (int) $segments[$searchSefUserComments - 1];
					}
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				}

				return $vars;
			}
		}

		
		$searchSefUserListings = array_search(JApplication::stringURLSafe('listings'), $segments);
		if ($searchSefUserListings !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchSefUserListings, $validArrayIndex))
			{
				$vars['view'] = 'userlistings';
				if (isset($segments[$searchSefUserListings - 1]))
				{
					if ($segments[$searchSefUserListings - 1] != JApplication::stringURLSafe('root'))
					{
						$vars['id'] = (int) $segments[$searchSefUserListings - 1];
					}
				}

				if (isset($segments[$searchSefUserListings + 1]))
				{
					if ($segments[$searchSefUserListings + 1] == JApplication::stringURLSafe('published'))
					{
						$vars['filter'] = 'published';
					}
					elseif ($segments[$searchSefUserListings + 1] == JApplication::stringURLSafe('unpublished'))
					{
						$vars['filter'] = 'unpublished';
					}
					elseif ($segments[$searchSefUserListings + 1] == JApplication::stringURLSafe('pending'))
					{
						$vars['filter'] = 'pending';
					}
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					if ($segments[$previousIndexSegment] == JApplication::stringURLSafe('rss'))
					{
						$vars['format'] = 'feed';
						$previousIndexSegment -= 1;
					}
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				}

				return $vars;
			}
		}

		
		$searchSefUserSubscriptions = array_search(JApplication::stringURLSafe('user-subscriptions'), $segments);
		if ($searchSefUserSubscriptions !== false)
		{
			$validArrayIndex = array(0, 1, 2);
			if (in_array($searchSefUserSubscriptions, $validArrayIndex))
			{
				$vars['view'] = 'usersubscriptions';

				if (isset($segments[$searchSefUserSubscriptions - 1]))
				{
					if ($segments[$searchSefUserSubscriptions - 1] != JApplication::stringURLSafe('root'))
					{
						$vars['id'] = (int) $segments[$searchSefUserSubscriptions - 1];
					}
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				}

				return $vars;
			}
		}

		$searchSefSearchBy = array_search(JApplication::stringURLSafe('search-by'), $segments);
		if ($searchSefSearchBy !== false)
		{
			$validArrayIndex = array(0, 1);
			if (in_array($searchSefSearchBy, $validArrayIndex))
			{
				$vars['view'] = 'searchby';
				if (isset($segments[$searchSefSearchBy + 1]))
				{
					$vars['field_id'] = (int) $segments[$searchSefSearchBy + 1];
				}

				if (isset($segments[$searchSefSearchBy + 2]))
				{
					$vars['value'] = $segments[$searchSefSearchBy + 2];
				}

				$previousIndexSegment = $total - 1;

				$isPaged = JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);
				if ($isPaged)
				{
					$previousIndexSegment -= 1;
				}

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				}

				return $vars;
			}
		}

		
		$searchSefContact = array_search(JApplication::stringURLSafe('contact'), $segments);
		if ($searchSefContact !== false)
		{
			if ($searchSefContact == $indexLastSegment || $searchSefContact == ($indexLastSegment - 1))
			{
				$vars['view'] = 'contact';

				if (isset($segments[$searchSefContact - 1]))
				{
					$vars['listing_id'] = (int) $segments[$searchSefContact - 1];
				}

				$previousIndexSegment = $total - 1;

				if (isset($segments[$previousIndexSegment]))
				{
					$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
				}

				return $vars;
			}
		}

		$searchSefCheckIn = array_search(JApplication::stringURLSafe('checkin'), $segments);
		if ($searchSefCheckIn !== false)
		{
			$vars['task'] = 'forms.checkin';
			if (isset($segments[$searchSefCheckIn - 1]))
			{
				$vars['id'] = (int) $segments[$searchSefCheckIn - 1];
			}

			return $vars;
		}

		$searchSefAdd = array_search(JApplication::stringURLSafe('add'), $segments);
		if ($searchSefAdd !== false)
		{
			$vars['task'] = 'form.add';
			if (isset($segments[$searchSefAdd - 1]))
			{
				$vars['cat_id'] = (int) $segments[$searchSefAdd - 1];
			}

			return $vars;
		}

		$searchSefEdit = array_search(JApplication::stringURLSafe('edit'), $segments);
		if ($searchSefEdit !== false)
		{
			$vars['task'] = 'form.edit';
			if (isset($segments[$searchSefEdit - 1]))
			{
				$vars['id'] = (int) $segments[$searchSefEdit - 1];
			}

			return $vars;
		}

		$searchSefDelete = array_search(JApplication::stringURLSafe('delete'), $segments);
		if ($searchSefDelete !== false)
		{
			$vars['task'] = 'forms.delete';
			if (isset($segments[$searchSefDelete - 1]))
			{
				$vars['id'] = (int) $segments[$searchSefDelete - 1];
			}

			return $vars;
		}

		$searchNewListing = array_search(JApplication::stringURLSafe('new-listing'), $segments);
		if ($searchNewListing !== false)
		{
			$vars['view']   = 'form';
			$vars['layout'] = 'edit';
			if (isset($segments[$searchNewListing - 1]))
			{
				$vars['cat_id'] = (int) $segments[$searchNewListing - 1];
			}

			return $vars;
		}

		$searchSefApprove = array_search(JApplication::stringURLSafe('approve'), $segments);
		if ($searchSefApprove !== false)
		{
			if ($searchSefApprove == $indexLastSegment)
			{
				$vars['task']    = 'modpendinglisting.edit';
				$vars['approve'] = 1;
			}
			else
			{
				$vars['view']    = 'form';
				$vars['layout']  = 'edit';
				$vars['approve'] = 1;
			}

			if (isset($segments[$searchSefApprove - 1]))
			{
				$vars['id'] = (int) $segments[$searchSefApprove - 1];
			}

			return $vars;
		}

		$searchSefPublish = array_search(JApplication::stringURLSafe('publish'), $segments);
		if ($searchSefPublish !== false)
		{
			$vars['task'] = 'forms.publish';
			if (isset($segments[$searchSefPublish - 1]))
			{
				$vars['id'] = (int) $segments[$searchSefPublish - 1];
			}

			return $vars;
		}

		$searchSefUnPublish = array_search(JApplication::stringURLSafe('unpublish'), $segments);
		if ($searchSefUnPublish !== false)
		{
			$vars['task'] = 'forms.unpublish';
			if (isset($segments[$searchSefUnPublish - 1]))
			{
				$vars['id'] = (int) $segments[$searchSefUnPublish - 1];
			}

			return $vars;
		}

		$searchSefSubscribe = array_search(JApplication::stringURLSafe('subscribe'), $segments);
		if ($searchSefSubscribe !== false)
		{
			$vars['task'] = 'subscribe.save';
			if (isset($segments[$searchSefSubscribe - 1]))
			{
				$vars['listing_id'] = (int) $segments[$searchSefSubscribe - 1];
			}

			return $vars;
		}

		$searchSefUnSubscribe = array_search(JApplication::stringURLSafe('unsubscribe'), $segments);
		if ($searchSefUnSubscribe !== false)
		{
			$vars['task'] = 'subscribe.remove';

			if (isset($segments[$searchSefUnSubscribe + 1]))
			{
				$vars['sub_id'] = (int) $segments[$searchSefUnSubscribe + 1];
			}

			if (isset($segments[$searchSefUnSubscribe + 2]))
			{
				$vars['code'] = $segments[$searchSefUnSubscribe + 2];
			}

			return $vars;
		}

		
		$previousIndexSegment = $indexLastSegment;

		
		if (isset($segments[$previousIndexSegment]))
		{
			$isPaged = preg_match('/' . preg_quote(JApplication::stringURLSafe('page') . '-') . '[0-9]*+/', $segments[$previousIndexSegment]);
			if ($isPaged)
			{
				if ($indexLastSegment == 0)
				{
					if (is_object($activeMenu) && $activeMenu->component == 'com_judirectory')
					{
						$vars = $activeMenu->query;
						JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);

						return $vars;
					}
				}
				$previousIndexSegment -= 1;
			}
		}

		
		if (isset($segments[$previousIndexSegment]))
		{
			$isFeed = $segments[$previousIndexSegment] == JApplication::stringURLSafe('rss') ? true : false;
			if ($isFeed)
			{
				$vars['format'] = 'feed';
				if ($indexLastSegment == 0)
				{
					if (is_object($activeMenu) && $activeMenu->component == 'com_judirectory')
					{
						$vars           = $activeMenu->query;
						$vars['format'] = 'feed';

						return $vars;
					}
				}
				$previousIndexSegment -= 1;
			}
		}

		
		if (isset($segments[$previousIndexSegment]))
		{
			$isLayout = JUDirectoryHelperRoute::parseLayout($segments[$previousIndexSegment], $vars, $params);
			if ($isLayout)
			{
				$previousIndexSegment -= 1;
			}
		}

		
		if (!empty($segments))
		{
			$reverseSegments = array_reverse($segments);
			foreach ($reverseSegments as $segmentItemKey => $segmentItem)
			{
				if (preg_match('/^\d+\-.+/', $segmentItem))
				{
					$indexAlias = $indexLastSegment - $segmentItemKey;
					break;
				}
			}

			if (isset($indexAlias) && isset($segments[$indexAlias]))
			{
				if (strpos($segments[$indexAlias], '-') === false)
				{
					$itemId    = (int) $segments[$indexAlias];
					$itemAlias = substr($segments[$indexAlias], strlen($itemId) + 1);
				}
				else
				{
					list($itemId, $itemAlias) = explode('-', $segments[$indexAlias], 2);
				}

				if (is_numeric($itemId))
				{
					$categoryObject = JUDirectoryHelper::getCategoryById($itemId);
					if (is_object($categoryObject) && isset($categoryObject->alias) && $categoryObject->alias == $itemAlias)
					{
						$vars['view'] = 'category';
						$vars['id']   = $itemId;

						JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);

						return $vars;
					}

					$listingObject = JUDirectoryHelper::getListingById($itemId);
					if (is_object($listingObject) && isset($listingObject->alias) && $listingObject->alias == $itemAlias)
					{
						$vars['id'] = $itemId;
						if (isset($vars['layout']))
						{
							if ($vars['layout'] == 'edit')
							{
								$vars['view'] = 'form';
							}
							else
							{
								$vars['view'] = 'listing';
							}
						}

						if (!isset($vars['view']))
						{
							$vars['view'] = 'listing';
						}

						if ($vars['view'] == 'listing')
						{
							if (isset($segments[$indexAlias + 1]))
							{
								if ($segments[$indexAlias + 1] == JApplication::stringURLSafe('print'))
								{
									$vars['print']  = 1;
									$vars['tmpl']   = 'component';
									$vars['layout'] = 'print';
								}
							}
						}

						JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);

						return $vars;
					}

					if (is_object($categoryObject) && isset($categoryObject->id) && $categoryObject->id)
					{
						$vars['view'] = 'category';
						$vars['id']   = $itemId;

						JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);

						return $vars;
					}

					if (is_object($listingObject) && isset($listingObject->id) && $listingObject->id)
					{
						$vars['id'] = $itemId;

						if (isset($vars['layout']))
						{
							if ($vars['layout'] == 'edit')
							{
								$vars['view'] = 'form';
							}
							else
							{
								$vars['view'] = 'listing';
							}
						}

						if (!isset($vars['view']))
						{
							$vars['view'] = 'listing';
						}

						if ($vars['view'] == 'listing')
						{
							if (isset($segments[$indexAlias + 1]))
							{
								if ($segments[$indexAlias + 1] == JApplication::stringURLSafe('print'))
								{
									$vars['print']  = 1;
									$vars['tmpl']   = 'component';
									$vars['layout'] = 'print';
								}
								elseif ($segments[$indexAlias + 1] == JApplication::stringURLSafe('changelogs'))
								{
									$vars['layout'] = 'changelogs';
								}
								elseif ($segments[$indexAlias + 1] == JApplication::stringURLSafe('versions'))
								{
									$vars['layout'] = 'versions';
								}
							}
						}

						JUDirectoryHelperRoute::parsePagination($vars, $segments, $params);

						return $vars;
					}
				}
			}
		}

		if (is_object($activeMenu) && $activeMenu->component == 'com_judirectory')
		{
			$vars = $activeMenu->query;
		}

		return $vars;
	}
}


function JUDirectoryBuildRoute(&$query)
{
	$router = new JUDirectoryRouter;

	return $router->build($query);
}

function JUDirectoryParseRoute($segments)
{
	$router = new JUDirectoryRouter;

	return $router->parse($segments);
}

