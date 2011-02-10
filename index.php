<?php
include 'db.php';
include 'lib/php/load.php';
include 'html/templates/Header.php';
include 'lib/php/tabs.php';


//Load the necessary tabs for the page
	

//Check to see which template is needed

	if (isset($_GET['i']))
		{
			$pg = load($_GET['i']);		
				
		}
		
		else
		
		{
			$pg = load('Login.php');	
		}



//Include the template

	if ($pg === false)
		{echo "Invalid File Request";}
		else
		{include($pg);}

include 'html/templates/Footer.php';


