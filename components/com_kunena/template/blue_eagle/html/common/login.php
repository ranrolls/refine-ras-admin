<?php

/**

 * Kunena Component

 * @package Kunena.Template.Blue_Eagle

 * @subpackage Common

 *

 * @copyright (C) 2008 - 2015 Kunena Team. All rights reserved.

 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL

 * @link http://www.kunena.org

 **/

defined ( '_JEXEC' ) or die ();

?>

<div class="row " >
<div class="col-xs-12 ">
<div class="bg_white" style="border:1px solid #dddddd;  padding-bottom:20px;padding-top:20px; margin-bottom:50px;">

<div class="col-sm-7 col-sm-offset-4  ">

	<div class="kcontainer" id="kprofilebox">

		<div class="kbody forum_login">

<div class="k_guest">
<?php echo JText::_('COM_KUNENA_PROFILEBOX_WELCOME'); ?>,
<b class="blue_text"><?php echo JText::_('COM_KUNENA_PROFILEBOX_GUEST'); ?></b>
</div>
   
  <div class="clearfix"><br></div>
				<?php if ($this->login->enabled()) : ?>

				<form action="<?php echo KunenaRoute::_('index.php?option=com_kunena') ?>" method="post" name="login">

					<input type="hidden" name="view" value="user" />

					<input type="hidden" name="task" value="login" />

					[K=TOKEN]

<div class="row input col-xs-12">

					 

	<div class="pull-left col-xs-4 pd0">

	<?php /*?><span class="us_nm">	<?php echo JText::_('COM_KUNENA_LOGIN_USERNAME') ?></span><?php */?>

	<input type="text" name="username" required="true" class="inputbox ks" alt="username" size="18" placeholder="Username" />

<?php /*?><input type="checkbox" name="remember" alt="" value="1" />
<?php if($this->remember) : ?>
<?php echo JText::_('COM_KUNENA_LOGIN_REMEMBER_ME'); ?>
<?php endif; ?><?php */?>
</div>

    <div class="pull-left col-xs-4">
   <?php /*?> <span class="us_nm"><?php echo JText::_('COM_KUNENA_LOGIN_PASSWORD'); ?></span><?php */?>
    <input type="password" name="password" required="true" class="inputbox ks" size="18" alt="password" placeholder="Password"/></div>


	<div class="pull-left col-xs-3"> 
<input type="submit" name="submit" class="blue_btn btn btn-default" value="<?php echo JText::_('COM_KUNENA_PROFILEBOX_LOGIN'); ?>" />
</div>

					 
<div class="clearfix"></div>
<div class="klink-block ">

<span class="kprofilebox-pass pull-left">
<a href="<?php echo $this->lostPasswordUrl ?>" rel="nofollow"><?php echo JText::_('COM_KUNENA_PROFILEBOX_FORGOT_PASSWORD') ?></a></span>
<span class="pull-left" style="color:#224c9e;"> &nbsp; | &nbsp; </span>
<span class="kprofilebox-user  pull-left">
<a href="<?php echo $this->lostUsernameUrl ?>" rel="nofollow"><?php echo JText::_('COM_KUNENA_PROFILEBOX_FORGOT_USERNAME') ?></a></span>
<span class="pull-left" style="color:#224c9e;"> &nbsp; | &nbsp; </span>

						<?php

						if ($this->registerUrl) : ?>

<span class="kprofilebox-register pull-left ">
New user, <a href="<?php echo $this->registerUrl ?>" rel="nofollow" style="color:#e10000 !important;"><?php echo JText::_('COM_KUNENA_PROFILEBOX_CREATE_ACCOUNT') ?></a>
</span>

						<?php endif; ?>

					</div>

</div>			
    </form>

				<?php endif; ?> 
  
  
 			<!-- Module position -->

			<?php if ($this->moduleHtml) : ?>

				<div class="kprofilebox-modul">

					<?php echo $this->moduleHtml; ?>

				</div>
	<?php endif; ?>   
                
<!--<table class="kprofilebox">

	<tbody>

		<tr class="krow1">

			<td valign="top" class="kprofileboxcnt">

				


			</td>

			<td class = "kprofilebox-right">


			</td>

		

		</tr>

	</tbody>

</table>-->

		</div>

	</div>

</div>
<!-- second -->
  <div class="login-right col-sm-3  "> <img  src="images/page/login_right.png" class="forum_login_ban"> </div>
   <div class="clearfix"></div>
   </div>    
   </div>
</div>
