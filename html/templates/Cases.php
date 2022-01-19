<!-- CSS specific to this page -->
<link rel="stylesheet" type="text/css" href="html/css/cases.min.css" />
<link href="lib/Grid/mermaid.min.css" rel="stylestheet" />

<!-- Js Calls Specific to this page -->
<script src="lib/axios/axios.bundle.min.js"></script>
<script src="lib/html2pdf/html2pdf.bundle.min.js"></script>
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
							<img src="html/ico/search.png" />
						</div>

					</div>

					<button data-bs-toggle="modal" data-bs-target="#newCaseModal" class="primary-button" type="button">+ Add Case</button>
				</div>
			
				<!--CASES TABLE -->
				<div id="table_cases" class="display <?php if ($_SESSION['permissions']['add_cases'] == "1") {
																									echo "can_add";
																								} ?>"></div>
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
