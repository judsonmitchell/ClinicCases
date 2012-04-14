<?php
session_start();
//require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/thumbnails.php');
require('../utilities/names.php');

//$user = $_SESSION['login'];
$user = 'jmitchell';
//$id = $_POST['case_id'];
$id = '1175';

function get_responsibles($dbh,$event_id) //get names of all users on event
{
	$q = $dbh->prepare("SELECT * FROM cm_events_responsibles
		WHERE event_id = '$event_id'");

	$q->execute();

	$users = $q->fetchAll(PDO::FETCH_ASSOC);

	$responsibles = array();

	foreach ($users as $user) {

		$lastname = username_to_lastname($dbh,$user['username']);

		$fullname = username_to_fullname($dbh,$user['username']);

		$user_id = username_to_userid($dbh,$user['username']);

		$thumb = return_thumbnail($dbh,$user['username']);

		$responsibles[] = array('user_id' => $user_id,'last_name' => $lastname,
			'full_name' => $fullname, 'thumb' => $thumb);
	}

	return $responsibles;
}

//Get all events for this case
$get_events = $dbh->prepare("SELECT * from cm_events
	WHERE case_id = :id ORDER BY start DESC");

$data = array('id' => $id);

$get_events->execute($data);

$events = $get_events->fetchAll(PDO::FETCH_ASSOC);

//print_r($events);die;

foreach ($events as $event) {

	$resps = get_responsibles($dbh,$event['id']);

   	array_push($event, $resps);
}

include('../../../html/templates/interior/cases_events.php');
