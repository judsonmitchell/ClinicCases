<?php
include '../db.php';
include '../classes/url_key_generator.php';


/* This finds out if the selected professor uses case management, journals, or both */
function get_prof_case_prefs($prof)
{
$find_out = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$prof' LIMIT 1");
$res = mysql_fetch_array($find_out);
if ($res[pref_case] == 'on')
{$cases = 'on';}
else
{$cases = '';}
return $cases;
}

function get_prof_journal_prefs($prof)
{
$find_out2 = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$prof' LIMIT 1");
$res2 = mysql_fetch_array($find_out2);
if ($res2[pref_journal] == 'on')
{$journals = 'on';}
else
{$journals = '';}
return $journals;

}

/* Processes form */
if ($_POST)
{
	//do captcha
	require_once ( 'class.captcha_x.php');
	$captcha = &new captcha_x ();
		
		if ( ! $captcha->validate ( $_POST[captcha])) {
			echo '<p><center><br>The text you entered in the security field is incorrect.  Please try again.<b><br><a href="#" onClick="history.back(-1);">Go Back</a></center></p>';die;}

		//make sure email is unique 
		$email=mysql_real_escape_string($_POST[email]);
		$email_query = mysql_query("SELECT `email` from `cm_users` WHERE `email` = '$email'");
		
		if (mysql_num_rows($email_query)>0)
			{echo "<p><center>An account with this email address already exists. If you have lost your username or password, <a href='../index.php'>go the login page</a> and click on 'Forgot username or password'.</center></p>";die;}

//create account

$first_name = ucfirst($_POST[first_name]);
$last_name = ucfirst($_POST[last_name]);
/* This strips - . ( ) from phone number */
$num = $_POST[mobile_phone];
$forbidden = array(" " , "(" ,")" ,"-", ".");
$mobile = str_replace($forbidden,'',$num);
$num2 = $_POST[home_phone];
$forbidden2 = array(" " , "(" ,")" ,"-", ".");
$home_phone = str_replace($forbidden2,'',$num2);

/* Format username */
$username_first_part = substr($first_name,0,2);
$username = strtolower($username_first_part) . strtolower($last_name);

$case_pref = get_prof_case_prefs($_POST[assigned_prof]);
$journal_pref = get_prof_journal_prefs($_POST[assigned_prof]);

$secure_password_clean = mysql_real_escape_string($_POST[password]);
$secure_password = md5($secure_password_clean);
$first_name=mysql_real_escape_string($first_name);
$last_name=mysql_real_escape_string($last_name);
$email=mysql_real_escape_string($_POST[email]);
$mobile=mysql_real_escape_string($mobile);
$home_phone=mysql_real_escape_string($home_phone);
$username=mysql_real_escape_string($username);
$assigned_prof=mysql_real_escape_string($_POST[assigned_prof]);
$timezone=mysql_real_escape_string($_POST[timezone]);

$timezone_offset = abs(date(Z) / 3600) - $timezone;
//Create private key
$key = alphanumericPass();




$query = mysql_query("INSERT INTO `cm_users` (`id`,`first_name`,`last_name`,`email`,`mobile_phone`,`home_phone`,`class`,`new`,`username`,`password`,`assigned_prof`,`timezone_offset`,`pref_case`,`pref_journal`,`private_key`) VALUES (NULL,'$first_name','$last_name','$email','$mobile','$home_phone','student','yes','$username','$secure_password','$assigned_prof','$timezone_offset','$case_pref','$journal_pref','$key')");

if (mysql_error($connection))
{

$username_mod = $username . rand(1,5);
$query = mysql_query("INSERT INTO `cm_users` (`id`,`first_name`,`last_name`,`email`,`mobile_phone`,`home_phone`,`class`,`new`,`username`,`password`,`assigned_prof`,`timezone_offset`,`pref_case`,`pref_journal`) VALUES (NULL,'$first_name','$last_name','$_POST[email]','$mobile','$home_phone','student','yes','$username_mod','$secure_password','$_POST[assigned_prof]','$_POST[timezone]','$case_pref','$journal_pref')");

}

$alert_admin = mysql_query("SELECT `email` FROM `cm_users` WHERE `class` = 'admin'");
while ($r = mysql_fetch_array($alert_admin))
	{
		//advise the adminstrator that there is a new signup
		$message = "A new user, $first_name $last_name, has applied for access to ClinicCases.  Please review the application and activate the user.";
		$subject = "New ClinicCases User";
		$to = $r['email'];
		$headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n" .
		   "Reply-To: no-reply@" . $_SERVER[HTTP_HOST] . "\r\n" .
		   "X-Mailer: PHP/" . phpversion();
		mail($to,$subject,$message,$headers);

		//tell the student happens next 
		$message2 = "Your request for a ClinicCases account has been submitted.  Your request for an account must first be approved by your clinic's administrator.  Once your request is approved, you will receive an email advising you of your username and password.  If you do not receive this message soon, please check the spam folder in your email to see if the message is there and then 2) contact your clinic's adminstrator at $CC_admin_email.";
		$subject2 = "ClinicCases: Your Account Request";
		$to2 = $email;
		mail($to2,$subject2,$message2,$headers);
		  
	}

echo <<<RESP

<html>
<head>
<title>Set Up Your Account - ClinicCases</title>
<link rel="stylesheet" href="cm.css" type="text/css">
</head>
<body>
<div id="content" style="margin-top:25px;">
<div id="left" style="float:left;width:42%;margin-top:15px;margin-left:15px;">

<h1>Set Up Your <br>ClinicCases Account</h1>
<br>

</div>
<div id="right" style="float:right;width:55%;border-left: 1px dashed gray;height:100%">
<p style="margin-top:40px;font-weight:bold;padding:10px;">
Thanks for applying, $first_name.  Your information will be forwarded to the adminstrator who will then activate your account.  You should receive an email shortly confirming your request.  If you do not, please check your spam folder.</p>
</DIV>
</DIV>
</BODY>
</HTML>
RESP;
DIE;
}

?>
<html>
<head>
<title>Set Up Your Account - ClinicCases</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<script src="../javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="../scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="..scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<style type="text/css">
<!--
 input {width:250px;font-weight:bold;}
 td {padding-bottom:15px;text-align:right;}
//-->
</style>
<script src="../javascripts/validations.js" type="text/javascript"></script>

</head>
<body>
<div id="content" style="min-height:600px;margin-top:1.5%;">
<div id="left" style="float:left;width:42%;margin-top:40px;margin-left:15px;">

<h1>Set Up Your <br>ClinicCases Account</h1>
<br>
<p style="text-align:left;">Please apply for an account only after you have been asked to do so by your clinic instructor. If you have any questions, please contact your <a href="mailto:<?php echo $CC_admin_email; ?>">Law Clinic's adminstrator</a>.</p>
</div>
<div id="right" style="float:right;width:55%;height:94%;border-left: 1px dashed gray;padding-top:1.5%;">
<center>
<form name="newAccount" id="newAccount" action="index.php" method="post" onSubmit="return valAcct();">
<table width="35%">
<tr><td><label for "first_name">First Name <span style="color:red;font-size:10pt;">*</span></label></td><td><input type="text" id="first_name" name="first_name"></td></tr>
<tr><td><label for "last_name">Last Name <span style="color:red;font-size:10pt;">*</span></label></td><td><input type="text" id="last_name" name="last_name"></td></tr>

<tr><td><label for "email">Email <span style="color:red;font-size:10pt;">*</span></label></td><td><input type="text" id="email" name="email" onBlur="validateEmail(this);"></td>
<tr><td><label for "confirm">Type again to confirm <span style="color:red;font-size:10pt;">*</span></label></td><td><input type="text" id="confirm" onBlur = "var target1 = document.getElementById('email'); var target2 = document.getElementById('confirm'); if ( target1.value != target2.value){alert('The email addresses you entered do not match.  Please re-enter.');target1.value = '';target2.value=''; }"></td></tr>


<tr><td><label for "email">Password (at least 6 characters) <span style="color:red;font-size:10pt;">*</span></label></td><td><input type="password" id="password" name="password" onBlur="if ($('password').value.length < 6){alert('Your password must be at least six characters in length');$('password').value='';}"></td>
<tr><td><label for "confirm">Type again to confirm <span style="color:red;font-size:10pt;">*</span></label></td><td><input type="password" id="confirm_password" onBlur = "var target1 = document.getElementById('password'); var target2 = document.getElementById('confirm_password'); if ( target1.value != target2.value){alert('The passwords you entered do not match.  Please re-enter.');target1.value = '';target2.value=''; }"></td></tr>

<tr><td><label for "prof">Your Professor <span style="color:red;font-size:10pt;">*</span></label></td><td style="text-align:left;">
<select name="assigned_prof" id="prof">
<option value = '' selected="selected">Please Select</option>
<?php
$get_prof = mysql_query("SELECT * FROM `cm_users` WHERE `class` = 'prof' AND `status` = 'active' ORDER BY `last_name` asc");
while ($result = mysql_fetch_array($get_prof))
{
$prof = $result['last_name'];
$prof_user = $result['username'];
echo "<option value='$prof_user'>$prof</option>";

}



?>

</select></td></tr>

<tr><td><label for "mobile">Mobile Phone Number <span style="color:red;font-size:10pt;">*</span><br>(include area code)</label></td><td><input type="text" id="mobile_phone" name="mobile_phone" onBlur="return validatePhone(this);"></td></tr>
<tr><td><label for "home">Home Number<br>(include area code)</label></td><td><input type="text" id="home" name="home_phone"></td></td></tr>
<tr><td><label for "timezone">Your Time Zone <span style="color:red;font-size:10pt;">*</span></label></td><td style="text-align:left;">
<select id="timezone" name="timezone">
<option value = "5" selected = "selected">U.S. Central</option>
<option value = "4">U.S. Eastern</option>
<option value = "6">U.S. Mountain</option>
<option value = "7">U.S. Pacific</option>
</select>
</td></tr>
<tr><td>
<img src="server.php" onclick="javasript:this.src='server.php?'+Math.random();" ><br><span style="font-size:10pt;color:red">Can't read? Click on image.</span></td>
<td><input name="captcha" id="captcha" type="text" value="Enter Text in Graphic" onFocus="this.value='';"/></td></tr>
<tr><td></td><td><input name="submit" type="submit" value="Submit" style="margin-left:10px;width:80px;"></td></tr>
</table>

</form>
<span style="color:red;font-size:10pt;">* Required Field</span>
</center>
</div>
</div>

</script>
</body>
</html>
