<?php
//Retrieves all data for case detail window when initially called.
require('../../../db.php');

$case_query = $dbh->prepare("SELECT * FROM cm WHERE id = ? LIMIT 1");

	$case_query->bindParam(1,$id);
	
	$case_query->execute();
	
	$case_data = $case_query->fetch(PDO::FETCH_OBJ);
	
$assigned_users_query = $dbh->prepare("SELECT * FROM cm_case_assignees WHERE case_id = ?");

	$assigned_users_query->bindParam(1,$id);
	
	$assigned_users_query->execute();
	
	$assigned_users_data = $assigned_users_query->fetch(PDO::FETCH_OBJ);
	
	//$user_query = $dbh->prepare("SELECT * FROM cm WHERE id = ? LIMIT 1");

	//$user_query->bindParam(1,$id);

	//$user_query->execute();
	
	//$user_data = $user_query->fetch(PDO::FETCH_OBJ);
