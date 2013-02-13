

<?php

//bool mail  ( string $to  , string $subject  , string $message  
//[, string $additional_headers  [, string //$additional_parameters  ]] )

$to = "webmaster@christchurchlye.org.uk";
$subject = "Subject";
$message = "Message";
$headers = 'From: webmaster@christchurch.org.uk' . "\r\n" .
           'Reply-To: webmaster@christchurch.org.uk' . "\r\n";

if (mail($to,$subject,$message, $headers))
{
	print("sent OK");
}
else
{
	print("mail failed");
}

?>
