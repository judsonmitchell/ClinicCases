<!-- Jquery Calls Specific to this page -->
	<script  src="html/js/Board.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>


<!-- Css Specific to this Page -->

	<link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet"/>

	<link type="text/css" href="lib/javascripts/lwrte/jquery.rte.css" rel="stylesheet"/>
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

		<div id = "board_nav" class="ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr">

			<label>Search:</label>

			<input name="board_search">

			<button>New Post</button>

		</div>

		<div id ="board_panel">

			Loading...

		</div>

	</div>





