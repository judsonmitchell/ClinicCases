<?php
//script to edit and delete cases
session_start();
require('../auth/session_check.php');
require('../../../db.php');

function bindPostVals($query_string,$open_close)
{
	$cols = '';
	$values = array();
	foreach ($query_string as $key => $value) {
		if ($key !== 'action')//'action' is not in the table column, so ignore it
		{
			$key_name = ":" . $key;
			$cols .= "`$key` = " . "$key_name,";
			$values[$key_name] = $value;
		}
	}

	//Time opened and closed is not presented to user.  So, we add the values.
	$now =  date('Y-m-d H:i:s');

	if ($open_close === 'open')
		{
			$cols .= "`time_opened` = :time_opened";
			$values[':time_opened'] = $now;
		}

	if ($open_close === 'close')
		{
			$cols .= "`time_closed` = :time_closed";
			$values[':time_closed'] = $now;
		}
		//If $open_close is 'edit', we don't need to add these fields

	$columns = rtrim($cols,',');

	return array('columns'=>$columns,'values' => $values);
}

$action = $_POST['action'];

if (isset($_POST['id']))
	{$id = $_POST['id'];}

if (isset($_POST['phone']))
	{
		$phone = json_decode($_POST['phone']);
		$phone_ser = serialize($phone);
		$_POST['phone'] = $phone_ser;
	}

if (isset($_POST['email']))
	{
		$email = json_decode($_POST['email']);
		$email_ser = serialize($email);
		$_POST['email'] = $email_ser;
	}

if (isset($_POST['adverse_parties']))
	{
		$adv = json_decode($_POST['adverse_parties']);
		$adv_ser = serialize($adv);
		$_POST['adverse_parties'] = $adv_ser;
	}


switch ($action) {

	case 'update_new_case':

		//Because we don't know all the table columns, we rely on an helper function,
		//bindPostVals().  This was very helpful http://stackoverflow.com/q/3773406/49359

		//First, determine if we are opening or closing a case
		if (!empty($_POST['date_close']))
			{$open_close = 'close';}
		else
			{$open_close = 'open';}

		$post = bindPostVals($_POST,$open_close);

		$q = $dbh->prepare("UPDATE cm SET " . $post['columns'] . " WHERE id = :id");

		$q->execute($post['values']);

		$error = $q->errorInfo();

			//now put adverse parties in cm_adverse_parties table
			if (!$error[1])
			{
				if (isset($_POST['adverse_parties']))
				{
					$ap = unserialize($_POST['adverse_parties']);

					foreach ($ap as $a) {

						$q = $dbh->prepare("INSERT INTO cm_adverse_parties (id, case_id, name) VALUES (NULL, :case_id, :name);");

						$data = array('case_id' => $_POST['id'],'name' => $a);

						$q->execute($data);
					}
				}
			}

	break;

	case 'edit':

		$open_close = 'edit';

		$post = bindPostVals($_POST,$open_close);

		$q = $dbh->prepare("UPDATE cm SET " . $post['columns'] . " WHERE id = :id");

		$q->execute($post['values']);

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
				{
					$case_name = $_POST['organization'];
				}
				else
				{
					$case_name = $_POST['first_name'] . " " . $_POST['middle_name']
					. " " . $_POST['last_name'];
				}

			if ($open_close === 'open')
				{$text = 'opened';}
			else
				{$text = 'closed';}

			$return = array("message" => "$case_name is now $text.","error" => false);
			echo json_encode($return);

		break;

		case 'edit':
			if (empty($_POST['first_name']) && empty($_POST['last_name']))
				{
					$case_name = $_POST['organization'];
				}
				else
				{
					$case_name = $_POST['first_name'] . " " . $_POST['middle_name']
					. " " . $_POST['last_name'];
				}

			$return = array("message" => "$case_name case edited.","error" => false);
			echo json_encode($return);

		break;

		case 'delete':

			$return = array('message' => 'Case deleted.','error' => false);
			echo json_encode($return);

		break;
	}
}