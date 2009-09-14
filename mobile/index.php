<?php
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
<title>ClinicCases Mobile</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png"  border="0"></a></p>
<center>
<strong>Please login</strong>.<br>
<div id="status" style="color:red;display:<?PHP ECHO $toggle_display; ?>">Your username or password is incorrect.  Please try again.</div>

<div id="status2" style="color:red;display:<?PHP ECHO $toggle_display2; ?>">Your account is currently inactive.  Please contact your clinic's adminstrator for more information.</div>

<div id="status3" style="color:red;display:<?PHP ECHO $toggle_display3; ?>">Your session has expired.  Please log in again.</div>

<form name = "getin" id="getin" action="login_m.php" method="post" style="margin-top:5px;">
<label for "username">Username</label><br><input type="text" id = "username" name="username" style="color:black;" value = "<?php if (isset($_COOKIE['cc_user'])){$cookie_value = $_COOKIE['cc_user'];echo $cookie_value;} ?>"><br>
<label for "password">Password</label><br><input type="password" id = "password" name="password" style="color:black;"><br>
<label for "remember">Remember My Username</label><input type="checkbox" name="remember"  style="margin-top:8px;color:red;"><br>
<input type="submit" value="Go">
</form>

</body>
</html>
