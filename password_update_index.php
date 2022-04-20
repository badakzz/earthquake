<?php
require_once 'header.php';
force_user_logged();
$page_title = 'Change your password';
// include($_SERVER['DOCUMENT_ROOT'] . '/controllers/passwordReset.php');
?>
<main class="container align-items-center">
	<div class="bg-light p-5 rounded text-center">
		<form method="post" action="https://www.lucasderay.com/app/password_update_index.php">
			<div style="display: none;">
				<input type="text" id="PreventChromeAutocomplete" name="PreventChromeAutocomplete" autocomplete="address-level4" />
			</div>
			<h3>Update your password :</h3>
			<input type="hidden" name="action" value="update" />
			<input class="input-box" type="password" name="passold" maxlength="50" placeholder="Current password" required /></br>
			<input class="input-box" type="password" name="pass1" maxlength="50" placeholder="New password" required /></br>
			<input class="input-box" type="password" name="pass2" maxlength="50" placeholder="Confirm new password" required /></br>
			<br /><br />
			<input id="submit" type="submit" class="btn btn-primary" name="pw-update-btn" value="Update password" />
			<?php if (isset($_POST['pw-update-btn'])) {
				require($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/profileUpdate.php');
			}
			?>
		</form>
	</div>
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
	<?php if (isset($_SESSION['message']) & isset($_SESSION['type'])) : ?>
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
	<?php
	require_once 'footer.php';
