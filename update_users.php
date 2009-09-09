<?php
session_start();
if (!$_SESSION)
{die("Error:You are not logged in");}
include 'db.php';


$old = $_GET['old'];
$new = $_GET['new'];
$query = mysql_query("SELECT * FROM `cm` WHERE `professor` = '$old'");
while ($r = mysql_fetch_array($query))
{
$id = $r['id'];
$prof = $r['professor'];
$update = mysql_query("UPDATE `cm` SET `professor` = '$new' WHERE `id` = '$id'");

}

echo "done. "






?>
