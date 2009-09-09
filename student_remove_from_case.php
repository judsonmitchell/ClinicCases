<?php
session_start();
if(!$_SESSION)
{die("Error: You Are Not Logged In.");}
include 'db.php';

$remove = mysql_query("UPDATE `cm_cases_students` SET `status` = 'inactive', `date_removed` = NOW() WHERE `username` = '$_GET[username]' AND `case_id` = '$_GET[case_id]' ");




echo "<span style=\"color:red;\">Inactive</a>";
?>
