
<!-- Jquery Calls Specific to this page -->
	<script type="text/javascript" src="html/js/Login.js"></script>

</head>

<body  class="login">

<div id="idletimeout">
                You have been logged off due to 60 minutes inactivity. Please log in again.
</div>

<div id="notifications"></div>

<div id="content" class="content_login">

	<div class="wrapper">

        <div><img src="html/images/logo_sm.png"></div>
        <div class="prog_name" style = "color:<?php echo CC_SCHOOL_COLOR; ?>"><?php echo CC_PROGRAM_NAME; ?></div>
		<div class = "login_left">

			<div id="status"></div>

			<form name = "getin" id="getin">

				<p><label for "username">Username</label>
				<input type="text" id = "username" name="username" value = "<?php if (isset($_COOKIE['cc_user'])){$cookie_value = $_COOKIE['cc_user'];echo $cookie_value;} ?>"></p>

				<p><label for "password">Password</label>
				<input type="password" id = "password" name="password"></p>

				<p><label for "remember">Remember Username</label><input type="checkbox" name="remember"  id="remember" value="remember"></p>

				<p style="text-align:center"><button id="login_button">Go</button></p>


			</form>


		</div>

		<div class = "login_right">

			<ul>
				<li><a class="lost_password" href="#">Forgot your username or password?</a></li>
				<li><a href="newaccount/">Need an account?</a></li>
				<li>Help and information available at <a href="http://cliniccases.com">cliniccases.com</a></li>
				<li>Ask questions at the <a href="http://cliniccases.com/forums">ClinicCases forum</a></li>
			</ul>

			<a href="http://www.facebook.com/pages/ClinicCases/130258760379259" target="_new" title="Like ClinicCases on Facebook"><img src="html/images/facebook.png"></a>
            &nbsp;
            <a href="https://github.com/judsonmitchell/ClinicCases" target="_new" title="Fork ClinicCases on Github"><img src="html/images/github.png"></a>
            &nbsp;
            <a href="http://twitter.com/cliniccases" target="_new" title="Follow ClinicCases on Twitter"><img src="html/images/twitter.png"></a>
		</div>

	</div>

</div>

	<?php include 'html/templates/Footer.php'; ?>
