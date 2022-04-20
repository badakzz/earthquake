<?php

function attributeId($db, $username) : string {
	$query = dbSelectUniqdsInMembers($db);
	$query->execute();
	$givenID = uniqid($username);
	$row = $query->rowCount();
	if ($row > 0) {
		$data = $query->fetchAll();
		$length = count($data);
		$i = 0;
		while ($i < $length) {
			if ($givenID == $data[$i]['randomID']) {
				$i = -1;
				$givenID = uniqid($username);
			}
			$i++;
		}
		$query->closeCursor();
		return $givenID;
	}
	 else {
		$query->closeCursor();
		return $givenID;
	}
}