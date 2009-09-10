<?php
session_start();
if (!$_SESSION){echo "You must be logged in to view this page.";die;}
include '../db.php';

	$get_client = mysql_query("SELECT * FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");
	$r = mysql_fetch_object($get_client);
?>

<html>
<head>
<title>ClinicCases Mobile - <?php echo $r->first_name . " " . $r->last_name; ?></title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<h1>ClinicCases <span style="color:gray;font-style:italic;">Mobile</span></h1>
<a href="cm_home_m.php">Main Menu</a> | <a href="cases_m.php">Back</a><br>
<br>

<strong><?PHP echo $r->first_name . ' ' . $r->last_name; ?> </strong><br>
<?php
	if (!empty($r->address1))
	{echo $r->address1 . "<br>";}

	if(!empty($r->address2))
	{echo $r->address2 . "<br";}

	if(!empty($r->city))
	{echo $r->city . ",";}

	if(!empty($r->state))
	{echo $r->state . " ";}

	if(!empty($r->zip))
	{echo $r->zip . "<br>";}

	if(!empty($r->phone1))
	{echo "ph1:<a href='tel:" . $r->phone1 . "'>" . $r->phone1 . "</a><br>" ;}

	if(!empty($r->phone2))
	{echo "ph2:<a href='tel:" . $r->phone2 . "'>" . $r->phone2 . "</a><br>" ;}

	if(!empty($r->email))
	{echo "<a href='mailto:" . $r->email . "'>" . $r->email . "</a><br>" ;}


	?>

