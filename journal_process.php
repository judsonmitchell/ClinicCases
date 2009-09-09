<?php
session_start();
include 'db.php';
$add_journal = mysql_query("INSERT INTO `cm_journals` (`id`,`username`,`content`,`date_added`) VALUES (NULL,'$_SESSION[login]','$_POST[content]', NOW())");

?>
