<?php
session_start();
if (!$_SESSION)
{header('Location: index.php');die;}
include 'db.php';

function generatePassword ($length = 8)
{

  // start with a blank password
  $password = "";

  // define possible characters
  $possible = "0123456789bcdfghjkmnpqrstvwxyz";

  // set up a counter
  $i = 0;

  // add random characters to $password until $length is reached
  while ($i < $length) {

    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) {
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;

}






if ($_POST)
{

$first_name = ucfirst($_POST[first_name]);
$last_name = ucfirst($_POST[last_name]);
/* This strips - . ( ) from phone number */
$num = $_POST[mobile_phone];
$forbidden = array(" " , "(" ,")" ,"-", ".");
$mobile_phone = str_replace($forbidden,'',$num);

$num2 = $_POST[home_phone];
$forbidden2 = array(" " , "(" ,")" ,"-", ".");
$home_phone = str_replace($forbidden2,'',$num2);


$num3 = $_POST[office_phone];
$forbidden3 = array(" " , "(" ,")" ,"-", ".");
$office_phone = str_replace($forbidden3,'',$num3);


/* Format username */
$username_first_part = substr($first_name,0,2);
$username = strtolower($username_first_part) . strtolower($last_name);

$password2 = generatePassword();
$password3 = md5($password2);
$temp_id = $_POST[temp_id];

//Correct timezone
$timezone_offset = abs(date(Z) / 3600) - $_POST[timezone];


$query = mysql_query("INSERT INTO `cm_users` (`id`,`first_name`,`last_name`,`email`,`mobile_phone`,`home_phone`,`office_phone`,`class`,`assigned_prof`,`username`,`password`,`timezone_offset`,`status`) VALUES (NULL,'$first_name','$last_name','$_POST[email]','$mobile_phone','$home_phone','$_POST[office_phone]','$_POST[class]','$_POST[assigned_prof]','$username','$password3','$timezone_offset','$_POST[status]')");

if (mysql_error($connection))
{
$username_mod = $username . rand(1,3);
$query = mysql_query("INSERT INTO `cm_users` (`id`,`first_name`,`last_name`,`email`,`mobile_phone`,`home_phone`,`office_phone`,`class`,`assigned_prof`,`username`,`password`,`timezone_offset`,`status`) VALUES (NULL,'$first_name','$last_name','$_POST[email]','$mobile_phone','$home_phone','$_POST[office_phone]','$_POST[class]','$_POST[assigned_prof]','$username_mod','$password3','$_POST[timezone]','$_POST[status]')");

}

$message = "You ClinicCases account has been activated.  Your username is $username and your temporary password is $password2.  Please make sure to change your password after you log in by clicking the Prefs Tab.";
$subject = "ClinicCases: Your Account is Activated";
$to = $_POST[email];
$headers = "From: " . $CC_default_email . "\r\n" .
   "Reply-To: " . $CC_default_email . "\r\n" .
   "X-Mailer: PHP/" . phpversion();
mail($to,$subject,$message,$headers);

/* This moves the picture from images_tmp to people and assigns the correct id.  Note that the temporary password is used as the unique identifier, so no md5 password here.  Md5 hash can be done when the user changes the password */



$fix_pic = mysql_query("SELECT `id` FROM `cm_users` WHERE `password` = '$password3' LIMIT 1");
$res = mysql_fetch_array($fix_pic);
$target = $res[id];

/* This checks if the user has uploaded a picture; if not, then the default icon is used */


$old_pic = "images_tmp/" . $temp_id . ".jpg";

if (file_exists($old_pic))
{
$new_pic = "people/". $target . ".jpg";
copy($old_pic,$new_pic);
unlink($old_pic);
}
else
{$new_pic = "people/no_picture.png";}
if ($res[picture_url])
{$target2 = $res[picture_url];}
else
{$target2 = $new_pic;}
/* Now, update image location in the db */
$update_loc = mysql_query("UPDATE `cm_users` SET `picture_url` = '$target2' WHERE `id` = '$target' LIMIT 1");

echo <<<RESP
<P>$_POST[first_name] $_POST[last_name] has been added a system. An email has been sent to $_POST[first_name] with a username and temporary password.</p><p><a href="#" onClick="createTargets('window1','window1');sendDataGet('new_user.php');return false;">Add Another User</a> | <a href="#" onClick="location.href='cm_admin_users.php';">Close Window</a></p>
RESP;
}
