<?php session_start();
require_once 'header.php';

if ($_SESSION['logged'] != TRUE || empty($_SESSION['type'])) { // redirect user to login page if they've not just logged in
	header('location: login.php');
	exit();
} else { ?>
	<main class="container align-items-center">
		<div class="bg-light p-5 text-center">
			<div class="alert <?php echo $_SESSION['type'] ?>">
				<strong><span style="font-size:26px">Welcome, <?php echo $_SESSION['username']; ?> !</span></strong>
			</div>
			</br>
			<p> You will be automatically redirected in 3 seconds</p>
			<?php if (isset($_SESSION['type']))
			{
				unset($_SESSION['type']);
			}
			header("refresh:3;url=clock.php"); // using html meta element loses php session
			exit();
			?>
		<?php
	}
	require_once 'footer.php';
