<?php
function dbConnect()
{
	try {
		$db = new PDO('mysql:host=SECRET.mysql.db;dbname=SECRET;charset=utf8', 'SECRET', 'SECRETPW', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (PDOException $e) {
		die('Erreur : ' . $e->getMessage());
	}
		date_default_timezone_set('Europe/Paris');
		return ($db);
}

function dbSearchForUserInTempPwResetTableByEmail($db, $email)
{
	$query = $db->prepare("SELECT * FROM password_reset_temp WHERE email='$email'");
	return $query;
}

function dbSearchForUserInMembersByEmail($db, $email)
{
	$query = $db->prepare("SELECT * FROM members WHERE email='$email' LIMIT 1");
	return $query;
}

function dbSearchForUserInMembersById($db, $id)
{
	$query = $db->prepare("SELECT * FROM members where id='$id' LIMIT 1");
	return $query;
}

function dbSearchForUserInMembersByUsername($db, $username)
{
	$query = $db->prepare("SELECT * FROM members WHERE username = '$username' LIMIT 1");
	return $query;
}

function dbInsertNewMember($db)
{
	$insert = $db->prepare('INSERT INTO members(username, email, password, ip, token, verified, randomID, mobile_device, inscription_date) VALUES(:username, :email, :password, :ip, :token, :verified, :randomID, :mobile_device, CURDATE())');
	return $insert;
}

function dbInsertIntoTempPwResetTable($db)
{
	$insert = $db->prepare('INSERT INTO password_reset_temp(email, token, exp_date) VALUES(:email, :token, :expDate)');
	return $insert;
}

function dbSearchInTempPwResetTableByTokenAndEmail($db, $token, $email)
{
	$query = $db->prepare("SELECT * FROM password_reset_temp WHERE token='$token' AND email='$email' LIMIT 1");
	return $query;
}

function dbmodifyPasswordInMembers($db)
{
	$insert = $db->prepare('UPDATE members SET password = ? WHERE email = ?');
	return $insert;
}

function dbDeleteFromTempPwResetTableByEmail($db)
{
	$delete = $db->prepare('DELETE FROM password_reset_temp WHERE email = ?');
	return $delete;
}

function dbSearchIntoMembersByToken($db, $token)
{
	$query = $db->prepare("SELECT * FROM members WHERE token='$token' LIMIT 1");
	return $query;
}

function dbSetMemberVerifiedByToken($db, $token)
{
	$query = $db->prepare("UPDATE members SET verified=1 WHERE token='$token'");
	return $query;
}

function dbCreateAlarm($db, $id, $postGMTEpochTime)
{
	$query = $db->prepare("INSERT INTO alarms(id_member, time) VALUES('$id', '$postGMTEpochTime')");
	return $query;
}

function dbSearchForAlarmById($db, int $id)
{
	$query = $db->prepare("SELECT time FROM alarms WHERE id_member = $id LIMIT 1");
	return $query;
}

function dbDeleteAlarmById($db, int $id)
{
	$delete = $db->prepare("DELETE FROM alarms WHERE id_member = $id");
	return $delete;
}

function dbDeleteMemberByEmail($db, $email)
{
	$delete = $db->prepare("DELETE FROM members WHERE email = $email");
	$delete->execute();
}

function dbSetAlarmOffById($db, $id)
{
	$query = $db->prepare("UPDATE alarms SET time=1 WHERE id_member='$id'");
	return $query;	
}

function dbSelectAllAlarms($db)
{
	$query = $db->prepare("SELECT * FROM alarms");
	return $query;
}

function dbUpdateRandomID($db)
{
	$query = $db->prepare("UPDATE alarms t1
INNER JOIN members t2 ON t1.id_member = t2.id 
SET t1.randomID = t2.randomID");
	return $query;
}

function dbSelectUniqdsInMembers($db)
{
	$query = $db->prepare("SELECT randomID from members");
	return $query;
}

function dbSearchForPasswordById($db, $id)
{
	$query = $db->prepare("SELECT password from members WHERE id = '$id'");
	return $query;
}

function dbChangePasswordById($db, $password, $id)
{
	$query = $db->prepare("UPDATE members SET password = '$password' WHERE id = '$id'");
	return $query;
}

function dbUpdateEmail($db, $old_email, $new_email)
{
	$query = $db->prepare("UPDATE members SET email='$new_email' WHERE email='$old_email'");
	return $query;
}

function dbSearchInTempEmailTableByEmail($db, $new_email)
{
	$query = $db->prepare("SELECT * FROM email_update_temp WHERE new_email='$new_email'");
	return $query;
}

function dbInsertIntoTempEmailTable($db)
{
	$insert = $db->prepare('INSERT INTO email_update_temp(old_email, new_email, token, exp_date) VALUES(:old_email, :new_email, :token, :expDate)');
	return $insert;	
}

function dbSearchInTempEmailTableByTokenAndEmail($db, $token, $new_email)
{
	$query = $db->prepare("SELECT * FROM email_update_temp WHERE token='$token' AND new_email='$new_email' LIMIT 1");
	return $query;
}

function dbDeleteFromTempEmailTableByEmail($db)
{
	$delete = $db->prepare('DELETE FROM email_update_temp WHERE new_email = ?');
	return $delete;
}

function dbUpdateMobileDeviceOnById($db, $id)
{
	$query = $db->prepare("UPDATE members set mobile_device=1 WHERE id='$id'");
	return $query;
}

function dbUpdateMobileDeviceOffById($db, $id)
{
	$query = $db->prepare("UPDATE members set mobile_device=0 WHERE id='$id'");
	return $query;
}

function dbCheckMobileModeById($db, $id)
{
	$query = $db->prepare("SELECT mobile_device FROM members where id='$id'");
	return $query;
}