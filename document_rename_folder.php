<?php
session_start();
include 'db.php';
$folder_name = $_GET['folder_name'];
$new_folder_name = $_GET['new_folder_name'];
$case_id = $_GET['case_id'];
$rand = rand();

$query = mysql_query("UPDATE `cm_documents` SET `folder` = '$new_folder_name' WHERE `folder` = '$folder_name' AND `case_id` = '$case_id'");

echo "<br><br><a href=\"#\" onClick=\"createTargets('case_activity','case_activity');sendDataGet('cm_docs.php?id=$case_id&ieyousuck=$rand');return false;\">OK</a> ";















?>
