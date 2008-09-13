<?php
session_start();
include 'db.php';
$mark_done = mysql_query("UPDATE `cm_events` SET `status` = 'Done' WHERE `id` = '$_GET[event_id]'");
header('Location: cm_cases_events.php?alerter=done&id=' . $_GET[case_id]);














?>
