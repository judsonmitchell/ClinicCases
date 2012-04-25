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
	{$all_day = '1';}
	else
		{$all_day = '0';}

switch ($action) {

	case 'add':  //add to cm_events table

		$add_event = $dbh->prepare("INSERT INTO cm_events (`id`, `case_id`, `set_by`, `task`, `date_set`, `start`, `end`, `all_day`, `status`, `notes`, `where`, `prof`, `archived`, `time_added`) VALUES (NULL, :case_id, :user, :task, '0000-00-00', :start, :end, '', :all_day, :notes, :where, '', 'n', NOW());");

		$data = array('case_id' => $case_id, 'user' => $user, 'task' => $task, 'start' => $start_c, 'end' => $end_c, 'all_day' => $all_day, 'notes' => $notes, 'where' => $where);

		$add_event->execute($data);

		$last_id = $add_Event->lastInsertId();

		//add responsible parties to cm_responsibles

		foreach ($responsibles as $responsible) {

			$add_resp = $dbh->prepare("INSERT INTO cm_events_responsibles (id,event_id,username) VALUES (NULL, :last_id,:resp)");

			$data = array('resp' => $responsible['']);
		}

};