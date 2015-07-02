<?php
require('../../../db.php');
require('../auth/pbkdf2.php');
function isValid() 
{
    try {

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret'   => RECAPTCHA_PRIVATE_KEY,
                 'response' => $_POST['g-recaptcha-response'],
                 'remoteip' => $_SERVER['REMOTE_ADDR']);

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data) 
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result)->success;
    }
    catch (Exception $e) {
        return null;
    }
}

if (RECAPTCHA_PUBLIC_KEY !== '%recaptcha_public%' && RECAPTCHA_PUBLIC_KEY !== '(optional)' ) //Recaptcha is enabled
{


	if (!isValid()) {
        // What happens when the CAPTCHA was entered incorrectly
		$response = array("error" => true, "message" => "ReCAPTCHA thinks you're a robot. Please check the box."); 
		echo json_encode($response);
		die;
  	}
 }

 //check for email uniqueness
 $q = $dbh->prepare("SELECT * FROM cm_users WHERE email = ?");

 $q->bindParam(1,$_POST['email']);

 $q->execute();

 $count = $q->rowCount();

 if ($count > 0)
 {
 	$response = array('error' => true,'message' => 'There is already a user on this system with the email address ' . $_POST['email'] . '.  Perhaps you already have an account?');

 	echo json_encode($response);

 	die;
 }

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

//Create private key
function genKey()
{
    $underscores = 2; // Maximum number of underscores allowed in password

    $length = 40; // Length of password

    $p ="";
    for ($i=0;$i<$length;$i++)
    {
        $c = mt_rand(1,7);
        switch ($c)
        {
            case ($c<=2):
                // Add a number
                $p .= mt_rand(0,9);
            break;
            case ($c<=4):
                // Add an uppercase letter
                $p .= chr(mt_rand(65,90));
            break;
            case ($c<=6):
                // Add a lowercase letter
                $p .= chr(mt_rand(97,122));
            break;
            case 7:
                 $len = strlen($p);
                if ($underscores>0&&$len>0&&$len<9&&$p[$len-1]!="_")
                {
                    $p .= "_";
                    $underscores--;
                }
                else
                {
                    $i--;
                    continue;
                }
            break;
        }
    }
    return $p;
}

if (isset($_POST['user_initiated'])) {
    $gen_pass = $_POST['password'];
    $force_new_password = '0';
} else {
    $gen_pass = generatePassword();
    $force_new_password = '1';
}

$private_key = genKey();

$salt = CC_SALT;

$hash = pbkdf2($gen_pass, $salt, 1000, 32);

$pass = base64_encode($hash);

$first_name = ucwords(strtolower($_POST['first_name']));

$last_name = ucwords(strtolower($_POST['last_name']));

$q = $dbh->prepare("INSERT INTO `cm_users` (`id`, `first_name`, `last_name`, `email`, `mobile_phone`, `home_phone`, `grp`, `username`, `password`, `timezone_offset`, `picture_url`,`status`, `new`, `date_created`, `private_key`,`force_new_password`) VALUES (NULL, :first_name, :last_name, :email, :mobile_phone, :home_phone, :grp, :username, :pass, :timezone, 'people/no_picture.png', 'inactive', 'yes', CURRENT_TIMESTAMP, :private_key,:force_new_password);");

$data = array('first_name' => $first_name, 'last_name' => $last_name,
	'email' => $_POST['email'],'mobile_phone' => $_POST['mobile_phone'],
	'home_phone' =>$_POST['home_phone'], 'grp' => $_POST['grp'],
	'username' => $new_username, 'pass' => $pass,'timezone' => $_POST['timezone_offset'],
	'private_key' => $private_key,'force_new_password' => $force_new_password); 

$q->execute($data);

$error = $q->errorInfo();

if ($error[1])
{
	$response = array('error' => true, 'message' => 'Sorry, there was an error.');

	echo json_encode($response);
}
else
{
	//Send email to applicant
	$subject = "ClinicCases " . CC_PROGRAM_NAME . ": Thanks for applying";

	$message = "Your application for ClinicCases has been received.  It will be reviewed by your administrator.  When it is approved, your administrator will send you another email letting you know your account is active.\n\nIn the meantime, feel free to contact your administrator at " . CC_ADMIN_EMAIL . " with any questions.";

	mail($_POST['email'],$subject,$message,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);

  //Send email to admins
  $q = $dbh->prepare("SELECT * FROM cm_groups WHERE `activate_users` = '1'");

  $q->execute();

  $notified = $q->fetchAll(PDO::FETCH_ASSOC);

  $notified_groups = null;

  foreach ($notified as $grp) {
    $notified_groups .= "'" . $grp['group_name'] . "',";
  }

  $notified_groups = rtrim($notified_groups,',');

  $get_emails = $dbh->prepare("SELECT email FROM cm_users WHERE grp IN($notified_groups) AND status = 'active'");

  $get_emails->execute();

  $emails = $get_emails->fetchAll(PDO::FETCH_ASSOC);

  $subject = "ClinicCases " . CC_PROGRAM_NAME . ": $first_name $last_name has applied for an account";

  $message = "$first_name $last_name has applied for an account on ClinicCases.  Please log on, go to the Users tab, and review the application.";

  foreach ($emails as $e) {

      mail($e['email'],$subject,$message,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);
  }

	//notify user
	$response = array("error" => false, "message" => "Account application successful",
		"html" => "<p>Thank you for submitting the application.  An email has been sent to <b>" . $_POST['email'] . "</b> to confirm your application.  If it doesn't arrive in a few minutes, please check your spam folder.  It's also a good idea to add <b>" . CC_EMAIL_FROM . "</b> to your email address book to ensure that you get ClinicCases emails in the future.</p><p> Your application will be reviewed by your administrator.  When it is approved, another email will be sent to let you know.  If you have any questions in the meantime, please contact your <a href='mailto:" . CC_ADMIN_EMAIL . "'>administrator</a>.</p>" );

	echo json_encode($response);
}

