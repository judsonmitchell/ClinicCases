<?php
session_start();
include 'db.php';
$mark = mysql_query("UPDATE `cm_journals` SET `read` = 'yes' WHERE `id` = '$_GET[id]' LIMIT 1");








?>
