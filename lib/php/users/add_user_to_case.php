<?php

//Script to add users to a case
session_start();
require('../auth/session_check.php');
require('../../../db.php');
$case_id = $_GET['case_id'];
$user_array = $_GET['users_add'];

//$users = explode(",",$user_array);

foreach ($user_array as $user)

	{

		$user_add_query = $dbh->prepare("INSERT INTO  cm_case_assignees (`id` ,`username` ,`case_id` ,`status` ,`date_assigned` ,`date_removed`)VALUES (NULL ,  :user,  :case_id,  'active', CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');");

		$user_add_query->bindParam(':user',$user);

		$user_add_query->bindParam(':case_id',$case_id);

		$user_add_query->execute();


	}


//Handle mysql errors
$error = $user_add_query->errorInfo();

	if($error[1])
	{echo "Error: User not added";}
	else
	{echo "Users assigned to case";}
