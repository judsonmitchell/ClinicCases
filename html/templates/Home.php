<!-- Jquery Calls Specific to this page -->
<script src="lib/axios/axios.bundle.min.js"></script>
<script src="html/js/Home.js" type="module"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>

<!-- Css specific to this page -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css">
<link rel="stylesheet" type="text/css" href="lib/javascripts/fullcalendar/fullcalendar.css" />
<link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="html/css/home.min.css" />
</head>

<body>

	<div id="pdf-viewer">
		<iframe src="" id="frme" allowfullscreen="true"></iframe>
	</div>

	<div id="notifications"></div>

	<?php include 'html/templates/interior/idletimeout.php' ?>

	<div class="header">
		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>

	</div>
	<div class="welcome-message">
		<div>

			<h1>Welcome, <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] ?>!</h1>
			<?php
			include 'lib/php/auth/last_login.php';

			include 'lib/php/utilities/convert_times.php';

			$last_log = extract_date_time(get_last_login($dbh, $_SESSION['login']));

			?>

			<div class="last_login"> Last login: <?php echo $last_log; ?> </div>
		</div>

		<div class="quick_add_button">

			<button data-bs-toggle="modal" data-bs-target="#quickAddModal" class="button--primary">+ Quick Add</button>
		</div>
	</div>


	<div id="grid">
		<div id="sidebar">

			<div id="activities"></div>
		</div>
		<div id="content">

			<div id="calendar"></div>
		</div>
	</div>

	<?php include('html/templates/interior/home_view_event.php'); ?>
	<?php include('html/templates/interior/home_quick_add.php'); ?>