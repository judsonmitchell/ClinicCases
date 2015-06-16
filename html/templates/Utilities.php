
<!-- Jquery Calls Specific to this page -->
	<script  src="html/js/utilities.min.js" type="text/javascript"></script>
	<script  src="html/js/utilitiesNonCase.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>
	<script src="lib/DataTables-1.8.2/media/js/jquery.dataTables.js" type="text/javascript"></script>
	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/TableTools/media/js/TableTools.min.js"></script>
	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColReorder/media/js/ColReorder.min.js"></script>
	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColVis/media/js/ColVis.js"></script>
	<script type="text/javascript" src="lib/javascripts/dataTablesFunctions.js"></script>
	<script type="text/javascript" src="lib/javascripts/jquery.textarea-expander.js"></script>
	<script type="text/javascript" src="lib/javascripts/jquery.highlight-3.js"></script>

<!-- CSS specific to this page -->

      <link rel="stylesheet" href="lib/DataTables-1.8.2/media/css/data_table_jui.css" type="text/css">
      <link rel="stylesheet" href="lib/DataTables-1.8.2/extras/TableTools/media/css/TableTools.css" type="text/css">
      <link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColVis/media/css/ColVis.css" type="text/css">
      <link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColReorder/media/css/ColReorder.css" type="text/css">
      <link type="text/css" href="lib/javascripts/valums-file-uploader/client/fileuploader.css" rel="stylesheet"/>
      <link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet"/>

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

				<input type="radio" id="reports_button" name="radio" checked="checked" /><label for="reports_button">Time Reports</label>

				<?php if ($_SESSION['permissions']['can_configure'] == '1'){?>

				<input type="radio" id="config_button" name="radio" /><label for="config_button">Configuration</label>

				<?php } ?>

				<?php if ($_SESSION['permissions']['add_case_notes'] == '1'){?>

				<input type="radio" id="non_case_button" name="radio" /><label for="non_case_button">Non-Case Time</label>

				<?php } ?>
			</span>

		</div>

		<div id = "utilities_panel" data-unit = "<?php echo CC_TIME_UNIT;?>">

            <div id="report_chooser" class="ui-toolbar ui-widget-header ui-corner-tl ui-corner-all">

                        <p>
                            <label>Report on:</label>
                            <select name = "type" data-placeholder="Select users or cases to report on">
                                <option value=""></option>
                                <?php
                                include 'db.php';
                                include 'lib/php/html/gen_select.php';
                                include 'lib/php/utilities/names.php';
                                echo reports_users_and_groups($dbh,null);
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

	</div>

</body>

</html>
