<?php
//Loads messages

session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/names.php');

$username = $_SESSION['username'];

if (isset($_POST['type']))
	{$type = $_POST['type'];}

switch ($type) {

	case 'inbox':

		$q = $dbh->prepare("SELECT * from cm_messages WHERE (`to` LIKE '%,$username,%' OR `to` LIKE '$username,%') OR (`ccs` LIKE  '%,$username,%' OR `ccs` LIKE '$username,%')  ORDER BY `time_sent` DESC");

		$q->execute();

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

	break;

	case 'sent':

		$q = $dbh->prepare("SELECT * from cm_messages WHERE `from` LIKE '$username' ORDER BY `time_sent` DESC");

		$q->execute();

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

	break;

	case 'archive':

		$q = $dbh->prepare("SELECT * from cm_messages WHERE `archive` LIKE '%,$username,%' OR `archive` LIKE '$username,%' ORDER BY `time_sent` DESC");

		$q->execute();

		$msgs = $q->fetchAll(PDO::FETCH_ASSOC);


	break;

	case 'draft' :


	break;
}


include('../../../html/templates/interior/messages_display.php');
