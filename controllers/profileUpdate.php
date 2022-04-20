<?php
if (!isset($_SESSION)) {
	session_start();
}

$errors = [];

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');
$db = dbConnect();

if (isset($_POST['pw-update-btn'])) // PASSWORD UPDATE
{
	$passwordOld = htmlspecialchars($_POST['passold']);
	$password = htmlspecialchars($_POST["pass1"]);
	$passwordConf = htmlspecialchars($_POST["pass2"]);
	$id = $_SESSION['id'];
	if ($password !== $passwordConf) {
		$errors['passwordConf'] = 'The two passwords do not match';
	}
	elseif (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@\#\$%\^&\*]).{8,}$#', $password) ||
		!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@\#\$%\^&\*]).{8,}$#', $passwordConf)) {
		$errors['validpassword'] = "The password must contain at least one upper case, one lowercase, one special character, one number and must be at least 8 characters long.";
	}
	if (count($errors) === 0) {
		$query = dbSearchForPasswordById($db, $id);
		$result = $query->execute();
		if ($result)
		{
			$data = $query->fetch();
			$row = $query->rowCount();
			if ($row > 0)
			{
				$passwordDb = $data['password'];
				$query->closeCursor();
				if (password_verify($passwordOld, $passwordDb))
				{
					$password = password_hash($password, PASSWORD_DEFAULT);
					$query2 = dbChangePasswordById($db, $password, $id);
					$result2 = $query2->execute();
					if ($result2)
					{
						$_SESSION['pwupdatesuccess'] = 1;
						$_SESSION['message'] = 'Your password has been successfully updated.';
						$_SESSION['type'] = 'alert-success';
						echo "<script> parent.self.location = \"password_update_success.php\";</script>"; // fails to redirect with php header function because of output
						exit(0); // without exit(0), $_SESSION message and type are lost
					}
					else {
						$_SESSION['type'] = 'alert-danger';
						$_SESSION['message'] = "Something went wrong, we couldn't access to our database.";
						$query2->closeCursor();
					}
				}
				else {
					$_SESSION['type'] = 'alert-danger';
					$_SESSION['message'] = 'The first password you entered is wrong.</br><a href="https://www.lucasderay.com/forgotten_password_form.php">Forgotten password ?</a>';
				}
			}
			else {
				$_SESSION['type'] = 'alert-danger';
				$_SESSION['message'] = "Something went wrong, we couldn't retrieve your old password in our database.";
				$query->closeCursor();
			}
		}
		else {
			$_SESSION['type'] = 'alert-danger';
			$_SESSION['message'] = "Something went wrong, we couldn't retrieve your old password in our database.";
			$query->closeCursor();
		}
	}
}

if (isset($_POST['email-update-btn'])) // EMAIL UPDATE
{
	if (isset($_POST["email"]))
	{
		$id = $_SESSION['id'];
		$query = dbSearchForUserInMembersById($db, $id);
		$result = $query->execute();
		if ($result)
		{
			$data = $query->fetch();
			$old_email = $data['email'];
			$new_email = $_POST["email"];
			$new_email = filter_var($new_email, FILTER_SANITIZE_EMAIL);
			$new_email = filter_var($new_email, FILTER_VALIDATE_EMAIL);
			if (!$new_email) {
				$errors['validemail'] = "Email is not valid";
			}
			elseif ($old_email == $new_email)
			{
				$errors['sameemail'] = "You need to enter a different email from your old one.";
			}
			else
			{
				$query2 = dbSearchForUserInMembersByEmail($db, $new_email);
				$result2 = $query2->execute();
				if ($result2)
				{
					$row2 = $query2->rowcount();
					if ($row2 > 0)
					{
						$errors['emailalreadyused'] = "This email address in already in use.";
						$query2->closeCursor();
					}
					else {
						$query2->closeCursor();
						$query3 = dbSearchInTempEmailTableByEmail($db, $new_email);
						$result3 = $query3->execute();
						if ($result3) {
							$row3 = $query3->rowCount();
							if ($row3 > 0) {
								$errors['emailalreadysent'] = 'A confirmation email has already been sent. Please check your mailbox.';
								$query3->closeCursor();
							}
						}
						else {
							$_SESSION['message'] = "Database error: Could not connect to the database.";
							$_SESSION['type'] = "alert-danger";
							$query3->closeCursor();
							exit(1);
						}
					}
				}
				else {
					$_SESSION['message'] = "Database error: Could not connect to the database.";
					$_SESSION['type'] = "alert-danger";
					$query3->closeCursor();
					exit(1);
				}
			}
			if (count($errors) === 0) {
				$expFormat = mktime(date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
				$expDate = date("Y-m-d H:i:s",$expFormat);
				$token = md5((string)(2418 * 2) . $new_email);
				$addToken= substr(md5(uniqid(rand(),1)),3,10);
				$token = $token . $addToken;
				// Insert Temp Table
				$insert4 = dbInsertIntoTempEmailTable($db);
				$result4 = $insert4->execute(array(
					'old_email' => $old_email,
					'new_email' => $new_email,
					'token' => $token,
					'expDate' => $expDate
				));
				if ($result4)
				{
					require_once($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/sendUpdateEmail.php');
					if (sendUpdateEmail($new_email, $token) == FALSE)
					{
						dbDeleteFromTempEmailTableByEmail($db, $new_email);
						$_SESSION['message'] = "Error : could not send an email from PHPMailer.";
						$_SESSION['type'] = "alert-danger";
					}
					else {
						$_SESSION['message'] = 'A confirmation email has been sent to your new email address. Please check your mailbox.';
						$_SESSION['type'] = 'alert-warning alert-dismissible fade show';
						$_SESSION['email_update'] = '1';
						echo "<script> parent.self.location = \"update_email_index.php\";</script>"; // fails to redirect with php header function because of output
						exit(0);
					}
				}
				else {
					$_SESSION['message'] = "Database error: Could not connect to the database";
					$_SESSION['type'] = "alert-danger";
					$insert4->closeCursor();
				}
			}
		}
		else {
			$_SESSION['message'] = "Database error: Could not connect to the database";
			$_SESSION['type'] = "alert-danger";
			$query->closeCursor();
		}
	}
	else {
		$errors['email'] = 'Please enter your email address.';
	}
}

if (isset($_POST['mobile-on-btn']))  // TURN MOBILE ON
{
	$id = $_SESSION['id'];
	$query = dbUpdateMobileDeviceOnById($db, $id);
	$result = $query->execute();
	if ($result)
	{
		$_SESSION['message'] = 'Successfuly switched to mobile mode.';
		$_SESSION['type'] = 'alert-success';
		$query->closeCursor();
		echo "<script> parent.self.location = \"profile.php\";</script>";
		exit(0);
	}
	else {
		$_SESSION['message'] = "Database error: Could not connect to the database";
		$_SESSION['type'] = "alert-danger";
		$query->closeCursor();
	}
}

if (isset($_POST['mobile-off-btn'])) {  // TURN MOBILE OFF
	$id = $_SESSION['id'];
	$query = dbUpdateMobileDeviceOffById($db, $id);
	$result = $query->execute();
	if ($result) {
		$_SESSION['message'] = 'Successfuly switched to browser-only mode.';
		$_SESSION['type'] = 'alert-success';
		$query->closeCursor();
		echo "<script> parent.self.location = \"profile.php\";</script>";
		exit(0);
	}
	else {
		$_SESSION['message'] = "Database error: Could not connect to the database";
		$_SESSION['type'] = "alert-danger";
		$query->closeCursor();
	}
}