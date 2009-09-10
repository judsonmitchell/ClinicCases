<?php
session_start();
if (!$_SESSION){echo "You must be logged in to view this page.";die;}
include '../db.php';


if ($_GET[type] == 'client')
{$q = mysql_query("SELECT * FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");}


if ($_GET[type] == 'contact')
{$q = mysql_query("SELECT * FROM `cm_contacts` WHERE `id` = '$_GET[id]' LIMIT 1");}


if ($_GET[type] == 'user')
{$q = mysql_query("SELECT * FROM `cm_users` WHERE `id` = '$_GET[id]' LIMIT 1");}

$r = mysql_fetch_object($q);

?>

<html>
<head>
<title>ClinicCases Mobile - ContactDetail for <?php echo $r->first_name . " " . $r->last_name; ?></title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<h1>ClinicCases <span style="color:gray;font-style:italic;">Mobile</span></h1>
<a href="cm_home_m.php">Main Menu</a><br><br>

<?php if (!empty($r->picture_url))
		echo "<img src='../". $r->picture_url . "' border='0' width=20 height=20>"; ?>
<strong><?PHP echo $r->first_name . ' ' . $r->last_name; ?> </strong><br>
<?php

	if (!empty($r->type))
	{echo $r->type . "<br>";}

	if (!empty($r->address))
	{echo $r->address . "<br>";}

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
	{echo "ph1: <a href='tel:" . $r->phone1 . "'>" . $r->phone1 . "</a><br>" ;}

	if(!empty($r->phone2))
	{echo "ph2: <a href='tel:" . $r->phone2 . "'>" . $r->phone2 . "</a><br>" ;}

	//phone types for users

	if(!empty($r->mobile_phone))
	{echo "mobile: <a href='tel:" . $r->mobile_phone . "'>" . $r->mobile_phone . "</a><br>" ;}

	if(!empty($r->office_phone))
	{echo "office: <a href='tel:" . $r->office_phone . "'>" . $r->office_phone . "</a><br>" ;}

	if(!empty($r->home_phone))
	{echo "home: <a href='tel:" . $r->office_phone . "'>" . $r->office_phone . "</a><br>" ;}

	//end phone type for users

	if (!empty($r->fax))
	{echo "fx: $r->fax<br>";}

	if(!empty($r->email))
	{echo "<a href='mailto:" . $r->email . "'>" . $r->email . "</a><br>" ;}

	if (!empty($r->notes))
	{echo "<p>$r->notes</p>";}

	?>

