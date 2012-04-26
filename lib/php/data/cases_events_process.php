<?php
//script to add, update and delete events in cases
session_start();
require('../auth/session_check.php');
require('../../../db.php');

//Get variables

$action = $_POST['action'];

$user = $_SESSION['login'];

if (isset($_POST['task']))
	{$task = $_POST['task'];}

if (isset($_POST['where']))
	{$where = $_POST['where'];}

if (isset($_POST['start']))
	{
		$start = strtotime($_POST['start']);
	 	$start_c = date('Y-m-d H:i:s', $start);
	}

if (isset($_POST['end']))
	{
		$end = strtotime($_POST['end']);
		$end_c = date('Y-m-d H:i:s',$end);
	}

if (isset($_POST['responsibles']))
	{$responsibles = $_POST['responsibles'];}

if (isset($_POST['notes']))
	{$notes = $_POST['notes'];}

if (isset($_POST['case_id']))
	{$case_id = $_POST['case_id'];}

if (isset($_POST['all_day']))
	{
		if ($_POST['all_day'] == 'on')
			{
				$all_day = '1';
			}
			else
			{
				$all_day ='0';
			}
	}

if (isset($_POST['event_id']))
	{$event_id = $_POST['event_id'];}


switch ($action) {

	case 'add':  //add to cm_events table

		$add_event = $dbh->prepare("INSERT INTO cm_events (`id`, `case_id`, `set_by`, `task`, `start`, `end`, `all_day`, `status`, `notes`, `where`,`time_added`) VALUES (NULL, :case_id, :user, :task, :start, :end, :all_day, :status, :notes, :where_val, NOW());");

		$data = array('case_id' => $case_id, 'user' => $user, 'task' => $task, 'start' => $start_c, 'end' => $end_c, 'all_day' => $all_day, 'notes' => $notes, 'where_val' => $where, 'status' => 'pending');

		$add_event->execute($data);

		$last_id = $dbh->lastInsertId();

		$error = $add_event->errorInfo();

		//add responsible parties to cm_responsibles

		if (!$error[1])
		{
			foreach ($responsibles as $responsible) {

				$add_resp = $dbh->prepare("INSERT INTO cm_events_responsibles (id,event_id,username) VALUES (NULL, :last_id,:resp)");

				$data = array('last_id' => $last_id,'resp' => $responsible);

				$add_resp->execute($data);
			}
		}

		break;

	case 'edit':

		//insert code

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
			$return = array('message'=>'Event Edited','id' => $id);
			echo json_encode($return);
			break;

			case "delete":
			$return = array('message'=>'Event Deleted');
			echo json_encode($return);
			break;

			}

		}