<?php
//This file includes all functions which deal with formatting names

function username_to_lastname ($dbh,$name)

	{

		$query = $dbh->prepare("SELECT username,last_name FROM cm_users WHERE username = ? LIMIT 1");

		$query->bindParam(1,$name);

		$query->execute();

		$r = $query->fetch();

		return htmlspecialchars($r['last_name'], ENT_QUOTES,'UTF-8');


	}


function username_to_firstname ($dbh,$name)

	{

		$query = $dbh->prepare("SELECT username,first_name FROM cm_users WHERE username = ? LIMIT 1");

		$query->bindParam(1,$name);

		$query->execute();

		$r = $query->fetch();

		return htmlspecialchars($r['first_name'], ENT_QUOTES,'UTF-8');


	}


function username_to_fullname ($dbh,$name)

	{

		$query = $dbh->prepare("SELECT username,first_name,last_name FROM cm_users WHERE username = ? LIMIT 1");

		$query->bindParam(1,$name);

		$query->execute();

		$r = $query->fetch();

		return htmlspecialchars($r['first_name'], ENT_QUOTES,'UTF-8') . " " . htmlspecialchars($r['last_name'], ENT_QUOTES,'UTF-8');


	}

function case_id_to_casename ($dbh,$id)

	{

		if ($id == 'NC')
			{return "Non-Case";}
		else
		{
			$query = $dbh->prepare("SELECT first_name,last_name,organization,id FROM cm WHERE id = ? LIMIT 1");

			$query->bindParam(1,$id);

			$query->execute();

			$r = $query->fetch();

			if (!$r['first_name'] and !$r['last_name'])

				{return htmlspecialchars($r['organization'], ENT_QUOTES,'UTF-8');}

			else

				{return htmlspecialchars($r['first_name'], ENT_QUOTES,'UTF-8') . ' ' . htmlspecialchars($r['last_name'], ENT_QUOTES,'UTF-8');}
		}
	}

function username_to_userid ($dbh,$username)
{
	$q = $dbh->prepare("SELECT id,username FROM cm_users WHERE username = '$username'");

	$q->execute();

	$user_id = $q->fetch(PDO::FETCH_ASSOC);

	return $user_id['id'];
}

function userid_to_username ($dbh,$id)
{
	$q = $dbh->prepare("SELECT id,username FROM cm_users WHERE id = '$id'");

	$q->execute();

	$user = $q->fetch(PDO::FETCH_ASSOC);

	return htmlspecialchars($user['username'], ENT_QUOTES,'UTF-8');
}

//Used in Users page.  Creates array of group names and titles.  Saves having to do
//a db call for each row
function group_display_name_array($dbh)
{

	$q = $dbh->prepare("SELECT group_name, group_title FROM cm_groups");

	$q->execute();

	$groups = $q->fetchAll(PDO::FETCH_ASSOC);

	$vals = array();

	foreach ($groups as $group) {
		$vals[$group['group_title']] = $group['group_name'];
	}

	return $vals;
}

//Also for Users page.  Returns an array of last names for users who supervise
function supervisor_names_array($dbh)
{
	$q = $dbh->prepare("SELECT cm_groups.group_name,cm_groups.supervises,cm_users.last_name,cm_users.grp,cm_users.username
		FROM cm_groups,cm_users
		WHERE cm_groups.supervises = '1' and cm_users.grp = cm_groups.group_name");

	$q->execute();

	$names = $q->fetchAll(PDO::FETCH_ASSOC);

	$vals = array();

	foreach ($names as $name) {
		$vals[$name['last_name']] = $name['username'];
	}

	return $vals;
}

