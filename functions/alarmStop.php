<?php
if (!isset($_SESSION)) {
	session_start();
}

if (isset($_POST['stop-btn']))
{
	require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');

	$db = dbConnect();
	$id = $_SESSION['id'];

	$update = dbSetAlarmOffById($db, $id);
	$result = $update->execute();
	if ($result)
	{
		header('location: clock.php');
		exit(0);
	}
}
else {
	$_SESSION['message'] = "Database error: Could not connect to the database.";
	$_SESSION['type'] = "alert-danger";
	header('location: login.php');
}	