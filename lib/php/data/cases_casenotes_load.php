<?php
@session_start();
require_once dirname(__FILE__) . '/../../../db.php';
require(CC_PATH . '/lib/php/auth/session_check.php');
include(CC_PATH . '/lib/php/utilities/thumbnails.php');
include(CC_PATH . '/lib/php/utilities/names.php');
include_once(CC_PATH . '/lib/php/utilities/convert_times.php');
include_once(CC_PATH . '/lib/php/utilities/convert_case_time.php');
include(CC_PATH . '/lib/php/html/gen_select.php');


//Load all case notes for a given case, along with user data
if (isset($_REQUEST['case_id'])) {
    $case_id = $_REQUEST['case_id'];
}

if (isset($_GET['start'])) {
    $start = $_GET['start'];
} else {
    $start='0';
}

if ($_SESSION['mobile']){ //temporary cop-out for implimenting inf scroll on mobile
    $limit = '1000';
} else {
    $limit = '20';
}

if (isset($_REQUEST['start'])) {
    $start = $_REQUEST['start'];
}
if (isset($_REQUEST['update'])) {
    $update = $_REQUEST['update'];
}
if (isset($_REQUEST['search'])) {
    $search = $_REQUEST['search'];
}
if (isset($_REQUEST['non_case'])) {
    $non_case = $_REQUEST['non_case'];
}

$username = $_SESSION['login'];

if (isset($search)) {
    if (isset($non_case)) {
        $sql = "SELECT cm_users.username,cm_users.first_name,cm_users.last_name,cm_users.picture_url, cm_case_notes.*
        FROM cm_case_notes,cm_users WHERE  (cm_case_notes.case_id = :id and cm_case_notes.username = cm_users.username)
        and cm_case_notes.username = '$username' and (cm_users.last_name LIKE '%$search%'  OR cm_users.first_name LIKE '%$search%'
        OR cm_case_notes.date LIKE '%$search%' OR cm_case_notes.description LIKE '%$search%') ORDER BY cm_case_notes.date DESC ";
    } else {
        $sql = "SELECT cm_users.username,cm_users.first_name,cm_users.last_name,cm_users.picture_url, cm_case_notes.*
        FROM cm_case_notes,cm_users WHERE  (cm_case_notes.case_id = :id and cm_case_notes.username = cm_users.username)
        and (cm_users.last_name LIKE '%$search%'  OR cm_users.first_name LIKE '%$search%'
        OR cm_case_notes.date LIKE '%$search%' OR cm_case_notes.description LIKE '%$search%') ORDER BY cm_case_notes.date DESC ";
    }
} else if (isset($non_case)){
	$sql = "SELECT cm_users.username,cm_users.first_name,cm_users.last_name,cm_users.picture_url, cm_case_notes.*
    FROM cm_case_notes,cm_users WHERE  cm_case_notes.case_id = :id and cm_case_notes.username = cm_users.username
    and cm_case_notes.username = '$username' ORDER BY cm_case_notes.date DESC LIMIT $start, $limit";
} else {
	$sql = "SELECT cm_users.username,cm_users.first_name,cm_users.last_name,cm_users.picture_url, cm_case_notes.*
    FROM cm_case_notes,cm_users WHERE  cm_case_notes.case_id = :id and cm_case_notes.username = cm_users.username
    ORDER BY cm_case_notes.date DESC LIMIT $start, $limit";
}

$case_notes_query = $dbh->prepare($sql);

$case_notes_query->bindParam(':id',$case_id);

$case_notes_query->execute();

$case_notes_data = $case_notes_query->fetchAll(PDO::FETCH_ASSOC);

if (!$_SESSION['mobile']){
    include('../../../html/templates/interior/cases_casenotes.php');
}


