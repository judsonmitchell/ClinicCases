<!-- Jquery Calls Specific to this page -->
	<script src="html/js/Home.js" type="text/javascript"></script>
</head>
<body>

	<div id="notifications"></div>
	<div id="idletimeout">
	You will be logged off in <span><!-- countdown place holder --></span>&nbsp;seconds due to inactivity. 
	<a id="idletimeout-resume" href="#">Click here to continue using ClinicCases</a>.
</div>
	<div id = "nav_container">

		<?php $t = tabs($dbh,$_GET['i']); echo $t; ?>
		
		<div id="menus">
			
			<?php include 'html/templates/Menus.php'; ?>

		</div>

	</div>

	<div id="content">

	Activity | Upcoming | Trends

	</div>


