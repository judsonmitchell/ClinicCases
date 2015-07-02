<?php
//script to for lost password form
require('../../../db.php');
require('pbkdf2.php');

$q = $dbh->prepare("SELECT * FROM cm_users WHERE email = ?");
$email = trim($_POST['email']);
$q->bindParam(1,$email);
$q->execute();

$count = $q->rowCount();
$check = $q->fetch();

if ($count < 1) {
	$resp = array('error' => true,'message' => 'There are no users with that email address on this server.  Please try again');
	echo json_encode($resp);
} else if ($count > 1) {
	$resp = array('error' => true,'message' => 'There are multiple accounts associated with that email address.
    Please ask your administrator to reset your password.');
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
    $pass = md5($gen_pass);
	$update = $dbh->prepare("UPDATE cm_users SET password = :pass, force_new_password = '1' WHERE email = :email");
	$data = array('pass' => $pass,'email' => $email);
	$update->execute($data);
	$error = $update->errorInfo();

	if ($error[1])
	{
		$resp = array('error' => true,'message' => "Sorry, there was an error resetting your password.  Please contact your administrator.");

		echo json_encode($resp);
	}
	else
	{

		//Send email to user
		$user = $check['username'];
		$email = $check['email'];
		$subject = "ClinicCases: Your Account Request";
		$body = "This is in response to your forgot username/password request on ClinicCases.  " .
        "Your username is $user.  Your temporary password is $gen_pass\n\n" .
        "Please log in to ClinicCases using these credentials.  You will then be prompted to change your " .
        "password to something you can remember.\n\nIf you did not make this request, please notify your administrator.";
		mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);

		$resp = array('error' => false, 'message' => "Your username and a new temporary password have been emailed to " . $email . ".  If it does not arrive in a few minutes, please check your spam folder.");

		echo json_encode($resp);
	}
}


