<?php
//script to edit and delete cases
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$action = $_POST['action'];

if (isset($_POST['id']))
	{$id = $_POST['id'];}

switch ($action) {

	case 'update_new_case':

	//Because we don't know all the table fields, we must loop through the
	//$_POST request and do an update query for each field.
	//Look at this http://stackoverflow.com/q/3773406/49359 if problems develop
	foreach ($_POST as $key => $value) {

		if ($key != 'action')//'action' is not in the table column, so ignore it
		{
			$q = $dbh->prepare("UPDATE cm SET :field = :value WHERE id = :id");

			$col_name = "`" . $key . "`";

			$data = array('field' => $col_name,'value' => $value,'id' => $id);

			$q->execute($data);
		}
	}

	$error = $q->errorInfo();

	break;

	case 'delete':

	$q = $dbh->prepare("DELETE FROM cm WHERE id = ?");

	$q->bindParam(1, $id);

	$q->execute();

	$error = $q->errorInfo();

	break;

}

if ($error[1])
{
	$return = array('message' => 'Sorry, there was an error. Please try again.','error' => true);
	echo json_encode($return);
}
else
{
	switch ($action) {

		case 'update_new_case':
			if (empty($_POST['first_name']) && empty($_POST['last_name']))
				{$case_name = $_POST['organization'];}
				else
				{$case_name = $_POST['first_name'] . " " . $_POST['middle_name'] . " " . $_POST['last_name'];}

			$return = array("message" => "$case_name is now opened.","error" => false);
			echo json_encode($return);

		break;

		case 'delete':

			$return = array('message' => 'Case deleted.','error' => false);
			echo json_encode($return);

		break;
	}
}