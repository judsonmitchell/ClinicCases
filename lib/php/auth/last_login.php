<?php

function get_last_login($dbh,$user)
{
	$query = $dbh->prepare("SELECT * FROM `cm_logs` WHERE `username` = '$user' and `type` = 'in' ORDER BY timestamp desc LIMIT 1,1");

	$query->execute();

	$last_log = $query->fetch(PDO::FETCH_ASSOC);

	return $last_log['timestamp'];

}