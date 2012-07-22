<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/names.php';
include '../utilities/thumbnails.php';
include '../utilities/convert_times.php';

if($_SESSION['permissions']['reads_journals'] == '0' &&
$_SESSION['permissions']['writes_journals'] == '0')
{
	die('Sorry, you do not have permission to read or write journals');
}

$id = $_POST['id'];

$q = $dbh->prepare("SELECT * FROM cm_journals WHERE id = ?");

$q->bindParam(1,$id);

$q->execute();

$journal = $q->fetch(PDO::FETCH_ASSOC);

extract($journal);

include '../../../html/templates/interior/journals_detail.php';

