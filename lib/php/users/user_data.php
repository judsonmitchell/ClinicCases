<?php
//
//Functions to return bits of user data
//

//Return user email
function user_email($dbh,$user)
{
	$q = $dbh->prepare("SELECT username, email FROM cm_users WHERE username = ?");

	$q->bindParam(1, $user);

	$q->execute();

	$data = $q->fetch(PDO::FETCH_ASSOC);

	return $data['email'];

}

//Return all users in a group
function all_users_in_group($dbh,$group)
{
	$q = $dbh->prepare("SELECT * FROM cm_users WHERE group = ?");

	$q->bindParam(1, $group);

	$q->execute();

	$users = $q->fetchAll(PDO::FETCH_ASSOC);

	$users_array = array();

	foreach ($users as $user) {

		$users_array[] = $user['username'];
	}

	return $users_array;

}

function all_users_by_supvsr($dbh,$supvsr)
{
	$q = $dbh->prepare("SELECT * FROM cm_users WHERE supervisors LIKE '%,$supvsr,%' OR supervisors LIKE '$supvsr,%' ");

	$q->execute();

	$users = $q->fetchAll(PDO::FETCH_ASSOC);

	$users_array = array();

	foreach ($users as $user) {

		$users_array[] = $user['username'];
	}

	return $users_array;

}