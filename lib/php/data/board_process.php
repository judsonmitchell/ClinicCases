<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/names.php';
include '../users/user_data.php';

$user = $_SESSION['login'];

$action = $_POST['action'];

switch ($action) {

	case 'new':
		$q = $dbh->prepare("INSERT INTO `cm_board` (`id`, `title`, `body`, `color`, `author`, `viewers`, `time_added`, `time_edited`) VALUES (NULL, '', '', '', ?, '', NOW(), NOW());");

		$q->bindParam(1,$user);

		$q->execute();

		$error = $q->errorInfo();

		$post_id = $dbh->lastInsertId();

		break;

	case 'edit':
		# code...
		break;

	case 'delete':

		break;
}

if ($error[1])
{
	$return = array('error' => true,'message'=>'Sorry, there was an error.');

	echo json_encode($return);
}
else
{
	switch ($action) {
		case 'new':

			$response = array('error' => false,'post_id' => $post_id);

			echo json_encode($response);

			break;

		case 'edit':

		break;

		case 'delete':

		break;

	}
}