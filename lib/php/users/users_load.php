<?php
// session_start();
// require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/convert_times.php';
include '../utilities/names.php';
include '../utilities/thumbnails.php';

//Columns we want from the users table
$columns = array("id", "picture_url","first_name", "last_name", "email", "mobile_phone", "office_phone", "home_phone", "grp", "username", "supervisors","status", "new", "date_created");

$cols = implode(', ', $columns);

$q = $dbh->prepare("SELECT $cols FROM `cm_users`");

$q->execute();

while ($result = $q->fetch(PDO::FETCH_ASSOC))
{
	$rows= array();

	foreach ($columns as $col)
		{

			if ($col === 'picture_url')
			{
				$th = thumbify($result[$col]);
				$rows[] = "<img src = '" . $th . "'></img>";
			}
			else
			{
			$rows[] = $result[$col];
			}
		}

	$output['aaData'][] = $rows;

}

	$json = json_encode($output);

	echo $json;
