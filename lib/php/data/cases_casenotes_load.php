<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include('../utilities/thumbnails.php');
include('../utilities/names.php');
include('../utilities/convert_times.php');
include('../utilities/convert_case_time.php');


//Load all case notes for a given case, along with user data

$id = $_POST['case_id'];
$start = $_POST['start'];
$limit = '20';
if (isset($_POST['update']))
{$update = $_POST['update'];}


$case_notes_query = $dbh->prepare("SELECT cm_users.username,cm_users.first_name,cm_users.last_name,cm_users.picture_url, cm_case_notes.* FROM cm_case_notes,cm_users WHERE  cm_case_notes.case_id = :id and cm_case_notes.username = cm_users.username ORDER BY cm_case_notes.datestamp DESC 
LIMIT $start, $limit");

$case_notes_query->bindParam(':id',$id);

$case_notes_query->execute();

$case_notes_data = $case_notes_query->fetchAll(PDO::FETCH_ASSOC);

include('../../../html/templates/interior/cases_casenotes.php');


