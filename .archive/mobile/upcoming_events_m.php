<?php 
session_start();
include '../db.php';
include '../get_name.php';
include '../get_client_name.php';
include '../classes/format_dates_and_times.class.php';
if (isset($_GET[s]))
{$start = $_GET[s];}
else
{$start = "0";}

$today = date('y-m-d');
?>
<html>
<head>
<title>ClinicCases Mobile - Upcoming Events</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<h1>ClinicCases <span style="color:gray;font-style:italic;">Mobile</span></h1>
<a href="cm_home_m.php">Main Menu</a><br>
<strong>Your Upcoming Events</strong>
<ul>
<?php
/* this is not done; need to think about what professor sees versus what student sees; */
echo "<ul>";
$query = mysql_query("SELECT * FROM `cm_events_responsibles` WHERE `username` = '$_SESSION[login]' ");
while ($r = mysql_fetch_array($query))
{
	$query2 = mysql_query("SELECT * FROM `cm_events` WHERE `date_due`  > '$today'  AND `id` = '$r[event_id]'  ORDER BY `date_due` DESC");
	/* PROBLEM HERE IS THAT IS CAN'T SORT THE DUE DATE RIGHT BECAUSE THERE'S ONLY ON DATA SET PER LOOP; need to do a join in sql */
	while ($r2 = mysql_fetch_array($query2))
		{
			$due = formatDateAsVarHuman($r2[date_due]);
echo <<<TASK
		
<li><a href = "upcoming_events_single_m.php?id=$r[id]">Due:$due[0] (Status: $r2[status])<br>$r2[task]</a></li> 
		
		
TASK;

		}
}
echo "</li>";
