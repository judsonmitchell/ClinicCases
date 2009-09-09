<?PHP
ECHO <<<RETRY
<p>Please provide your email address and your information will be sent to you.</p>
<input type = "text" id = "email" name="email" style="color:black;"><br>
<a href="#" onClick = "createTargets('forgot','forgot');sendDataGet('forgot_password.php?email=' + document.getElementById('email').value);return false;"><img src="images/check_yellow.png" border="0" style="margin-top:15px;"></a>

RETRY;
?>
