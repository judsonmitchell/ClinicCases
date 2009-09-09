<?php
session_start();
include 'session_error.php';
include 'db.php';
$id = $_GET['id'];
$activate = mysql_query("DELETE FROM `cm_users` WHERE `id` = '$id' LIMIT 1");
ECHO "<span style=\"color:red;\">Deleted.</span>";

?>
