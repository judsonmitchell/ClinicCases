<?php
session_start();
include 'db.php';
$mark_read = mysql_query("UPDATE `cm_messages` SET `read` = 'yes' WHERE `id` = '$_GET[id]' LIMIT 1");



?>
