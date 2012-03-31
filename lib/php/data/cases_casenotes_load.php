<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include('../utilities/thumbnails.php');
include('../utilities/names.php');
include('../utilities/convert_times.php');
include('../utilities/convert_case_time.php');
include('../html/gen_select.php');


//Load all case notes for a given case, along with user data

$id = $_POST['case_id'];
$limit = '20';

if (isset($_POST['start']))
	{$start = $_POST['start'];}
if (isset($_POST['update']))
	{$update = $_POST['update'];}
if (isset($_POST['search']))
	{$search = $_POST['search'];}


if (isset($search))
	{$sql = "SELECT cm_users.username,cm_users.first_name,cm_users.last_name,cm_users.picture_url, cm_case_notes.* FROM cm_case_notes,cm_users WHERE  (cm_case_notes.case_id = :id and cm_case_notes.username = cm_users.username) and (cm_users.last_name LIKE '%$search%'  OR cm_users.first_name LIKE '%$search%' OR cm_case_notes.date LIKE '%$search%' OR cm_case_notes.description LIKE '%$search%') ORDER BY cm_case_notes.date DESC ";}
	else
	{$sql = "SELECT cm_users.username,cm_users.first_name,cm_users.last_name,cm_users.picture_url, cm_case_notes.* FROM cm_case_notes,cm_users WHERE  cm_case_notes.case_id = :id and cm_case_notes.username = cm_users.username ORDER BY cm_case_notes.date DESC LIMIT $start, $limit";}

$case_notes_query = $dbh->prepare($sql);

$case_notes_query->bindParam(':id',$id);

$case_notes_query->execute();

$case_notes_data = $case_notes_query->fetchAll(PDO::FETCH_ASSOC);

include('../../../html/templates/interior/cases_casenotes.php');


