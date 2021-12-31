<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left"></div>

	<div class="case_detail_panel_tools_right">

		<input type="text" class="contacts_search" value="Search Contacts">

		<input type="button" class="contacts_search_clear">

		<?php
			if ($_SESSION['permissions']['add_contacts'] == '1') {
                echo "<button class='new_contact'>New Contact</button>";
            }
		?>

		<button class = "contact_print">Print</button>

	</div>

</div>

<div class="case_detail_panel_casenotes">

	<div class='csenote csenote_new new_contact contact'>
			<form>

			<div class='csenote_bar'>

				<div class = 'csenote_bar_left new_contact_left'>

					<h4><span class="first_name_live">New Contact</span> <span class="last_name_live"></span></h4>

					<h5><span class="contact_type_live"></span></h5>

				</div>

				<div class = 'csenote_bar_right new_contact_right'>

					<button class='contact_action_submit'>Add</button><button class='contact_action_cancel'>Cancel</button>

				</div>

			</div>

			<div class="new_contact_data">

				<p><label>First Name</label><input type="text" name="first_name" id="contact_first_name"></p>

				<p><label>Last Name</label><input type="text" name="last_name" id="contact_last_name"></p>

				<p><label>Organization</label><input type="text" name = "organization" id="contact_organization"></p>

				<p><label>Contact Type</label><select name="contact_type" id="contact_type">
						<option value=''></option>

						<?php $type_list = gen_contact_types($dbh,$case_id); echo $type_list; ?>

					</select></p>


				<p><label>Address</label><textarea name="address"></textarea></p>

				<p><label>City</label><input type="text" name="city"></p>

				<p><label>State</label><?php $select = state_selector('state','state_select'); echo $select; ?></p>

				<p><label>Zip</label><input type="text" name="zip"></p>

				<span class="contact_phone_widget"></span>

				<span class="contact_email_widget"></span>

				<p><label>Website</label><input type="text" name="url"></p>

				<p><label>Notes</label><textarea name="notes" class="contact_notes"></textarea></p>

			</div>

			</form>
	</div>

<?php

	foreach($contacts as $contact) {
            echo "
            <div class='csenote contact' data-id = '$contact[id]'>
                <div class='csenote_bar contact_bar'>
                    <div class = 'csenote_bar_left'>";

                    if (empty($contact['first_name']) and empty($contact['last_name'])) {
                        echo "<h4>" . htmlspecialchars($contact['organization'], ENT_QUOTES,'UTF-8') . "</h4>";
                    } else {
                        echo "<h4><span class='cnt_first_name'>". htmlspecialchars($contact['first_name'], ENT_QUOTES,'UTF-8') .
                        "</span> <span class='cnt_last_name'>" . htmlspecialchars($contact['last_name'], ENT_QUOTES,'UTF-8') . "</span></h4>";
                    }

                    echo "<h5><span class='cnt_type'>" . htmlspecialchars($contact['type'], ENT_QUOTES,'UTF-8')  . "</span></h5></div>
                    <div class = 'csenote_bar_right'>";

                    if ($_SESSION['permissions']['edit_contacts'] == '1') {
                        echo "<a href='#' class='contact_edit'>Edit</a> ";
                    }

                    if ($_SESSION['permissions']['delete_contacts'] == '1') {
                        echo "<a href='#' class='contact_delete'>Delete</a>";
                    }

                    echo "
                    </div>
                </div>

                <div class='contact_left'>";

                    if ($contact['organization']) {
                        echo "<p><label>Organization: </label><span class='cnt_organization'>" . 
                        htmlspecialchars($contact['organization'], ENT_QUOTES,'UTF-8') . "</span></p>";
                    }

                    if ($contact['phone']) {
                        $phones = json_decode($contact['phone'],true);

                        foreach ($phones as $key => $value) {
                            if(!empty($value)){
                                echo "<p class='contact_phone_group'><label>Phone (<span class='contact_phone_type'>$key</span>)</label>
                                <span  class='contact_phone_value'>" . htmlspecialchars($value ,ENT_QUOTES,'UTF-8')." </span></p>";
                            }
                        }
                    }

                    if ($contact['email']) {
                        $emails = json_decode($contact['email'],true);

                        foreach ($emails as $key => $value) {
                            if(!empty($value)){
                                echo "<p class='contact_email_group'><label>Email (<span class='contact_email_type'>$key</span>)</label>" .
                                "<a href='mailto:" . htmlspecialchars($value ,ENT_QUOTES,'UTF-8'). "' target='_blank'>
                                <span class='contact_email_value'>" . htmlspecialchars($value ,ENT_QUOTES,'UTF-8'). "</span></a></p>";
                            }
                        }
                    }

                    if (empty($contact['organization']) and empty($contact['phone']) and empty($contact['email'])) {
                        echo "<p><label>....</label></p>";
                    }

                echo "</div>

                <div class='contact_right'>";

                if ($contact['address']) {
                    echo "<p><label>Address:</label><span class='cnt_address'>" . htmlspecialchars($contact['address'], ENT_QUOTES,'UTF-8') . "</span><br />
                    <span class='cnt_city'>" . htmlspecialchars($contact['city'], ENT_QUOTES, 'UTF-8')  . "</span> <span class='cnt_state'>" .
                    htmlspecialchars($contact['state'], ENT_QUOTES,'UTF-8') . "</span> <span class='cnt_zip'>" .
                    htmlspecialchars($contact['zip'], ENT_QUOTES, 'UTF-8')  . "</span></p>";
                }
                if ($contact['url']) {
                    echo "<p><label>Website:</label><a href='" . htmlspecialchars($contact['url'], ENT_QUOTES,'UTF-8') . 
                    "' target='_blank'><span class='cnt_url'>" . htmlspecialchars($contact['url'], ENT_QUOTES,'UTF-8') . "</span></a>";
                }
                if ($contact['notes']) {
                    echo "<p><label>Notes:</label><span class='cnt_notes'>" . nl2br(htmlspecialchars($contact['notes'], ENT_QUOTES,'UTF-8')) . "</span></p>";
                }

                echo "</div>

            </div>";

        }

        if (empty($contacts)) {
            if (isset($q)) {
                echo "<p>No contacts found matching <i>$q</i></p>";
            } else {
                echo "<p>No contacts in this case</p>";
            }
        }
?>

</div>
