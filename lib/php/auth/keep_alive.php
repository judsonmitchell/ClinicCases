<?php 
session_start();

	if (!isset($_SESSION['cc_session_id']))
		//this header will trigger an ajax error and warn the user
		{header("HTTP/1.0 401 Not Authorized");}
		
		else
		
		{echo "OK";}
			
			
?>
