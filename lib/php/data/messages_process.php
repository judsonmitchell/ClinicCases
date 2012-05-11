<?php
//script to add, update and delete events in cases
session_start();
require('../auth/session_check.php');
require('../../../db.php');

//Get variables

$action = $_POST['action'];

$user = $_SESSION['login'];

if (isset($_POST['id']))
	{$id = $_POST['id'];}


switch ($action) {

	case 'star_on':  //add start to message

		$q = $dbh->prepare("UPDATE cm_messages SET starred = CONCAT(starred,:user) WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'star_off':  //remove star from message

		$q = $dbh->prepare("UPDATE cm_messages SET starred = REPLACE(starred,:user,'') WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();


		break;

	case 'mark_read':


		break;

	case 'archive':


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
			case "archive":
			$return = array('message'=>'Message Archived');
			echo json_encode($return);
			break;

			case "star_on":
			$return = array('message'=>'OK');
			echo json_encode($return);
			break;

			case "star_off":
			$return = array('message'=>'OK');
			echo json_encode($return);
			break;

			}

		}