<?php
require_once 'header.php';
$page_title = 'Verify email';
// include_once($_SERVER['DOCUMENT_ROOT'] . '/controllers/authController.php'); // necessary to keep 	
// print_r($_SESSION);
// redirect user to login page if they're not awaiting for a verification
if ((!isset($_SESSION['verified']))) {
	header('location: index.php');
	exit();
}
else {
?>
<main class="container">
	<div class="row">
		<?php if (isset($_SESSION['message'])) : ?>
			<div class="alert <?php echo $_SESSION['type'] ?>">
				<?php
				echo $_SESSION['message'];
				unset($_SESSION['message']);
				?>
				<?php if ($_SESSION['type'] == "alert-success") : ?>
					<?php
					unset($_SESSION['type']);
					unset($_SESSION['verified']);
					header("refresh:3;url=clock.php");
					exit();
					?>
				<?php endif;
				unset($_SESSION['type']);
				?>
			</div>
		<?php elseif ($_SESSION['verified'] != TRUE) : ?>
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
				You need to verify your email address!
				Sign into your email account and click
				on the verification link we just emailed you
				at
				<strong><?php echo $_SESSION['email']; ?></strong>
				</br></br></br>
				<em>Emails from verification or password reset can take a few minutes to be sent.</br>
					Please check your spam inbox if you're not receiving them.</em>
		<?php endif; ?>
		</div>
	</div>
	<?php
	}
	require_once 'footer.php'; ?>