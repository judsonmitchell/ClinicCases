<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

function trim_value(&$value) { 
    $value = trim($value); 
}

$type = $_POST['type'];

$result_errors = array();

switch ($type) {
	case 'court':
		//if first array element is empty, delete it
		if ($_POST['court'][0] == '')
		{
			unset($_POST['court'][0]);
		}
		else
		//Array must start at index 1 for html purposes, so:
		{
			array_unshift($_POST['court'], '');
			unset($_POST['court'][0]);
		}

        array_walk($_POST['court'],'trim_value');

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
		//if first array element is empty, delete it
		if ($_POST['dispo'][0] == '')
		{
			unset($_POST['dispo'][0]);
		}
		else
		{
			array_unshift($_POST['dispo'], '');
			unset($_POST['dispo'][0]);
		}

        array_walk($_POST['dispo'],'trim_value');

		//clear old values
		$q = $dbh->prepare("TRUNCATE TABLE  `cm_dispos`");

		$q->execute();

		$error = $q->errorInfo();

		if ($error[1]){$result_errors[] = $error[1];}

		//update db
		foreach ($_POST['dispo'] as $key => $value) {
			$update = $dbh->prepare('INSERT INTO cm_dispos (`id`,`dispo`) VALUES (NULL,?)');

			$update->bindParam(1,$value);

			$update->execute();

			$error = $update->errorInfo();

			if ($error[1]){$result_errors[] = $error[1];}
		}

		//update column definition
		$s = serialize($_POST['dispo']);
		$col_update = $dbh->prepare("UPDATE cm_columns SET select_options = ? WHERE db_name = 'dispo'");
		$col_update->bindParam(1,$s);
		$col_update->execute();
		$error = $col_update->errorInfo();
		if ($error[1]){$result_errors[] = $error[1];}

		break;

	case 'case':
		//if first array element is empty, delete it
		if ($_POST['case'][0] == '')
		{
			unset($_POST['case'][0]);
			unset($_POST['case_code'][0]);
		}


		//clear old values
		$q = $dbh->prepare("TRUNCATE TABLE  `cm_case_types`");

		$q->execute();

		$error = $q->errorInfo();

		if ($error[1]){$result_errors[] = $error[1];}

		//update db
        array_walk($_POST['case'],'trim_value');
		$cases = array_combine($_POST['case_code'], $_POST['case']);
		foreach ($cases as $key => $value) {
			$update = $dbh->prepare('INSERT INTO cm_case_types (`id`,`type`,`case_type_code`) VALUES (NULL,?,?)');

			$update->bindParam(1,$value);

			$update->bindParam(2,$key);

			$update->execute();

			$error = $update->errorInfo();

			if ($error[1]){$result_errors[] = $error[1];}
		}

		//update column definition
		$s = serialize($cases);
		$col_update = $dbh->prepare("UPDATE cm_columns SET select_options = ? WHERE db_name = 'case_type'");
		$col_update->bindParam(1,$s);
		$col_update->execute();
		$error = $col_update->errorInfo();
		if ($error[1]){$result_errors[] = $error[1];}

		break;

	case 'clinic':
		//if first array element is empty, delete it
		if ($_POST['clinic_code'][0] == '')
		{
			unset($_POST['clinic_code'][0]);
			unset($_POST['clinic_name'][0]);
		}
        array_walk($_POST['clinic_name'],'trim_value');

		//clear old values
		$q = $dbh->prepare("TRUNCATE TABLE  `cm_clinic_type`");

		$q->execute();

		$error = $q->errorInfo();

		if ($error[1]){$result_errors[] = $error[1];}

		$clinics = array_combine($_POST['clinic_code'], $_POST['clinic_name']);

		foreach ($clinics as $key => $value) {
			$update = $dbh->prepare("INSERT INTO cm_clinic_type (`id`,`clinic_name`,`clinic_code`) VALUES (NULL,?,?)");

			$update->bindParam(1,$value);

			$update->bindParam(2,$key);

			$update->execute();

			$error = $update->errorInfo();

			if ($error[1]){$result_errors[] = $error[1];}
		}

		$s = serialize($clinics);
		$col_update = $dbh->prepare("UPDATE cm_columns SET select_options = ? WHERE db_name = 'clinic_type'");
		$col_update->bindParam(1,$s);
		$col_update->execute();
		$error = $col_update->errorInfo();
		if ($error[1]){$result_errors[] = $error[1];}

		break;

	case 'referral':
		//if first array element is empty, delete it
		if ($_POST['referral'][0] == '')
		{
			unset($_POST['referral'][0]);
		}
		else
		{
			array_unshift($_POST['referral'], '');
			unset($_POST['referral'][0]);
		}
        array_walk($_POST['referral'],'trim_value');

		//clear old values
		$q = $dbh->prepare("TRUNCATE TABLE  `cm_referral`");

		$q->execute();

		$error = $q->errorInfo();

		if ($error[1]){$result_errors[] = $error[1];}

		//update db
		foreach ($_POST['referral'] as $key => $value) {
			$update = $dbh->prepare('INSERT INTO cm_referral (`id`,`referral`) VALUES (NULL,?)');

			$update->bindParam(1,$value);

			$update->execute();

			$error = $update->errorInfo();

			if ($error[1]){$result_errors[] = $error[1];}
		}

		//update column definition
		$s = serialize($_POST['referral']);
		$col_update = $dbh->prepare("UPDATE cm_columns SET select_options = ? WHERE db_name = 'referral'");
		$col_update->bindParam(1,$s);
		$col_update->execute();
		$error = $col_update->errorInfo();
		if ($error[1]){$result_errors[] = $error[1];}

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

		case 'case':
			$response = array('error' => false, "message" => 'Your case types have been updated.');
			echo json_encode($response);
			break;
	}
}

