<script src="lib/axios/axios.bundle.min.js"></script>
<script type="module" src="html/js/NewPass.js"></script>

</head>

<body>

	<div id="notifications"></div>


	<?php include 'html/templates/interior/idletimeout.php' ?>

	<div class="header">
		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>
	</div>

	<div class="container mx-auto">

		<div class="force_new_password_content">

			<h3>Welcome to ClinicCases 8!</h3>

			<br />

			<p>Before you proceed, you must update your password.</p>

			<br />

			<p>Your new password should be at least 8 characters long and contain both upper and lower case letters and at least one number.</p>

			<br />

			<form id="force_password_change">

				<div class="form__control">
					<input id="new_pass" type="password" name="new_pass" placeholder=" " required>
					<label for="new_pass">Enter your new password</label>
				</div>

				<div class="form__control">
					<input id="new_pass_check" type="password" name="new_pass_check" placeholder=" " required>
					<label for="new_pass_check">Please enter again</label>
				</div>

				<br />

				<button class="button--primary submit_new_password">Go</button>

			</form>
			<p class="indicator" id="min-char">Minimum 8 characters</p>
			<p class="indicator" id="uppercase">At least 1 uppercase character</p>
			<p class="indicator" id="lowercase">At least 1 lowercase character</p>
			<p class="indicator" id="number">At least 1 number</p>
		</div>

	</div>