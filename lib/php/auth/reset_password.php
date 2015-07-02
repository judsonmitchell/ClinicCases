<?php
//script for admin to reset passwords
session_start();
require('../../../db.php');
require('../auth/session_check.php');

//add permissions here
if (!$_SESSION['permissions']['activate_users'] == '1'){
    $resp = array('error' => true, 'message' => "You do not have permission to do this.");
    die(json_encode($resp));
}

$q = $dbh->prepare("SELECT * FROM cm_users WHERE id = ?");
$id = $_POST['id'];
$q->bindParam(1,$id);
$q->execute();
$count = $q->rowCount();
$check = $q->fetch();

if ($count < 1) {
	$resp = array('error' => true,'message' => 'Error retrieving user.');
	echo json_encode($resp);
} else {
	//Create temp password
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

	  return $password;
	}

	$gen_pass = generatePassword();
	$update = $dbh->prepare("UPDATE cm_users SET password = :pass, force_new_password = '1' WHERE id = :id");
    $pass = md5($gen_pass);
	$data = array('pass' => $pass,'id' => $id);
	$update->execute($data);
	$error = $update->errorInfo();

	if ($error[1]) {
		$resp = array('error' => true,'message' => "Sorry, there was an error resetting the password. ");
		echo json_encode($resp);
	} else {
		//Send email to user
		$user = $check['username'];
		$email = $check['email'];
		$subject = "ClinicCases: Your Password Has Been Reset";
		$body = "Your Administrator has reset your ClinicCases password.  Your username is $user. " .
        "Your temporary password is $gen_pass\n\nPlease log in to ClinicCases using these credentials. ".
        "You will then be prompted to change your password to something you can remember.\n\nIf you did not make this request, please notify your administrator.\n\n" . CC_EMAIL_FOOTER;
		mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);

		$resp = array('error' => false, 'message' => "The user's password has been reset to <b>$gen_pass</b>");

		echo json_encode($resp);
	}
}


