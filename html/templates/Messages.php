<!-- Jquery Calls Specific to this page -->
	<script  src="html/js/messages.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="lib/javascripts/jquery.highlight-3.js"></script>

<!-- Css specific to this page -->
	<link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet"/>

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

		<div id = "msg_nav">

			<select id = "msg_view_chooser">

				<option value="inbox" selected=selected>Inbox</option>

				<option value="starred">Starred</option>

				<option value="sent">Sent</option>

				<option value="archive">Archive</option>

			</select>

			<div id = "msg_header_bar_right">

				<input type="text" class="messages_search" id="msg_search" value= "Search Messages">

				<input type="button" class="msg_search_clear">

				<button id ="msg_archive_all_button">Archive All</button>

				<button id = "new_msg_button">New Message</button>

			</div>

		</div>

		<div id = "msg_panel">

				Loading...

		</div>


	</div>





