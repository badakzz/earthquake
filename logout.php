<?php
session_start();
// unset($_SESSION['id']);
// unset($_SESSION['username']);
// unset($_SESSION['email']);
// unset($_SESSION['verified']);	
// unset($_SESSION['message']);
// unset($_SESSION['type']);
// unset($_SESSION['logged']);
// unset($_SESSION['date']);
// unset($_SESSION['time']);
session_unset();
header("location: index.php");