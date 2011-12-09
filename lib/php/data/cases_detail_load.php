<?php
//Retrieves all data for case detail window when initially called.
require('../../../db.php');


//function to return thumbnail url
function thumbify($url)
	{
			$split = explode('/', $url);
			$thumbnail = $split[0] 	. "/tn_" . $split[1];
			return $thumbnail;
	}
	


function array_searchRecursive( $needle, $haystack, $strict=false, $path=array() ) 
{ 
    if( !is_array($haystack) ) { 
        return false; 
    } 

    foreach( $haystack as $key => $val ) { 
        if( is_array($val) && $subPath = array_searchRecursive($needle, $val, $strict, $path) ) { 
            $path = array_merge($path, array($key), $subPath); 
            return $path; 
        } elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) { 
            $path[] = $key; 
            return $path; 
        } 
    } 
    return false; 
} 
//Get the data for the case
$case_query = $dbh->prepare("SELECT * FROM cm WHERE id = ? LIMIT 1");

	$case_query->bindParam(1,$id);
	
	$case_query->execute();
	
	$case_data = $case_query->fetch(PDO::FETCH_OBJ);

//Get everybody who is assigned to the case	and their user data
$assigned_users_query = $dbh->prepare("SELECT cm_case_assignees.id as assign_id,cm_case_assignees.case_id, cm_case_assignees.username, cm_users . * FROM cm_case_assignees, cm_users WHERE cm_case_assignees.case_id =  ? AND cm_users.username = cm_case_assignees.username");

	$assigned_users_query->bindParam(1,$id);
	
	$assigned_users_query->execute();
	
	$assigned_users_data = $assigned_users_query->fetchAll(PDO::FETCH_OBJ);

/*
//Get the total time each user has put into the case
$case_time_query = $dbh->prepare("SELECT case_id, username, SUM( TIME ) as totaltime FROM  `cm_case_notes` WHERE  `case_id` LIKE  ? GROUP BY username");

	$case_time_query->bindParam(1,$id);
	
	$case_time_query->execute();
	
	$case_time_data = $case_time_query->fetchAll(PDO::FETCH_ASSOC);

//Get the last activity by each user on the case.  Eternal gratitude to this gentleman for helping me with the groupwise maximum query:	http://stackoverflow.com/questions/8296629/mysql-select-most-recent-entry-by-user-and-case-number
	$last_activity_query = $dbh->prepare("SELECT cn.* FROM cm_case_notes AS cn JOIN ( SELECT case_id, username ,MAX(  `date` ) AS recent_date FROM cm_case_notes WHERE case_id = :id GROUP BY username) AS q ON  ( q.username,  q.recent_date ) = ( cn.username, cn.`date`) WHERE cn.case_id = :id ");
	$last_activity_query->bindParam(':id', $id, PDO::PARAM_INT);
	$last_activity_query->execute();
	$last_activity_data = $last_activity_query->fetchAll(PDO::FETCH_ASSOC);
*/

//print_r($last_activity_data);
	
	//print_r($case_time_data);
//	foreach($case_time_data as $ttime)
	//{
		
		//print_r($ttime);}die;
		//if ($ttime->username = $
		//echo $ttime->totaltime . "\n";}
//Get the user information for everybody assigned to the case
	//print_r($assigned_users_data);die;
	

	//$user_query = $dbh->prepare("SELECT * FROM cm WHERE id = ? LIMIT 1");

	//$user_query->bindParam(1,$id);

	//$user_query->execute();
	
	//$user_data = $user_query->fetch(PDO::FETCH_OBJ);
