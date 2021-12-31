<?php
//Gets upcoming events data for home page calendar, returns json

session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/names.php');

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

		$responsibles[] = array('user_id' => $user_id,'last_name' => $lastname,
			'full_name' => $fullname);
	}

	return $responsibles;
}

$user = $_SESSION['login'];

if (isset($_GET['summary'])){
    $summary = true;
}

$get_events = $dbh->prepare("SELECT * from cm_events, cm_events_responsibles
	WHERE cm_events_responsibles.username = :user
	AND cm_events_responsibles.event_id = cm_events.id");

$data = array('user' => $user);

$get_events -> execute($data);

$events = $get_events->fetchAll(PDO::FETCH_ASSOC);

$events_data = array();

foreach ($events as $event)
{
	if ($event['all_day'] == '1')
		{$all_day = true;}
	else
		{$all_day = false;}

	if ($event['case_id'] == 'NC')  //Non-case items have a different color
		{$bg_color = '#DAA520';}
	else
		{$bg_color = '#36C';}

	$case_name = case_id_to_casename($dbh,$event['case_id']);

	//get an array of data about everybody assigned to event
	$resps = get_responsibles($dbh, $event['event_id']);

	//Add last names of repsonsible parties to each event title
	$event_last_names = null;

	$n = 0;

	$large_group = false;

	foreach ($resps as $resp) {

		if ($n > 5)  //Stop loop if more than 5 users
			{
				$event_last_names .= 'and others   ';
				$large_group = true; //notify json that there will be a lot of thumbnails
				break;
			}
		else
			{$event_last_names .= $resp['last_name'] . ', ';}

		$n++;

	}

	$title = $event['task'] . ' (' . substr($event_last_names, 0, -2) . ')';

	if ($_SESSION['permissions']['delete_events'] == '1')
		{$delete = true;}
	else
		{$delete = false;}

	//generate the array
    if ($summary){
        $summary_date = explode(' ', $event['start']);
        $desc = $title . $event['notes'];
        if ($event['case_id'] == 'NC') {
            $classname = 'cal-noncase-event';
        } else {
            $classname = 'cal-case-event';
        }
        $events_data[] = array('date' => $summary_date[0], 'badge' => false, 'title' => $event['task'],'body' => $desc,'footer' =>'' ,'classname' => $classname,'id' => $event['event_id']);

    } else {
        $events_data[] = array('id' => $event['event_id'],'title' => $title,
        'shortTitle' => $event['task'],'start' => $event['start'],
        'end' => $event['end'], 'allDay' => $all_day,
        'description' => $event['notes'],'where' => $event['location'],
        'backgroundColor' => $bg_color,'caseId' => $event['case_id'],
        'caseName' => $case_name,'users' => $resps,'canDelete' => $delete,
        'largeGroup' => $large_group);
    }

}

$events_json = json_encode($events_data);

echo $events_json;
