<<<<<<< Updated upstream
<!-- CSS specific to this page -->
<link rel="stylesheet" type="text/css" href="lib/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="html/css/cases.min.css" />

<!-- Js Calls Specific to this page -->
<script type="text/javascript" src="lib/datatables.min.js"></script>
<script src="html/js/cases.js" type="text/javascript"></script>
<script type="text/javascript" src="lib/javascripts/router.js"></script>
<script type="text/javascript" src="html/js/Tables.js "></script>


</head>

<body>

	<div class="header">
		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>
	</div>

	<div id="notifications"></div>
	<div id="grid">
		<!-- SIDEBAR -->
		<div id="sidebar">
			<div id="nav-tab" role="tablist" class="nav-tabs nav">
				<div class="nav-link active" data-bs-toggle="tab" data-bs-target="#searchCases" role='tab'>
					<h2>Search Cases</h2>
				</div>
				<div class="nav-link" data-bs-toggle="tab" data-bs-target="#openCases" role='tab'>
					<h2>Open Cases</h2>
				</div>
			</div>
		</div>
		<!--CONTENT -->
		<div id="content">
			<!-- SEARCH CASES -->
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

					<button data-bs-toggle="modal" data-bs-target="#newCaseModal" class="primary-button" type="button">+ Add Case</button>
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
									<!--pull column names and initial visibility from from DB -->
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
						<!--dynamically added -->
					</div>
				</div>
				<!--CASES TABLE -->
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
			<!--OPEN CASES-->
			<div role='tabpane' class="tab-pane fade" id="openCases">
				<h1>Open Cases</h1>
					<div class="open-cases-container">

					</div>
			</div>

		</div>
	</div>
	<?php include('html/templates/interior/new_case_modal.php') ?>
=======
<!-- CSS specific to this page -->
<link rel="stylesheet" type="text/css" href="lib/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="html/css/cases.min.css" />

<!-- Js Calls Specific to this page -->
<script type="text/javascript" src="lib/datatables.min.js"></script>
<script src="html/js/cases.js" type="text/javascript"></script>
<script type="text/javascript" src="lib/javascripts/router.js"></script>
<script type="text/javascript" src="html/js/Tables.js "></script>


</head>

<body>

	<div class="header">
		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>
	</div>

	<div id="notifications"></div>
	<div id="grid">
		<!-- SIDEBAR -->
		<div id="sidebar">
			<div id="nav-tab" role="tablist" class="nav-tabs nav">
				<div class="nav-link active" data-bs-toggle="tab" data-bs-target="#searchCases" role='tab'>
					<h2>Search Cases</h2>
				</div>
				<div class="nav-link" data-bs-toggle="tab" data-bs-target="#openCases" role='tab'>
					<h2>Open Cases</h2>
				</div>
			</div>
		</div>
		<!--CONTENT -->
		<div id="content">
			<!-- SEARCH CASES -->
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

					<button data-bs-toggle="modal" data-bs-target="#newCaseModal" class="primary-button" type="button">+ Add Case</button>
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
									<!--pull column names and initial visibility from from DB -->
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
						<!--dynamically added -->
					</div>
				</div>
				<!--CASES TABLE -->
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
			<!--OPEN CASES-->
			<div role='tabpane' class="tab-pane fade" id="openCases">
				<h1>Open Cases</h1>
					<div class="open-cases-container">

					</div>
			</div>

		</div>
	</div>
	<?php include('html/templates/interior/new_case_modal.php') ?>
>>>>>>> Stashed changes
