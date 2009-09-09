<?php
session_start();
include 'db.php';
$doc_id = $_GET['doc_id'];
$chosen_folder = $_GET['chosen_folder'];
$query = mysql_query("UPDATE `cm_documents` SET `folder` = '$chosen_folder' WHERE `id` = '$doc_id' LIMIT 1");
ECHO "DONE";













?>
