<?php
include 'db.php';
$login_error = $_GET['login_error'];
if ($login_error == "1")
{
$toggle_display = "block";
}
else
{$toggle_display = "none";}

if ($login_error == "2")
{
$toggle_display2 = "block";
}
else
{$toggle_display2 = "none";}

if ($login_error == "3")
{
$toggle_display3 = "block";
}
else
{$toggle_display3 = "none";}


?>

<html>
<head>
<title>ClinicCases - Online Case Management Software for Law School Clinics</title>

<link rel="stylesheet" href="cm.css" type="text/css">
<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<SCRIPT TYPE="text/javascript">
<!--
function submitenter(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
   myfield.form.submit();
   return false;
   }
else
   return true;
}
//-->
</SCRIPT>
</head>
<body>
<div id="content" style="margin-top:25px;">
<!-- Left Side -->


<div id="wrapper1">
<img src="images/logo.png" id="logo" style="margin-top:9%">


<h4>Case Management Software</h4>
<p><?php echo $CC_program_name; ?></p>
<center>
<div id="status" style="color:red;display:<?PHP ECHO $toggle_display; ?>">Your username or password is incorrect.  Please try again.</div>

<div id="status2" style="color:red;display:<?PHP ECHO $toggle_display2; ?>">Your account is currently inactive.  Please contact <a href="mailto:<?php echo $CC_admin_email; ?>">your clinic's adminstrator</a> for more information.</div>

<div id="status3" style="color:red;display:<?PHP ECHO $toggle_display3; ?>">Your session has expired.  Please log in again.</div>
</center>
<form name = "getin" id="getin" action="login.php" method="post" style="margin-top:5%">

<label for "username">Username</label><br><input type="text" id = "username" name="username" style="color:black;" value = "<?php if (isset($_COOKIE['cc_user'])){$cookie_value = $_COOKIE['cc_user'];echo $cookie_value;} ?>"><br>
<label for "password">Password</label><br><input type="password" id = "password" name="password" style="color:black;"   onKeyPress="return submitenter(this,event)"><br>

<a href="#" onClick = "document.getin.submit();return false;"><img src="./images/check_yellow.png" border="0" style="margin-top:15px;"></a><br>
<label for "remember">Remember My Username</label><input type="checkbox" name="remember"  style="margin-top:8px;color:red;"><br>
</form>
<div style="margin-top:30px"><a href="#" onClick="$('forgot').show();return false">Forgot username or password?</a></div>


<div id="forgot" style="background-color:rgb(255, 255, 204);margin-top:15px;width:99%;height:18%;display:none;">
<p>Please provide your email address and your information will be sent to you.</p>
<input type = "text" id = "email" name="email" style="color:black;"><br>
<a href="#" onClick = "createTargets('forgot','forgot');sendDataGet('forgot_password.php?email=' + document.getElementById('email').value);return false;"><img src="images/check_yellow.png" border="0" style="margin-top:15px;"></a>

</div>
<div style="margin-top:30px"><a href="./newaccount">Students: New Account</a></div>

</div>
</div>






<?php include 'footer.php'; ?>


</body>
</html>
