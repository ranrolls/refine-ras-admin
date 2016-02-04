<?php
$to = "singhakanksha2007@gmail.com";
$subject = "Please verify your email address";
$base_url="http://google.com";
$activation="http://google.com";
$message = "
<p>Greetings, <br> <br> Thanks for joining our community ! Please take a moment to verify your email address by.<br>
<a href='".$base_url."activation/".$activation."'>Clicking Here</a>
<p>Regards,<br><br>
AUTOsist Team,<br><br></p>
<p>Managing your vehicle records just got easier<br>
<a href='http://www.AUTOsist.com'>www.AUTOsist.com</a></p>";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <webmaster@example.com>' . "\r\n";
$headers .= 'Cc: myboss@example.com' . "\r\n";

mail($to,$subject,$message,$headers);
echo "done";
?> 
