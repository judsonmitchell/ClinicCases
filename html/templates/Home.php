<!-- Jquery Calls Specific to this page -->
	<script src="html/js/Home.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/javascripts/fullcalendar/fullcalendar.min.js"></script>

<!-- Css specific to this page -->

	<link rel="stylesheet" type="text/css" href="lib/javascripts/fullcalendar/fullcalendar.css" />

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

				<input type="radio" id="activity_button" name="radio" checked="checked" /><label for="activity_button">Activity</label>

				<input type="radio" id="upcoming_button" name="radio" /><label for="upcoming_button">Upcoming</label>

				<input type="radio" id="trends_button" name="radio" /><label for="trends_button">Trends</label>

			</span>

			<button id="quick_add">Quick Add</button>

		</div>

		<div id = "home_panel">Loading .... </div>

	</div>

	<div id = "quick_add_form">

		<div>

			<a href="#">Case Note</a><a href="#">Event</a>

		</div>

	</div>


