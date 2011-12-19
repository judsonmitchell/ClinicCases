<?php
session_start();
//require('../auth/session_check.php');
require('../../../db.php');

//Load all case notes for a given case, along with user data

$id = '1175';

$case_notes_query = $dbh->prepare("SELECT cm_users.username,cm_users.first_name,cm_users.last_name,cm_users.picture_url, cm_case_notes.* FROM cm_case_notes,cm_users WHERE  cm_case_notes.case_id = :id and cm_case_notes.username = cm_users.username
");

$case_notes_query->bindParam(':id',$id);

$case_notes_query->execute();

$case_notes_data = $case_notes_query->fetchAll(PDO::FETCH_ASSOC);

$json = json_encode($case_notes_data);

echo $json;
