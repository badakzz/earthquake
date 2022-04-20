<?php

function isAlarmSet($db, $username): int
{
	$query = dbSearchForUserInMembersByUsername($db, $username);
	$query->execute();
	$row = $query->rowCount();
	$data = $query->fetch();
	if ($row > 0)
	{
		$id_member = (int) $data['id'];
		$query->closeCursor();
		$query2 = dbSearchForAlarmById($db, $id_member);
		$query2->execute();
		$row2 = $query2->rowCount();
		if ($row2 > 0)
		{
			$data2 = $query2->fetch();
			$time = (int) $data2['time'];
			if (isset($time))
			{
				$query2->closeCursor();
				return ($time);
			}
			$query2->closeCursor();
		}
	}
	$query->closeCursor();
	return (-1); // not FALSE because FALSE == 0 and value is tested in alarmCheck.php
}