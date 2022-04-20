<?php
require 'header.php';
force_user_logged();
?>

<main class="container align-items-center">
	<div class="bg-light p-5 text-center">
		<div class="alert alert-success">
			<?php
			if (empty($_GET)) {
				header('location: index.php');
				exit();
			}
			elseif (isset($_GET['time'])) { ?>
				<strong><span style="font-size:26px">Congratulations !</span></strong></br>Your alarm has been set to <?php echo '<span style="font-size:18px;color:#eeed8d">' . $_SESSION['time'] . '</span> on the ' . '<span style="font-size:18px;color:#eeed8d">' . $_SESSION['date'] . '</span>' . '.';
			}
			?>
			<?php header("refresh:3;url=clock.php");
			exit(); ?>
		</div>
	</div>
	<?php
	require 'footer.php';