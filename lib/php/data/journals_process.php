<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/names.php';
include '../users/user_data.php';

$user = $_SESSION['login'];

$type = $_POST['type'];

if (isset($_POST['id']))
{
	$id = $_POST['id'];
}
else
{
	$id = null;
}

if (isset($_POST['text']))
{
	$text = $_POST['text'];
}
else
{
	$text = null;
}

if (isset($_POST['comment_text']))
{
	$comment_text = nl2br($_POST['comment_text']);
}
else
{
	$comment_text = null;
}

if (isset($_POST['comment_id']))
{
	$comment_id = $_POST['comment_id'];
}
else
{
	$comment_id = null;
}

if (isset($_POST['readers']))
{
	$readers = $_POST['readers'];
}


switch ($type) {

	case 'mark_read':
		$q = $dbh->prepare("UPDATE cm_journals SET `read` = REPLACE(`read`,:user,''),
			`read` = CONCAT(`read`, :user) WHERE `id` = :id");

		foreach ($id as $i) {

			$user_string = $user . ',';

			$data = array('user' => $user_string, 'id' => $i);

			$q->execute($data);
		}

		$error = $q->errorInfo();

		break;

	case 'mark_unread':
		$q = $dbh->prepare("UPDATE cm_journals SET `read` = REPLACE(`read`,:user,'')
			WHERE `id` = :id");

		foreach ($id as $i) {

			$user_string = $user . ',';

			$data = array('user' => $user_string, 'id' => $i);

			$q->execute($data);
		}

		$error = $q->errorInfo();

		break;

	case 'archive':

		$q = $dbh->prepare("UPDATE cm_journals SET `archived` = REPLACE(`archived`,:user,''),
			`archived` = CONCAT(`archived`, :user) WHERE `id` = :id");

		$user_string = $user . ',';

		foreach ($id as $i) {

			$data = array('user' => $user_string, 'id' => $i);

			$q->execute($data);
		}

		$error = $q->errorInfo();

		break;

	case 'new':

		//Check to see if user has permission to do this.
		if (!$_SESSION['permissions']['writes_journals'] == "1")
			{
				$response = array('error' => true,'message' => 'Sorry, you do not have permission to add journals.');

				echo json_encode($response);die;
			}

		$q = $dbh->prepare("INSERT INTO `cm_journals` (`id`, `username`, `reader`, `text`, `date_added`, `archived`, `read`, `commented`, `comments`) VALUES (NULL, ?, '', '', NOW(), '', '', '', '');");

		$q->bindParam(1,$user);

		$q->execute();

		$error = $q->errorInfo();

		if (!$error[1])
		{
			$new_id = $dbh->lastInsertId();

			$response = array('error' => false,'newId' => $new_id);

			echo json_encode($response);

		}

		break;

	case 'edit':

		$q = $dbh->prepare("UPDATE cm_journals SET `text` = :text, `reader` = :reader WHERE `id` = :id");

		if (is_array($readers))
		{
			$reader = implode(',', $readers) . ",";
		}
		else
		{
			$reader = '';
		}

		$data = array('text' => $text,'id' => $id[0],'reader' => $reader);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'update_readers':

		$q = $dbh->prepare("UPDATE cm_journals SET `reader` = :reader WHERE `id` = :id");

		if (is_array($readers))
		{
			$reader = implode(',', $readers) . ",";
		}
		else
		{
			$reader = '';
		}

		$data = array('id' => $id[0],'reader' => $reader);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'delete':

		$q=$dbh->prepare("DELETE FROM cm_journals WHERE id = ?");

		$q->bindParam(1,$id[0]);

		$q->execute();

		$error = $q->errorInfo();

		break;

	case 'add_comment':

		$c = array();

		$time =  date('Y-m-d H:i:s');

		$c = array('id' => $id[0],'by' =>  $_SESSION['login'],'text' => $comment_text,'time' => $time);

		//Get current comment thread, if any
		$q = $dbh->prepare("SELECT comments FROM cm_journals WHERE id = ?");

		$q->bindParam('1',$id[0]);

		$q->execute();

		$thread = $q->fetch(PDO::FETCH_ASSOC);

		if (count($thread['comments']) > 0)
		{
			$old = unserialize($thread['comments']);

			$old[] = $c;

			$new = serialize($old);

			$update = $dbh->prepare("UPDATE cm_journals SET comments = :comments, commented = 'yes' WHERE id = :id");

			$data = array('comments' => $new,'id' => $id[0]);

			$update->execute($data);

			$error = $q->errorInfo();
		}
		else
		{
			$update = $dbh->prepare("UPDATE cm_journals SET comments = :comments, commented = 'yes' WHERE id = :id");

			$new = serialize($c);

			$data = array('comments' => $new,'id' => $id[0]);

			$update->execute($data);

			$error = $q->errorInfo();
		}

		//notify users via email

		//figure out who needs to receive this notification
		$q = $dbh->prepare("SELECT reader,username FROM cm_journals WHERE id =?");

		$q->bindParam(1,$id[0]);

		$q->execute();

		$u = $q->fetch(PDO::FETCH_ASSOC);

		$involved = $u['reader'] . $u['username'];

		$inv = explode(',', $involved);

		$this_user = array($_SESSION['login']);

		$notify = array_diff($inv,$this_user);

		foreach ($notify as $user) {
			$commenter = username_to_fullname($dbh,$_SESSION['login']);

			$email = user_email($dbh,$user);

			$subject = "ClinicCases: $commenter has commented on a journal.";

			$body = "$commenter has commented on a journal.n\n" . CC_EMAIL_FOOTER;

			mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);
		}

		//TODO test on mail server

		break;

	case 'delete_comment':

		//Get current comment array for this journal
		$q = $dbh->prepare('SELECT comments FROM cm_journals WHERE id = ?');

		$q->bindParam(1,$id[0]);

		$q->execute();

		$error = $q->errorInfo();

		$result = $q->fetch(PDO::FETCH_ASSOC);

		$old = unserialize($result['comments']);

		unset($old[$comment_id]);

		if (count($old) > 0)
		{
			$new = serialize($old);

			$sql = "UPDATE cm_journals SET comments = ? WHERE id = ?";
		}
		else
		{
			$new = '';

			$sql = "UPDATE cm_journals SET comments = ?, commented = '' WHERE id = ?";
		}

		//put comment array back in db
		$update = $dbh->prepare($sql);

		$update->bindParam(1,$new);

		$update->bindParam(2,$id[0]);

		$update->execute();

		$error = $update->errorInfo();

		break;
}

if ($error[1])
{
	$return = array('error' => true,'message'=>'Sorry, there was an error.');

	echo json_encode($return);
}
else
{
	switch ($type) {
		case 'mark_read':
			$return = array('error' => false,'message' => "Journals Marked Read.");
			echo json_encode($return);
			break;

		case 'mark_unread':
			$return = array('error' => false,'message' => "Journals Marked Unread.");
			echo json_encode($return);
			break;

		case 'archive':
			$return = array('error' => false,'message' => 'Journals Archived.');
			echo json_encode($return);
			break;

		case 'edit':
			$return = array('error' => false,'message' => 'Changes Saved.');
			echo json_encode($return);
			break;

		case 'delete':
			$return = array('error' => false,'message' => 'Journal Deleted.');
			echo json_encode($return);
			break;

		case 'add_comment':
			$return = array('error' => false,'message' => 'Comment Saved');
			echo json_encode($return);
			break;

		case 'delete_comment':
			$return = array('error' => false,'message' => 'Comment Deleted');
			echo json_encode($return);
			break;

	}
}
