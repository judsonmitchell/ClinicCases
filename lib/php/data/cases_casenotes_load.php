<?php
@session_start();
require(__DIR__ . '/../../../db.php');
require(CC_PATH . '/lib/php/auth/session_check.php');
include(CC_PATH . '/lib/php/utilities/thumbnails.php');
include(CC_PATH . '/lib/php/utilities/names.php');
include_once(CC_PATH . '/lib/php/utilities/convert_times.php');
include_once(CC_PATH . '/lib/php/utilities/convert_case_time.php');
include(CC_PATH . '/lib/php/html/gen_select.php');


//Load all case notes for a given case, along with user data

if (isset($_POST['case_id']))
    {$case_id = $_POST['case_id'];}
if (isset($_GET['start'])) {
    $start = $_GET['start'];
} else {
    $start='0';
}

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

$case_notes_query->bindParam(':id',$case_id);

$case_notes_query->execute();

$case_notes_data = $case_notes_query->fetchAll(PDO::FETCH_ASSOC);

if (!$_SESSION['mobile']){
    include('../../../html/templates/interior/cases_casenotes.php');
}


