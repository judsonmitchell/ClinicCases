<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';

function bindPostVals($query_string)
{
	$cols = '';
	$values = array();
	foreach ($query_string as $key => $value) {
		if ($key !== 'action')//'action' is not in the table, so ignore it
		{
			$key_name = ":" . $key;
			$cols .= "`$key` = " . "$key_name,";
			$values[$key_name] = trim($value);
		}
	}

	$columns = rtrim($cols,',');

	return array('columns'=>$columns,'values' => $values);
}

$action = $_POST['action'];

switch ($action) {

	case 'update_profile':

		$post = bindPostVals($_POST);

		$q = $dbh->prepare("UPDATE cm_users SET " . $post['columns'] . " WHERE id = :id");

		$q->execute($post['values']);

		$error = $q->errorInfo();

		break;

	case 'change_password':
		//code...
		break;

	case 'change_picture':
		//Not yet implemented.  Admin must change picture now.
		break;

	case 'change_private_key':
		//code...
		break;

	default:
		# code...
		break;
}

if ($error[1])
{
	$return = array('error' => true, 'message' => $error[1]);

	echo json_encode($return);
}
else
{
	switch ($action) {
		case 'update_profile':
			$return = array('error' => false,'message' => 'Your profile has been updated.');
			echo json_encode($return);
			break;

		case 'change_password':
			$return = array('error' => false,'message' => 'Your password has been changed.');
			echo json_encode($return);
			break;

		case 'change_picture':
			//Not yet implemented.  Admin must change picture now.
		break;

		case 'change_private_key':
			$return = array('error' => false,'message' => 'Your private_key has been changed.');
			echo json_encode($return);
			break;
	}
}
