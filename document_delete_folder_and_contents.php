<?php
session_start();
include 'db.php';

$folder_name = $_GET['folder_name'];
$case_id = $_GET['case_id'];
/* This to delete files off of server */
$unlink_query = mysql_query("SELECT * FROM `cm_documents` WHERE `folder` = '$folder_name' AND `case_id` = '$case_id' AND `url` != ''");


while ($r = mysql_fetch_array($unlink_query))
{
$doc = $r['url'];
unlink($doc);

}


/* This to delete document database entry */

$query = mysql_query("DELETE FROM `cm_documents` WHERE `folder` = '$folder_name' and `case_id` = '$case_id'");
echo "<br><br><a href=\"#\" onClick=\"killDroppables();createTargets('case_activity','case_activity');sendDataGet('cm_docs.php?id=$case_id');return false;\">OK</a> ";





?>
