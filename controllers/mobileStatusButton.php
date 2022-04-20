<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');

$db = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['toggle_update'])) {
		$update = $db->prepare("UPDATE `members` SET `mobile_device` = ? WHERE `id` = ? LIMIT 1;");
		$update->execute([$_POST['status'], $_POST['id']]);
		echo json_encode($_POST);
	}
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (isset($_GET['toggle_select'])) {
		$select = $db->prepare("SELECT `mobile_device` FROM `members` WHERE `id` = ? LIMIT 1;");
		$select->execute([$_POST['id']]);
		echo json_encode($select->fetchColumn());
	}
} else {
	echo json_encode(array());
}
