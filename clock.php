<?php
require_once 'header.php';
force_user_logged();
$page_title = 'Setting up your alarm';
?>
<link href="style-clock.css" rel="stylesheet" />

<main class="container align-items-center">
	<div class="bg-light p-5 text-center">
		<h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
		<p class="body-text-red">
			It is currently <span id="time"></span> on the <span id="date"></span>
		</p>
			<canvas id="canvas"></canvas>
		<?php
		include_once($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/alarmCheck.php');
		?>
		</br>
		<p style="color:#53322E">
			By default, you will not be able to turn off your alarm on your mobile device, forcing you to get out of bed ! You can change this setting in your profile.
		</p>
		</br>
		<h3><u>Disclaimer :</u></h2>
		<p style="color:red"><em>Right now you can only have one alarm programmed, and the webapp is only designed for the GMT+1 timezone for now.</br>
			Refer to our <a href="tutorial.php">tutorial</a> for more information.</em>
		</p>
		<script src="scripts/clock_script.js"></script>
		<script src="scripts/current_time_script.js"></script>
		<script src="scripts/current_date_script.js"></script>
		<?php require_once 'footer.php';
