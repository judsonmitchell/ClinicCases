<?php
//script to add, update and delete events in cases
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../users/user_data.php');
require('../utilities/format_text.php');
require('../utilities/names.php');


function generate_recipients($dbh,$thread_id) //Get list of recipients for a reply
{

	$q = $dbh->prepare("SELECT * FROM `cm_messages` WHERE `id` = $thread_id");

	$q->execute();

	$parties = $q->fetch(PDO::FETCH_ASSOC);

	$data = array('tos' => $parties['to'], 'ccs' => $parties['ccs'], 'from' => $parties['from']);

	return $data;

}

function get_subject($dbh,$msg_id) //Get subject for inclusion in notification email
{
	$q = $dbh->prepare("SELECT id, subject FROM cm_messages WHERE id = ?");

	$q->bindParam(1, $msg_id);

	$q->execute();

	$data = $q->fetch(PDO::FETCH_ASSOC);

	return $data['subject'];

}

function get_assoc_case($dbh,$id)//Determine if parent message was filed in a case; if so, get
//that the case number so it can be added to the reply
{
	$q = $dbh->prepare("SELECT `id`,`assoc_case` FROM cm_messages WHERE `id` = '$id'");

	$q->execute();

	$r = $q->fetch();

	if ($r['assoc_case'])
		{return $r['assoc_case'];}
	else
		{return false;}
}


//Get variables

$action = $_POST['action'];

$user = $_SESSION['login'];

if (isset($_POST['id']))
	{$id = $_POST['id'];}

if (isset($_POST['thread_id']))
	{$thread_id = $_POST['thread_id'];}

if (isset($_POST['reply_text']))
	{$reply_text = $_POST['reply_text'];}

if (isset($_POST['forward_tos']))
	{$forward_tos = $_POST['forward_tos'];}

if (isset($_POST['new_tos']))
	{$new_tos = $_POST['new_tos'];}

if (isset($_POST['new_ccs']))
	{$new_ccs = $_POST['new_ccs'];}
	else
	{$new_ccs = null;}

if (isset($_POST['new_file_msg']))
	{$assoc_case = $_POST['new_file_msg'];}

if (isset($_POST['new_subject']))
	{
		if (empty($_POST['new_subject']))
			{$new_subject = '(No Subject)';}
		else
			{$new_subject = $_POST['new_subject'];}
	}

if (isset($_POST['new_msg_text']))
	{$new_msg_text = $_POST['new_msg_text'];}

switch ($action) {

	case 'send':

		//prepare the post variables

		$tos = null;
		foreach ($new_tos as $to) {
			if (stristr($to, '_grp_'))
			{
			//user has selected a group as defined in config
				$group = substr($to, 5);
				$all_in_group = all_users_in_group($dbh,$group);
				$tos .= implode(',', $all_in_group) . ',';
			}
			elseif(stristr($to, '_spv_'))
			//user has selected a group that is defined by who the supervisor is
			{
				$supervisor= substr($to, 5);
				$all_in_group = all_users_by_supvsr($dbh,$supervisor);
				$tos .= implode(',', $all_in_group) . ',';
			}
			elseif (stristr($to, '_all_users_'))
			{
				$tos .= implode(',', all_active_users_a($dbh)) . ',';
			}
			elseif (stristr($to, '_all_on_case_'))
			//all users assigned to a particular case
			{
				$tos .= implode(',', all_users_on_case($dbh,$assoc_case)) . ',';
			}
			else
			{
				$tos .= $to . ',';
			}
		}

		if ($new_ccs)
		{
			$ccs = null;
			foreach ($new_ccs as $cc) {
				//user has selected a group as defined in config
				if (stristr($cc, '_grp_'))
				{
					$group = substr($cc, 5);
					$all_in_group = all_users_in_group($dbh,$group);
					$ccs .= implode(',', $all_in_group). ',';
				}
				elseif(stristr($cc, '_spv_'))
				//user has selected a group that is defined by who the supervisor is
				{
					$supervisor= substr($cc, 5);
					$all_in_group = all_users_by_supvsr($dbh,$supervisor);
					$ccs .= implode(',', $all_in_group) .',';
				}
				elseif (stristr($to, '_all_users_'))
				{
					$ccs .= implode(',', all_active_users_a($dbh)) . ',';
				}
				elseif (stristr($to, '_all_on_case_'))
				//all users assigned to a particular case
				{
					$ccs .= implode(',', all__users_on_case($dbh,$assoc_case)) . ',';
				}
				else
				{
					$ccs .= $cc . ',';
				}
			}
		}
		else
			{$ccs = null;}

		//next insert into db
		$q = $dbh->prepare("INSERT INTO `cm_messages` (`id`, `thread_id`, `to`, `from`, `ccs`, `subject`, `body`, `assoc_case`, `time_sent`, `read`, `archive`, `starred`) VALUES (NULL, '', :tos, :sender, :ccs, :subject, :body, :assoc_case, CURRENT_TIMESTAMP, :sender_has_read, '', '');");

		//strip trailing commas, if present
		if (substr($tos, -1) == ',')
			{$tos = substr($tos, 0,-1);}

		if (substr($ccs, -1) == ',')
			{$ccs = substr($ccs, 0,-1);}

		//Add the sender to the 'read' column.  Stops this message from being
		//counted in unread message count

		$sender_has_read = $user .',';

		$data = array('tos' => $tos,'sender' => $user, 'ccs' => $ccs, 'subject' => $new_subject,'body' => $new_msg_text,'assoc_case' => $assoc_case,'sender_has_read' => $sender_has_read);

		$q->execute($data);

		$error = $q->errorInfo();

		if (!$error[1])
		{
				//Add thread id to message; if thread_id the same as id,
				//we know message was not a reply.

				$last_id = $dbh->lastInsertId();

				$insert_thread = $dbh->prepare("UPDATE cm_messages SET `thread_id` = '$last_id' WHERE `id` = '$last_id'");

				$insert_thread->execute();

				//Send email notfications

				$recipients_to = explode(',',$tos);

				if (!empty($ccs))
				{
					$recipients_cc = explode(',', $ccs);
					$email_to = array_merge($recipients_to,$recipients_cc);
				}
				else
				{
					$email_to = $recipients_to;
				}

				$msg_subject = $new_subject;
				$preview = snippet(20,$new_msg_text, true);

				foreach ($email_to as $r) {
                    if ($r != $user){ //no email notification to sender
                        $email = user_email($dbh,$r);
                        $subject = "ClinicCases: New Message:'" . $msg_subject . "'";
                        $body = username_to_fullname($dbh,$user) . " has sent you a message '" . $msg_subject ."':\n\n'$preview'\n\n" . CC_EMAIL_FOOTER;
                        mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);
                    }
				}
		}


	break;

	case 'reply':

		$q = $dbh->prepare("INSERT INTO  `cm_messages` (`id` ,`thread_id` ,`to` ,`from` ,`ccs` ,`subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive` ,`starred`)
			VALUES (NULL ,  :thread_id,  :to,  :sender, :ccs,  '',:reply_text,  :assoc_case, CURRENT_TIMESTAMP ,  '',  '',  '');");

		$tos = generate_recipients($dbh,$thread_id);

		$to = $tos['from'] . ',' . $tos['tos'];

		$cc = $tos['ccs'];

		$case = get_assoc_case($dbh,$thread_id);

		$data = array('thread_id' => $thread_id, 'to' => $to,'ccs' => $cc, 'sender' => $user,'reply_text' => $reply_text,'assoc_case' => $case);

		$q->execute($data);

		$error = $q->errorInfo();

		//Remove any usernames from read and archive fields; this will show that there is a new reply
		//to the message in the inbox
		if (!$error[1])
			{
				$q = $dbh->prepare("UPDATE cm_messages SET `archive` = '',`read` = ? WHERE `id` = '$thread_id'");

				//Add the sender to the 'read' column.  Stops this message from being
				//counted in unread message count
				$sender_has_read = $user .',';

				$q->bindParam(1, $sender_has_read);

				$q->execute();

				//next send an email notifying user of the new reply
				$recipients_to = explode(',', $to);
				if (!empty($cc))
				{
					$recipients_cc = explode(',', $cc);
					$email_to = array_merge($recipients_to,$recipients_cc);
				}
				else
				{
					$email_to = $recipients_to;
				}

				$msg_subject = get_subject($dbh,$thread_id);
				$preview = snippet(20,$reply_text, true);

				foreach ($email_to as $r) {
                    if ($r != $user){
                        $email = user_email($dbh,$r);
                        $subject = "ClinicCases: Reply to '" . $msg_subject . "'";
                        $body = username_to_fullname($dbh,$user) . " has replied to '" . $msg_subject ."':\n\n'$preview'\n\n" . CC_EMAIL_FOOTER;
                        mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);
                    }
				}
			}


	break;

	case 'forward':

		//first add the forward users to the first message in the thread
		$q = $dbh->prepare("UPDATE cm_messages SET `to` = CONCAT(`to`,:forwards) WHERE `id` = :id");

		$forwards = "," . implode(',', $forward_tos);

		$data = array('forwards' => $forwards,'id' => $thread_id);

		$q->execute($data);

		$error = $q->errorInfo();

		//then add a reply about the forward

		if (!$error[1])
		{
			//Take message out of archive and read
			$q = $dbh->prepare("UPDATE cm_messages SET `archive` = '',`read` = '' WHERE `id` = '$thread_id'");

			$q->execute();

			//Add the reply
			$q = $dbh->prepare("INSERT INTO  `cm_messages` (`id` ,`thread_id` ,`to` ,`from` ,`ccs` ,`subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive` ,`starred`)
				VALUES (NULL ,  :thread_id,  :to,  :sender, :ccs,  '',:forward_text,  '', CURRENT_TIMESTAMP, '',  '',  '');");

			$forward_names = null;

			foreach ($forward_tos as $fts) {
				$name = username_to_fullname($dbh,$fts);
				$forward_names .= $name . ", ";
			}

			$forward_names_string = substr($forward_names, 0,-2);

			$forward_text = "<<<Forwarded this message to $forward_names_string" . "\n\n" . $reply_text;

			$tos = generate_recipients($dbh,$thread_id);

			$to = $tos['from'] . ',' . $tos['tos'];

			$cc = $tos['ccs'];

			$data = array('thread_id' => $thread_id, 'to' => $to,'ccs' => $cc, 'sender' => $user,'forward_text' => $forward_text);

			$q->execute($data);

			$error = $q->errorInfo();

			//TODO notify forward recipients by email
			if (!$error[1])
			{
				$msg_subject = get_subject($dbh,$thread_id);
				$preview = snippet(20,$reply_text, true);

				foreach ($forward_tos as $f) {
                    if ($f != $user){
                        $email = user_email($dbh,$f);
                        $subject = "ClinicCases: New Message: '" . $msg_subject . "'";
                        $body = username_to_fullname($dbh,$user) . " forwarded '" . $msg_subject ."' to you:\n\n'$preview'\n\n" . CC_EMAIL_FOOTER;
                        mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);
					}
				}
			}

		}


	break;

	case 'star_on':  //add start to message

		$q = $dbh->prepare("UPDATE cm_messages SET `starred` = REPLACE(`starred`,:user,''),
			starred = CONCAT(starred,:user) WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'star_off':  //remove star from message

		$q = $dbh->prepare("UPDATE cm_messages SET starred = REPLACE(starred,:user,'') WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();


		break;

	case 'mark_read':

		//First replace any previous mark reads by this user, then put it a new.
		//This way, there are not multiple mark reads in the list
		$q = $dbh->prepare("UPDATE cm_messages SET `read` = REPLACE(`read`,:user,''),
			`read` = CONCAT(`read`, :user)  WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'mark_unread':

		$q = $dbh->prepare("UPDATE cm_messages SET `read` = REPLACE(`read`,:user,'') WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'archive':

		$q = $dbh->prepare("UPDATE cm_messages SET `archive` = REPLACE(`archive`,:user,''),
			`archive` = CONCAT(`archive`,:user) WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'unarchive':

		$q = $dbh->prepare("UPDATE cm_messages SET archive = REPLACE(archive,:user,'') WHERE id = :id");

		$user_string = $user . ",";

		$data = array('user' => $user_string,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'archive_all':
		//get all unarchived messages
		$q = $dbh->prepare("SELECT * FROM (SELECT * FROM cm_messages WHERE `archive` NOT LIKE '%,$user,%' AND `archive` NOT LIKE '$user,%' AND `archive` NOT LIKE '%,$user' AND `id` = `thread_id`) AS no_archive WHERE (no_archive.to LIKE '%,$user,%' OR no_archive.to LIKE '$user,%' OR no_archive.to LIKE '%,$user' OR no_archive.to LIKE '$user') OR (no_archive.ccs LIKE  '%,$user,%' OR no_archive.ccs LIKE '$user,%'  OR no_archive.ccs LIKE '%,$user' OR no_archive.ccs LIKE '$user') OR (no_archive.from LIKE '$user')");

		$q->execute();

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

		$error = $q->errorInfo();

		if (!$error[1])
		{

			foreach ($msgs as $msg) {

				$update = $dbh->prepare("UPDATE cm_messages SET `archive` = REPLACE(`archive`,:user,''),
				`archive` = CONCAT(`archive`,:user) WHERE id = :id");

				$user_string = $user . ",";

				$data = array('user' => $user_string, 'id' => $msg['id']);

				$update->execute($data);

			}
		}

		break;

};

if($error[1])

		{
			$return = array('message' => 'Sorry, there was an error. Please try again.','error' => true);
			echo json_encode($return);
		}

		else
		{

			switch($action){

			case "send":
			$return = array('message'=>'Message sent.');
			echo json_encode($return);
			break;

			case "reply":
			$return = array('message'=>'Reply sent.');
			echo json_encode($return);
			break;

			case "forward":
			$return = array('message'=>'Message forwarded.');
			echo json_encode($return);
			break;

			case "archive":
			$return = array('message'=>'Message archived.');
			echo json_encode($return);
			break;

			case "unarchive":
			$return = array('message'=>'Message returned to Inbox.');
			echo json_encode($return);
			break;

			case "star_on":
			$return = array('message'=>'OK');
			echo json_encode($return);
			break;

			case "star_off":
			$return = array('message'=>'OK');
			echo json_encode($return);
			break;

			case "mark_read":
			$return = array('message'=>'OK');
			echo json_encode($return);
			break;

			case "mark_unread":
			$return = array('message'=>'Message marked unread.');
			echo json_encode($return);
			break;

			case "archive_all":
			$return = array('message'=>'All inbox messages sent to archive.');
			echo json_encode($return);
			break;

			}

		}
