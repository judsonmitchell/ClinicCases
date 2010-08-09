<?php
session_start();
if (!$_SESSION)
{die("Error:You are not logged in");}
include 'db.php';

if ($_POST)
{
$update = mysql_query("UPDATE `cm_users` SET `pref_journal` = '$_POST[pref_journals]', `pref_case` = '$_POST[pref_case]' WHERE `assigned_prof` LIKE '%$_SESSION[login]%'");

$update2 = mysql_query("UPDATE `cm_users` SET `pref_journal` = '$_POST[pref_journals]', `pref_case` = '$_POST[pref_case]', `pref_case_prof` = '$_POST[pref_case_prof]' WHERE `username` = '$_SESSION[login]'");

echo <<<NOTIFY
 <div id="notifier2" style="width:100%;height:20px;color:blue;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier2',{ duration: 4.0 });">Changes are made and will take effect the next time you log in.</div>
NOTIFY;
}

ECHO <<<NOTIFY
<span id="close"><a href="#" onclick="Effect.Shrink('window1');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small.png" border="0"></a></span>
<div id="substance">
<div style="margin:10px 20px 20px 20px;">
<h4>Configure Your Class:</h4>
<form id="configure">
<p><label for "cases">My students work on cases</label><input type="checkbox" name="pref_case" 
NOTIFY;

$get_change = mysql_query("SELECT `pref_journal`,`pref_case`,`pref_case_prof` FROM `cm_users` WHERE `username` = '$_SESSION[login]' LIMIT 1");
$z = mysql_fetch_array($get_change);
if ($z[pref_case] == 'on' )
{echo "checked='checked'";}
ECHO <<<NOTIFY

id="cases"></p>
<p><label for "journals">My students write journals</label><input type="checkbox" name="pref_journals" 
NOTIFY;
if ($z[pref_journal] == 'on' )
{echo "checked='checked'";}

ECHO <<<NOTIFY

id = "journals"></p>

<p><label for "cases2">I work on cases</label><input type="checkbox" name="pref_case_prof" 
NOTIFY;
//$get_change2 = mysql_query("SELECT `pref_journal`,`pref_case` FROM `cm_users` WHERE `username` = '$_SESSION[login]'");
//$z = mysql_fetch_array($get_change2);
if ($z[pref_case_prof] == 'on' )
{echo "checked='checked'";}
ECHO <<<NOTIFY

id="cases2"></p>

<br>
<p>
<input type="button" name="submit" value="Change Configuration" onClick="createTargets('window1','window1');sendDataPost('pref_config_change.php','configure');return false;"></p>



</form>
</div>
</div>
NOTIFY;


?>
