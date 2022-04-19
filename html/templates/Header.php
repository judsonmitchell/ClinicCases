<!DOCTYPE html>

<!--[if lt IE 7]> <html class="ie6"> <![endif]-->
<!--[if IE 7]>    <html class="ie7"> <![endif]-->

<head>
	<title>ClinicCases - <?php echo CC_PROGRAM_NAME; ?></title>
	<meta charset="utf-8">
	<meta name="robots" content="noindex">
	<meta content="width=device-width, initial-scale=1" name="viewport" />


	<!-- Judson -->
	<link rel="stylesheet" href="lib/bootstrap-5.1.3-dist/css/bootstrap.min.css" type="text/css" />
	<!-- <link rel="stylesheet" href="lib/default.min.css" type="text/css" media="print" /> -->
	<link rel="stylesheet" href="html/css/cm.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="html/css/print.css" type="text/css" media="print" />
	<!-- <link rel="stylesheet" href="lib/alertify.min.css" type="text/css" media="print" /> -->

	<!-- <link rel="stylesheet" href="lib/jqueryui/css/custom-theme/jquery-ui-1.8.9.custom.css" type="text/css" />
	<link rel="stylesheet" href="lib/jqueryui/css/custom-theme/jquery-ui-1.8.9.custom.css" type="text/css" /> -->
	<link type="text/css" href="html/css/fff.icon.core.css" rel="stylesheet" />
	<link type="text/css" href="html/css/fff.icon.icons.css" rel="stylesheet" />
	<link rel="shortcut icon" type="image/x-icon" href="html/images/favicon.ico" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<!-- Nina -->
	<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://use.typekit.net/pyd8ztf.css">
	<link rel="stylesheet" href="html/css/app.min.css" type="text/css" media="screen" />

	<!-- Nina -->
	<script src="html/js/tabs.js" type="text/javascript"></script>
	<script src="lib/jquery/jquery-3.6.0.min.js" type="text/javascript"></script>
	<script src="lib/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
	<!-- <script src="lib/alertify.min.js"></script> -->
	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />

	<!-- Bootstrap theme -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css" />

	<!-- Judson -->

	<!-- <script src="lib/jqueryui/js/jquery-1.4.4.min.js" type="text/javascript"></script> -->
	<!-- <script src="lib/jqueryui/js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script> -->
	<script src="lib/javascripts/jquery.livequery.min.js" type="text/javascript"></script>
	<script src="lib/javascripts/jquery.cookie.js" type="text/javascript"></script>
	<script src="html/js/sizeContentWindow.js" type="text/javascript"></script>
	<script src="html/js/notifyUser.js" type="text/javascript"></script>
	<!-- <script src="lib/javascripts/timer.js" type="text/javascript"></script> -->
	<script src="lib/javascripts/validations.js" type="text/javascript"></script>
	<script src="lib/javascripts/globalFunctions.js" type="text/javascript"></script>
	<script src="lib/javascripts/print.js" type="text/javascript"></script>


	<?php if (!empty($_GET) && !isset($_GET['force_close']) && !strstr(@$_GET['i'], 'Logout.php')) { //if we are not on index page
	?>
		<!-- <script src="html/js/idletimerStart.js" type="text/javascript"></script>
		<script src="lib/javascripts/jquery.idletimer.js" type="text/javascript"></script>
		<script src="lib/javascripts/jquery.idletimeout.js" type="text/javascript"></script> -->
		<script src="lib/javascripts/messageChecker.js" type="text/javascript"></script>
	<?php } ?>