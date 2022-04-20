<?php
if (!isset($_SESSION)) {
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');
$db = dbConnect();

if (isset($_GET["token"]) && isset($_GET["email"]) && isset($_GET["action"])
	&& ($_GET["action"] == "update"))
{
	$_SESSION['email_update'] = '1'; // can't assign a variable to $_SESSION['email_update'] or we get a 500 error
	$token = htmlspecialchars($_GET["token"]);
	$new_email = htmlspecialchars($_GET["email"]);
	$curDate = date("Y-m-d H:i:s");
	$query = dbSearchInTempEmailTableByTokenAndEmail($db, $token, $new_email);
	$query->execute();
	$data = $query->fetch();
	$row = $query->rowCount();
	if ($row < 1) {
		$_SESSION['type'] = "alert-danger";
		$_SESSION['message'] = '<span style="font-size:26px">Invalid Link</span></br>
<p>The link is invalid/expired. Either you did not copy the correct link
from the email, or you have already used the key in which case it is 
deactivated.</p>
<p><a href="https://www.lucasderay.com/app/profile.php">Click here</a> to change your email.</p>';
		echo "<script> parent.self.location = \"../update_email_index.php\";</script>";
		$query->closeCursor();
		exit(1);
	}
	else {
		$expDate = $data['exp_date'];
		if ($expDate >= $curDate) {
			$old_email = $data['old_email'];
			$query->closeCursor();
			$update = dbUpdateEmail($db, $old_email, $new_email);
			$result = $update->execute();
			if ($result) {
				$_SESSION['message'] = 'Your email has been successfully updated.';
				$_SESSION['type'] = 'alert-success';
				$update->closeCursor();
				$delete = dbDeleteFromTempEmailTableByEmail($db);
				$delete->execute(array($new_email));
				echo "<script> parent.self.location = \"../update_email_success.php\";</script>";
				unset($_SESSION['email_update']);
				exit(0);
			}
			else {
				$_SESSION['type'] = 'alert-danger';
				$_SESSION['message'] = "Something went wrong, we couldn't access to our database.";
				$update->closeCursor();
				echo "<script> parent.self.location = \"../update_email_index.php\";</script>";
				exit(1);
			}
		}
		else {
			$_SESSION['type'] = "alert-danger";
			$_SESSION['message'] = '<span style="font-size:26px">Expired Link</span></br>
<p>The link is expired. You are trying to use the expired link which 
as valid only 24 hours (1 days after request).<br /><br /></p>
<p><a href="https://www.lucasderay.com/app/profile.php">Click here</a> to go to your profile and change your email address.</p>';
			$query->closeCursor();
			echo "<script> parent.self.location = \"../update_email_index.php\";</script>";
			exit(1);
		}
	}
}