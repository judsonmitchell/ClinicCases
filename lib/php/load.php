<?php

	function load($page)

		{
			//white list of allowed include files
			$allowed_includes = array('Login.php','Home.php','Cases.php','Case.php','Group.php','Journals.php','Board.php','Users.php','Utilities.php','Prefs.php', 'Messages.php', 'New_Pass.php','QuickAdd.php', 'Logout.php');

			//include file requested in URL
			$requested_include = $page;

			if (in_array($requested_include,$allowed_includes,true))

				{
                    if ($_SESSION['mobile']){
                        $include = "html/templates/mobile/" . $page;
                    } else {
                        $include = "html/templates/" . $page;
                    }
					return $include;
				}

				else

				{return false;}


		}


