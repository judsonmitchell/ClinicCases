<?php
//
//Functions to return data about users
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

//Return user picture
function user_picture($dbh,$user)
{
	$q = $dbh->prepare("SELECT picture_url FROM cm_users WHERE username = ? LIMIT 1");

	$q->bindParam(1,$user);

	$q->execute();

	$u = $q->fetch();

	return $u['picture_url'];
}

function user_email_from_id($dbh,$id)
{
	$q = $dbh->prepare("SELECT email FROM cm_users WHERE id = ?");

	$q->bindParam(1, $id);

	$q->execute();

	$data = $q->fetch(PDO::FETCH_ASSOC);

	return $data['email'];

}


//Return all users in a group
function all_users_in_group($dbh,$group)
{
	$q = $dbh->prepare("SELECT * FROM `cm_users` WHERE `grp` = ? AND `status` = 'active'");

	$q->bindParam(1, $group);

	$q->execute();

	$users = $q->fetchAll(PDO::FETCH_ASSOC);

	$users_array = array();

	foreach ($users as $user) {

		$users_array[] = $user['username'];
	}

	return $users_array;

}

//Return all users who share the same supervisor
function all_users_by_supvsr($dbh,$supvsr)
{
	$q = $dbh->prepare("SELECT * FROM cm_users WHERE (`supervisors` LIKE '%,$supvsr,%' OR `supervisors` LIKE '$supvsr,%') AND `status` = 'active'  ");

	$q->execute();

	$users = $q->fetchAll(PDO::FETCH_ASSOC);

	$users_array = array();

	foreach ($users as $user) {

		$users_array[] = $user['username'];
	}

	//Add supervisor to the group
	array_push($users_array,$supvsr);

	return $users_array;
}

//Return all active users
function all_active_users($dbh)
{
	$q = $dbh->prepare("SELECT * FROM `cm_users` WHERE `status` = 'active'");

	$q->execute();

	$users = $q->fetchAll(PDO::FETCH_ASSOC);

	$users_array = array();

	foreach ($users as $user) {

		$users_array[] = $user['username'];
	}

	return $users_array;

}

//Return all active users on a case
function all_users_on_case($dbh, $id)
{
	$q = $dbh->prepare("SELECT * FROM cm_case_assignees WHERE `case_id` = '$id' AND `status` = 'active'");

	$q->execute();

	$users = $q->fetchAll(PDO::FETCH_ASSOC);

	$users_array = array();

	foreach ($users as $user) {

		$users_array[] = $user['username'];
	}

	return $users_array;

}

