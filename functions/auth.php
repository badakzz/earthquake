<?php
function is_logged(): bool {
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	if (isset($_SESSION['logged']) && $_SESSION['logged'] == TRUE)
		return TRUE	;
	else
		return FALSE;
}

function force_user_logged(): void {
	if (empty($_SESSION['logged'])) {
		header("Location: index.php");
		exit();
	}
}