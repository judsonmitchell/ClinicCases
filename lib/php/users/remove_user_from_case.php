<?php
session_start();
require ('../auth/session_check.php');
require ('../../../db.php');

$remove_id = $_GET['remove_id'];

$remove_query = $dbh->prepare("UPDATE cm_case_assignees SET status='inactive', date_removed=NOW() WHERE `id`= :remove_id LIMIT 1");

$remove_query->bindParam(':remove_id',$remove_id);
$remove_query->execute();

//Handle mysql errors
$error = $remove_query->errorInfo();
	if($error[1])
		{echo "Error: User Not Removed";}
		else
		{echo "User removed from case";}
