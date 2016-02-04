<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

?>
 

<div class="lan_breadcrumb">
<ol class="breadcrumb<?php echo $moduleclass_sfx; ?>">
	<?php
	if ($params->get('showHere', 1))
	{
		echo '<span>' . JText::_('MOD_BREADCRUMBS_HERE') . '&#160;</span>';
	}
	else
	{
		echo '<li><i class="fa fa-home"></i></li>';
	}

	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i++)
	{
		if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link == $list[$i - 1]->link)
		{
			unset($list[$i]);
		}
	}

	end($list);
	$last_item_key = key($list);
	prev($list);
	$penult_item_key = key($list);

	$show_last = $params->get('showLast', 1);

	foreach ($list as $key => $item) {
		if ($key != $last_item_key) {

if($item->name != 'Restaurant Association' ) {
			echo '<li>';
			if (!empty($item->link)) {
				echo '<a href="' . $item->link . '" class="pathway">' . $item->name . '</a>';
			} else {
				echo $item->name;
			}
			echo '</li>';
}

		} elseif ($show_last) {
			if($item->name == 'Categories')
			{

			//echo '<li class="active">' . 'Directory'. '</li>';
			}
			else
			{
				echo '<li class="active">' . $item->name . '</li>';
if($_SERVER['REQUEST_URI'] == '/register')
				{
				echo '<li class="active">' . 'Register' . '</li>';
				}

else if($item->name == 'Register')
				{
				echo '<li class="active">My Account</li>';
				}
 
 


			}
		}
	}
	?>
</ol>
</div>

<div class="lan_page_title">
<?php if ($params->get('showHere', 1))
	
	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i ++)
	{
		if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i-1]->link) && $list[$i]->link == $list[$i-1]->link)
		{
			unset($list[$i]);
		}
	}

	// Find last and penultimate items in breadcrumbs list
	end($list);
	$last_item_key = key($list);
	prev($list);
	$penult_item_key = key($list);

	// Generate the trail
	foreach ($list as $key=>$item) :
	// Make a link if not the last item in the breadcrumbs
	$show_last = $params->get('showLast', 1);
	if ($key != $last_item_key)
	{


	}
	elseif ($show_last)
	{

//echo $item->name;
		// Render last item if reqd.
		if($item->name == 'Categories') 
			{
				
			echo '<span>' . 'Directory' . '</span>';
			}
             

               else if($item->name == 'Register')
			{
				
			echo '<span>' . 'My Account' . '</span>';
			}
                       
			else
			{
				echo '<span>' . $item->name . '</span>';
			}
	}

 


	endforeach; ?>
</div>