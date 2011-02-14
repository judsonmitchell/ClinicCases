<!-- Jquery Calls Specific to this page -->
	<script src="lib/DataTables-1.7.5/media/js/jquery.dataTables.min.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/DataTables-1.7.5/extras/TableTools-2.0.0/media/js/TableTools.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.7.5/extras/ColReorder/media/js/ColReorder.min.js"></script>

	<script  src="html/js/Cases.js" type="text/javascript"></script>

</head>
<body>

	<div id="notifications"></div>

	<div id = "nav_container">

		<?php $t = tabs($_GET['i']); echo $t; ?>
		
		<div id="menus">
			
			<?php include 'html/templates/Menus.php'; ?>

		</div>

	</div>

	<div id="content">

		<table id="table_cases" class="display">
			<thead>
				<tr>
					<th>Id</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Date Open</th>
					<th>Date Close</th>
					<th>Case Type</th>
					<th>Professor</th>
					<th>Disposition</th>
				</tr>
				</thead>
			<tbody>
				
			</tbody>
		</table>

	</div>





