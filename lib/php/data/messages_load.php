<?php
//Loads messages

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

function apply_labels($dbh,$id,$user)
{
	$labels = null;

	$label_data = $dbh->prepare("SELECT * FROM cm_messages WHERE id = '$id'");

	$label_data->execute();

	$ld = $label_data->fetch(PDO::FETCH_ASSOC);

	if (in_string($user,$ld['archive']))
		{$labels .= '<span class="label_archive">Archive</span>';}
	else
		{$labels .= '<span class="label_inbox">Inbox</span>';}

	if ($ld['from'] == $user)
		{$labels .= '<span class="label_sent">Sent</span>';}

	return $labels;
}

$username = $_SESSION['login'];
$limit = '20';

if (isset($_POST['type']))
	{$type = $_POST['type'];}

if (isset($_POST['start']))
	{$start = $_POST['start'];}

if (isset($_POST['id']))
	{$id = $_POST['id'];}

if (isset($_POST['thread_id']))
	{$thread_id = $_POST['thread_id'];}

if (isset($_POST['new_message']))
	{$new_message = true;}
	else
	{$new_message = false;}

if (isset($_POST['s']))
	{$s = $_POST['s'];}

$replies = false;

switch ($type) {

	case 'inbox':

		$q = $dbh->prepare("SELECT * from cm_messages,(SELECT DISTINCT thread_id FROM cm_messages
			WHERE `to` LIKE '$username,%' OR `to` LIKE '%,$username,%' OR `to` LIKE '%,$username' OR `to`
			LIKE '$username' OR `ccs` LIKE '$username,%' OR `ccs` LIKE '%,$username,%' OR `ccs` LIKE '%,$username' or `ccs` LIKE '$username') AS all_msg WHERE cm_messages.id = all_msg.thread_id AND `archive` NOT LIKE '%,$username,%' AND `archive` NOT LIKE '$username,%' AND `archive` NOT LIKE '%,$username' ORDER BY cm_messages.time_sent DESC LIMIT $start, $limit");

		$q->execute();

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

	break;

	case 'sent':

		$q = $dbh->prepare("SELECT * from cm_messages WHERE `from` LIKE '$username' ORDER BY `time_sent` DESC  LIMIT $start, $limit");

		$q->execute();

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

	break;

	case 'archive':

		$q = $dbh->prepare("SELECT * from (SELECT * FROM cm_messages WHERE `id` = `thread_id`) AS no_thread WHERE no_thread.archive LIKE '%,$username,%' OR no_thread.archive LIKE '$username,%' OR no_thread.archive LIKE '%,$username' ORDER BY no_thread.time_sent DESC  LIMIT $start, $limit");

		$q->execute();

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

	break;

	case 'starred' :

		$q = $dbh->prepare("SELECT * from (SELECT * FROM cm_messages WHERE `id` = `thread_id`) AS no_thread WHERE no_thread.starred LIKE '%,$username,%' OR no_thread.starred LIKE '$username,%' OR no_thread.starred LIKE '%,$username' ORDER BY no_thread.time_sent DESC  LIMIT $start, $limit");

		$q->execute();

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

	break;

	case 'replies' :

		$q = $dbh->prepare("SELECT * FROM cm_messages WHERE thread_id = :thread_id AND id != :thread_id");

		$data = array('thread_id' => $thread_id);

		$q->execute($data);

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

		$replies = true;


	break;

	case 'search' :

		$q = $dbh->prepare("SELECT * from cm_messages, (SELECT DISTINCT thread_id FROM cm_messages WHERE (`to_text` LIKE :s OR `cc_text` LIKE :s OR `assoc_case_text` LIKE :s OR `time_sent_text` LIKE :s OR `subject` LIKE :s OR `body` LIKE :s) AND (`to` LIKE :user_last OR `to` LIKE :user_middle OR `to` LIKE :user_first  OR `ccs` LIKE :user_last OR `ccs` LIKE :user_middle OR `ccs` LIKE :user_first  OR `from` = :user)) AS all_msg WHERE cm_messages.id = all_msg.thread_id ORDER BY cm_messages.time_sent DESC LIMIT $start, $limit");

		$search_term = '%' . $s .'%';

		$user_last = '%,' . $username;

		$user_middle = '%,' . $username . ',%';

		$user_first = $username . ',%';

		$data = array('s' => $search_term,'user' => $username,'user_last' => $user_last,'user_middle' => $user_middle,'user_first' => $user_first);

		$q->execute($data);

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);//TODO need to filter out the replies here; subquery in sql or loop through result in php and return only where id = thread_id?

	break;
}

if (empty($msgs) AND $replies === false AND $new_message === false)
	//i.e, there are no messages to display, we are not loading replies, and
	//this is not a request for the new message html
	{

		if (isset($s))
			{echo "<p>No messages found matching <i>$s</i></p>";}
		else
			{echo "<p>There are no messages in your $type folder</p>";die;}
	}

include('../../../html/templates/interior/messages_display.php');
