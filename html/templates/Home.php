<!-- Jquery Calls Specific to this page -->
	<script src="html/js/Home.js" type="text/javascript"></script>
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

		<div id = "home_nav">

			<div id = "home_data">

				<span><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></span>

			</div>

			<span class = "home_nav_choices">

				<input type="radio" id="activity_button" name="activity" checked="checked" /><label for="activity_button">Activity</label>

				<input type="radio" id="upcoming_button" name="upcoming" /><label for="upcoming_button">Upcoming</label>

				<input type="radio" id="trends_button" name="trends" /><label for="trends_button">Trends</label>

			</span>

			<button id="quick_add">Quick Add</button>

		</div>

		<div id = "home_panel">Panel here </div>

	</div>


