<script type="text/javascript" src="html/js/NewPass.js"></script>

</head>

<body>

	<div id="notifications"></div>

	<?php include 'html/templates/interior/timer.php' ?>

	<?php include 'html/templates/interior/idletimeout.php' ?>

	<div id = "nav_container">

		<?php $t = tabs($dbh,$_GET['i']); echo $t; ?>

		<div id="menus">

			<?php include 'html/templates/Menus.php'; ?>

		</div>

	</div>

	<div id = "content">

		<div class = "force_new_password_content">

			<h3>Welcome to ClinicCases 7!</h3>

			<br />

			<p>Before you proceed, you must update your password.</p>

			<br  />

			<p>Your new password should be at least 8 characters long and contain both upper and lower case letters and at least one number.</p>

			<br />

			<form id="force_password_change">

				<p>
					<label>Enter your new password</label>
					<input type="password" name="new_pass">
				</p>

				<p>
					<label>Please enter again</label>
					<input type="password" name="new_pass_check">
				</p>

				<br />

				<p><button>Go</button></p>

			</form>

		</div>

	</div>
