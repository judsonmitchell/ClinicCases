<?php
session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
include 'db.php';
if ($_POST)
{
	$new_pw = md5($_POST[new_pword]);
	$old_pw = md5($_POST[old_pword]);
$check_pw = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$_SESSION[login]'");
$a = mysql_fetch_array($check_pw);
if ($old_pw !== $a[password])
{
echo "The old password you provided does not match the one on file.  Please try again.";

}

else
{
$update = mysql_query("UPDATE `cm_users` SET `password` = '$new_pw' WHERE `username` = '$_SESSION[login]' LIMIT 1");
ECHO <<<NOTI
<div id="notifier2" style="width:100%;height:20px;color:blue;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier2',{ duration: 4.0 });" >Your Password Has Been Changed.</div>
NOTI;
$subject = "Your Password";
$body = "This is to notify you that your password has been changed successully.  If you did not request this, please contact your system adminstrator immediately.";
$rand = rand();
$notify = mysql_query("INSERT INTO `cm_messages` ( `id` ,`thread_id` ,`to` ,`from` ,`subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive` ,`temp_id` ) VALUES (NULL,'','$_SESSION[login]','system','$subject','$body','',CURRENT_TIMESTAMP,'','','$rand')");

$upd = mysql_query("UPDATE `cm_messages` SET `thread_id` = cm_messages.id WHERE `temp_id` = '$rand' LIMIT 1 ");

$del_upd = mysql_query("UPDATE `cm_messages` SET `temp_id` = '' WHERE `temp_id` = '$rand' LIMIT 1 ");

$headers = 'From: ' . $CC_default_email . "\n" .
   'Reply-To: ' . $CC_default_email . "\n" .
   'X-Mailer: PHP/' . phpversion();

$get_email = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$_SESSION[login]' LIMIT 1");
$x = mysql_fetch_array($get_email);
mail($x[email],$subject,$body,$headers);
die;



}
}
?>
<span id="close"><a href="#" onclick="Effect.Shrink('window1');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small.png" border="0"></a></span>
<div id="substance">
<h4>Complete the form below to change your password:</h4>
<div style="margin:10px 20px 20px 20px;">
<form id="pchange">
<table width="50%" border="0">
<tr><td><label for "old_pword" class="msg">Your Old Password</label></td><td><input type="password" name="old_pword" id="old_pword"></td></tr>
<tr><td>
<label for "new_pword" class="msg">Your New Password</label></td><td><input type="password" name="new_pword" id="new_pword"></td></tr>
<tr><td><label for "new_pword_c" class="msg">Type New Password to Confirm</label></td><td><input type="password" name="new_pword_c" id="new_pword_c"></td></tr>
<tr><td colspan="2" align="center"><a href="#" onClick="var spot = document.getElementById('new_pword');var spot2 = document.getElementById('new_pword_c');if (spot.value != spot2.value){alert('The new passwords you typed do not match. Please retype.');spot.value='';spot2.value='';return false;} else {          createTargets('substance','substance');sendDataPost('password_change.php','pchange');return false;}" alt="Change Your Password" title="Change Your Password"><img src='images/check.png' border="0"></a></td></tr>
</table>
</form>
</div>
</div>
