<?php
//script to add, update and change status of users
session_start();
require('../auth/session_check.php');
require('../../../db.php');

//Get variables

$action = $_POST['action'];

if (isset($_POST['users']))
{
	$users = $_POST['users'];
}

switch ($action) {
	case 'activate':

		$q = $dbh->prepare("UPDATE cm_users SET status = 'active' WHERE id = :id");

		foreach ($users as $user) {

			$data = array('id' => $user);

			$q->execute($data);
		}

		$error = $q->errorInfo();

		break;

	case 'deactivate':

		$q = $dbh->prepare("UPDATE cm_users SET status = 'inactive' WHERE id = :id");

		foreach ($users as $user) {

			$data = array('id' => $user);

			$q->execute($data);
		}

		$error = $q->errorInfo();

		break;

	case 'activate':
		# code...
		break;

}

if($error[1])

		{
			$return = array('message' => 'Sorry, there was an error. Please try again.','error' => true);
			echo json_encode($return);
		}

		else
		{
			switch ($action) {
				case 'activate':
					$return = array('message'=>'Users activated');
					echo json_encode($return);
					break;

				case 'deactivate':
					$return = array('message'=>'Users deactivated');
					echo json_encode($return);
					break;

			}
		}

