<?php
//script to add, update and change status of users
session_start();
require('../auth/session_check.php');
require('../../../db.php');

function bindPostVals($query_string)
{print_r($query_string);die;
	$cols = '';
	$values = array();
	$supervisor_string = null;
	foreach ($query_string as $key => $value) {
		if ($key !== 'action' && $key !=='supervisors')
		{
			$key_name = ":" . $key;
			$cols .= "`$key` = " . "$key_name,";
			$values[$key_name] = $value;
		}
		//Put supervisors into one string
		if ($key === 'supervisors')
			{
				if ($value)
				{
					$supervisor_string .= $value . ',';
				}
			}
	}

	//add supervisors
	$key_name = ":supervisors";
	$cols .= "`supervisors` = $key_name,";
	$values[$key_name] = $supervisor_string;

	$columns = rtrim($cols,',');

	return array('columns'=>$columns,'values' => $values);
}

//Get variables

$action = $_POST['action'];

if (isset($_POST['users']))
{
	$users = $_POST['users'];
}

switch ($action) {
	case 'activate':

		$q = $dbh->prepare("UPDATE cm_users SET status = 'active', new = '' WHERE id = :id");

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

	case 'delete':

		$q = $dbh->prepare("DELETE FROM cm_users WHERE id = ?");

		$q->bindParam(1, $users);

		$q->execute();

		$error = $q->errorInfo();

		break;

	case 'update':

		$post = bindPostVals($_POST);

		//$q = $dbh->prepare("UPDATE cm SET " . $post['columns'] . " WHERE id = :id");

		//$q->execute($post['values']);

		print_r($post);die;

		$error = $q->errorInfo();

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

				case 'delete':
					$return = array('message'=>'User deleted.');
					echo json_encode($return);
					break;

			}
		}

