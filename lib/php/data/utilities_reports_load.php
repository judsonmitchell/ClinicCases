<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$user = $_SESSION['login'];

if (isset($_GET['type']))
{
	$type = $_GET['type'];
}

if (isset($_GET['date_start']))
{
	$date_start = $_GET['date_start'] . ' 00:00:00';
}

if (isset($_GET['date_end']))
{
	$date_end = $_GET['date_end'] . ' 23:59:59';
}

//Types of queries
//1. Case activity by group
//2. Case activity by individual (displays case notes)

switch ($type) {
	case 'user':
		$sql = "SELECT * FROM cm_case_notes WHERE `username` = :user AND `date` > :date_start AND `date` < :date_end";

		$q = $dbh->prepare($sql);

		$data = array('user' => $user,'date_start' => $date_start, 'date_end' => $date_end);

		break;

	case 'grp':
		$sql = "SELECT * FROM cm_case_notes WHERE username = :user_group";

		$q = $dbh->prepare($sql);

		$q->bindParam(1,$user_group);

		break;

	case 'supvsr_grp':
		$sql ="";
		break;

	case 'case';
		if ($_SESSION['permissions']['view_all_cases'] == '1')
		{
			$sql = "";
		}
		else
		{
			$sql = "";
		}
		break;
}

$q->execute($data);

$data = $q->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $data) {
	# code...
}



