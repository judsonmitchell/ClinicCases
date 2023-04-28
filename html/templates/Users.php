<link rel="stylesheet" type="text/css" href="html/css/users.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>

<script src="lib/axios/axios.bundle.min.js"></script>
<script src="html/js/idletimerStart.js" type="module"></script>
<script src="lib/javascripts/timer.js" type="module"></script>
<script src="html/js/Users.js" type="module"></script>
<script type="text/javascript" src="html/js/Tables.js "></script>
<script src="lib/html2pdf/html2pdf.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css">

</head>


<body>

	<div id="notifications"></div>


	<div class="header">
		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>
	</div>


	<div class="container my-4">
		<h1 class="fw-bold"> Users</h1>
		<div id="table_users" class="<?php if ($_SESSION['permissions']['add_users'] == 1) {
																		echo "can_add";
																	} ?>"></div>
	</div>
	<!-- <div id="content">


		<?php if ($_SESSION['permissions']['view_users']  !== 1) {
			die("Sorry, you do not have permission to view users.");
		} ?>

<?php include('html/templates/interior/users_new_user_modal.php'); ?>
<?php
$user_id = $_GET['user_id'];
if ($user_id) {
	include('lib/php/users/user_detail_load.php');
}
?>
	</div> -->