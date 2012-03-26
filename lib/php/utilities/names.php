<?php

//This file includes all functions which deal with formatting names

function username_to_lastname ($dbh,$name)

	{

		$query = $dbh->prepare("SELECT username,last_name FROM cm_users WHERE username = ? LIMIT 1");

		$query->bindParam(1,$name);

		$query->execute();

		$r = $query->fetch();

		return $r['last_name'];


	}


function username_to_firstname ($dbh,$name)

	{

		$query = $dbh->prepare("SELECT username,first_name FROM cm_users WHERE username = ? LIMIT 1");

		$query->bindParam(1,$name);

		$query->execute();

		$r = $query->fetch();

		return $r['first_name'];


	}


function username_to_fullname ($dbh,$name)

	{

		$query = $dbh->prepare("SELECT username,first_name,last_name FROM cm_users WHERE username = ? LIMIT 1");

		$query->bindParam(1,$name);

		$query->execute();

		$r = $query->fetch();

		return $r['first_name'] . " " . $r['last_name'];


	}

function case_id_to_casename ($dbh,$id)

	{
		$query = $dbh->prepare("SELECT first_name,last_name,organization,id FROM cm WHERE id = ? LIMIT 1");

		$query->bindParam(1,$id);

		$query->execute();

		$r = $query->fetch();

		if (!$r['first_name'] and !$r['last_name'])

			{return $r['organization'];}

		else

			{return $r['first_name'] . ' ' . $r['last_name'];}
	}
