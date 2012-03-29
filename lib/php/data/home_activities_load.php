<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include('../utilities/thumbnails.php');
include('../utilities/names.php');
include('../utilities/convert_times.php');
include('../auth/last_login.php');

//function to sort the activities array by subkey - date

function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC) {

    foreach ($array as $subarray) {

        $keys[] = $subarray[$subkey];
    }

    array_multisort($keys, $sortType, $array);
}

$username = $_SESSION['login'];

//$last_login = get_last_login($dbh,$username);

//$last_login = '2012-01-01 06:09:36';

$phpdate = strtotime('-60 days');

$mysqldate = date( 'Y-m-d H:i:s', $phpdate );


//Types of events covered by this:
// 1. Cases opened
// 2. Cases closed
// 3. Casenotes entered
// 4. Documents uploaded or edited
// 5. Journal added
// 6. Events added
// 7. Being assigned to a case
// 8. Board post

	// Info to be abstracted:
	// 1. User who did the action
	// 2. Time action was done
	// 3. Title of action (what was it?)
	// 4. Substance of action (casenote description)
	// 5. Link to the resource

//Case notes
$get_notes = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
	cm_case_notes.id as note_id,
	cm_case_assignees.username as assign_user,
	cm_case_notes.username as note_user
	FROM cm_case_assignees,cm_case_notes
	WHERE cm_case_assignees.username = '$username'
	AND cm_case_notes.case_id = cm_case_assignees.case_id
	AND cm_case_notes.datestamp >= '$mysqldate'");

$get_notes->execute();

$casenotes = $get_notes->fetchAll(PDO::FETCH_ASSOC);

foreach ($casenotes as $note) {
	$activity_type = 'casenote';

	if ($note['note_user'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$note['note_user']);
	}

	$thumb = return_thumbnail($dbh,$note['note_user']);
	$action_text = " added a case note to ";
	$casename = case_id_to_casename($dbh,$note['case_id']);
	$time_done = $note['datestamp'];
	$time_formatted = extract_date_time($note['datestamp']);
	$id = $note['note_id'];
	$what = htmlentities($note['description']);
	$follow_url = 'index.php?i=Cases.php#cases/' . $note['case_id'];

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//Documents
$get_documents = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
	cm_documents.id as doc_id,
	cm_case_assignees.username as assign_user,
	cm_documents.username as doc_user
	FROM cm_case_assignees,cm_documents
	WHERE cm_case_assignees.username = '$username'
	AND cm_documents.case_id = cm_case_assignees.case_id
	AND cm_documents.date_modified >= '$mysqldate'
	AND cm_documents.name != ''");

$get_documents->execute();

$documents = $get_documents->fetchAll(PDO::FETCH_ASSOC);

foreach ($documents as $document) {
	$activity_type = 'document';

	if ($document['doc_user'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$document['doc_user']);
	}

	$thumb = return_thumbnail($dbh,$document['doc_user']);
	$action_text = ' added a document to ';
	$casename = case_id_to_casename($dbh,$document['case_id']);
	$time_done = $document['date_modified'];
	$time_formatted = extract_date_time($document['date_modified']);
	$id = $document['doc_id'];
	$doc_title = htmlentities($document['name']);
	$what = "<a href='#' data-id='" . $id . "' class='doc_view " . $document['extension'] . "'>" . $doc_title . "</a>";
	$follow_url = 'index.php?i=Cases.php#cases/' . $document['case_id'] . '/3';
	//3 indicates third item in nav list

	$item = array('activity_type' => $activity_type, 'by' => $by,'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;
}

//Cases opening

$get_opened_cases = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
	cm_case_assignees.username as assign_user
	FROM cm_case_assignees,cm
	WHERE cm_case_assignees.username = '$username'
	AND cm.id = cm_case_assignees.case_id
	AND cm.time_opened >= '$mysqldate'");

$get_opened_cases->execute();

$opened = $get_opened_cases->fetchAll(PDO::FETCH_ASSOC);

foreach ($opened as $open) {
	$activity_type = 'opening';

	if ($open['opened_by'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$open['opened_by']);
	}

	$thumb = return_thumbnail($dbh,$open['opened_by']);
	$action_text = " opened a case: ";
	$casename = case_id_to_casename($dbh,$open['case_id']);
	$time_done = $open['time_opened'];
	$time_formatted = extract_date_time($open['time_opened']);
	$id = $open['id'];
	$what = $open['notes'];
	$follow_url = 'index.php?i=Cases.php#cases/' . $open['id'];

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//Cases closing

$get_closed_cases = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
	cm_case_assignees.username as assign_user
	FROM cm_case_assignees,cm
	WHERE cm_case_assignees.username = '$username'
	AND cm.id = cm_case_assignees.case_id
	AND cm.time_closed >= '$mysqldate'");

$get_closed_cases->execute();

$closed = $get_closed_cases->fetchAll(PDO::FETCH_ASSOC);

foreach ($closed as $close) {
	$activity_type = 'closing';

	if ($close['closed_by'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$close['closed_by']);
	}

	$thumb = return_thumbnail($dbh,$close['closed_by']);
	$action_text = " closed a case: ";
	$casename = case_id_to_casename($dbh,$close['case_id']);
	$time_done = $close['time_closed'];
	$time_formatted = extract_date_time($close['time_closed']);
	$id = $close['id'];
	$what = $close['close_notes'];
	$follow_url = 'index.php?i=Cases.php#cases/' . $close['id'];

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//Case assignments

//Thanks to this gentleman for the query:
//http://stackoverflow.com/questions/9906326/run-a-query-on-a-query-in-mysql

$get_assignments = $dbh->prepare("SELECT assignments_join.*
	FROM cm_case_assignees AS assignments_base
    JOIN cm_case_assignees AS assignments_join ON
    assignments_base.case_id = assignments_join.case_id
	WHERE
    assignments_base.username = '$username' AND
    assignments_join.date_assigned > '$mysqldate'");

$get_assignments->execute();

$assignments = $get_assignments->fetchAll(PDO::FETCH_ASSOC);

foreach ($assignments as $assign) {
	$activity_type = 'assign';

	if ($assign['username'] === $username) {
		$by = 'You were ';
	} else {
		$by = username_to_fullname($dbh,$assign['username']) . ' was ';
	}

	$thumb = return_thumbnail($dbh,$assign['username']);
	$action_text = " assigned to a case: ";
	$casename = case_id_to_casename($dbh,$assign['case_id']);
	$time_done = $assign['date_assigned'];
	$time_formatted = extract_date_time($assign['date_assigned']);
	$id = $assign['id'];
	$what = '';
	$follow_url = 'index.php?i=Cases.php#cases/' . $assign['case_id'];

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//TODO  add journals, events, and board post

sortBySubkey($activities,'time_done');

//print_r($activities);die;

include('../../../html/templates/interior/home_activities.php');