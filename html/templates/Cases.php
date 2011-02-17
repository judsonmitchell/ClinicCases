<!-- CSS specific to this page -->
<link rel="stylesheet" href="lib/DataTables-1.7.5/media/css/data_table_jui.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.7.5/extras/TableTools-2.0.0/media/css/TableTools.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.7.5/extras/ColVis/media/css/ColVis.css" type="text/css">

<!-- Jquery Calls Specific to this page -->
	<script src="lib/DataTables-1.7.5/media/js/jquery.dataTables.min.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/DataTables-1.7.5/extras/TableTools-2.0.0/media/js/TableTools.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.7.5/extras/ColReorder/media/js/ColReorder.min.js"></script>
	
	<script type="text/javascript" src="lib/DataTables-1.7.5/extras/ColVis/media/js/ColVis.js"></script> 


	<script  src="html/js/Cases.js" type="text/javascript"></script>
	
	

</head>
<body>

	<div id="notifications"></div>
	
	<div id="idletimeout">
		You will be logged off in <span><!-- countdown place holder --></span>&nbsp;seconds due to inactivity. 
		<a id="idletimeout-resume" href="#">Click here to continue using ClinicCases</a>.
	</div>

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
					<th>Case Number</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Date Open</th>
					<th>Date Close</th>
					<th>Case Type</th>
					<th>Professor</th>
					<th>SSN</th>
					<th>DOB</th>
					<th>Age</th>
					<th>Gender</th>
					<th>Race</th>
					<th>Disposition</th>
				</tr>
				<tr class="advanced">
					<th><input type="text" size="9" class="search_init"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
					<th><input type="text"></th>
				</tr>
				</thead>
			<tbody>
				
			</tbody>
		</table>

	</div>





