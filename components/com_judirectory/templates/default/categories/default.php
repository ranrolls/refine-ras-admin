<style type="text/css">
.sz-search.cf.width {margin-top: 0px !important;}
</style>
<?php

/**

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

?>



<div id="judir-container" class="jubootstrap component judir-container view-categories <?php echo $this->pageclass_sfx; ?>">

	<?php if ($this->params->get('show_page_heading'))

	{

		?>

		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
                  
	<?php

	} ?>



	<?php if ($this->params->get('all_categories_show_category_title', 1))

	{

		?>
                     
		<h3 class="cat-title"><?php echo $this->category->title; ?></h3>

	<?php

	} ?>



	<?php

	// Total category level 1 (virtual level)

	$totalCategoryLevel1 = 0;

	foreach ($this->all_categories AS $category)

	{

		if ($category->level == ($this->category->level + 1))

		{

			$totalCategoryLevel1++;

		}

	}



	// Get total columns

	$columns = (int) $this->params->get('all_categories_columns', 2);

	if (!is_numeric($columns) || ($columns <= 0))

	{

		$columns = 1;

	}

	$this->subcategory_bootstrap_columns = JUDirectoryFrontHelper::getBootstrapColumns($columns);



	// Row and column class

	$rows_class = htmlspecialchars($this->params->get('all_categories_row_class', ''));

	$columns_class = htmlspecialchars($this->params->get('all_categories_column_class', ''));



	// Index row

	$indexRow = 0;



	// Index column

	$indexColumn = 0;



	// Index element in array

	$indexElementArray = 0;

	?>
                  <h1 style="font-size: 24px; font-weight: bold;color:#2b4d76;font-family:SlabThing; ">Useful Listings for the F&B Industry in Singapore</h1>
<br>
	<div

		class="categories-row <?php echo $rows_class; ?> categories-row-<?php echo $indexRow + 1; ?> clearfix row">

		<?php

		foreach ($this->all_categories AS $category)

		{

		if ($category->level == ($this->category->level + 1))

		{

		$indexElementArray++;

		?>

		<div

			class="categories-col <?php echo $columns_class; ?> categories-col-<?php echo $indexColumn + 1; ?> col-md-<?php echo $this->subcategory_bootstrap_columns[$indexColumn]; ?>">



			<div class="panel panel-default">
                             
				<div class="panel-heading category-title">

				<h2 class="blue_text cat-tittle">	<a href="<?php echo JRoute::_(JUDirectoryHelperRoute::getCategoryRoute($category->id)); ?>">





 <? 
				   $paramsArray12 = array();
				   $paramsArray12 = json_decode($category->images, true);
                   $paramsArray12['detail_image'];
					if($paramsArray12['detail_image'] != '') {  ?>
					<img src="./media/com_judirectory/images/category/detail/<?=$paramsArray12['detail_image'];?>" width="30" height="30">
					<? } else
					{
						?>
					<img src="./media/com_judirectory/images/listing/4_2.png" width="50" height="30">
                    <? } ?>
					<p class="p_cat"><?php echo $category->title; ?></p></a></h2>

				</div>



				<?php

				if ($category->total_childs > 0)

				{

					$this->new_parent_id = $category->id;

					echo $this->loadTemplate('categories');

				}

 else
 {
  
echo '<ul class="list-group">';
  
$db = JFactory::getDBO();

$catid= $category->id;

$cattitle= $category->title;

$catalias = $category->alias;

 $userQuery = "SELECT * FROM ras_judirectory_listings_xref  where cat_id ='".$catid."' ";
  
$db->setQuery($userQuery);

$userData = $db->loadObjectList();
 
//print_r($userData);



foreach($userData as $finaldata){

 $listing_id=$finaldata->listing_id;

############################################### 
 $userQuery1 = "SELECT * FROM ras_judirectory_listings  where id ='".$listing_id."' ";
  
$db->setQuery($userQuery1);

$userData1 = $db->loadObjectList();


foreach($userData1 as $finaldata1){  //print_r($finaldata1); ?>
 


<li class="list-group-item">
   <i class="fa fa-caret-right"></i>
   
    <a href="/directory/<?php echo $catalias;?>#<?php echo $title=$finaldata1->id; ?>"><?php echo $title=$finaldata1->title; ?> </a> 
 
    </li>

<?php
//$title=$finaldata1->title;

//echo $title;

//echo "<a href='/directory/'.'".$cattitle."'.'/'.'".$title."'>$title</a>"; 

//echo "<br/>";

}


}


//print_r($userData1);

?>

<div class="panel-body">
</div>

<?php

echo '</ul>';

}

				?>

			</div>

			<!-- /.panel -->

		</div>

		<!-- /.categories-col -->

		<?php

		$indexColumn++;

		if ((($indexColumn % $columns) == 0) && ($indexElementArray < $totalCategoryLevel1))

		{

		$indexRow++;

		// Reset index column

		$indexColumn = 0;

		?>

	</div>

	<div class="categories-row <?php echo $rows_class; ?> categories-row-<?php echo $indexRow; ?> clearfix row">

		<?php

		} // end if column

		} // end if level + 1

		} // end foreach

		?>

	</div>

	<!-- /.categories-row -->

</div> 

<div class="bottom_hide"></div>
<!-- /#judir-container -->

