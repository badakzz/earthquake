<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/app/vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;

function sendresetpasswordEmail($userEmail, $token) {

	$mail = new PHPMailer();
	$body =
	'<!DOCTYPE html>
    <html lang="en">
    <body>
      <div class="wrapper">
	  	<p>Dear user,</p>
        <p>Please click on the following link to reset your password.</p>
		<p><a href="https://www.lucasderay.com/app/controllers/passwordReset.php?token=' . $token . '&email=' . $userEmail . '&action=reset" target="_blank">
		https://www.lucasderay.com/app/controllers/passwordReset.php?token=' . $token . '&email=' . $userEmail .
	'&action=reset</a></p>	
	  <p>Please be sure to copy the entire link into your browser.
	  The link will expire after 1 day for security reason.</p>
	  <p>If you did not request this forgotten password email, no action is needed, your password will not be reset. However, you may want to log into your account and change your security password as someone may have guessed it.</p>
	    </div>
    </body>

    </html>';

	$mail->From = 'noreply@lucasderay.com';
	$mail->FromName = 'Arduino Remote Alarm';
	$mail->addAddress($userEmail);
	$mail->isHTML(TRUE);
	$mail->Subject = 'Reset your password';
	$mail->Body = $body;
	$mail->AltBody = "https://www.lucasderay.com/app/controllers/passwordReset.php?token=' . $token . '&email=' . $userEmail .
	'&action=reset";
	$result = $mail->send();
	if ($result != FALSE) {
		return TRUE;
	} else {
		echo $mail->ErrorInfo;
		return FALSE;
	}
}