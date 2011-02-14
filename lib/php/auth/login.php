<?php
session_start();
include '../../../db.php';
include 'log_write.php';

//Get post variables

	$username = mysql_real_escape_string($_POST['username']);
	$password_clean = mysql_real_escape_string($_POST['password']);
	$password = md5($password_clean);
	$ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_POST['remember']))
		{$remember = $_POST['remember'];}

//Check user credentials

	$user_query = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$username' AND `password` = '$password' LIMIT 1 ");
	
	$r = mysql_fetch_object($user_query);

//Do error handling

		if (!mysql_num_rows($user_query))
			{		
				$msg = "Your username or password is incorrect. Please try again";
				
				$json = array('login'=>'false','message'=>'' . $msg . '','url'=>'null');

				$response = json_encode($json);

				die($response);
			}

		if ($r->status == "inactive")
			{
				$msg = "Your account is currently inactive.  Please contact <a href='mailto:$CC_admin_email'>your clinic's adminstrator</a> for more information.";
				
				$json = array('login'=>'false','message'=>''. $msg . '','url'=>'null');

				$response = json_encode($json);
				
				die($response);				
			}


//Create Session Variables
	$_SESSION['login'] = $r->username;
	$_SESSION['group'] = $r->group;
	$_SESSION['first_name'] = $r->first_name;
	$_SESSION['last_name'] = $r->last_name;
	$_SESSION['timezone_offset'] = $r->timezone_offset;
	$_SESSION['pref_journal'] = $r->pref_journal;
	$_SESSION['pref_case'] = $r->pref_case;

//Set remember me cookie

	if(isset($_POST['remember'])){
      setcookie("cc_user", $_SESSION['login'], time()+60*60*24*100, "/");
		}
		
//Create a unique session id and then write to the log
	$sess_id = md5(time());
	$_SESSION['cc_session_id'] = $sess_id;
	write_log ($_SESSION['login'],$_SERVER['REMOTE_ADDR'],$sess_id,'in');

//If login is successful, go to home page
	$home_url = $CC_base_url . "index.php?i=Home.php";
	$json = array('login'=>'true','message'=>'Logging you in....','url'=>'' . $home_url . '');
	$response = json_encode($json);
	echo $response;

