<?php
session_start();
include '../db.php';
include '../classes/get_names.php';

if ($_POST)
	{
		//Get possible tos
		$to_list = explode(",",$_POST[to]);
		foreach ($to_list as $v)
		{
			$lookup_to = mysql_query("SELECT * FROM `cm_users` WHERE `first_name` LIKE '%$v%' OR `last_name` LIKE '%$v%' AND `status` = 'active'");
			while ($r = mysql_fetch_array($lookup_to))
				{
					$tos[]=  $r[username];
				}
			}

		IF (!empty($_POST[cc]))
	{
		$cc_list = explode(",",$_POST[cc]);

		//Get possible ccs
		foreach ($cc_list as $x)
		{
			$lookup_cc = mysql_query("SELECT * FROM `cm_users` WHERE `first_name` LIKE '%$x%' OR `last_name` LIKE '%$x%' AND `status` = 'active'");
			while ($s = mysql_fetch_array($lookup_cc))
				{
					$ccs[]=  $s[username];
				}
			}
	}

echo<<<HEAD
<html>
<head>
<title>ClinicCases Mobile - Add Recipients</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png"  border="0"></a></p>
<a class="nav" href="cm_home_m.php">Main Menu</a> > <a class="nav" href="messages_m.php">Message List</a> > <a href="#" onClick="history.back(1);return false">Your New Message</a> > <strong> Add Recipients

HEAD;

ECHO <<<FORM
<form name="add_recp" method="get" action="message_new_m.php">
<p>Select To<p>
<table>

FORM;
	foreach ($tos as $t)
		{
			$i=0;
			$name = new get_names; $toname = $name->get_users_name($t);
			echo "<tr><td><input type='checkbox' name='to" . rand()  . "' value='t:$t'></td><td>$toname</td><tr>";

		}

ECHO<<<FORM
</table>
<p>Select Cc:</p>
<table>
FORM;
if (!empty($_POST[cc]))
{
	foreach ($ccs as $c)
		{
			$name = new get_names; $ccname = $name->get_users_name($c);
			echo "<tr><td><input type='checkbox' name='cc" . rand() . "' value='c:$c'></td><td>$ccname</td><tr>";
		}
}
ECHO<<<FORM
</table>
<input type="submit" value="Add">  <input type="button" value="Add More" onClick="history.back(2);return false;">
FORM;

die;
}

?>
<html>
<head>
<title>ClinicCases Mobile - Add Recipients</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png"  border="0"></a></p>
<a class="nav" href="cm_home_m.php">Main Menu</a> > <a class="nav" href="messages_m.php">Message List</a> > > <strong> Add Recipients
<p>Type in the first or last names of each recipient, seperated by a comma.  Ex: "john,emily,Smith".</p>
<form name="lookup" action="add_recipients_m.php" method="post">
<p>To: <textarea name="to" cols="30" rows="2"></textarea></p>
<p>Cc: <textarea name="cc" cols="30" rows="2"></textarea></p>
<p><input type="submit" value="Find">  <input type="button" value="Cancel" onClick="history.back(1);return false;"></p>
