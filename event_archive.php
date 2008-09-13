<?php
session_start();
include 'db.php';
$archive = mysql_query("UPDATE `cm_events` SET `archived` = 'y' WHERE `id` = '$_GET[id]'");
echo "<span style=\"color:red;font-weight:bold;\">Archived</span>";











?>
