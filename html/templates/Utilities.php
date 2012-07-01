
<!-- Jquery Calls Specific to this page -->
	<script  src="html/js/Utilities.js" type="text/javascript"></script>
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

	<div id="content">

		<div id = "utilities_nav">

			<span class = "utilities_nav_choices">

				<input type="radio" id="reports_button" name="radio" checked="checked" /><label for="reports_button">Reports</label>

				<?php if ($_SESSION['permissions']['can_configure'] == '1'){?>

				<input type="radio" id="config_button" name="radio" /><label for="config_button">Configuration</label>

				<?php } ?>

			</span>

		</div>

		<div id = "utilities_panel">Loading...</div>

	</div>





