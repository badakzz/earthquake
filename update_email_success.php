<?php
require_once 'header.php';

if ($_SESSION['message'] !== "Your email has been successfully updated.") { // if the user hasn't just changed his email
	header('location: index.php');
	exit();
}
else { ?>
<main class="container align-items-center">
	<div class="bg-light p-5 rounded text-center">
			<div class="alert <?php echo $_SESSION['type'] ?>">
				<p><?php echo $_SESSION['message']; ?></p>
			</div></br>
			<p> You will be automatically redirected in 3 seconds</p>
			<?php
			unset($_SESSION['type']);
			unset($_SESSION['message']);
			header("refresh:3;url=profile.php");
			exit();
			?>
		</div>
	<?php
}
require_once 'footer.php';