<?php
//session_start();
//require('../auth/session_check.php');
require('../../../db.php');
include('../utilities/thumbnails.php');
include('../utilities/names.php');
include('../utilities/convert_times.php');
include('../auth/last_login.php');

function sortBySubkey(&$array, $subkey, $sortType = SORT_ASC) {

    foreach ($array as $subarray) {

        $keys[] = $subarray[$subkey];
    }

    array_multisort($keys, $sortType, $array);
}

//$username = $_SESSION['login'];
$username = 'jmitchell';

//$last_login = get_last_login($dbh,$username);

//$last_login = '2012-01-01 06:09:36';

$phpdate = strtotime('-1 year');

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
FROM cm_case_assignees,cm_case_notes WHERE cm_case_assignees.username = '$username' AND cm_case_notes.case_id = cm_case_assignees.case_id AND cm_case_notes.datestamp >= '$mysqldate'");

$get_notes->execute();

$casenotes = $get_notes->fetchAll(PDO::FETCH_ASSOC);

foreach ($casenotes as $note) {
	$type = 'casenote';
	$by = username_to_fullname($dbh,$note['note_user']);
	$id = $note['note_id'];
	$what = $note['description'];
	$time = $note['datestamp'];
	$casename = case_id_to_casename($dbh,$note['case_id']);

	$item = array('type' => $type, 'what' => $what, 'id' => $id, 'by' => $by,'time' => $time,'case_title' => $casename);

	$activities[] = $item;

}

//Documents
$get_documents = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
	cm_documents.id as doc_id,
	cm_case_assignees.username as assign_user,
	cm_documents.username as doc_user
FROM cm_case_assignees,cm_documents WHERE cm_case_assignees.username = '$username' AND cm_documents.case_id = cm_case_assignees.case_id AND cm_documents.date_modified >= '$mysqldate' AND cm_documents.name != ''");

$get_documents->execute();

$documents = $get_documents->fetchAll(PDO::FETCH_ASSOC);

foreach ($documents as $document) {
	$type = 'document';
	$by = username_to_fullname($dbh,$document['doc_user']);
	$id = $document['doc_id'];
	$what = $document['name'];
	$time = $document['date_modified'];
	$casename = case_id_to_casename($dbh,$document['case_id']);

	$item = array('type' => $type, 'by' => $by, 'id' => $id, 'what' => $what,'time' => $time,'case_title' => $casename);

	$activities[] = $item;
}

sortBySubkey($activities,'time');

print_r($activities);die;


include('../../../html/templates/interior/home_activities.php');