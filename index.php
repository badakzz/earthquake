<?php
require 'header.php';
$page_title = 'Home';
?>
<main class="container align-items-center">
	<div class="bg-light p-5 text-center">
		<?php if (!is_logged()) {
			echo <<<HTML
			<img id="logobig" class="img-fluid" src="assets/img/icons/logo.png">
			<p id="intro" class="lead">This is the webapp where you are going to pair and configure your EarthQuake alarm !</br>
				Authenticate yourself and start setting it up !
			</p>
			<form method="post" action="index.php">
				<input id="username" class="input-box" type="text" name="username" maxlength="=255" placeholder="Username"/></br>
				<input id="password" class="input-box" type="password" name="password" maxlength="255" placeholde="Password"/></br>
				<a id="frgtpw" href="forgotten_password_form.php">Forgotten password ?</a></br>
				<input id="submit" name="login-btn" type="submit" class="btn btn-primary" value="Log in"></br>
				<a id="signup" href="signup.php">Sign up<a></br>
			</form></br>
HTML;
		} else {
			header('Location: clock.php');
			exit();
		} ?>
		<?php
		if (isset($_POST['login-btn'])) {
			require_once($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/authController.php');
		}
		?>
		<?php if (isset($errors)) : ?>
			<?php if (count($errors) > 0) : ?>
			</br>
				<div class="alert alert-danger">
					<?php foreach ($errors as $error) : ?>
						<li>
							<?php echo $error;?>
						</li>
					<?php endforeach;
						unset($error)
					?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if (isset($_SESSION['message']) && isset($_SESSION['type'])) : ?>
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
	<?php
	// require 'footer.php';
