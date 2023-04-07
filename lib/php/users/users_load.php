<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/convert_times.php';
include '../utilities/names.php';
include '../utilities/thumbnails.php';

if ($_SESSION['permissions']['view_users'] == '1')
{

//Columns we want from the users table
$columns = array("id", "picture_url","first_name", "last_name", "email", "mobile_phone", "office_phone", "home_phone", "grp", "username", "supervisors","status", "new", "date_created");

//Get the group name data.  This avoids having to a db call
//for each row
$group_name_data = group_display_name_array($dbh);

//Get array of supervisor names.  Again, avoids excessive db calls
$supervisor_name_data  = supervisor_names_array($dbh);

$cols = implode(', ', $columns);

$q = $dbh->prepare("SELECT $cols FROM `cm_users`");

$q->execute();

while ($result = $q->fetch(PDO::FETCH_ASSOC))
{
	$rows= array();

	foreach ($columns as $col)
		{

			if ($col === 'picture_url') //generate thumbnail url and hmtl
			{
				$th = thumbify($result[$col]);
				$rows[$col] = "<img class='thumbnail-mask' src = '" . $th . "?" . rand() . "'></img>";
			}
			elseif ($col === 'date_created') //convert dates
			{
				$rows[$col] = extract_date_time_sortable($result[$col]);
			}
			elseif ($col === 'grp') //show display name of group, instead of db name
			{
				$rows[$col] = array_search($result[$col], $group_name_data);
			}
			elseif ($col === 'supervisors') //show display name of group, instead of db name
			{
				if (!empty($result[$col]))
				{
					$sups = explode(',', $result[$col]);
					$sup_names = array();
					foreach ($sups as $sup) {
						$sup_names[] = array_search($sup, $supervisor_name_data);
					}

					$rows[$col] = rtrim(implode(', ', $sup_names),', ');
				}
				else
				{
					$rows[$col] = '';
				}
			}
			else
			{
				$rows[$col] = $result[$col];
			}
		}

	$output['aaData'][] = $rows;

}

	$json = json_encode($output);

	echo $json;

}
else
{
	die("Sorry, you don't have permission to view users.");
}
