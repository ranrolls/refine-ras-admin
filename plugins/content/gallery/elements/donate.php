<?php
/**
 * @package Content - Gallery for K2
 * @version 1.2.0
 * @subpackage Plugins - Content
 * @copyright Copyright (C) 2012 JLEX Team - All rights reserved. (http://www.joomla-extensions.info)
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author JLEX Team
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.form.formfield' );
class JFormFieldDonate extends JFormField {
	protected $type = 'Donate';
	protected function getInput() {
		$html = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XRE2BDPAZNCGN" target="_blank"><img src="' . JUri::root () . 'plugins/content/gallery/assets/PayPal-Donations_Button.png" /></a>';
		return $html;
	}
}