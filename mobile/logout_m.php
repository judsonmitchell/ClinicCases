<?php
session_start();
unset($_SESSION['login']);
unset($_SESSION['class']);
session_destroy();

?>
<html>
<head>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
<meta http-equiv="refresh" content="5;URL=index.php">
</head>
<body>
<div id="content" style="margin-top:30px;">
<h4>You have been logged out</h4>


</div>
