<?php
//Retrieves all data for case detail window when initially called.
require('../../../db.php');
include_once('../../../lib/php/utilities/convert_case_time.php');
include_once('../../../lib/php/utilities/group_title.php');

//function to return thumbnail url
function thumbify($url)
	{
			$split = explode('/', $url);
			$thumbnail = $split[0] 	. "/tn_" . $split[1];
			return $thumbnail;
	}

//Get the data for the case
$case_query = $dbh->prepare("SELECT * FROM cm WHERE id = ? LIMIT 1");

	$case_query->bindParam(1,$id);
	
	$case_query->execute();
	
	$case_data = $case_query->fetch(PDO::FETCH_OBJ);

//Get everybody who is assigned to the case	and their user data
$assigned_users_query = $dbh->prepare("SELECT cm_case_assignees.case_id, cm_case_assignees.username, cm_users . * FROM cm_case_assignees, cm_users WHERE cm_case_assignees.case_id =  ? AND cm_users.username = cm_case_assignees.username");

	$assigned_users_query->bindParam(1,$id);
	
	$assigned_users_query->execute();
	
	$assigned_users_data = $assigned_users_query->fetchAll(PDO::FETCH_OBJ);

//Get the totol time each user has put into the case
$case_time_query = $dbh->prepare("SELECT case_id, username, SUM( TIME ) as totaltime FROM  `cm_case_notes` WHERE  `case_id` LIKE  ? GROUP BY username");

	$case_time_query->bindParam(1,$id);
	
	$case_time_query->execute();
	
	$case_time_data = $case_time_query->fetchAll(PDO::FETCH_OBJ);
	
	//print_r($case_time_data);
	//foreach($case_time_data as $ttime)
	//{
		//if ($ttime->username = $
		//echo $ttime->totaltime . "\n";}
//Get the user information for everybody assigned to the case
	//print_r($assigned_users_data);die;
	

	//$user_query = $dbh->prepare("SELECT * FROM cm WHERE id = ? LIMIT 1");

	//$user_query->bindParam(1,$id);

	//$user_query->execute();
	
	//$user_data = $user_query->fetch(PDO::FETCH_OBJ);
