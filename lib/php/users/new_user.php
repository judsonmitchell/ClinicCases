<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';

$temp_username = rand();

$q = $dbh->prepare("INSERT INTO `cm_users` (`id`, `first_name`, `last_name`, `email`, `mobile_phone`, `office_phone`, `home_phone`, `grp`, `username`, `password`, `supervisors`, `picture_url`, `timezone_offset`, `status`, `new`, `date_created`, `pref_case`, `pref_journal`, `pref_case_prof`, `evals`, `private_key`, `force_new_password`) VALUES (NULL, '', '', '', '', '', '', '', '$temp_username', '', '', 'people/no_picture.png', '1', 'inactive', '', CURRENT_TIMESTAMP, 'on', 'on', 'on', '', '', '0');");

$q->execute();

$error = $q->errorInfo();

if ($error[1])
{print_r($error);die;
	$response = array('error' => true, "message" => "Sorry, there was an error creating the new user.");

	echo json_encode($response);
}

else
{
	$last_id = $dbh->lastInsertId();

	$response = array('id' => $last_id);

	echo json_encode($response);

}



