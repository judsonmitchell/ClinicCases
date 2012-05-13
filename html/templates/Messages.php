<!-- Jquery Calls Specific to this page -->
	<script  src="html/js/Messages.js" type="text/javascript"></script>
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

				<option value="drafts">Drafts</option>

				<option value="starred">Starred</option>

				<option value="sent">Sent</option>

				<option value="archive">Archive</option>

			</select>

			<div id = "msg_bar_right">

				<input type="text" class="messages_search" id="msg_search" value= "Search Messages">

				<input type="button" class="msg_search_clear">

				<button id ="msg_archive_all">Archive All</button>

				<button id = "new_msg">New Message</button>

			</div>

		</div>

		<div id = "msg_panel">

				Loading...

		</div>


	</div>





