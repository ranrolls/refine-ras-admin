<script src="http://tjvantoll.com/demos/assets/jquery/1.9.1/jquery.js"></script>
<?php

/*

# mod_sp_quickcontact - Ajax based quick contact Module by JoomShaper.com

# -----------------------------------------------------------------------	

# Author    JoomShaper http://www.joomshaper.com

# Copyright (C) 2010 - 2014 JoomShaper.com. All Rights Reserved.

# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.joomshaper.com

*/



// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<style>
.errorMessages { display: none; list-style-type: disc; margin: 0 10px 15px 10px; padding: 8px 35px 8px 30px; color: #B94A48; background-color: #F2DEDE; border: 2px solid #EED3D7; border-radius: 4px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5); }
.errorMessages span { font-weight: bold; }

.green_text{ color:green;}


</style>
<div id="sp_quickcontact<?php echo $uniqid ?>" class="sp_quickcontact ">
  <div id="sp_qc_status"></div>
  <div  class="col-xs-12 pull-left">
    <? 
if($_REQUEST['Send_Message'])
						   {
							######## Mailing Code Here
							if($_REQUEST['sccaptcha'] != '10')
							{
								$msg1 =  'You have entered wrong captcha. Please try again.';
							}
							else
							{
						$sub = $_POST['name'];
						$to		 		= 'yogendra.paul@refine-interactive.com';
						$myemail		= $_POST['email'];
						$subject 		= 'Web Enquiry from RAS';
						$headers  		= 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: '.$sub.'('.$myemail.')' . "\r\n";

						$body='Hi Admin, <br/> <br/><br/>Please find below Web Enquiry for RAS:<br/> <br/>
						
						Name : '.$_POST['name'].'<br/> <br/> 
                        Email : '.$_POST['email'].'<br/> <br/>  
                        Subject  : '.$_POST['subject'].'<br/> <br/> 
                        Message : '.$_POST['message'].'<br/> <br/> 
						<br/><br/>
						Regards,<br/><br/>
						RAS';
				$suce =  mail($to, $subject, $body,$headers);
				
                              
				$msg =  'Message was sent successfully.';
				//header('location:contact-us');
											}
						   }

?>
        <span class="red_text" ><?php echo $msg1; ?></span>
       <span class="green_text" ><?php echo $msg; ?></span>

    <form action="" method="post">
      <div class="quickcontact_left">
        <div class="sp_qc_clr" >  
          <input type="text" name="name" id="name" placeholder="Name*"  value="<?=$_POST['name']?>" required  />
        </div>
        <div class="sp_qc_clr">
          <input type="email" name="email" id="email" placeholder="Email*" value="<?=$_POST['email']?>" required />
        </div>
        <div class="sp_qc_clr">
          <input type="text" name="subject" id="subject" placeholder="Subject*"  value="<?=$_POST['subject']?>" required />
        </div>
        <?php if($formcaptcha) { ?>
        <input id="sp-quickcontact-form" type="text" name="sccaptcha" placeholder="<?php echo $captcha_question ?>" required />
        <?php } ?>
      </div>
      <div class="quickcontact_right">
        <textarea name="message" id="message" required placeholder="Message*"><?=$_POST['message']?>
</textarea>
        <div class="sp_qc_clr"></div>
        <input id="sp_qc_submit" class="button btn btn-primary" type="submit" name="Send_Message" value="<?php echo $send_msg ?>" />
        <div class="sp_qc_clr"></div>
      </div>
    </form>
    <script src="http://tjvantoll.com/demos/assets/jquery/1.9.1/jquery.js"></script> 
    <script>

	var createAllErrors = function() {
		var form = $( this ),
			errorList = $( "ul.errorMessages", form );

		var showAllErrorMessages = function() {
			errorList.empty();
		};
		// Support Safari
		form.on( "submit", function( event ) {
			if ( this.checkValidity && !this.checkValidity() ) {
				$( this ).find( ":invalid" ).first().focus();
				event.preventDefault();
			}
		});
	};
	$( "form" ).each( createAllErrors );
</script> 
  </div>
</div>
