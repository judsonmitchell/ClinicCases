<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include_once('../utilities/convert_case_time.php');
include_once('../utilities/group_title.php');
include_once('../utilities/convert_times.php');
include_once('../utilities/format_text.php');
$case_id = $_POST['case_id']; $username = $_POST['username'];

//function to get all user activity on a given case.

function get_user_activity($username,$case_id,$dbh)
{
//find the user's total time on the case

	$case_time_query = $dbh->prepare("SELECT case_id, username, SUM( TIME ) as totaltime FROM  `cm_case_notes` WHERE  `case_id` LIKE  :case_id and `username` LIKE :username  GROUP BY username");

	$case_time_query->bindParam(':case_id',$case_id);
	
	$case_time_query->bindParam(':username',$username);
	
	$case_time_query->execute();
	
	$case_time_data = $case_time_query->fetch(PDO::FETCH_ASSOC);

//Get the user's last activity on the case
	
	$last_activity_query = $dbh->prepare("SELECT cn.* FROM cm_case_notes AS cn JOIN ( SELECT case_id, username ,MAX(  `date` ) AS recent_date FROM cm_case_notes WHERE case_id = :case_id  GROUP BY username) AS q ON  ( q.username,  q.recent_date ) = ( cn.username, cn.`date`) WHERE cn.case_id = :case_id and cn.username = :username ");

	$last_activity_query->bindParam(':case_id',$case_id);
	
	$last_activity_query->bindParam(':username',$username);
	
	$last_activity_query->execute();
	
	$last_activity_data = $last_activity_query->fetch(PDO::FETCH_ASSOC);
	
//Get the users data

	$user_data_query = $dbh->prepare("SELECT * from cm_users where username = :username");
	
	$user_data_query->bindParam(':username',$username);
	
	$user_data_query->execute();
	
	$user_data = $user_data_query->fetch(PDO::FETCH_ASSOC);
	
//Get the assignment id of the user to this case.

	$assignment_query = $dbh->prepare("SELECT id as assign_id,username,case_id, status as case_status,date_assigned from cm_case_assignees WHERE username = :username and case_id = :case_id LIMIT 1");
	
	$assignment_query->bindParam(':case_id',$case_id);
	
	$assignment_query->bindParam(':username',$username);
	
	$assignment_query->execute();
	
	$assignment = $assignment_query->fetch(PDO::FETCH_ASSOC);
	
	$user_activity  = array_merge((array)$user_data,(array)$last_activity_data,(array)$case_time_data,(array)$assignment);
	
	return $user_activity;
}

$data = get_user_activity($username,$case_id,$dbh);

include '../../../html/templates/interior/cases_detail_user_activity.php';
