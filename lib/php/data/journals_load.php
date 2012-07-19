<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/convert_times.php';
include '../utilities/names.php';
include '../utilities/thumbnails.php';

$user = $_SESSION['login'];

if ($_SESSION['permissions']['reads_journals'] == '1')
{
	$sql = "SELECT * FROM cm_journals WHERE professor LIKE '$user'";
}
elseif ($_SESSION['permissions']['writes_journals'] == '1')
{
	$sql = "SELECT * FROM cm_journals WHERE username = '$user'";
}
else
{
	die("Sorry, you do not have permission to read or write journals.");
}

$q = $dbh->prepare($sql);