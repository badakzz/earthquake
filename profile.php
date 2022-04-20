<?php
require 'header.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');
$page_title = "Your profile";
force_user_logged();
?>
<?php
$db = dbConnect();
$id = $_SESSION['id'];
$query = dbSearchForUserInMembersById($db, $id);
$query->execute();
$data = $query->fetch();
?>

<head>
	<style>
		#emailform {
			display: none;
		}

		@media (min-width: 479px) {
			.bg-light {
				max-width: 85%;
			}
		}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<main class="container-fluid">
	<script>
		if (window.innerWidth < 960) {
			document.write('<div class="bg-light p-5 text-center">');
		} else {
			document.write('<div class="bg-light p-5">');
		}
	</script>
		<div class="row">
			<div style="margin-bottom:40px" class="col-md-2">
				<h1>User Profile</h1>
				<span style="font-size:14px" ;>Member since <?php echo $data['inscription_date']; ?></span>
			</div>
			<div class="col-md-8"></div>
			<div class="col-md-2">
				<form method="post" action="password_update_index.php">
					<input id="pw-change" type=submit class="btn btn-primary" name="password-update-btn" value="Change your password" />
				</form>
			</div>
		</div>
		</br></br>
		<div class="row">
			<div style="margin-bottom:40px" class="col-md-2">
				<h4>Your username :</h4>
				<span style="color:#DB443A;"><?php echo $data['username']; ?></span>
			</div>
			<div class="col-md-8"></div>
			<div class="col-md-2">
				<h4>
					<font color="#94A2B3">Your ID :</font>
				</h4>
				<span style="color:#53322E;"><?php echo $data['randomID'] ?></span>
			</div>
		</div>
		</br></br>
		<div class="row">
			<div class="col-md-2">
				<h4>Your email address :</h4>
				<span style="color:#DB443A;display:inline-block;margin-bottom:40px"><?php echo $data['email'] . "    "; ?></span>
			</div>
			</br></br>
			<div class="col-md-8"></div>
			<div class="col-md-2">
				<button id="emailbtn" class="btn btn-primary" onclick="javascript:showForm();">Change your email </button> </br>
			</div>
		</div>
		</br>
		<div class="row align-items-center text-center">
			<div id="emailform">
				<form method="post" action="profile.php">
					<label style="color:#53322E" for="email">New email adress :</label></br>
					<input class="input-box" type="text" id="email" name="email" maxlength="255" placeholder="example@example.com" /></br>
					<input id="submit" type="submit" class="btn btn-primary" name="email-update-btn" value="Submit" />
				</form>
			</div>
		</div>
		<div class="col-md-9"></div>
		</br></br>
		<?php
		include($_SERVER['DOCUMENT_ROOT'] . '/app/profileWebbrowserMode.php');
		?>
	</br>
	<?php
	if (isset($_POST['email-update-btn'])) {
		require($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/profileUpdate.php');
	}
	?>
	<?php
	if (isset($_POST['mobile-on-btn'])) {
		require($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/profileUpdate.php');
		header("refresh:1;url=profile.php");
		exit();
	}
	?>
	<?php
	if (isset($_POST['mobile-off-btn'])) {
		require($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/profileUpdate.php');
		header("refresh:1;url=profile.php");
		exit();
	}
	?>
	<?php if (isset($errors)) : ?>
		<?php if (count($errors) > 0) : ?>
			<div class="alert alert-danger">
				<?php foreach ($errors as $error) : ?>
					<li>
						<?php echo $error; ?>
					</li>
				<?php endforeach;
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
	<script type="text/javascript">
		function showForm() {
			div = document.getElementById('emailform');
			div.style.display = "block";
			button = document.getElementById('emailbtn');
			button.style.display = "none";
		}
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.min.js"></script>
	</div>
	<?php
	require 'footer.php';
