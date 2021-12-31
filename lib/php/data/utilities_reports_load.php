<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../users/user_data.php');
require('../utilities/names.php');
require('../utilities/convert_case_time.php');
require('../utilities/convert_times.php');


$user = $_SESSION['login'];

if (isset($_GET['type']))
{
	$type = $_GET['type'];
}

//$type = 'supvsr_grp';

if (isset($_GET['val']))
{
	$val = $_GET['val'];
}

//$val = "_spv_jmitchell";

if (isset($_GET['date_start']))
{
	$date_start = $_GET['date_start'] . " 00:00:00";
}

//$date_start = "2011-08-01";

if (isset($_GET['date_end']))
{
	$date_end = $_GET['date_end'] . " 23:59:59";
}

//$date_end = "2011-10-01";

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

		$cols = array("username","case_id", "date", "description", "time","seconds");

		$col_data = array(
                array(
                    'sTitle' => 'Name'
                ),
                array(
                    'sTitle' => 'Case'
                ),
                array(
                    'sTitle' => 'Date'
                ),
                array(
                    'sTitle' => 'Description'
                ),
                array(
                    'sTitle' => 'Time (hours)'
                ),
                array(
                	'sTitle' => 'Seconds',
                	'bVisible' => false
                )
            );

		if ($columns_only)
		{
			$output['aoColumns'] = $col_data;
		}
		else
		{

			$q = $dbh->prepare("SELECT * FROM cm_case_notes WHERE `username` = :val AND `date` >= :date_start AND `date` <= :date_end");

			$data = array('val' => $val,'date_start' => $date_start,'date_end' => $date_end);

			$q->execute($data);

			$error = $q->errorInfo();

			while ($result = $q->fetch(PDO::FETCH_ASSOC))
			{

				$rows= array();

				$result['username'] = username_to_fullname($dbh,$result['username']);

				$result['case_id'] = case_id_to_casename ($dbh,$result['case_id']);

				$result['date'] = extract_date($result['date']);

				$result['seconds'] = $result['time'];

				$result['time'] = convert_to_hours($result['time']);


				foreach ($cols as $col) {

				  	$rows[] = $result[$col];

				 }

				 $output['aaData'][] = $rows;
			}

			if ($q->rowCount() < 1)
			{
				$output['aaData'] = array();
			}

			$output['aoColumns'] = $col_data;
		}

		break;

	case 'grp':

			$cols = array("username","SUM(time)","seconds");

			$col_data = array(
	                array(
	                    'sTitle' => 'Name'
	                ),
	                array(
	                    'sTitle' => 'Time (hours)'
	                ),
	                array(
                		'sTitle' => 'Seconds',
                		'bVisible' => false
                	)
	            );

			if ($columns_only)
			{
				$output['aoColumns'] = $col_data;
			}
			else
			{
				$group = substr($val, 5);

				$members = all_users_in_group_no_status($dbh,$group);

				$m = implode("','",$members);

				$sql = "SELECT username,SUM(time) FROM cm_case_notes WHERE `username` IN ('$m') AND `date` >= '$date_start' AND `date` <= '$date_end' GROUP BY username";

				$q = $dbh->prepare($sql);

				$q->execute();

				$error = $q->errorInfo();

				while ($result = $q->fetch(PDO::FETCH_ASSOC))
				{

					$rows= array();

					$result['username'] = username_to_fullname($dbh,$result['username']);

					$result['seconds'] = $result['SUM(time)'];

					$result['SUM(time)'] = convert_to_hours($result['SUM(time)']);


					 foreach ($cols as $col) {

					  	$rows[] = $result[$col];

					 }

					 $output['aaData'][] = $rows;
				}

				if ($q->rowCount() < 1)
				{
					$output['aaData'] = array();
				}

				$output['aoColumns'] = $col_data;
			}

		break;

	case 'supvsr_grp':

		$cols = array("username","SUM(time)","seconds");

		$col_data = array(
                array(
                    'sTitle' => 'Name'
                ),
                array(
                    'sTitle' => 'Time (hours)'
                ),
                array(
                	'sTitle' => 'Seconds',
                	'bVisible' => false
                )
            );

		if ($columns_only)
		{
			$output['aoColumns'] = $col_data;
		}
		else
		{
			$supervisor = substr($val, 5);

			$members = all_users_by_supvsr_no_status($dbh,$supervisor);

			$m = implode("','",$members);

			$sql = "SELECT username,SUM(time) FROM cm_case_notes WHERE `username` IN ('$m') AND `date` >= '$date_start' AND `date` <= '$date_end' GROUP BY username";

			$q = $dbh->prepare($sql);

			$q->execute();

			$error = $q->errorInfo();

			while ($result = $q->fetch(PDO::FETCH_ASSOC))
			{

				$rows= array();

				$result['username'] = username_to_fullname($dbh,$result['username']);

				$result['seconds'] = $result['SUM(time)'];

				$result['SUM(time)'] = convert_to_hours($result['SUM(time)']);


				 foreach ($cols as $col) {

				  	$rows[] = $result[$col];

				 }

				 $output['aaData'][] = $rows;
			}

			if ($q->rowCount() < 1)
			{
				$output['aaData'] = array();
			}

			$output['aoColumns'] = $col_data;
		}

		break;

	case 'case';

		$cols = array("username","case_id", "date", "description", "time","seconds");

		$col_data = array(
                array(
                    'sTitle' => 'Name'
                ),
                array(
                    'sTitle' => 'Case'
                ),
                array(
                    'sTitle' => 'Date'
                ),
                array(
                    'sTitle' => 'Description'
                ),
                array(
                    'sTitle' => 'Time (hours)'
                ),
                array(
                	'sTitle' => 'Seconds',
                	'bVisible' => false
                )
            );

		if ($columns_only)
		{
			$output['aoColumns'] = $col_data;
		}
		else
		{
			$case_number = substr($val, 5);

			$q = $dbh->prepare("SELECT * FROM cm_case_notes WHERE `case_id` = :val AND `date` >= :date_start AND `date` <= :date_end ORDER BY `date` ASC");

			$data = array('val' => $case_number,'date_start' => $date_start,'date_end' => $date_end);

			$q->execute($data);

			$error = $q->errorInfo();

			while ($result = $q->fetch(PDO::FETCH_ASSOC))
			{

				$rows= array();

				$result['username'] = username_to_fullname($dbh,$result['username']);

				$result['case_id'] = case_id_to_casename ($dbh,$result['case_id']);

				$result['date'] = extract_date($result['date']);

				$result['seconds'] = $result['time'];

				$result['time'] = convert_to_hours($result['time']);

				foreach ($cols as $col) {

				  	$rows[] = $result[$col];

				 }

				 $output['aaData'][] = $rows;
			}

			if ($q->rowCount() < 1)
			{
				$output['aaData'] = array();
			}

			$output['aoColumns'] = $col_data;
		}

		break;
}

echo json_encode($output);



