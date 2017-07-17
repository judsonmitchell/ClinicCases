<?php
//script to add, update and delete events in cases
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include('../users/user_data.php');
include('../utilities/names.php');

function flatten_array($mArray) {
	$sArray = array();
    foreach ($mArray as $row) {
        if ( !(is_array($row)) ) {
            if($sArray[] = $row){
            }
        } else {
            $sArray = array_merge($sArray,flatten_array($row));
        }
    }
    return $sArray;
}

//Get variables

$action = $_POST['action'];

$user = $_SESSION['login'];

if (isset($_POST['task']))
	{$task = $_POST['task'];}

if (isset($_POST['where']))
	{$where = $_POST['where'];}

if (isset($_POST['start']))
	{
		$start = $_POST['start'];
		$start_unix = strtotime($_POST['start']);
	 	$start_mysql = date('Y-m-d H:i:s', $start_unix);
	}

if (isset($_POST['end']))
	{
		$end = $_POST['end'];
		$end_unix = strtotime($_POST['end']);
		$end_mysql = date('Y-m-d H:i:s',$end_unix);
	}

if (isset($_POST['responsibles']))
	{$responsibles = $_POST['responsibles'];}

if (isset($_POST['notes']))
	{$notes = $_POST['notes'];}

if (isset($_POST['case_id']))
	{$case_id = $_POST['case_id'];}

if (isset($_POST['all_day']))
	{
        if ($_POST['all_day'] == 'true')
			{
				$all_day = '1';
			}
			else
			{
				$all_day ='0';
			}
	}
	else
		{$all_day = '0';}

if (isset($_POST['event_id']))
	{$event_id = $_POST['event_id'];}


switch ($action) {

	case 'add':  //add to cm_events table

		$add_event = $dbh->prepare("INSERT INTO cm_events (`id`, `case_id`, `set_by`, `task`, `start`,`start_text`, `end`,`end_text`, `all_day`, `status`, `notes`, `location`,`time_added`) VALUES (NULL, :case_id, :user, :task, :start, :start_text, :end, :end_text, :all_day, :status, :notes, :location, NOW());");

		$data = array('case_id' => $case_id, 'user' => $user, 'task' => $task, 'start' => $start_mysql, 'start_text' => $start, 'end' => $end_mysql, 'end_text' => $end, 'all_day' => $all_day, 'notes' => $notes, 'location' => $where, 'status' => 'pending');

		$add_event->execute($data);

		$last_id = $dbh->lastInsertId();

		$error = $add_event->errorInfo();

		//add responsible parties to cm_responsibles

		if (!$error[1])
		{
			$resps = array();

			foreach ($responsibles as $responsible) {

				if (stristr($responsible, '_grp_'))
				{
				//user has selected a group as defined in config
					$group = substr($responsible, 5);
					$all_in_group = all_users_in_group($dbh,$group);
					$resps[] = $all_in_group;
				}
				elseif(stristr($responsible, '_spv_'))
				//user has selected a group that is defined by who the supervisor is
				{
					$supervisor= substr($responsible, 5);
					$all_in_group = all_users_by_supvsr($dbh,$supervisor);
					$resps[] = $all_in_group;
				}
				elseif (stristr($responsible, '_all_users_'))
				{
					$resps[] = all_active_users_a($dbh);
				}
				else
				{
					$resps[] = $responsible;
				}
			}

			$resps_flat = flatten_array($resps);

			$add_resp = $dbh->prepare("INSERT INTO cm_events_responsibles (id,event_id,username,time_added) VALUES (NULL, :last_id,:resp,NOW())");

			for ($i=0; $i < sizeof($resps_flat); $i++) {

				$data = array('last_id' => $last_id,'resp' => $resps_flat[$i]);

				$add_resp->execute($data);

				//notify user via email
				$email = user_email($dbh,$resps_flat[$i]);
				$subject = "ClinicCases: You have been assigned to an event";
				$body = "You have been assigned to an event (" . $_POST['task']  . ") in the " . case_id_to_casename($dbh,$case_id) . " case.\n\n" . CC_EMAIL_FOOTER;
				mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);
					//TODO test on mail server

			}
		}

		break;

	case 'edit':

		$edit_event = $dbh->prepare("UPDATE cm_events SET task = :task, set_by = :user, start = :start, start_text = :start_text, end = :end, end_text = :end_text, all_day = :all_day, location = :location, notes = :notes WHERE id = :event_id");

		$data = array('task' => $task, 'user' => $user, 'start' => $start_mysql, 'start_text' => $start, 'end' => $end_mysql,'end_text' => $end, 'all_day' => $all_day, 'location' => $where, 'notes' => $notes, 'event_id' => $event_id);

		$edit_event->execute($data);

		$error = $edit_event->errorInfo();

		if (!$error[1])
		{
			//First, find out who is currently on the event and put
			//in an array for later use.

			$current = $dbh->prepare("SELECT username FROM cm_events_responsibles WHERE event_id = :event_id");

			$data = array('event_id' => $event_id);

			$current->execute($data);

			$curs = $current->fetchAll(PDO::FETCH_ASSOC);

			$curs_flat = flatten_array($curs);

			//Then delete all of the current assignments
			$delete_old = $dbh->prepare("DELETE FROM cm_events_responsibles WHERE event_id = :event_id");

			$data = array('event_id' => $event_id);

			$delete_old->execute($data); //remove previously assigned users

			//Then put in the current assignments
			$resps = array();

			foreach ($responsibles as $responsible) {

				if (stristr($responsible, '_grp_'))
				{
				//user has selected a group as defined in config
					$group = substr($responsible, 5);
					$all_in_group = all_users_in_group($dbh,$group);
					$resps[] = $all_in_group;
				}
				elseif(stristr($responsible, '_spv_'))
				//user has selected a group that is defined by who the supervisor is
				{
					$supervisor= substr($responsible, 5);
					$all_in_group = all_users_by_supvsr($dbh,$supervisor);
					$resps[] = $all_in_group;
				}
				elseif (stristr($responsible, '_all_users_'))
				{
					$resps[] = all_active_users_a($dbh);
				}
				else
				{
					$resps[] = $responsible;
				}
			}

			$resps_flat = flatten_array($resps);

			$add_resp = $dbh->prepare("INSERT INTO cm_events_responsibles (id,event_id,username,time_added) VALUES (NULL, :last_id,:resp,NOW())");

			for ($i=0; $i < sizeof($resps_flat); $i++) {

				$data = array('last_id' => $event_id,'resp' => $resps_flat[$i]);

				$add_resp->execute($data);
			}

			//Then notify only the newly-added users of the assignement via email
			$new_assignees = array_diff($resps_flat, $curs_flat);

			if (!empty($new_assignees))
			{
				foreach ($new_assignees as $n) {
					$email = user_email($dbh,$resps_flat[$i]);
					$subject = "ClinicCases: You have been assigned to an event";
					$body = "You have been assigned to an event (" . $_POST['task']  . ")in the " . case_id_to_casename($dbh,$case_id) . " case.\n\n" . CC_EMAIL_FOOTER;
					mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);
					//TODO test on mail server
				}
			}
		}

		break;

	case 'delete':

		$delete_event = $dbh->prepare("DELETE FROM cm_events WHERE id = :event_id");

		$data = array('event_id' => $event_id);

		$delete_event->execute($data);

		$error = $delete_event->errorInfo();

		//also remove all event assignments

		if (!$error[1])
		{
			$delete_assign = $dbh->prepare("DELETE FROM cm_events_responsibles WHERE event_id = :event_id");

			$data = array('event_id' => $event_id);

			$delete_assign->execute($data);

			$error = $delete_event->errorInfo();
		}

		break;
};

if($error[1])

		{
			$return = array('message' => 'Sorry, there was an error. Please try again.','error' => true);
			echo json_encode($return);
		}

		else
		{

			switch($action){
			case "add":
			$return = array('message'=>'Event Added');
			echo json_encode($return);
			break;

			case "edit":
			$return = array('message'=>'Event Edited');
			echo json_encode($return);
			break;

			case "delete":
			$return = array('message'=>'Event Deleted');
			echo json_encode($return);
			break;

			}

		}
