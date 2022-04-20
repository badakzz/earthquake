<?php
require 'header.php';
if (is_logged()) {
	header('location: clock.php');
	exit();
}
$page_title = 'Log in';
// if (isset($_POST['username']))
// {
// 	setcookie("form_username", $_POST['username']);
// }
?>
<main class="container">
	<div class="bg-light p-5 rounded">
		<h1>Log in</h1>
		<form action="login.php" method="post">
			<p>
				<label for="username">Username :</label>
				<input type="text" id="username" name="username" maxlength="=255" />
			</p>
			<p>
				<label for="password">Password :</label>
				<input type="password" id="password" name="password" maxlength="255" />
			</p>
			<p>
				<input type="submit" name="login-btn" class="btn btn-primary" value="Submit" />
			</p>
			<?php
			if (isset($_POST['login-btn'])) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/authController.php');
			}
			?>
		</form>
		</br>
		<a href='signup.php'>Don't have an account yet ?</a> </br>
		<a href='forgotten_password_form.php'>Forgotten password ?</a>
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
	</div>
	<script>
		function onSubmit(token) {
			document.getElementById("demo-form").submit();
		}
	</script>
	<script type="text/javascript">
		function sendPost() {
			$.ajax({
				url: "https://lucasderay.com/temp/clock_online/controllers/authController.php", //the page containing php script
				type: "POST", //request type
				success: function(result) {
					alert(result);
				}
			});
		}
	</script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

	<script src="https://www.google.com/recaptcha/api.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<?php
	require 'footer.php';
