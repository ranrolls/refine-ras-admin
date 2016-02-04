 <?php
/**
* @version		$Id:fb.php  1 2015-06-04 06:35:13Z  $
* @package		Fb
* @subpackage 	Tables
* @copyright	Copyright (C) 2015, . All rights reserved.
* @license #
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Jimtawl TableFb class
*
* @package		Fb
* @subpackage	Tables
*/
class TableFb extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	public function __construct(& $db) 
	{
		parent::__construct('#__fandbstartup_fb', 'id', $db);
	}

	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	public function bind($array, $ignore = '')
	{ 
		
		return parent::bind($array, $ignore);		
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	public function check()
	{
		if ($this->id === 0) {
			//get next ordering

			$this->ordering = $this->getNextOrder();

		}


		/** check for valid name */
		/**
		if (trim($this->title) == '') {
			$this->setError(JText::_('Your Fb must contain a title.')); 
			return false;
		}
		**/		

		return true;
	}
}
 