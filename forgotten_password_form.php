<?php require 'header.php';
$page_title = 'Forgotten password';
if (isset($_POST['email'])) {
	setcookie("form_email", $_POST['email']);
}
?>
<main class="container align-items-center">
	<div class="bg-light p-5 text-center">
		<h1>Reset your password</h1></br></br>
		<form action="forgotten_password_form.php" method="post">
			<input class="input-box" type="text" id="email" name="email" maxlength="=255" value=<?php
			if (isset($_COOKIE['form_email'])) {
				echo '"' . $_COOKIE['form_email'] . '"';
			} else {
				echo '"" placeholder="Email address"';
			} ?> required /></br>
			<input id="submit-grey" type="submit" name="forgotten_password-btn" class="btn btn-primary" value="Reset my password" /></br>
			<?php
			if (isset($_POST['forgotten_password-btn'])) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/authController.php');
			}
			?>
		</form>
		</br>
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
		</br></br>
		<p>
			Emails from verification or password reset can take a few minutes to be sent.
		</p>
	</div>
	<?php require 'footer.php';
