<?php
include '../db.php';
include '../lib/php/html/gen_select.php';
?>
<!DOCTYPE html>

<head>
	<title>ClinicCases - Apply for an Account</title>
	<meta name="robots" content="noindex">
	<link rel="stylesheet" href="../html/css/cm.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="../lib/jqueryui/css/custom-theme/jquery-ui-1.8.9.custom.css" type="text/css" />
	<link rel="shortcut icon" type="image/x-icon" href="html/images/favicon.ico" />
    <link href='https://fonts.googleapis.com/css?family=Crimson+Text' rel='stylesheet' type='text/css'>

	<script src="../lib/jqueryui/js/jquery-1.4.4.min.js" type="text/javascript"></script>
	<script src="../lib/jqueryui/js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
	<script src="../lib/javascripts/validations.js" type="text/javascript"></script>
	<script src="../html/js/notifyUser.js" type="text/javascript"></script>
	<script type="text/javascript" src="new_account.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>


</head>

<body class="login">

	<div id="notifications"></div>

	<div id="content" class="content_login">

		<div class="wrapper">

			<div class="new_account_left">

				<h1>Set Up Your <br>ClinicCases Account for <span style="color:<?php echo CC_SCHOOL_COLOR; ?>"><?php echo CC_PROGRAM_NAME; ?></span></h1>
				<p>Submit the form to the right to apply for a ClinicCases account.  Your application will be reviewed and approved by your administrator. If you have any questions, please contact your <a href="mailto:<?php echo CC_ADMIN_EMAIL; ?>">Administrator</a>.</p>

			</div>

			<div class="new_account_right">

				<p>Fields with an <span style="color:red">asterisk</span> are required.</p>

				<form name="newAccount">

				<p><label for "first_name" class="req">First Name</label>
					<input type="text" name="first_name" id="first_name"></p>

				<p><label for "last_name" class="req">Last Name</label>
					<input type="text" name="last_name" id="last_name"></p>

				<p><label for "email" class="req">Email</label>
					<input type="text" name="email" id="email"></p>

				<p><label for "password" class="req">Password (at least 8 characters)</label>
					<input type="password" name="password" id="password"></p>

				<p><label for "confirm_password" class="req">Type again to confirm:</label>
					<input type="password" name="confirm_password" id="confirm_password"></p>

				<p><label for "grp" class="req">Select your group</label>
					<select name = "grp" >

					<?php echo group_select($dbh,'student'); ?>

					</select></p>

				<p><label for "mobile" class="req">Mobile Phone</label>
					<input type="text" name="mobile_phone" id="mobile"></p>

				<p><label for "home_phone">Home Phone</label>
					<input type="text" name="home_phone" id="home_phone"></p>

				<p><label for "timezone_offset" class="req">Your Time Zone</label>
					<select id="timezone" name="timezone_offset">
					<option value = "5" selected = "selected">U.S. Central</option>
					<option value = "4">U.S. Eastern</option>
					<option value = "6">U.S. Mountain</option>
					<option value = "7">U.S. Pacific</option>
					</select>
					</p>

				<?php if (RECAPTCHA_PUBLIC_KEY !== '%recaptcha_public%' && RECAPTCHA_PUBLIC_KEY !== '(optional)' ) {//Recaptcha is enabled

                    echo ' 
                    <div class="g-recaptcha" data-sitekey="' . RECAPTCHA_PUBLIC_KEY . '"></div>
                    ';
                }
				?>

				<p><input type="button" id="sbmt" name="sbmt" value="Submit"></p>
                <input type="hidden" name="user_initiated" value="true">
				</form>

			</div>

		</div>

	</div>

</body>
</html>
