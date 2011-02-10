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
<title>ClinicCases Mobile - Case Contacts for<?php echo $r->first_name . " " . $r->last_name; ?></title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png" border="0"></a></p>

<a href="cm_home_m.php">Main Menu</a> > <a href="cases_m.php">Open Cases</a> >
<strong>Case Contacts for <?PHP echo $r->first_name . ' ' . $r->last_name; ?> </strong><br><br>
<?php
	$q = mysql_query("SELECT * FROM `cm_contacts` WHERE `assoc_case` = '$_GET[id]' ");
	while ($p = mysql_fetch_array($q))
		{

			echo "<a href=\"contact_m.php?type=contact&id=$p[id]\">$p[first_name] $p[last_name]</a><br>";
		}
?>
</body>
</html>
