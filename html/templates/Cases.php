<!-- CSS specific to this page -->
<link rel="stylesheet" type="text/css" href="lib/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="html/css/cases.min.css" />



<!-- Js Calls Specific to this page -->

<script type="text/javascript" src="lib/datatables.min.js"></script>
<script src="html/js/cases.js" type="text/javascript"></script>
<script type="text/javascript" src="lib/javascripts/router.js"></script>
<script type="text/javascript" src="html/js/Tables.js "></script>

<!-- <script src="lib/DataTables-1.8.2/media/js/jquery.dataTables.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/TableTools/media/js/TableTools.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColReorder/media/js/ColReorder.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColVis/media/js/ColVis.js"></script>

	<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>

	<script type="text/javascript" src="lib/javascripts/jquery.textarea-expander.js"></script>


	<script type="text/javascript" src="lib/javascripts/jScrollPane/jquery.jscrollpane.min.js"></script>

	<script type="text/javascript" src="lib/javascripts/contextMenu/jquery.contextMenu.js"></script>

	<script type="text/javascript" src="lib/javascripts/jScrollPane/jquery.mousewheel.js"></script>

	<script type="text/javascript" src="lib/javascripts/jqueryui.combobox.js"></script>

	<script type="text/javascript" src="lib/javascripts/json2.js"></script>

	<script type="text/javascript" src="lib/javascripts/valums-file-uploader/client/fileuploader.js"></script>

	<script type="text/javascript" src="lib/javascripts/lwrte/jquery.rte.js"></script>

	<script type="text/javascript" src="lib/javascripts/lwrte/jquery.rte.tb.js"></script>

	<script type="text/javascript" src="lib/javascripts/jQuery.download.js"></script>


	<script type="text/javascript" src="lib/javascripts/timepicker/jquery-ui-timepicker-addon.js"></script>

	<script type="text/javascript" src="lib/javascripts/jquery.highlight-3.js"></script>

	<script type="text/javascript" src="lib/javascripts/dataTablesFunctions.js"></script> -->

</head>

<body>

	<div class="header">
		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>
		<!-- <?php include 'html/templates/Menus.php'; ?> -->
	</div>

	<!-- <div id="pdf-viewer">
		<iframe src="" id="frme" allowfullscreen="true"></iframe>
	</div> -->

	<div id="notifications"></div>

	<?php include 'html/templates/interior/timer.php' ?>

	<?php include 'html/templates/interior/idletimeout.php' ?>


	<div id="grid">
		<div id="sidebar">
			<div id="nav-tab" role="tablist" class="nav-tabs nav">
				<div class="nav-link active" data-bs-toggle="tab" data-bs-target="#searchCases" role='tab'>
					<h3>Search Cases</h3>
				</div>
				<div class="nav-link" data-bs-toggle="tab" data-bs-target="#openCases" role='tab'>
					<h3>Open Cases</h3>
				</div>

			</div>
		</div>

		<div id="content">
			<div role='tabpane' class="tab-pane fade" id="openCases">
				<h1>Open Cases</h1>
			</div>
			<div role="tabpane" class="tab-pane fade show active" id="searchCases">
				<div class=" display-grid-2-1">
					<div class="search_container">
						<select id="cases_select">
							<option value="open">Open Cases Only</option>
							<option value="closed">Closed Cases Only</option>
							<option value="all">All Cases</option>

						</select>
						<div class="input_search">
							<input type="search" id="cases_search" placeholder="Search" />
							<img src="./icons/search.png" />
						</div>

					</div>
					<button class="primary-button" type="button">+ Add Case</button>

				</div>
				<div class="table__utils">
					<div class="advanced_search">
						<p>Advanced Search </p>
					</div>
					<div class="table__buttons">
						<div class="select">
							<button type="button" data-select="#columnsSelect" class="select__button">Columns</button>
							<div id="columnsSelect" class="select__options closed">
								<div class="select__list">
									<?php $CC_columns = columns_array($dbh);
									$index = 0;
									foreach ($CC_columns as $key => $col) {
										if ($col['include_in_case_table'] == "true" && $col['display_by_default'] == "true") {
											echo "<label for'" . $index . "'><input id=" . $index . "  checked data-type='" . $col['input_type'] . "'  data-id='" . $col['db_name'] . "' type='checkbox' name='" . $col['display_name'] . "'/>"  . $col['display_name'] . "</label>";
										} else if ($col['include_in_case_table'] == "true" && $col['display_by_default'] == "false") {
											echo "<label for'" . $index . "'><input id=" . $index . " data-type='" . $col['input_type'] . "'  data-id='" . $col['db_name'] . "' type='checkbox'  name='" . $col['display_name'] . "'/>"  . $col['display_name'] . "</label>";
										}
										$index++;
									}
									?>
									<?php ?>
								</div>
								<div class="select__footer">
									<button data-select="#columnsSelect" id="columnsSelectButton" class="mt-2 mb-1">Apply Changes </button>
								</div>

							</div>

						</div>


						<button>Print/Export</button>
						<button class="cases__reset" type='button'>Reset</button>
					</div>
					<div class="advanced-search__fields">
					</div>
				</div>

				<!-- <div id="processing">Loading....</div> -->

				<table id="table_cases" class="display <?php if ($_SESSION['permissions']['add_cases'] == "1") {
																									echo "can_add";
																								} ?>">

					<thead>

						<tr>
							<?php $CC_columns = columns_array($dbh);
							foreach ($CC_columns as $key => $col) {
								if ($col['include_in_case_table'] == "true") {
									echo "<th>" . $col['display_name'] . "</th>";
								}
							}
							?>
						</tr>


					</thead>

					<tbody>

					</tbody>

				</table>
			</div>


		</div>


	</div>

	<!-- This is html for the right click context menu in documents.  Needs to be here for positioning purposes -->
	<!-- <ul id="docMenu" class="contextMenu">

		<li class="open">
			<a href="#open">Open</a>
		</li>

		<?php if ($_SESSION['permissions']['documents_modify'] === '1') { ?>
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
	</ul> -->
	<!-- This is html for the copy and paste context menu in documents -->
	<!-- <ul id="docMenu_copy_paste" class="contextMenu">

		<li class="open disabled">
			<a href="#open">Open</a>
		</li>

		<?php if ($_SESSION['permissions']['documents_modify'] === '1') { ?>
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
	</ul> -->

	<!-- Document upload div goes here -->
	<div class="upload_dialog">

		<div class="upload_dialog_file" tabindex="1"></div>

		<div class="upload_dialog_url" tabindex="2">
			<div class="upload_url_button qq-upload-button">Web address</div>
			<p class="upload_url_notify"></p>
			<div class="upload_url_form">
				<label>URL</label><input type="text" class="url_upload"><br />
				<p class="upload_url_form_error_url"></p>
				<br />
				<label>Name</label><input type="text" class="url_upload_name">
				<p class="upload_url_form_error_name"></p>
				<button class="upload_url_submit">Submit</button>
			</div>

		</div>

	</div>