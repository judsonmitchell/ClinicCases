<?php
// session_start();
// require('../auth/session_check.php');
require('../../../db.php');
require('../users/user_data.php');
require('../utilities/names.php');
require('../utilities/convert_case_time.php');



//$user = $_SESSION['login'];

// if (isset($_GET['type']))
// {
// 	$type = $_GET['type'];
// }

$type = 'supvsr_grp';

// if (isset($_GET['val']))
// {
// 	$val = $_GET['val'];
// }

$val = "_grp_jmitchell";

// if (isset($_GET['date_start']))
// {
// 	$date_start = $_GET['date_start'];
// }

$date_start = "2011-08-01";

// if (isset($_GET['date_end']))
// {
// 	$date_end = $_GET['date_end'];
// }

$date_end = "2011-10-01";

if (isset($_GET['columns_only']))
{
	$columns_only = true;
}
else
{
	$columns_only = false;
}

//Types of queries
//1. Case activity by group
//2. Case activity by individual (displays case notes)

switch ($type) {
	case 'user':
		$sql = "SELECT * FROM cm_case_notes WHERE `username` = :user AND `date` >= $date_start AND `date` <= $date_end";

		$q = $dbh->prepare($sql);

		$data = array('user' => $user,'date_start' => $date_start, 'date_end' => $date_end);

		$cols = array( 'engine', 'browser', 'platform', 'version', 'grade' );


		break;

	case 'grp':


		break;

	case 'supvsr_grp':

		$cols = array("username","SUM(time)");

		$col_data = array(
                array(
                    'sTitle' => 'Name'
                ),
                array(
                    'sTitle' => 'Time'
                )
            );

		if ($columns_only)
		{
			$output['aoColumns'] = $col_data;
		}
		else
		{

			$members = all_users_by_supvsr($dbh,'jmitchell');

			$m = implode("','",$members);

			$sql = "SELECT username,SUM(time) FROM cm_case_notes WHERE `username` IN ('$m') AND `date` >= '$date_start' AND `date` <= '$date_end' GROUP BY username";

			$q = $dbh->prepare($sql);

			$q->execute();

			$error = $q->errorInfo();

			while ($result = $q->fetch(PDO::FETCH_ASSOC))
			{

				$rows= array();

				 foreach ($cols as $col) {
				  	$rows[] = $result[$col];

				 	}

				 $output['aaData'][] = $rows;

				//$rows[] = $result;

				// $t = convert_case_time($result['SUM(time)']);

				// echo $t[0]  . " " . $t[1] . " " . $result['username'] . "\n";
			}

			$output['aoColumns'] = $col_data;
		}

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

echo json_encode($output);



