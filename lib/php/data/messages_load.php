<?php
//Loads messages
//[A brief explanation of how messages are set up:  If the id matches the thread_id, this is a parent message.  If it doesn't, then this is a reply to the parent message.  Whenever someone replies to a parent message, the parent message archive and read fields are cleared.  The parent message will then appear in the recipients inbox as a new message.  When recipient clicks on parent message header, all replies, including the one just sent, are loaded.  A reply should never have anything in its read or archive fields, unless there are some left over messages from cc6.  Apologies for this complexity.]
@session_start();
require_once dirname(__FILE__) . '/../../../db.php';
require_once(CC_PATH . '/lib/php/auth/session_check.php');
require_once(CC_PATH . '/lib/php/utilities/names.php');
require_once(CC_PATH . '/lib/php/utilities/convert_times.php');
require_once(CC_PATH . '/lib/php/utilities/thumbnails.php');
require_once(CC_PATH . '/lib/php/utilities/format_text.php');
require_once(CC_PATH . '/lib/php/html/gen_select.php');

function in_string($val,$string)
{
	$val_1 = ',' . $val . ',';
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

if (isset($_REQUEST['type'])) {
    $type = $_REQUEST['type'];
} else {
    $type = 'inbox';
}

if (isset($_REQUEST['start'])) {
    $start = $_REQUEST['start'];
} else {
    $start = '0';
}

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
}

if (isset($_REQUEST['thread_id'])) {
    $thread_id = $_REQUEST['thread_id'];
}

if (isset($_REQUEST['new_message'])) {
    $new_message = true;
} else {
    $new_message = false;
}

if (isset($_REQUEST['s'])) {
    $s = $_REQUEST['s'];
}

$replies = false;

switch ($type) {

	case 'inbox':

		$q = $dbh->prepare("SELECT * from cm_messages,(SELECT DISTINCT thread_id FROM cm_messages
			WHERE `to` LIKE '$username,%' OR `to` LIKE '%,$username,%' OR `to` LIKE '%,$username' OR `to`
			LIKE '$username' OR `ccs` LIKE '$username,%' OR `ccs` LIKE '%,$username,%' OR `ccs` LIKE '%,$username' or `ccs` LIKE '$username') AS all_msg
				WHERE cm_messages.id = all_msg.thread_id
				AND cm_messages.id = cm_messages.thread_id
				AND cm_messages.archive NOT LIKE '%,$username,%' AND cm_messages.archive NOT LIKE '$username,%' AND cm_messages.archive NOT LIKE '%,$username' AND cm_messages.archive NOT LIKE '$username,' ORDER BY cm_messages.time_sent DESC LIMIT $start, $limit");

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

		$q = $dbh->prepare("SELECT * FROM cm_messages WHERE thread_id = :thread_id AND id != :thread_id ORDER BY `time_sent` ASC");

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

if (empty($msgs) AND $replies === false AND $new_message === false) {
	//i.e, there are no messages to display, we are not loading replies, and
	//this is not a request for the new message html
		if (isset($s)) {
            echo "<div class='alert alert-danger' role='alert'>No messages found matching <i>$s</i></div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>There are no messages in your $type folder</div>";
        }
	}

if (!$_SESSION['mobile']){
    include('../../../html/templates/interior/messages_display.php');
}
