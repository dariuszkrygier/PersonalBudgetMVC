<?php

namespace App;

use App\Config;
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';



/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function send($to, $subject, $text, $html)
    {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Mailer = "smtp";

		$mail->SMTPDebug  = 0;  
		$mail->SMTPAuth   = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Port       = 587;
		$mail->Host       = "smtp.gmail.com";
		$mail->Username   = "dariusz.krygier.programista@gmail.com";
		$mail->Password   = "China2016";
			
		$mail->IsHTML(true);
	$mail->AddAddress($to);
	$mail->SetFrom("dariusz.krygier.programista@gmail.com");
	$mail->AddReplyTo($to);
	 $mail->Subject = $subject;
    $mail->Body    = $html;
    $mail->AltBody = $text;
	
	
if(!$mail->Send()) {
  echo "Error while sending Email.";
  var_dump($mail);
} 
/*else {
  echo "Email sent successfully";
}*/

	}
    
}
