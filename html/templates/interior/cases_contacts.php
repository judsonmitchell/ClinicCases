<div class="user_display ui-widget ui-widget-content ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left"></div>

	<div class="case_detail_panel_tools_right">

		<input type="text" class="contacts_search" value="Search Contacts">

		<button class='new_contact'>New Contact</button>

	</div>

</div>

<div class="case_detail_panel_casenotes">

<?php

	foreach($contacts as $contact)
			{

				echo "
				<div class='csenote'>
					<div class='csenote_bar'>
						<div class = 'csenote_bar_left'>". $contact['first_name'] . " " . $contact['last_name'] . "</div>
						<div class = 'csenote_bar_right'></div>
					</div>
				</div>";

			}

			if (empty($contacts))
				{echo "<p>No contacts found.</p>";}
?>

</div>