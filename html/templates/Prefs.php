
<!-- Jquery Calls Specific to this page -->
	<script type="text/javascript">
		
		$(document).ready(function(){
			
			
			
			
			
			
		}
	</script>
</head>
<body>

	<div id="notifications"></div>

	<div id = "nav_container">

		<?php $t = tabs($_GET['i']); echo $t; ?>
		
		<div id="menus">
			<img src="html/images/logo_small4.png">  <a class="menu" href="http://code.google.com/p/cliniccases/issues/entry" target="_new" >Report Problems</a> | <a class="menu" href="logout.php">Logout</a> | Status: <span id="session_info"><span style="color:red;">Offline</span></span>
		</div>

	</div>

	<div id="content">

This is the prefs file.

	</div>





