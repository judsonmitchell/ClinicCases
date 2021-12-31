<?php

function tabs($dbh, $current)

{
	//Format the current tab by removing the php extension
	$current_tab = substr($current, 0, -4);

	//Determine which tabs the user sees depending on their group membership
	$get_tab_config = $dbh->prepare('SELECT group_name,allowed_tabs FROM cm_groups WHERE group_name = ? ');

	$get_tab_config->bindParam(1, $_SESSION['group']);

	$get_tab_config->execute();

	$r = $get_tab_config->fetch();

	$group_tabs = unserialize($r['allowed_tabs']);

	//output the tabs
	ob_start();
	echo "<div id='tabs'>
			<div class='header__group'>
			<h1 class='header__logo'><a href='index.php?i=Home.php'>ClinicCases</a></h1>
			<div class='header__toggle'>
			<img src='./html/images/menu_toggle.svg' alt='Toggle for mobile menu' />
			</div>
			</div>
			<ul class='collapsed'>";

	foreach ($group_tabs as $tab) {
		if ($tab == $current_tab) {
			echo "<li class='current' id = 'tab_$tab'><a href='index.php?i=" . $tab . ".php'><span>$tab</span></a></li>";
		} else {
			echo "<li><a id = 'tab_$tab' href='index.php?i=" . $tab . ".php'><span>$tab</span></a></li>";
		}
	}

	echo "<li class='header__prof-pic'><a> <img src='" . $_SESSION['picture_url'] . "' alt='" .  $_SESSION['first-name'] . ' ' . $_SESSION['last-name'] . "Profile Picture' />" . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . "</a></li></ul></div>";



	$tabs_html = ob_get_clean();

	return $tabs_html;
}
