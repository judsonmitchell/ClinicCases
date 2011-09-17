<?php
session_start();
include '../../../db.php';
include 'log_write.php';

//Set variables

	$password = md5($_POST['password']);
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
				$msg = "Your account is currently inactive.  Please contact <a href='mailto:" . CC_ADMIN_EMAIL   . "'>your clinic's adminstrator</a> for more information.";
				
				$json = array('login'=>'false','message'=>''. $msg . '','url'=>'null');

				$response = json_encode($json);
				
				die($response);				
			}

//Determine the user's group and put the relevant permissions in an array

	$group_query = $dbh->prepare("SELECT * FROM cm_groups WHERE group_name = ? LIMIT 1");

	$group_query->bindParam(1, $r->group);
	
	$group_query->execute();
	
	$group_query->setFetchMode(PDO::FETCH_ASSOC);  

	$permissions = $group_query->fetch();
		
//Create Session Variables
	$_SESSION['permissions'] = $permissions;
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
	write_log ($dbh,$_SESSION['login'],$_SERVER['REMOTE_ADDR'],$sess_id,'in');

//If login is successful, go to home page
	$home_url = CC_BASE_URL . "index.php?i=Home.php";
	$json = array('login'=>'true','message'=>'Logging you in....','url'=>'' . $home_url . '');
	$response = json_encode($json);
	echo $response;

