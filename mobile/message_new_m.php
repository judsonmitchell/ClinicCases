<?php
session_start();
if (!$_SESSION)
{die("You must be logged in to view this page.");}

include '../db.php';
include '../classes/get_names.php';

if($_POST)
{
	$body_mod_a = addslashes($_POST[body]);
	$body_mod = nl2br($body_mod_a);

	$send_msg = mysql_query("INSERT INTO `cm_messages` (`id`,`thread_id`,`to`,`from`,`ccs`,`subject`,`body`,`assoc_case`) VALUES (NULL,'$_POST[thread_id]','$_POST[to]','$_POST[from]','$_POST[cc]','$_POST[subject]','$body_mod','$_POST[assoc_case]')");

			if (empty($_POST[thread_id]))
				{
					$lst_id = mysql_insert_id();
					$set_thread = mysql_query("UPDATE `cm_messages` SET `thread_id` = '$lst_id' WHERE `id` = '$lst_id' LIMIT 1");


				}

echo <<<MSG
<html>
<head>
<title>ClinicCases Mobile - Message Sent</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png" border="0"></a></p>
<a class="nav" href="cm_home_m.php">Main Menu</a> > <a class="nav"  href="messages_m.php">Message List</a><br>
<STRONG>Your message has been sent.</strong>
<p>To: $_POST[to]</p>
<p>CC: $_POST[cc]</P>
<p>From: $_POST[from]</p>
<p>Subject: $_POST[subject]</p>
<br>
<p>$_POST[body]</p>

MSG;



die;

}


$recips = array_values($_GET);

foreach ($recips as $z)
	{
		$rp = substr($z,0,2);
		if ($rp == 't:')
		{
			$to_list .= substr($z,2) . ",";
		}

		if ($rp == 'c:')
		{
			$cc_list .= substr($z,2) . ",";
		}
	}
	$to_list_strp = substr($to_list,0,-1);
	$cc_list_strp = substr($cc_list,0,-1);


?>
<html>
<head>
<title>ClinicCases Mobile - New Message</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png"  border="0"></a></p>
<a class="nav" href="cm_home_m.php">Main Menu</a> > <a class="nav" href="messages_m.php">Message List</a> >
<strong>New Message</strong><br>

<form name="new_msg" method="post" action="message_new_m.php">
<p>To: <input type="text" name="to" size = "35" value="<?php echo $to_list_strp; ?>"> </P>
<p>Cc: <input type="text" name="cc" size="35" value="<?php echo $cc_list_strp; ?>">  </p>
<p>From: <?php $n = new get_names; $nn = $n->get_users_name($_SESSION[login]); echo $nn;?></p>

<p>Re: <input type="text" name="subject" size = "35"></p>
<p>
<textarea name="body" cols="40" rows="15"></textarea>
</p>
<input type="hidden" name="from" value="<?php echo $_SESSION[login]; ?>">
<p><input type="submit" value="Send">  <input type="button" value="Cancel" onClick="location.href='messages_m.php';">
