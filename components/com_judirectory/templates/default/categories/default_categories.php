<?php/** * ------------------------------------------------------------------------ * JUDirectory for Joomla 2.5, 3.x * ------------------------------------------------------------------------ * * @copyright      Copyright (C) 2010-2015 JoomUltra Co., Ltd. All Rights Reserved. * @license        GNU General Public License version 2 or later; see LICENSE.txt * @author         JoomUltra Co., Ltd * @website        http://www.joomultra.com * @----------------------------------------------------------------------@ */// No direct access to this filedefined('_JEXEC') or die('Restricted access');?><ul class="list-group">	<?php	$newParentId = $this->new_parent_id;	
foreach ($this->all_categories AS $category)	{		

if ($category->parent_id == $newParentId)		{			
?>		
	<li class="list-group-item">				
<i class="fa fa-caret-right"></i>				
<a href="<?php echo JRoute::_(JUDirectoryHelperRoute::getCategoryRoute($category->id)); ?>">					

<?php echo $category->title; ?>				
</a>
				
<?php 

/*?><?php				
if ($this->params->get('all_categories_show_total_subcategories', 1) || $this->params->get('all_categories_show_total_listings', 1))				
{					$totalCatsListings = array();					
if ($this->params->get('all_categories_show_total_subcategories', 1))					
{						
$totalCatsListings[]    = '<span class="subcat-count"><span>' . $category->total_nested_categories . '</span> ' . JText::_('COM_JUDIRECTORY_SUB_CATEGORIES') . '</span>';					
}					
if ($this->params->get('all_categories_show_total_listings', 1))					
{						
$totalCatsListings[] = '<span class="listing-count"><span>' . $category->total_listings . '</span> ' . JText::_('COM_JUDIRECTORY_LISTINGS') . '</span>';					}					echo '<small>(' . implode(" / ", $totalCatsListings) . ')</small>';				
}				if ($category->total_childs > 0)				
{					$this->new_parent_id = $category->id;					echo $this->loadTemplate('categories');				
}				?><?php */?>			

</li>		
<?php		
}	
} ?></ul>