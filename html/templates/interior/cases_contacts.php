<div class="user_display ui-widget ui-widget-content ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left"></div>

	<div class="case_detail_panel_tools_right">

		<input type="text" class="contacts_search" value="Search Contacts">

<?php
	if ($_SESSION['permissions']['add_contacts'] == '1')
		{echo "<button class='new_contact'>New Contact</button>";}

?>

		<button class = "contact_print">Print</button>

	</div>

</div>

<div class="case_detail_panel_casenotes">

	<div class='csenote csenote_new new_contact contact'>
			<form>

			<div class='csenote_bar'>

				<div class = 'csenote_bar_left new_contact_left'>

					<h4><span class="first_name_live">New</span> <span class="last_name_live">Contact</span><h4>

					<h5><span class="contact_type_live"></span></h5>

				</div>

				<div class = 'csenote_bar_right new_contact_right'>

					<button class='contact_action_submit'>Add</button><button class='contact_action_cancel'>Cancel</button>

				</div>

			</div>

			<div class="new_contact_data">

				<p><label>First Name</label><input type="text" name="first_name" id="contact_first_name"></p>

				<p><label>Last Name</label><input type="text" name="last_name" id="contact_last_name"><p>

				<p><label>Organization</label><input type="text" name = "organization"><p>

				<p><label>Contact Type</label><select name="contact_type" id="contact_type">

						<option value=''></option>

						<?php $type_list = contact_types($dbh); echo $type_list; ?>

					</select></p>


				<p><label>Address</label><textarea name="address"></textarea><p>

				<p><label>City</label><input type="text" name="city"></p>

				<p><label>State</label><?php $select = state_selector('state','state_select'); echo $select; ?></p>

				<p><label>Zip</label><input type="text" name="zip"></p>

				<span class="contact_phone_widget"></span>

				<span class="contact_email_widget"></span>

				<p><label>Notes</label><textarea name="notes"></textarea></p>

			</div>

			<div class="contact_right">

			</div>

			</form>
	</div>

<?php

	foreach($contacts as $contact)
			{

				echo "
				<div class='csenote contact'>
					<div class='csenote_bar contact_bar'>
						<div class = 'csenote_bar_left'><h4>". $contact['first_name'] . " " . $contact['last_name'] . "</h4><h5>" . $contact['type']  . "</h5></div>
						<div class = 'csenote_bar_right'>";

						if ($_SESSION['permissions']['edit_contacts'] == '1')
							{echo "<a href='#' class='csenote_edit'>Edit</a> ";}

						if ($_SESSION['permissions']['delete_contacts'] == '1')
							{echo "<a href='#' class='csenote_delete'>Delete</a>";}

						echo "
						</div>
					</div>

					<div class='contact_left'>
						<p><label>Organization:</label> $contact[organization]</p>
						<p><label>Address:</label> $contact[address]<br>$contact[city] $contact[state] $contact[zip]</p>
						<p><label>Phone 1</label> $contact[phone1]</p>
						<p><label>Phone 2</label> $contact[phone2]</p>
						<p><label>Fax</label> $contact[fax]</p>
						<p><label>Email</label> $contact[email]</p>

					</div>


					<div class='contact_right'>
						<p><label>Notes:</label> $contact[notes]</p>

					</div>

				</div>";

			}

			if (empty($contacts))
				{echo "<p>No contacts found.</p>";}
?>

</div>