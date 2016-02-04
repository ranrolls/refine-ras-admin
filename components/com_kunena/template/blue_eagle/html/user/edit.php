<?php

/**

 * Kunena Component

 * @package Kunena.Template.Blue_Eagle

 * @subpackage User

 *

 * @copyright (C) 2008 - 2015 Kunena Team. All rights reserved.

 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL

 * @link http://www.kunena.org

 **/

defined ( '_JEXEC' ) or die ();

?>



<div class="kblock k-profile">

	<div class="kheader" style=" background:#2a4c75 !important;">

		<h2><span class="k-name"><?php echo JText::_('COM_KUNENA_USER_PROFILE'); ?> <?php echo $this->escape($this->name); ?></span>

		<?php if (!empty($this->editlink)) echo '<span class="kheadbtn kright">'.$this->editlink.'</span>';?></h2>

	</div>

	<div class="kcontainer">

		<div class="kbody">
<div class="col-xs-12">
<div class="height30"></div>

<div class="col-xs-3 bg-blue"> <?php $this->displaySummary(); ?>     </div> 

<div id="kprofile-rightcol" class="col-xs-9"><?php $this->displayTab(); ?></div>
</div>        
			<!--<table class = "kblocktable" id ="kprofile">

				<tr>

					<td class = "kcol-first kcol-left">

						<div id="kprofile-leftcol">

							

						</div>

					</td>

					<td class="kcol-mid kcol-right">

						

					</td>

				</tr>

			</table>-->

		</div>

	</div>

</div>

