<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/names.php';
include '../utilities/thumbnails.php';
include '../utilities/convert_times.php';
include '../html/gen_select.php';

if($_SESSION['permissions']['reads_journals'] == '0' &&
$_SESSION['permissions']['writes_journals'] == '0')
{
	die('Sorry, you do not have permission to read or write journals');
}

$id = $_POST['id'];

if (isset($_POST['view']))
{
	$view = $_POST['view'];
}
else
{
	$view = null;
}

$q = $dbh->prepare("SELECT * FROM cm_journals WHERE id = ?");

$q->bindParam(1,$id[0]);

$q->execute();

$journal = $q->fetch(PDO::FETCH_ASSOC);

extract($journal);

include '../../../html/templates/interior/journals_detail.php';

