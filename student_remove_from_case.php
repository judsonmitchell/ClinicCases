<?php
session_start();
if(!$_SESSION)
{die("Error: You Are Not Logged In.");}
include 'db.php';
include './classes/get_names.php';

$remove = mysql_query("UPDATE `cm_cases_students` SET `status` = 'inactive', `date_removed` = NOW() WHERE `username` = '$_GET[username]' AND `case_id` = '$_GET[case_id]' ");

$z = new get_names;
$student = $z->get_users_name($_GET['username']);
$client = $z->get_clients_name($_GET['case_id']);
echo "$student removed from $client case.";

?>
