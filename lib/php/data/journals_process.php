<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';

$id = $_POST['id'];

$type = $_POST['type'];

switch ($type) {

	case 'mark_read':
		$q = $dbh->prepare("UPDATE cm_journals SET `read` = 'yes' WHERE `id` = ?");

		$q->bindParam(1,$id);

		$q->execute();

		$error = $q->errorInfo();

		break;

	case 'archive':

		break;

	case 'new':

		break;

	case 'edit':

		break;

	case 'add_comment':

		break;
}

if ($error[1])
{
	$return = array('error' => true,'message','Sorry, there was an error.');

	echo json_encode($return);
}
else
{
	switch ($type) {
		case 'mark_read':
			$return = array('error' => false);
			echo json_encode($return);
			break;

		default:
			# code...
			break;
	}
}