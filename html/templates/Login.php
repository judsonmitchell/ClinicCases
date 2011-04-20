
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
			
			$("#f_button").bind('click',function () {
				event.preventDefault();
				$("#forgot").show();
				});
			
			$("#f_submit").live('click',function () {
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
				
			$("#login_button").bind('click', function(){
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

	<div id="idletimeout">
		You have been logged off due to 20 minutes inactivity. Please log in again. 
	</div>


	<div id="wrapper1">
		<img src="html/images/logo.png" id="logo" style="margin-top:9%">
		
		<h4>Case Management Software</h4>
		<p style="color:<?php echo $CC_school_color;?>"><?php echo $CC_program_name; ?></p>

	<center>
		<div id="status"></div>
	</center>
	
		<form name = "getin" id="getin">

			<label for "username">Username</label><br>
			<input type="text" id = "username" name="username" value = "<?php if (isset($_COOKIE['cc_user'])){$cookie_value = $_COOKIE['cc_user'];echo $cookie_value;} ?>"><br>

			<label for "password">Password</label><br>
			<input type="password" id = "password" name="password"><br>
			
			<label for "remember">Remember My Username</label><input type="checkbox" name="remember"  id="remember" value="remember"><br>
			
			<a href="#" id="login_button">
				<img src="html/images/check_yellow.png" border="0" style="margin-top:15px;">
			</a><br>
		
			
		</form>

	<div style="margin-top:30px">
		<a href="#" id="f_button">Forgot username or password?</a>
	</div>

	<div id="forgot">

	<p>Please provide your email address and your information will be sent to you.</p>
	<input type = "text" id = "email" name="email"><br>
	<a id = "f_submit" href="#">
		<img src="html/images/check_yellow.png" border="0" style="margin-top:15px;">
	</a>

	</div>
	
	<div style="margin-top:30px"><a href="./newaccount">Students: New Account</a></div>

	</div>

</div>
