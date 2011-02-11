
<!-- Jquery Calls Specific to this page -->
	<script type="text/javascript">
		
		$(document).ready(function(){
			
			$.idleTimeout('#idletimeout', '#idletimeout a', {
				idleAfter: 5,
				pollingInterval: 2,
				keepAliveURL: 'lib/php/keep_alive.php',
				serverResponseEquals: 'OK',
				onTimeout: function(){
					$(this).slideUp();
					window.location = "html/templates/Logout.php";
					},
						onIdle: function(){
						$(this).slideDown(); // show the warning bar
									},
						onCountdown: function( counter ){
						$(this).find("span").html( counter ); // update the counter
							},
						onResume: function(){
						$(this).slideUp(); // hide the warning bar
						}
				});
			
})
	</script>
</head>
<body>

	<div id="notifications"></div>
	<div id="idletimeout">
	You will be logged off in <span><!-- countdown place holder --></span>&nbsp;seconds due to inactivity. 
	<a id="idletimeout-resume" href="#">Click here to continue using ClinicCases</a>.
</div>
	<div id = "nav_container">

		<?php $t = tabs($_GET['i']); echo $t; ?>
		
		<div id="menus">
			<img src="html/images/logo_small4.png">  <a class="menu" href="http://code.google.com/p/cliniccases/issues/entry" target="_new" >Report Problems</a> | <a class="menu" href="logout.php">Logout</a> | Status: <span id="session_info"><span style="color:red;">Offline</span></span>
		</div>

	</div>

	<div id="content">

This is the home file.

	</div>


