<?php
	//Existence of session permissions indicates that the user is validly logged in to a ClinicCases session on the server	
		
	if (!isset($_SESSION['cc_session_id']))

		{echo "You don't have permission to view this page.  Please <a href=\"index.php\">log in.</a>";die;}
		

