<?php
require_once 'header.php';
$page_title = 'Password reset';
// include($_SERVER['DOCUMENT_ROOT'] . '/controllers/passwordReset.php');
?>
<?php
if (empty($_SESSION['message']) && empty($_SESSION['email_reset'])) {
	header('location: index.php');
	exit();
}
?>
<main class="container align-items-center">
	<div class="bg-light p-5 rounded text-center">
		<div class="col-md-4 offset-md-4 home-wrapper">

		<!-- Display messages -->
		<?php if (isset($_SESSION['message']) && $_SESSION['message'] !== 'Congratulations! Your password has been updated successfully. You will be redirected to the login page in 3 seconds.') : //wont show form if there were db errors aswell 
		?>
		<div class="alert <?php echo $_SESSION['type']?>">
		<?php
		echo $_SESSION['message'];
		unset($_SESSION['message']);
		unset($_SESSION['type']);
		?>
	</div>
	</br>
	<em>Emails from verification or password reset can take a few minutes to be sent.</em>
		</br>
		</br>
	<?php elseif (isset($_SESSION['message']) && $_SESSION['message'] == 'Congratulations! Your password has been updated successfully. You will be redirected to the login page in 3 seconds.') : 
	?>
	<div class="alert <?php echo $_SESSION['type'] ?>">
	<?php
	echo $_SESSION['message'];
	unset($_SESSION['message']);
	unset($_SESSION['type']);
	unset($_SESSION['email_reset']);
	header("refresh:3;url=index.php"); 
	?>
	</div>
	<?php elseif (isset($_SESSION['email_reset'])) : 
	?>
	<br />
	<form method="post" action="https://www.lucasderay.com/app/password_reset_index.php">
		<div style="display: none;">
				<input type="text" id="PreventChromeAutocomplete" name="PreventChromeAutocomplete" autocomplete="address-level4" />
		</div>
		<input type="hidden" name="action" value="update" />
		<h3>Enter New Password:</h3>
		<input class="input-box" type="password" id="password" name="pass1" maxlength="255" placeholder="Password" /></br>
		<input class="input-box" type="password" id="passwordConf" name="pass2" maxlength="255" autocomplete="off" placeholder="Confirm Password" /></br>
		<input id="submit" type="submit" class="btn btn-primary" name="reset-button" value="Reset Password" />
		<?php if (isset($_POST['reset-button'])) {
			require($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/passwordReset.php');
		}
		?>
	</form>
	<?php endif; 
	?>
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
	</div>
	<?php
	require_once 'footer.php';
