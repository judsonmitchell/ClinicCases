<?php
session_start();
include 'db.php';
$archive_all = mysql_query("UPDATE `cm_messages` SET `archive` = 'yes' WHERE `to` = '$_SESSION[login]'");
header('Location:message_roll.php?notify=4');













?>
