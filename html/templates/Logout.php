<?php
	session_start();
	include '../../db.php';
	include '../../lib/php/auth/log_write.php';
	
	write_log($_SESSION['login'],$_SERVER['REMOTE_ADDR'],$_SESSION['cc_session_id'],'out');
	session_unset();
	session_destroy();
	
?>
<html>
	<head>
		<link rel="stylesheet" href="../css/cm.css" type="text/css">
		
<?php
	if (isset($_GET['user']))
		//if the logout has been initiated by the user
		{echo "<meta http-equiv=\"refresh\" content=\"5;URL=" . $CC_base_url .  "index.php\"";}
			
			else
			
			//logout is the result of inactivity
			{echo "<meta http-equiv=\"refresh\" content=\"5;URL=" . $CC_base_url .  "index.php?force_close=1\"";}
			
?>
		
		
	</head>

	<body>
		<div id="content" style="margin-top:30px;">
		<h4>You have been logged out</h4>


</body>
