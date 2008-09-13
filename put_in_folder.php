<?php
include 'db.php';
$id = $_GET['id'];
$folder = $_GET['folder'];
$result = mysql_query("UPDATE `cm_documents` SET `folder` = '$folder' WHERE `id` = '$id' LIMIT 1");
?>
