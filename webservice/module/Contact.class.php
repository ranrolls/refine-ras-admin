<?php
/**
 * Class Name 		:	True Value
	Description		:	This class  for Service Booking 
 * Author			:	vishal
 * Created on		:	07-09-2013
 * 
 * */
 
 
Class Contact{
	
 
	public function __construct(){
		
		}
	#######################################################################
	
	
	public function ContactMail()
			{
				global $dbObj,$common;
				$action = $common->replaceEmpty('action',''); 
				$email = $common->replaceEmpty('email','');
				$name = $common->replaceEmpty('name','');
				$message = $common->replaceEmpty('message','');
				$subject = $common->replaceEmpty('subject','');
			 
			if($action='contactUs'){

			if($email){
                   				
					$message 		= 'You have Contact us.';
					$to		 	= 'vishal.k@refine-interactive.com'; //admin email
					$myemail		= $email;
					$subject 		= $subject;
					$name			= $name;
					$headers  		= 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: '.$myemail.'('.$name.')' . "\r\n";
					
					$body= 'Hello <br>'.$name. '<br>'. $email.'<br>'.$message."<br/>
					  
					<br/><br/>

					Regards,<br/><br/>
					RAS Team<br/>
					www.rasmentorshipforum.com";
					
					//print_r($body);
 
					mail($to, $subject,$body,$headers);
				 
				    echo json_encode(array('result'=>'Message was sent successfully.')); 
				 } 
				 
				else {
					echo json_encode(array('result'=>'No account found associated with this email')); 
					}			
			
			 	}
				
			}
} //Closing of Class


		
?>
		
		
