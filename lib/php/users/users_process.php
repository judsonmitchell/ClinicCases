<?php
//script to add, update and change status of users
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../auth/pbkdf2.php');
require('../utilities/names.php');
require('../users/user_data.php');

function bindPostVals($query_string)
{
	$cols = '';
	$values = array();
	$supervisor_string = null;
	foreach ($query_string as $key => $value) {
		if ($key !== 'action')
		{
			$key_name = ":" . $key;
			$cols .= "`$key` = " . "$key_name,";
			$values[$key_name] = $value;
		}

	}

	$columns = rtrim($cols,',');

	return array('columns'=>$columns,'values' => $values);
}

//Get variables

$action = $_POST['action'];

if (isset($_POST['users']))
{
	$users = $_POST['users'];
}

switch ($action) {
	case 'activate':

		$q = $dbh->prepare("UPDATE cm_users SET status = 'active', new = '' WHERE id = :id");

		foreach ($users as $user) {

			$data = array('id' => $user);
			$q->execute($data);

			//Notify new user
			$email = user_email_from_id($dbh,$user);
			$subject = "ClinicCases: Your ClinicCases account is now activated.";
			$body = "You new ClinicCases account has been activated.  Your username is " . 
            userid_to_username($dbh,$user)   . ".\n\nPlease log on to ClinicCases at ". CC_BASE_URL;

			mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);
		}

		$error = $q->errorInfo();

		break;

	case 'deactivate':

		$q = $dbh->prepare("UPDATE cm_users SET status = 'inactive' WHERE id = :id");

		foreach ($users as $user) {

			$data = array('id' => $user);
			$q->execute($data);
		}

		$error = $q->errorInfo();

		break;

	case 'delete':

		$q = $dbh->prepare("DELETE FROM cm_users WHERE id = ?");
		$q->bindParam(1, $users);
		$q->execute();
		$error = $q->errorInfo();
		break;

	case 'update':

		$post = bindPostVals($_POST);
		$q = $dbh->prepare("UPDATE cm_users SET " . $post['columns'] . " WHERE id = :id");
		$q->execute($post['values']);
		$error = $q->errorInfo();

		//see if new was set to yes; if so send email.
		if ($_POST['new'] === 'yes' || $_POST['status'] === 'active')
		{
			//Notify new user
			$email = $_POST['email'];
			$subject = "ClinicCases: Your ClinicCases account is now activated.";
			$body = "You new ClinicCases account has been activated.  Your username is " .
            userid_to_username($dbh,$_POST['id'])   . ".\n\nPlease log on to ClinicCases at ". CC_BASE_URL;
			mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);

			//Set to not new
			$q = $dbh->prepare("UPDATE cm_users SET new = '' WHERE id = ?");
			$q->bindParam(1,$_POST['id']);
			$q->execute();

		}

		break;

	case 'create':

		$post = bindPostVals($_POST);
		$q = $dbh->prepare("UPDATE cm_users SET " . $post['columns'] . " WHERE id = :id");
		$q->execute($post['values']);
		$error = $q->errorInfo();

		if (!$error[1])
		{
			//Create username
			$fname = trim(str_replace(' ', '', $_POST['first_name']));
			$lname = trim(str_replace(' ', '', $_POST['last_name']));
			$concat_name = substr($fname, 0,1) . $lname;
			$proposed_username =  preg_replace("/[^a-zA-Z0-9]/", "", $concat_name);

			function check_uniqueness($dbh,$proposed_username)
			{
				$q = $dbh->prepare("SELECT username FROM cm_users WHERE username = '$proposed_username'");

				$q->execute();

				if ($q->rowCount() > 0)
					{return true;}
				else
					{return false;}

			}

			//Loop until we get a unique username
			while (check_uniqueness($dbh,$proposed_username))
			{
				if (is_numeric(substr($proposed_username, -1)))
				//we have already tried to make username unique by adding a number
				{
					$digit = substr($proposed_username, -1) + 1;
					$proposed_username = substr($proposed_username, 0,-1) . $digit;
				}
				else
				{$proposed_username = $proposed_username . "1";}
			}

			$new_username = strtolower($proposed_username);

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

			//Update database with this info
			$q = $dbh->prepare("UPDATE cm_users SET username = :user,password = :pass,force_new_password ='1' WHERE id = :id");
			$data = array('user' => $new_username,'pass' => $pass,'id' =>$_POST['id']);
			$q->execute($data);

			//Notify new user
			$email = $_POST['email'];
			$subject = "ClinicCases: Your new account has been created";
			$body = "You new ClinicCases account has been created. Your username is $new_username. " .
            "Your temporary password is $gen_pass .  Please log on to ClinicCases at ". CC_BASE_URL .
            " . You will then be prompted to update your password.";

			mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);

		}
}

if($error[1])

		{
			$return = array('message' => 'Sorry, there was an error. Please try again.','error' => true);
			echo json_encode($return);
		}

		else
		{
			switch ($action) {
				case 'activate':
					$return = array('message'=>'Users activated and notified by email.');
					echo json_encode($return);
					break;

				case 'deactivate':
					$return = array('message'=>'Users deactivated');
					echo json_encode($return);
					break;

				case 'delete':
					$return = array('message'=>'User deleted.');
					echo json_encode($return);
					break;

				case 'update':
                   if ($_POST['new'] === 'yes' && $_POST['status'] === 'active')
						{$return = array('message' => 'User activated and notified by email.');}
					else
						{$return = array('message'=>'User edited.');}
					echo json_encode($return);
					break;

				case 'create':
					$return = array('message'=>'New account created.  The user has been notified by email');
					echo json_encode($return);
					break;

			}
		}

