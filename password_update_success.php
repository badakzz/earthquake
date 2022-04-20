<?php
require_once 'header.php';

if (!isset($_SESSION['pwupdatesuccess'])) { // if the user hasn't just changed his password
	header('location: index.php');
	exit();
}
else { ?>
	<main class="container align-items-center">
		<div class="bg-light p-5 rounded text-center">
			<div class="alert <?php echo $_SESSION['type'] ?>">
				<p><span style="font-size:26px;"><strong><?php echo $_SESSION['message']; ?></p></strong></span>
			</div></br>
			<p> You will be automatically redirected in 3 seconds</p>
			<?php
			unset($_SESSION['type']);
			unset($_SESSION['message']);
			unset($_SESSION['pwupdatesuccess']);
			header("refresh:3;url=https://lucasderay.com/app/clock.php");
			exit();
			?>
		</div>
	<?php
}
require_once 'footer.php';
