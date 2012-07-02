
<!-- Jquery Calls Specific to this page -->
	<script type="text/javascript">

		$(document).ready(function(){

			function login_user()
			{
				$.post("lib/php/auth/login.php", $("#getin").serialize(),function(data){

					var response = $.parseJSON(data);
							if (response.login == 'true')
								{$("#status").html(response.message);
								window.location=response.url;
								}

								else

									{$("#status").html(response.message);
									 $("input").addClass('error');
									}

					})
			}

			$("#f_button").bind('click',function (event) {
				event.preventDefault();
				$("#forgot").show();
				});

			$("#f_submit").live('click',function (event) {
				event.preventDefault();
				$.post("lib/php/auth/forgot_password", {email:$("#email").val()}, function (data)
					{$("#forgot").html(data)}
					)})

			$("#forgot").ajaxError(function ()
				{$(this).text('Sorry, system error.');}
				)

			$("#forgot").ajaxSend(function ()
				{$(this).text('Loading...');}
				)

			$("#login_button").bind('click', function(event){
				event.preventDefault();
				login_user();

				})

			$("#status").ajaxSend(function ()
				{$(this).text('Sending...');}
				)

			$("#status").ajaxError(function ()
				{$(this).text('Sorry, system error.');}
				)

			$("#password").keyup(function(event){
				event.preventDefault();
				if(event.keyCode == '13'){login_user();}})


		})

</script>
<!-- End Jquery Calls -->
</head>

<body>
<div id="content" style="margin-top:25px;">

	<div class="wrapper">

		<div class = "login_left">

			<img src="html/images/logo_sm.png">
			<div class="prog_name" style = "color:<?php echo CC_SCHOOL_COLOR; ?>"><?php echo CC_PROGRAM_NAME; ?></div>

			<div id="status"></div>

			<form name = "getin" id="getin">

				<p><label for "username">Username</label>
				<input type="text" id = "username" name="username" value = "<?php if (isset($_COOKIE['cc_user'])){$cookie_value = $_COOKIE['cc_user'];echo $cookie_value;} ?>"></p>

				<p><label for "password">Password</label>
				<input type="password" id = "password" name="password"></p>

				<p><label for "remember">Remember Username</label><input type="checkbox" name="remember"  id="remember" value="remember"></p>

				<p style="text-align:center"><a href="#" id="login_button">
					<img src="html/images/check_yellow.png" border="0" style="margin-top:15px;">
				</a></p>


			</form>


		</div>

		<div class = "login_right">

			<ul>
				<li><a href="#">Forgot your username or password?</a></li>
				<li><a href="#">Need an account?</a></li>
				<li>Help and information available at <a href="http://cliniccases.com">cliniccases.com</a></li>
				<li>Ask questions at the <a href="http://cliniccases.com/forum">ClinicCases forum</a></li>
			</ul>

			<a href="http://www.facebook.com/pages/ClinicCases/130258760379259" target="_new" title="Like ClinicCases on Facebook"><img src="html/images/facebook-icon.png"></a><a href="http://twitter.com/cliniccases" target="_new" title="Follow ClinicCases on Twitter"><img src="html/images/twitter-icon.png"></a>

		</div>

	</div>