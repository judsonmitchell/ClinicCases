<?php
session_start();

	function tabs($current)
	
		{
			
			//Format the current tab by removing the php extension
			$current_tab = substr($current,0,-4);
			
			//Determine which tabs the user sees depending on their group membership
			switch($_SESSION['group']){
				
				case 'admin':
					$group_tabs = array('Home','Cases','Students','Users','Board','Utilities','Prefs');
					break;
				
				case 'super':
					$group_tabs = array('Home','Cases','Students','Journals','Users','Board','Utilities','Prefs');
					break;
					
				case 'prof':
					$group_tabs = array('Home','Cases','Students','Journals','Board','Utilities','Prefs');
					break;
					
				case 'student': 
					$group_tabs = array('Home','Cases','Journals','Board','Utilities','Prefs');
					break;
				
			}
			
			//output the tabs
			ob_start();
			echo "<div id='tabs'><ul>";
			
			foreach ($group_tabs as $tab)
			
					{
						if ($tab == $current_tab)
						{
							echo "<li id='current'><a href='index.php?i=?" . $tab . ".php'><span>$tab</span></a></li>";
						}
						
						else
						
						{
							echo "<li><a href='index.php?i=" . $tab . ".php'><span>$tab</span></a></li>";
						}
						
					}
			
			echo "</ul></div>";
			$tabs_html = ob_get_clean();
			
			return $tabs_html;
			
			
		}
