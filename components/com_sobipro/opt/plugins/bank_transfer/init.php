<?php
/**
 * @version: $Id: init.php 4387 2015-02-19 12:24:35Z Radek Suski $
 * @package: SobiPro Component for Joomla!
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * @copyright Copyright (C) 2006 - 2015 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license GNU/GPL Version 3
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License version 3 as published by the Free Software Foundation, and under the additional terms according section 7 of GPL v3.
 * See http://www.gnu.org/licenses/gpl.html and http://sobipro.sigsiu.net/licenses.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * $Date: 2015-02-19 13:24:35 +0100 (Thu, 19 Feb 2015) $
 * $Revision: 4387 $
 * $Author: Radek Suski $
 * $HeadURL: file:///opt/svn/SobiPro/Component/branches/SobiPro-1.1/Site/opt/plugins/bank_transfer/init.php $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 27-Nov-2009 17:10:15
 */
class SPPBankTransfer extends SPPlugin
{
	/* (non-PHPdoc)
	 * @see Site/lib/plugins/SPPlugin#provide($action)
	 */
	public function provide( $action )
	{
		return
				$action == 'PaymentMethodView' ||
				$action == 'AppPaymentMessageSend';
	}

	public function AppPaymentMessageSend( &$methods, $entry, &$payment )
	{
		return $this->PaymentMethodView( $methods, $entry, $payment );
	}

	public static function admMenu( &$links )
	{
		$links[ Sobi::Txt( 'APP.BANK_TRANSFER' ) ] = 'bank_transfer';
	}

	/**
	 * This function have to add own string into the given array
	 * Basically: $methods[ $this->id ] = "Some String To Output";
	 * Optionally the value can be also SobiPro Arr2XML array.
	 * Check the documentation for more information
	 * @param array $methods
	 * @param SPEntry $entry
	 * @param array $payment
	 * @return void
	 */
	public function PaymentMethodView( &$methods, $entry, &$payment )
	{
		$bankdata = SPLang::getValue( 'bankdata', 'plugin', Sobi::Section() );
		$bankdata = SPLang::replacePlaceHolders( $bankdata, array( 'entry' => $entry ) );
		$methods[ $this->id ] = array(
			'content' => SPLang::clean( $bankdata ),
			'title' => Sobi::Txt( 'APP.PBT.PAY_TITLE' )
		);
	}
}
