<!-- CSS specific to this page -->
<link rel="stylesheet" href="lib/DataTables-1.8.2/media/css/data_table_jui.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/TableTools/media/css/TableTools.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColVis/media/css/ColVis.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColReorder/media/css/ColReorder.css" type="text/css">

<link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet"/>

<link type="text/css" href="lib/javascripts/jScrollPane/jquery.jscrollpane.css" rel="stylesheet"/>

<link type="text/css" href="lib/javascripts/lwrte/jquery.rte.css" rel="stylesheet"/>

<link type="text/css" href="lib/javascripts/contextMenu/jquery.contextMenu.css" rel="stylesheet"/>

<link type="text/css" href="lib/javascripts/valums-file-uploader/client/fileuploader.css" rel="stylesheet"/>

<link rel="stylesheet" type="text/css" href="lib/javascripts/timepicker/jquery-ui-timepicker-addon.css" />



<!-- Js Calls Specific to this page -->
	<script src="lib/DataTables-1.8.2/media/js/jquery.dataTables.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/TableTools/media/js/TableTools.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColReorder/media/js/ColReorder.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColVis/media/js/ColVis.js"></script>

	<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>

	<script type="text/javascript" src="lib/javascripts/jquery.textarea-expander.js"></script>

	<script  src="html/js/cases.min.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/javascripts/jScrollPane/jquery.jscrollpane.min.js"></script>

	<script type="text/javascript" src="lib/javascripts/contextMenu/jquery.contextMenu.js"></script>

	<script type="text/javascript" src="lib/javascripts/jScrollPane/jquery.mousewheel.js"></script>

	<script type="text/javascript" src="lib/javascripts/jqueryui.combobox.js"></script>

	<script type="text/javascript" src="lib/javascripts/json2.js"></script>

	<script type="text/javascript" src="lib/javascripts/valums-file-uploader/client/fileuploader.js"></script>

	<script type="text/javascript" src="lib/javascripts/lwrte/jquery.rte.js"></script>

	<script type="text/javascript" src="lib/javascripts/lwrte/jquery.rte.tb.js"></script>

	<script type="text/javascript" src="lib/javascripts/jQuery.download.js"></script>

	<script type="text/javascript" src="lib/javascripts/router.js"></script>

	<script type="text/javascript" src="lib/javascripts/timepicker/jquery-ui-timepicker-addon.js"></script>

	<script type="text/javascript" src="lib/javascripts/jquery.highlight-3.js"></script>

	<script type="text/javascript" src="lib/javascripts/dataTablesFunctions.js"></script>

</head>
<body>

    <div id="pdf-viewer">
        <iframe src = ""  id="frme" allowfullscreen="true"></iframe>
    </div>

	<div id="notifications"></div>

	<?php include 'html/templates/interior/timer.php' ?>

	<?php include 'html/templates/interior/idletimeout.php' ?>

	<div id = "nav_container">

		<?php $t = tabs($dbh,$_GET['i']); echo $t; 	?>

		<div id="menus">

			<?php include 'html/templates/Menus.php'; ?>

		</div>

	</div>

	<div id="content">

		<div id="processing">Loading....</div>

			<table id="table_cases" class="display <?php if ($_SESSION['permissions']['add_cases'] == "1"){echo "can_add";}?>">

			<thead>

				<tr>
					<?php $CC_columns = columns_array($dbh);
					foreach($CC_columns as $key=>$col){
						if ($col['include_in_case_table'] == "true")
						{echo "<th>" . $col['display_name'] . "</th>";}
						}
					?>
				</tr>

				<tr class="advanced">

					<?php
					foreach($CC_columns as $key=>$col){

						if ($col['include_in_case_table'] == "true"  && $col['input_type'] == "text"):
							echo "<th><input type=\"text\" name = \"" . $col['db_name'] . "\" class = \"search_init\"></th>";

						elseif ($col['include_in_case_table'] == "true"  && $col['input_type'] == "multi-text"):
							echo "<th><input type=\"text\" name = \"" . $col['db_name'] . "\" class = \"search_init\"></th>";

						elseif ($col['include_in_case_table'] == "true"  && $col['input_type'] == "textarea"):
							echo "<th><input type=\"text\" name = \"" . $col['db_name'] . "\" class = \"search_init\"></th>";

						elseif ($col['include_in_case_table'] == "true"  && $col['input_type'] == "dual"):
							echo "<th><input type=\"text\" name = \"" . $col['db_name'] . "\" class = \"search_init\"></th>";

						elseif ($col['include_in_case_table'] == "true" && $col['input_type'] == "select"):
							echo "<th class=\"addSelects\" name =\"" . $col['display_name'] . "\"></th>";

						elseif ($col['include_in_case_table'] == "true" && $col['input_type'] == "select_multiple"):
							echo "<th><input type=\"text\" name = \"" . $col['db_name'] . "\" class = \"search_init\"></th>";

						elseif ($col['include_in_case_table'] == "true" && $col['input_type'] == "date"):
							//Create id variable
							$date_type = substr($col['db_name'],5);

							echo "
							<th class=\"complex\">

								<select id=\"" . $date_type . "_range\" title=\"" . $date_type . " date is less, greater, or equal to...\">
									<option value=\"equals\" selected=selected>&#61;</option>
									<option value=\"greater\">&gt;</option>
									<option value=\"less\">&lt;</option>
								</select>

								<input type=\"text\" name = \"" . $col['db_name'] . "\" id=\"" . $col['db_name'] . "\" class=\"search_init\" title=\"Select a Date\" column = \"" . $col['db_name'] . "\"><br />

								<a href=\"#\" id=\"add" . $date_type . "Row\" class=\"smallgray\">Add Condition</a>

							</th>";

						endif;

						}
						?>
					</tr>
					<tr class="advanced_2">
					<?php
						foreach($CC_columns as $key=>$col){

						//Check for date fields. They get special treatment.
						$date_check = substr($col['db_name'],0,4);

						if ($col['include_in_case_table'] == "true" && $col['input_type'] !== 'date'):
						 echo "<th></th>";

						elseif ($col['include_in_case_table'] == "true" && $col['input_type'] == 'date'):
							//Create id variable
							$date_type = substr($col['db_name'],5);
							echo "
							<th class=\"complex\" id=\"second_" . $date_type . "_cell\">

								<select id=\"" . $date_type . "_range_2\" title=\"" . $date_type . " date is less, greater, or equal to...\">
									<option value=\"equals\" selected=selected>&#61;</option>
									<option value=\"greater\">&gt;</option>
									<option value=\"less\">&lt;</option>
								</select>

								<input type=\"text\" name = \"" . $col['db_name'] . "_2" .  "\" id=\"" . $col['db_name'] .  "_2" . "\" class=\"search_init\" title=\"Select a Date\"><br />


							</th>";

							endif;
						}
							?>
					</tr>
				</thead>

			<tbody>

			</tbody>

		</table>

	</div>

	<!-- This is html for the right click context menu in documents.  Needs to be here for positioning purposes -->
	<ul id="docMenu" class="contextMenu">

	    <li class="open">
	        <a href="#open">Open</a>
	    </li>

        <?php if ($_SESSION['permissions']['documents_modify'] === '1'){ ?>
	    <li class="cut separator">
	        <a href="#cut">Cut</a>
	    </li>

	    <li class="copy">
	        <a href="#copy">Copy</a>
	    </li>

	    <li class="paste disabled">
	        <a href="#paste">Paste</a>
	    </li>

	     <li class="rename">
	        <a href="#rename">Rename</a>
	    </li>

	    <li class="delete">
	        <a href="#delete">Delete</a>
	    </li>

        <?php } ?>
	    <li class="properties separator">
	        <a href="#properties">Properties</a>
	    </li>
	</ul>
<!-- This is html for the copy and paste context menu in documents -->
	<ul id="docMenu_copy_paste" class="contextMenu">

	    <li class="open disabled">
	        <a href="#open">Open</a>
	    </li>

        <?php if ($_SESSION['permissions']['documents_modify'] === '1'){ ?>
	    <li class="cut separator disabled">
	        <a href="#cut">Cut</a>
	    </li>

	    <li class="copy disabled">
	        <a href="#copy">Copy</a>
	    </li>

	    <li class="paste">
	        <a href="#paste">Paste</a>
	    </li>

	     <li class="rename disabled">
	        <a href="#rename">Rename</a>
	    </li>

	    <li class="delete disabled">
	        <a href="#delete">Delete</a>
	    </li>

        <?php } ?>
	    <li class="properties separator">
	    <li class="properties separator disabled">
	        <a href="#properties">Properties</a>
	    </li>
	</ul>

<!-- Document upload div goes here -->
<div class = "upload_dialog">

		<div class = "upload_dialog_file" tabindex="1"></div>

		<div class = "upload_dialog_url" tabindex="2">
			<div class = "upload_url_button qq-upload-button">Web address</div>
			<p class = "upload_url_notify"></p>
			<div class = "upload_url_form">
				<label>URL</label><input type="text" class="url_upload"><br />
				<p class="upload_url_form_error_url"></p>
				<br />
				<label>Name</label><input type="text" class="url_upload_name">
				<p class="upload_url_form_error_name"></p>
				<button class="upload_url_submit">Submit</button>
			</div>

		</div>

	</div>
