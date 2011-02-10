<?php

	function load($page)
	
		{			
			//white list of allowed include files
			$allowed_includes = array('Login.php','Home.php','Cases.php');
			//include file requested in URL
			$requested_include = $page;
			
			if (in_array($requested_include,$allowed_includes,true))
				
				{	
					$include = "html/templates/" . $page;
					return $include;
				}
				
				else
				
				{return false;}

				
		}
	
	
