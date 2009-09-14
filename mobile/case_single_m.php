<?php
session_start();
if (!$_SESSION){echo "You must be logged in to view this page.";die;}
include '../db.php';
include '../classes/format_dates_and_times.class.php';

	$get_client = mysql_query("SELECT * FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");
	$r = mysql_fetch_object($get_client);
?>

<html>
<head>
<title>ClinicCases Mobile - <?php echo $r->first_name . " " . $r->last_name; ?></title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png" border="0"></a></p>

<a href="cm_home_m.php">Main Menu</a> > <a href="cases_m.php">Results</a> >
<strong><?PHP echo $r->first_name . ' ' . $r->last_name; ?> </strong><br>
<p>Latest Activity</p>
<table>
<?php
	$q = mysql_query("SELECT * FROM `cm_case_notes` WHERE `case_id` = '$_GET[id]' ORDER BY `date` desc LIMIT 0,5");
	while ($r = mysql_fetch_array($q))
		{
			echo "<tr><td valign='top'>" . formatDate2($r[date]) . "</td><td>$r[description]</td><td valign='top'>$r[username]</td></tr>";
		}
		if (mysql_num_rows($q)<1)
		{echo "<p class='none'>No results found.</p>";}
?>
</table>
<P>Upcoming Events</p>
<table>
<?php


?>
</table>
</body>
</html>
