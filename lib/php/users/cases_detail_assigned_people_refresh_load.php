<?php
//Refreshes the row of assigned user thumbnails when a new user is added to a case.
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include('../utilities/thumbnails.php');
$id = $_POST['id'];

$refresh_users_query = $dbh->prepare("SELECT cm_case_assignees.id as assign_id,cm_case_assignees.case_id, cm_case_assignees.status as user_case_status, cm_case_assignees.username, cm_case_assignees.date_assigned, cm_users . * FROM cm_case_assignees, cm_users WHERE cm_case_assignees.case_id =  ? AND cm_users.username = cm_case_assignees.username ORDER BY cm_case_assignees.date_assigned desc");

$refresh_users_query->bindParam(1,$id);
	 
$refresh_users_query->execute();
	
$refresh_users_data = $refresh_users_query->fetchAll(PDO::FETCH_OBJ);

include '../../../html/templates/interior/cases_detail_assigned_people_refresh.php';

