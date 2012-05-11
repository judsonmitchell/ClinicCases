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

	case 'send':


	break;

	case 'reply':


	break;

	case 'forward':

	break;

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

		$q = $dbh->prepare("UPDATE cm_messages SET read = CONCAT(read,:user) WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'mark_unread':

		$q = $dbh->prepare("UPDATE cm_messages SET read = REPLACE(read,:user,'') WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'archive':

		$q = $dbh->prepare("UPDATE cm_messages SET archive = CONCAT(archive,:user) WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'unarchive':

		$q = $dbh->prepare("UPDATE cm_messages SET archive = REPLACE(archive,:user,'') WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

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

			case "send":
			$return = array('message'=>'Message sent.');
			echo json_encode($return);
			break;

			case "reply":
			$return = array('message'=>'Reply sent.');
			echo json_encode($return);
			break;

			case "forward":
			$return = array('message'=>'Message forwarded.');
			echo json_encode($return);
			break;

			case "archive":
			$return = array('message'=>'Message archived.');
			echo json_encode($return);
			break;

			case "unarchive":
			$return = array('message'=>'Message returned to Inbox.');
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

			case "mark_read":
			$return = array('message'=>'OK');
			echo json_encode($return);
			break;

			case "mark_unread":
			$return = array('message'=>'Message marked unread.');
			echo json_encode($return);
			break;

			}

		}