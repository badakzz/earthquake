<?php
if (!isset($_SESSION))
{
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');
$db = dbConnect();

if (isset($_GET['token'])) {
	$token = htmlspecialchars($_GET['token']);
	$query = dbSearchIntoMembersByToken($db, $token);
	$query->execute();
	$data = $query->fetch();
	$row = $query->rowCount();
	if ($row > 0) {
		$user = $data;
		if ($user['verified'] == 0)
		{
			$query->closeCursor();
			$query2 = dbSetMemberVerifiedByToken($db, $token);
			$query2->execute();
			$query2->closeCursor();
			$query3 = dbSearchIntoMembersByToken($db, $token);
			$query3->execute();
			$data = $query3->fetch();
			$_SESSION['id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['email'] = $user['email'];
			$_SESSION['verified'] = TRUE;
			$_SESSION['logged'] = TRUE;
			$_SESSION['message'] = "Your email address has been verified successfully";
			$_SESSION['type'] = 'alert-success';
			$query3->closeCursor();
			header('location:  https://lucasderay.com/app/verify_email_index.php');
			exit(0);
		}
		else {
			$_SESSION['type'] = 'alert-warning alert-dismissible fade show';
			$_SESSION['message'] = "You have aleady verified your email address";
			$_SESSION['verified'] = "verified";
			$query->closeCursor();
			header('location:  https://lucasderay.com/app/verify_email_index.php');
		}
	}
	else {
		$_SESSION['type'] = 'alert-danger';
		$_SESSION['message'] = "User not found!";
		$_SESSION['verified'] = FALSE;
		$query->closeCursor();
		header('location:  https://lucasderay.com/app/verify_email_index.php');
	}
}
else {
	$_SESSION['type'] = 'alert-danger';
	$_SESSION['message'] = "No token provided!";
	// $query->closeCursor(); gives 500 internal error
	header('location:  https://lucasderay.com/app/verify_email_index.php');
}