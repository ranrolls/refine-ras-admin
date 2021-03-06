<?php
/**
 * @version: $Id: editor.php 4387 2015-02-19 12:24:35Z Radek Suski $
 * @package: SobiPro Library

 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET

 * @copyright Copyright (C) 2006 - 2015 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license GNU/LGPL Version 3
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License version 3 as published by the Free Software Foundation, and under the additional terms according section 7 of GPL v3.
 * See http://www.gnu.org/licenses/lgpl.html and http://sobipro.sigsiu.net/licenses.

 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

 * $Date: 2015-02-19 13:24:35 +0100 (Thu, 19 Feb 2015) $
 * $Revision: 4387 $
 * $Author: Radek Suski $
 * $HeadURL: file:///opt/svn/SobiPro/Component/branches/SobiPro-1.1/Site/lib/cms/joomla15/html/editor.php $
 */

/**
 * @author Radek Suski
 * @version 1.0
 * @created 21-Jan-2009 5:51:03 PM
 */
class SPCMSEditor
{
	/**
	 * @param	string	The control name
	 * @param	string	The contents of the text area
	 * @param	string	The width of the text area (px or %)
	 * @param	string	The height of the text area (px or %)
	 * @param	boolean	True and the editor buttons will be displayed
	 * @param	array	Associative array of editor parameters
	 * @return string
	 */
	public function display( $name, $html, $width, $height, $buttons = false, $params = array() )
	{
		if( SPRequest::cmd( 'format' ) != 'raw' ) {
			return JFactory::getEditor()->display( $name, $html, $width, $height, 0, 0, $buttons, $params );
		}
	}
}
