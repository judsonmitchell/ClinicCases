<!-- CSS specific to this page -->
<link rel="stylesheet" type="text/css" href="html/css/journals.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css">


<!-- Jquery Calls Specific to this page -->
<script src="lib/axios/axios.bundle.min.js"></script>
<script type="text/javascript" src="html/js/Tables.js "></script>
<script src="html/js/Journals.js" type="module"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>

</head>

<body>

	<div id="notifications"></div>


	<?php include 'html/templates/interior/idletimeout.php' ?>

	<div class="header">

		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>

	</div>

	<div class="container my-4">

		<?php if (
			$_SESSION['permissions']['reads_journals']  == '0' &&
			$_SESSION['permissions']['writes_journals'] == '0'
		) {
			var_dump($_SESSION['permissions']);
			die("Sorry, you do not have permission to read or write journals.");
		} ?>



		<table id="table_journals" class="display <?php if ($_SESSION['permissions']['writes_journals'] == "1") {
																								echo "can_add";
																							} ?>" <thead>
			</thead>

			<tbody></tbody>

		</table>

	</div>
	<?php include('html/templates/interior/journals_new_journal_modal.php'); ?>