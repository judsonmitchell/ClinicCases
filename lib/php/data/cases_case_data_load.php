<?php //scripts for case data tab in case detail
session_start();
require('../auth/session_check.php');
require('../../../db.php');

if (isset($_POST['id'])) {
	$case_id = $_POST['id'];
}

if (isset($_POST['type'])) {
	$type = $_POST['type'];
}

if ($type = 'new')
	{echo "new case form";}
else
	{echo "Just display data";}
