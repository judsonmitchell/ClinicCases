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
		$q = $dbh->prepare("INSERT INTO `cm_board` (`id`, `title`, `body`, `color`, `author`, `time_added`, `time_edited`) VALUES (NULL, '', '', '', ?, NOW(), NOW());");

		$q->bindParam(1,$user);

		$q->execute();

		$error = $q->errorInfo();

		$post_id = $dbh->lastInsertId();

		break;

	case 'edit':
		$q = $dbh->prepare("UPDATE `cm_board` SET `title` = :title, `body` = :body, `color` = :color, `time_edited` = NOW() WHERE `id` = :id");

		$data = array('title' => $title, 'body' => $text, 'color' => $color, 'id' => $id);

		$q->execute($data);

		$error = $q->errorInfo();

		//now, update cm_board_viewers with users who are allowed to see post

		//first, delete old viewers
		$del_viewers = $dbh->prepare("DELETE FROM cm_board_viewers WHERE post_id = ?");

		$del_viewers->bindParam(1,$id);

		$del_viewers->execute();

		//second, add current viewers

		$viewers_query = $dbh->prepare("INSERT INTO cm_board_viewers (`id`, `post_id`,`viewer`) VALUES (NULL,:post_id,:viewer)");

		foreach ($viewers as $v) {

			$data = array('post_id' => $id,'viewer' => $v);

			$viewers_query->execute($data);

			//Notify viewer; TODO test with mail server
			$author = username_to_fullname ($dbh,$_SESSION['login']);
			$email = user_email($dbh,$v);
			$subject = "ClinicCases: $author posted on your Board";
			$body = "$author posted on your Board in ClinicCases: $title.\n\n" . CC_EMAIL_FOOTER;
			mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);
		}

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

		$open_query->bindParam(':item_id',$item_id);

		$open_query->execute();

		$doc_properties = $open_query->fetch();

		$error = $open_query->errorInfo();

		$file = CC_DOC_PATH . "/" . $doc_properties['local_file_name'];

        if ($doc_properties['extension'] == 'pdf'){
            header('Content-Description: File Transfer');
            header('Content-type: application/pdf');
            header('Content-disposition: inline; filename="'. $doc_properties['name'] .'"');
            header('Content-Transfer-Encoding: binary');
            header("Content-Length: ". filesize($file));
            header('Accept-Ranges: bytes');
            header('Expires: 0');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Pragma: no-cache');
            readfile(CC_DOC_PATH . "/" . $doc_properties['local_file_name']);
            exit;
            break;
        } else {
            $mime = finfo_open(FILEINFO_MIME_TYPE);
            $file = CC_DOC_PATH . "/" . $doc_properties['local_file_name'];
            header('Content-Description: File Transfer');
            header("Content-type: $mime");
            header("Pragma: "); 
            header("Cache-Control: ");
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
