<?php
	session_start();
	include '../../db.php';
	unset($_SESSION['login']);
	unset($_SESSION['class']);
	session_destroy();
?>
<html>
	<head>
		<link rel="stylesheet" href="../css/cm.css" type="text/css">
		<meta http-equiv="refresh" content="5;URL=<?php echo $CC_base_url; ?>index.php">
	</head>

	<body>
		<div id="content" style="margin-top:30px;">
		<h4>You have been logged out</h4>


</body>
