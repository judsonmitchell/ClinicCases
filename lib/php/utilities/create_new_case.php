<?php //Creates a new case in database
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$user = $_SESSION['login'];

//Check to see if user has permission to do this.
if (!$_SESSION['permissions']['add_cases'] == "1")
	{
		$response = array('error' => true,'message' => 'Sorry, you do not have permission to add cases.');
		echo json_encode($response);die;
	}

$q = $dbh->prepare("INSERT INTO `cm` (`id`, `clinic_id`, `first_name`, `m_initial`, `last_name`, `organization`, `date_open`, `date_close`, `case_type`, `professor`, `address1`, `address2`, `city`, `state`, `zip`, `phone1`, `phone2`, `email`, `ssn`, `dob`, `age`, `gender`, `race`, `income`, `per`, `judge`, `pl_or_def`, `court`, `section`, `ct_case_no`, `case_name`, `notes`, `type1`, `type2`, `dispo`, `close_code`, `close_notes`, `referral`, `opened_by`, `time_opened`, `closed_by`, `time_closed`, `dingo`, `fringo`) VALUES (NULL, '0', '', '', '', 'New Case', CURDATE(), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ?, '', '', '', '', '');");

$q->bindParam(1,$user);

$q->execute();

$error = $q->errorInfo();

if (!$error[1])
{
	$new_id = $dbh->lastInsertId();

	$response = array('error' => false,'newId' => $new_id);

	echo json_encode($response);
}