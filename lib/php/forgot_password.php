<?php
include '../../db.php';
	
	//Check for the email
	
	$email = mysql_real_escape_string($_POST['email']);

	$query = mysql_query("SELECT * FROM `cm_users` WHERE  `email` = '$email' LIMIT 1");

		if (!mysql_num_rows($query))

			{
				echo <<<NOTE
				Sorry, we have no records that match the email address you provided.<br>
				<b>Retry:</b>		
				<input type = "text" id = "email" name="email" class="error"><br>
				<a id = "f_submit" href="#"><img src="html/images/check_yellow.png" border="0" style="margin-top:15px;"></a>

NOTE;
				die;
			}
			
			else
			
			{
				
				$row = mysql_fetch_object($query);
				$username = $row->username;
				
			}
			
	//Generate a temporary password		
	
	$temp_pw = rand();
	$temp_pw2 = md5($temp_pw);
	$update = mysql_query("UPDATE `cm_users` SET `password` = '$temp_pw2' WHERE `username` = '$username' LIMIT 1 ");


	//Send the email
	
	$subject = "The information you requested from ClinicCases.com";

	$message = "Here is the information you requested from ClinicCases".  "\r\n" . "Your username is $username" .  "\r\n" . "For security purposes, you will have to reset your password.  Your temporary password is $temp_pw.  Once you login, you can go to the Preferences tab to change your password.";

	$headers = 'From: ' . $CC_default_email . "\n" .
   'Reply-To: ' . $CC_default_email . "\n" .
   'X-Mailer: PHP/' . phpversion();

	mail($email,$subject,$message,$headers);
	echo "The information has been emailed to you.";

?>
