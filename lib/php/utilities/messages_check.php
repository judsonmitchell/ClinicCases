<?php
//Check for new messages
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$username = $_SESSION['login'];

$user_last = '%,' . $username;

$user_middle = '%,' . $username . ',%';

$user_first = $username . ',%';

$user_last_trailing = '%,' . $username . ',';

//$user_first_trailing = $username . ',';

//Get last mail check

//Check to see if there has been a mail check this session
$q = $dbh->prepare("SELECT `last_msg_check` from cm_logs WHERE session_id = ?");

$q->bindParam(1, $_SESSION['cc_session_id']);

$q->execute();

$l = $q->fetch();

$check_val = $l['last_msg_check'];

//If there has been no mail check this session, then get time out last logout

if ($check_val === '0000-00-00 00:00:00') {

	$q = $dbh->prepare("SELECT MAX(timestamp) FROM cm_logs WHERE username = ? and type = 'out'");

	$user = $_SESSION['login'];

	$q->bindParam(1, $user);

	$q->execute();

	$l = $q->fetch();

	$check_val = $l['MAX(timestamp)'];

}

if ($check_val) {
	$q = $dbh->prepare("SELECT COUNT(id) from cm_messages WHERE `time_sent` > :last_msg_check AND (`to` LIKE :user_last OR `to` LIKE :user_middle OR `to` LIKE :user_first OR `to`LIKE :user OR `ccs` LIKE :user_last OR `ccs` LIKE :user_middle OR `ccs` LIKE :user_first or `ccs` LIKE :user) AND `from` != :user");

	$data = array('user' => $username,'user_last' => $user_last,'user_middle' => $user_middle,'user_first' => $user_first,'last_msg_check' => $check_val);

	$q->execute($data);

	$r = $q->fetch();

	if (empty($r['COUNT(id)']))
		{$number = '0';}
	else
		{$number = $r['COUNT(id)'];}

	$new = array('new_msg' => $number);

} else {
	//This is user's first time ever logging in
	$new = array('new_msg' => '0');
}


//Now get the unread message count

$q = $dbh->prepare("SELECT COUNT(id) from cm_messages WHERE (`to` LIKE :user_last OR `to` LIKE :user_middle OR `to` LIKE :user_first OR `to` LIKE :user OR `ccs` LIKE :user_last OR `ccs` LIKE :user_middle OR `ccs` LIKE :user_first OR `ccs` LIKE :user OR `from` LIKE :user)
AND (`read` NOT LIKE :user_first AND `read` NOT LIKE :user_middle AND `read` NOT LIKE :user_last_trailing)
AND (`archive` NOT LIKE :user_first AND `archive` NOT LIKE :user_middle AND `archive` NOT LIKE :user_last_trailing) AND `id` = `thread_id`");

$data = array('user_first' => $user_first,'user_middle' => $user_middle, 'user_last' => $user_last, 'user_last_trailing' => $user_last_trailing, 'user' => $username);

$q->execute($data);

$unr = $q->fetch();

$unread_count = $unr['COUNT(id)'];

$unread = array('unread' => $unread_count);

$return = array_merge($new,$unread);

echo json_encode($return);

//Now update mail check to current time
$q = $dbh->prepare('UPDATE cm_logs SET `last_msg_check` = CURRENT_TIMESTAMP WHERE session_id = ?');

$q->bindParam(1, $_SESSION['cc_session_id']);

$q->execute();
