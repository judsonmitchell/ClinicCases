<?php 
session_start();
include 'db.php';
$kill_log = mysql_query("UPDATE `cm_logs` SET `session_id` = '' WHERE `session_id` = '$_COOKIE[PHPSESSID]' LIMIT 1");
unset($_SESSION['login']); 
session_destroy();

?>
<html>
<head>
<link rel="stylesheet" href="cm.css" type="text/css">
<meta http-equiv="refresh" content="5;URL=index.php">
</head>
<body>
<div id="content" style="margin-top:30px;">
<h4>You have been logged out</h4>


</div>
