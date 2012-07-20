<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/convert_times.php';
include '../utilities/names.php';
include '../utilities/thumbnails.php';

$user = $_SESSION['login'];

if ($_SESSION['permissions']['reads_journals'] == '1')
{
	$sql = "SELECT * FROM cm_journals WHERE reader LIKE '$user,%'
			OR reader LIKE '%,$user,%'";
}
elseif ($_SESSION['permissions']['writes_journals'] == '1')
{
	$sql = "SELECT * FROM cm_journals WHERE username LIKE '$user'";
}
else
{
	die("Sorry, you do not have permission to read or write journals.");
}

//Get column names
$c = $dbh->prepare("DESCRIBE cm_journals");

$c->execute();

$cols = $c->fetchAll(PDO::FETCH_COLUMN);

//Get output
$q = $dbh->prepare($sql);

$q->execute();

$error = $q->errorInfo();

while ($result = $q->fetch(PDO::FETCH_ASSOC))
	{

		$rows= array();

		//Add user picture
		$pic = "<img src='" . return_thumbnail($dbh,$result['username']) . "' border='0'>";

		$rows[] = $pic;

		//Format data
		$result['username'] = username_to_fullname($dbh,$result['username']);

		$result['date_added'] = extract_date_time_sortable($result['date_added']);

		foreach ($cols as $col) {

		  	$rows[] = $result[$col];

		 }

		$output['aaData'][] = $rows;
	}

	if ($q->rowCount() < 1)
	{
		$output['aaData'] = array();
	}

echo json_encode($output);

