<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/thumbnails.php');
require('../utilities/names.php');
require('../utilities/convert_times.php');
require('../html/gen_select.php');
require('../users/user_data.php');

function check_attachments($dbh,$post_id)
{
	$q = $dbh->prepare("SELECT * FROM cm_board_attachments WHERE post_id = ?");

	$q->bindParam(1,$post_id);

	$q->execute();

	if ($q->rowCount() > 0)
	{
		$attachments = $q->fetchAll(PDO::FETCH_ASSOC);

		$a = null;

		foreach ($attachments as $v) {

			$a .= '<p><a href="#" class="attachment" data-id="' . $v['id'] . '">' . $v['name'] . '</a></p>';
		}

		return $a;
	}
	else
	{
		return false;
	}
}

function get_viewers($dbh,$post_id)
{
	$q = $dbh->prepare("SELECT viewer FROM cm_board_viewers WHERE post_id = ?");

	$q->bindParam('1',$post_id);

	$q->execute();

	$viewers = $q->fetchAll(PDO::FETCH_ASSOC);

	$viewers_string = null;

	foreach ($viewers as $v) {
		$viewers_string .= $v['viewer'] . ',';
	}

	return rtrim($viewers_string, ',');
}

$this_users_groups = user_which_groups($dbh,$_SESSION['login']);

$grps = implode("','", $this_users_groups);

$q = $dbh->prepare("SELECT * FROM `cm_board` as all_posts
JOIN
(SELECT * FROM cm_board_viewers WHERE viewer IN ('$grps') GROUP BY cm_board_viewers.post_id) AS  this_user
ON
all_posts.id = this_user.post_id ORDER BY all_posts.time_edited DESC");

$q->execute();

$error = $q->errorInfo();

$posts = $q->fetchAll(PDO::FETCH_ASSOC);

include '../../../html/templates/interior/board_display.php';


