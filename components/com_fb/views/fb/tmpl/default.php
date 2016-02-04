<?php
/**
* @version		$Id:default.php 1 2015-06-04 06:35:13Z  $
* @copyright	Copyright (C) 2015, . All rights reserved.
* @license 		
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><h2><?php echo $this->params->get('page_title');  ?></h2></div>
<h3><?php echo $this->item->title; ?></h3>
<div class="contentpane">
	<div><h4>Some interesting informations</h4></div>
		<div>
		Id: <?php echo $this->item->id; ?>
	</div>
		
		<div>
		Title: <?php echo $this->item->title; ?>
	</div>
		
		<div>
		Description: <?php echo $this->item->description; ?>
	</div>
		
		<div>
		State: <?php echo $this->item->state; ?>
	</div>
		
		<div>
		Checked_out_time: <?php echo $this->item->checked_out_time; ?>
	</div>
		
		<div>
		Checked_out: <?php echo $this->item->checked_out; ?>
	</div>
		
		<div>
		Ordering: <?php echo $this->item->ordering; ?>
	</div>
		
		<div>
		Asset_id: <?php echo $this->item->asset_id; ?>
	</div>
		
		<div>
		Filetype: <?php echo $this->item->filetype; ?>
	</div>
		
		<div>
		Created_date: <?php echo $this->item->created_date; ?>
	</div>
		
		<div>
		Id: <?php echo $this->item->id; ?>
	</div>
		
	</div>
 