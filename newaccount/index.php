<?php
include '../db.php';
include '../lib/php/html/gen_select.php';
?>
<!DOCTYPE html>

<head>
	<title>ClinicCases - Apply for an Account</title>
	<meta name="robots" content="noindex">
	<link rel="stylesheet" href="../html/css/cm.css" type="text/css">
	<link rel="shortcut icon" type="image/x-icon" href="html/images/favicon.ico" />

	<script src="../lib/jqueryui/js/jquery-1.4.4.min.js" type="text/javascript"></script>
	<script src="../lib/jqueryui/js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
	<script src="../lib/javascripts/validations.js" type="text/javascript"></script>

	<script type="text/javascript">
	$(document).ready(function(){

		$('#email').change(function(){alert($(this).val())})



	})

	</script>

</head>

<body class="login">

	<div id="content" class="content_login">

		<div class="wrapper">

			<div class="new_account_left">

				<h1>Set Up Your <br>ClinicCases Account for <?php echo CC_PROGRAM_NAME; ?></h1>
				<p>Submit the form to the right to apply for a ClinicCases account.  Your application will be reviewed and approved by your administrator. If you have any questions, please contact your <a href="mailto:<?php echo CC_ADMIN_EMAIL; ?>">adminstrator</a>.</p>

			</div>

			<div class="new_account_right">

				<p>Fields with an <span style="color:red">asterisk</span> are required.</p>

				<form name="newAccount" id="newAccount" action="index.php" method="post" onSubmit="return valAcct();">

				<p><label for "first_name" class="req">First Name</label>
					<input type="text" name="first_name" id="first_name"></p><br />

				<p><label for "last_name" class="req">Last Name</label>
					<input type="text" name="last_name" id="last_name"></p><br />

				<p><label for "email" class="req">Email</label>
					<input type="text" name="email" id="email"></p><br />

				<p><label for "confirm" class="req">Type again to confirm:</label>
					<input type="text" name="confirm" id="confirm"></p>	<br />

				<p><label for "password" class="req">Password (at least 8 characters)</label>
					<input type="password" name="password" id="password"></p>	<br />

				<p><label for "confirm_password" class="req">Type again to confirm:</label>
					<input type="password" name="confirm_password" id="confirm_password"></p>	<br />

				<p><label for "grp" class="req">Select your group</label>
					<select name = "grp" >

					<?php $v = '*';group_select($dbh,$v); ?>

					</select></p><br />

				<p><label for "mobile" class="req">Mobile Phone(include area code)</label>
					<input type="text" name="mobile" id="mobile"></p><br />

				<p><label for "home_phone">Home Phone(include area code)</label>
					<input type="text" name="home_phone" id="home_phone"></p><br />

				<p><label for "timezone" class="req">Your Time Zone</label>
					<select id="timezone" name="timezone">
					<option value = "5" selected = "selected">U.S. Central</option>
					<option value = "4">U.S. Eastern</option>
					<option value = "6">U.S. Mountain</option>
					<option value = "7">U.S. Pacific</option>
					</select>
					</p><br />

				<p><input type="button" id="sbmt" name="sbmt" value="Submit"></p>

			</div>

		</div>

	</div>

</body>
