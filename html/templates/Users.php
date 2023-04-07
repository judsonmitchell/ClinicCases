<link rel="stylesheet" type="text/css" href="html/css/users.min.css" />
<script src="lib/axios/axios.bundle.min.js"></script>
<script src="html/js/idletimerStart.js" type="module"></script>
<script src="lib/javascripts/timer.js" type="module"></script>
<script src="html/js/Users.js" type="module"></script>
<script type="text/javascript" src="html/js/Tables.js "></script>
<script src="lib/html2pdf/html2pdf.bundle.min.js"></script>

</head>

<body>

	<div id="notifications"></div>



	<div class="header">
		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>
	</div>


	<div class="container my-4">
		<h1 class="fw-bold"> Users</h1>
		<div id="table_users"></div>
	</div>
	<!-- <div id="content">


		<?php if ($_SESSION['permissions']['view_users']  !== 1) {
			die("Sorry, you do not have permission to view users.");
		} ?>


	</div> -->