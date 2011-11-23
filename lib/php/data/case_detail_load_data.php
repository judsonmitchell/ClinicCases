<?php
//Retrieves all data for case detail window when initially called.
require('../../../db.php');
$id="1175";
//Get the data for the case
$case_query = $dbh->prepare("SELECT * FROM cm WHERE id = ? LIMIT 1");

	$case_query->bindParam(1,$id);
	
	$case_query->execute();
	
	$case_data = $case_query->fetch(PDO::FETCH_OBJ);

//Get everybody who is assigned to the case	
$assigned_users_query = $dbh->prepare("SELECT * FROM cm_case_assignees WHERE case_id = ?");

	$assigned_users_query->bindParam(1,$id);
	
	$assigned_users_query->execute();
	
	$assigned_users_data = $assigned_users_query->fetchAll();

//Get the user information for everybody assigned to the case
	
	foreach ($assigned_users_data as $user)
	{
		$username = $user['username'];
		
		$get_user_data = $dbh->prepare("SELECT * FROM cm_users WHERE username = ?");

		$get_user_data->bindParam(1,$username);
		
		$get_user_data->execute();
		
		
	}

	//$user_query = $dbh->prepare("SELECT * FROM cm WHERE id = ? LIMIT 1");

	//$user_query->bindParam(1,$id);

	//$user_query->execute();
	
	//$user_data = $user_query->fetch(PDO::FETCH_OBJ);
