<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';

$id = $_POST['id'];

$type = $_POST['type'];

if (isset($_POST['comment_text']))
{
	$comment_text = nl2br($_POST['comment_text']);
}
else
{
	$comment_text = null;
}

switch ($type) {

	case 'mark_read':
		$q = $dbh->prepare("UPDATE cm_journals SET `read` = 'yes' WHERE `id` = ?");

		$q->bindParam(1,$id);

		$q->execute();

		$error = $q->errorInfo();

		break;

	case 'archive':

		break;

	case 'new':

		break;

	case 'edit':

		break;

	case 'add_comment':

		$c = array();

		$time =  date('Y-m-d H:i:s');

		$c = array('id' => $id,'by' =>  $_SESSION['login'],'text' => $comment_text,'time' => $time);

		//Get current comment thread, if any
		$q = $dbh->prepare("SELECT comments FROM cm_journals WHERE id = ?");

		$q->bindParam('1',$id);

		$q->execute();

		$thread = $q->fetch(PDO::FETCH_ASSOC);

		if (count($thread['comments']) > 0)
		{
			$old = unserialize($thread['comments']);

			$old[] = $c;

			$new = serialize($old);

			$update = $dbh->prepare("UPDATE cm_journals SET comments = :comments WHERE id = :id");

			$data = array('comments' => $new,'id' => $id);

			$update->execute($data);

			$error = $q->errorInfo();
		}
		else
		{
			$update = $dbh->prepare("UPDATE cm_journals SET comments = :comments WHERE id = :id");

			$new = serialize($c);

			$data = array('comments' => $new,'id' => $id);

			$update->execute($data);

			$error = $q->errorInfo();
		}
}

if ($error[1])
{
	$return = array('error' => true,'message','Sorry, there was an error.');

	echo json_encode($return);
}
else
{
	switch ($type) {
		case 'mark_read':
			$return = array('error' => false);
			echo json_encode($return);
			break;

		case 'add_comment':
			$return = array('error' => false,'message' => 'Comment added');
			echo json_encode($return);
			break;

		default:
			# code...
			break;
	}
}