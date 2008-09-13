<?php
include 'db.php';
$email = $_GET['email'];
$query = mysql_query("SELECT * FROM `cm_users` WHERE  `email` = '$email' LIMIT 1");
while ($r =  mysql_fetch_array($query))
{
$username = $r['username'];
$password = $r['password'];
 
}
if (!mysql_num_rows($query))
{
echo <<<NOTE
Sorry, we have no records that match the email address you provided.<br>
<center><a href="#" onClick="createTargets('forgot','forgot');sendDataGet('forgot_password_retry.php');return false;">Retry</a></center>

NOTE;
die;
}
$temp_pw = rand();
$temp_pw2 = md5($temp_pw);
$update = mysql_query("UPDATE `cm_users` SET `password` = '$temp_pw2' WHERE `username` = '$username' LIMIT 1 ");

//$c = "http://" .  $_SERVER['HTTP_HOST']  .  $_SERVER['REQUEST_URI'];
//$fix = str_replace('aa_test.php','password_reset.php',$c);
$subject = "The information you requested from ClinicCases.com";
$message = "Here is the information you requested from ClinicCases".  "\r\n" . "Your username is $username" .  "\r\n" . "For security purposes, you will have to reset your password.  Your temporary password is $temp_pw.  Once you login, you can go to the Preferences tab to change your password.";
// Please go to the link below to do that." . "\r\n" . "http://" . "$_SERVER['HTTP_HOST']/cm/passwordreset.php?id";
$headers = 'From: no-reply@' . $_SERVER[HTTP_HOST] . "\r\n" .
   'Reply-To: no-reply@' . $_SERVER[HTTP_HOST] . "\r\n" .
   'X-Mailer: PHP/' . phpversion();
mail($email,$subject,$message,$headers);
echo "The information has been emailed to you.";

?>
