<?php
session_start();

	function tabs($current)
	
		{
			
			//each supported tab corresponds with the name of a template file.
			//$supported_tabs = array('Home','Cases','Students','Journals','Users','Board','Utilities','Prefs','Custom');
			
			switch($_SESSION['group']){
				
				case 'admin':
					$group_tabs = array('Home','Cases','Students','Users','Board','Utilites','Prefs');
					break;
				
				case 'super':
					$group_tabs = array('Home','Cases','Students','Journals','Users','Board','Utilites','Prefs');
					break;
					
				case 'prof':
					$group_tabs = array('Home','Cases','Students','Journals','Board','Utilites','Prefs');
					break;
					
				case 'student': 
					$group_tabs = array('Home','Cases','Journals','Board','Utilites','Prefs');
					break;
				
			}
			
			ob_start();
			echo "<div id='tabs'><ul>";
			
			foreach ($group_tabs as $tab)
			
					{
						echo "<li><a href='index.php?" . $tab . ".php'><span>$tab</span></a></li>";
						
					}
			
			echo "</ul></div>";
			$tabs_html = ob_get_clean();
			
			return $tabs_html;
			
			
		}
