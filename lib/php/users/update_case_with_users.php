<?php

//Function to insert assigned, active users into cm table
function update_case_with_users($dbh,$case_id)
{
	$q = $dbh->prepare("SELECT username from cm_case_assignees WHERE case_id = ? AND status = 'active'");

	$q->bindParam(1,$case_id);

	$q->execute();

	$users = $q->fetchALL(PDO::FETCH_ASSOC);

	$assigned_users = array();

	foreach ($users as $user) {
		$name = username_to_fullname($dbh,$user['username']);

		$assigned_users[$name] = '';
	}

	if (count($assigned_users) > 0)
	{
		$ser = serialize($assigned_users);
	}
	else
	{
		$ser = '';
	}

	$update = $dbh->prepare("UPDATE cm SET assigned_users = ? WHERE id = ?");

	$update->bindParam(1,$ser);

	$update->bindParam(2,$case_id);

	$update->execute();

	$error = $update->errorInfo();

	if ($error[1])
		{return false;}
	else
		{return true;}
}

