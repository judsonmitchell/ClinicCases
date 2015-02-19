<?php
//Generate RSS feed for activities and journals
require('../db.php');
require('../lib/php/utilities/names.php');
require('../lib/php/utilities/convert_times.php');


//function to sort the activities array by subkey - date

function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC) {

    foreach ($array as $subarray) {

        $keys[] = $subarray[$subkey];
    }

    array_multisort($keys, $sortType, $array);
}

//Define variables
$key = $_GET['key'];

$type = $_GET['type'];

$phpdate = strtotime('-60 days');

$mysqldate = date( 'Y-m-d H:i:s', $phpdate );

//Check if key is valid
$q =$dbh->prepare("SELECT username,grp FROM cm_users WHERE private_key = ?");

$q->bindParam(1,$key);

$q->execute();

if ($q->rowCount() < 1)
{
	die('No feed available.  Please check the URL for this feed by going to your ClinicCases preferences and clicking "Private Key."');
}

else
{
	if ($type == 'activities')
	{
		$data = $q->fetch();

		$username = $data['username'];

		//Check to see if this person is an Administrator
		$q = $dbh->prepare("SELECT * FROM cm_groups WHERE group_name = ?");

		$q->bindParam(1,$data['grp']);

		$q->execute();

		$p = $q->fetch(PDO::FETCH_ASSOC);

		//Now get the data
		//Case notes
		$get_notes = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
			cm_case_notes.id as note_id,
			cm_case_assignees.username as assign_user,
			cm_case_notes.username as note_user
			FROM cm_case_assignees,cm_case_notes
			WHERE cm_case_assignees.username = '$username'
			AND cm_case_assignees.status = 'active'
			AND cm_case_notes.case_id = cm_case_assignees.case_id
			AND cm_case_notes.datestamp >= '$mysqldate'");

		$get_notes->execute();

		$casenotes = $get_notes->fetchAll(PDO::FETCH_ASSOC);

		foreach ($casenotes as $note) {

			if ($note['note_user'] === $username) {
				$by = 'You';
			} else {
				$by = username_to_fullname($dbh,$note['note_user']);
			}

			$action_text = " added a case note to ";
			$casename = case_id_to_casename($dbh,$note['case_id']);
			$time_done = $note['datestamp'];
			$time_formatted = extract_date_time($note['datestamp']);
			$id = $note['note_id'];
			$what = htmlentities($note['description']);
			$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $note['case_id'];

			$item = array('by' => $by,'action_text' => $action_text,'casename' => $casename,
				'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
				'time_formatted' => $time_formatted);

			$activities[] = $item;

		}

		//Get any non-case time
		$get_noncase = $dbh->prepare("SELECT * FROM cm_case_notes
			WHERE username = '$username'
			AND case_id = 'NC'
			AND datestamp >= '$mysqldate'");

		$get_noncase->execute();

		$noncases = $get_noncase->fetchAll(PDO::FETCH_ASSOC);

		foreach ($noncases as $noncase) {
			$by = 'You';
			$action_text = " added non-case activity ";
			$casename = '';
			$time_done = $noncase['datestamp'];
			$time_formatted = extract_date_time($noncase['datestamp']);
			$id = $noncase['id'];
			$what = htmlentities($noncase['description']);
			$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $noncase['case_id'];

			$item = array('by' => $by,'action_text' => $action_text,'casename' => $casename,
				'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
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
			if ($document['doc_user'] === $username) {
				$by = 'You';
			} else {
				$by = username_to_fullname($dbh,$document['doc_user']);
			}
			$action_text = ' added a document to ';
			$casename = case_id_to_casename($dbh,$document['case_id']);
			$time_done = $document['date_modified'];
			$time_formatted = extract_date_time($document['date_modified']);
			$id = $document['doc_id'];
			$doc_title = htmlentities($document['name']);
			$what = "<a href='#' data-id='" . $id . "' class='doc_view " . $document['extension'] . "'>" . $doc_title . "</a>";
			$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $document['case_id'] . '/3';
			//3 indicates third item in nav list

			$item = array('by' => $by,'action_text' => $action_text,'casename' => $casename,
				'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
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

			if ($open['opened_by'] === $username) {
				$by = 'You';
			} else {
				$by = username_to_fullname($dbh,$open['opened_by']);
			}

			$action_text = " opened a case: ";
			$casename = case_id_to_casename($dbh,$open['case_id']);
			$time_done = $open['time_opened'];
			$time_formatted = extract_date_time($open['time_opened']);
			$id = $open['id'];
			$what = $open['notes'];
			$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $open['id'];

			$item = array('by' => $by,'action_text' => $action_text,'casename' => $casename,
				'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
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

			if ($close['closed_by'] === $username) {
				$by = 'You';
			} else {
				$by = username_to_fullname($dbh,$close['closed_by']);
			}

			$action_text = " closed a case: ";
			$casename = case_id_to_casename($dbh,$close['case_id']);
			$time_done = $close['time_closed'];
			$time_formatted = extract_date_time($close['time_closed']);
			$id = $close['id'];
			$what = $close['close_notes'];
			$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $close['id'];

			$item = array('by' => $by, 'action_text' => $action_text,'casename' => $casename,
				'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
				'time_formatted' => $time_formatted);

			$activities[] = $item;

		}

		//Case assignments
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

			$action_text = " assigned to a case: ";
			$casename = case_id_to_casename($dbh,$assign['case_id']);
			$time_done = $assign['date_assigned'];
			$time_formatted = extract_date_time($assign['date_assigned']);
			$id = $assign['id'];
			$what = '';
			$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $assign['case_id'];

			$item = array('by' => $by, 'action_text' => $action_text,'casename' => $casename,
				'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
				'time_formatted' => $time_formatted);

			$activities[] = $item;

		}

		//Create Events
		$get_events = $dbh->prepare("SELECT * FROM cm_events WHERE set_by = :username
			AND time_added >= :mysqldate");

		$data = array('username' => $username, 'mysqldate' => $mysqldate);

		$get_events->execute($data);

		$events = $get_events->fetchAll(PDO::FETCH_ASSOC);

		foreach ($events as $event) {

			if ($event['set_by'] === $username) {
				$by = 'You';
			} else {
				$by = username_to_fullname($dbh,$assign['username']);
			}

			$action_text = " created an event in ";
			$casename = case_id_to_casename($dbh,$event['case_id']);
			$time_done = $event['time_added'];
			$time_formatted = extract_date_time($event['time_added']);
			$id = $event['id'];
			$what = $event['task'];
			$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $event['case_id'] . '/4';

			$item = array('by' => $by, 'action_text' => $action_text,'casename' => $casename,
				'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
				'time_formatted' => $time_formatted);

			$activities[] = $item;

		}

		//Assigned to Events
		$get_event_assign = $dbh->prepare("SELECT * FROM cm_events,cm_events_responsibles WHERE cm_events.id = cm_events_responsibles.event_id AND cm_events_responsibles.username = :username AND cm_events_responsibles.time_added >= :mysqldate");

		$data = array('username' => $username, 'mysqldate' => $mysqldate);

		$get_event_assign->execute($data);

		$ev_assigns = $get_event_assign->fetchAll(PDO::FETCH_ASSOC);

		foreach ($ev_assigns as $e) {

			if ($e['username'] === $username) {
				$by = 'You';
			} else {
				$by = username_to_fullname($dbh,$e['username']);
			}

			$action_text = " were assigned to an event in ";
			$casename = case_id_to_casename($dbh,$e['case_id']);
			$time_done = $e['time_added'];
			$time_formatted = extract_date_time($e['time_added']);
			$id = $e['id'];
			$what = $e['task'];
			$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $e['case_id'] .'/4';

			$item = array('by' => $by, 'action_text' => $action_text,'casename' => $casename,
				'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
				'time_formatted' => $time_formatted);

			$activities[] = $item;
		}

		//cases that have opened
		if ($p['add_cases'] == '1'  && $p['view_all_cases'] == '1')
		{
			$get_opened_cases = $dbh->prepare("SELECT * FROM cm
				WHERE time_opened >= '$mysqldate'");

			$get_opened_cases->execute();

			$opened = $get_opened_cases->fetchAll(PDO::FETCH_ASSOC);

			foreach ($opened as $open) {
				if ($open['opened_by'] === $username) {
					$by = 'You';
				} else {
					$by = username_to_fullname($dbh,$open['opened_by']);
				}

				$action_text = " opened a case: ";
				$casename = case_id_to_casename($dbh,$open['id']);
				$time_done = $open['time_opened'];
				$time_formatted = extract_date_time($open['time_opened']);
				$id = $open['id'];
				$what = $open['notes'];
				$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $open['id'];

				$item = array('by' => $by,'action_text' => $action_text,'casename' => $casename,
					'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
					'time_formatted' => $time_formatted);

				$activities[] = $item;
			}
		}

		//cases that have been closed
		if ($p['close_cases'] == '1'  && $p['view_all_cases'] == '1')
		{
			$get_closed_cases = $dbh->prepare("SELECT * FROM cm
			WHERE time_closed >= '$mysqldate'");

			$get_closed_cases->execute();

			$closed = $get_closed_cases->fetchAll(PDO::FETCH_ASSOC);

			foreach ($closed as $close) {
				if ($close['closed_by'] === $username) {
					$by = 'You';
				} else {
					$by = username_to_fullname($dbh,$close['closed_by']);
				}

				$action_text = " closed a case: ";
				$casename = case_id_to_casename($dbh,$close['id']);
				$time_done = $close['time_closed'];
				$time_formatted = extract_date_time($close['time_closed']);
				$id = $close['id'];
				$what = $close['close_notes'];
				$follow_url = CC_BASE_URL .  'index.php?i=Cases.php#cases/' . $close['id'];

				$item = array('by' => $by,'action_text' => $action_text,'casename' => $casename,
					'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
					'time_formatted' => $time_formatted);

				$activities[] = $item;

				}

		}

		//new users who have requested access
		if ($p['activate_users'] == '1')
		{
			$get_new_users = $dbh->prepare("SELECT * FROM cm_users
				WHERE date_created >= '$mysqldate' AND new = 'yes'");

			$get_new_users->execute();

			$news = $get_new_users->fetchAll(PDO::FETCH_ASSOC);

			foreach ($news as $new) {

				$by = username_to_fullname($dbh,$new['username']);
				$thumb = 'people/tn_no_picture.png';
				$action_text = " signed up for ClinicCases ";
				$time_done = $new['date_created'];
				$time_formatted = extract_date_time($new['date_created']);
				$what = 'Please review this application.';
				$follow_url = CC_BASE_URL .  'index.php?i=Users.php';
				$casename = '(view here)';
				$id = null;

				$item = array('by' => $by,'action_text' => $action_text,'casename' => $casename,
					'id' => $id,'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
						'time_formatted' => $time_formatted);

				$activities[] = $item;
			}
		}

		//TODO add board posts

		//Sort the activity array
		if (!empty($activities)) {
			sortBySubkey($activities,'time_done');
		}

		//Create RSS
		$body="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
		<rss version=\"2.0\">
		<channel>
			<title>Latest Activity - ClinicCases - " . CC_PROGRAM_NAME . "</title>
			<link>" . CC_BASE_URL . "</link>
			<description>Notification of new case activity on ClinicCases</description>
		";

		foreach ($activities as $a) {
			$body.= "<item>
					<title>" . $a['by'] . " " . $a['action_text'] . $a['casename'] . "</title>
					<link>" . $a['follow_url'] . "</link>
					<description> <![CDATA[" .  $a['what'] . "]]> </description>
					<pubDate>" . $a['time_done'] . "</pubDate>
					</item>
					";
		}

		$body .= "</channel></rss>";

		echo $body;

	}
}
