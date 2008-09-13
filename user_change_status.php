<?php
session_start();
if (!$_SESSION)
{echo "You must be logged in to access this.";die;}

include 'db.php';
$determine_status = mysql_query("SELECT `status` FROM `cm_users` WHERE `id` = '$_GET[id]' LIMIT 1");
$ds = mysql_fetch_array($determine_status);
$status =$ds['status'];
if ($status == 'inactive')
{$updater = 'active';}
else
{$updater = 'inactive';}
$do_update = mysql_query("UPDATE `cm_users` SET `status` = '$updater' WHERE `id` = '$_GET[id]' LIMIT 1");
echo "<span style='color:red;font-weight:bold;font-style:italic'>$updater<br><br></span>";

?>