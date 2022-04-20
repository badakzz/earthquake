<?php
if (!isset($_SESSION)) {
	session_start();
}

$username = "";
$email = "";
$errors = [];

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');
$db = dbConnect();

// SIGN UP USER
if (isset($_POST['signup-btn'])) {
	$username = htmlspecialchars($_POST['username']);
	$email = htmlspecialchars($_POST['email']);
	$email = strtolower($email);
	$password = htmlspecialchars($_POST['password']);
	$passwordConf = htmlspecialchars($_POST['passwordConf']);
	$ip = $_SERVER['REMOTE_ADDR'];
	if (empty($username)) {
		$errors['username'] = 'Username required';
	}
	if (empty($email)) {
		$errors['email'] = 'Email required';
	}
	elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE
	|| filter_var($email, FILTER_SANITIZE_EMAIL) == FALSE)
	{
		$errors['validemail'] = "Email is not valid";
	}
	if (empty($password)) {
		$errors['password'] = 'Password required';
	}
	elseif ($password !== $passwordConf) {
		$errors['passwordConf'] = 'The two passwords do not match';
	}
	elseif (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@\#\$%\^&\*]).{8,}$#', $password)) {
		$errors['validpassword'] = "The password must contain at least one upper case, one lowercase, one special character, one number and must be at least 8 characters long";
	}
	else {
		$password = password_hash($password, PASSWORD_DEFAULT); //encrypt password
	}
	// Check if email already exists
	$query = dbSearchForUserInMembersByEmail($db, $email);
	$query->execute();
	$row = $query->rowCount();
	$data = $query->fetch();
	if ($row > 0 && $data['verified'] == 1) {
		$errors['email'] = "Email already in use.";
	}
	$query->closeCursor();
	$query2 = dbSearchForUserInTempPwResetTableByEmail($db, $email);
	$row2 = $query2->rowCount();
	$data2 = $query2->fetch();
	if ($row2 > 0 && $data2['verified'] == 0 ) {
		$errors['notverified'] = "This email adress has already been sent a verification token. Please check your emails inbox.";
	}
	$query2->closeCursor();
	$query3 = dbSearchForUserInMembersByUsername($db, $username);
	$query3->execute();
	$row3 = $query3->rowCount();
	if ($row3 > 0)
	{
		$errors['usernametaken'] = "Username already in use.";
	}
	$query3->closeCursor();
	if (count($errors) === 0) {
		$token = bin2hex(random_bytes(50)); // generate unique token
		require_once($_SERVER['DOCUMENT_ROOT'] . '/app/functions/attributeID.php');
		$givenID = attributeId($db, $username);
		$insert = dbInsertNewMember($db);
		$result = $insert->execute(array(
			'username' => $username,
			'email' => $email,
			'password' => $password,
			'ip' => $ip,
			'token' => $token,
			'verified' => 0,
			'randomID' => $givenID,
			'mobile_device' => 0
		));
		if ($result) {
			$user_id = $db->lastInsertId($username);
			$insert->closeCursor();
			require_once($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/sendVerificationEmail.php');
			if (sendVerificationEmail($email, $token) == FALSE)
			{
				dbDeleteMemberByEmail($db, $email); // delete if mail was not sent
				$_SESSION['message'] = "Error : could not send an email from PHPMailer.";
				$_SESSION['type'] = "alert-danger";
			}
			else {
				$_SESSION['id'] = $user_id;
				$_SESSION['username'] = $username;
				$_SESSION['email'] = $email;
				$_SESSION['verified'] = FALSE;
				header('location: verify_email_index.php');
				exit(0);	
			}

		}
		else {
			$_SESSION['message'] = "Database error: Could not register user";
			$_SESSION['type'] = "alert-danger";
			$insert->closeCursor();
		}
	}
}

// LOGIN
if (isset($_POST['login-btn'])) {
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	if (empty($username)) {
		$errors['username'] = 'Username or email required';
	}
	if (empty($password)) {
		$errors['password'] = 'Password required';
	}
	if (count($errors) === 0) {
		$query = dbSearchForUserInMembersByUsername($db, $username);
		$result = $query->execute();
		if ($result)
		{
			$data = $query->fetch();
			$row = $query->rowCount(); // checks if user exists
			if ($row > 0) {
				if (password_verify($password, $data['password'])) {
					if ($data['verified'] == 1)
					{
						$_SESSION['id'] = $data['id'];
						$_SESSION['username'] = $data['username'];
						$_SESSION['email'] = $data['email'];
						$_SESSION['type'] = 'alert-success';
						$_SESSION['logged'] = TRUE;
						unset($_SESSION['verified']);
						$query->closeCursor();
						header('location: login_success.php');
						exit(0);
					}
					else {
						$errors['login_fail'] = "This account has not been verified yet. Please check your emails.";
						$query->closeCursor();
					}
				}
				else { // if password does not match
					$errors['login_fail'] = "Wrong username / password";
					$query->closeCursor();
				}
			}
			else {
				$_SESSION['message'] = "Wrong username";
				$_SESSION['type'] = "alert-danger";
				$query->closeCursor();
			}
		}
		else {
			$_SESSION['message'] = "Database error: Could not connect to the database";
			$_SESSION['type'] = "alert-danger";
			$query->closeCursor();
		}
	}
}

// PASSWORD RESET
if (isset($_POST['forgotten_password-btn']))
{
	if (isset($_POST["email"]))
	{
		$email = $_POST["email"];
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if (!$email) {
			$errors['validemail'] = "Email is not valid";
		}
		else
		{
			$query = dbSearchForUserInMembersByEmail($db, $email);
			$result = $query->execute();
			if ($result)
			{
				$data = $query->fetch();
				$row = $query->rowCount();
				if ($row < 1)
				{
					$errors['usernotfound'] = 'User not found in database';
					$query->closeCursor();
				}
				else {
					$query->closeCursor();
					$query2 = dbSearchForUserInTempPwResetTableByEmail($db, $email);
					$result2 = $query2->execute();
					if ($result2)
					{
						$row2 = $query2->rowCount();
						if ($row2 > 0) {
							$errors['emailalreadysent'] = 'A password reset email has already been sent. Please check your mailbox.';
							$query2->closeCursor();
						}
					}
					else {
						$_SESSION['message'] = "Database error: Could not connect to the database.";
						$_SESSION['type'] = "alert-danger";
						$query2->closeCursor();
						exit(1);
					}
				}
			}
			else {
				$_SESSION['message'] = "Database error: Could not connect to the database";
				$_SESSION['type'] = "alert-danger";
				$query->closeCursor();
				exit(1);
			}
		}
		if (count($errors) === 0) {
			$expFormat = mktime(
			date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
			);
			$expDate = date("Y-m-d H:i:s",$expFormat);
			$token = md5((string)(2418 * 2) . $email);
			$addToken= substr(md5(uniqid(rand(),1)),3,10);
			$token = $token . $addToken;
			// Insert Temp Table
			$insert = dbInsertIntoTempPwResetTable($db);
			$result = $insert->execute(array(
				'email' => $email,
				'token' => $token,
				'expDate' => $expDate
			));
			if ($result)
			{
				require_once($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/sendResetPasswordEmail.php');
				if (sendresetpasswordEmail($email, $token) == FALSE)
				{
					$_SESSION['message'] = "Error : could not send an email from PHPMailer.";
					$_SESSION['type'] = "alert-danger";
					$delete = dbDeleteFromTempPwResetTableByEmail($db);
					$delete->execute(array($email));
				}
				else {
					$_SESSION['message'] = 'A password reset email has been sent. Please check your mailbox.';
					$_SESSION['type'] = 'alert-success';
					$insert->closeCursor();
					$_SESSION['email_reset'] = $email;
					header('location: password_reset_index.php');
					exit(0);
				}
			}
			else {
				$_SESSION['message'] = "Database error: Could not connect to the database";
				$_SESSION['type'] = "alert-danger";
				$insert->closeCursor();
			}
		}
	}
	else {
		$errors['email'] = 'Please enter your email address.';
		$insert->closeCursor();
	}
}