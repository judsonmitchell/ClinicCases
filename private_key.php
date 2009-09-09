<?php
session_start();
if (!$_SESSION)
	{die("Error: You are not logged in.");}

include 'db.php';
include 'classes/url_key_generator.php';

if ($_POST[change])
	{

		$new_key = alphanumericPass();
		$reset = mysql_query("UPDATE `cm_users` SET `private_key` = '$new_key' WHERE `username` = '$_SESSION[login]' LIMIT 1");
		if (mysql_error($connection))
			{echo "There was a problem.  Please try again.";}
			else
			{echo $new_key;}

die();
	}



$get_current_key = mysql_query("SELECT `username`,`private_key` FROM `cm_users` WHERE `username` = '$_SESSION[login]' LIMIT 1");
$r = mysql_fetch_object($get_current_key);
?>

<div id="close"><a href="#" onclick="Effect.Shrink('window1');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small.png" border="0"></a></div>
<div id="substance">
<div style="margin:10px 20px 20px 20px;">
<h4>Private Key</h4>
<p class="smallgray_nohref">This is your private key which is used for web-based services (Google Calendar, etc) to access your account information, e.g. calendar and RSS feeds. Do not share it with anyone.  If you suspect that your key has been compromised, please reset it.</p>
<br>
<br>
<p>Your Ical feed: <a target="_new" href="<?php echo $CC_base_url ?>feeds_ical_generate.php?sid=<?php echo $r->private_key; ?>"><?php echo $CC_base_url ?>feeds_ical_generate.php?sid=<?php echo $r->private_key; ?></a>
<br>
<br>
<P>Your Private Key: <div id="key"><strong><?php echo  $r->private_key ?></strong></div></P>
<P><a href="#" onClick="new Ajax.Updater('key','private_key.php',{parameters:({change:'yes'}),onSuccess:function(){
	new Ajax.Updater('window1','private_key.php',{method:'get'});
	}});return false;">Reset Key</a></P>

</div>
</div>




