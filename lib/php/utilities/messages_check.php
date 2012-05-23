<?php
//Check for new messages
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$username = $_SESSION['login'];

//Get last mail check

//Check to see if there has been a mail check this session
$q = $dbh->prepare("SELECT `last_msg_check` from cm_logs WHERE session_id = ?");

$q->bindParam(1, $_SESSION['cc_session_id']);

$q->execute();

$last_mail_check = $q->fetch();

//If there has been no mail check this session, then get time out last logout

if (!$last_mail_check['last_msg_check']) {
	$q = $dbh->prepare("SELECT MAX(timestamp) FROM cm_logs WHERE username = :user and type = 'out'");

	$user = $_SESSION['login'];

	$q->execute($user);

	$last_mail_check = $q->fetch();

}

//Now update mail check to current time
$q = $dbh->prepare('UPDATE cm_logs SET `last_msg_check` = CURRENT_TIMESTAMP WHERE session_id = ?');

$q->bindParam(1, $_SESSION['cc_session_id']);

$q->execute();

if ($last_mail_check['last_msg_check']) {
	$q = $dbh->prepare("SELECT COUNT(id) from cm_messages WHERE `time_sent` > :last_msg_check AND (`to` LIKE :user_last OR `to` LIKE :user_middle OR `to` LIKE :user_last OR `to`LIKE :user OR `ccs` LIKE :user_last OR `ccs` LIKE :user_middle OR `ccs` LIKE :user_last or `ccs` LIKE :user)");

	$user_last = '%,' . $username;

	$user_middle = '%,' . $username . ',%';

	$user_first = $username . ',%';

	$data = array('user' => $username,'user_last' => $user_last,'user_middle' => $user_middle,'user_first' => $user_first,'last_msg_check' => $last_mail_check['last_msg_check']);

	$q->execute($data);

	$r = $q->fetch();

	$number = $r['COUNT(id)'];

	echo $number;


} else {
	//This is user's first time ever logging in
	return false;
}

