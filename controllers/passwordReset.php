<?php
if (!isset($_SESSION)) {
	session_start();
}
$email = "";
$errors = [];

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');
$db = dbConnect();

if (isset($_GET["token"]) && isset($_GET["email"]) && isset($_GET["action"])
	&& ($_GET["action"] == "reset")) {
	$token = htmlspecialchars($_GET["token"]);
	$email = htmlspecialchars($_GET["email"]);
	$_SESSION['email_reset'] = $email; // rajoute pour que si session fade, peut qd meme acceder a la page index via lien de l email
	$curDate = date("Y-m-d H:i:s");
	$query = dbSearchInTempPwResetTableByTokenAndEmail($db, $token, $email);
	$query->execute();
	$data = $query->fetch();
	$row = $query->rowCount();
	if ($row < 1) {
		$_SESSION['type'] = "alert-danger";
		$_SESSION['message'] = '<h2>Invalid Link</h2>
<p>The link is invalid/expired. Either you did not copy the correct link
from the email, or you have already used the key in which case it is 
deactivated.</p>
<p><a href="https://www.lucasderay.com/app/forgotten_password_form.php">Click here</a> to reset password.</p>';
		unset($_SESSION['email_reset']);
		header('location: https://lucasderay.com/app/password_reset_index.php');
		$query->closeCursor();
	}
	else {
		$expDate = $data['exp_date'];
		if ($expDate >= $curDate) {
			$_SESSION['email_reset'] = $data['email'];
			// $_SESSION['message'] = "ok";  // so user won't be redirected to login page, necessary so that password_reset_index.php can't be accessed from anywhere
			$query->closeCursor();
			header('location:  https://lucasderay.com/app/password_reset_index.php');
			exit(0);
		}	
		else {
			$_SESSION['type'] = "alert-danger";
			$_SESSION['message'] = '<h2>Link Expired</h2>
<p>The link is expired. You are trying to use the expired link which 
as valid only 24 hours (1 days after request).<br /><br /></p>
<p><a href="https://www.lucasderay.com/app/forgotten_password_form.php">Click here</a> to reset password.</p>';
			unset($_SESSION['email_reset']);
			$query->closeCursor();
			header('location: password_reset_index.php');
		}
	}
} // isset email key validate end

if (isset($_POST['reset-button'])) // tester link sans premieres etapes
{
	$email = $_SESSION['email_reset'];
	$password = htmlspecialchars($_POST["pass1"]);
	$passwordConf = htmlspecialchars($_POST["pass2"]);
	
	if ($password !== $passwordConf) {
		$errors['passwordConf'] = 'The two passwords do not match';
	}
	elseif (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@\#\$%\^&\*]).{8,}$#', $password)) {
		$errors['validpassword'] = "The password must contain at least one upper case, one lowercase, one special character, one number and must be at least 8 characters long.";
	}
	else {
		$password = password_hash($password, PASSWORD_DEFAULT); //encrypt password
	}
	if (count($errors) === 0) {
		$insert = dbModifyPasswordInMembers($db);
		$result_insert = $insert->execute(array($password, $email));
		if ($result_insert)
		{
			$insert->closeCursor();
			$delete = dbDeleteFromTempPwResetTableByEmail($db);
			$result_delete = $delete->execute(array($email));
			if ($result_delete)
			{
				$_SESSION['type'] = "alert-success";
				$_SESSION['message'] = 'Congratulations! Your password has been updated successfully. You will be redirected to the login page in 3 seconds.';
				$delete->closeCursor();
				header('location: password_reset_index.php');
				exit(0);
			}
			else {
				$_SESSION['type'] = 'alert-danger';
				$_SESSION['message'] = 'Could not connect to the database. Please try again';
				$delete->closeCursor();
				exit(1);
			}
		}
		else {
			$_SESSION['type'] = 'alert-danger';
			$_SESSION['message'] = 'Could not connect to the database. Please try again';
			$insert->closeCursor();
		}
	}
}