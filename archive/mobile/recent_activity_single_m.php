<?php 
session_start();
include '../db.php';
include '../get_name.php';
include '../get_client_name.php';
include '../classes/format_dates_and_times.class.php';

$activity = $_GET[id];
$query = mysql_query("SELECT * FROM `cm_case_notes` WHERE `id` = '$activity' LIMIT 1");
$r = mysql_fetch_array($query);


$ttime = formatTime($r[time]);
?>
<html>
<head>
<title>ClinicCases Mobile - Recent Activity Detail</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<h1>ClinicCases <span style="color:gray;font-style:italic;">Mobile</span></h1>
<a href="cm_home_m.php">Main Menu</a> > <a href="recent_activity_m.php">Recent Activity</a><br>
<strong>Activity in the <?php getClient($r[case_id]); ?> case </strong><br>
<p>By: <?php getName($r[username]); ?></P>
<p>Date: <?php formatDateHuman($r[date]); ?></p>
<p>Time: <?php       echo $ttime[0] . ' ' .  $ttime[1] ;              ?></p>
<hr>
<P><?php echo  $r[description]; ?></p>
