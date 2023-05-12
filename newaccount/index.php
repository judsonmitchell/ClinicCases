<?php
include '../db.php';
include '../lib/php/html/gen_select.php';
?>
<!DOCTYPE html>

<head>
	<title>ClinicCases - Apply for an Account</title>
	<meta name="robots" content="noindex">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://use.typekit.net/pyd8ztf.css">
	<link rel="shortcut icon" type="image/x-icon" href="html/images/favicon.ico" />
	<link rel="stylesheet" href="../html/css/app.min.css" />
	<link rel="stylesheet" href="../html/css/new-account.min.css" />
	<script src="../lib/axios/axios.bundle.min.js"></script>
	<script src="../lib/javascripts/validations.js" type="text/javascript"></script>
	<script src="../html/js/notifyUser.js" type="text/javascript"></script>
	<script type="module" src="new_account.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />

</head>

<body class="login">

	<div id="notifications"></div>

	<div id="content" class="content_login">

		<h1>Set Up Your ClinicCases Account for <span style="color:<?php echo CC_SCHOOL_COLOR; ?>"><?php echo CC_PROGRAM_NAME; ?></span></h1>
		<p>Submit the form to the right to apply for a ClinicCases account. Your application will be reviewed and approved by your administrator. If you have any questions, please contact your <a href="mailto:<?php echo CC_ADMIN_EMAIL; ?>">Administrator</a>.</p>




		<div id="newAccount" class="new_account_right">

			<p>Fields with an <span style="color:red">asterisk</span> are required.</p>

			<form name="newAccount">

				<div class="form__control">
					<input id="first_name" required type="text" name="first_name" placeholder=" ">
					<label for="first_name">First Name*</label>
				</div>
				<div class="form__control">
					<input id="last_name" required type="text" name="last_name" placeholder=" ">
					<label for="last_name">Last Name*</label>
				</div>
				<div class="form__control">
					<input id="email" required type="email" name="email" placeholder=" ">
					<label for="email">Email*</label>
				</div>

				<div class="form__control">
					<input id="password" minlength="8" required type="password" name="password" placeholder=" ">
					<label for="password">Password (at least 8 characters)*</label>
				</div>
				<div class="form__control">
					<input id="confirmPassword" required type="password" name="confirmPassword" placeholder=" ">
					<label for="confirmPassword">Confirm Password*</label>
				</div>


				<div class="form__control">
					<input id="mobile_phone" required type="text" name="mobile_phone" placeholder=" ">
					<label for="mobile_phone">Mobile Phone*</label>
				</div>
				<div class="form__control">
					<input id="home_phone" type="text" name="home_phone" placeholder=" ">
					<label for="home_phone">Home Phone</label>
				</div>


				<div class="form__control form__control--select">
					<select id="grp" name="grp" required class="new_user_group_slim_select" tabindex="2">
						<?php echo group_select($dbh, 'student'); ?>
					</select>
					<label for="grp">Select your group*</label>
				</div>

				<div class="form__control form__control--select">
					<select class="timezone_offset" name="timezone_offset" tabindex="2">
						<option value="5" selected="selected">U.S. Central</option>
						<option value="4">U.S. Eastern</option>
						<option value="6">U.S. Mountain</option>
						<option value="7">U.S. Pacific</option>

					</select>
					<label for="timezone_offset">Timezone*</label>

				</div>



				<?php if (RECAPTCHA_PUBLIC_KEY !== '%recaptcha_public%' && RECAPTCHA_PUBLIC_KEY !== '(optional)') { //Recaptcha is enabled

					echo ' 
                    <div class="g-recaptcha" data-sitekey="' . RECAPTCHA_PUBLIC_KEY . '"></div>
                    ';
				}
				?>

				<input type="hidden" name="user_initiated" value="true">
				<div class="actions">
					<button id="sbmt" type="button" class="primary-button new_account_submit">Submit</button>
				</div>
			</form>

		</div>


	</div>

</body>

</html>