<?php
/**
 * @package   TJ Set Generator Tag for Joomla! 3.0+
 * @type      Plugin (System)
 * @filename  tjsetgeneratortag.php
 * @folder    <root>/plugins/system/tjsetgeneratortag
 * @version   1.0.0
 * @author    ToolJoom
 * @website   http://www.tooljoom.com
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @copyright (C) 2014 ToolJoom
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
**/

defined('_JEXEC') or die;

class plgSystemTJSetGeneratorTag extends JPlugin
{
	public function onBeforeRender()
	{
		$doc = JFactory::getDocument();

		$tag = $this->params->get('tag', '');

		$doc->setGenerator($tag);
	}
}