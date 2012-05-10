<?php
//Loads messages

session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/names.php');
require('../utilities/thumbnails.php');


function in_string($val,$string)
{
	$val_1 = ',' . $val .',';
	$val_2 = $val . ',';

	if (stristr($string, $val_1))
		{return true;}
	elseif (stristr($string, $val_2))
		{return true;}
	else
		{return false;}
}

$username = $_SESSION['login'];

if (isset($_POST['type']))
	{$type = $_POST['type'];}

switch ($type) {

	case 'inbox':

		$q = $dbh->prepare("SELECT * FROM (SELECT * FROM cm_messages WHERE `archive` NOT LIKE '%,$username,%' AND `archive` NOT LIKE '$username,%') AS no_archive WHERE (no_archive.to LIKE '%,$username,%' OR no_archive.to LIKE '$username,%') OR (no_archive.ccs LIKE  '%,$username,%' OR no_archive.ccs LIKE '$username,%') ORDER BY no_archive.time_sent DESC");

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

	case 'starred' :


	break;
}


include('../../../html/templates/interior/messages_display.php');
