<?php
//script to edit and delete cases
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$action = $_POST['action'];

if (isset($_POST['id']))
	{$id = $_POST['id'];}

switch ($action) {
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

		case 'delete':

			$return = array('message' => 'Case deleted.','error' => false);
			echo json_encode($return);

		break;
	}
}