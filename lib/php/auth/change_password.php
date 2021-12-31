<?php
//script to change user's password
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('pbkdf2.php');

$user = $_SESSION['login'];
$salt = CC_SALT;
$hash = pbkdf2($_POST['pass'], $salt, 1000, 32);
$pass = base64_encode($hash);

if (isset($_POST['upgrade']))
{$upgrade = true;}
else
{$upgrade = false;}

$q = $dbh->prepare("UPDATE cm_users SET password = :pass WHERE username = :user");

$data = array('pass' => $pass, 'user' => $user);

$q->execute($data);

$error = $q->errorInfo();

if (!$error[1])
{
	if ($upgrade === true)
	{
		$q = $dbh->prepare("UPDATE cm_users SET force_new_password = '0' WHERE username = ?");

		$q->bindParam(1, $user);

		$q->execute();
	}
}

if ($error[1])
{
	$result = array('error' => true,'message' => 'Sorry, there was an error.');

	echo json_encode($result);
}
else
{
	$result = array('error' => false,'message' => 'Your password has been changed.');

	echo json_encode($result);
}