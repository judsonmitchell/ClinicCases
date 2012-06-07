<?php

	function tabs($dbh,$current)

		{

			//Format the current tab by removing the php extension
			$current_tab = substr($current,0,-4);

			//Determine which tabs the user sees depending on their group membership
			$get_tab_config = $dbh->prepare('SELECT group_name,allowed_tabs FROM cm_groups WHERE group_name = ? ');

			$get_tab_config->bindParam(1, $_SESSION['group']);

			$get_tab_config->execute();

			$r = $get_tab_config->fetch();

			$group_tabs = unserialize($r['allowed_tabs']);

			//output the tabs
			ob_start();
			echo "<div id='tabs'><ul>";

			foreach ($group_tabs as $tab)

					{
						if ($tab == $current_tab)
						{
							echo "<li class='current' id = 'tab_$tab'><a href='index.php?i=" . $tab . ".php'><span>$tab</span></a></li>";
						}

						else

						{
							echo "<li><a id = 'tab_$tab' href='index.php?i=" . $tab . ".php'><span>$tab</span></a></li>";
						}

					}

			echo "</ul></div>";
			$tabs_html = ob_get_clean();

			return $tabs_html;


		}
