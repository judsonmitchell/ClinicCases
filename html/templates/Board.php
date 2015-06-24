<!-- Jquery Calls Specific to this page -->
	<?php if ($_SESSION['permissions']['view_board'] == '1') { ?>

	<script  src="html/js/board.min.js" type="text/javascript"></script>

	<?php } ?>

	<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>

	<script type="text/javascript" src="lib/javascripts/lwrte/jquery.rte.js"></script>

	<script type="text/javascript" src="lib/javascripts/lwrte/jquery.rte.tb.js"></script>

	<script type="text/javascript" src="lib/javascripts/valums-file-uploader/client/fileuploader.js"></script>

	<script type="text/javascript" src="lib/javascripts/jQuery.download.js"></script>

	<script type="text/javascript" src="lib/javascripts/jquery.highlight-3.js"></script>



<!-- Css Specific to this Page -->

	<link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet"/>

	<link type="text/css" href="lib/javascripts/lwrte/jquery.rte.css" rel="stylesheet"/>

	<link type="text/css" href="lib/javascripts/valums-file-uploader/client/fileuploader.css" rel="stylesheet"/>
</head>
<body>
    <div id="pdf-viewer">
        <iframe src = ""  id="frme" allowfullscreen="true"></iframe>
    </div>

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

			<input type="button" class="casenotes_search_clear">

			<?php if  ($_SESSION['permissions']['post_in_board'] == '1') { ?>

			<button>New Post</button>

			<?php } ?>

		</div>

		<div id ="board_panel">

			<?php if ($_SESSION['permissions']['view_board'] == '0') { ?>

			<p>Sorry, you do not have permission to view the Board.</p>

			<?php } else { ?>

			Loading...


			<?php } ?>

		</div>

	</div>





