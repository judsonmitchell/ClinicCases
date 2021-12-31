<?php
session_start();
include '../../../db.php';
include 'log_write.php';
include 'pbkdf2.php';

//Check if password needs to be updated
function force_new_password($dbh,$user)
{
	$q = $dbh->prepare("SELECT username,force_new_password FROM cm_users WHERE username = ?");

	$q->bindParam(1,$user);

	$q->execute();

	$nu = $q->fetch(PDO::FETCH_ASSOC);

	if ($nu['force_new_password'] == '1')
		{
			$result = 'yes';
		}
	else
		{
			$result = 'no';
		}

		return $result;
}

//Set variables
$update_password = force_new_password($dbh,$_POST['username']);

if ($update_password === 'yes')
{
	$password = md5($_POST['password']);
}
else
{
	$salt = CC_SALT;
	$hash = pbkdf2($_POST['password'], $salt, 1000, 32);
	$password = base64_encode($hash);
}

	$ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_POST['remember']))
		{$remember = $_POST['remember'];}


	$user_query = $dbh->prepare("SELECT * FROM cm_users WHERE username = ? AND password = ? LIMIT 1");

	$user_query->setFetchMode(PDO::FETCH_OBJ);

	$user_query->bindParam(1, $_POST['username']);

	$user_query->bindParam(2, $password);

	$user_query->execute();

	$r = $user_query->fetch();

//Do error handling

		if ($user_query->rowCount() < 1)
			{
				$msg = "Your username or password is incorrect. Please try again";

				$json = array('login'=>'false','message'=>'' . $msg . '','url'=>'null');

				$response = json_encode($json);

				die($response);
			}

		if ($r->status == "inactive")
			{
				$msg = "Your account is currently inactive.  Please contact <a href='mailto:" . CC_ADMIN_EMAIL   . "'>your clinic's Administrator</a> for more information.";

				$json = array('login'=>'false','message'=>''. $msg . '','url'=>'null');

				$response = json_encode($json);

				die($response);
			}

//Determine the user's group and put the relevant permissions in an array

	$group_query = $dbh->prepare("SELECT * FROM cm_groups WHERE group_name = ? LIMIT 1");

	$group_query->bindParam(1, $r->grp);

	$group_query->execute();

	$group_query->setFetchMode(PDO::FETCH_ASSOC);

	$permissions = $group_query->fetch();

//Create Session Variables
	$_SESSION['permissions'] = $permissions;
	$_SESSION['login'] = $r->username;
	$_SESSION['group'] = $r->grp;
	$_SESSION['first_name'] = $r->first_name;
	$_SESSION['last_name'] = $r->last_name;
	$_SESSION['email'] = $r->email;
	$_SESSION['timezone_offset'] = $r->timezone_offset;
	$_SESSION['picture_url'] = $r->picture_url;
	$_SESSION['private_key'] = $r->private_key;



//Set remember me cookie

	if(isset($_POST['remember'])){
      setcookie("cc_user", $_SESSION['login'], time()+60*60*24*100, "/");
		}

	else
		//just set a session cookie with username to be used by timer
		{setcookie("cc_user", $_SESSION['login'],"0", "/");}

//Create a unique session id and then write to the log
	$sess_id = md5(time());
	$_SESSION['cc_session_id'] = $sess_id;
	write_log ($dbh,$_SESSION['login'],$_SERVER['REMOTE_ADDR'],$sess_id,'in');

	if ($update_password === 'yes')
		{
			//direct user to new password page
			$target_url = CC_BASE_URL . "index.php?i=New_Pass.php";
		}
		else
		{
		//If login is successful, go to home page
			$target_url = CC_BASE_URL . "index.php?i=Home.php";
		}

		$json = array('login'=>'true','message'=>'Logging you in....','url'=>'' . $target_url . '');
		$response = json_encode($json);
		echo $response;

