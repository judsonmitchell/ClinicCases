<?php
session_start();

include 'db.php';
/* Nb */
$username = mysql_real_escape_string($_POST['username']);
$password_clean = mysql_real_escape_string($_POST['password']);
$password = md5($password_clean);

$ip = $_SERVER['REMOTE_ADDR'];
$remember = $_POST['remember'];

$user_query = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$username' AND `password` = '$password' LIMIT 1 ");
while ($r = mysql_fetch_array($user_query))
{

$username = $r['username'];
$password = $r['password'];
$class = $r['class'];
$assigned_prof = $r['assigned_prof'];
$first_name = $r['first_name'];
$last_name =$r['last_name'];
$timezone_offset = $r['timezone_offset'];
$status = $r['status'];
$pref_journal = $r['pref_journal'];
$pref_case = $r['pref_case'];
}

if (!mysql_num_rows($user_query))
{
header('Location: index.php?login_error=1');die;

}

if ($status == "inactive")
{
header('Location:index.php?login_error=2');die;

}



$_SESSION['login'] = $username;
$_SESSION['class'] = $class;
$_SESSION['assigned_prof'] = $assigned_prof;
$_SESSION['first_name'] = $first_name;
$_SESSION['last_name'] = $last_name;
$_SESSION['timezone_offset'] = $timezone_offset;

$_SESSION['pref_journal'] = $pref_journal;
$_SESSION['pref_case'] = $pref_case;
if(isset($_POST['remember'])){
      setcookie("cc_user", $_SESSION['login'], time()+60*60*24*100, "/");

      }



switch ($class) {
case "prof":
    header('Location: cm_home.php');
    break;
case "student":
    header('Location: cm_home.php');
    break;
case "admin":
    header('Location: cm_admin_home.php');
    break;
}


?>
