<?php
if (!isset($_SESSION)) {
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/functions/isAlarmSet.php');
$db = dbConnect();

$username = $_SESSION['username'];
$dbDatetimestamp = isAlarmset($db, $username); // the timestamp registered in the database


if ($dbDatetimestamp != -1)
{
	if ($dbDatetimestamp === 0) // Arduino has updated the db `time` field to 0, meaning that it is currently vibrating
	{
		require_once($_SERVER['DOCUMENT_ROOT'] . '/app/functions/isMobileDevice.php');
		if (isMobileDevice() == FALSE) // if the user is browsing the page from a computer
		{
			echo <<<HTML
			<div>
				<span id="span-mg-bot">Click on the button below to stop the alarm.</span></br></br>
				<form action="" method="POST">
					<input id="submit" type="submit" name="stop-btn" class="btn btn-primary" value="Stop the alarm !"/>
				</form>
			<div>
HTML;
		}
		else { // the user is browsing from a mobile device
			$id = $_SESSION['id'];
			require_once($_SERVER['DOCUMENT_ROOT'] . '/app/functions/isMobileMode.php');
			if (isMobileMode($db, $username) == 0)  // mobile mode is off so we don't get the user the option to turn off the alarm
			{
				echo <<<HTML
				<div>
					<span id="span-mg-bot" style="color:rgba(219, 68, 58, 1)">Get up and turn on your computer !</span>
				<div>
HTML;
			}
			else { // mobile mode is on so the user can turn off the alarm from his mobile device
				echo <<<HTML
				<div>
					<span id="span-mg-bot">Click on the button below to stop the alarm.</span></br></br>
					<form action="" method="POST">
						<input id="submit" type="submit" name="stop-btn" class="btn btn-primary" value="Stop the alarm !"/>
					</form>
				<div>
HTML;
			}
		}
		if (isset($_POST['stop-btn'])) { // the user has pressed the button to stop the alarm from vibrating
			$_SESSION['alarmstop'] == TRUE;
			require_once($_SERVER['DOCUMENT_ROOT'] . '/app/functions/alarmStop.php');
		}
	}
	elseif ($dbDatetimestamp === 1) { // Arduino has updated the db `time` field to 1, meaning that it has received the command to turn off the alarm and has done so.
		echo <<<HTML
		<div>
			<span id="span-mg-bot">Your alarm has been set off ! Click in the link below to set up a new one.</span></br></br>
			<form method="post" action="alarm_form.php">
    			<input id="submit-brown-hover-red" class="btn btn-primary" type="submit" value="Set a new alarm"/>
			</form>
		<div>
HTML;
		}
	else { // There is already an alarm set
		$alarmDate = new DateTime();
		$alarmDate->setTimestamp($dbDatetimestamp);
		$year = $alarmDate->format("Y");
		$month = $alarmDate->format("m");
		$day = $alarmDate->format("d");
		$hour = $alarmDate->format("H");
		$minute = $alarmDate->format("i");
		echo <<<HTML
		<div mb-3>
			<span style="color: #53322E; font-size: 18px">
					You have an alarm set at 
				<span style="color: #db443a">
					$hour:$minute
				</span>
					on the 
				<span style="color: #db443a">$day/$month/$year</span>.
			</span></br>
			<span id="span-mg-bot">If you wish to erase it, click on the button below.</span></br></br>
			<form method="post" action="alarm_form.php">
    			<input id="submit-brown" class="btn btn-primary" type="submit" value="Replace my current alarm"/>
			</form>
			<form action="" method="POST">
				<input id="submit-red" type="submit" name="stop-btn" class="btn btn-primary" value="Cancel my alarm"/>
			</form>
		</div>
HTML;
	}
	if (isset($_POST['stop-btn'])) { // the user has pressed the button to stop the alarm from vibrating
		$_SESSION['alarmstop'] == TRUE;
		require_once($_SERVER['DOCUMENT_ROOT'] . '/app/functions/alarmStop.php');
	}
}
else { // No alarm has ever been set for this user.
		echo  <<<HTML
		<div> 
			<span id="span-mg-bot">Welcome. Click on this link to start setting up your alarm.</span></br></br>
			<form method="post" action="alarm_form.php">
    			<input id="submit-red" class="btn btn-primary" type="submit" value="Set up an alarm"/>
			</form>
		</div>
HTML;
}