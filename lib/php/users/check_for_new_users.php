<?php
//Script to check for new users.  Called from Users.php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$q = $dbh->prepare("SELECT * FROM cm_users WHERE `new` = 'yes'");

$q->execute();

$count = $q->rowCount();

if ($_SESSION['permissions']['activate_users']  == "1")
{

	if ($count > 0)
		{
			$result = array('new_user' => true,'number' => $count);
			echo json_encode($result);
		}
	else
		{
			$result = array('new_user' => false,'number' => '0');
			echo json_encode($result);
		}
}

else

{
		$result = array('new_user' => false,'number' => '0');
		echo json_encode($result);
}