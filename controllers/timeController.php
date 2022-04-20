<?php
if (!isset($_SESSION)) {
	session_start();
}
$errors = [];

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');
$db = dbConnect();

if (isset($_POST['alarm-btn']))
{
	date_default_timezone_set('Europe/Paris');
	$timeArr = explode(":", $_POST['time']);
	$hour = $timeArr[0];
	$minute = $timeArr[1];
	$second = "00";
	$dateArr = explode("/", $_POST['date']);
	$year = $dateArr[2];
	$month = $dateArr[1];
	$day = $dateArr[0];

	$datePostString = $year . "/" . $month . "/" . $day . " " . $hour . ":" . $minute;
	$dateTimeStamp = strtotime($datePostString);
	$datePost = new \DateTime();
	$datePost->setTimestamp($dateTimeStamp);
	$unixTime = time();
	$unixTime = $unixTime - ($unixTime % 60);
	$timeZone = new \DateTimeZone('Europe/Paris');
	$time = new \DateTime();
	$time->setTimestamp($unixTime)->setTimezone($timeZone);
	$time->format('Y-m-d H:i');
	// $formattedCurrentTime = $time->format('Y-m-d H:i');

	if ($datePost > $time)
	{
		$_SESSION['date'] = $_POST['date'];
		$_SESSION['time'] = $_POST['time'];
		$postGMTEpochTime = mktime($hour, $minute, $second, $month, $day, $year);
		// $UTCDate = gmdate("M d Y H:i", $postGMTEpochTime);
		// var_dump($UTCDate);
		// // $UTCDateTime = new DateTime();
		// $UTCDateTime = DateTime::createFromFormat("M d Y H:i", $UTCDate);
		// var_dump($UTCDateTime);
		// $postUTCEpochTime = $UTCDateTime->getTimestamp();
		$username = $_SESSION['username'];
		$query = dbSearchForUserInMembersByUsername($db, $username);
		$result = $query->execute();
		if ($result)
		{
			$row = $query->rowCount();
			$data = $query->fetch();
			if ($row > 0) {
				$id = $data['id'];
				$query->closeCursor();
				$query2 = dbSearchForAlarmById($db, $id);
				$result2 = $query2->execute();
				if ($result2)
				{
					$row2 = $query2->rowCount();
					if ($row2 > 0) // if an alarm was already set, delete it
					{
						$delete = dbDeleteAlarmById($db, $id);
						$result_delete = $delete->execute();
						if (!$result_delete)
						{
							$query2->closeCursor();
							$result_delete->closeCursor();
							$_SESSION['type'] = 'alert-danger';
							$_SESSION['message'] = 'Could not connect to the database. Please try again';
							exit(1);
						}
					}
				}
				else {
					$query2->closeCursor();
					$_SESSION['type'] = 'alert-danger';
					$_SESSION['message'] = 'Could not connect to the database. Please try again';
					exit(1);
				}
				$insert = dbCreateAlarm($db, $id, $postGMTEpochTime);  // insert the set alarm into db with post values UTC and GMT+1
				$result_insert = $insert->execute();
				if ($result_insert)
				{
					$query2->closeCursor();
					$insert->closeCursor();
					$update = dbUpdateRandomID($db);
					$update->execute();
					header("Location:alarm_set_success.php?time=" . $postGMTEpochTime);
					$_SESSION['alarmset'] = TRUE;
					exit(0);
				}
				else {
					$query2->closeCursor();
					$insert->closeCursor();
					$_SESSION['type'] = 'alert-danger';
					$_SESSION['message'] = 'Could not connect to the database. Please try again';
				}
			}
			else {
				$query->closeCursor();
				$errors['username'] = "Couldn't find your username in our database. Please log out and log in again.";
			}
		}
		else {
			$query->closeCursor();
			$_SESSION['type'] = 'alert-danger';
			$_SESSION['message'] = 'Could not connect to the database. Please try again';		}
	}
	else {
		$errors['invalid_time'] = "Invalid alarm time : can't set a time in the past";
	}
}