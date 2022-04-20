	<?php if (empty($_POST['email-change-btn'])) {
		echo '<form method="post" action="https://www.lucasderay.com/app/profile.php">
				<input type="submit" class="btn btn-primary" name="email-change-button" value="Change" />';
		header('location: https://www.lucasderay.com/app/profile.php');
		exit();
	} else {
		echo '<form method="post" action="https://www.lucasderay.com/app/profile.php">
			<input type="submit" class="btn btn-primary" name="email-confirm-change-button" value="Submit" />';
	} ?>