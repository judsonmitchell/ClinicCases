<?php
//Load case-related messages

session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/names.php');
require('../utilities/convert_times.php');
require('../utilities/thumbnails.php');
require('../utilities/format_text.php');
require('../html/gen_select.php');

function in_string($val,$string)
{
	$val_1 = ',' . $val .',';
	$val_2 = $val . ',';

	if (stristr($string, $val_1))
		{return true;}
	elseif (stristr($string, $val_2))
		{return true;}
	else
		{return false;}
}

function format_name_list($dbh,$list)
{
	$names = explode(',', $list);
	$n = null;
	foreach ($names as $name) {
		$n .= username_to_fullname($dbh,$name) . ", ";
	}
	$n_strip = substr($n, 0,-2);
	return $n_strip;
}

$username = $_SESSION['login'];

$type = $_POST['type'];

$limit = '20';

if (isset($_POST['case_id']))
{$case_id = $_POST['case_id'];}

if (isset($_POST['thread_id']))
{$thread_id = $_POST['thread_id'];}

if (isset($_POST['start']))
	{$start = $_POST['start'];}

if (isset($_POST['new_message']))
	{$new_message = true;}
	else
	{$new_message = false;}

if (isset($_POST['s']))
	{$s = $_POST['s'];}

//If updating infinite scroll, turn off header and new message html
if (isset($_POST['update']))
	{$refresh = true;}
	else
	{$refresh = false;}

$replies = false;

switch ($type) {
	case 'main':
		$q = $dbh->prepare("SELECT * FROM cm_messages WHERE `assoc_case` = ? AND `id` = `thread_id` ORDER BY `time_sent` DESC LIMIT $start, $limit");

		$q->bindParam(1, $case_id);

		$q->execute();

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

	break;

	case 'replies':
		$q = $dbh->prepare("SELECT * FROM cm_messages WHERE thread_id = :thread_id AND id != :thread_id ORDER BY `time_sent` ASC");

		$q->bindParam(':thread_id', $thread_id);

		$q->execute();

		$replies = true;

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

	break;

	case 'search':

		$q = $dbh->prepare("SELECT * from cm_messages, (SELECT DISTINCT thread_id FROM cm_messages WHERE (`to_text` LIKE :s OR `cc_text` LIKE :s OR `assoc_case_text` LIKE :s OR `time_sent_text` LIKE :s OR `subject` LIKE :s OR `body` LIKE :s)) AS all_msg WHERE cm_messages.id = all_msg.thread_id  AND cm_messages.assoc_case = :case_id ORDER BY cm_messages.time_sent DESC LIMIT $start, $limit");

		$search_term = '%' . $s .'%';

		$data = array('s' => $search_term, 'case_id' => $case_id);

		$q->execute($data);

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

		$refresh = true;

	break;
}

include('../../../html/templates/interior/cases_messages.php');

