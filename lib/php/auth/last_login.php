<?php

function get_last_login($dbh,$user)
{
	$query = $dbh->prepare("SELECT *, max(timestamp) as last_log FROM `cm_logs` WHERE `username` = '$user' and `type` = 'in'");

	$query->execute();

	$last_log = $query->fetch(PDO::FETCH_ASSOC);

	return $last_log['last_log'];

}