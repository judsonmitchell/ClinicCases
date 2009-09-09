<?php
session_start();
include 'db.php';

$folder_name = $_GET['folder_name'];
$case_id = $_GET['case_id'];


/* This to delete folder reference in document database entry */

$query = mysql_query("UPDATE `cm_documents` SET `folder` = '' WHERE `folder` = '$folder_name' and `case_id` = '$case_id'");

/* This gets rid of the empty placeholder line for the folder in the documents table */
$query2 = mysql_query("DELETE FROM `cm_documents` WHERE `case_id` = '$case_id' AND `url` = '' AND `folder` = '' LIMIT 1");


echo "<br><br><a href=\"#\" onClick=\"killDroppables();createTargets('case_activity','case_activity');sendDataGet('cm_docs.php?id=$case_id&ieyousuck=$rand');return false;\">OK</a> ";





?>
