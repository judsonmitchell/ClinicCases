<?php
// session_start();
// require('../auth/session_check.php');
// include '../../../db.php';
// include '../utilities/names.php';
include 'lib/php/utilities/thumbnails.php';
include 'lib/php/utilities/convert_times.php';
// include '../html/gen_select.php';

if (
	$_SESSION['permissions']['reads_journals'] == '0' &&
	$_SESSION['permissions']['writes_journals'] == '0'
) {
	die('Sorry, you do not have permission to read or write journals');
}

$id = $_GET['journal_id'];

if (isset($_GET['view'])) {
	$view = $_GET['view'];
} else {
	$view = null;
}

$q = $dbh->prepare("SELECT * FROM cm_journals WHERE id = ?");

$q->bindParam(1, $id);

$q->execute();

$journal = $q->fetch(PDO::FETCH_ASSOC);

if (is_array($journal)) {
	extract($journal);
	include 'html/templates/interior/journals_detail.php';
} else {
	include 'html/templates/interior/journal_not_found.php';
}
