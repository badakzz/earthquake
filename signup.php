<?php
require_once 'header.php';
$page_title = 'Sign up'; ?>
<?php $page_title = 'Sign up'; ?>
<main class="container align-items-center">
	<div class="bg-light p-5 text-center">
		<h1>Sign up</h1>
		</br></br>
		<form action="signup.php" method="post">
			<div style="display: none;">
				<input type="text" id="PreventChromeAutocomplete" name="PreventChromeAutocomplete" autocomplete="address-level4" />
			</div>
			<input class="input-box" type="text" id="username" name="username" maxlength="=255" value="" placeholder="Username"></br>
			<input class="input-box" type="text" id="email" name="email" maxlength="255" placeholder="Email" /></br>
			<input class="input-box" type="password" id="password" name="password" maxlength="255" placeholder="Password" /></br>
			<input class="input-box" type="password" id="passwordConf" name="passwordConf" maxlength="255" autocomplete="off" placeholder="Confirm Password" /></br>
			<input id="submit-grey" type="submit" name="signup-btn" class="btn btn-primary" value="Submit" /></br>
			<?php
			if (isset($_POST['signup-btn'])) {
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
		</br></br>
		<p>
			Emails from verification or password reset can take a few minutes to be sent.</br>
			Please check your spawm inbox if you're not receving them.
		</p>
	</div>
	<?php
	require_once 'footer.php';
