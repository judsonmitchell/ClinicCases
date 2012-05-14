<?php
//
//Functions to return bits of user data
//

//Return user email
function user_email($dbh,$user)
{
	$q = $dbh->prepare("SELECT username, email FROM cm_users WHERE username = ?");

	$q->bindParam(1, $user);

	$q->execute();

	$data = $q->fetch(PDO::FETCH_ASSOC);

	return $data['email'];

}