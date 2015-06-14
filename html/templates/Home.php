<!-- Jquery Calls Specific to this page -->
	<script src="html/js/home.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="lib/javascripts/fullcalendar/fullcalendar.min.js"></script>
	<script type="text/javascript" src="lib/javascripts/jQuery.download.js"></script>
	<script type="text/javascript" src="lib/javascripts/timepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>

<!-- Css specific to this page -->
	<link rel="stylesheet" type="text/css" href="lib/javascripts/fullcalendar/fullcalendar.css" />
	<link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet"/>

</head>
<body>
    <div id="pdf-viewer">
        <iframe src = ""  id="frme" allowfullscreen="true"></iframe>
    </div>

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

		<div id = "home_nav" data-key = "<?php echo $_SESSION['private_key'];?>">

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

			</span>

			<button id="quick_add">Quick Add</button>

		</div>

		<div id = "home_panel" class="print_content">Loading .... </div>


	</div>

	<div id = "quick_add_form">

		<div id="quick_add_nav">

			<a href="#" class="active toggle">Case Note</a> |

			<a href="#" class="toggle">Event</a>

			<a class="quick_add_close" href="#"><img src='html/ico/cross.png' border=0 title="Close"></a>

		</div>

		<div id="quick_add_body">

				<div id = "quick_add_body_cn" class="toggle_form">

					<form name="quick_cn">

						<p class="error"></p>

						<p><label>Date</label><input type="text" name="csenote_date" id="cn_date"></p>

						<p><label>Case</label>

							<select name="csenote_case_id" id="cn_case" style="width:230px;" >
							<!-- Note Chosen seems to require the inline style width -->
								<option value="NC">Non-Case Time</option>

								<?php
								include('lib/php/utilities/names.php');

								include('lib/php/html/gen_select.php');

								$options = generate_active_cases_select($dbh,$_SESSION['login']);

								echo $options;

							?>


							</select>

						</p>

						<p class="quick_add_times">

							<?php $selector = generate_time_selector(); echo $selector; ?>

						</p>

						<p>
							<label>Description</label><br />

							<textarea name="csenote_description"></textarea>

						</p>

						<input type="hidden" name="query_type" value="add">

						<input type="hidden" name="csenote_user" value="<?php echo $_SESSION['login'];?>">

						<p id = "quick_add_cn">

							<button id="quick_add_cn_submit">Add</button>

						</p>

					</form>

				</div>

				<div id = "quick_add_body_event" class="toggle_form">

					<form name = "quick_event">

						<p class="error"></p>

						<p><label>What: </label><input type="text" name="task"></p>

						<p><label>Where: </label><input type="text" name="where"></p>

						<p><label>Start: </label><input type="text" id="ev_start" name="start"></p>

						<p><label>End: </label><input type="text" id="ev_end" name="end"></p>

						<p><label>All Day? </label><input type="checkbox" class="check" name="all_day" value="off"></p>

						<p><label>Case: </label>

							<select id="ev_case" style="width:230px;" data-placeholder="Select a Case" name="case_id">

								<option selected=selected value="NC">Non-Case</option>

								<?php $options = generate_active_cases_select($dbh,$_SESSION['login']);

								echo $options;?>

							</select>

						</p>

						<p><label>Who?</label>

							<select multiple id="ev_users" style="width:230px;" data-placeholder="Select Users" name="responsibles">

								<?php echo all_active_users_and_groups($dbh,false,$_SESSION['login']); ?>

							</select>

						</p>

						<p><label>Notes</label>

							<textarea name="notes"></textarea>

						</p>

						<input type="hidden" name="action" value="add">

						<p id = "quick_add_ev">

							<button id="quick_add_ev_submit">Add</button>

						</p>

					</form>

				</div>

		</div>

	</div>

	<div id = "event_detail_window">


	</div>

