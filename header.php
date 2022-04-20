<?php
if (!isset($_SESSION)) // start session on every page in not started
{
	session_start();
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php
		if (isset($page_title)) {
			echo $page_title;
		} else {
			echo 'Arduino Vibrating Alarm';
		} ?>
	</title>

	<link href="/home/badakzz/public_html/arduino_alarm_final/app/assets/css/theme.css" rel="stylesheet" />
	<link href="/home/badakzz/public_html/arduino_alarm_final/app/assets/styles.css" rel="stylesheet" />


	<style>
		.bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;
		}

		@media (min-width: 768px) {
			.bd-placeholder-img-lg {
				font-size: 3.5rem;
			}
		}
	</style>
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-light py-2 mb-6">
		<div class="container-fluid">
			<a class="navbar-brand" href="https://lucasderay.com/app/index.php"><img src="assets/img/icons/logo.png" alt="" width="169" /></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav me-auto ms-lg-4 ms-xl-7 border-bottom border-lg-bottom-0 pt-2 pt-lg-0">
					<?php
					require($_SERVER['DOCUMENT_ROOT'] . '/app/functions/navbarFunctions.php');
					require($_SERVER['DOCUMENT_ROOT'] . '/app/functions/auth.php');
					if (is_logged()) {
						nav_logged_in();
					} else {
						nav_logged_out();
					} ?>
					<!-- </ul>
				<form class="d-flex py-3 py-lg-0">
					<a class="nav-item order-1 order-lg-0 me-lg-2" href="#" role="button">Nous contacter</a>
					<a class="btn btn-info order-0 me-1" href="#" role="button">How to setup ?</a>
				</form> -->
			</div>
		</div>
	</nav>