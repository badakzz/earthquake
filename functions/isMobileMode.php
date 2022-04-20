<?php

function isMobileMode($db, $username): int
{
	$query = dbSearchForUserInMembersByUsername($db, $username);
	$query->execute();
	$row = $query->rowCount();
	$data = $query->fetch();
	if ($row > 0)
	{
		$id_member = (int) $data['id'];
		$query->closeCursor();
		$query2 = dbCheckMobileModeById($db, $id_member);
		$query2->execute();
		$row2 = $query2->rowCount();
		if ($row2 > 0)
		{
			$data2 = $query2->fetch();
			$mobile_mode = (int) $data2['mobile_device'];
			if (isset($mobile_mode))
			{
				$query2->closeCursor();
				return ($mobile_mode);
			}
			$query2->closeCursor();
		}
	}
	$query->closeCursor();
	return (0); // if not set default to off
}