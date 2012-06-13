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

	<script type="text/javascript" src="lib/javascripts/dataTablesFunctions.js"></script>



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

				<tr class="advanced">

					<th><input type="text"  name = "id" class = "search_init"></th>

					<th></th>

					<th><input type="text"  name = "first_name" class = "search_init"></th>

					<th><input type="text"  name = "last_name" class = "search_init"></th>

					<th><input type="text"  name = "email" class = "search_init"></th>

					<th><input type="text"  name = "mobile_phone" class = "search_init"></th>

					<th><input type="text"  name = "office_phone" class = "search_init"></th>

					<th><input type="text"  name = "home_phone" class = "search_init"></th>

					<th class="addSelects" name = "group"></th>

					<th><input type="text"  name = "username" class = "search_init"></th>

					<th><input type="text"  name = "supervisors" class = "search_init"></th>

					<th class="addSelects" name = "status"></th>

					<th></th>

					<th class="complex">

						<select id="date_created_range " title = "Date created is less, greater, or equal to...">
							<option value="equals" selected=selected>=</option>
							<option value="greater">&gt;</option>
							<option value="less">&lt;</option>
						</select>

						<input type="text" name = "date_created" id="date_created" class="search_init" title="Select a Date" column = "date_created"><br />

						<a href="#" id="addDateRow" class="smallgray">Add Condition</a>

					</th>


				</tr>

			</thead>

			<tbody>

			</tbody>

		</table>


	</div>




