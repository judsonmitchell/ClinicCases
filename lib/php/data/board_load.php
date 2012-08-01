<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/thumbnails.php');
require('../utilities/names.php');
require('../utilities/convert_times.php');
require('../html/gen_select.php');

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

$q = $dbh->prepare("SELECT * FROM cm_board ORDER BY time_added DESC");

$q->execute();

$posts = $q->fetchAll(PDO::FETCH_ASSOC);

include '../../../html/templates/interior/board_display.php';


