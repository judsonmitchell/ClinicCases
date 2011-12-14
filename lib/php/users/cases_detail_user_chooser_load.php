<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
$case_id = $_POST['case_id'];

function load_user_list($dbh)
{
	$user_list_query = $dbh->prepare("SELECT * from cm_users where status='active' ORDER BY last_name asc"); 
	
	$user_list_query->execute();
	
	$user_list_data = $user_list_query->fetchAll();
	
	$users=NULL;
	
	foreach ($user_list_data as $user)
	{
		
	 $users .= "<option value='" . $user['username'] . "'>" . $user['first_name'] . " " . $user['last_name'] . "</option>";	
		
	}
	
	return $users;

}

$user_list = load_user_list($dbh);
include '../../../html/templates/interior/cases_detail_user_chooser.php';
