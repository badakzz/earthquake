<?php
require_once 'header.php';
force_user_logged();
$page_title = 'Setting up your alarm';
?>

<body>
	<main class="container align-items-center">
		<div class="bg-light p-5 text-center">
			<h1>Set your alarm timer :</h1>
			<p class="body-text-red">
				It is currently <span id="time"></span> on the <span id="date"></span>
			</p></br>
			<form action="alarm_form.php" method="post">
				<input class="input-box" type="text" id="time" name="time" pattern="([0-1]{1}[0-9]{1}|20|21|22|23):[0-5]{1}[0-9]{1}" placeholder="HH:mm" /></br>
				<input class="input-box" type="text" id="date" name="date" pattern="^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$" class="datepicker" placeholder="dd/mm/YYYY" /></br>
				<input id="submit-grey" type="submit" name="alarm-btn" class="btn btn-primary" value="Set this this time" /></br></br>
				<?php
				if (isset($_POST['alarm-btn'])) {
					require_once($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/timeController.php');
				}
				?>
			</form>
			<p>
				The alarm needs to be set in this format : <b>HH:mm</b> | <b>dd/mm/YYYY</b>.</br>
				For instance, <b>12:00</b> | <b>01/12/2021</b>.
			</p>
			<?php if (isset($errors)) : ?>
				<?php if (count($errors) > 0) : ?>
					<div class="alert alert-danger">
						<?php foreach ($errors as $error) : ?>
							<li>
								<?php echo $error; ?>
							</li>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php
			if (isset($_SESSION['message']) & isset($_SESSION['type'])) : ?>
				<div class="alert <?php echo $_SESSION['type'] ?>">
					<li>
						<?php
						echo $_SESSION['message'];
						unset($_SESSION['message']);
						unset($_SESSION['type']);
						?>
					</li>
				</div>
			<?php endif; ?>
		</div>
		<script src="scripts/current_time_script.js"></script>
		<script src="scripts/current_date_script.js"></script>
		<?php
		require_once 'footer.php';
