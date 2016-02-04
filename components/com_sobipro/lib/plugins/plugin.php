<?php
/**
 * @version: $Id: plugin.php 4387 2015-02-19 12:24:35Z Radek Suski $
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
 * $HeadURL: file:///opt/svn/SobiPro/Component/branches/SobiPro-1.1/Site/lib/plugins/plugin.php $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );

/**
 * @author Radek Suski
 * @version 1.0
 * @updated 13-Feb-2010 14:11:22
 */
abstract class SPApplication extends SPObject
{
	/**
	 * to check if the plugin have implementation for the called action
	 * @param string $action
	 * @return bool
	 */
	abstract function provide( $action );

	/**
	 * @var string
	 */
	protected $id = null;

	/**
	 * @param string $id - unique id string of the plugin
	 * @return \SPApplication
	 */
	public function __construct( $id )
	{
		$this->id = $id;
	}
}
// well, ... hmmm - shit happens
abstract class SPPlugin extends SPApplication {}
