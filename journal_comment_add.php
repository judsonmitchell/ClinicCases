<?php
session_start();
if (!$_SESSION)
{die("Error:You are not logged in");}
/* REMEMBER addslashes(); */

include 'db.php';
include './classes/format_dates_and_times.class.php';
$comment_fix = addslashes($_POST[comment_text]);
$comment_fix2 = nl2br($comment_fix);
$add_comment = mysql_query("UPDATE `cm_journals` SET `commented` = 'yes',`comments` = '$comment_fix2' WHERE `id` = '$_POST[id]' LIMIT 1");


$get_stud = mysql_query("SELECT * FROM `cm_journals` WHERE `id` = '$_POST[id]'");
$r = mysql_fetch_array($get_stud);
$get_email = mysql_query("SELECT `email` FROM `cm_users` WHERE `username` = '$r[username]' LIMIT 1");
$x = mysql_fetch_array($get_email);
$to = $x[email];
$subject = "Journal Comment - ClinicCases.com";
list($date_correct) = formatDateAsVarHuman($r[date_added]);

$headers = 'From: ' . $CC_default_email . "\n" .
   'Reply-To: ' . $CC_default_email . "\n" .
   'X-Mailer: PHP/' . phpversion();
$body = "Your professor has commented on your journal entry submitted $date_correct.\n\nComment:\n\n$_POST[comment_text]\n\n The full text of your journal and the comment is available on ClinicCases";
mail($to,$subject,$body,$headers);
$comment_no_slash = stripslashes($r[comments]);
ECHO "$r[text]<br><p style=\"color:red\">Your Comment: <i>$comment_no_slash</i></p>";








?>
