<?php
//Script to unassign currently assigned users from a case or to reassign a user who was previously unassigned.  Clear?
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('update_case_with_users.php');
require('../utilities/names.php');

$remove_id = $_GET['remove_id'];

//check to see if this user has already been assigned to this case.
$check_status = $dbh->prepare("SELECT id,status,case_id from cm_case_assignees WHERE id = ?");

$check_status->bindParam(1,$remove_id);

$check_status->execute();

$status = $check_status->fetch();

$case_id = $status['case_id'];

if ($status['status'] == 'inactive')
	{
		$sql= "UPDATE cm_case_assignees SET status = 'active',date_assigned=NOW() WHERE id = :remove_id LIMIT 1";
	}
	else

		{
			$sql ="UPDATE cm_case_assignees SET status='inactive', date_removed=NOW() WHERE `id`= :remove_id LIMIT 1";
		}

$remove_query = $dbh->prepare($sql);

$remove_query->bindParam(':remove_id',$remove_id);

$remove_query->execute();

//Add current users to cm table
update_case_with_users($dbh,$case_id);

//Handle mysql errors
$error = $remove_query->errorInfo();

	if($error[1])

		{echo "Error: User Status Not Updated";}

		else
		{

			if ($status['status'] == 'inactive')
			{echo "User reassigned to case";}
			else
			{echo "User unassigned from case";}


		}
