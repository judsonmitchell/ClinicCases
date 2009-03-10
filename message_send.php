<?php
session_start();
include 'db.php';
$body_mod_a = addslashes($_POST[body]);
$body_mod = nl2br($body_mod_a);
$rand = rand();

if (!empty($_POST[group]))
{


if ($_POST[group] == 'All Your Students')
{$group_query = "SELECT * FROM `cm_users` WHERE `assigned_prof` = '$_SESSION[login]' AND `status` = 'active'";}

if ($_POST[group] == 'All Professors')
{$group_query = "SELECT * FROM `cm_users` WHERE `class` = 'prof' AND `status` = 'active'";}

if ($_POST[group] == 'All Users')
{$group_query = "SELECT * FROM `cm_users` WHERE `status` = 'active'";}

if ($_POST[group] == 'All Students')
{$group_query = "SELECT * FROM `cm_users` WHERE `class` = 'student' AND `status` = 'active'";}


$send_to_group = mysql_query("$group_query");
while ($result = mysql_fetch_array($send_to_group))
{
/* This is a bit screwy.  To set thread id at bottom of this script - i.e. no groups - I used a temp id and insert.  Here is I just assign a random number to the thread and don't worry about temp_id. It's too hard to assign the same thread_id to each message in the loop.  The theory is that there will be fewer messages sent to large groups, so less of a chance of the thread_ids getting mixed up */
$rand2 = rand();
$send_msg = mysql_query("INSERT INTO `cm_messages` (`id`,`thread_id`,`to`,`from`,`subject`,`body`,`assoc_case`) VALUES (NULL,'$rand2','$result[username]','$_POST[from]','$_POST[subject]','$body_mod','$_POST[assoc_case]');");


$get_data = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$result[username]' LIMIT 1");
while ($line = mysql_fetch_array($get_data, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_data,$i);
        $d[$field] = $col_value;
        $i++;

    }

$get_sender_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$_POST[from]' LIMIT 1");
$x = mysql_fetch_array($get_sender_name);
$sender_name = "$x[first_name] $x[last_name]";



$sms_message = "Preview:  " . substr($body_mod,0,50) . "...";
$email_message = "You have a new message re: $_POST[subject] from $sender_name on the ClinicCases system." . "\r\n" . "Please log on to http://" . $_SERVER[HTTP_HOST] . "to view or http://" . $_SERVER[HTTP_HOST] . "cliniccases/mobile from a mobile browser." ;
$subject = "ClinicCases Message from $sender_name";
$sms_to = "$d[mobile_phone]@teleflip.com";
$email_to = "$d[email]";
$headers = 'From: no-reply@' . $_SERVER[HTTP_HOST] . "\r\n" .
   'Reply-To: no-reply@' . $_SERVER[HTTP_HOST] . "\r\n" .
   'X-Mailer: PHP/' . phpversion();
mail($email_to,$subject,$email_message,$headers);

if ($_POST[sms] == 'on')
{
mail($sms_to,$subject,$sms_message,$headers);
}

}
}
}

/* This is if no group is selected */
else
{
$tos = array($_POST[to],$_POST[cc1]);
foreach ($tos as $v)
{
/* Next line deals with situation where there is no cc */
if (!empty($v))

{$send_msg = mysql_query("INSERT INTO `cm_messages` (`id`,`thread_id`,`to`,`from`,`subject`,`body`,`assoc_case`,`temp_id`) VALUES (NULL,'$_POST[thread_id]','$v','$_POST[from]','$_POST[subject]','$body_mod','$_POST[assoc_case]','$rand')");

if (empty($_POST[thread_id]))
{
$set_thread = mysql_query("UPDATE `cm_messages` SET `thread_id` = cm_messages.id WHERE `temp_id` = '$rand' LIMIT 1");
$flush_temp_id = mysql_query("UPDATE `cm_messages` SET `temp_id` = '' WHERE `temp_id` = '$rand' LIMIT 1");


}


$get_data = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$v' LIMIT 1");
while ($line = mysql_fetch_array($get_data, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_data,$i);
        $d[$field] = $col_value;
        $i++;

    }

$get_sender_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$_POST[from]' LIMIT 1");
$x = mysql_fetch_array($get_sender_name);
$sender_name = "$x[first_name] $x[last_name]";



$sms_message = "Preview:  " . substr($body_mod,0,50) . "...";
$email_message = "You have a new message re: $_POST[subject] from $sender_name on the ClinicCases system." . "\r\n" . $sms_message . "\r\n" . "Please log on to http://" . $_SERVER[HTTP_HOST] . "to view or http://" . $_SERVER[HTTP_HOST] . "cliniccases/mobile from a mobile browser." ;
$subject = "ClinicCases Message from $sender_name";

$email_to = "$d[email]";
$headers = 'From: no-reply@' . $_SERVER[HTTP_HOST] . "\r\n" .
   'Reply-To: no-reply@' . $_SERVER[HTTP_HOST] . "\r\n" .
   'X-Mailer: PHP/' . phpversion();

mail($email_to,$subject,$email_message,$headers);
if ($_POST[sms] == 'on')
{
	
/* Because teleflip no longer exists, we need to put in an array of likely US mail- sms domains and run a loop through all of them; will produce 6 SMSs, only one of which will hit, but that's the only solution I can think of */
$domains[] = "$d[mobile_phone]@txt.att.net,$d[mobile_phone]@message.alltel.com,$d[mobile_phone]@tmomail.net,$d[mobile_phone]@vtext.com,$d[mobile_phone]@messaging.nextel.com,$d[mobile_phone]@messaging.sprintpcs.com";

foreach ($domains as $sms_to)
{

mail($sms_to,$subject,$sms_message,$headers);
}

}
}
}
}

if ($_POST[redirection]=='case')
{
header("Location:message_roll.php?notify=1&case_id=$_POST[assoc_case]");
}
elseif ($_POST[redirection]=='student')
{
echo <<<NOTIFY
<img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('msg_notify');"><span style="color:red;font-weight:bold;">Message Sent.</span>
NOTIFY;
}
else
{
header('Location:message_roll.php?notify=1');
}
}
?>
