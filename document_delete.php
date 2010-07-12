<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}

include 'db.php';

$id = $_GET['id'];
$get_file_url = mysql_query("SELECT * FROM `cm_documents` WHERE `id` = '$id' LIMIT 1");

while ($r = mysql_fetch_array($get_file_url))
    {
    
    $prefix = substr($r[url], 0, 4);
    
    if ( $prefix !== 'http')
    {
  
		unlink($r[url]);
  
    }
    
}
$delete = mysql_query("DELETE FROM `cm_documents` WHERE `id` = '$id' LIMIT 1");

?>
