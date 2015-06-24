<?php
@session_start();
require_once dirname(__FILE__) . '/../../../db.php';
require_once(CC_PATH . '/lib/php/auth/session_check.php');
require_once(CC_PATH . '/lib/php/utilities/thumbnails.php');
require_once(CC_PATH . '/lib/php/utilities/names.php');
require_once(CC_PATH . '/lib/php/utilities/convert_times.php');
require_once(CC_PATH . '/lib/php/html/gen_select.php');
require_once(CC_PATH . '/lib/php/users/user_data.php');

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

			$a .= '<p><a href="#" class="attachment ' . $v['extension'] .'" data-id="' . $v['id'] . '">' . $v['name'] . '</a></p>';
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

if (isset($_POST['s']))
{
	$search = $_POST['s'];

	$sql = "SELECT * FROM `cm_board` as all_posts
	JOIN
	(SELECT * FROM cm_board_viewers WHERE viewer IN ('$grps') GROUP BY cm_board_viewers.post_id) AS  this_user
	ON
	all_posts.id = this_user.post_id AND (all_posts.title LIKE '%$search%' OR all_posts.body LIKE '%$search%') ORDER BY all_posts.time_edited DESC";
}
else
{
	$sql = "SELECT * FROM `cm_board` as all_posts
	JOIN
	(SELECT * FROM cm_board_viewers WHERE viewer IN ('$grps') GROUP BY cm_board_viewers.post_id) AS  this_user
	ON
	all_posts.id = this_user.post_id ORDER BY all_posts.time_edited DESC";
}

$q = $dbh->prepare($sql);

$q->execute();

$error = $q->errorInfo();

$posts = $q->fetchAll(PDO::FETCH_ASSOC);

if (!$_SESSION['mobile']){
    include '../../../html/templates/interior/board_display.php';
}


