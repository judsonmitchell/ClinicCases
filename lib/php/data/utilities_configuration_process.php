<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$type = $_POST['type'];

$result_errors = array();

switch ($type) {
	case 'court':
		//if first array element is empty, delete it
		if ($_POST['court'][0] == '')
		{
			unset($_POST['court'][0]);
		}

		//clear old values
		$q = $dbh->prepare("TRUNCATE TABLE  `cm_courts`");

		$q->execute();

		$error = $q->errorInfo();

		if ($error[1]){$result_errors[] = $error[1];}

		//update db
		foreach ($_POST['court'] as $key => $value) {
			$update = $dbh->prepare('INSERT INTO cm_courts (`id`,`court`) VALUES (NULL,?)');

			$update->bindParam(1,$value);

			$update->execute();

			$error = $update->errorInfo();

			if ($error[1]){$result_errors[] = $error[1];}
		}

		//update column definition
		$s = serialize($_POST['court']);
		$col_update = $dbh->prepare("UPDATE cm_columns SET select_options = ? WHERE db_name = 'court'");
		$col_update->bindParam(1,$s);
		$col_update->execute();
		$error = $col_update->errorInfo();
		if ($error[1]){$result_errors[] = $error[1];}

		break;

	case 'dispo':
		# code...
		break;

	case 'clinic':
		# code...
		break;

	case 'referral':
		# code...
		break;
}

if (count($result_errors) > 0)
{
	$response = array('error' => true,'message'=>'Sorry, there was an error');
	echo json_encode($response);
}
else
{
	switch ($type) {
		case 'court':
			$response = array('error' => false, "message" => 'Your courts have been updated.');
			echo json_encode($response);
			break;

		case 'dispo':
			$response = array('error' => false, "message" => 'Your dispositions have been updated.');
			echo json_encode($response);
			break;

		case 'referral':
			$response = array('error' => false, "message" => 'Your referrals have been updated.');
			echo json_encode($response);
			break;

		case 'clinic':
			$response = array('error' => false, "message" => 'Your clinics have been updated.');
			echo json_encode($response);
			break;
	}
}

