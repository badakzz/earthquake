<?php
if (isset($_POST)) {
	$conn = new PDO('mysql:host=lucasdozer.mysql.db;dbname=lucasdozer;charset=utf8', 'lucasdozer', 'Databasepw1');

	$timeValue = $_POST["time"];
	$userGivenID = $_POST["id"];

	$sql = "UPDATE `alarms`   
	SET `time` = :time
	WHERE `randomID` = :randomID";

	$statement = $conn->prepare($sql);
	$statement->bindValue(":time", $timeValue);
	$statement->bindValue(":randomID", $userGivenID);
	$statement->execute();
}