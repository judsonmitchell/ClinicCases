<!-- Jquery Calls Specific to this page -->
	<script src="html/js/Home.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/javascripts/fullcalendar/fullcalendar.min.js"></script>

	<script type="text/javascript" src="lib/javascripts/jQuery.download.js"></script>


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

				<div><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></div>

				<?php
					include 'lib/php/auth/last_login.php';

					include 'lib/php/utilities/convert_times.php';

					$last_log = extract_date_time(get_last_login($dbh,$_SESSION['login']));

				?>

				<div class="small"> Last login: <?php echo $last_log; ?> </div>

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

		<div id="quick_add_nav">

			<a href="#" class="active toggle">Case Note</a> |

			<a href="#" class="toggle">Event</a>

			<a class="quick_add_close" href="#"><img src='html/ico/cross.png' border=0 title="Close"></a>

		</div>

		<div id="quick_add_body">

				<div id = "quick_add_body_cn" class="toggle_form">

					<form>

						<p><label>Date</label><input type="text" name="date" id="cn_date"></p>

						<p><label>Case</label>

							<select name="case_id" id="cn_case">

								<option value="NC">Non-Case Time</option>

								<?php include('lib/php/html/gen_select.php');

								$options = generate_active_cases_select($dbh,$_SESSION['login']);

								echo $options;
							?>


							</select>

						</p>

						<p>

							<?php $selector = generate_time_selector(); echo $selector; ?>

						</p>

					</form>

				</div>

				<div id = "quick_add_body_event" class="toggle_form">

					Event here

				</div>

		</div>

	</div>


