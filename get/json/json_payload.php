<?php
if (!isset($_SESSION)) {
	session_start();
}
header('Content-Type: application/json');

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/model/dbFunctions.php');

$db = dbConnect();
$rows = array();
$query = dbSelectAllAlarms($db);
$result = $query->execute();
$i = 0;
if ($result) {
	$row = $query->rowCount();
	while ($i < $row)
	{
		$data = $query->fetch();
		$time = $data['time'];
		$id = $data['randomID'];
		$rows[$i] = array('id' => $id, 'time' => $time);
		$i++;
	}
	echo json_encode($rows);
}