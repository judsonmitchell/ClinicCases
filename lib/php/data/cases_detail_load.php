<?php
//Retrieves all data for case detail window when initially called.
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include '../utilities/thumbnails.php';
$id = $_GET['id'];
$username = $_SESSION['login'];

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
$assigned_users_query = $dbh->prepare("SELECT cm_case_assignees.id as assign_id,cm_case_assignees.case_id, cm_case_assignees.status as user_case_status, cm_case_assignees.username, cm_case_assignees.date_assigned, cm_users . * FROM cm_case_assignees, cm_users WHERE cm_case_assignees.case_id =  ? AND cm_users.username = cm_case_assignees.username ORDER BY cm_case_assignees.date_assigned desc");

	$assigned_users_query->bindParam(1,$id);

	$assigned_users_query->execute();

	$assigned_users_data = $assigned_users_query->fetchAll(PDO::FETCH_ASSOC);

	//Check to see if the user has permission to view the case selected.  This is for the situation when a case is called via url.
	$check_permission = array_searchRecursive($username,$assigned_users_data);

	if (!$check_permission AND !$_SESSION['permissions']['view_all_cases'] == '1')
		{echo "Sorry, you do not have permission to view this case. <br /><br />If you need to see this case, please <a href='mailto:" . CC_ADMIN_EMAIL . "'> ask your administrator you to assign you to the case temporarily.";die;}

include '../../../html/templates/interior/cases_detail.php';
