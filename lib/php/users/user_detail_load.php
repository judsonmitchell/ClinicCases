<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/convert_times.php';
include '../utilities/convert_case_time.php';
include '../utilities/names.php';
include '../utilities/thumbnails.php';
include '../auth/last_login.php';
include '../html/gen_select.php';
include 'user_data.php';


//Returns the name of open cases that user is assigned to
function get_active_cases($dbh,$user)
{

	$q = $dbh->prepare("SELECT cm.date_close,cm.id,cm_case_assignees.case_id,
		cm_case_assignees.username,cm_case_assignees.status
		FROM cm, cm_case_assignees WHERE cm.date_close = '' AND
		cm.id = cm_case_assignees.case_id
		AND cm_case_assignees.status = 'active'
		AND cm_case_assignees.username = ?");

	$q->bindParam(1, $user);

	$q->execute();

	$cases = $q->fetchAll(PDO::FETCH_ASSOC);

	$case_array = array();

	foreach ($cases as $case) {

		$case_array[$case['case_id']] = case_id_to_casename($dbh,$case['case_id']);
	}

	asort($case_array);

	return $case_array;

}

function get_total_hours($dbh,$user)
{
	$q = $dbh->prepare("SELECT SUM(time) FROM `cm_case_notes` WHERE username = ?");

	$q->bindParam(1, $user);

	$q->execute();

	$sum = $q->fetch();

	if (!empty($sum['SUM(time)']))
	{
		$time = convert_case_time($sum['SUM(time)']);
	}
	else
		{
			$time = array('0',' minutes');
		}

	return $time;

}

function get_last_case_activity($dbh,$user)
{
	$q = $dbh->prepare("SELECT * FROM `cm_case_notes`
		WHERE username = ? ORDER BY `date` desc LIMIT 0,1");

	$q->bindParam(1, $user);

	$q->execute();

	$c = $q->fetch();

	if ($c)
	{
		$data = extract_date_time($c['date']) . ": " . implode(' ', convert_case_time($c['time'])) . ' on the <a href="index.php?i=Cases.php#cases/' . $c['case_id']  .  '" target="_new">' . case_id_to_casename($dbh,$c['case_id']) . "</a> case.";
	}
	else
	{
		$data = "None";
	}

	return $data;

}

$group_name_data = group_display_name_array($dbh);

$supervisor_name_data  = supervisor_names_array($dbh);

$id = $_POST['id'];

if (isset($_POST['view']))
{$view = $_POST['view'];}

$q = $dbh->prepare("SELECT * FROM cm_users WHERE id = ?");

$q->bindParam(1, $id);

$q->execute();

$user = $q->fetch(PDO::FETCH_ASSOC);

include('../../../html/templates/interior/user_detail.php');

