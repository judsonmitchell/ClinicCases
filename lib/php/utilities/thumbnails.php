<?php
//function to return the correct url for the user thumbnail

function thumbify($url)
	{
			$split = explode('/', $url);
			$thumbnail = $split[0] 	. "/tn_" . $split[1];
			return $thumbnail;
	}

function return_thumbnail($dbh,$username)
	{
		$get_user_id = $dbh->prepare("SELECT id,username FROM cm_users WHERE username = '$username' LIMIT 1");

		$get_user_id->execute();

		$user = $get_user_id->fetch();

		$thumbnail = 'people/tn_' . $user['id'] . '.jpg';

		if (file_exists(CC_PATH . '/' . $thumbnail))
			{return $thumbnail;}
		else
			{return 'people/tn_no_picture.png';}

	}
