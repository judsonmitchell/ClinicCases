<!-- CSS specific to this page -->
<link rel="stylesheet" type="text/css" href="html/css/cases.min.css" />
<link href="lib/Grid/mermaid.min.css" rel="stylestheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css">
<!-- Js Calls Specific to this page -->
<!-- <script src="html/js/live.js" type="module"></script> -->
<script src="lib/axios/axios.bundle.min.js"></script>
<script src="lib/html2pdf/html2pdf.bundle.min.js"></script>
<script type="module" src="html/js/Forms.js "></script>
<script src="html/js/cases.js" type="module"></script>
<script src="html/js/casesCaseNotes.js" type="module"></script>
<script src="html/js/casesDocuments.js" type="module"></script>
<script src="html/js/casesEvents.js" type="module"></script>
<script src="html/js/print.js" type="module"></script>
<script src="html/js/idletimerStart.js" type="module"></script>
<script src="lib/javascripts/timer.js" type="module"></script>
<script type="text/javascript" src="lib/javascripts/router.js"></script>
<script type="text/javascript" src="html/js/Tables.js "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>

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
				<div class="nav-link disabled" aria-disabled="true" data-bs-toggle="tab" data-bs-target="#openCases" role='tab'>
					<h2>
						<span class="open-cases-label">
							Open Cases &nbsp;&nbsp;
							<span class="notification blue"></span>
						</span>
					</h2>
					</span>
				</div>
			</div>
		</div>
		<!--CONTENT -->
		<div id="content">
			<!-- SEARCH CASES -->
			<div role="tabpane" class="tab-pane fade show active" id="searchCases">
				<!--CASES TABLE -->
				<div id="table_cases" class="display <?php if ($_SESSION['permissions']['add_cases'] == "1") {
																								echo "can_add";
																							} ?>">
				</div>
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
	<?php include('html/templates/interior/new_case_modal.php') ?>
	<?php include('html/templates/interior/cases_documents_new_folder_modal.php'); ?>
	<?php include('html/templates/interior/cases_documents_new_document_modal.php'); ?>
	<?php include('html/templates/interior/cases_documents_edit_document_modal.php'); ?>
	<?php include('html/templates/interior/cases_documents_upload_file_modal.php'); ?>
	<?php include('html/templates/interior/cases_documents_view_ccdoc_modal.php'); ?>
	<?php include('html/templates/interior/cases_documents_rename_file_modal.php'); ?>
	<?php include('html/templates/interior/cases_documents_context_menu.php'); ?>
	<?php include('html/templates/interior/cases_events_new_event_modal.php'); ?>