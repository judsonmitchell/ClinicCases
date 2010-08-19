<?php
session_start();
include 'db.php';
if ($_POST)
{
$bug = mysql_query("INSERT INTO `cm_bugs` ( `id` ,`date` ,`problem` ,`code` ,`app` ,`version` ,`height` ,`width` ,`platform` ,`agent` ,`page` ,`username` ,
`java`,`env_session` ) VALUES (NULL, NOW(),'$_POST[problem]','$_POST[code]','$_POST[app]','$_POST[version]','$_POST[height]','$_POST[width]','$_POST[platform]','$_POST[agent]','$_POST[page]','$_POST[username]','$_POST[java]','$_POST[env_session]')");

$get_email = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$_POST[username]'");
$z = mysql_fetch_array($get_email);

$subject= "Bug Report on ClinicCases.com";
$message = "A bug report from $z[first_name] $z[last_name]:  " . stripslashes($_POST[problem]) . "\nUserId: $z[id]" . "\nSession: $_POST[env_session]";
$headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\n" .
   "Reply-To: $z[email]" . "\n" .
   "X-Mailer: PHP/" . phpversion();

mail('judsonmitchell@gmail.com',$subject,$message,$headers);

echo <<<REPLY

<span id="close"><a href="#" onclick="Effect.Fade('bug');return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small_blue.png" border="0"></a></span>
<p>Thanks for reporting this.<br>I'll look into it ASAP.</p>
<br>
<p><a href="http://docs.google.com/Doc?id=ddbddn2w_160fngmb4" target="_new">View Known Issues and Feature Requests</a></p><br><br>
<p>Follow Known Issues and<br>Feature Requests <a href="http://docs.google.com/RSSFeed?userID=ddbddn2w&authToken=rD2DLHHSHVIrwwPfGr9BGg00&docid=ddbddn2w_160fngmb4&revisioncount=10&x=" target="_new">via RSS  <img src="images/feed-icon-14x14.png" border="0"></a></p>
<P>Ask questions and get answers on our <a href="http://cliniccases.com/forums" target="_new">Forum</a></p>
REPLY;
DIE;

}


?>
<div id="bar" style="width:100%;height:30px;background-color:rgb(195, 217, 255);"></div>
<div id = "bug_reporter" style="margin-top:0px;">
<span id="close"><a href="#" onclick="Effect.Fade('bug');return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small_blue.png" border="0"></a></span>

<h4>Report Problems and Suggestions</h4>
<br>
<form name="bugs" id="bugs" action="bugs_report.php" method="post">

<textarea id="problem" name="problem" cols="28" rows="13" >Your Problem or Suggestion here</textarea>
<br>
<input type="hidden" name="code" id="code">
<input type="hidden" name="app" id="app">
<input type="hidden" name="version" id="version">
<input type="hidden" name="height" id="height">
<input type="hidden" name="width" id="width">
<input type="hidden" name="platform" id="platform">
<input type="hidden" name="agent" id="agent">
<input type="hidden" name="java" id="java">
<input type="hidden" name="page" id="page">

<input type="hidden" name="env_session" value="<?php if (!$_SESSION){echo "session expired";}else{echo "session active";} ?>">





<input type="hidden" name="username" id="username" value="<?php echo $_SESSION[login] ?>">
<input type= "button" value="Submit" onClick="createTargets('bug_reporter','bug_reporter');sendDataPost('bugs_report.php','bugs');return false;">
</form>
</div>
<img src="images/onload_tricker.gif" border="0" onLoad="check();">

