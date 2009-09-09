<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}
include 'db.php';

$q = mysql_query("DELETE FROM `cm_board` WHERE `id` = '$_POST[id]' LIMIT 1");

?>
