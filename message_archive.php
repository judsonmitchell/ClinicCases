<?php
session_start();
include 'db.php';
$archive = mysql_query("UPDATE `cm_messages` SET `archive` = 'yes' WHERE `id` = '$_GET[id]'");
if (isset($_GET[case_id]))
{header('Location:message_roll.php?notify=2&case_id=' .$_GET[case_id]);}
else
{
header('Location:message_roll.php?notify=2');
}











?>
