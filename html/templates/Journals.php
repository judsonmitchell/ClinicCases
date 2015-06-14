<!-- CSS specific to this page -->
<link rel="stylesheet" href="lib/DataTables-1.8.2/media/css/data_table_jui.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/TableTools/media/css/TableTools.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColVis/media/css/ColVis.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColReorder/media/css/ColReorder.css" type="text/css">

<link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet"/>

<link type="text/css" href="lib/javascripts/lwrte/jquery.rte.css" rel="stylesheet"/>

<!-- Jquery Calls Specific to this page -->
	<script src="lib/DataTables-1.8.2/media/js/jquery.dataTables.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/TableTools/media/js/TableTools.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColReorder/media/js/ColReorder.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColVis/media/js/ColVis.js"></script>

	<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>

	<script type="text/javascript" src="lib/javascripts/jquery.textarea-expander.js"></script>

	<script type="text/javascript" src="lib/javascripts/lwrte/jquery.rte.js"></script>

	<script type="text/javascript" src="lib/javascripts/lwrte/jquery.rte.tb.js"></script>

	<script type="text/javascript" src="lib/javascripts/router.js"></script>

	<script type="text/javascript" src="lib/javascripts/jquery.highlight-3.js"></script>

	<script type="text/javascript" src="lib/javascripts/dataTablesFunctions.js"></script>

	<script type="text/javascript" src="lib/javascripts/print.js"></script>

	<script  src="html/js/journals.min.js" type="text/javascript"></script>
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

		<?php if ($_SESSION['permissions']['reads_journals']  == '0' &&
				$_SESSION['permissions']['writes_journals'] == '0')
				{die("Sorry, you do not have permission to read or write journals.");} ?>


		<div id="processing">Loading....</div>

			<table id="table_journals" class="display <?php if ($_SESSION['permissions']['writes_journals'] == "1"){echo "can_add";}?>"

				<thead></thead>

				<tbody></tbody>

			</table>

	</div>





