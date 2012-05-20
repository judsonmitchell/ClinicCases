<?php
//Load case-related messages

session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/names.php');
require('../utilities/convert_times.php');
require('../utilities/thumbnails.php');
require('../utilities/format_text.php');
require('../html/gen_select.php');

$username = $_SESSION['login'];

$case_id = $_POST['case_id'];

$replies = false;

$q = $dbh->prepare("SELECT * FROM cm_messages WHERE `assoc_case` = ? AND `id` = `thread_id` ORDER BY `time_sent` DESC");

$q->bindParam(1, $case_id);

$q->execute();

$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

include('../../../html/templates/interior/cases_messages.php');

