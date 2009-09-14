<?php
session_start();
if (!$_SESSION){echo "You must be logged in to view this page.";die;}
include '../db.php';
?>

<html>
<head>
<title>ClinicCases Mobile - Add Time</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png" border="0"></a></p>
<a class="nav"  href="cm_home_m.php">Main Menu</a> > <strong>Add Time</strong>
