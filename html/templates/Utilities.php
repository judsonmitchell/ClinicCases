<!-- Jquery Calls Specific to this page -->
<!-- <script src="html/js/utilities.min.js" type="text/javascript"></script>
<script src="html/js/utilitiesNonCase.min.js" type="text/javascript"></script>
<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>
<script src="lib/DataTables-1.8.2/media/js/jquery.dataTables.js" type="text/javascript"></script>
<script type="text/javascript" src="lib/DataTables-1.8.2/extras/TableTools/media/js/TableTools.min.js"></script>
<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColReorder/media/js/ColReorder.min.js"></script>
<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColVis/media/js/ColVis.js"></script>
<script type="text/javascript" src="lib/javascripts/dataTablesFunctions.js"></script>
<script type="text/javascript" src="lib/javascripts/jquery.textarea-expander.js"></script>
<script type="text/javascript" src="lib/javascripts/jquery.highlight-3.js"></script> -->
<script src="lib/axios/axios.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
<script src="html/js/forms.js" type="module"></script>
<script src="html/js/utilities.js" type="module"></script>

<!-- CSS specific to this page -->

<!-- <link rel="stylesheet" href="lib/DataTables-1.8.2/media/css/data_table_jui.css" type="text/css">
<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/TableTools/media/css/TableTools.css" type="text/css">
<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColVis/media/css/ColVis.css" type="text/css">
<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColReorder/media/css/ColReorder.css" type="text/css">
<link type="text/css" href="lib/javascripts/valums-file-uploader/client/fileuploader.css" rel="stylesheet" />
<link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet" /> -->

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css">
<link rel="stylesheet" href="html/css/utilities.min.css" />

</head>

<body>


	<div class="header">
		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>
	</div>
	<div id="grid">
		<!-- SIDEBAR -->
		<div id="sidebar">
			<div id="nav-tab" role="tablist" class="nav-tabs nav">
				<div class="nav-link active" data-bs-toggle="tab" data-bs-target="#timeReports" role='tab'>
					<h2>Time Reports</h2>
				</div>
				<div class="nav-link" data-bs-toggle="tab" data-bs-target="#nonCaseTime" role='tab'>
					<h2>Non-Case Time</h2>
				</div>
				<div class="nav-link" data-bs-toggle="tab" data-bs-target="#configuration" role='tab'>
					<h2>Configuration</h2>
				</div>
			</div>
		</div>
		<!--CONTENT -->
		<div id="content">
			<!-- SEARCH CASES -->
			<div role="tabpane" class="tab-pane fade show active p-4" id="timeReports">
				<!--CASES TABLE -->
				<h2>Time Reports</h2>
				<form id="timeReportsForm">

					<div class="d-flex align-items-end">
						<div class="form__control form__control--select flex-grow-1 ">

							<select name="type" class="time_reports_slim_select p-0" placeholder="Select a group or case...">
								<?php
								include 'db.php';
								include 'lib/php/html/gen_select.php';
								include 'lib/php/utilities/names.php';
								echo reports_users_and_groups($dbh, null);
								?>

							</select>
							<label>Select a user, group, or case for which to load reports.</label>
						</div>

						<?php
						$currentDate = date('Y-m-d');
						$oneWeekAgo = strtotime('-1 week', strtotime($currentDate));
						?>
						<div class="form__control">

							<input id="date_start" name="date_start" type="date" value="<?php echo date('Y-m-d', $oneWeekAgo);; ?>" />
							<label for="date_start">Date Start</label>
						</div>
						<div class="form__control">


							<input id="date_end" name="date_end" type="date" value="<?php echo $currentDate; ?>" />
							<label for="date_end">Date End</label>
						</div>
						<button type="submit" class="button--primary time_reports_load">Go</button>
					</div>

				</form>

				<div id="table_reports"></div>
			</div>
			<!--OPEN CASES-->
			<div role='tabpane' class="tab-pane fade" id="openCases">
				<div class="open-cases-container">


					<nav>
						<div class="nav nav-tabs" id="openCasesTabs" role="tablist">
							<!-- <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Home</button> -->
						</div>
						<div id="openCasesTabsMobile">
							<select name="openCasesTabs">

							</select>
							<button id="closeCaseTabMobile">
								<img src="html/ico/times_circle.svg" alt="Close Case Button">
								Close</button>
						</div>
					</nav>
					<div class="tab-content" id="openCasesTabContent">
					</div>

				</div>
			</div>

		</div>
	</div>
	<!-- <div id="content">

		<div id = "utilities_nav">

			<span class = "utilities_nav_choices">

				<input type="radio" id="reports_button" name="radio" checked="checked" /><label for="reports_button">Time Reports</label>

				<?php if ($_SESSION['permissions']['can_configure'] == '1') { ?>

				<input type="radio" id="config_button" name="radio" /><label for="config_button">Configuration</label>

				<?php } ?>

				<?php if ($_SESSION['permissions']['add_case_notes'] == '1') { ?>

				<input type="radio" id="non_case_button" name="radio" /><label for="non_case_button">Non-Case Time</label>

				<?php } ?>
			</span>

		</div>

		<div id = "utilities_panel" data-unit = "<?php echo CC_TIME_UNIT; ?>">

            <div id="report_chooser" class="ui-toolbar ui-widget-header ui-corner-tl ui-corner-all">

                        <p>
                            <label>Report on:</label>
                            <select name = "type" data-placeholder="Select users or cases to report on">
                                <option value=""></option>
                                <?php
																include 'db.php';
																include 'lib/php/html/gen_select.php';
																include 'lib/php/utilities/names.php';
																echo reports_users_and_groups($dbh, null);
																?>

                            </select>
                        </p>

                        <p>
                            <label>Date Start</label>
                            <input type="hidden" name="date_start">
                            <input type="text" name="date_start_display" class="report_date">
                        </p>

                        <p>
                            <label>Date End </label>
                            <input type="hidden" name="date_end">
                            <input type="text" name="date_end_display" class="report_date">

                        </p>

                        <p><button class="report_submit">Go</button></p>

            </div>

		</div>

	</div> -->

</body>

</html>