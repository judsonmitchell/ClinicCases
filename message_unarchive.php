<?php
session_start();
include 'db.php';
$rand = rand();
$archive = mysql_query("UPDATE `cm_messages` SET `archive` = '' WHERE `id` = '$_GET[id]'");
header('Location:message_roll.php?notify=3&archive=1&ieyousuck=$rand');












?>
