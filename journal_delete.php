<?php
session_start();
include 'db.php';
$del_journal = mysql_query("UPDATE `cm_journals` SET `deleted` = 'yes' WHERE `id` = '$_GET[id]' LIMIT 1");
$rand = rand();
header('location: journal_list.php?notify=1&ieyousuck=' . $rand);

?>
