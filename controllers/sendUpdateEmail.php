<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;

function sendUpdateEmail($userEmail, $token) {

	$mail = new PHPMailer();
	$body = '<!DOCTYPE html>
    <html lang="en"
    <body>
      <div class="wrapper">
        <p>This is a confirmation that your have changed your email for this one on our website. If you did not ask for anything, please ignore this message.</p>
        <p><a href="https://www.lucasderay.com/app/controllers/updateEmail.php?token=' . $token . '&email=' . $userEmail. '&action=update' .'">Verify Email!</a></p>
			  <p>Please be sure to copy the entire link into your browser.
	  	<p>The link will expire after 1 day for security reason.</p>
	  	<p>If you did not request this forgotten password email, no action is needed, your password will not be reset. However, you may want to log into your account and change your security password as someone may have guessed it.</p>
	    </div>
      </div>
    </body>

    </html>';

	$mail->From = 'noreply@lucasderay.com';
	$mail->FromName = 'Arduino Remote Alarm';
	$mail->addAddress($userEmail);
	$mail->isHTML(TRUE);
	$mail->Subject = 'Verify your email';
	$mail->Body = $body;
	$mail->AltBody = "https://www.lucasderay.com/app/controllers/updateEmail.php?token=' . $token . '&email=' . $userEmail . '&action=update";
	$result = $mail->send();
	if ($result != FALSE) {
		return TRUE;
	} else {
		echo $mail->ErrorInfo;
		return FALSE;
	}
}
