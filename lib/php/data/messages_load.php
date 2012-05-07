<?php
//Loads messages

session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/names.php');

$user = $_SESSION['username'];

if (isset($_POST['type']))
	{$type = $_POST['type'];}

switch ($type) {

	case 'inbox':

		$q = $dbh->prepare("SELECT * from cm_messages WHERE ");

	break;

	case 'sent':



	break;

	case 'archive':


	break;

	case 'draft' :


	break;
}


include('../../../html/templates/interior/messages_display.php');
