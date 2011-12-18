
<!-- Jquery Calls Specific to this page -->
	<script  src="html/js/Utilities.js" type="text/javascript"></script>
</head>
<body>

	<div id="notifications"></div>

	<div id = "nav_container">

		<?php $t = tabs($dbh,$_GET['i']); echo $t; ?>
		
		<div id="menus">
			<?php include 'html/templates/Menus.php'; ?>
		</div>

	</div>

	<div id="content">

This is the utilities file.

	</div>





