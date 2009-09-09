<?php
session_start();
include 'db.php';
$id = $_GET['id'];
$get_file_url = mysql_query("SELECT * FROM `cm_documents` WHERE `id` = '$id' LIMIT 1");
while ($r = mysql_fetch_array($get_file_url))
{
$url = $r['url'];
unlink($url);

}
$delete = mysql_query("DELETE FROM `cm_documents` WHERE `id` = '$id' LIMIT 1");

?>
