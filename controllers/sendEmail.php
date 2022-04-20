<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/app/vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;

function sendVerificationEmail($userEmail, $token) {
	
	$mail = new PHPMailer();
	$body = '<!DOCTYPE html>
    <html lang="en"
    <body>
      <div class="wrapper">
        <p>Thank you for signing up on our site. Please click on the link below to verify your account:.</p>
        <p><a href="https://www.lucasderay.com/app/controllers/verifyEmail.php?token=' . $token . '">Verify Email!</a></p>
      </div>
    </body>

    </html>';

	$mail->From = 'noreply@lucasderay.com';
	$mail->FromName = 'Arduino Remote Alarm';
	$mail->addAddress($userEmail);
	$mail->isHTML(TRUE);
	$mail->Subject = 'Verify your email';
	$mail->Body = $body;
	$mail->AltBody = "https://www.lucasderay.com/app/controllers/verifyEmail.php?token=' . $token . ";
	$result = $mail->send();
	if ($result != FALSE) {
		return TRUE;
	} else {
		echo $mail->ErrorInfo;
		return FALSE;
	}
}