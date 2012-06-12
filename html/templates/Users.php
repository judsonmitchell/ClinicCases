<!-- CSS specific to this page -->
<link rel="stylesheet" href="lib/DataTables-1.8.2/media/css/data_table_jui.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/TableTools/media/css/TableTools.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColVis/media/css/ColVis.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColReorder/media/css/ColReorder.css" type="text/css">

<!-- Jquery Calls Specific to this page -->
	<script  src="html/js/Users.js" type="text/javascript"></script>

	<script src="lib/DataTables-1.8.2/media/js/jquery.dataTables.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/TableTools/media/js/TableTools.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColReorder/media/js/ColReorder.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColVis/media/js/ColVis.js"></script>


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

		<div id="processing">Loading....</div>

		<table id = "table_users" class="display">

			<thead>

				<tr>

					<th>Id</th>

					<th>Face</th>

					<th>First Name</th>

					<th>Last Name</th>

					<th>Email</th>

					<th>Mobile Phone</th>

					<th>Office Phone</th>

					<th>Home Phone</th>

					<th>Group</th>

					<th>Username</th>

					<th>Supervisors</th>

					<th>Status</th>

					<th>New</th>

					<th>Date Created</th>

				</tr>

			</thead>

			<tbody>

			</tbody>

		</table>


	</div>




