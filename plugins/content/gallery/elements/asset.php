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

class JFormFieldAsset extends JFormField {
	protected $type = 'Asset';
	
	protected function getInput() {
		$element = $this->element ['element'];
		if ($element == 'fb') {
			$html = '<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FJLexArt&amp;width=240&amp;height=75&amp;colorscheme=light&amp;show_faces=false&amp;header=true&amp;stream=false&amp;show_border=false&amp;appId=257802371026308" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:240px; height:75px;" allowTransparency="true"></iframe>';
		} elseif ($element == 'link') {
			$html = '<a href="' . $this->element ['value'] . '" target="_blank">' . $this->element ['value'] . '</a>';
		}
		return $html;
	}
}