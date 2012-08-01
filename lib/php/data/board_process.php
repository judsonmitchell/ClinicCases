<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/names.php';
include '../users/user_data.php';

$user = $_SESSION['login'];

$action = $_POST['action'];

if (isset($_POST['id']))
{
	$id = $_POST['id'];
}

if (isset($_POST['item_id']))
{
	$item_id = $_POST['item_id'];
}

if (isset($_POST['post_title']))
{
	$title = $_POST['post_title'];
}

if (isset($_POST['text']))
{
	$text = $_POST['text'];
}

if (isset($_POST['post_color']))
{
	$color = $_POST['post_color'];
}

if (isset($_POST['viewer_select']))
{
	$viewers = $_POST['viewer_select'];
}

switch ($action) {

	case 'new':
		$q = $dbh->prepare("INSERT INTO `cm_board` (`id`, `title`, `body`, `color`, `author`, `viewers`, `time_added`, `time_edited`) VALUES (NULL, '', '', '', ?, '', NOW(), NOW());");

		$q->bindParam(1,$user);

		$q->execute();

		$error = $q->errorInfo();

		$post_id = $dbh->lastInsertId();

		break;

	case 'edit':
		$q = $dbh->prepare("UPDATE `cm_board` SET `title` = :title, `body` = :body, `color` = :color, `viewers` = :viewers, `time_edited` = NOW() WHERE `id` = :id");

		$viewers_ser = serialize($viewers);

		$data = array('title' => $title, 'body' => $text, 'color' => $color, 'viewers' => $viewers_ser,'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		break;

	case 'delete':

		$q = $dbh->prepare("DELETE FROM cm_board WHERE id = ?");

		$q->bindParam(1,$item_id);

		$q->execute();

		$error = $q->errorInfo();

		//check for attachments and delete them
		$attch = $dbh->prepare("SELECT * FROM cm_board_attachments WHERE post_id = ?");

		$attch->bindParam(1, $item_id);

		$attch->execute();

		if ($attch->rowCount() > 0)
		{
			$attachments = $attch->fetchAll(PDO::FETCH_ASSOC);

			foreach ($attachments as $a) {

				//delete attachment files
				unlink(CC_DOC_PATH . '/' . $a['local_file_name']);
			}

			//Delete from db
			$del = $dbh->prepare("DELETE FROM cm_board_attachments WHERE post_id = ?");

			$del->bindParam(1,$post_id);

			$del->execute();

			$error = $del->errorInfo();
		}

		break;

	case 'download':

		$open_query = $dbh->prepare("SELECT * FROM cm_board_attachments WHERE id = :item_id");

		$open_query->bindParam('item_id',$item_id);

		$open_query->execute();

		$doc_properties = $open_query->fetch();

		$error = $open_query->errorInfo();

		$file = CC_DOC_PATH . "/" . $doc_properties['local_file_name'];
		header('Content-Description: File Transfer');
		header("Content-type: application/force-download");
		//header("Content-type:" . finfo_file($finfo, $file));
		//header('Content-Type: application/octet-stream');
		header('Content-disposition: attachment; filename="'. $doc_properties['name'] .'"');
		header('Content-Transfer-Encoding:  binary');
		header("Content-Length: ". filesize($file));
		header('Expires: 0');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Pragma: no-cache');
		readfile(CC_DOC_PATH . "/" . $doc_properties['local_file_name']);
		exit;
		break;
}

if ($error[1])
{
	$return = array('error' => true,'message'=>'Sorry, there was an error.');

	echo json_encode($return);
}
else
{
	switch ($action) {
		case 'new':

			$response = array('error' => false,'post_id' => $post_id);

			echo json_encode($response);

			break;

		case 'edit':

			$response = array('error' => false,'message' => 'Changes Saved');

			echo json_encode($response);

		break;

		case 'delete':

			$response = array('error' =>false,'message' => "Post Deleted");

			echo json_encode($response);

		break;

	}
}